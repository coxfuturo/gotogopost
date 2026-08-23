<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CodGotogoBusinessParcel;
use App\Models\ECustomer;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoBusinessTrackOrder;
use Carbon\Carbon;
use  App\Http\Controllers\franchise\RateCalculator;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\View;
use PDF;

class BookingController extends Controller
{

    protected $rateCalculator;

    public function __construct(RateCalculator $rateCalculator)
    {
        $this->rateCalculator = $rateCalculator;
    }

   public function index(Request $request)
{
    $id = Auth::guard('customer')->id();
    $searchKey = $request->input('searchKey');
    $date = $request->input('date');
    $perPage = 5; // हर पेज पर 20 रिकॉर्ड

    $query = GotogoBusinessParcel::where('cod_customer_id', $id)
        ->where('insert_type', 1)
        ->where('payment_method', 'cod')
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
    }

    if ($date) {
        $date = Carbon::createFromFormat('d-m-Y', $date)->startOfDay()->toDateString();
        $query->whereDate('created_at', '=', $date);
    } else {
        $query->whereDate('created_at', Carbon::today());
    }

    // Pagination जोड़ें
    $datas = $query->paginate($perPage);

    return view('customer.parcel.index', compact('datas'));
}

public function prepaids(Request $request)
{
    $id = Auth::guard('customer')->id();
    $searchKey = $request->input('searchKey');
    $date = $request->input('date');
    $perPage = 5; // हर पेज पर 20 रिकॉर्ड

    $query = GotogoBusinessParcel::where('cod_customer_id', $id)
        ->where('insert_type', 1)
        ->where('payment_method', 'prepaid')
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
    }

    if ($date) {
        $date = Carbon::createFromFormat('d-m-Y', $date)->startOfDay()->toDateString();
        $query->whereDate('created_at', '=', $date);
    } else {
        $query->whereDate('created_at', Carbon::today());
    }

    
    $datas = $query->paginate($perPage);

    return view('customer.parcel.index', compact('datas'));
}

      public function downloadTableForCreatedTable(Request $request)

    {
        // Fetch data
        $id= Auth::guard('customer')->id();
        $date = $request->input('date');
        $searchKey = $request->input('searchKey');

        $query = GotogoBusinessParcel::where('cod_customer_id', $id)

            ->where('insert_type', 1)

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

            $sheet->setCellValue('C' . $row, GotogoBusinessParcel::getServiceType(GotogoBusinessParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL));

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

     public function view($id)

    {

        $data = GotogoBusinessParcel::findorfail($id);

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

        return view('customer.parcel.view', compact('data', 'rateDetails'));
    }

    public function fullPrint($id)

    {

        $parcel = GotogoBusinessParcel::findorfail($id);

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


        $otherPageContent = View::make('print.gotogopost.fullPrint', ['parcel' => $parcel, 'barcode' => $barcode, 'rateDetails' => $rateDetails])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }

    public function trackOrder($id)

    {
        $trackingDetails = GotogoBusinessTrackOrder::where('parcel_id', $id)->first();
        return response()->json([
            'trackingDetails' => $trackingDetails
        ]);
    }
    
    
    
    
    
    
   public function shortPrintForCreatedParcel(Request $request)
{
    $ids = $request->ids ?? [];
    
    if(empty($ids)) {
        return response()->json([
            'otherPageContent' => '<p>No parcels selected.</p>'
        ]);
    }

    $data = \App\Models\Booking::whereIn('id', $ids)->get();

    // Check if data exists
    if($data->isEmpty()){
        return response()->json([
            'otherPageContent' => '<p>No matching data found.</p>'
        ]);
    }

    $title = "India Post Speed";

    $html = view('print.indiaPost.shortPrint', compact('data', 'title'))->render();

    return response()->json(['otherPageContent' => $html]);
}




}
