<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

use App\Models\IndiaPostBarcode;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\Franchise;
use App\Models\MManager;
use App\Models\PickupDetails;
use App\Models\NoRegisterCustomer;
use App\Models\IndiaPostSpeedPostTrackOrder;
use App\Models\FranchiseCommissionDetail;
use App\Models\ManagerCommissionDetail;
use App\Models\FranchiseBarcodeSeries;
use App\Models\FranchiseBarcodes;
use App\Http\Controllers\franchise\RateCalculator;
use App\Http\Controllers\franchise\IndiaPostSpeedPostController;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ProcessIndiaPostExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;

    protected string $filePath;
    protected int $franchiseId;
    protected string $barcodeOption;

    public function __construct(string $filePath, string $barcodeOption, int $franchiseId)
    {
        $this->filePath      = $filePath;
        $this->barcodeOption = $barcodeOption;
        $this->franchiseId   = $franchiseId;
    }

    public function handle()
    {
        $spreadsheet = IOFactory::load($this->filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $header = null;
        $successCount = 0;
        $failCount = 0;
        $failedRows = [];
        $totalAmounts = 0;

        $franchise = Franchise::findOrFail($this->franchiseId);

        $rateCalculater = new RateCalculator(); // Fixed: define RateCalculator

        foreach ($sheet->getRowIterator() as $row) {

            $rowData = [];
            $hasData = false;

            foreach ($row->getCellIterator() as $cell) {
                $val = trim((string) $cell->getValue());
                $rowData[] = $val;
                if ($val !== '') $hasData = true;
            }

            if (!$hasData) continue;

            if (!$header) {
                $header = array_map('strtolower', $rowData);
                continue;
            }

            $rowAssoc = array_combine($header, array_pad($rowData, count($header), null));

            try {
                /* ---------------- WEIGHT ---------------- */
                $actualWeight = floatval($rowAssoc["package weight"] ?? 0);
                $length = floatval($rowAssoc["package length"] ?? 0);
                $width  = floatval($rowAssoc["package width"] ?? 0);
                $height = floatval($rowAssoc["package height"] ?? 0);

                $totalWeight = $actualWeight;
                if ($length && $width && $height) {
                    $totalWeight += (($length * $width * $height) / 6000) * 1000;
                }

                if ($actualWeight <= 0) {
                    throw new \Exception("Invalid weight");
                }

                $from = $rowAssoc['pickup pincode'] ?? null;
                $to   = $rowAssoc['consignee pincode'] ?? null;

                if (!$from || !$to) {
                    throw new \Exception("Pincode missing");
                }

                $fuel_charge = $rowAssoc["fuel charge"] ?? 0;
                $pickup_charge = $rowAssoc["pickup charge"] ?? 0;
                $other_service_charge = $rowAssoc["other service charge"] ?? 0;
                $register_amount = $rowAssoc["register fee"] ?? 0;

                $controller = new IndiaPostSpeedPostController();

                $rateDetails = $controller->getPrice(
                    $from,
                    $to,
                    $totalWeight,
                    0,
                    0,
                    0,
                    $register_amount
                );

                if (($rateDetails['status'] ?? 'fail') === 'fail') {
                    throw new \Exception($rateDetails['message'] ?? 'Rate calculation failed');
                }

                $paymentAmount = $rateDetails['total'];
                $totalAmounts += $paymentAmount;

                /* ---------------- CUSTOMER ---------------- */
                $bookingType = 'prepaid';
                $customer = null;

                if ($bookingType == "prepaid") {
                    $customer = $this->resolveCustomer($rowAssoc, $this->franchiseId);
                    if (!$customer) {
                        $failCount++;
                        $rowAssoc["Error Reason"] = "Customer not found (Prepaid)";
                        $failedRows[] = $rowAssoc;
                        continue;
                    }
                }

                /* ---------------- MANAGER ---------------- */
                if (!empty($rowAssoc["market manager _id"])) {
                    $manager = MManager::where("mobile", $rowAssoc["market manager _id"])
                        ->where("franchise_id", $this->franchiseId)
                        ->first();
                    $managertype = "manager";
                } elseif (!empty($rowAssoc["pickup boy_id"])) {
                    $pickupBoy = PickupDetails::where("phone", $rowAssoc["pickup boy_id"])
                        ->where("franchise_id", $this->franchiseId)
                        ->first();
                    $managertype = "pickup";
                } else {
                    $managertype = "franchise";
                }

                /* ---------------- BARCODE ---------------- */
                $code = isset($rowAssoc['barcode']) && trim($rowAssoc['barcode']) !== '' ? trim($rowAssoc['barcode']) : $this->getNextBarcode($this->franchiseId);

                $generator = new BarcodeGeneratorPNG();
                $barcodeImage = base64_encode(
                    $generator->getBarcode($code, $generator::TYPE_CODE_128)
                );

                /* ---------------- PARCEL ---------------- */
                $franchise_role_user_id = Auth::guard('franchiseRoleUser')->id() ?? null;

                $parcel = IndiaPostSpeedPostParcel::create([
                    "franchise_id" => $franchise->id,
                    "franchise_role_users_id" => $franchise_role_user_id,
                    "cod_customer_id" => $customer->id ?? null,
                    "no_r_customer_id" => $customer->id ?? null,
                    "booking_type" => $managertype,

                    "pickup_name" => $rowAssoc["pickup name"] ?? null,
                    "pickup_mobile" => $rowAssoc["pickup phone"] ?? null,
                    "pickup_email" => $rowAssoc["pickup email"] ?? null,
                    "pickup_pincode" => $rowAssoc["pickup pincode"] ?? null,
                    "pickup_city" => $rowAssoc["pickup city"] ?? null,
                    "pickup_state" => $rowAssoc["pickup state"] ?? null,
                    "pickup_address" => $rowAssoc["pickup address"] ?? null,

                    "consignee_name" => $rowAssoc["consignee name"] ?? null,
                    "consignee_mobile" => $rowAssoc["consignee phone"] ?? null,
                    "consignee_email" => $rowAssoc["consignee email"] ?? null,
                    "consignee_pincode" => $rowAssoc["consignee pincode"] ?? null,
                    "consignee_city" => $rowAssoc["consignee city"] ?? null,
                    "consignee_state" => $rowAssoc["consignee state"] ?? null,
                    "consignee_address" => $rowAssoc["consignee address"] ?? null,

                    "package_weight" => $rowAssoc["package weight"] ?? 0,
                    "package_length" => $rowAssoc["package length"] ?? 0,
                    "package_width" => $rowAssoc["package width"] ?? 0,
                    "package_height" => $rowAssoc["package height"] ?? 0,

                    "payment_method" => $bookingType,
                    "cod_amount" => $rowAssoc["cod amount"] ?? 0,

                    "fuel_charge" => $fuel_charge,
                    "pickup_charge" => $pickup_charge,
                    "other_service_charge" => $other_service_charge,

                    "payment_amount" => $paymentAmount,
                    "totalOtherAmount" => $rateDetails["total"] ?? 0,

                    "barcode_no" => $code,
                    "barcode_image_src" => $barcodeImage,
                    "insert_type" => IndiaPostSpeedPostParcel::INSERT_TYPE_BULK,
                ]);

                $successCount++;

                /* ---------------- TRACKING ---------------- */
                IndiaPostSpeedPostTrackOrder::create([
                    "parcel_id" => $parcel->id,
                    "barcode_no" => $code,
                    "source_franchise_id" => $this->franchiseId,
                    "order_placed_datetime" => now(),
                    "source_franchise_location" => $franchise->address,
                ]);

                /* ---------------- COMMISSION ---------------- */
                $commission = $rateCalculater->calculateCommissionForIndiaPost($actualWeight, $paymentAmount, IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);

                if (!empty($rowAssoc["market manager _id"]) || !empty($rowAssoc["pickup boy_id"])) {
                    $marketcommission = $rateCalculater->calculateCommissionForIndiaPostMarket($bookingType, $paymentAmount);
                }

                if (!empty($rowAssoc["market manager _id"]) || !empty($rowAssoc["pickup boy_id"])) {
                    $datamanager = new ManagerCommissionDetail();
                    $datamanager->commission_id = $bookingType;
                    $datamanager->servicetype = IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED;
                    $datamanager->amount = $paymentAmount / 1.18;
                    $datamanager->commission = number_format($marketcommission ?? 0, 2, ".", "");
                    $datamanager->type = $managertype;
                    $datamanager->save();
                }

                FranchiseCommissionDetail::create([
                    "franchise_id" => $franchise->id,
                    "service_type" => IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED,
                    "amount" => $paymentAmount / 1.18,
                    "commission" => number_format($commission ?? 0, 2, ".", ""),
                    "payment_method" => $bookingType,
                ]);

                /* ---------------- BARCODE SERIES ---------------- */
                if ($this->barcodeOption === "barcode_auto") {
                    $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
                    $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";
                    $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

                    $series = FranchiseBarcodeSeries::where("franchise_id", $this->franchiseId)->first();
                    $seriesNum = $series->{$last_code_issued_column} ? $series->{$last_code_issued_column} + 1 : $series->{$range_start_column};
                    $series->{$last_code_issued_column} = $seriesNum;
                    $series->save();

                    $franchiseBarcode = new FranchiseBarcodes();
                    $franchiseBarcode->barcodes = $code;
                    $franchiseBarcode->franchise_barcodeseries_id = $series->id;
                    $franchiseBarcode->save();
                }

            } catch (\Throwable $e) {
                Log::warning('ROW FAILED', [
                    'error' => $e->getMessage(),
                    'row' => $rowAssoc
                ]);

                $rowAssoc['Error Reason'] = $e->getMessage();
                $failedRows[] = $rowAssoc;
                $failCount++;

                
            }
        }

        Cache::put("preview_excel_{$this->franchiseId}", [
            'summary' => [
                'total_rows' => $successCount + $failCount,
                'success' => $successCount,
                'failed' => $failCount,
                'total_amount' => round($totalAmounts),
                'id' => $this->franchiseId,
            ],
            'success_rows' => $successCount,
            'failed_rows' => $failedRows,
        ], 3600);

        // Job ke end me, cache delete karo
    Cache::forget("preview_excel_{$this->franchiseId}");

    }

    private function getNextBarcode($franchiseId)
    {
        $user = Franchise::findOrFail($franchiseId);

        try {
            $barcode = IndiaPostBarcode::where("availables", ">", 0)
                ->where('service_type', IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED)->first();
            if ($barcode) {
                $nextBarcode = $barcode->getNextBarcode();
                $barcode->increment("range_from");
                $barcode->decrement("availables");

                return $nextBarcode;
            } else {
                throw new \Exception("Barcode not available");
            }
        } catch (\Exception $e) {
            return "barcode_error";
        }
    }

    private function resolveCustomer($rowAssoc, $franchiseId)
    {
        if (!empty($rowAssoc["market manager _id"])) {
            $manager = MManager::where("mobile", $rowAssoc["market manager _id"])
                ->where("franchise_id", $franchiseId)
                ->first();

            if (!$manager) return null;

            return NoRegisterCustomer::where([
                ["phone", $rowAssoc["pickup phone"]],
                ["type", "pickup"],
                ["market_id", $manager->id],
                ["franchise_id", $franchiseId],
            ])->first();
        }

        if (!empty($rowAssoc["pickup boy_id"])) {
            $pickupBoy = PickupDetails::where("phone", $rowAssoc["pickup boy_id"])
                ->where("franchise_id", $franchiseId)
                ->first();

            if (!$pickupBoy) return null;

            return NoRegisterCustomer::where([
                ["phone", $rowAssoc["pickup phone"]],
                ["type", "pickup"],
                ["market_id", $pickupBoy->id],
                ["franchise_id", $franchiseId],
            ])->first();
        }

        $existing = NoRegisterCustomer::where([
            ["phone", $rowAssoc["pickup phone"]],
            ["type", "franchise"],
            ["franchise_id", $franchiseId],
        ])->first();

        if ($existing) return $existing;

        $password = substr(str_shuffle("0123456789"), 0, 10);

        return NoRegisterCustomer::create([
            "name" => $rowAssoc["pickup name"] ?? "",
            "type" => "franchise",
            "franchise_id" => $franchiseId,
            "phone" => $rowAssoc["pickup phone"] ?? "",
            "email" => $rowAssoc["pickup email"] ?? "",
            "gst_no" => $rowAssoc["pickup gst"] ?? "",
            "pincode" => $rowAssoc["pickup pincode"] ?? "",
            "city" => $rowAssoc["pickup city"] ?? "",
            "state" => $rowAssoc["pickup state"] ?? "",
            "address" => $rowAssoc["pickup address"] ?? "",
            "password" => Hash::make($password),
        ]);
    }
}
