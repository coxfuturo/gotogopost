<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\FranchiseBarcodeSeries;
use App\Models\CMSBarcodeSeries;
use App\Models\PPHBarcodeSeries;
use Illuminate\Http\Request;

class AllotedBarcodeController extends Controller
{
    /**
     * Get all Franchise Parcel Barcodes
     */

    protected $services;
    protected $bags;


    public function __construct()
    {
        // Parcel Services
        $this->services = [
            \App\Models\Admin::GOTOGO_POST_SPEED => [
                'start' => 'parcel_barcode_range_start_gotoSpeed',
                'end' => 'parcel_barcode_range_end_gotoSpeed',
                'issued' => 'last_parcel_code_issued_gotoSpeed'
            ],
            \App\Models\Admin::GOTOGO_POST_BUSINESS => [
                'start' => 'parcel_barcode_range_start_gotoBusiness',
                'end' => 'parcel_barcode_range_end_gotoBusiness',
                'issued' => 'last_parcel_code_issued_gotoBusiness'
            ],
            \App\Models\Admin::GOTOGO_POST_REGISTERED => [
                'start' => 'parcel_barcode_range_start_gotoRegistered',
                'end' => 'parcel_barcode_range_end_gotoRegistered',
                'issued' => 'last_parcel_code_issued_gotoRegistered'
            ],
            \App\Models\Admin::INDIA_POST_SPEED => [
                'start' => 'parcel_barcode_range_start_IPSpeed',
                'end' => 'parcel_barcode_range_end_IPSpeed',
                'issued' => 'last_parcel_code_issued_IPSpeed'
            ],
            \App\Models\Admin::INDIA_POST_BUSINESS => [
                'start' => 'parcel_barcode_range_start_IPBusiness',
                'end' => 'parcel_barcode_range_end_IPBusiness',
                'issued' => 'last_parcel_code_issued_IPBusiness'
            ],
        ];

        // Bag Services (Exactly same format)
        $this->bags = [
            \App\Models\Admin::GOTOGO_POST_SPEED => [
                'start' => 'bag_barcode_range_start_gotoSpeed',
                'end' => 'bag_barcode_range_end_gotoSpeed',
                'issued' => 'last_bag_code_issued_gotoSpeed'
            ],
            \App\Models\Admin::GOTOGO_POST_BUSINESS => [
                'start' => 'bag_barcode_range_start_gotoBusiness',
                'end' => 'bag_barcode_range_end_gotoBusiness',
                'issued' => 'last_bag_code_issued_gotoBusiness'
            ],
            \App\Models\Admin::GOTOGO_POST_REGISTERED => [
                'start' => 'bag_barcode_range_start_gotoRegistered',
                'end' => 'bag_barcode_range_end_gotoRegistered',
                'issued' => 'last_bag_code_issued_gotoRegistered'
            ],
            \App\Models\Admin::INDIA_POST_SPEED => [
                'start' => 'bag_barcode_range_start_IPSpeed',
                'end' => 'bag_barcode_range_end_IPSpeed',
                'issued' => 'last_bag_code_issued_IPSpeed'
            ],
            \App\Models\Admin::INDIA_POST_BUSINESS => [
                'start' => 'bag_barcode_range_start_IPBusiness',
                'end' => 'bag_barcode_range_end_IPBusiness',
                'issued' => 'last_bag_code_issued_IPBusiness'
            ],
        ];
    }

    public function franchiseParcel(Request $request)
    {
        $datas = FranchiseBarcodeSeries::with('franchise')->get();

        // Define services and related barcode fields
        $services = $this->services;
        // Process data for each franchise
        $processedData = [];

        foreach ($datas as $data) {
            $franchiseData = [
                'name' => $data->franchise->name ?? 'N/A',
                'services' => [],
            ];

            foreach ($services as $service => $keys) {
                if ($data->{$keys['start']} && $data->{$keys['end']}) {
                    $start = intval($data->{$keys['start']});
                    $end = intval($data->{$keys['end']});

                    // Correct Issued Logic
                    $issued = intval($data->{$keys['issued']} ?? $start - 1); // Same as your `getBarcodeAvailableCount()`

                    // Allotted Calculation
                    $allotted = max(0, ($end - $start + 1));

                    // Available Calculation (Using same logic as your function)
                    $available = max(0, ($end - $issued));

                    $franchiseData['services'][] = [
                        'service_name' => $service,
                        'barcode_range' => "$start - $end",
                        'allotted' => $allotted,
                        'available' => $available,
                    ];
                }
            }

            $processedData[] = $franchiseData;
        }

        return view('admin.alloted-barcode.index', [
            'franchises' => $processedData,
            'title' => "Parcel Barcode Alloted to Franchise"
        ]);
    }


    /**
     * Get all Franchise Bag Barcodes
     */
    public function franchiseBag()
    {
        $datas = FranchiseBarcodeSeries::with('franchise')->get();
        $processedData = [];

        foreach ($datas as $data) {
            $franchiseData = [
                'name' => $data->franchise->name ?? 'N/A',
                'services' => [], // 'barcodes' ko 'services' kar diya
            ];

            foreach ($this->bags as $bagService => $keys) {
                if ($data->{$keys['start']} && $data->{$keys['end']}) {
                    $start = intval($data->{$keys['start']});
                    $end = intval($data->{$keys['end']});
                    $issued = intval($data->{$keys['issued']} ?? $start - 1);

                    $allotted = max(0, ($end - $start + 1));
                    $available = max(0, ($end - $issued));

                    $franchiseData['services'][] = [ // Yaha bhi 'services' rakha
                        'service_name' => $bagService,
                        'barcode_range' => "$start - $end",
                        'allotted' => $allotted,
                        'available' => $available,
                    ];
                }
            }

            $processedData[] = $franchiseData;
        }

        return view('admin.alloted-barcode.index', [
            'franchises' => $processedData,
            'title' => "Bag Barcode Alloted to Franchise"
        ]);
    }


    /**
     * Get all CMS Bag Barcodes
     */
    public function cphBag(Request $request)
    {
        $datas = CMSBarcodeSeries::with('cms')->get();
        $processedData = [];

        foreach ($datas as $data) {
            $franchiseData = [
                'name' => $data->cms->name ?? 'N/A',
                'services' => [], // 'barcodes' ko 'services' kar diya
            ];

            foreach ($this->bags as $bagService => $keys) {
                if ($data->{$keys['start']} && $data->{$keys['end']}) {
                    $start = intval($data->{$keys['start']});
                    $end = intval($data->{$keys['end']});
                    $issued = intval($data->{$keys['issued']} ?? $start - 1);

                    $allotted = max(0, ($end - $start + 1));
                    $available = max(0, ($end - $issued));

                    $franchiseData['services'][] = [ // Yaha bhi 'services' rakha
                        'service_name' => $bagService,
                        'barcode_range' => "$start - $end",
                        'allotted' => $allotted,
                        'available' => $available,
                    ];
                }
            }

            $processedData[] = $franchiseData;
        }

        return view('admin.alloted-barcode.index', [
            'franchises' => $processedData,
            'title' => "Bag Barcode Alloted to CPH"
        ]);
    }

    /**
     * Get all PPH Bag Barcodes
     */

    public function pphBag(Request $request)
    {
        $datas = PPHBarcodeSeries::with('pph')->get();
        $processedData = [];

        foreach ($datas as $data) {
            $franchiseData = [
                'name' => $data->pph->name ?? 'N/A',
                'services' => [], // 'barcodes' ko 'services' kar diya
            ];

            foreach ($this->bags as $bagService => $keys) {
                if ($data->{$keys['start']} && $data->{$keys['end']}) {
                    $start = intval($data->{$keys['start']});
                    $end = intval($data->{$keys['end']});
                    $issued = intval($data->{$keys['issued']} ?? $start - 1);

                    $allotted = max(0, ($end - $start + 1));
                    $available = max(0, ($end - $issued));

                    $franchiseData['services'][] = [ // Yaha bhi 'services' rakha
                        'service_name' => $bagService,
                        'barcode_range' => "$start - $end",
                        'allotted' => $allotted,
                        'available' => $available,
                    ];
                }
            }

            $processedData[] = $franchiseData;
        }

        return view('admin.alloted-barcode.index', [
            'franchises' => $processedData,
            'title' => "Bag Barcode Alloted to CPH"
        ]);
    }
}
