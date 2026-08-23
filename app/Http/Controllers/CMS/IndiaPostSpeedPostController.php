<?php



namespace App\Http\Controllers\CMS;



use App\Http\Controllers\Controller;

use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostSpeedPostTrackOrder;

use App\Models\PickupDetails;

use App\Models\Franchise;
use App\Models\CMS;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\File;

use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Symfony\Component\HttpFoundation\StreamedResponse;

use GuzzleHttp\Client;

use Picqer\Barcode\BarcodeGeneratorPNG;

use DB;

use App\Models\FranchiseBarcodeSeries;

use App\Models\Admin;

use App\Models\FranchiseBarcodes;

use Illuminate\Support\Facades\View;

use Carbon\Carbon;

use App\Models\IndiaPostLink;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;



class IndiaPostSpeedPostController extends Controller

{



    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $serviceStatuses = CMS::checkServiceStatus(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);
            // Check if at least one service status is set to 1
            if (!$serviceStatuses) {
                return abort(403, 'Service not available.');
            }

            return $next($request);
        });
    }

    public function getUniqueCode()

    {

        $serviceType = IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED;

        $randomNumber = rand(1, 9);

        $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB($serviceType);

        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

        $range_end_column = "parcel_barcode_range_end_{$serviceTypeValue}";

        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";



        $franchiseId = Auth::id();

        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("cms_id", $franchiseId)->first();



        if ($franchiseSeriesDetails) {

            if ($franchiseSeriesDetails->{$range_end_column} != null && $franchiseSeriesDetails->{$range_end_column} > $franchiseSeriesDetails->{$last_code_issued_column}) {

                if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                    $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                    $seriesNum = str_pad($last_parcel_code_issued + 1, 7, '0', STR_PAD_LEFT);
                } else {

                    $seriesNum = str_pad($franchiseSeriesDetails->{$range_start_column}, 7, '0', STR_PAD_LEFT);
                }

                $serviceCode = IndiaPostSpeedPostParcel::getServiceCode($serviceType);

                $code = $serviceCode . FranchiseBarcodeSeries::$PARCELCODE . $seriesNum . $randomNumber . 'CO';

                return $code;
            } else {

                return 'Barcode series end';
            }
        } else {

            return 'Barcodes not assigned';
        }
    }


    public function index(Request $request)
{
    $totalParcels = 0;
        $totalAmount = 0;
        $CodtotalAmount = 0;
        $type = $request->type;
    $searchKey = $request->input('searchKey');
    $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
    $fromdate = $request->input('fromdate');
    $todate = $request->input('todate');

    $userId = Auth::guard('cms')->user()->id;

    // Default to today's date if none provided
    $formattedDate = (!empty($date)) ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

    // Initialize query for SCID and DCID
    $scidData = IndiaPostSpeedPostParcel::where('scid_forfile_upload', $userId);
    $dcidData = IndiaPostSpeedPostParcel::where('dcid_forfile_upload', $userId);

    // Helper function to apply search filters
    $applySearchFilter = function ($query) use ($searchKey) {
        $query->where(function ($q) use ($searchKey) {
            $q->where('pickup_name', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")
                ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
        });
    };

    // Apply search filter if search key is provided
    if (!empty($searchKey)) {
        $scidData->where($applySearchFilter);
        $dcidData->where($applySearchFilter);
    }

    // Apply date filters
    if (!empty($fromdate) || !empty($todate)) {
        if (!empty($fromdate)) {
            $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)->startOfDay()->toDateString();
        }
        if (!empty($todate)) {
            $todate = Carbon::createFromFormat("d-m-Y", $todate)->endOfDay()->toDateString();
        }

        if (!empty($fromdate) && !empty($todate)) {
            $scidData->whereBetween('scid_file_upload_date', [$fromdate, $todate]);
            $dcidData->whereBetween('dcid_file_upload_date', [$fromdate, $todate]);
        } elseif (!empty($fromdate)) {
            $scidData->whereDate('scid_file_upload_date', $fromdate);
            $dcidData->whereDate('dcid_file_upload_date', $fromdate);
        } elseif (!empty($todate)) {
            $scidData->whereDate('scid_file_upload_date', $todate);
            $dcidData->whereDate('dcid_file_upload_date', $todate);
        }
    } else {
        // Default to today's date
        $scidData->whereDate('scid_file_upload_date', Carbon::today());
        $dcidData->whereDate('dcid_file_upload_date', Carbon::today());
    }

    // Get the results
    $scidResults = $scidData->get();
    $dcidResults = $dcidData->get();

    // Merge both collections
    $mergedCollection = $scidResults->merge($dcidResults);
    $CodtotalAmount = $mergedCollection->sum("cod_amount");
    $totalAmount    = $mergedCollection->sum("payment_amount");
    $totalParcels   = $mergedCollection->count();
    return view('cms.indiaPost-speedPost.index', ['datas' => $mergedCollection, 'CodtotalAmount'  => $CodtotalAmount,'totalAmount'     => $totalAmount,'totalParcels'    => $totalParcels,]);
}




    public function getPrice($origin, $destination, $weight)

    {

        $newrequest = new Request([

            'originPincode' => $origin,

            'destinationPincode' => $destination,

            'packageWeight' => $weight

        ]);

        $rateCalculator = new RateCalculator();

        $result = $rateCalculator->calculate($newrequest);

        return json_decode(json_encode($result), true);
    }



    public function create()

    {



        //  DB::statement('ALTER TABLE gotogo_speed_post_parcels MODIFY barcode_no VARCHAR(255) NULL');

        // DB::statement('ALTER TABLE gotogo_speed_post_parcels MODIFY barcode_image_src VARCHAR(255) NULL');





        $cms_details = CMS::where('id', Auth::guard('cms')->user()->id)->select('cms_no', 'wallet_balance', 'remaining_balance')->first();

        $generator = new BarcodeGeneratorPNG();

        $code =   $this->getUniqueCode();

        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        $barcode = base64_encode($barcode);

        return view('cms.indiaPost-speedPost.create', ['barcode' => $barcode, 'code' => $code, 'cms_details' => $cms_details]);
    }



    public function store(Request $request)

    {

        // return $request;

        $requestData = $request->all();

        $this->validate($request, [

            // Pickup details validation rules

            'PickupName' => 'required|string|max:191',

            'PickupMobile' => 'required|string',

            'PickupEmail' => 'required|email|max:191',

            'PickupPincode' => 'required|string|max:6',

            'PickupCity' => 'required|string|max:191',

            'PickupState' => 'required|string|max:191',

            'PickupAddress' => 'required|string|max:255',

            // Consignee details validation rules

            'ConsigneeName' => 'required|string|max:191',

            'ConsigneeMobile' => 'required|string',

            'ConsigneeEmail' => 'required|email|max:191',

            'ConsigneePincode' => 'required|string|max:6',

            'ConsigneeCity' => 'required|string|max:191',

            'ConsigneeState' => 'required|string|max:191',

            'ConsigneeAddress' => 'required|string|max:255',

            // Parcel details validation rules

            'package_weight' => 'required|numeric',

           

            'payment_method' => 'required|string',

        ]);



        $rateDetails = $this->getPrice($request->PickupPincode, $request->ConsigneePincode, $request->package_weight);



        if ($request->amount) {

            $transportation = 0;

            $payment_amount = $request->amount + ($request->amount + $transportation) * 0.18;
        } else {

            $payment_amount = $rateDetails['original']['total'];
        }



        if ($rateDetails['original']['status'] == 'fail') {

            if ($request->static == 1) {

                return response()->json(['status' => 400, 'message' => $rateDetails['original']['message'], 'data' => $requestData]);
            } else {

                return back()->with('error', $rateDetails['original']['message'])->withInput();;
            }
        }



        if ($this->getNextBarcode() == 'Barcode series end' || $this->getNextBarcode() == 'Barcodes not assigned') {

            if ($request->static == 1) {

                return response()->json(['status' => 400, 'message' => 'barcode series end', 'data' => $requestData]);
            } else {

                return back()->with('error', 'barcode series end for this services');
            }
        }



        try {



            // return $request->static;

            // return $rateDetails;



            // Create Pickup

            IndiaPostSpeedPostParcel::create([

                'cms_id' => Auth::guard('cms')->user()->id,

                'pickup_name' => $request->PickupName,

                'pickup_mobile' => $request->PickupMobile,

                'pickup_email' => $request->PickupEmail,

                'pickup_pincode' => $request->PickupPincode,

                'pickup_city' => $request->PickupCity,

                'pickup_state' => $request->PickupState,

                'pickup_address' => $request->PickupAddress,

                'consignee_name' => $request->ConsigneeName,

                'consignee_mobile' => $request->ConsigneeMobile,

                'consignee_email' => $request->ConsigneeEmail,

                'consignee_pincode' => $request->ConsigneePincode,

                'consignee_city' => $request->ConsigneeCity,

                'consignee_state' => $request->ConsigneeState,

                'consignee_address' => $request->ConsigneeAddress,

                'package_weight' => $request->package_weight,

                'package_length' => $request->package_length,

                'package_width' => $request->package_width,

                'package_height' => $request->package_height,

                'payment_amount' => $payment_amount,

                'payment_method' => $request->payment_method,

                'barcode_no' =>  $this->getNextBarcode(),

                'barcode_image_src' =>  $request->barcodeImageSrc,

                'insert_type' =>  IndiaPostSpeedPostParcel::INSERT_TYPE_SINGLE,

            ]);



            $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);

            $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

            $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";



            $franchiseId = Auth::guard('cms')->user()->id;

            $franchiseSeriesDetails = FranchiseBarcodeSeries::where("cms_id", $franchiseId)->first();

            if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                $seriesNum = $last_parcel_code_issued + 1;
            } else {

                $seriesNum = $franchiseSeriesDetails->{$range_start_column};
            }



            $franchiseSeriesDetails->{$last_code_issued_column} = $seriesNum;

            $franchiseSeriesDetails->save();



            $franchiseBarcode = new FranchiseBarcodes;

            $franchiseBarcode->barcodes = $this->getNextBarcode();

            $franchiseBarcode->franchise_barcodeseries_id = $franchiseSeriesDetails->id;

            $franchiseBarcode->save();





            if ($request->static == 1) {

                $code =   $this->getUniqueCode();

                return response()->json(['status' => 200, 'message' => 'new parcel added', 'data' => $requestData, 'code' => $code]);
            } else {

                return redirect()->back()->with('success', 'New Parcel Added');
            }
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }



    public function view($id)

    {

        $data = IndiaPostSpeedPostParcel::findorfail($id);

        $fuel_charge = $data->fuel_charge;
        $pickup_charge = $data->pickup_charge;
        $other_service_charge = $data->other_service_charge;
        $total_payment_amount = $data->payment_amount;
        $net_price = $total_payment_amount / 1.18;
        $amount = $net_price - ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, '.', '');
        $net_price_formatted = number_format($net_price, 2, '.', '');


        $rateDetails = [
            'fuel_charge' => $fuel_charge,
            'pickup_charge' => $pickup_charge,
            'other_service_charge' => $other_service_charge,
            'total_payment_amount' => number_format($total_payment_amount, 2, '.', ''),
            'net_price' => $net_price_formatted,
            'amount' => number_format($amount, 2, '.', ''),
            'gst' => $gst,
        ];

        return view('cms.indiaPost-speedPost.view', compact('data', 'rateDetails'));
    }



    public function edit(Request $request, $id)

    {

        $post = IndiaPostSpeedPostParcel::findOrFail($id);



        if ($request->isMethod('POST')) {





            $this->validate($request, [

                // Pickup details validation rules

                'PickupName' => 'required|string|max:191',

                'PickupMobile' => 'required|string',

                'PickupEmail' => 'required|email|max:191',

                'PickupPincode' => 'required|string|max:6',

                'PickupCity' => 'required|string|max:191',

                'PickupState' => 'required|string|max:191',

                'PickupAddress' => 'required|string|max:255',

                // Consignee details validation rules

                'ConsigneeName' => 'required|string|max:191',

                'ConsigneeMobile' => 'required|string',

                'ConsigneeEmail' => 'required|email|max:191',

                'ConsigneePincode' => 'required|string|max:6',

                'ConsigneeCity' => 'required|string|max:191',

                'ConsigneeState' => 'required|string|max:191',

                'ConsigneeAddress' => 'required|string|max:255',

                // Parcel details validation rules

                'package_weight' => 'required|numeric',

               
                'payment_method' => 'required|string',

            ]);





            try {

                $post->pickup_name = $request->PickupName;

                $post->pickup_mobile = $request->PickupMobile;

                $post->pickup_email = $request->PickupEmail;

                $post->pickup_pincode = $request->PickupPincode;

                $post->pickup_city = $request->PickupCity;

                $post->pickup_state = $request->PickupState;

                $post->pickup_address = $request->PickupAddress;

                $post->consignee_name = $request->ConsigneeName;

                $post->consignee_mobile = $request->ConsigneeMobile;

                $post->consignee_email = $request->ConsigneeEmail;

                $post->consignee_pincode = $request->ConsigneePincode;

                $post->consignee_city = $request->ConsigneeCity;

                $post->consignee_state = $request->ConsigneeState;

                $post->consignee_address = $request->ConsigneeAddress;

                $post->package_weight = $request->package_weight;

                $post->package_length = $request->package_length;

                $post->package_width = $request->package_width;

                $post->package_height = $request->package_height;

                $post->payment_method = $request->payment_method;



                $post->save();



                return redirect()->route('cms.india-post-speed-postindex', ['insert_type' => $post->insert_type])->with('success', 'Parcel updated successfully!');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage())->withInput();
            }
        }



        $data = $post;

        $pickupDetails = PickupDetails::where('cms_id', Auth::guard('cms')->user()->id)

            ->get();



        return view('cms.indiaPost-speedPost.edit', compact('data', 'pickupDetails'));
    }



    public function delete($id)

    {

        try {

            IndiaPostSpeedPostParcel::findorfail($id)->delete();

            return redirect()->back()->with('success', 'Parcel Deleted Successfully');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage())->withInput();
        }
    }



    public function downloadFormat()

    {

        try {

            $filePath = public_path('admin/assets/file/excelFomatFile.xlsx');

            if (!file_exists($filePath)) {

                return back()->with('error', 'File not found');
            }

            return response()->download($filePath, 'excelFomatFile.xlsx');
        } catch (\Exception $e) {

            return back()->with('error', 'Error downloading file: ' . $e->getMessage());
        }
    }



    public function getUploadedFileData(Request $request)

    {

        if (!$request->hasFile('file')) {

            return back()->with('error', 'No file uploaded.');
        }

        try {

            // Move the uploaded file to the destination path

            $file = $request->file('file');

            $destinationPath = public_path('tenancy/assets/cms/RoleUser/');

            $fileName = uniqid() . '_' . $file->getClientOriginalName();

            $file->move($destinationPath, $fileName);



            // Load the Excel file

            $filePath = public_path("tenancy/assets/cms/RoleUser/$fileName");

            if (!File::exists($filePath)) {

                return back()->with('error', 'File not found.');
            }



            $spreadsheet = IOFactory::load($filePath);

            $sheet = $spreadsheet->getActiveSheet();

            $data = [];

            $header = null;



            // Iterate through each row in the worksheet

            foreach ($sheet->getRowIterator() as $rowIndex => $row) {

                $rowData = [];

                $hasData = false;



                foreach ($row->getCellIterator() as $cellIndex => $cell) {

                    $cellValue = $cell->getValue();

                    $rowData[] = $cellValue;

                    $hyperlink = $cell->getHyperlink();

                    $hyperlinkUrl = $hyperlink ? $hyperlink->getUrl() : null;



                    if ($hyperlinkUrl) {

                        $rowData['Hyperlink'] = $hyperlinkUrl;
                    }

                    if (!is_null($cellValue) && $cellValue !== '') {

                        $hasData = true;
                    }
                }

                if ($hasData) {

                    if (is_null($header)) {

                        $header = $rowData;

                        $header[] = "Hyperlink";
                    } else {

                        if (count($rowData) < count($header)) {

                            $rowData = array_pad($rowData, count($header), null);
                        }

                        $data[] = array_combine($header, $rowData);
                    }
                }
            }


            return response()->json(['data' => $data]);
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }


    public function storeByFile(Request $request)

    {

        try {

            $data = $request->updatedData;
            foreach ($data as $element) {

                if (!empty($element['Hyperlink'])) {

                    $hyperlinkUrl = $element['Hyperlink'];

                    $downloadFileName = uniqid() . '_' . pathinfo($hyperlinkUrl, PATHINFO_BASENAME);

                    $destination = $downloadDirectory . $downloadFileName;

                    try {

                        $client->get($hyperlinkUrl, ['sink' => $destination]);
                    } catch (\Exception $e) {

                        Log::error("Error downloading file from $hyperlinkUrl: " . $e->getMessage());

                        continue;
                    }
                }



                $rateDetails = $this->getPrice($element['Pickup Pincode'], $element['Consignee Pincode'], $element['Package Weight']);



                if ($rateDetails['original']['status'] == 'fail') {



                    return back()->with('error', $rateDetails['original']['message']);
                }





                try {



                    if ($request->barcode_option === "barcode_auto") {

                        $generator = new BarcodeGeneratorPNG();

                        $code =   $this->getUniqueCode();

                        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

                        $barcode = base64_encode($barcode);
                    } else {

                        $code = null;

                        $barcode = null;
                    }



                    IndiaPostSpeedPostParcel::create([

                        'cms_id' => Auth::guard('cms')->user()->id,

                        'pickup_name' => $element['Pickup Name'],

                        'pickup_mobile' => $element['Pickup Phone'],

                        'pickup_email' => $element['Pickup Email'],

                        'pickup_pincode' => $element['Pickup Pincode'],

                        'pickup_city' => $element['Pickup City'],

                        'pickup_state' => $element['Pickup State'],

                        'pickup_address' => $element['Pickup Address'],

                        'consignee_name' => $element['Consignee Name'],

                        'consignee_mobile' => $element['Consignee Phone'],

                        'consignee_email' => $element['Consignee Email'],

                        'consignee_pincode' => $element['Consignee Pincode'],

                        'consignee_city' => $element['Consignee City'],

                        'consignee_state' => $element['Consignee State'],

                        'consignee_address' => $element['Consignee Address'],

                        'package_weight' => $element['Package Weight'],

                        'package_length' => $element['Package Length'],

                        'package_width' => $element['Package Width'],

                        'package_height' => $element['Package Height'],

                        'payment_method' => $element['Payment Method'],

                        'payment_amount' => $rateDetails['original']['total'],

                        'barcode_no' =>  $code,

                        'barcode_image_src' =>  $barcode,

                        'insert_type' =>  IndiaPostSpeedPostParcel::INSERT_TYPE_BULK,

                    ]);



                    if ($request->barcode_option === "barcode_auto") {

                        $serviceTypeValue = IndiaPostSpeedPostParcel::getServiceTypeDB(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED);

                        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

                        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";



                        $franchiseId = Auth::guard('cms')->user()->id;

                        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("cms_id", $franchiseId)->first();

                        if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                            $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                            $seriesNum = $last_parcel_code_issued + 1;
                        } else {

                            $seriesNum = $franchiseSeriesDetails->{$range_start_column};
                        }



                        $franchiseSeriesDetails->{$last_code_issued_column} = $seriesNum;

                        $franchiseSeriesDetails->save();



                        $franchiseBarcode = new FranchiseBarcodes;

                        $franchiseBarcode->barcodes =  $code;

                        $franchiseBarcode->franchise_barcodeseries_id = $franchiseSeriesDetails->id;

                        $franchiseBarcode->save();
                    }
                } catch (\Exception $e) {

                    return back()->with('error', $e->getMessage());
                }
            }

            return redirect()->route('cms.india-post-speed-postindex', ['insert_type' => IndiaPostSpeedPostParcel::INSERT_TYPE_BULK])->with('success', 'Data added successfully');
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }



    public function excelUploadByCms(Request $request)

    {

        if (!$request->hasFile('file')) {

            return back()->with('error', 'No file uploaded.');
        }

        try {

            // Move the uploaded file to the destination path

            $file = $request->file('file');

            $destinationPath = public_path('tenancy/assets/cms/RoleUser/');

            $fileName = uniqid() . '_' . $file->getClientOriginalName();

            $file->move($destinationPath, $fileName);



            // Load the Excel file

            $filePath = public_path("tenancy/assets/cms/RoleUser/$fileName");

            if (!File::exists($filePath)) {

                return back()->with('error', 'File not found.');
            }



            $spreadsheet = IOFactory::load($filePath);

            $sheet = $spreadsheet->getActiveSheet();

            $data = [];

            $header = null;



            // Iterate through each row in the worksheet

            foreach ($sheet->getRowIterator() as $rowIndex => $row) {

                $rowData = [];

                $hasData = false;



                foreach ($row->getCellIterator() as $cellIndex => $cell) {

                    $cellValue = $cell->getValue();

                    $rowData[] = $cellValue;

                    $hyperlink = $cell->getHyperlink();

                    $hyperlinkUrl = $hyperlink ? $hyperlink->getUrl() : null;



                    if ($hyperlinkUrl) {

                        $rowData['Hyperlink'] = $hyperlinkUrl;
                    }

                    if (!is_null($cellValue) && $cellValue !== '') {

                        $hasData = true;
                    }
                }

                if ($hasData) {

                    if (is_null($header)) {

                        $header = $rowData;

                        $header[] = "Hyperlink";
                    } else {

                        if (count($rowData) < count($header)) {

                            $rowData = array_pad($rowData, count($header), null);
                        }

                        $data[] = array_combine($header, $rowData);
                    }
                }
            }

            $desiredBarcode = [];
            foreach ($data as $key => $value) {
                $desiredBarcode[] = $value['Barcode'];
            }


            IndiaPostSpeedPostParcel::whereIn('barcode_no', $desiredBarcode)
                ->where(function ($query) {
                    $userId = Auth::guard('cms')->user()->id;
                    $query->whereNull('scid_forfile_upload')
                        ->orWhere('scid_forfile_upload', $userId);
                })
                ->update(['scid_forfile_upload' => Auth::guard('cms')->user()->id, 'scid_file_upload_date' => Carbon::today()->toDateString()]);

            IndiaPostSpeedPostParcel::whereIn('barcode_no', $desiredBarcode)
                ->whereNotNull('scid_forfile_upload')
                ->where('scid_forfile_upload', '!=', Auth::guard('cms')->user()->id)
                ->update(['dcid_forfile_upload' => Auth::guard('cms')->user()->id, 'dcid_file_upload_date' => Carbon::today()->toDateString()]);


            $updatedParcels = IndiaPostSpeedPostParcel::whereIn('barcode_no', $desiredBarcode)->get();

            return view('cms.indiaPost-speedPost.index', ['datas' => $updatedParcels]);
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }



    public function fullPrint($id)

    {

        $parcel = IndiaPostSpeedPostParcel::findorfail($id);

        $generator = new BarcodeGeneratorPNG();
        $code = $parcel->barcode_no;
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
        $barcode = base64_encode($barcode);

        $fuel_charge = $parcel->fuel_charge;
        $pickup_charge = $parcel->pickup_charge;
        $other_service_charge = $parcel->other_service_charge;
        $total_payment_amount = $parcel->payment_amount;
        $net_price = $total_payment_amount / 1.18;
        $amount = $net_price - ($fuel_charge + $pickup_charge + $other_service_charge);
        $gst = number_format($net_price * 0.18, 2, '.', '');
        $net_price_formatted = number_format($net_price, 2, '.', '');


        $rateDetails = [
            'fuel_charge' => $fuel_charge,
            'pickup_charge' => $pickup_charge,
            'other_service_charge' => $other_service_charge,
            'total_payment_amount' => number_format($total_payment_amount, 2, '.', ''),
            'net_price' => $net_price_formatted,
            'amount' => number_format($amount, 2, '.', ''),
            'gst' => $gst,
        ];


        $otherPageContent = View::make('print.indiaPost.fullPrint', ['parcel' => $parcel, 'barcode' => $barcode, 'rateDetails' => $rateDetails])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }


   public function shortPrint(Request $request)
{
    $fromdate = $request->input("fromdate");
    $todate = $request->input("todate");
    $searchKey = $request->input('searchKey');
    $userId = Auth::guard('cms')->user()->id;

    $fromdateFormatted = !empty($fromdate) ? Carbon::createFromFormat("d-m-Y", $fromdate)->startOfDay()->toDateString() : null;
    $todateFormatted = !empty($todate) ? Carbon::createFromFormat("d-m-Y", $todate)->endOfDay()->toDateString() : null;

    // Common search filter closure
    $searchFilter = function ($query) use ($searchKey) {
        $query->where('pickup_name', 'LIKE', "%{$searchKey}%")
            ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")
            ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")
            ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")
            ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")
            ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")
            ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")
            ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")
            ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")
            ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")
            ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")
            ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")
            ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")
            ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")
            ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
    };

    // SCID Data
    $scidData = IndiaPostSpeedPostParcel::where('scid_forfile_upload', $userId);

    if ($searchKey) {
        $scidData->where($searchFilter);
    }

    if ($fromdateFormatted && $todateFormatted) {
        $scidData->whereBetween("scid_file_upload_date", [$fromdateFormatted, $todateFormatted]);
    } elseif ($fromdateFormatted) {
        $scidData->whereDate("scid_file_upload_date", $fromdateFormatted);
    } elseif ($todateFormatted) {
        $scidData->whereDate("scid_file_upload_date", $todateFormatted);
    } else {
        $scidData->whereDate("scid_file_upload_date", Carbon::today());
    }

    $scidResults = $scidData->get();

    // DCID Data
    $dcidData = IndiaPostSpeedPostParcel::where('dcid_forfile_upload', $userId);

    if ($searchKey) {
        $dcidData->where($searchFilter);
    }

    if ($fromdateFormatted && $todateFormatted) {
        $dcidData->whereBetween("dcid_file_upload_date", [$fromdateFormatted, $todateFormatted]);
    } elseif ($fromdateFormatted) {
        $dcidData->whereDate("dcid_file_upload_date", $fromdateFormatted);
    } elseif ($todateFormatted) {
        $dcidData->whereDate("dcid_file_upload_date", $todateFormatted);
    } else {
        $dcidData->whereDate("dcid_file_upload_date", Carbon::today());
    }

    $dcidResults = $dcidData->get();

    // Merge and transform
    $mergedCollection = $scidResults->merge($dcidResults);
    $refinedData = [];

    foreach ($mergedCollection as $data) {
        $franchise = Franchise::find($data->franchise_id);
        $linkDetail = IndiaPostLink::where('franchise_no', optional($franchise)->franchise_no)->first();

        $refinedData[] = (object)[
            'franchise_pincode' => optional($franchise)->pincode,
        ];
    }

    $title = \App\Models\Admin::INDIA_POST_SPEED;
    $view = 'print.indiaPost.prepaidRecipt'; // You had $view used but never defined; set default here

    $otherPageContent = View::make($view, [
        "data" => $mergedCollection,
        "title" => $title,
        "franchises" => $refinedData,
        "type" => 5,
    ])->render();

    return response()->json([
        "otherPageContent" => $otherPageContent,
    ]);
}


    public function downloadTable(Request $request)

    {
        $title = \App\Models\Admin::INDIA_POST_SPEED;
        $searchKey = $request->input('searchKey');
    $fromdate = $request->input("fromdate");
        $todate = $request->input("todate");
    $fromdate = $request->input('fromdate');
    $todate = $request->input('todate');

    $userId = Auth::guard('cms')->user()->id;

    // Default to today's date if none provided
    $formattedDate = (!empty($date)) ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

    // Initialize query for SCID and DCID
    $scidData = IndiaPostSpeedPostParcel::where('scid_forfile_upload', $userId);
    $dcidData = IndiaPostSpeedPostParcel::where('dcid_forfile_upload', $userId);

    // Helper function to apply search filters
    $applySearchFilter = function ($query) use ($searchKey) {
        $query->where(function ($q) use ($searchKey) {
            $q->where('pickup_name', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")
                ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")
                ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")
                ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
        });
    };

    // Apply search filter if search key is provided
    if (!empty($searchKey)) {
        $scidData->where($applySearchFilter);
        $dcidData->where($applySearchFilter);
    }

    // Apply date filters
    if (!empty($fromdate) || !empty($todate)) {
        if (!empty($fromdate)) {
            $fromdate = Carbon::createFromFormat("d-m-Y", $fromdate)->startOfDay()->toDateString();
        }
        if (!empty($todate)) {
            $todate = Carbon::createFromFormat("d-m-Y", $todate)->endOfDay()->toDateString();
        }

        if (!empty($fromdate) && !empty($todate)) {
            $scidData->whereBetween('scid_file_upload_date', [$fromdate, $todate]);
            $dcidData->whereBetween('dcid_file_upload_date', [$fromdate, $todate]);
        } elseif (!empty($fromdate)) {
            $scidData->whereDate('scid_file_upload_date', $fromdate);
            $dcidData->whereDate('dcid_file_upload_date', $fromdate);
        } elseif (!empty($todate)) {
            $scidData->whereDate('scid_file_upload_date', $todate);
            $dcidData->whereDate('dcid_file_upload_date', $todate);
        }
    } else {
        // Default to today's date
        $scidData->whereDate('scid_file_upload_date', Carbon::today());
        $dcidData->whereDate('dcid_file_upload_date', Carbon::today());
    }

    // Get the results
    $scidResults = $scidData->get();
    $dcidResults = $dcidData->get();

    // Merge both collections
    $parcels = $scidResults->merge($dcidResults);
            
          return $this->getExcel($request, $parcels, $title);

        // Create a new Spreadsheet object

       
    }

      public function getExcel($request, $parcels)
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        // Set the headings

        $headings = [
            "SERIAL NUMBER",
            "BARCODE NO",
            "PHYSICAL WEIGHT",
            "RECEIVER CITY",
            "RECEIVER PINCODE",
            "RECEIVER NAME",
            "RECEIVER ADD LINE 1",
            "RECEIVER ADD LINE 2",
            "RECEIVER ADD LINE 3",
            "FALSE",
            "SENDER MOBILE NO",
            "RECEIVER MOBILE NO",
            "PREPAYMENT CODE",
            "VALUE OF PREPAYMENT",
            "CODR/COD",
            "VALUE FOR CODR/COD",
            "INSURANCE TYPE",
            "VALUE OF INSURANCE",
            "SHAPE OF ARTICLE",
            "LENGTH",
            "BREADTH/DIAMETER",
            "HEIGHT",
            "PRIORITY FLAG",
            "DELIVERY INSTRUCTION",
            "DELIVERY SLOT",
            "INSTRUCTION RTS",
            "SENDER NAME",
            "SENDER COMPANY NAME",
            "SENDER CITY",
            "SENDER STATE/UT",
            "SENDER PINCODE",
            "SENDER EMAILID",
            "SENDER ALT CONTACT",
            "SENDER KYC",
            "SENDER TAX",
            "RECEIVER COMPANY NAME",
            "RECEIVER STATE/UT",
            "RECEIVER EMAILID",
            "RECEIVER ALT CONTACT",
            "RECEIVER KYC",
            "RECEIVER TAX REF",
            "ALT ADDRESS FLAG",
            "BULK REFERENCE",
            "SENDER ADD LINE 1",
            "SENDER ADD LINE 2",
            "SENDER ADD LINE 3",
        ];

        $redHeaders = [
            "SERIAL NUMBER",
            "PHYSICAL WEIGHT",
            "RECEIVER CITY",
            "RECEIVER PINCODE",
            "RECEIVER NAME",
            "RECEIVER ADD LINE 1",
            "RECEIVER ADD LINE 2",
            "FALSE",
            "SENDER MOBILE NO",
            "RECEIVER MOBILE NO",
            "SENDER NAME",
            "SENDER CITY",
            "SENDER STATE/UT",
            "SENDER PINCODE",
            "RECEIVER STATE/UT",
            "ALT ADDRESS FLAG",
            "SENDER ADD LINE 1",
            "SENDER ADD LINE 2",
        ];

        $column = "A";
        foreach ($headings as $heading) {
            $cell = $column . "1";
            $sheet->setCellValue($cell, $heading);

            // Apply red font color if in list
            if (in_array(strtoupper(trim($heading)), $redHeaders)) {
                $sheet
                    ->getStyle($cell)
                    ->getFont()
                    ->getColor()
                    ->setRGB(Color::COLOR_RED); // FF0000
            }

            // Make header bold
            $sheet
                ->getStyle($cell)
                ->getFont()
                ->setBold(true);

            $column++;
        }

        // Populate the data

        $row = 2; // Start from the second row

        foreach ($parcels as $key => $parcel) {
            // Process pickup address

            $pickupAddressParts = explode(",", $parcel->pickup_address);

            $PADD1 = $pickupAddressParts[0] ?? "";

            $PADD2 = $pickupAddressParts[1] ?? $PADD1;

            $PADD3 = $pickupAddressParts[2] ?? $PADD2;

            $consigneeAddressParts = explode(",", $parcel->consignee_address);

            $CADD1 = $consigneeAddressParts[0] ?? "";

            $CADD2 = $consigneeAddressParts[1] ?? $CADD1;

            $CADD3 = $consigneeAddressParts[2] ?? $CADD2;

            // Set cell values

            $sheet->setCellValue("A" . $row, $key + 1);

            $sheet->setCellValue("B" . $row, $parcel->barcode_no);

            // $sheet->setCellValue('C' . $row, IndiaPostSpeedPostParcel::getServiceType(IndiaPostSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED));
            $sheet->setCellValue("C" . $row, $parcel->package_weight);
            $sheet->setCellValue("D" . $row, $parcel->consignee_city);
            $sheet->setCellValue("E" . $row, $parcel->consignee_pincode);
            $sheet->setCellValue("F" . $row, $parcel->consignee_name);
            $sheet->setCellValue("G" . $row, $CADD1);
            $sheet->setCellValue("H" . $row, $CADD2);
            $sheet->setCellValue("I" . $row, $CADD3);
            $sheet->setCellValue("J" . $row, "FALSE");
            $sheet->setCellValue("K" . $row, $parcel->pickup_mobile);
            $sheet->setCellValue("L" . $row, $parcel->consignee_mobile);
            $sheet->setCellValue("M" . $row, "");
            $sheet->setCellValue("N" . $row, "");
            $sheet->setCellValue("O" . $row, "");
            $sheet->setCellValue("P" . $row, "");
            $sheet->setCellValue("Q" . $row, "");
            $sheet->setCellValue("R" . $row, "");
            $sheet->setCellValue("S" . $row, "");
            $sheet->setCellValue("T" . $row, "");
            $sheet->setCellValue("U" . $row, "");
            $sheet->setCellValue("V" . $row, "");
            $sheet->setCellValue("W" . $row, "");
            $sheet->setCellValue("X" . $row, "");
            $sheet->setCellValue("Y" . $row, "");
            $sheet->setCellValue("Z" . $row, "");

            $sheet->setCellValue("AA" . $row, $parcel->pickup_name);
            $sheet->setCellValue("AB" . $row, "");
            $sheet->setCellValue("AC" . $row, $parcel->pickup_city);
            $sheet->setCellValue("AD" . $row, $parcel->pickup_state);
            $sheet->setCellValue("AE" . $row, $parcel->pickup_pincode);
            $sheet->setCellValue("AF" . $row, $parcel->pickup_email);
            $sheet->setCellValue("AG" . $row, "");
            $sheet->setCellValue("AH" . $row, "");
            $sheet->setCellValue("AI" . $row, "");
            $sheet->setCellValue("AJ" . $row, "");
            $sheet->setCellValue("AK" . $row, $parcel->consignee_state);
            $sheet->setCellValue("AL" . $row, $parcel->consignee_email);
            $sheet->setCellValue("AM" . $row, "");
            $sheet->setCellValue("AN" . $row, "");
            $sheet->setCellValue("AO" . $row, "");
            $sheet->setCellValue("AP" . $row, "FALSE");
            $sheet->setCellValue("AQ" . $row, "");
            $sheet->setCellValue("AR" . $row, $PADD1);
            $sheet->setCellValue("AS" . $row, $PADD2);
            $sheet->setCellValue("AT" . $row, $PADD3);

            $row++;
        }

        // Create a Writer

        $writer = new Xlsx($spreadsheet);

        // Create a response to stream the file

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save("php://output");
        });

        $response->headers->set(
            "Content-Type",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        );

        $response->headers->set(
    "Content-Disposition",
    'attachment; filename="' . $title . '.xlsx"'
);

        $response->headers->set("Cache-Control", "max-age=0");

        return $response;
    }

    public function assignBarcode(Request $request)

    {

        $parcel = IndiaPostSpeedPostParcel::where("barcode_no", $request->barcode)->first();


        if ($parcel) {

            return response()->json(['status' => 'success', 'data' => $parcel, "message" => "dublicate barcode"]);
        }



        if ($request->isMethod('post')) {

            try {

                $generator = new BarcodeGeneratorPNG();

                $barcode = $generator->getBarcode($request->barcode, $generator::TYPE_CODE_128);

                $barcode_image_src = base64_encode($barcode);

                $Model = IndiaPostSpeedPostParcel::where("barcode_no", null)->orderBy('created_at', 'desc')->first();

                $Model->barcode_no = $request->barcode;

                $Model->barcode_image_src = $barcode_image_src;

                $Model->save();



                return response()->json(['status' => 'success', 'data' => $Model, 'message' => "barcode assigned successfully"]);
            } catch (\Throwable $th) {

                return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
            }
        }



        return response()->json(['status' => 'error', 'message' => 'Invalid request method'], 400);
    }





    public function showPrice(Request $request)

    {

        if ($request->isMethod('post')) {

            try {

                if ($request->amount) {

                    $amount = $request->amount;

                    $transportation = 0;

                    $gst = number_format(($amount + $transportation) * 0.18, 2, '.', '');

                    $total = $amount + $transportation + $gst;

                    $data = [

                        'price' => $amount,

                        'gst' => $gst,

                        'total' => $total,

                        'transportation' => $transportation,

                    ];
                } else {

                    $result = $this->getPrice($request->from, $request->to, $request->weight);

                    $data = [

                        'price' => $result['original']['price'],

                        'gst' => $result['original']['gst'],

                        'total' => $result['original']['total'],

                        'transportation' => 0,

                    ];
                }



                return response()->json(['status' => 'success', 'data' => $data, 'message' => "data fetched successfully"]);
            } catch (\Throwable $th) {

                return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
            }
        }



        return response()->json(['status' => 'error', 'message' => 'Invalid request method'], 400);
    }

    
    public function trackOrder($id)
    {
        $trackingDetails = IndiaPostSpeedPostTrackOrder::where('parcel_id', $id)->first();
        return response()->json([
            'trackingDetails' => $trackingDetails
        ]);
    }

    public function getNextBarcode()
    {
        $user = Auth::guard('franchise')->user();
        $state = $user->state; // Get the state of the franchise
    
        try {
            // Get the next available barcode based on state
            $barcode = IndiaPostBarcode::where('state', $state) // Filter by state
                ->where('availables', '>', 0)
                ->first();
    
            if ($barcode) {
                $nextBarcode = $barcode->getNextBarcode();
    
                // Increment range_from and decrement availables
                $barcode->increment('range_from');
                $barcode->decrement('availables');
    
                return response()->json([
                    'status' => 'success',
                    'barcode' => $nextBarcode
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No available barcodes for this state'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

   public function GetTotalAmount(Request $request)
{
    $userId = Auth::guard('cms')->user()->id;

    // Validate and fetch IDs from request
    $ids = $request->input('id', []);
    
    if (!is_array($ids) || empty($ids)) {
        return response()->json([
            "status" => "error",
            "message" => "No IDs provided.",
        ], 400);
    }

    // Get SCID and DCID data
    $scidData = IndiaPostSpeedPostParcel::where('scid_forfile_upload', $userId)
                ->whereIn("id", $ids)
                ->get();

    $dcidData = IndiaPostSpeedPostParcel::where('dcid_forfile_upload', $userId)
                ->whereIn("id", $ids)
                ->get();

    // Merge both collections
    $mergedCollection = $scidData->merge($dcidData);

    // Calculate totals
    $CodtotalAmount = $mergedCollection->sum("cod_amount");
    $totalAmount    = $mergedCollection->sum("payment_amount");
    $totalParcels   = $mergedCollection->count();

    return response()->json([
        "status"          => "success",
        "CodtotalAmount"  => $CodtotalAmount,
        "totalAmount"     => $totalAmount,
        "totalParcels"    => $totalParcels,
        "message"         => "Amount fetched successfully",
    ]);
}


    
}
