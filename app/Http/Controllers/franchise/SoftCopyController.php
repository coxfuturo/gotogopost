<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use App\Models\SoftCopyParcel;
use App\Models\PickupDetails;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use GuzzleHttp\Client;




class SoftCopyController extends Controller
{
    public function index(): Renderable|JsonResponse|RedirectResponse
    {
        //SoftCopyParcel::query()->truncate();
        $data = SoftCopyParcel::where('franchise_id', Auth::guard('franchise')->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('franchise.softCopyParcel.index', ['datas' => $data]);
    }

    public function create()
    {
        $pickupDetails = PickupDetails::where('franchise_id', Auth::guard('franchise')->user()->id)
            ->get();
        return view('franchise.softCopyParcel.create', ['pickupDetails' => $pickupDetails]);
    }


    public function store(Request $request): Renderable|RedirectResponse
    {


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
            'payment_method' => 'required|string|max:255',
            // Parcel details validation rules
            'package_weight' => 'required|numeric',
            'package_length' => 'required|numeric',
            'package_width' => 'required|numeric',
            'package_height' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        try {
            // Create Pickup
            SoftCopyParcel::create([
                'franchise_id' => Auth::guard('franchise')->user()->id,
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
                'payment_method' => $request->payment_method,
                'order_no' => uniqid(),
            ]);


            return redirect()->route('franchise.softCopyParcel.index')->with('success', 'New Parcel Added');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function view($id)
    {
        $data = SoftCopyParcel::findorfail($id);

        return view('franchise.softCopyParcel.view', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $post = SoftCopyParcel::findOrFail($id);

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

                return redirect()->route('franchise.softCopyParcel.index')->with('success', 'Parcel updated successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        $data = $post;
        $pickupDetails = PickupDetails::where('franchise_id', Auth::guard('franchise')->user()->id)
            ->get();

        return view('franchise.softCopyParcel.edit', compact('data', 'pickupDetails'));
    }

    public function delete($id)
    {

        try {
            SoftCopyParcel::findorfail($id)->delete();
            return redirect()->route('franchise.softCopyParcel.index')->with('success', 'Parcel Deleted Successfully');
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
                try {
                    SoftCopyParcel::create([
                        'franchise_id' => Auth::guard('franchise')->user()->id,
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
                        'order_no' => uniqid(),
                    ]);
                } catch (\Exception $e) {
                    Log::error("Error inserting into SoftCopyParcel: " . $e->getMessage());
                }
            }

            return redirect()->route('franchise.softCopyParcel.index')->with('success', 'Data added successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
