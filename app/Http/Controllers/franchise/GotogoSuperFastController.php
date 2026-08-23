<?php
namespace App\Http\Controllers\franchise;
use App\Http\Controllers\Controller;
use App\Models\GotogoSuperFastParcel;
use App\Models\PickupDetails;
use App\Models\Franchise;
use App\Models\GotogoLink;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\File;

use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Symfony\Component\HttpFoundation\StreamedResponse;

use GuzzleHttp\Client;

use Picqer\Barcode\BarcodeGeneratorPNG;


use App\Models\FranchiseBarcodeSeries;

use App\Models\FranchiseBarcodes;

use Illuminate\Support\Facades\View;

use Carbon\Carbon;

use PDF;

use Illuminate\Support\Facades\Mail;

use App\Mail\ParcelMail;

use  App\Http\Controllers\franchise\RateCalculator;

use Config;


class GotogoSuperFastController extends Controller

{


    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (Auth::guard('franchise')->check()) {
                $user = Auth::guard('franchise')->user();
            } elseif (Auth::guard('franchiseRoleUser')->check()) {
                $user = Auth::guard('franchiseRoleUser')->user();
            } else {
                return abort(403, 'Unauthorized.');
            }

            $action = $request->route()->getActionMethod();

            $actionToPermissionMap = [
                'index' => 'Bookings-view',
                'create' => 'Bookings-create',
                'store' => 'Bookings-create',
                'edit' => 'Bookings-edit',
                'delete' => 'Bookings-delete',
            ];

            if (!array_key_exists($action, $actionToPermissionMap)) {
                return $next($request);
            }

            if (array_key_exists($action, $actionToPermissionMap)) {
                $requiredPermission = $actionToPermissionMap[$action];

                // if (!$user->hasPermissionTo($requiredPermission, 'franchise')) {

                //     abort(403, 'You do not have permission to perform this action.');
                // }
            }
            return $next($request);
        });
    }




    public function getUniqueCode()

    {




        // FranchiseBarcodeSeries::query()->truncate();

        // return FranchiseBarcodeSeries::all();

        // $test = new FranchiseBarcodeSeries;

        // $test->series_id = "234567";

        // $test->franchise_id = Franchise::getFranchiseId();

        // $test->save();

        // dd("done");



        $serviceType = GotogoSuperFastParcel::SERVICE_TYPE_GOTO_POST_SUPERFAST;

        $randomNumber = rand(1, 9);

        $serviceTypeValue = GotogoSuperFastParcel::getServiceTypeDB($serviceType);

        $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

        $range_end_column = "parcel_barcode_range_end_{$serviceTypeValue}";

        $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";



        $franchiseId = Franchise::getFranchiseId();

        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();


        if ($franchiseSeriesDetails) {

            if ($franchiseSeriesDetails->{$range_end_column} != null && $franchiseSeriesDetails->{$range_end_column} > $franchiseSeriesDetails->{$last_code_issued_column}) {

                if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                    $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                    $seriesNum = str_pad($last_parcel_code_issued + 1, 8, '0', STR_PAD_LEFT);
                } else {

                    $seriesNum = str_pad($franchiseSeriesDetails->{$range_start_column}, 8, '0', STR_PAD_LEFT);
                }

                $serviceCode = GotogoSuperFastParcel::getServiceCode($serviceType);

                $code = $serviceCode . FranchiseBarcodeSeries::$PARCELCODE . $seriesNum . $randomNumber . 'CO';

                return $code;
            } else {

                return 'Barcode series end';
            }
        } else {

            return 'Barcodes not assigned';
        }
    }



    public function index(Request $request, RateCalculator $rateCalculator)

    {

        $searchKey = $request->input('searchKey');

        $date = $request->input('date');

        $query = GotogoSuperFastParcel::where('franchise_id', Franchise::getFranchiseId())

            ->where('insert_type', $request->insert_type)

            ->orderBy('created_at', 'desc');

        if ($searchKey) {

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

            $datas = $query->get();

            return view('franchise.gotogoSuperFast.index', compact('datas'));
        }

        if ($date) {
            $date = Carbon::createFromFormat('d-m-Y', $date)->startOfDay()->toDateString();
            $query->whereDate('created_at', '=', $date);
            $datas = $query->get();
            return view('franchise.gotogoSuperFast.index', compact('datas'));
        } else {

            $query->whereDate('created_at', Carbon::today());
            $datas = $query->get();

            return view('franchise.gotogoSuperFast.index', compact('datas'));
        }
    }


    public function getPrice($origin, $destination, $weight, $fuel_charge = 0, $pickup_charge = 0, $other_service_charge = 0)

    {
        $newrequest = new Request([
            'originPincode' => $origin,
            'destinationPincode' => $destination,
            'packageWeight' => $weight,
            'fuel_charge' => $fuel_charge,
            'pickup_charge' => $pickup_charge,
            'other_service_charge' => $other_service_charge,
            'service_type' => GotogoSuperFastParcel::SERVICE_TYPE_GOTO_POST_SUPERFAST,
        ]);
        $rateCalculator = new RateCalculator();
        $result = $rateCalculator->calculate($newrequest);
        return $result;
    }


    public function showPrice(Request $request, RateCalculator $rateCalculater)

    {

        if ($request->isMethod('post')) {

            try {

                if ($request->amount) {
                    $result = $rateCalculater->calculateByAmount($request);
                    return response()->json(['status' => 'success', 'data' => $result, 'message' => "data fetched successfully"]);
                } else {

                    $from = $request->from;
                    $to = $request->to;
                    $weight = $request->weight;
                    $fuel_charge = $request->fuel_charge ?? 0;
                    $pickup_charge = $request->pickup_charge ?? 0;
                    $other_service_charge = $request->other_service_charge ?? 0;
                    $result = $this->getPrice($from, $to, $weight, $fuel_charge, $pickup_charge, $other_service_charge);

                    return response()->json(['status' => 'success', 'data' => $result, 'message' => "data fetched successfully"]);
                }
            } catch (\Throwable $th) {

                return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
            }
        }



        return response()->json(['status' => 'error', 'message' => 'Invalid request method'], 400);
    }



    public function create()

    {

        $franchise_details = Franchise::where('id', Franchise::getFranchiseId())->select('franchise_no', 'wallet_balance', 'remaining_balance','gst_number')->first();
        $linkDetail = GotogoLink::where('franchise_no', $franchise_details->franchise_no)->first();
        $generator = new BarcodeGeneratorPNG();

        $code =   $this->getUniqueCode();

        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        $barcode = base64_encode($barcode);

        $pickupDetails = PickupDetails::where('franchise_id', Franchise::getFranchiseId())

            ->get();

        return view('franchise.gotogoSuperFast.create', [
            'pickupDetails' => $pickupDetails,
            'barcode' => $barcode,
            'code' => $code,
            'franchise_details' => $franchise_details,
            'linkDetails' => $linkDetail
        ]);
    }


    public function savePdf($parcel)
    {
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

        $pdf = PDF::loadView('franchise.mail.pdfview', ['parcel' => $parcel, 'barcode' => $barcode, 'rateDetails' => $rateDetails]);
        $destinationPath = public_path('tenancy/assets/franchise/Pdf/');

        $fileName = uniqid() . '_parcel_' . '.pdf';
        $filePath = $destinationPath . $fileName;
        $pdf->save($filePath);

        return $filePath;
    }




    public function store(Request $request, RateCalculator $rateCalculater)

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
            'ConsigneeGstNo' => 'required|string',

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

        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);

        $from = $request->PickupPincode;
        $to = $request->ConsigneePincode;
        $weight = $request->package_weight;
        $fuel_charge = $request->fuel_charge ?? 0;
        $pickup_charge = $request->pickup_charge ?? 0;
        $other_service_charge = $request->other_service_charge ?? 0;
        $rateDetails = $this->getPrice($from, $to, $weight, $fuel_charge, $pickup_charge, $other_service_charge);

        if ($request->amount) {

            $amount = $request->amount ?? 0;
            $price = $amount + $fuel_charge + $pickup_charge + $other_service_charge;
            $gst = number_format(($price) * 0.18, 2, '.', '');
            $total = $price + $gst;
            $payment_amount =  $total;
        } else {

            $payment_amount = $rateDetails['total'];
        }


        if ($rateDetails['status'] == 'fail') {

            return response()->json(['status' => 400, 'message' => $rateDetails['message'], 'data' => $requestData]);
        }

        if ($request->barcodeCode == 'Barcode series end' || $request->barcodeCode == 'Barcodes not assigned') {

            return response()->json(['status' => 400, 'message' => 'barcode series end', 'data' => $requestData]);
        }


        $ramaining_balace = $franchise->remaining_balance;

        if ($ramaining_balace - $payment_amount < 0) {
            return response()->json(['status' => 400, 'message' => 'Balance Low', 'data' => $requestData]);
        }

        try {


            $franchise_role_user_id = null;
            if (Auth::guard('franchiseRoleUser')->user()) {
                $franchise_role_user_id = Auth::guard('franchiseRoleUser')->user()->id;
            }


            $parcel = GotogoSuperFastParcel::create([

                'franchise_id' => Franchise::getFranchiseId(),

                'franchise_role_users_id' => $franchise_role_user_id,

                'pickup_name' => $request->PickupName,

                'pickup_mobile' => $request->PickupMobile,
                'pickup_gst_number' => $request->PickupGstNo,

                'pickup_email' => $request->PickupEmail,

                'pickup_pincode' => $request->PickupPincode,

                'pickup_city' => $request->PickupCity,

                'pickup_state' => $request->PickupState,

                'pickup_address' => $request->PickupAddress,

                'consignee_name' => $request->ConsigneeName,

                'consignee_mobile' => $request->ConsigneeMobile,
                'consignee_gst_number' => $request->ConsigneeGstNo,

                'consignee_email' => $request->ConsigneeEmail,

                'consignee_pincode' => $request->ConsigneePincode,

                'consignee_city' => $request->ConsigneeCity,

                'consignee_state' => $request->ConsigneeState,

                'consignee_address' => $request->ConsigneeAddress,

                'package_weight' => $request->package_weight,

                'package_length' => $request->package_length,

                'package_width' => $request->package_width,

                'package_height' => $request->package_height,

                'fuel_charge' => $request->fuel_charge,
                'pickup_charge' => $request->pickup_charge,
                'other_service_charge' => $request->other_service_charge,

                'payment_amount' => $payment_amount,

                'payment_method' => $request->payment_method,

                'barcode_no' =>  $request->barcodeCode,

                'barcode_image_src' =>  $request->barcodeImageSrc,

                'insert_type' =>  GotogoSuperFastParcel::INSERT_TYPE_SINGLE,

            ]);





            $serviceTypeValue = GotogoSuperFastParcel::getServiceTypeDB(GotogoSuperFastParcel::SERVICE_TYPE_GOTO_POST_SUPERFAST);

            $range_start_column = "parcel_barcode_range_start_{$serviceTypeValue}";

            $last_code_issued_column = "last_parcel_code_issued_{$serviceTypeValue}";



            $commission = $rateCalculater->calculateCommissionForGotogoPost($request->package_weight, 'franchise', 2);

            $franchise->decrement('remaining_balance', $payment_amount);
            $franchise->increment('commission', $commission);
            $franchise->increment('remaining_balance', $commission);
            $franchise->save();

            $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();

            if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                $seriesNum = $last_parcel_code_issued + 1;
            } else {

                $seriesNum = $franchiseSeriesDetails->{$range_start_column};
            }


            // //=====================================

            // Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
            // Config::set('mail.mailers.smtp.port', 587);
            // Config::set('mail.mailers.smtp.username', 'snehalsharan10@gmail.com');
            // Config::set('mail.mailers.smtp.password', 'aipn fdol xxjb rshv');
            // Config::set('mail.mailers.smtp.encryption', 'tls');
            // Config::set('mail.from.address', 'deferfe1214@gmail.com');
            // Config::set('mail.from.name', 'gotogopost');

            // //=====================================


            // ======================================

            $savedPdfFilePath = $this->savePdf($parcel);

            Mail::to($request->PickupEmail)->send(new ParcelMail([$savedPdfFilePath, $parcel]));

            // ======================================


            $franchiseSeriesDetails->{$last_code_issued_column} = $seriesNum;
            $franchiseSeriesDetails->save();
            $franchiseBarcode = new FranchiseBarcodes;
            $franchiseBarcode->barcodes = $request->barcodeCode;
            $franchiseBarcode->franchise_barcodeseries_id = $franchiseSeriesDetails->id;
            $franchiseBarcode->save();
            $generator = new BarcodeGeneratorPNG();
            $code =   $this->getUniqueCode();
            $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
            $barcode = base64_encode($barcode);
            $otherPageContent = View::make('franchise.gotogoSuperFast.shortPrintWhileBooking', ['parcel' => $parcel])->render();
            return response()->json(['status' => 200, 'message' => 'new parcel added', 'data' => $requestData, 'code' => $code, 'otherPageContent' => $otherPageContent, 'barcodeImageSrc' => $barcode, 'franchiseDetails' => $franchise]);
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }



    public function view($id)

    {

        $data = GotogoSuperFastParcel::findorfail($id);

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

        return view('franchise.gotogoSuperFast.view', compact('data', 'rateDetails'));
    }



    public function edit(Request $request, $id)

    {

        $post = GotogoSuperFastParcel::findOrFail($id);

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



                return redirect()->route('franchise.go-super-fast-parcel.index', ['insert_type' => $post->insert_type])->with('success', 'Parcel updated successfully!');
            } catch (\Exception $th) {

                return back()->with('error', $th->getMessage())->withInput();
            }
        }



        $data = $post;

        $pickupDetails = PickupDetails::where('franchise_id', Franchise::getFranchiseId())

            ->get();



        return view('franchise.gotogoSuperFast.edit', compact('data', 'pickupDetails'));
    }



    public function delete($id)

    {

        try {

            GotogoSuperFastParcel::findorfail($id)->delete();

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



    public function storeByFile(Request $request, RateCalculator $rateCalculater)

    {

        if (!$request->hasFile('file')) {

            return back()->with('error', 'No file uploaded.');
        }

        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);

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
            $serviceType = GotogoSuperFastParcel::SERVICE_TYPE_GOTO_POST_SUPERFAST;

            $serviceTypeValue = GotogoSuperFastParcel::getServiceTypeDB($serviceType);

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

                if ($rateDetails['status'] == 'fail') {

                    return back()->with('error', $rateDetails['message']);
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

                    $from = $element['Pickup Pincode'];
                    $to = $element['Consignee Pincode'];
                    $weight = $element['Package Weight'];
                    $fuel_charge = $element['Fuel Charge'] ?? 0;
                    $pickup_charge = $element['Pickup Charge'] ?? 0;
                    $other_service_charge = $element['Other service Charge'] ?? 0;
                    $rateDetails = $this->getPrice($from, $to, $weight, $fuel_charge, $pickup_charge, $other_service_charge);

                    if (isset($element['Amount'])) {
                        $amount = $element['Amount'] ?? 0;
                        $price = $amount + $fuel_charge + $pickup_charge + $other_service_charge;
                        $gst = number_format(($price) * 0.18, 2, '.', '');
                        $total = $price + $gst;
                        $payment_amount =  $total;
                    } else {

                        $payment_amount = $rateDetails['total'];
                    }

                    if ($franchise->remaining_balance - $payment_amount < 0) {

                        return back()->with('error', 'Balance Low');
                    }


                    $parcel = GotogoSuperFastParcel::create([

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

                        'fuel_charge' => $fuel_charge,
                        'pickup_charge' => $pickup_charge,
                        'other_service_charge' => $other_service_charge,

                        'payment_amount' => $payment_amount,

                        'barcode_no' =>  $code,

                        'barcode_image_src' =>  $barcode,

                        'insert_type' =>  GotogoSuperFastParcel::INSERT_TYPE_BULK,

                    ]);


                    $commission = $rateCalculater->calculateCommissionForGotogoPost($element['Package Weight'], 'franchise', GotogoSuperFastParcel::SERVICE_TYPE_GOTO_POST_SUPERFAST);

                    $franchiseId = Franchise::getFranchiseId();
                    $franchise = Franchise::findOrFail($franchiseId);

                    $franchise->decrement('remaining_balance', $payment_amount);
                    $franchise->increment('commission', $commission);
                    $franchise->increment('remaining_balance', $commission);
                    $franchise->save();


                    if ($request->barcode_option === "barcode_auto") {

                        $serviceTypeValue = GotogoSuperFastParcel::getServiceTypeDB(GotogoSuperFastParcel::SERVICE_TYPE_GOTO_POST_SUPERFAST);

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
                    }


                    // ======================================

                    $savedPdfFilePath = $this->savePdf($parcel);
                    Mail::to($element['Pickup Email'])->send(new ParcelMail([$savedPdfFilePath, $parcel]));

                    // ======================================
                } catch (\Exception $e) {

                    return back()->with('error', $e->getMessage());
                }
            }

            return redirect()->route('franchise.go-super-fast-parcel.index', ['insert_type' => GotogoSuperFastParcel::INSERT_TYPE_BULK])->with('success', 'Data added successfully');
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }



    public function fullPrint($id)

    {

        $parcel = GotogoSuperFastParcel::findorfail($id);

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


        $otherPageContent = View::make('franchise.gotogoSuperFast.fullPrint', ['parcel' => $parcel, 'barcode' => $barcode, 'rateDetails' => $rateDetails])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }





    public function shortPrintForCreatedParcel(Request $request)

    {
        $date = $request->input('date');
        $searchKey = $request->input('searchKey');
        $query = GotogoSuperFastParcel::where('franchise_id', Franchise::getFranchiseId())

            ->where('insert_type', $request->insert_type)

            ->orderBy('created_at', 'desc');


        if ($searchKey) {

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

            $parcels = $query->get();

            $otherPageContent = View::make('franchise.gotogoSuperFast.shortPrint', ['data' => $parcels])->render();
            return response()->json([

                'otherPageContent' => $otherPageContent

            ]);
        }



        if ($date) {

            $date = Carbon::createFromFormat('d-m-Y', $date)->startOfDay()->toDateString();
            $query->whereDate('created_at', '=', $date);
            $parcels = $query->get();
        } else {
            $query->whereDate('created_at', Carbon::today());
            $parcels = $query->get();
        }

        $otherPageContent = View::make('franchise.gotogoSuperFast.shortPrint', ['data' => $parcels])->render();


        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }







    public function downloadTableForCreatedTable(Request $request)

    {
        // Fetch data
        $date = $request->input('date');
        $searchKey = $request->input('searchKey');

        $query = GotogoSuperFastParcel::where('franchise_id', Franchise::getFranchiseId())

            ->where('insert_type', $request->insert_type)

            ->orderBy('created_at', 'desc');

        if ($date) {

            $date = Carbon::createFromFormat('d-m-Y', $date)->startOfDay()->toDateString();
            $query->whereDate('created_at', '=', $date);
            $parcels = $query->get();
        } else {
            $query->whereDate('created_at', Carbon::today());
            $parcels = $query->get();
        }




        if ($searchKey) {

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

            $parcels = $query->get();
        }






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

            $sheet->setCellValue('C' . $row, GotogoSuperFastParcel::getServiceType(GotogoSuperFastParcel::SERVICE_TYPE_GOTO_POST_SUPERFAST));

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



        $parcel = GotogoSuperFastParcel::where("barcode_no", $request->barcode)->first();



        if ($parcel) {

            return response()->json(['status' => 'success', 'data' => $parcel, "message" => "dublicate barcode"]);
        }



        if ($request->isMethod('post')) {

            try {



                $generator = new BarcodeGeneratorPNG();

                $barcode = $generator->getBarcode($request->barcode, $generator::TYPE_CODE_128);

                $barcode_image_src = base64_encode($barcode);

                $Model = GotogoSuperFastParcel::where("barcode_no", null)->orderBy('created_at', 'desc')->first();

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


    public function showReceivedParcelList(Request $request)
    {

        $searchKey = $request->input('searchKey');
        $date = $request->input('date');

        $userId = Franchise::getFranchiseId();
        // $insertType = $request->input('insert_type');
        $formattedDate = (!empty($date) && $date !== '') ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

        $scidData = GotogoSuperFastParcel::where('sfid_forfile_upload', $userId);

        if ($scidData) {
            $scidData->where('sfid_file_upload_date', '=', $formattedDate);
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

        $dcidData = GotogoSuperFastParcel::where('dfid_forfile_upload', $userId);

        if ($dcidData) {
            $dcidData->where('dfid_file_upload_date', '=', $formattedDate);

            if ($searchKey) {
                $dcidData->where(function ($query) use ($searchKey) {
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

            $dcidResults = $dcidData->get();
        }
        $mergedCollection = $scidResults->merge($dcidResults);

        return view('franchise.gotogoSuperFast.showReceivedParcelList', ['datas' => $mergedCollection]);
    }


    public function excelUploadByFranchise(Request $request)

    {

        if (!$request->hasFile('file')) {

            return back()->with('error', 'No file uploaded.');
        }

        try {


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

            $desiredBarcode = [];
            foreach ($data as $key => $value) {
                $desiredBarcode[] = $value['Barcode'];
            }


            GotogoSuperFastParcel::whereIn('barcode_no', $desiredBarcode)
                ->where(function ($query) {
                    $userId = Franchise::getFranchiseId();
                    $query->whereNull('scid_forfile_upload')
                        ->orWhere('scid_forfile_upload', $userId);
                })
                ->update(['sfid_forfile_upload' => Franchise::getFranchiseId(), 'sfid_file_upload_date' => Carbon::today()->toDateString()]);

            GotogoSuperFastParcel::whereIn('barcode_no', $desiredBarcode)
                ->whereNotNull('sfid_forfile_upload')
                ->where('sfid_forfile_upload', '!=', Franchise::getFranchiseId())
                ->update(['dfid_forfile_upload' => Franchise::getFranchiseId(), 'dfid_file_upload_date' => Carbon::today()->toDateString()]);


            $updatedParcels = GotogoSuperFastParcel::whereIn('barcode_no', $desiredBarcode)->get();

            return view('franchise.gotogoSuperFast.showReceivedParcelList', ['datas' => $updatedParcels]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function downloadTableOfReceivedParcel(Request $request)

    {

        // Fetch data
        $searchKey = $request->input('searchKey');
        $date = $request->input('date');

        $userId = Franchise::getFranchiseId();
        // $insertType = $request->input('insert_type');
        $formattedDate = (!empty($date) && $date !== '') ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

        $scidData = GotogoSuperFastParcel::where('sfid_forfile_upload', $userId);

        if ($scidData) {
            $scidData->where('sfid_file_upload_date', '=', $formattedDate);
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

        $dcidData = GotogoSuperFastParcel::where('dfid_forfile_upload', $userId);

        if ($dcidData) {
            $dcidData->where('dfid_file_upload_date', '=', $formattedDate);

            if ($searchKey) {
                $dcidData->where(function ($query) use ($searchKey) {
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

            $dcidResults = $dcidData->get();
        }
        $parcels = $scidResults->merge($dcidResults);


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

            $sheet->setCellValue('C' . $row, GotogoSuperFastParcel::getServiceType(GotogoSuperFastParcel::SERVICE_TYPE_GOTO_POST_SUPERFAST));

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


    public function shortPrintForReceivedParcel(Request $request)

    {

        $date = $request->input('date');
        $searchKey = $request->input('searchKey');
        $userId = Franchise::getFranchiseId();

        $formattedDate = (!empty($date) && $date !== '') ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->toDateString();

        $scidData = GotogoSuperFastParcel::where('sfid_forfile_upload', $userId);

        if ($scidData) {
            $scidData->where('sfid_file_upload_date', '=', $formattedDate);
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

        $dcidData = GotogoSuperFastParcel::where('dfid_forfile_upload', $userId);

        if ($dcidData) {
            $dcidData->where('dfid_file_upload_date', '=', $formattedDate);

            if ($searchKey) {
                $dcidData->where(function ($query) use ($searchKey) {
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

            $dcidResults = $dcidData->get();
        }

        $mergedCollection = $scidResults->merge($dcidResults);

        // Pass data to the view
        $otherPageContent = View::make('franchise.gotogoSuperFast.shortPrint', ['data' => $mergedCollection])->render();


        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }
}
