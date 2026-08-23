<?php



namespace App\Http\Controllers\PPH;



use App\Http\Controllers\Controller;

use App\Models\GotogoRegisteredParcel;
use App\Models\GotogoRegisteredTrackOrder;

use App\Models\PickupDetails;

use App\Models\Franchise;
use App\Models\PPH;

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

use App\Models\FranchiseBarcodes;

use Illuminate\Support\Facades\View;

use Carbon\Carbon;
use App\Models\GotogoLink;




class GotogoRegisteredController extends Controller

{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $serviceStatuses = PPH::checkServiceStatus(GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED);

            if (!$serviceStatuses) {
                return abort(403, 'Service not available.');
            }

            return $next($request);
        });
    }


    public function getUniqueCode()

    {

        $serviceType = GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED;

        $randomNumber = rand(1, 9);

        $serviceTypeValue = GotogoRegisteredParcel::getServiceTypeDB($serviceType);

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

                $serviceCode = GotogoRegisteredParcel::getServiceCode($serviceType);

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

        $searchKey = $request->input('searchKey');
        $date = $request->input('date');

        $userId = Auth::guard('pph')->user()->id;
        // $insertType = $request->input('insert_type');
        $formattedDate = (!empty($date) && $date !== '') ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

        $scidData = GotogoRegisteredParcel::where('spid_forfile_upload', $userId);

        if ($scidData) {
            $scidData->where('spid_file_upload_date', '=', $formattedDate);
            if ($searchKey) {
                $scidData->where(function ($query) use ($searchKey) {
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
                });
            }

            $scidResults = $scidData->get();
        }

        $mergedCollection =  $scidResults;

        return view('pph.gotogoRegistered.index', ['datas' => $mergedCollection]);
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


    public function view($id)

    {

        $data = GotogoRegisteredParcel::findorfail($id);

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

        return view('pph.gotogoRegistered.view', compact('data', 'rateDetails'));
    }



    public function edit(Request $request, $id)

    {

        $post = GotogoRegisteredParcel::findOrFail($id);



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

                return redirect()->route('pph.go-registered.index', ['insert_type' => $post->insert_type])->with('success', 'Parcel updated successfully!');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage())->withInput();
            }
        }



        $data = $post;

        $pickupDetails = PickupDetails::where('cms_id', Auth::guard('pph')->user()->id)

            ->get();



        return view('pph.gotogoRegistered.edit', compact('data', 'pickupDetails'));
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



                    GotogoRegisteredParcel::create([

                        'cms_id' => Auth::guard('pph')->user()->id,

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

                        'insert_type' =>  GotogoRegisteredParcel::INSERT_TYPE_BULK,

                    ]);



                    if ($request->barcode_option === "barcode_auto") {

                        $serviceTypeValue = GotogoRegisteredParcel::getServiceTypeDB(GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED);

                        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

                        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";



                        $franchiseId = Auth::guard('pph')->user()->id;

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

            return redirect()->route('pph.go-registered.index', ['insert_type' => GotogoRegisteredParcel::INSERT_TYPE_BULK])->with('success', 'Data added successfully');
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }



    public function excelUploadByPph(Request $request)

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


            GotogoRegisteredParcel::whereIn('barcode_no', $desiredBarcode)
                ->where(function ($query) {
                    $userId = Auth::guard('pph')->user()->id;
                    $query->whereNull('spid_forfile_upload')
                        ->orWhere('spid_forfile_upload', $userId);
                })
                ->update(['spid_forfile_upload' => Auth::guard('pph')->user()->id, 'spid_file_upload_date' => Carbon::today()->toDateString()]);

            $updatedParcels = GotogoRegisteredParcel::whereIn('barcode_no', $desiredBarcode)->get();

            return view('pph.gotogoRegistered.index', ['datas' => $updatedParcels]);
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }



    public function fullPrint($id)

    {

        $parcel = GotogoRegisteredParcel::findorfail($id);

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


        // return view('franchise.parcel.fullPrint', ['parcel' => $parcel, 'barcode' => $barcode]);

        $otherPageContent = View::make('print.gotogopost.fullPrint', ['parcel' => $parcel, 'barcode' => $barcode, 'rateDetails' => $rateDetails])->render();



        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }


    public function shortPrint(Request $request)

    {

        $date = $request->input('date');
        $searchKey = $request->input('searchKey');
        $userId = Auth::guard('pph')->user()->id;
        $formattedDate = (!empty($date) && $date !== '') ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

        $scidData = GotogoRegisteredParcel::where('spid_forfile_upload', $userId);

        if ($scidData) {
            $scidData->where('spid_file_upload_date', '=', $formattedDate);
            if ($searchKey) {
                $scidData->where(function ($query) use ($searchKey) {
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
                });
            }
            $scidResults = $scidData->get();
        }


        $mergedCollection = $scidResults;


        $refinedData = [];
        foreach ($mergedCollection as $data) {

            $franchiseId = $data->franchise_id;
            $franchise = Franchise::findOrFail($franchiseId);
            $linkDetail = GotogoLink::where('franchise_no', $franchise->franchise_no)->first();
            $refinedData[] = (object)[
                'barcode_image_src' => $data->barcode_image_src,
                'barcode_no'        => $data->barcode_no,
                'franchise_no'      => $linkDetail->franchise_no,
                'cms_no'            => $linkDetail->cms_no,
                'pph_no'            => $linkDetail->pph_no,
            ];
        }

        // Pass data to the view
        $otherPageContent = View::make('print.gotogopost.shortPrint', ['data' => $refinedData])->render();


        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }


    public function downloadTable(Request $request)

    {

        // Fetch data

        $searchKey = $request->input('searchKey');
        $date = $request->input('date');

        $userId = Auth::guard('pph')->user()->id;
        // $insertType = $request->input('insert_type');
        $formattedDate = (!empty($date) && $date !== '') ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

        $scidData = GotogoRegisteredParcel::where('spid_forfile_upload', $userId);

        if ($scidData) {
            $scidData->where('spid_file_upload_date', '=', $formattedDate);
            if ($searchKey) {
                $scidData->where(function ($query) use ($searchKey) {
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
                });
            }

            $scidResults = $scidData->get();
        }


        $parcels =  $scidResults;


        // Create a new Spreadsheet object

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();



        // Set the headings

        $headings = [

            'Sl',
            'Barcode',
            'Ref',
            'From Address',
            'ADD1',

            'ADD2',
            'ADD3',

            'Pincode',
            'City',
            'State',
            'Mobile',
            'Email',
            'To Address',
            'ADD1',
            'ADD2',

            'ADD3',

            'Pincode',
            'City',

            'State',
            'Mobile',
            'Email',
            'Weight',

        ];



        $column = 'A';

        foreach ($headings as $heading) {

            $sheet->setCellValue($column . '1', $heading);

            $column++;
        }



        // Populate the data

        $row = 2; // Start from the second row

        foreach ($parcels as $key => $parcel) {

            // Process pickup address

            $pickupAddressParts = explode(',', $parcel->pickup_address);

            $PADD1 = $pickupAddressParts[0] ?? '';

            $PADD2 = $pickupAddressParts[1] ?? $PADD1;

            $PADD3 = $pickupAddressParts[2] ?? $PADD2;



            $consigneeAddressParts = explode(',', $parcel->consignee_address);

            $CADD1 = $consigneeAddressParts[0] ?? '';

            $CADD2 = $consigneeAddressParts[1] ?? $CADD1;

            $CADD3 = $consigneeAddressParts[2] ?? $CADD2;





            // Set cell values

            $sheet->setCellValue('A' . $row, $key + 1);

            $sheet->setCellValue('B' . $row, $parcel->barcode_no);

            $sheet->setCellValue('C' . $row, GotogoRegisteredParcel::getServiceType(GotogoRegisteredParcel::SERVICE_TYPE_GOTO_POST_REGISTERED));

            $sheet->setCellValue('D' . $row, $parcel->pickup_address);

            $sheet->setCellValue('E' . $row, $PADD1);

            $sheet->setCellValue('F' . $row, $PADD2);

            $sheet->setCellValue('G' . $row, $PADD3);

            $sheet->setCellValue('H' . $row, $parcel->pickup_pincode);

            $sheet->setCellValue('I' . $row, $parcel->pickup_city);

            $sheet->setCellValue('J' . $row, $parcel->pickup_state);

            $sheet->setCellValue('K' . $row, $parcel->pickup_mobile);

            $sheet->setCellValue('L' . $row, $parcel->pickup_email);

            $sheet->setCellValue('M' . $row, $parcel->consignee_address);

            $sheet->setCellValue('N' . $row, $CADD1);

            $sheet->setCellValue('O' . $row, $CADD2);

            $sheet->setCellValue('P' . $row, $CADD3);

            $sheet->setCellValue('Q' . $row, $parcel->consignee_pincode);

            $sheet->setCellValue('R' . $row, $parcel->consignee_city);

            $sheet->setCellValue('S' . $row, $parcel->consignee_state);

            $sheet->setCellValue('T' . $row, $parcel->consignee_mobile);

            $sheet->setCellValue('U' . $row, $parcel->consignee_email);

            $sheet->setCellValue('V' . $row, $parcel->package_weight);



            $row++;
        }



        // Create a Writer

        $writer = new Xlsx($spreadsheet);



        // Create a response to stream the file

        $response = new StreamedResponse(function () use ($writer) {

            $writer->save('php://output');
        });



        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response->headers->set('Content-Disposition', 'attachment;filename="parcels.xlsx"');

        $response->headers->set('Cache-Control', 'max-age=0');



        return $response;
    }





    public function assignBarcode(Request $request)

    {

        $parcel = GotogoRegisteredParcel::where("barcode_no", $request->barcode)->first();


        if ($parcel) {

            return response()->json(['status' => 'success', 'data' => $parcel, "message" => "dublicate barcode"]);
        }



        if ($request->isMethod('post')) {

            try {

                $generator = new BarcodeGeneratorPNG();

                $barcode = $generator->getBarcode($request->barcode, $generator::TYPE_CODE_128);

                $barcode_image_src = base64_encode($barcode);

                $Model = GotogoRegisteredParcel::where("barcode_no", null)->orderBy('created_at', 'desc')->first();

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
        $trackingDetails = GotogoRegisteredTrackOrder::where('parcel_id', $id)->first();
        return response()->json([
            'trackingDetails' => $trackingDetails
        ]);
    }
}
