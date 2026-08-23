<?php

namespace App\Http\Controllers\market;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NoRegisterCustomer;
use App\Models\GotogoSpeedPostParcel;
use App\Models\GotogoRegisteredParcel;
use App\Models\IndiaPostBusinessParcel;
use App\Models\IndiaPostSpeedPostParcel;
use App\Models\GotogoBusinessParcel;
use App\Models\MManager;
use App\Models\Franchise;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Notifications\SMSNotification;
use Carbon\Carbon;

class CustomerController extends Controller
{
   public function index(Request $request)
{
    $market_id= auth()->guard('market')->id();
    $query = NoRegisterCustomer::with('manager');

    // Search key filter
    if ($request->filled('searchKey')) {
        $searchKey = $request->input('searchKey');
        $query->where(function ($q) use ($searchKey) {
            $q->where('name', 'like', '%' . $searchKey . '%')
              ->orWhere('phone', 'like', '%' . $searchKey . '%')
              ->orWhere('email', 'like', '%' . $searchKey . '%')
              ->orWhere('pincode', 'like', '%' . $searchKey . '%')
              ->orWhere('city', 'like', '%' . $searchKey . '%')
              ->orWhere('state', 'like', '%' . $searchKey . '%'); 
        });
    }

    // Date filter (d-m-Y format)
    if ($request->filled('date')) {
            $date = Carbon::createFromFormat('d-m-Y', $request->input('date'))->format('Y-m-d');
            $query->whereDate('created_at', $date);
        
    }
                $query->where('type','manager');
                $query->where('market_id',$market_id);
                $query->orderBy("bulk_batch_id", "ASC")
                      ->orderBy("excel_row_no", "ASC");
                $data['data'] = $query->get();

    // ✅ Add parcel count for each customer
    foreach ($data['data'] as $customer) {
        $customer->parcel_count = $this->totalCount($customer->id, $request);
    }

     // ✅ Add parcel Total Amount for each customer
    foreach ($data['data'] as $customer) {
        $customer->parcel_amount = $this->totalAmount($customer->id, $request);
    }

    return view('market.customer.index', $data);
}



     public function export(Request $request)
{
   $market_id= auth()->guard('market')->id();

   $customer = MManager::find($market_id);

    $query = NoRegisterCustomer::with('manager');

    // Search key filter
    if ($request->filled('searchKey')) {
        $searchKey = $request->input('searchKey');
        $query->where(function ($q) use ($searchKey) {
            $q->where('name', 'like', '%' . $searchKey . '%')
              ->orWhere('phone', 'like', '%' . $searchKey . '%')
              ->orWhere('email', 'like', '%' . $searchKey . '%')
              ->orWhere('pincode', 'like', '%' . $searchKey . '%')
              ->orWhere('city', 'like', '%' . $searchKey . '%')
              ->orWhere('state', 'like', '%' . $searchKey . '%'); 
        });
    }

    // Date filter (d-m-Y format)
    if ($request->filled('date')) {
            $date = Carbon::createFromFormat('d-m-Y', $request->input('date'))->format('Y-m-d');
            $query->whereDate('created_at', $date);
        
    }          
                $query->where('type','manager');
                $query->where('market_id',$market_id);
                $query->orderBy("bulk_batch_id", "ASC")
                      ->orderBy("excel_row_no", "ASC");
    $parcels = $query->get();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $headings = [

            'Sl',
            'Franchise',
            'Marketing Manager',
            'Name',
            'Mobile',
            'Email',
            'GST',

            'Pincode',
            'City',

            'State',
            'Address',
            
        ];

        $column = 'A';

        foreach ($headings as $heading) {

            $sheet->setCellValue($column . '1', $heading);

            $column++;
        }

        $row = 2; // Start from the second row

        foreach ($parcels as $key => $parcel) {

        
            // Set cell values

            $sheet->setCellValue('A' . $row, $key + 1);

            $sheet->setCellValue('B' . $row, $parcel->franchise->name ?? NULL);

            $sheet->setCellValue('C' . $row, $parcel->manager->name);

            $sheet->setCellValue('D' . $row, $parcel->name);

            $sheet->setCellValue('E' . $row, $parcel->phone);

            $sheet->setCellValue('F' . $row, $parcel->email);

            $sheet->setCellValue('G' . $row, $parcel->gst_no);

            $sheet->setCellValue('H' . $row, $parcel->pincode);

            $sheet->setCellValue('I' . $row, $parcel->city);

            $sheet->setCellValue('J' . $row, $parcel->state);

            $sheet->setCellValue('K' . $row, $parcel->address);

            $row++;
        }

        // Create a Writer
        $writer = new Xlsx($spreadsheet);
        // Create a response to stream the file

        $response = new StreamedResponse(function () use ($writer) {

            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $filename = 'NonRegisterCustomer=' . $customer->name . '.xlsx';
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;

}

    public function create(){
        return view('market.customer.create');
    }

   public function store(Request $request)
{
    // Step 1: Validate the request
    $request->validate([
        'name'            => 'required|string|max:255',
        'register_type'            => 'required',
        'mobile'          => 'required',
        'pincode'         => 'required|digits:6',
        'city'            => 'required|string|max:255',
        'state'           => 'required|string|max:255',
        'address'   => 'required|string|max:500',
        'franchise_id'    => 'required|exists:franchises,id', // Added existence validation
    ]);
  $market_id= auth()->guard('market')->id();
    // Step 2: Check if customer already exists as manager
    $mobileExists = NoRegisterCustomer::where('phone', $request->mobile)->where('market_id',$market_id)
    ->where('type', 'manager')
    ->exists();

if ($mobileExists) {
    return redirect()->back()->with('error', 'Mobile number already exists for a Customer!');
}

if (!empty($request->email)) {
    $emailExists = NoRegisterCustomer::where('email', $request->email)->where('market_id',$market_id)
        ->where('type', 'manager')
        ->exists();

    if ($emailExists) {
        return redirect()->back()->with('error', 'Email already exists for a Customer!');
    }
}


    // Step 3: Fetch the franchise
    $franchise = Franchise::find($request->franchise_id);

    if (!$franchise) {
        return redirect()->back()->with('error', 'Franchise not found.');
    }
   $mobile = $request->mobile;
    // Step 4: Generate a random 10-digit numeric password
    $password = substr(str_shuffle('0123456789'), 0, 10);
    $pass = Hash::make($password);
    // Step 5: Create the new franchise manager
    NoRegisterCustomer::create([
        'name'         => $request->input('name'),
        'type'         => 'manager',
        'franchise_id' => $franchise->id,
        'market_id'    => $market_id,
        'register_type'=> $request->input('register_type'),
        'phone'        => $request->input('mobile'),
        'gst_number'   => $request->input('gst_no'),
        'email'        => $request->input('email'),
        'pincode'      => $request->input('pincode'),
        'city'         => $request->input('city'),
        'state'        => $request->input('state'),
        'address'      => $request->input('address'),
        'password'     => $pass,
		'status'        => 1,
    ]);
    // Send SMS to pickup mobile
            try {
               $notification = new SMSNotification($mobile, 'WELCOME', [$mobile,$password]);
                $response = $notification->sendMessage();
            } catch (\Exception $e) {
                \Log::error('Error sending SMS to pickup mobile: ' . $e->getMessage());
            }
   session()->flash('success', 'Customer created successfully!');
   return redirect()->route('market.customer.index');
}

public function edit($id){
     $data = NoRegisterCustomer::find($id);
     $franchise = Franchise::where('city',$data->city)->get();
        return view('market.customer.update',compact('data','franchise'));
    }

    public function update(Request $request, $customer_id)
{
    $request->validate([
        'name'          => 'required|string|max:255',
        'register_type' => 'required',
        'mobile'        => 'required',
        'pincode'       => 'required|digits:6',
        'city'          => 'required|string|max:255',
        'state'         => 'required|string|max:255',
        'address'       => 'required|string|max:500',
        'franchise_id'  => 'required|exists:franchises,id',
    ]);

    $market_id = auth()->guard('market')->id();

    // Step 1: Fetch existing customer
    $customer = NoRegisterCustomer::where('id', $customer_id)
        ->where('market_id', $market_id)
        ->where('type', 'manager')
        ->firstOrFail();

    // Step 2: Check for duplicate mobile (only if changed)
    if ($customer->phone !== $request->mobile) {
        $mobileExists = NoRegisterCustomer::where('phone', $request->mobile)
            ->where('market_id', $market_id)
            ->where('type', 'manager')
            ->where('id', '!=', $customer->id)
            ->exists();

        if ($mobileExists) {
            return redirect()->back()->with('error', 'Mobile number already exists for a Customer!');
        }
    }

    // Step 3: Check for duplicate email (only if changed and not empty)
    if (!empty($request->email) && $customer->email !== $request->email) {
        $emailExists = NoRegisterCustomer::where('email', $request->email)
            ->where('market_id', $market_id)
            ->where('type', 'manager')
            ->where('id', '!=', $customer->id)
            ->exists();

        if ($emailExists) {
            return redirect()->back()->with('error', 'Email already exists for a Customer!');
        }
    }

    // Step 4: Update the customer
    $customer->update([
        'name'          => $request->input('name'),
        'register_type' => $request->input('register_type'),
        'franchise_id'  => $request->input('franchise_id'),
        'phone'         => $request->input('mobile'),
        'gst_number'    => $request->input('gst_no'),
        'email'         => $request->input('email'),
        'pincode'       => $request->input('pincode'),
        'city'          => $request->input('city'),
        'state'         => $request->input('state'),
        'address'       => $request->input('address'),
    ]);

    session()->flash('success', 'Customer updated successfully!');
   return redirect()->route('market.customer.index');

}


public function view($id, $type, Request $request)
{
    $searchKey = $request->input('searchKey');
    $date = $request->input('date');

    $data['type'] = $type;
    $data['id'] = $id;

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
        $baseQuery->where('booking_type', 'manager')
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
    } else {
        $data['datas'] = collect(); 
        $data['count'] = 0;
        $data['total_amount'] = 0;
    }

    return view('market.customer.view', $data);
}


public function parcelExport( Request $request)
{

    $searchKey = $request->input('searchKey');
    $date = $request->input('date');

   $type=  $data['type'] = $request->input('type');
   $id = $data['id'] = $request->input('id');

   $baseQuery = null;

    $customer = NoRegisterCustomer::find($id);

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
        $baseQuery->where('booking_type', 'manager')
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

             $clonedQuery = clone $baseQuery;
        $parcels = $baseQuery->get();
        $parcelscount = $clonedQuery->count();
        $parcelsamount = $clonedQuery->sum('payment_amount'); 
    } else {
        $parcels = collect(); 
        $parcelscount = 0;
        $parcelsamount = 0;
    }

     // Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add "Gotogo Post" title at the top
$sheet->setCellValue('A1', $customer->name . ' | Total Parcels: ' . $parcelscount . ' | Total Amount: ₹' . number_format($parcelsamount, 2));
$sheet->mergeCells('A1:X1'); // Adjust if you add more columns
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Set the column headings in row 2
$headings = [
    'Sl', 'Franchise', 'Marketing Manager', 'Barcode', 'Ref', 'From Address', 'ADD1',
    'ADD2', 'ADD3', 'Pincode', 'City', 'State', 'Mobile', 'Email',
    'To Address', 'ADD1', 'ADD2', 'ADD3', 'Pincode', 'City',
    'State', 'Mobile', 'Email', 'Weight',
];

$column = 'A';
foreach ($headings as $heading) {
    $sheet->setCellValue($column . '2', $heading);
    $column++;
}

// Populate the data starting from row 3
$row = 3;
foreach ($parcels as $key => $parcel) {
    $pickupAddressParts = explode(',', $parcel->pickup_address);
    $PADD1 = $pickupAddressParts[0] ?? '';
    $PADD2 = $pickupAddressParts[1] ?? $PADD1;
    $PADD3 = $pickupAddressParts[2] ?? $PADD2;

    $consigneeAddressParts = explode(',', $parcel->consignee_address);
    $CADD1 = $consigneeAddressParts[0] ?? '';
    $CADD2 = $consigneeAddressParts[1] ?? $CADD1;
    $CADD3 = $consigneeAddressParts[2] ?? $CADD2;

    $sheet->setCellValue('A' . $row, $key + 1);
    $sheet->setCellValue('B' . $row, $parcel->noregister->franchise->name ?? NULL);
    $sheet->setCellValue('C' . $row, $parcel->noregister->manager->name);
    $sheet->setCellValue('D' . $row, $parcel->barcode_no);
    $sheet->setCellValue('E' . $row, GotogoSpeedPostParcel::getServiceType(GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED));
    $sheet->setCellValue('F' . $row, $parcel->pickup_address);
    $sheet->setCellValue('G' . $row, $PADD1);
    $sheet->setCellValue('H' . $row, $PADD2);
    $sheet->setCellValue('I' . $row, $PADD3);
    $sheet->setCellValue('J' . $row, $parcel->pickup_pincode);
    $sheet->setCellValue('K' . $row, $parcel->pickup_city);
    $sheet->setCellValue('L' . $row, $parcel->pickup_state);
    $sheet->setCellValue('M' . $row, $parcel->pickup_mobile);
    $sheet->setCellValue('N' . $row, $parcel->pickup_email);
    $sheet->setCellValue('O' . $row, $parcel->consignee_address);
    $sheet->setCellValue('P' . $row, $CADD1);
    $sheet->setCellValue('Q' . $row, $CADD2);
    $sheet->setCellValue('R' . $row, $CADD3);
    $sheet->setCellValue('S' . $row, $parcel->consignee_pincode);
    $sheet->setCellValue('T' . $row, $parcel->consignee_city);
    $sheet->setCellValue('U' . $row, $parcel->consignee_state);
    $sheet->setCellValue('V' . $row, $parcel->consignee_mobile);
    $sheet->setCellValue('W' . $row, $parcel->consignee_email);
    $sheet->setCellValue('X' . $row, $parcel->package_weight);
    $row++;
}

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {

            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

$filename = 'MarketManagerCustomerParcels=' . $customer->name . '.xlsx';
$response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

$response->headers->set('Cache-Control', 'max-age=0');


        return $response;
}

public function totalCount($id, Request $request)
{

    $searchKey = $request->input('searchKey');
    $date = $request->input('date');

    // List of all parcel models to search
    $parcelModels = [
        GotogoSpeedPostParcel::class,
        GotogoBusinessParcel::class,
        GotogoRegisteredParcel::class,
        IndiaPostSpeedPostParcel::class,
        IndiaPostBusinessParcel::class,
    ];

    $totalCount = 0;

    foreach ($parcelModels as $model) {
        $query = $model::with('noregister')
            ->where('booking_type', 'manager')
            ->whereHas('noregister', function ($q) use ($id) {
                $q->where('id', $id);
            });

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
            $parsedDate = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
            $query->whereDate('created_at', $parsedDate);
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

        // Add this model's count to total
        $totalCount += $query->count();
    }

    return $totalCount;
}

public function totalAmount($id, Request $request)
{

    $searchKey = $request->input('searchKey');
    $date = $request->input('date');

    // List of all parcel models to search
    $parcelModels = [
        GotogoSpeedPostParcel::class,
        GotogoBusinessParcel::class,
        GotogoRegisteredParcel::class,
        IndiaPostSpeedPostParcel::class,
        IndiaPostBusinessParcel::class,
    ];

    $totalAmount = 0;

    foreach ($parcelModels as $model) {
        $query = $model::with('noregister')
            ->where('booking_type', 'manager')
            ->whereHas('noregister', function ($q) use ($id) {
                $q->where('id', $id);
            });

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
            $parsedDate = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
            $query->whereDate('created_at', $parsedDate);
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

        // Add this model's count to total
        $totalAmount += $query->sum('payment_amount');
    }

    return $totalAmount;
}

public function cancel(Request $request, $id, $type)
{
    if ($type == 1) {
        $baseQuery = GotogoSpeedPostParcel::findOrFail($id);
    } elseif ($type == 2) {
        $baseQuery = GotogoBusinessParcel::findOrFail($id);
    } elseif ($type == 3) {
        $baseQuery = GotogoRegisteredParcel::findOrFail($id);
    } elseif ($type == 4) {
        $baseQuery = IndiaPostSpeedPostParcel::findOrFail($id);
    } elseif ($type == 5) {
        $baseQuery = IndiaPostBusinessParcel::findOrFail($id);
    } else {
        return redirect()->back()->with('error', 'Invalid type!');
    }

    $baseQuery->discription = $request->discription; // maybe typo? (should be description?)
    $baseQuery->status = 1;
    $baseQuery->save();

    return redirect()->back()->with('success', 'Cancel Request successfully!');
}


//  Cancel reports start

public function cancelReport($id, $type, Request $request)
{
   
    $searchKey = $request->input('searchKey');
    $date = $request->input('date');

    $data['type'] = $type;
    $data['id'] = $id;

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
        $baseQuery->where('booking_type', 'manager')
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
     $baseQuery->where('status', 2);

             $clonedQuery = clone $baseQuery;
        $data['datas'] = $baseQuery->get();
        $data['count'] = $clonedQuery->count();
        $data['total_amount'] = $clonedQuery->sum('payment_amount'); 
    } else {
        $data['datas'] = collect(); 
        $data['count'] = 0;
        $data['total_amount'] = 0;
    }

    return view('market.customer.cancel', $data);
}

public function parcelExportCancel( Request $request)
{

    $searchKey = $request->input('searchKey');
    $date = $request->input('date');

   $type=  $data['type'] = $request->input('type');
   $id = $data['id'] = $request->input('id');

   $baseQuery = null;

    $customer = NoRegisterCustomer::find($id);

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
        $baseQuery->where('booking_type', 'manager')
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
     $baseQuery->where('status', 2);
             $clonedQuery = clone $baseQuery;
        $parcels = $baseQuery->get();
        $parcelscount = $clonedQuery->count();
        $parcelsamount = $clonedQuery->sum('payment_amount'); 
    } else {
        $parcels = collect(); 
        $parcelscount = 0;
        $parcelsamount = 0;
    }

     // Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add "Gotogo Post" title at the top
$sheet->setCellValue('A1', $customer->name . ' | Total Cancel Parcels: ' . $parcelscount . ' | Total Amount: ₹' . number_format($parcelsamount, 2));
$sheet->mergeCells('A1:X1'); // Adjust if you add more columns
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Set the column headings in row 2
$headings = [
    'Sl', 'Franchise', 'Marketing Manager', 'Barcode', 'Ref', 'From Address', 'ADD1',
    'ADD2', 'ADD3', 'Pincode', 'City', 'State', 'Mobile', 'Email',
    'To Address', 'ADD1', 'ADD2', 'ADD3', 'Pincode', 'City',
    'State', 'Mobile', 'Email', 'Weight',
];

$column = 'A';
foreach ($headings as $heading) {
    $sheet->setCellValue($column . '2', $heading);
    $column++;
}

// Populate the data starting from row 3
$row = 3;
foreach ($parcels as $key => $parcel) {
    $pickupAddressParts = explode(',', $parcel->pickup_address);
    $PADD1 = $pickupAddressParts[0] ?? '';
    $PADD2 = $pickupAddressParts[1] ?? $PADD1;
    $PADD3 = $pickupAddressParts[2] ?? $PADD2;

    $consigneeAddressParts = explode(',', $parcel->consignee_address);
    $CADD1 = $consigneeAddressParts[0] ?? '';
    $CADD2 = $consigneeAddressParts[1] ?? $CADD1;
    $CADD3 = $consigneeAddressParts[2] ?? $CADD2;

    $sheet->setCellValue('A' . $row, $key + 1);
    $sheet->setCellValue('B' . $row, $parcel->noregister->franchise->name ?? NULL);
    $sheet->setCellValue('C' . $row, $parcel->noregister->manager->name);
    $sheet->setCellValue('D' . $row, $parcel->barcode_no);
    $sheet->setCellValue('E' . $row, GotogoSpeedPostParcel::getServiceType(GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED));
    $sheet->setCellValue('F' . $row, $parcel->pickup_address);
    $sheet->setCellValue('G' . $row, $PADD1);
    $sheet->setCellValue('H' . $row, $PADD2);
    $sheet->setCellValue('I' . $row, $PADD3);
    $sheet->setCellValue('J' . $row, $parcel->pickup_pincode);
    $sheet->setCellValue('K' . $row, $parcel->pickup_city);
    $sheet->setCellValue('L' . $row, $parcel->pickup_state);
    $sheet->setCellValue('M' . $row, $parcel->pickup_mobile);
    $sheet->setCellValue('N' . $row, $parcel->pickup_email);
    $sheet->setCellValue('O' . $row, $parcel->consignee_address);
    $sheet->setCellValue('P' . $row, $CADD1);
    $sheet->setCellValue('Q' . $row, $CADD2);
    $sheet->setCellValue('R' . $row, $CADD3);
    $sheet->setCellValue('S' . $row, $parcel->consignee_pincode);
    $sheet->setCellValue('T' . $row, $parcel->consignee_city);
    $sheet->setCellValue('U' . $row, $parcel->consignee_state);
    $sheet->setCellValue('V' . $row, $parcel->consignee_mobile);
    $sheet->setCellValue('W' . $row, $parcel->consignee_email);
    $sheet->setCellValue('X' . $row, $parcel->package_weight);
    $row++;
}

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {

            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

$filename = 'MarketManagerCustomerCancelParcels=' . $customer->name . '.xlsx';
$response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

$response->headers->set('Cache-Control', 'max-age=0');


        return $response;
}

//   Details 

 public function details($id, $type)
{
    if ($type == 1) {
        $data = GotogoSpeedPostParcel::findOrFail($id);
    } elseif ($type == 2) {
        $data = GotogoBusinessParcel::findOrFail($id);
    } elseif ($type == 3) {
        $data = GotogoRegisteredParcel::findOrFail($id);
    } elseif ($type == 4) {
        $data = IndiaPostSpeedPostParcel::findOrFail($id);
    } elseif ($type == 5) {
        $data = IndiaPostBusinessParcel::findOrFail($id);
    } else {
        return redirect()->back()->with('error', 'Invalid type!');
    }
      
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

    return view('market.parcel.details', compact('data', 'rateDetails','type'));
}

public function trackOrder($id, $type)
{
    if ($type == 1) {
        $data = GotogoSpeedPostTrackOrder::where('parcel_id', $id)->first();
    } elseif ($type == 2) {
        $data = GotogoBusinessTrackOrder::where('parcel_id', $id)->first();
    } elseif ($type == 3) {
        $data = GotogoRegisteredTrackOrder::where('parcel_id', $id)->first();
    } elseif ($type == 4) {
        $data = IndiaPostSpeedPostTrackOrder::where('parcel_id', $id)->first();
    } elseif ($type == 5) {
        $data = IndiaPostBusinessTrackOrder::where('parcel_id', $id)->first();
    } else {
        return redirect()->back()->with('error', 'Invalid type!');
    }
      
       return response()->json([
            'trackingDetails' => $trackingDetails
        ]);

    }

    public function fullPrint($id, $type)
{
    if ($type == 1) {
        $parcel = GotogoSpeedPostTrackOrder::where('parcel_id', $id)->first();
        $file = 'gotogopost';
        $title = \App\Models\Admin::GOTOGO_POST_SPEED;
    } elseif ($type == 2) {
        $parcel = GotogoBusinessTrackOrder::where('parcel_id', $id)->first();
        $file = 'gotogopost';
        $title = \App\Models\Admin::GOTOGO_POST_BUSINESS;
    } elseif ($type == 3) {
        $parcel = GotogoRegisteredTrackOrder::where('parcel_id', $id)->first();
        $file = 'gotogopost';
        $title = \App\Models\Admin::GOTOGO_POST_REGISTERED;
    } elseif ($type == 4) {
        $parcel = IndiaPostSpeedPostTrackOrder::where('parcel_id', $id)->first();
        $file = 'indiaPost';
        $title = \App\Models\Admin::INDIA_POST_SPEED;
    } elseif ($type == 5) {
        $parcel = IndiaPostBusinessTrackOrder::where('parcel_id', $id)->first();
        $file = 'indiaPost';
        $title = \App\Models\Admin::INDIA_POST_BUSINESS;
    } else {
        return redirect()->back()->with('error', 'Invalid type!');
    }
      
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

    $otherPageContent = View::make('print.' . $file . '.fullPrint', [
    'parcel' => $parcel,
    'barcode' => $barcode,
    'rateDetails' => $rateDetails,
    'type' => 1,
    'title' => $title
])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);

    }

}
