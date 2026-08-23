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


class PreviewIndiaPostExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $franchiseId;
    protected $barcodeOption;
    
    public $timeout = 1200;

    public function __construct($filePath, $franchiseId, $barcodeOption = 'barcode_auto')
    {
        $this->filePath = $filePath;
        $this->franchiseId = $franchiseId;
        $this->barcodeOption = $barcodeOption;
    }

    public function handle()
    {
        $spreadsheet = IOFactory::load($this->filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $header = null;
        $successRows = [];
        $failedRows = [];
        $totalAmounts = 0;

        // ✅ Get franchise for balance check
        $franchise = Franchise::find($this->franchiseId);
        if (!$franchise) {
            throw new \Exception("Franchise not found");
        }

        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            $rowData = [];
            $hasData = false;

            foreach ($row->getCellIterator() as $cell) {
                $val = trim((string)$cell->getValue());
                $rowData[] = $val;
                if ($val !== '') $hasData = true;
            }

            if (!$hasData) continue;

            if (!$header) {
                $header = array_map('strtolower', $rowData);
                continue;
            }

            $rowAssoc = array_combine($header, array_pad($rowData, count($header), null));
            $rowNumber = $rowIndex; // Excel row number

            try {
                // ✅ LOG FOR DEBUGGING
                Log::info("Preview Processing Row {$rowNumber}:", $rowAssoc);

                // ✅ STEP 1: Weight Calculation (EXACTLY LIKE SINGLE UPLOAD)
                $actualWeight = floatval($rowAssoc["package weight"] ?? 0);
                $length = floatval($rowAssoc["package length"] ?? 0);
                $width = floatval($rowAssoc["package width"] ?? 0);
                $height = floatval($rowAssoc["package height"] ?? 0);
                
                Log::info("Weight Inputs - Row {$rowNumber}: Actual: {$actualWeight}g, L: {$length}, W: {$width}, H: {$height}");

                // ✅ यही formula जो single upload में use होता है
                $totalWeight = $actualWeight;
                if ($length && $width && $height) {
                    // Volumetric weight in grams (SINGLE UPLOAD FORMULA)
                    $volumetric = (($length * $width * $height) / 6000) * 1000;
                    $totalWeight += $volumetric;
                    Log::info("Row {$rowNumber}: Volumetric Added: {$volumetric}g, Total Weight: {$totalWeight}g");
                }

                // ✅ Round the weight (जैसे single में होता है)
                $computedWeight = round($totalWeight);
                Log::info("Row {$rowNumber}: Computed Weight (Rounded): {$computedWeight}g");

                // ✅ STEP 2: Get Charges (SINGLE UPLOAD की तरह)
                $from = $rowAssoc["pickup pincode"] ?? null;
                $to = $rowAssoc["consignee pincode"] ?? null;
                
                // ✅ EXACTLY LIKE SINGLE UPLOAD - सभी charges
                $fuel_charge = isset($rowAssoc["fuel charge"]) && $rowAssoc["fuel charge"] !== '' 
                    ? floatval($rowAssoc["fuel charge"]) 
                    : 0;
                
                $pickup_charge = isset($rowAssoc["pickup charge"]) && $rowAssoc["pickup charge"] !== ''
                    ? floatval($rowAssoc["pickup charge"])
                    : 0;
                
                $other_service_charge = isset($rowAssoc["other service charge"]) && $rowAssoc["other service charge"] !== ''
                    ? floatval($rowAssoc["other service charge"])
                    : 0;
                
                $register_amount = isset($rowAssoc["register fee"]) && $rowAssoc["register fee"] !== ''
                    ? floatval($rowAssoc["register fee"])
                    : 0;
                
                Log::info("Row {$rowNumber} Charges - Fuel: {$fuel_charge}, Pickup: {$pickup_charge}, Other: {$other_service_charge}, Register: {$register_amount}");

                // ✅ Validate required fields
                if (!$from || !$to) {
                    throw new \Exception("Pickup or Consignee pincode missing");
                }

                if ($actualWeight <= 0) {
                    throw new \Exception("Invalid package weight");
                }

                // ✅ STEP 3: Price Calculation (SINGLE UPLOAD की तरह)
                $controller = new IndiaPostSpeedPostController();
                
                // ✅ FIX: सभी charges पास करें (जैसे single में होता है)
                $rateDetails = $controller->getPrice(
                    $from, 
                    $to, 
                    $computedWeight, 
                    $fuel_charge, 
                    $pickup_charge, 
                    $other_service_charge, 
                    $register_amount
                );

                Log::info("Row {$rowNumber} Rate Calculation Result:", $rateDetails);

                if (($rateDetails['status'] ?? 'fail') === 'fail') {
                    throw new \Exception($rateDetails['message'] ?? 'Rate calculation failed');
                }

                // ✅ Balance check (SINGLE UPLOAD की तरह)
                $gotogo_balance = $franchise->indiapost_balance;
                $credit_balance = $franchise->india_credit_amount;
                $indiapost_balance = $gotogo_balance + $credit_balance;

                if ($indiapost_balance < $rateDetails['total']) {
                    throw new \Exception('Low balance: Available ' . $indiapost_balance . ', Required ' . $rateDetails['total']);
                }

                // ✅ Add calculated data to row
                $rowAssoc['calculated_weight'] = $computedWeight;
                $rowAssoc['calculated_amount'] = $rateDetails['total'];
                $rowAssoc['rate_details'] = $rateDetails;
                $rowAssoc['row_number'] = $rowNumber;
                
                $totalAmounts += $rateDetails['total'];
                $successRows[] = $rowAssoc;

                Log::info("Row {$rowNumber} Success - Amount: " . $rateDetails['total']);

            } catch (\Throwable $e) {
                Log::warning("Row {$rowNumber} Failed", [
                    'error' => $e->getMessage(),
                    'row' => $rowAssoc
                ]);
                
                $rowAssoc['Error Reason'] = $e->getMessage();
                $rowAssoc['row_number'] = $rowNumber;
                $failedRows[] = $rowAssoc;
            }
        }

        // ✅ Store results in cache
        Cache::put("preview_excel_{$this->franchiseId}", [
            'summary' => [
                'total_rows' => count($successRows) + count($failedRows),
                'success' => count($successRows),
                'failed' => count($failedRows),
                'total_amount' => round($totalAmounts, 2),
                'success_amount' => $totalAmounts,
            ],
            'success_rows' => $successRows,
            'failed_rows' => $failedRows,
            'franchise_balance' => [
                'indiapost_balance' => $franchise->indiapost_balance,
                'credit_balance' => $franchise->india_credit_amount,
                'total_balance' => $franchise->indiapost_balance + $franchise->india_credit_amount
            ]
        ], 3600);

        Log::info("Preview Job Completed", [
            'success_count' => count($successRows),
            'failed_count' => count($failedRows),
            'total_amount' => $totalAmounts,
            'franchise_id' => $this->franchiseId
        ]);
    }
}