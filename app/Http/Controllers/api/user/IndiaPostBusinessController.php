<?php



namespace App\Http\Controllers\api\user;



use App\Http\Controllers\Controller;

use App\Models\IndiaPostBusinessParcel;

use App\Models\PickupDetails;

use App\Models\Franchise;

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





class IndiaPostBusinessController extends Controller

{


    public function getUniqueCode($franchiseId)

    {

        $serviceType = IndiaPostBusinessParcel::SERVICE_TYPE_INDIA_POST_BUSINESS;

        $randomNumber = rand(1, 9);

        $serviceTypeValue = IndiaPostBusinessParcel::getServiceTypeDB($serviceType);

        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

        $range_end_column = "parcel_barcode_range_end_{$serviceTypeValue}";

        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";


        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();


        if ($franchiseSeriesDetails) {

            if ($franchiseSeriesDetails->{$range_end_column} != null && $franchiseSeriesDetails->{$range_end_column} > $franchiseSeriesDetails->{$last_code_issued_column}) {

                if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                    $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                    $seriesNum = str_pad($last_parcel_code_issued + 1, 7, '0', STR_PAD_LEFT);
                } else {

                    $seriesNum = str_pad($franchiseSeriesDetails->{$range_start_column}, 7, '0', STR_PAD_LEFT);
                }

                $serviceCode = IndiaPostBusinessParcel::getServiceCodeForApi($serviceType, $franchiseId);

                $code = $serviceCode . FranchiseBarcodeSeries::$PARCELCODE . $seriesNum . $randomNumber . 'CO';

                return $code;
            } else {

                return 'Barcode series end';
            }
        } else {

            return 'Barcodes not assigned';
        }
    }


    public function store(Request $request)

    {

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

            'package_length' => 'required|numeric',

            'package_width' => 'required|numeric',

            'package_height' => 'required|numeric',

            'payment_method' => 'required|string',

        ]);


        if ($request->barcodeCode == 'Barcode series end' || $request->barcodeCode == 'Barcodes not assigned') {

            return response()->json(['status' => 400, 'message' => 'barcode series end', 'data' => $requestData]);
        }

        try {


            $generator = new BarcodeGeneratorPNG();
            $barcodeCode =   $this->getUniqueCode($request->franchise_id);

            $temp = $generator->getBarcode($barcodeCode, $generator::TYPE_CODE_128);
            $barcodeImageSrc = base64_encode($temp);

            if ($barcodeCode  == 'Barcode series end' || $barcodeCode  == 'Barcodes not assigned') {

                return response()->json(['status' => 400, 'message' => 'barcode series end', 'data' => $requestData]);
            }

            $addedData = IndiaPostBusinessParcel::create([

                'franchise_id' => $request->franchise_id,

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

                'payment_amount' => $request->payment_amount,

                'payment_method' => $request->payment_method,

                'barcode_no' =>  $barcodeCode,

                'barcode_image_src' =>  $barcodeImageSrc,

                'insert_type' =>  IndiaPostBusinessParcel::INSERT_TYPE_APP,

            ]);


            $serviceTypeValue = IndiaPostBusinessParcel::getServiceTypeDB(IndiaPostBusinessParcel::SERVICE_TYPE_INDIA_POST_BUSINESS);


            $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

            $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";

            $franchiseId = $request->franchise_id;

            $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();


            if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                $seriesNum = $last_parcel_code_issued + 1;
            } else {

                $seriesNum = $franchiseSeriesDetails->{$range_start_column};
            }



            $franchiseSeriesDetails->{$last_code_issued_column} = $seriesNum;

            $franchiseSeriesDetails->save();


            $franchiseBarcode = new FranchiseBarcodes;
            $franchiseBarcode->barcodes = $barcodeCode;
            $franchiseBarcode->franchise_barcodeseries_id = $franchiseSeriesDetails->id;
            $franchiseSeriesDetails->id;
            $franchiseBarcode->save();

            return response()->json(['status' => 200, 'message' => 'new parcel added', 'data' => $addedData]);
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }

    public function view($id)

    {

        $data = IndiaPostBusinessParcel::findorfail($id);

        $generator = new BarcodeGeneratorPNG();

        $code = $data->barcode_no;

        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        $barcode = base64_encode($barcode);



        return view('franchise.indiaPost-businessParcel.view', compact('data', 'barcode', 'code'));
    }



    public function edit(Request $request, $id)

    {

        $post = IndiaPostBusinessParcel::findOrFail($id);



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

                'package_length' => 'required|numeric',

                'package_width' => 'required|numeric',

                'package_height' => 'required|numeric',

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



                return redirect()->route('franchise.india-post-business.index', ['insert_type' => $post->insert_type])->with('success', 'Parcel updated successfully!');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage())->withInput();
            }
        }



        $data = $post;

        $pickupDetails = PickupDetails::where('franchise_id', Franchise::getFranchiseId())

            ->get();



        return view('franchise.indiaPost-businessParcel.edit', compact('data', 'pickupDetails'));
    }



    public function delete($id)

    {

        try {

            $post = IndiaPostBusinessParcel::findorfail($id)->delete();

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



    public function storeByFile(Request $request)

    {



        if (!$request->hasFile('file')) {

            return back()->with('error', 'No file uploaded.');
        }



        try {

            // Move the uploaded file to the destination path

            $file = $request->file('file');

            $destinationPath = public_path('tenancy/assets/franchise/RoleUser/');

            $fileName = uniqid() . '_' . $file->getClientOriginalName();

            $file->move($destinationPath, $fileName);



            // Load the Excel file

            $filePath = public_path("tenancy/assets/franchise/RoleUser/$fileName");

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



            $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", Franchise::getFranchiseId())->first();



            $serviceType = IndiaPostBusinessParcel::SERVICE_TYPE_INDIA_POST_BUSINESS;

            $serviceTypeValue = IndiaPostBusinessParcel::getServiceTypeDB($serviceType);



            $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

            $range_end_column = "parcel_barcode_range_end_{$serviceTypeValue}";





            $range = $franchiseSeriesDetails->{$range_end_column} - $franchiseSeriesDetails->{$range_start_column};

            if ($franchiseSeriesDetails->{$range_end_column} == null || $range < count($data)) {

                return redirect()->back()->with('error', 'number of data in excel file exceeds barode series available for this service');
            }

            $client = new Client();

            $downloadDirectory = public_path("tenancy/assets/franchise/RoleUser/");



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

                    $generator = new BarcodeGeneratorPNG();

                    $code =   $this->getUniqueCode();

                    $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

                    $barcode = base64_encode($barcode);



                    IndiaPostBusinessParcel::create([

                        'franchise_id' => Franchise::getFranchiseId(),

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

                        'insert_type' =>  IndiaPostBusinessParcel::INSERT_TYPE_BULK,

                    ]);



                    $serviceTypeValue = IndiaPostBusinessParcel::getServiceTypeDB(IndiaPostBusinessParcel::SERVICE_TYPE_INDIA_POST_BUSINESS);

                    $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

                    $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";



                    $franchiseId = Franchise::getFranchiseId();

                    $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();

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
                } catch (\Exception $e) {

                    return back()->with('error', $e->getMessage());
                }
            }

            return redirect()->route('franchise.india-post-business.index', ['insert_type' => IndiaPostBusinessParcel::INSERT_TYPE_BULK])->with('success', 'Data added successfully');
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }





    public function fullPrint($id)

    {

        $parcel = IndiaPostBusinessParcel::findorfail($id);

        $generator = new BarcodeGeneratorPNG();

        $code = $parcel->barcode_no;

        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        $barcode = base64_encode($barcode);



        // return view('franchise.parcel.fullPrint', ['parcel' => $parcel, 'barcode' => $barcode]);

        $otherPageContent = View::make('franchise.indiaPost-businessParcel.fullPrint', ['parcel' => $parcel, 'barcode' => $barcode])->render();



        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }





    public function shortPrint(Request $request)

    {



        $parcels = IndiaPostBusinessParcel::where('franchise_id', Franchise::getFranchiseId())

            ->where('insert_type', $request->insert_type)

            ->whereDate('created_at', Carbon::today())

            ->orderBy('created_at', 'desc')

            ->get();





        // return view('franchise.parcel.shortPrint', ['data' =>  $data, 'barcode' => $barcode]);

        $otherPageContent = View::make('franchise.indiaPost-businessParcel.shortPrint', ['data' => $parcels])->render();



        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }





    // public function downloadTable(Request $request, $id)

    // {



    //     $query = Parcel::where('franchise_id', Franchise::getFranchiseId())

    //         ->where('service_type', $id)

    //         ->orderBy('created_at', 'desc');



    //     $query->whereDate('created_at', Carbon::today());

    //     $data = $query->get();

    //     return $data;

    // }





    public function downloadTable(Request $request)

    {

        // Fetch data

        $parcels = IndiaPostBusinessParcel::where('franchise_id', Franchise::getFranchiseId())

            ->where('insert_type', $request->insert_type)

            ->whereDate('created_at', Carbon::today())

            ->orderBy('created_at', 'desc')

            ->get();



        // Create a new Spreadsheet object

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();



        // Set the headings

        $headings = [

            'Sl', 'Barcode', 'Ref', 'From Address', 'ADD1',

            'ADD2', 'ADD3',

            'Pincode', 'City', 'State',  'Mobile', 'Email', 'To Address', 'ADD1', 'ADD2',

            'ADD3',

            'Pincode', 'City',

            'State', 'Mobile', 'Email', 'Weight',

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

            $sheet->setCellValue('C' . $row, IndiaPostBusinessParcel::getServiceType(IndiaPostBusinessParcel::SERVICE_TYPE_INDIA_POST_BUSINESS));

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
}
