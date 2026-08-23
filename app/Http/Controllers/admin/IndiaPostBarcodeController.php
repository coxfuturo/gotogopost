<?php

// app/Http/Controllers/BarcodeController.php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\IndiaPostBarcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;
use App\SmsServices;

class IndiaPostBarcodeController extends Controller
{
    // Get the next available barcode and update the availability
    public function getNextBarcode($code,$prefix,$postfix)
{
    

    try {
        $barcode = IndiaPostBarcode::where('code', $code)
            ->where('prefix', $prefix)
            ->where('postfix', $postfix)
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
                'message' => 'No available barcodes'
            ], 404);
        }
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}


    // Store a new range of barcodes
    public function store(Request $request)
    {
          
        $request->validate([
            'service_type' => 'required',
            'state' => 'required|string',
            'code' => 'required|string|max:2',
            'prefix' => 'required|string|max:2',
            'range_from' => 'required|integer|lt:range_to',
            'range_to' => 'required|integer|gt:range_from',
            'postfix' => 'required|string|max:2',
        ], [
            'range_from.lt' => 'The starting range must be less than the ending range.',
            'range_to.gt' => 'The ending range must be greater than the starting range.',
        ]);

        try {
            $availables = $request->range_to - $request->range_from + 1;

            IndiaPostBarcode::create([
                'service_type' => $request->service_type,
                'state' => $request->state,
                'code' => $request->code,
                'prefix' => $request->prefix,
                'range_from' => $request->range_from,
                'range_to' => $request->range_to,
                'postfix' => $request->postfix,
                'availables' => $availables,
            ]);

            return redirect()->route('indiapostbarcodes.index')
                ->with('success', 'Barcode range added successfully.');
        } catch (Exception $e) {
            return redirect()->route('indiapostbarcodes.index')
                ->with('error', 'Failed to add barcode range: ' . $e->getMessage());
        }
    }

    // Display the index page with barcode list
    public function index()
    {
        

        $states = [
            'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 
            'Chhattisgarh', 'Delhi', 'Goa', 'Gujarat', 'Haryana', 
            'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 
            'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 
            'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 
            'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 
            'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'
        ];
        
        $barcodes = IndiaPostBarcode::all(); // Get all barcode records
        
        return view('admin.indiapost-barcode.index', compact('barcodes', 'states'));


    }

    // Show the edit form
    public function edit($id)

    {

        $ass=$this->getNextBarcode('TR','UU','JU');

        dd($ass);

        // $barcode = IndiaPostBarcode::findOrFail($id);
        // return view('indiapostbarcodes.edit', compact('barcode'));
    }

    // Update a barcode range
    public function update(Request $request, $id)
    {
        $request->validate([
            'state' => 'required|string',
            'code' => 'required|string|max:2',
            'prefix' => 'required|string|max:2',
            'range_from' => 'required|integer|lt:range_to',
            'range_to' => 'required|integer|gt:range_from',
            'postfix' => 'required|string|max:2',
        ]);

        try {
            $barcode = IndiaPostBarcode::findOrFail($id);

            $availables = $request->range_to - $request->range_from + 1;

            $barcode->update([
                'state' => $request->state,
                'code' => $request->code,
                'prefix' => $request->prefix,
                'range_from' => $request->range_from,
                'range_to' => $request->range_to,
                'postfix' => $request->postfix,
                'availables' => $availables,
            ]);

            return redirect()->route('indiapostbarcodes.index')
                ->with('success', 'Barcode range updated successfully.');
        } catch (Exception $e) {
            return redirect()->route('indiapostbarcodes.index')
                ->with('error', 'Failed to update barcode range: ' . $e->getMessage());
        }
    }

    // Delete a barcode range
    // public function destroy($id)
    // {

       
    //     try {
    //         $barcode = IndiaPostBarcode::findOrFail($id);
    //         $barcode->delete();

    //         return redirect()->route('indiapostbarcodes.index')
    //             ->with('success', 'Barcode range deleted successfully.');
    //     } catch (Exception $e) {
    //         return redirect()->route('indiapostbarcodes.index')
    //             ->with('error', 'Failed to delete barcode range: ' . $e->getMessage());
    //     }
    // }

    public function destroy($id)
{



    $sendSms=new SmsServices();

    // $variables = [
    //     '132332322', // Article Number
    //     '12/02/2025', // Delivery Date
    //     'Delhi',
    //     '12/02/2025' // Delivery Date
    // ];

    $variables = [
        '88888', // Article Number
        
    ];



    $sms=$sendSms->sendSms('OTP', $variables, '918287537054');

    dd($sms);
}
}
