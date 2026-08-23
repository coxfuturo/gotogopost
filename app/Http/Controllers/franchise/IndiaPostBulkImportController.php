<?php

namespace App\Http\Controllers\franchise;





use App\Http\Controllers\Controller;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\Franchise;
use App\Models\NoRegisterCustomer;
use App\Models\ECustomer;
use App\Models\MManager;
use App\Models\PickupDetails;
use App\Models\IndiaPostSpeedPostTrackOrder;
use App\Models\FranchiseCommissionDetail;
use App\Models\ManagerCommissionDetail;
use App\Models\FranchiseBarcodeSeries;
use App\Models\FranchiseBarcodes;
use App\Models\IndiaPostBarcode;
use App\Http\Controllers\franchise\RateCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\DB;

class IndiaPostBulkImportController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard("franchise")->check()) {
                $user = Auth::guard("franchise")->user();
            } elseif (Auth::guard("franchiseRoleUser")->check()) {
                $user = Auth::guard("franchiseRoleUser")->user();
            } else {
                return abort(403, "Unauthorized.");
            }
            return $next($request);
        });
    }

    /**
     * Bulk Import Page Show Karega
     */
    public function index()
    {
        $franchise_details = Franchise::where("id", Franchise::getFranchiseId())
            ->select("franchise_no","india_credit_amount","credit_balance","indiapost_balance","gst_number")
            ->first();
            
        $barcodeAvailable = $this->getBarcodeAvailableCount();
        
        return view("franchise.indiaPost-speedPost.bulk-import", [
            "franchise_details" => $franchise_details,
            "barcodeAvailable" => $barcodeAvailable,
        ]);
    }

    /**
     * Excel File Process Karega
     */
  /**
 * Excel File Process Karega
 */
/**
 * Excel File Process Karega
 */
/**
 * Excel File Process Karega
 */
public function processBulkUpload(Request $request, RateCalculator $rateCalculator)
{
    $request->validate([
        'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        'barcode_option' => 'required|in:barcode_auto,barcode_custom'
    ]);

    try {
        $file = $request->file('excel_file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        $header = array_shift($rows); // Header row nikaali
        
        // 1. Barcode Index Identify Karein
        $barcodeIndex = -1;
        foreach ($header as $key => $columnName) {
            if (strtolower(trim($columnName)) == 'barcode') {
                $barcodeIndex = $key;
                break;
            }
        }

        if ($barcodeIndex === -1) {
            return back()->with('error', 'Excel file mein "Barcode" naam ka column nahi mila.');
        }

        // --- 2. THE STRICT BARCODE VALIDATION LOOP ---
        $barcodeErrors = [];
        
        foreach ($rows as $index => $row) {
            if (empty(array_filter($row))) continue;

            $excelBarcode = isset($row[$barcodeIndex]) ? trim($row[$barcodeIndex]) : '';
            
            // Log for debugging
            \Log::info("Row " . ($index + 2) . " - Excel Barcode: '$excelBarcode' | Selected Option: " . $request->barcode_option);

            // Case 1: Auto Generate selected but Excel has barcode
            if ($request->barcode_option === 'barcode_auto' && !empty($excelBarcode)) {
                $barcodeErrors[] = "Row " . ($index + 2) . ": Barcode '$excelBarcode' present in Excel. Please select 'Custom Barcode' or remove barcodes from Excel.";
            }
            
            // Case 2: Custom Barcode selected but Excel is empty
            if ($request->barcode_option === 'barcode_custom' && empty($excelBarcode)) {
                $barcodeErrors[] = "Row " . ($index + 2) . ": Barcode missing in Excel. Please select 'Auto Generate' or provide barcode in Excel.";
            }
        }

        // If there are barcode errors, return them all
        if (!empty($barcodeErrors)) {
            $errorMessage = implode("<br>", $barcodeErrors);
            return back()->with('error', $errorMessage);
        }

        // --- 3. PROCESSING (Sirf tab chalega jab upar wali validation pass hogi) ---
        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);
        $successCount = 0;
        $failedRows = [];

        DB::beginTransaction();

        foreach ($rows as $rowIndex => $row) {
            if (empty(array_filter($row))) continue;

            try {
                $formattedRow = array_combine($header, $row);
                $currentBarcode = isset($row[$barcodeIndex]) ? trim($row[$barcodeIndex]) : '';

                $this->processSingleRow($formattedRow, $franchise, $rateCalculator, $request->barcode_option, $currentBarcode);
                $successCount++;
            } catch (\Exception $e) {
                $failedRows[] = [
                    'row' => $rowIndex + 2,
                    'error' => $e->getMessage(),
                    'data' => $row
                ];
            }
        }

        DB::commit();
        
        // If all rows failed, show error
        if ($successCount === 0 && !empty($failedRows)) {
            $errorMessages = [];
            foreach ($failedRows as $failed) {
                $errorMessages[] = "Row {$failed['row']}: {$failed['error']}";
            }
            return back()->with('error', implode("<br>", $errorMessages));
        }

        return redirect()->route('franchise.india-post-speed-post.index')
            ->with('success', "$successCount Parcels saved successfully!")
            ->with('failed_count', count($failedRows));

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Bulk Upload Error: " . $e->getMessage());
        return back()->with('error', "System Error: " . $e->getMessage());
    }
}

private function validateBarcodeLogic(array $row, string $barcodeType, int $rowNumber)
{
    $excelBarcode = trim($row['barcode'] ?? '');

    // Case 1: Auto Generate selected, but barcode exists in Excel
    if ($barcodeType === 'auto' && $excelBarcode !== '') {
        throw new \Exception(
            "Row {$rowNumber}: Barcode already present in Excel. Please select Barcode = Custom."
        );
    }

    // Case 2: Custom selected, but barcode missing in Excel
    if ($barcodeType === 'custom' && $excelBarcode === '') {
        throw new \Exception(
            "Row {$rowNumber}: Barcode missing in Excel. Please select Barcode = Auto Generate."
        );
    }
}


    /**
     * Excel File Read Karega
     */
    private function readExcelFile($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        $data = [];
        $header = null;
        
        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            $hasData = false;
            
            foreach ($row->getCellIterator() as $cell) {
                $value = $cell->getValue();
                $rowData[] = $value;
                
                if (!is_null($value) && $value !== '') {
                    $hasData = true;
                }
            }
            
            if (!$hasData) continue;
            
            if ($header === null) {
                $header = $rowData;
                // ✅ Ensure distance column in headers
                if (!in_array('distance', $header)) {
                    $header[] = 'distance';
                }
            } else {
                // Pad row data to match header length
                if (count($rowData) < count($header)) {
                    $rowData = array_pad($rowData, count($header), null);
                }
                
                $combinedRow = array_combine($header, $rowData);
                
                // ✅ Ensure distance key exists
                if (!isset($combinedRow['distance'])) {
                    $combinedRow['distance'] = 0;
                }
                
                $data[] = $combinedRow;
            }
        }
        
        return $data;
    }

    /**
     * Row Data Validate aur Complete Karega
     */
    private function validateRowData($row)
    {
        // Required fields check
        $requiredFields = [
            'Pickup Phone', 'Pickup Pincode', 'Consignee Pincode', 'Package Weight'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($row[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
        
        // ✅ Ensure distance field exists
        if (!isset($row['distance']) || $row['distance'] === null) {
            $row['distance'] = 0;
        }
        
        // Set default values for optional fields
        $defaultValues = [
            'Fuel Charge' => 0,
            'Pickup Charge' => 0,
            'Other service Charge' => 0,
            'Register Fee' => 0,
            'Amount' => 0,
            'Cod Amount' => 0,
            'Pickup Email' => null,
            'Consignee Email' => null,
            'Market Manager _id' => null,
            'Pickup Boy_id' => null,
            'Barcode' => null
        ];
        
        foreach ($defaultValues as $key => $value) {
            if (!isset($row[$key])) {
                $row[$key] = $value;
            }
        }
        
        return $row;
    }

/**
 * Single Row Process Karega
 */
private function processSingleRow($row, $franchise, $rateCalculator, $barcodeOption, $excelBarcode)
{
    // Basic validation
    $requiredFields = ['Pickup Phone', 'Pickup Pincode', 'Consignee Pincode', 'Package Weight'];
    foreach ($requiredFields as $field) {
        if (empty($row[$field])) {
            throw new \Exception("Missing required field: {$field}");
        }
    }

    // Pricing calculation logic
    $rateDetails = $rateCalculator->calculateRate(
        $row['Pickup Pincode'], 
        $row['Consignee Pincode'], 
        $row['Package Weight'] ?? 20, 
        'speed_post', 
        $franchise->id
    );

    if (!$rateDetails || $rateDetails['status'] === 'fail') {
        throw new \Exception("Rate calculation failed: " . ($rateDetails['message'] ?? 'Check Pincodes'));
    }

    // Check Wallet
    if ($franchise->wallet_balance < $rateDetails['total']) {
        throw new \Exception("Inadequate wallet balance. Required: {$rateDetails['total']}, Available: {$franchise->wallet_balance}");
    }

    // Barcode Assignment
    $barcodeData = $this->generateBarcode($barcodeOption, $excelBarcode, $franchise->id);

    // Create parcel record
    $parcelData = [
        'franchise_id'      => $franchise->id,
        'barcode_no'        => $barcodeData['code'],
        'barcode_image'     => $barcodeData['image'],
        'consignee_name'    => $row['Consignee Name'] ?? '',
        'consignee_mobile'  => $row['Consignee Phone'] ?? '',
        'consignee_pincode' => $row['Consignee Pincode'],
        'pickup_pincode'    => $row['Pickup Pincode'],
        'pickup_mobile'     => $row['Pickup Phone'],
        'pickup_name'       => $row['Pickup Name'] ?? '',
        'pickup_address'    => $row['Pickup Address'] ?? '',
        'payment_amount'    => $rateDetails['total'],
        'payment_method'    => strtolower($row['Payment Method'] ?? 'prepaid'),
        'status'            => 'Pending',
        'insert_type'       => IndiaPostSpeedPostParcel::INSERT_TYPE_BULK,
    ];

    $parcel = IndiaPostSpeedPostParcel::create($parcelData);

    // Wallet Deduction
    $franchise->decrement('wallet_balance', $rateDetails['total']);

    // Mark Used if Auto
    if ($barcodeOption === 'barcode_auto') {
        IndiaPostBarcode::where('barcode_no', $barcodeData['code'])->update(['is_used' => 1]);
    }

    // Create track order
    IndiaPostSpeedPostTrackOrder::create([
        'parcel_id' => $parcel->id,
        'barcode_no' => $barcodeData['code'],
        'source_franchise_id' => $franchise->id,
        'order_placed_datetime' => now(),
        'source_franchise_location' => $franchise->address,
    ]);

    return $parcel;
}
	

    /**
     * Customer Resolve Karega
     */
    private function resolveCustomerForRow($row, $franchiseId)
    {
        $paymentMethod = strtolower($row['Payment Method'] ?? 'prepaid');
        
        if ($paymentMethod == 'cod') {
            // COD customer
            $customer = ECustomer::where('mobile', $row['Pickup Phone'])
                ->where('cph_link', $franchiseId)
                ->first();
                
            if (!$customer) {
                throw new \Exception("COD customer not found with phone: " . $row['Pickup Phone']);
            }
            
            return [
                'type' => 'cod',
                'id' => $customer->id,
                'customer' => $customer
            ];
        } else {
            // Prepaid customer
            return $this->resolvePrepaidCustomer($row, $franchiseId);
        }
    }

    /**
     * Prepaid Customer Resolve Karega
     */
    private function resolvePrepaidCustomer($row, $franchiseId)
    {
        $type = 'franchise';
        $marketId = null;
        
        if (!empty($row['Market Manager _id'])) {
            $manager = MManager::where('mobile', $row['Market Manager _id'])
                ->where('franchise_id', $franchiseId)
                ->first();
                
            if (!$manager) {
                throw new \Exception("Market Manager not found: " . $row['Market Manager _id']);
            }
            
            $type = 'manager';
            $marketId = $manager->id;
            
        } elseif (!empty($row['Pickup Boy_id'])) {
            $pickupBoy = PickupDetails::where('phone', $row['Pickup Boy_id'])
                ->where('franchise_id', $franchiseId)
                ->first();
                
            if (!$pickupBoy) {
                throw new \Exception("Pickup Boy not found: " . $row['Pickup Boy_id']);
            }
            
            $type = 'pickup';
            $marketId = $pickupBoy->id;
        }
        
        // Find existing customer
        $customer = NoRegisterCustomer::where('phone', $row['Pickup Phone'])
            ->where('type', $type)
            ->where('franchise_id', $franchiseId);
            
        if ($marketId) {
            $customer->where('market_id', $marketId);
        }
        
        $customer = $customer->first();
        
        // Create if not exists
        if (!$customer) {
            $password = substr(str_shuffle("0123456789"), 0, 10);
            
            $customer = NoRegisterCustomer::create([
                'name' => $row['Pickup Name'] ?? '',
                'type' => $type,
                'franchise_id' => $franchiseId,
                'market_id' => $marketId,
                'phone' => $row['Pickup Phone'],
                'email' => $row['Pickup Email'] ?? '',
                'gst_no' => $row['Pickup gst'] ?? '',
                'pincode' => $row['Pickup Pincode'],
                'city' => $row['Pickup City'] ?? '',
                'state' => $row['Pickup State'] ?? '',
                'address' => $row['Pickup Address'] ?? '',
                'password' => Hash::make($password)
            ]);
        }
        
        return [
            'type' => 'prepaid',
            'id' => $customer->id,
            'customer' => $customer
        ];
    }

    /**
     * Weight Calculate Karega
     */
    private function calculateWeight($row)
    {
        $actual = floatval($row['Package Weight'] ?? 0);
        $length = floatval($row['Package Length'] ?? 0);
        $width = floatval($row['Package Width'] ?? 0);
        $height = floatval($row['Package Height'] ?? 0);
        
        $total = $actual;
        
        // Volumetric weight calculation
        if ($length && $width && $height) {
            $volumetric = ($length * $width * $height) / 6000;
            $total = max($actual, $volumetric);
        }
        
        return [
            'actual' => $actual,
            'volumetric' => $volumetric ?? 0,
            'total' => $total
        ];
    }

    /**
     * Price Calculate Karega
     */
    private function calculatePrice($from, $to, $weight, $fuel, $pickup, $other, $register)
    {
        // Use your existing getPrice function
        $request = new Request([
            "originPincode" => $from,
            "destinationPincode" => $to,
            "packageWeight" => $weight,
            "fuel_charge" => $fuel,
            "pickup_charge" => $pickup,
            "other_service_charge" => $other,
            "register_amount" => $register,
            "service_type" => IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED,
        ]);
        
        $rateCalculator = new RateCalculator();
        return $rateCalculator->calculate($request);
    }

    /**
     * Franchise Balance Check Karega
     */
    private function checkFranchiseBalance($franchise, $amount)
    {
        $gotogoBalance = $franchise->indiapost_balance;
        $creditBalance = $franchise->india_credit_amount;
        $totalBalance = $gotogoBalance + $creditBalance;
        
        if ($totalBalance < $amount) {
            throw new \Exception("Insufficient balance. Required: {$amount}, Available: {$totalBalance}");
        }
    }

   /**
 * Barcode Generate Karega
 */
private function generateBarcode($barcodeOption, $excelBarcode, $franchiseId)
{
    $code = '';
    
    if ($barcodeOption === 'barcode_auto') {
        // Auto Generate - get next available barcode
        $barcodeModel = IndiaPostBarcode::where('franchise_id', $franchiseId)
            ->where('is_used', 0)
            ->first();
            
        if (!$barcodeModel) {
            throw new \Exception("No barcodes left in series for Auto Generate.");
        }
        
        $code = $barcodeModel->barcode_no;
        
        // Mark as used
        $barcodeModel->update(['is_used' => 1]);
        
    } else {
        // Custom Barcode - use Excel barcode
        $code = trim($excelBarcode);
        
        // Validate custom barcode
        if (empty($code)) {
            throw new \Exception("Custom barcode is empty. Please provide barcode or select Auto Generate.");
        }
        
        // Check if barcode already exists
        $existingParcel = IndiaPostSpeedPostParcel::where('barcode_no', $code)->first();
        if ($existingParcel) {
            throw new \Exception("Barcode '$code' already exists in database.");
        }
        
        // Check if barcode exists in IndiaPostBarcode table and mark as used
        $barcodeModel = IndiaPostBarcode::where('barcode_no', $code)
            ->where('franchise_id', $franchiseId)
            ->where('is_used', 0)
            ->first();
            
        if ($barcodeModel) {
            $barcodeModel->update(['is_used' => 1]);
        }
    }

    $generator = new BarcodeGeneratorPNG();
    $image = base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128));

    return ['code' => $code, 'image' => $image];
}

/**
 * Debug method to check barcode validation
 */
public function debugBarcodeValidation(Request $request)
{
    $request->validate([
        'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        'barcode_option' => 'required|in:barcode_auto,barcode_custom'
    ]);

    try {
        $file = $request->file('excel_file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        $header = array_shift($rows);
        
        // Find barcode column
        $barcodeIndex = -1;
        foreach ($header as $key => $columnName) {
            if (strtolower(trim($columnName)) == 'barcode') {
                $barcodeIndex = $key;
                break;
            }
        }

        $debugInfo = [
            'barcode_column_index' => $barcodeIndex,
            'barcode_column_name' => $barcodeIndex !== -1 ? $header[$barcodeIndex] : 'Not Found',
            'selected_option' => $request->barcode_option,
            'rows_count' => count($rows),
            'barcode_values' => []
        ];

        foreach ($rows as $index => $row) {
            if (empty(array_filter($row))) continue;
            
            $excelBarcode = isset($row[$barcodeIndex]) ? trim($row[$barcodeIndex]) : '';
            
            $debugInfo['barcode_values'][] = [
                'row' => $index + 2,
                'value' => $excelBarcode,
                'is_empty' => empty($excelBarcode),
                'length' => strlen($excelBarcode)
            ];
        }

        return response()->json([
            'status' => 'success',
            'debug_info' => $debugInfo
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
	
public function confirmExcel(Request $request, RateCalculator $rateCalculator)
{
    // Session se data aur barcode option nikalna
    $rows = session('excel_data'); 
    $barcodeOption = session('barcode_option');
    $franchiseId = Franchise::getFranchiseId();
    $franchise = Franchise::findOrFail($franchiseId);

    if (!$rows) {
        return response()->json(['status' => 'error', 'message' => 'No data found in session.']);
    }

    $successCount = 0;
    $failCount = 0;
    $failedRows = [];

    foreach ($rows as $row) {
        try {
            // 1. Strict Validation Check
            $excelBarcode = isset($row['Barcode']) ? trim($row['Barcode']) : '';

            if ($barcodeOption === 'barcode_auto' && !empty($excelBarcode)) {
                throw new \Exception("Barcode is present in Excel, please select 'Custom Barcode' or remove barcodes from file.");
            }

            if ($barcodeOption === 'barcode_custom' && empty($excelBarcode)) {
                throw new \Exception("Barcode is missing in Excel, please select 'Auto Generate'.");
            }

            // 2. Row Processing & Saving
            $result = $this->processSingleRow($row, $franchise, $rateCalculator, $barcodeOption);
            
            if ($result['success']) {
                $successCount++;
            } else {
                throw new \Exception($result['message']);
            }

        } catch (\Exception $e) {
            $failCount++;
            $row['Error Reason'] = $e->getMessage();
            $failedRows[] = $row;
        }
    }

    // Session clear karein processing ke baad
    session()->forget(['excel_data', 'barcode_option']);

    return response()->json([
        'status' => 'success',
        'data' => [
            'success_count' => $successCount,
            'fail_count' => $failCount,
            'failed_rows' => $failedRows
        ]
    ]);
}


    /**
     * Booking Type Determine Karega
     */
    private function determineBookingType($row)
    {
        if (!empty($row['Market Manager _id'])) {
            return 'manager';
        } elseif (!empty($row['Pickup Boy_id'])) {
            return 'pickup';
        }
        return 'franchise';
    }

    /**
     * Parcel Record Create Karega
     */
    private function createParcelRecord($data)
    {
        $row = $data['row'];
        $franchise = $data['franchise'];
        $customer = $data['customerDetails'];
        $weight = $data['weightDetails'];
        $rate = $data['rateDetails'];
        $barcode = $data['barcodeData'];
        $bookingType = $data['bookingType'];
        
        $paymentMethod = strtolower($row['Payment Method'] ?? 'prepaid');
        
        $parcelData = [
            'franchise_id' => $franchise->id,
            'franchise_role_users_id' => Auth::guard('franchiseRoleUser')->id() ?? null,
            'cod_customer_id' => $paymentMethod == 'cod' ? ($customer['id'] ?? null) : null,
            'no_r_customer_id' => $paymentMethod != 'cod' ? ($customer['id'] ?? null) : null,
            'booking_type' => $bookingType,
            'pickup_name' => $row['Pickup Name'] ?? '',
            'pickup_mobile' => $row['Pickup Phone'],
            'pickup_email' => $row['Pickup Email'] ?? null,
            'pickup_pincode' => $row['Pickup Pincode'],
            'pickup_city' => $row['Pickup City'] ?? '',
            'pickup_state' => $row['Pickup State'] ?? '',
            'pickup_address' => $row['Pickup Address'] ?? '',
            'consignee_name' => $row['Consignee Name'] ?? '',
            'consignee_mobile' => $row['Consignee Phone'] ?? '',
            'consignee_email' => $row['Consignee Email'] ?? null,
            'consignee_pincode' => $row['Consignee Pincode'],
            'consignee_city' => $row['Consignee City'] ?? '',
            'consignee_state' => $row['Consignee State'] ?? '',
            'consignee_address' => $row['Consignee Address'] ?? '',
            'package_weight' => $weight['actual'],
            'package_length' => $row['Package Length'] ?? null,
            'package_width' => $row['Package Width'] ?? null,
            'package_height' => $row['Package Height'] ?? null,
            'payment_method' => $paymentMethod,
            'cod_amount' => $paymentMethod == 'cod' ? ($row['Cod Amount'] ?? 0) : 0,
            'fuel_charge' => $row['Fuel Charge'] ?? 0,
            'pickup_charge' => $row['Pickup Charge'] ?? 0,
            'other_service_charge' => $row['Other service Charge'] ?? 0,
            'register_amount' => $row['Register Fee'] ?? 0,
            'payment_amount' => $data['paymentAmount'],
            'totalOtherAmount' => $rate['total'] ?? 0,
            'barcode_no' => $barcode['code'],
            'barcode_image_src' => $barcode['image'],
            'insert_type' => IndiaPostSpeedPostParcel::INSERT_TYPE_BULK,
        ];
        
        // Create parcel
        $parcel = IndiaPostSpeedPostParcel::create($parcelData);
        
        // Create track order
        IndiaPostSpeedPostTrackOrder::create([
            'parcel_id' => $parcel->id,
            'barcode_no' => $barcode['code'],
            'source_franchise_id' => $franchise->id,
            'order_placed_datetime' => now(),
            'source_franchise_location' => $franchise->address,
        ]);
        
        // Create commission record
        FranchiseCommissionDetail::create([
            'franchise_id' => $franchise->id,
            'service_type' => IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED,
            'amount' => $data['paymentAmount'] / 1.18,
            'commission' => $this->calculateCommission($weight['actual'], $data['paymentAmount']),
            'payment_method' => $paymentMethod,
        ]);
        
        return $parcel;
    }

    /**
     * Commission Calculate Karega
     */
    private function calculateCommission($weight, $amount)
    {
        // Add your commission calculation logic here
        return 0; // Temporary
    }

    /**
     * Franchise Balance Update Karega
     */
    private function updateFranchiseBalance($franchise, $amount)
    {
        if ($franchise->indiapost_balance >= $amount) {
            $franchise->decrement('indiapost_balance', $amount);
        } else {
            $remaining = $amount - $franchise->indiapost_balance;
            $franchise->decrement('indiapost_balance', $franchise->indiapost_balance);
            $franchise->decrement('india_credit_amount', $remaining);
        }
    }

    /**
     * Barcode Series Update Karega
     */
    private function updateBarcodeSeries($franchiseId, $barcode)
    {
        $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB(
            IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED
        );
        
        $lastCodeColumn = "last_parcel_code_issued_{$serviceTypeValue}";
        $startColumn = "parcel_barcode_range_start_{$serviceTypeValue}";
        
        $series = FranchiseBarcodeSeries::where('franchise_id', $franchiseId)->first();
        
        if ($series) {
            $seriesNum = $series->{$lastCodeColumn} ? $series->{$lastCodeColumn} + 1 : $series->{$startColumn};
            $series->{$lastCodeColumn} = $seriesNum;
            $series->save();
            
            FranchiseBarcodes::create([
                'barcodes' => $barcode,
                'franchise_barcodeseries_id' => $series->id,
            ]);
        }
    }

    /**
     * Next Barcode Generate Karega
     */
    private function getNextBarcode()
    {
        $user = Auth::guard("franchise")->user();
        
        $barcode = IndiaPostBarcode::where("availables", ">", 0)
            ->where('service_type', IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED)
            ->first();
            
        if ($barcode) {
            $nextBarcode = $barcode->getNextBarcode();
            $barcode->increment("range_from");
            $barcode->decrement("availables");
            return $nextBarcode;
        }
        
        throw new \Exception("No barcodes available");
    }

    /**
     * Available Barcode Count
     */
    private function getBarcodeAvailableCount()
    {
        $serviceType = IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED;
        $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB($serviceType);

        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";
        $range_end_column = "parcel_barcode_range_end_{$serviceTypeValue}";
        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";

        $franchiseId = Franchise::getFranchiseId();
        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();

        if (!$franchiseSeriesDetails || $franchiseSeriesDetails->{$range_end_column} === null) {
            return 0;
        }

        $start = (int) $franchiseSeriesDetails->{$range_start_column};
        $end = (int) $franchiseSeriesDetails->{$range_end_column};
        $lastIssued = (int) ($franchiseSeriesDetails->{$last_code_issued_column} ?? $start - 1);

        return max(0, $end - $lastIssued);
    }

    /**
     * Failed Rows Excel Generate Karega
     */
    private function generateFailedExcel($failedRows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        $headers = array_keys($failedRows[0]);
        $sheet->fromArray($headers, NULL, 'A1');
        
        // Data
        $rowIndex = 2;
        foreach ($failedRows as $row) {
            $sheet->fromArray(array_values($row), NULL, "A{$rowIndex}");
            $rowIndex++;
        }
        
        $fileName = 'failed_rows_' . date('Ymd_His') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filePath);
        
        return $fileName;
    }

    /**
     * Download Updated Excel Format
     */
    public function downloadUpdatedFormat()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Updated headers with all required columns including distance
        $headers = [
            'sr no', 
            'Barcode',
            'Market Manager _id',
            'Pickup Boy_id',
            'Pickup Name',
            'Pickup Phone',
            'Pickup Email',
            'Pickup gst',
            'Pickup Pincode',
            'Pickup City',
            'Pickup State',
            'Pickup Address',
            'Consignee Name',
            'Consignee Phone',
            'Consignee Email',
            'Consignee Pincode',
            'Consignee City',
            'Consignee State',
            'Consignee Address',
            'Payment Method',
            'Package Weight',
            'Package Length',
            'Package Width',
            'Package Height',
            'Fuel Charge',
            'Pickup Charge',
            'Other service Charge',
            'Register Fee',
            'Amount',
            'Cod Amount',
            'distance'  // ✅ REQUIRED COLUMN
        ];
        
        $sheet->fromArray($headers, NULL, 'A1');
        
        // Add sample data
        $sampleData = [
            [
                1, 'JF537271811IN', null, null, 'LEX DIGITAL', '123456789', 'email@example.com', 'GST123',
                '110053', 'BHAJANPURA', 'DELHI', 'BHAJANPURA',
                'NALLA SATYA RAMA', '123456789', 'consignee@example.com', '533262', 'ANDHRA PRADESH', 
                'ANDHRA PRADESH', '2-94 NALLA VARI STREET KAJULURU',
                'PREPAID', 20, null, null, null, null, null, null, 0, 0, 0,
                20  // ✅ distance value
            ]
        ];
        
        $sheet->fromArray($sampleData, NULL, 'A2');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'india_post_bulk_upload_format.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}