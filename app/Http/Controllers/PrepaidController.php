<?php

namespace App\Http\Controllers;
use App\Models\NoRegisterCustomer;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\IndiaPostBusinessParcel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Config;
use Illuminate\Support\Facades\File;

use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Picqer\Barcode\BarcodeGeneratorPNG;

use App\Models\FranchiseBarcodeSeries;

use App\Models\FranchiseBarcodes;

class PrepaidController extends Controller
{
     public function index(Request $request, $type)
{
    $searchKey = $request->input('searchKey');
    $date = $request->input('date');
    $data['type'] = $type;
    $id = auth()->guard('prepaid')->id();
    $data['id'] =  $id;

    $baseQuery = null;

    if ($type == 1) {
        $baseQuery = GotogoSpeedPostParcel::with('noregister');
    } elseif ($type == 2) {
        $baseQuery = GotogoBusinessParcel::with('noregister');
    } elseif ($type == 3) {
        $baseQuery = GotogoRegisteredParcel::with('noregister');
    } elseif ($type == 4) {
        $baseQuery = IndiaPostSpeedPostParcel::with('noregister');
    } elseif ($type == 5) {
        $baseQuery = IndiaPostBusinessParcel::with('noregister');
    }

    if ($baseQuery) {
       $baseQuery->where('no_r_customer_id', $id)
    ->whereHas('noregister', function ($query) use ($id) {
        $query->where('id', $id);
    });


                  
        // Add searchKey filter (e.g. on recipient_name)
        if ($searchKey) {
            $baseQuery->where(function ($query) use ($searchKey) {
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

      if ($date) {
          $parsedDate = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
          $baseQuery->whereDate('created_at', $parsedDate);
     } else {
       // If no date is selected, default to today
       $baseQuery->whereDate('created_at', Carbon::today());
     }
         $baseQuery->where('status','!=',2);
             $clonedQuery = clone $baseQuery;
        $data['datas'] = $baseQuery->get();
        $data['count'] = $clonedQuery->count();
        $data['total_amount'] = $clonedQuery->sum('payment_amount');
        $data['type'] = $type; 
    } else {
        $data['datas'] = collect(); 
        $data['count'] = 0;
        $data['total_amount'] = 0;
        
    }
  
    return view('prepaid.parcel.index', $data);
}

 public function shortPrintForCreatedParcel(Request $request)
{
    $fromdate = $request->input("fromdate");
    $todate = $request->input("todate");
    $searchKey = $request->input("searchKey");
    $type = $request->input("type"); // cod / prepaid

    // View selection
    if ($type === "cod") {
        $view = "print.indiaPost.codPrint";
    } else {
        $view = "print.indiaPost.prepaidRecipt";
    }

    $franchiseId = Franchise::getFranchiseId();
    $franchise = Franchise::findOrFail($franchiseId);
    $linkDetail = IndiaPostLink::where("franchise_no", $franchise->franchise_no)->first();

    // Parcel type selection
    switch ($type) {
        case 1:
            $query = GotogoSpeedPostParcel::with('noregister');
            break;
        case 2:
            $query = GotogoBusinessParcel::with('noregister');
            break;
        case 3:
            $query = GotogoRegisteredParcel::with('noregister');
            break;
        case 4:
            $query = IndiaPostSpeedPostParcel::with('noregister');
            break;
        case 5:
            $query = IndiaPostBusinessParcel::with('noregister');
            break;
        default:
            return response()->json(['error' => 'Invalid parcel type']);
    }

    // Payment method filter
    if ($type === "cod") {
        $query->where("payment_method", "cod");
    } else {
        $query->where("payment_method", "prepaid");
    }

    // Search key filter
    if ($searchKey) {
        $query->where(function ($q) use ($searchKey) {
            $q->where("pickup_name", "LIKE", "%{$searchKey}%")
                ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")
                ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")
                ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")
                ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")
                ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")
                ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")
                ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")
                ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")
                ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")
                ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")
                ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")
                ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")
                ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")
                ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
        });
    }

    // Date filter
    if (!empty($fromdate) || !empty($todate)) {
        if (!empty($fromdate) && !empty($todate)) {
            $from = Carbon::createFromFormat("d-m-Y", $fromdate)->startOfDay();
            $to = Carbon::createFromFormat("d-m-Y", $todate)->endOfDay();
            $query->whereBetween("created_at", [$from, $to]);
        } elseif (!empty($fromdate)) {
            $from = Carbon::createFromFormat("d-m-Y", $fromdate)->startOfDay();
            $query->whereDate("created_at", $from);
        } elseif (!empty($todate)) {
            $to = Carbon::createFromFormat("d-m-Y", $todate)->endOfDay();
            $query->whereDate("created_at", $to);
        }
    } else {
        // Default: today
        $query->whereDate("created_at", Carbon::today());
    }

    // Selected IDs filter
    $selectedIds = array_map("intval", $request->input("ids", []));
    if (!empty($selectedIds)) {
        $query->whereIn("id", $selectedIds);
    }

    // Status and order
    if($type==4){
      $query->where("status", 0)->orderBy("bulk_batch_id", "ASC")
                                 ->orderBy("excel_row_no", "ASC");  
    }else{
    $query->where("status", 0)->orderBy("created_at", "desc");
}
    // Get parcels
    $parcels = $query->get();

    $title = \App\Models\Admin::INDIA_POST_SPEED;

    $otherPageContent = View::make($view, [
        "data" => $parcels,
        "linkDetail" => $linkDetail,
        "title" => $title,
        "franchise" => $franchise,
        "franchise_no" => $franchise->franchise_no,
        "type" => $type,
    ])->render();

    return response()->json([
        "otherPageContent" => $otherPageContent,
    ]);
}

//   excel download
 public function downloadTableForCreatedTable(Request $request)
{
    // Fetch data
    
    $fromdate = $request->input("fromdate");
    $todate = $request->input("todate");
    $searchKey = $request->input("searchKey");
    $type = $request->input("type"); // 1 to 5
    $paymentType = $request->input("payment_type"); // cod / prepaid (optional)
    $selectedIds = array_map("intval", $request->input("ids", []));

    // Parcel type selection
    switch ($type) {
        case 1:
            $query = GotogoSpeedPostParcel::with('noregister');
            $title = \App\Models\Admin::INDIA_POST_SPEED;
            break;
        case 2:
            $query = GotogoBusinessParcel::with('noregister');
            $title = \App\Models\Admin::INDIA_POST_SPEED;
            break;
        case 3:
            $query = GotogoRegisteredParcel::with('noregister');
            $title = \App\Models\Admin::INDIA_POST_SPEED;
            break;
        case 4:
            $query = IndiaPostSpeedPostParcel::with('noregister');
            $title = \App\Models\Admin::INDIA_POST_SPEED;
            break;
        case 5:
            $query = IndiaPostBusinessParcel::with('noregister');
            $title = \App\Models\Admin::INDIA_POST_SPEED;
            break;
        default:
            return response()->json(['error' => 'Invalid parcel type']);
    }

    // Payment method filter
    if ($paymentType === "cod") {
        $query->where("payment_method", "cod");
    } elseif ($paymentType === "prepaid") {
        $query->where("payment_method", "prepaid");
    }

    // Search key filter
    if ($searchKey) {
        $query->where(function ($q) use ($searchKey) {
            $q->where("pickup_name", "LIKE", "%{$searchKey}%")
              ->orWhere("pickup_mobile", "LIKE", "%{$searchKey}%")
              ->orWhere("pickup_email", "LIKE", "%{$searchKey}%")
              ->orWhere("pickup_pincode", "LIKE", "%{$searchKey}%")
              ->orWhere("pickup_city", "LIKE", "%{$searchKey}%")
              ->orWhere("pickup_state", "LIKE", "%{$searchKey}%")
              ->orWhere("pickup_address", "LIKE", "%{$searchKey}%")
              ->orWhere("consignee_name", "LIKE", "%{$searchKey}%")
              ->orWhere("consignee_mobile", "LIKE", "%{$searchKey}%")
              ->orWhere("consignee_email", "LIKE", "%{$searchKey}%")
              ->orWhere("consignee_pincode", "LIKE", "%{$searchKey}%")
              ->orWhere("consignee_city", "LIKE", "%{$searchKey}%")
              ->orWhere("consignee_state", "LIKE", "%{$searchKey}%")
              ->orWhere("consignee_address", "LIKE", "%{$searchKey}%")
              ->orWhere("barcode_no", "LIKE", "%{$searchKey}%");
        });
    }

    // Date filter
    if (!empty($fromdate) || !empty($todate)) {
        if (!empty($fromdate) && !empty($todate)) {
            $from = Carbon::createFromFormat("d-m-Y", $fromdate)->startOfDay();
            $to = Carbon::createFromFormat("d-m-Y", $todate)->endOfDay();
            $query->whereBetween("created_at", [$from, $to]);
        } elseif (!empty($fromdate)) {
            $from = Carbon::createFromFormat("d-m-Y", $fromdate)->startOfDay();
            $query->whereDate("created_at", $from);
        } elseif (!empty($todate)) {
            $to = Carbon::createFromFormat("d-m-Y", $todate)->endOfDay();
            $query->whereDate("created_at", $to);
        }
    } else {
        $query->whereDate("created_at", Carbon::today());
    }

    // Selected IDs filter
    if (!empty($selectedIds)) {
        $query->whereIn("id", $selectedIds);
    }

    // Status and order
    $query->where("status", 0)->orderBy("created_at", "desc");

    // Get parcels
    $parcels = $query->get();

    // Return Excel
    return $this->getExcel($request, $parcels,$title);
}

    
    
    
       public function getExcel($request, $parcels, $title)
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


    /**
     * Show the user profile page.
     */
   /**
 * Show profile for prepaid users
 */
public function showProfile()
{
    $user = auth()->guard('prepaid')->user();
    
    if (!$user) {
        return redirect()->route('customer.login');
    }
    
    // Create fake franchise object for prepaid user
    $franchise = (object)[
        'name' => $user->name ?? 'Prepaid User',
        'father_name' => 'N/A',
        'generated_id' => 'PREPAID-' . $user->id,
        'franchise_no' => 'PREPAID-' . $user->id,
        'mobile' => $user->phone,
        'email' => $user->email ?? 'N/A',
        'city' => 'N/A',
        'district' => 'N/A',
        'state' => 'N/A',
        'address' => 'N/A',
        'pincode' => 'N/A',
        'latitude' => null,
        'longitude' => null,
        'created_at' => $user->created_at,
        'kyc' => (object)[
            'photo' => null,
            'bank_name' => 'N/A',
            'branch_name' => 'N/A',
            'account_number' => 'N/A',
            'ifsc_code' => 'N/A',
            'pan_card' => 'N/A',
            'adhar_card' => 'N/A',
            'adhar_front_img' => null,
            'adhar_back_img' => null,
            'pan_img' => null,
            'cheque_img' => null,
            'other_document' => null,
            'video_kyc' => null,
        ]
    ];
    
    return view('prepaid.profile', [
        'franchise' => $franchise,
        'user' => $user,
        'userType' => 'prepaid'
    ]);
}
}
