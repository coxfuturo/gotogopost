<?php

namespace App\Http\Controllers\franchise;

use App\Http\Controllers\Controller;
use App\Models\FranchiseBag;
use App\Models\CMSBag;
use App\Models\CMS;
use App\Models\DeliveryBoy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\FranchiseBarcodeSeries;
use App\Models\FranchiseBarcodes;
use App\Models\FranchiseCommissionDetail;
use App\Models\Franchise;
use Illuminate\Support\Facades\View;
use DB;

use Carbon\Carbon;
use App\Models\GotogoLink;
use App\Models\IndiaPostLink;
use App\Notifications\FranchisePushNotification;
use App\Models\User;
use App\Models\ReturnedParcel;
use App\Notifications\SMSNotification;
use App\Models\GotogoSpeedPostParcel;
use App\Models\DeliveryBoyBagParcel;



class BagController extends Controller

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
                'showcreatedBags' => 'Bag-view',
                'store' => 'Bag-create',
                'update' => 'Bag-edit',
                'delete' => 'Bag-delete',
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

        $this->middleware(function ($request, $next) {
            if ($request->service_type) {
                $serviceStatuses = Franchise::checkServiceStatus($request->service_type);
                if (!$serviceStatuses) {
                    return abort(403, 'Service not available.');
                }
            }
            return $next($request);
        });
    }




    public function getUniqueCode($serviceType)

    {

        $randomNumber = rand(1, 9);

        $serviceTypeValue = FranchiseBarcodeSeries::getServiceTypeDB($serviceType);

        $range_start_column = "bag_barcode_range_start_{$serviceTypeValue}";

        $range_end_column = "bag_barcode_range_end_{$serviceTypeValue}";

        $last_code_issued_column = "last_bag_code_issued_{$serviceTypeValue}";



        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", Franchise::getFranchiseId())->first();



        if ($franchiseSeriesDetails) {

            if ($franchiseSeriesDetails->{$range_end_column} != null && $franchiseSeriesDetails->{$range_end_column} > $franchiseSeriesDetails->{$last_code_issued_column}) {

                if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                    $last_bag_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                    $seriesNum = str_pad($last_bag_code_issued + 1, 7, '0', STR_PAD_LEFT);
                } else {

                    $seriesNum = str_pad($franchiseSeriesDetails->{$range_start_column}, 7, '0', STR_PAD_LEFT);
                }

                $serviceCode = FranchiseBarcodeSeries::getServiceCode($serviceType);

                $code =  FranchiseBarcodeSeries::$BAGCODE . $serviceCode . $seriesNum . $randomNumber . 'ND';

                return $code;
            } else {

                return 'Barcode series end';
            }
        } else {

            return 'Barcodes not assigned';
        }
    }


    public function getBarcodeAvailableCount($serviceType)
    {

        $serviceTypeValue = GotogoSpeedPostParcel::getServiceTypeDB($serviceType);
        $range_start_column = "bag_barcode_range_start_{$serviceTypeValue}";
        $range_end_column = "bag_barcode_range_end_{$serviceTypeValue}";
        $last_code_issued_column = "last_bag_code_issued_{$serviceTypeValue}";

        $franchiseId = Franchise::getFranchiseId();
        $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", $franchiseId)->first();

        if (!$franchiseSeriesDetails || $franchiseSeriesDetails->{$range_end_column} === null) {
            return 0; // No barcodes available
        }

        $start = (int) $franchiseSeriesDetails->{$range_start_column};
        $end = (int) $franchiseSeriesDetails->{$range_end_column};
        $lastIssued = (int) ($franchiseSeriesDetails->{$last_code_issued_column} ?? $start - 1);

        // Calculate available barcodes
        return max(0, ($end - $lastIssued));
    }

    public function showcreatedBags(Request $request)
    {
       
        // return $this->getBarcodeAvailableCount($request->service_type);

        $generator = new BarcodeGeneratorPNG();

        $code =   $this->getUniqueCode($request->service_type);

        $title = FranchiseBag::getServiceType($request->service_type);

        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        $barcode = base64_encode($barcode);

        $availableBarcodes = $this->getBarcodeAvailableCount($request->service_type);


        // Fetch bags for Franchise

        $bagsforDeliveryBoy = FranchiseBag::where('franchise_id', Franchise::getFranchiseId())

            ->whereNotNull('delivery_boy_id')

            ->whereDate('created_at', Carbon::today())

            ->orderBy('created_at', 'desc')

            ->with('deliveryBoy')

            ->get();


        // Fetch bags for CMS

        $bagsforCMS = FranchiseBag::where('franchise_id', Franchise::getFranchiseId())

            ->where('service_type', $request->service_type)

            ->whereNotNull('cms_id')

            ->whereDate('created_at', Carbon::today())

            ->orderBy('created_at', 'desc')

            ->with('cms')

            ->get();





        // return $bag;

        $franchise_details = Franchise::where('id', Franchise::getFranchiseId())->select('franchise_no', 'gotogo_balance', 'indiapost_balance')->first();

        $allcms = CMS::all();

        $deliveryBoy = DeliveryBoy::where('franchise_id', Franchise::getFranchiseId())->where("status", 1)->get();


        $service_type = $request->service_type;
         $linkDetail= NULL;
        if ($service_type == 1 || $service_type == 3 || $service_type == 4) {
            $linkDetail = GotogoLink::where('franchise_no', $franchise_details->franchise_no)->first();
        }

        if ($service_type == 5 || $service_type == 6 || $service_type == 7) {
            $linkDetail = IndiaPostLink::where('franchise_no', $franchise_details->franchise_no)->first();

            if (!$linkDetail) {
                return redirect()->back()->with('error', 'No link details found');
            }
        }

        return view('franchise.bag.createdBags', compact('bagsforDeliveryBoy', 'bagsforCMS', 'barcode', 'code', 'franchise_details', 'allcms', 'deliveryBoy', 'title', 'linkDetail', 'availableBarcodes'));
    }


    public function cretedBagsSearch(Request $request)
    {
       
        $allcms = CMS::all();
        $deliveryBoy = DeliveryBoy::where('franchise_id', Franchise::getFranchiseId())->get();

        $franchiseId = Franchise::getFranchiseId();
        $bagType = $request->input('bag_type');
        $serviceType = $request->input('serviceType');
        $searchKey = $request->input('searchKey');
        $createdDate = $request->input('createdDate');
        $formattedDate = $createdDate ? Carbon::parse($createdDate)->format('Y-m-d') : Carbon::today()->format('Y-m-d');


        // Determine the type of bags to query based on 'bag_type'
        if ($bagType === "bagsCreatedForCMS") {

            if ($searchKey) {
                $Model = FranchiseBag::getServiceModel($serviceType);

                $parselToSearch = $Model::where('barcode_no', $searchKey)
                    ->select('destination_franchise_bag_id', 'source_franchise_bag_id', 'id')
                    ->first();

                if ($parselToSearch) {


                    // search if parcel is present in new bag table
                    $source_franchise_bag_id = $parselToSearch->source_franchise_bag_id;
                    $destination_franchise_bag_id = $parselToSearch->destination_franchise_bag_id;
                    $desiredBags = [];

                    if ($source_franchise_bag_id) {
                        $potentialBag = FranchiseBag::where('id', $source_franchise_bag_id)
                            ->whereDate('created_at', $formattedDate)->first();

                        if ($potentialBag && $potentialBag->franchise_id == $franchiseId) {
                            $desiredBags[] = $potentialBag;
                        }
                    }

                    if ($destination_franchise_bag_id) {
                        $potentialBag = FranchiseBag::where('id', $destination_franchise_bag_id)
                            ->whereDate('created_at', $formattedDate)->first();

                        if ($potentialBag && $potentialBag->franchise_id == $franchiseId) {
                            $desiredBags[] = $potentialBag;
                        }
                    }

                    // check if parcel is present in returned bag table
                    $returnBagParcel = ReturnedParcel::where('parcel_id', $parselToSearch->id)
                        ->where('service_type', $serviceType)
                        ->first();

                    if ($returnBagParcel) {
                        $return_source_franchise_bag_id = $returnBagParcel->source_franchise_bag_id;
                        $return_destination_franchise_bag_id = $returnBagParcel->destination_franchise_bag_id;

                        if ($return_source_franchise_bag_id) {
                            $potentialBag = FranchiseBag::where('id', $return_source_franchise_bag_id)
                                ->whereDate('created_at', $formattedDate)->first();

                            if ($potentialBag && $potentialBag->franchise_id == $franchiseId) {
                                $desiredBags[] = $potentialBag;
                            }
                        }

                        if ($return_destination_franchise_bag_id) {  // ✅ Fixed this check
                            $potentialBag = FranchiseBag::where('id', $return_destination_franchise_bag_id)
                                ->whereDate('created_at', $formattedDate)->first();

                            if ($potentialBag && $potentialBag->franchise_id == $franchiseId) {
                                $desiredBags[] = $potentialBag;
                            }
                        }
                    }
                }
            }

            // Base query for FranchiseBag
            $bagsQuery = FranchiseBag::where('franchise_id', $franchiseId)
                ->where('service_type', $serviceType)
                ->whereNotNull('cms_id')
                ->orderBy('created_at', 'desc');

            // Apply date filter if $createdDate is provided
            $bagsQuery->when($formattedDate, function ($query) use ($formattedDate) {
                return $query->whereDate('created_at', $formattedDate);
            });

            $bagsQuery->whereNotNull('cms_id')
                ->with('cms')
                ->when($searchKey, function ($query) use ($searchKey) {
                    $query->where(function ($q) use ($searchKey) {
                        $q->whereHas('cms', function ($q) use ($searchKey) {
                            $q->where('pincode', 'like', '%' . $searchKey . '%')
                                ->orWhere('city', 'like', '%' . $searchKey . '%');
                        })->orWhere('barcode_no', 'like', '%' . $searchKey . '%');
                    });
                });


            if (!empty($desiredBags)) {
                $filteredBags = collect($desiredBags);
            } else {
                $filteredBags = $bagsQuery->get();
            }

            $html = '';
            foreach ($filteredBags as $bag) {
                ob_start();
?>
                <div class="col-lg-2 d-flex" style="position:relative;padding:6px">
                    <!-- Dropdown Menu (Outside Clickable Area) -->
                    <div class="dropdown dropdown-action" style="position: absolute; right: 0px; z-index: 5; top: 9px;">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_role<?= $bag->id ?>">
                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                            </a>
                            <a class="dropdown-item" onclick="delete_modal(<?= $bag->id ?>)" href="#" data-bs-toggle="modal" data-bs-target="#delete_asset">
                                <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                            </a>
                        </div>
                    </div>

                    <!-- Clickable Profile Widget -->
                    <a href="<?= route('franchise.bag.viewParcelCMSCreated', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>" class="w-100 text-decoration-none">
                        <div class="profile-widget w-100 bagCard <?= $searchKey ? 'filteredColor' : '' ?>">
                            <div class="dash-card-icon">
                                <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                            </div>

                            <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; font-weight: bold; color: #333;">
                                <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                    <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                                </span>
                            </h4>

                            <img src="data:image/png;base64,<?= $bag->barcode_img_src ?>" alt="Barcode" style="height: 50px; display: none;" />

                            <h5 class="user-name mt-2 mb-0 text-ellipsis">
                                ID: <?= $bag->barcode_no ?>
                            </h5>
                            <h6 class="user-name mb-0 text-ellipsis" style="padding: 2px;">
                                CMSCode: <?= $bag->cms->cms_no ?>
                            </h6>
                            <h6 class="user-name mb-0 text-ellipsis">
                                City: <?= $bag->cms->pincode ?>
                            </h6>
                        </div>
                    </a>
                </div>



                <div id="edit_role<?= $bag->id ?>" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content modal-md">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Bag</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= route('franchise.bag.update', ['id' => $bag->id]) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">CMS <span class="text-danger">*</span></label>
                                        <select name="cms" class="floating">
                                            <option value=""> -- Select CMS -- </option>
                                            <?php foreach ($allcms as $key => $value) : ?>
                                                <option value="<?= $value->id ?>" <?= $bag->cms_id == $value->id ? 'selected' : '' ?>>Name:<?= $value->name ?> || City:<?= $value->pincode ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                $html .= ob_get_clean();
            }

            $allotherBags = FranchiseBag::where('franchise_id', $franchiseId)
                ->where('service_type', $serviceType)
                ->whereNotNull('cms_id')
                ->whereNotIn('id', $filteredBags->pluck('id')->toArray())
                ->orderBy('created_at', 'desc')
                ->with('cms')
                ->whereDate('created_at', $formattedDate)
                ->get();

            foreach ($allotherBags as $bag) {
                ob_start();
            ?>
                <div class="col-lg-2 d-flex" style="position:relative;padding:6px">
                    <!-- Dropdown Menu (Placed Outside the <a> Tag) -->
                    <div class="dropdown dropdown-action" style="position: absolute; right: 0px; z-index: 5; top: 9px;">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_role<?= $bag->id ?>">
                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                            </a>
                            <a class="dropdown-item" onclick="delete_modal(<?= $bag->id ?>)" href="#" data-bs-toggle="modal" data-bs-target="#delete_asset">
                                <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                            </a>
                        </div>
                    </div>

                    <!-- Clickable Profile Widget -->
                    <a href="<?= route('franchise.bag.viewParcelCMSCreated', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>" class="w-100">
                        <div class="profile-widget w-100 bagCard">
                            <div class="dash-card-icon">
                                <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                            </div>

                            <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; font-weight: bold; color: #333;">
                                <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                    <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                                </span>
                            </h4>

                            <img src="data:image/png;base64,<?= $bag->barcode_img_src ?>" alt="Barcode" style="height: 50px; display: none;" />

                            <h5 class="user-name mt-2 mb-0 text-ellipsis">
                                ID: <?= $bag->barcode_no ?>
                            </h5>
                            <h6 class="user-name mb-0 text-ellipsis" style="padding: 2px;">
                                CMSCode: <?= $bag->cms->cms_no ?>
                            </h6>
                            <h6 class="user-name mb-0 text-ellipsis">
                                City: <?= $bag->cms->pincode ?>
                            </h6>
                        </div>
                    </a>
                </div>


                <div id="edit_role<?= $bag->id ?>" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content modal-md">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Bag</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= route('franchise.bag.update', ['id' => $bag->id]) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">CMS <span class="text-danger">*</span></label>
                                        <select name="cms" class="floating">
                                            <option value=""> -- Select CMS -- </option>
                                            <?php foreach ($allcms as $key => $value) : ?>
                                                <option value="<?= $value->id ?>" <?= $bag->cms_id == $value->id ? 'selected' : '' ?>>Name:<?= $value->name ?> || City:<?= $value->pincode ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                $html .= ob_get_clean();
            }

            // Return HTML as response
            return response()->json(['html' => $html]);
        } elseif ($bagType === "bagsCreatedForDeliveryBoy") {

            if ($searchKey) {
                $desiredBags = [];

                // Get the correct model for the service type
                $Model = FranchiseBag::getServiceModel($serviceType);

                // Find parcel by barcode
                $parselToSearch = $Model::where('barcode_no', $searchKey)
                    ->select('id')
                    ->first();

                if ($parselToSearch) {
                    // Get bag IDs associated with the parcel
                    $bagIds = DeliveryBoyBagParcel::whereJsonContains('parcels', [['parcel_id' => intval($parselToSearch->id), 'service_type' => intval($serviceType)]])
                        ->pluck('bag_id');

                    // Get Franchise Bags
                    $allFranchiseBag = FranchiseBag::whereIn('id', $bagIds)
                        ->where('franchise_id', $franchiseId)
                        ->where('service_type', $serviceType)
                        ->get();
                    // Ensure bags belong to the current franchise (Filtering collection)
                    $allFranchiseBag = $allFranchiseBag->filter(function ($bag) use ($bagIds) {

                        return in_array($bag->id, $bagIds->toArray());
                    });
                    $desiredBags = $allFranchiseBag;
                }
            }



            // Base query for FranchiseBag
            $bagsQuery = FranchiseBag::where('franchise_id', $franchiseId)
                ->where('service_type', $serviceType)
                ->whereNotNull('delivery_boy_id')
                ->orderBy('created_at', 'desc');

            // Apply date filter if $createdDate is provided
            $bagsQuery->when($formattedDate, function ($query) use ($formattedDate) {
                return $query->whereDate('created_at', $formattedDate);
            });

            $bagsQuery->whereNotNull('delivery_boy_id')
                ->with('deliveryBoy')
                ->when($searchKey, function ($query) use ($searchKey) {
                    $query->where(function ($q) use ($searchKey) {
                        $q->whereHas('deliveryBoy', function ($q) use ($searchKey) {
                            $q->where('pincode', 'like', '%' . $searchKey . '%')
                                ->orWhere('city', 'like', '%' . $searchKey . '%');
                        })->orWhere('barcode_no', 'like', '%' . $searchKey . '%');
                    });
                });

            if (!empty($desiredBags)) {
                $filteredBags = collect($desiredBags);
            } else {
                $filteredBags = $bagsQuery->get();
            }


            $html = '';
            foreach ($filteredBags as $bag) {
                ob_start();
            ?>
                <div class="col-lg-2 d-flex" style="position:relative;padding:6px">
                    <!-- Dropdown Menu (Outside Clickable Area) -->
                    <div class="dropdown dropdown-action" style="position: absolute; right: 0px; z-index: 5; top: 9px;">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_role<?= $bag->id ?>">
                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                            </a>
                            <a class="dropdown-item" onclick="delete_modal(<?= $bag->id ?>)" href="#" data-bs-toggle="modal" data-bs-target="#delete_asset">
                                <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                            </a>
                        </div>
                    </div>

                    <!-- Clickable Profile Widget -->
                    <a href="<?= route('franchise.bag.viewParcelDeliveryBoyCreated', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>" class="w-100 text-decoration-none">
                        <div class="profile-widget w-100 bagCard <?= $searchKey ? 'filteredColor' : '' ?>">
                            <div class="dash-card-icon">
                                <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                            </div>

                            <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; font-weight: bold; color: #333;">
                                <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                    <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                                </span>
                            </h4>

                            <img src="data:image/png;base64,<?= $bag->barcode_img_src ?>" alt="Barcode" style="height: 50px; display: none;" />

                            <h5 class="user-name mt-2 mb-0 text-ellipsis">
                                ID: <?= $bag->bag_id ?>
                            </h5>
                            <h6 class="user-name mb-0 text-ellipsis" style="padding: 2px;">
                                Pincode: <?= $bag->deliveryBoy->pincode ?>
                            </h6>
                            <h6 class="user-name mb-0 text-ellipsis">
                                City: <?= $bag->deliveryBoy->city ?>
                            </h6>
                        </div>
                    </a>
                </div>


                <div id="edit_role<?= $bag->id ?>" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content modal-md">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Bag</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= route('franchise.bag.update', ['id' => $bag->id]) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Delivery Boy <span class="text-danger">*</span></label>
                                        <select name="delivery_boy" class="floating">
                                            <option value=""> -- Select Delivery Boy -- </option>
                                            <?php foreach ($deliveryBoy as $key => $value) : ?>
                                                <option value="<?= $value->id ?>" <?= $bag->delivery_boy_id == $value->id ? 'selected' : '' ?>>Name:<?= $value->name ?> || City:<?= $value->pincode ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                $html .= ob_get_clean();
            }

            $allotherBags = FranchiseBag::where('franchise_id', $franchiseId)
                ->where('service_type', $serviceType)
                ->whereNotNull('delivery_boy_id')
                ->whereNotIn('id', $filteredBags->pluck('id')->toArray())
                ->orderBy('created_at', 'desc')
                ->with('deliveryBoy')
                ->whereDate('created_at', $formattedDate)
                ->get();

            foreach ($allotherBags as $bag) {
                ob_start();
            ?>
                <div class="col-lg-2 d-flex" style="position:relative;padding:6px">
                    <!-- Dropdown Menu (Outside Clickable Area) -->
                    <div class="dropdown dropdown-action" style="position: absolute; right: 0px; z-index: 5; top: 9px;">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_role<?= $bag->id ?>">
                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                            </a>
                            <a class="dropdown-item" onclick="delete_modal(<?= $bag->id ?>)" href="#" data-bs-toggle="modal" data-bs-target="#delete_asset">
                                <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                            </a>
                        </div>
                    </div>

                    <!-- Clickable Profile Widget -->
                    <a href="<?= route('franchise.bag.viewParcelDeliveryBoyCreated', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>" class="w-100 text-decoration-none">
                        <div class="profile-widget w-100 bagCard">
                            <div class="dash-card-icon">
                                <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                            </div>

                            <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; font-weight: bold; color: #333;">
                                <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                    <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                                </span>
                            </h4>

                            <img src="data:image/png;base64,<?= $bag->barcode_img_src ?>" alt="Barcode" style="height: 50px; display: none;" />

                            <h5 class="user-name mt-2 mb-0 text-ellipsis">ID: <?= $bag->bag_id ?></h5>
                            <h6 class="user-name mb-0 text-ellipsis" style="padding: 2px;">Pincode: <?= $bag->deliveryBoy->pincode ?></h6>
                            <h6 class="user-name mb-0 text-ellipsis">City: <?= $bag->deliveryBoy->city ?></h6>
                        </div>
                    </a>
                </div>


                <div id="edit_role<?= $bag->id ?>" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content modal-md">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Bag</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= route('franchise.bag.update', ['id' => $bag->id]) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Delivery Boy <span class="text-danger">*</span></label>
                                        <select name="delivery_boy" class="floating">
                                            <option value=""> -- Select Delivery Boy -- </option>
                                            <?php foreach ($deliveryBoy as $key => $value) : ?>
                                                <option value="<?= $value->id ?>" <?= $bag->delivery_boy_id == $value->id ? 'selected' : '' ?>>Name:<?= $value->name ?> || City:<?= $value->pincode ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $html .= ob_get_clean();
            }

            // Return HTML as response
            return response()->json(['html' => $html]);
        }
    }


    public function showRecievedBags(Request $request)
    {

        $bagfromCMS = CMSBag::where('service_type', $request->service_type)
            ->where('franchise_id', Franchise::getFranchiseId())
            ->whereDate('received_date', Carbon::today())
            ->orderBy('created_at', 'desc')->get();

        $title = FranchiseBag::getServiceType($request->service_type);
        $service_type = $request->service_type;

        return view('franchise.bag.recievedBags', compact('bagfromCMS', 'service_type', 'title'));
    }


    public function sendNotificationToUser($recieverDetails, $parcel, $service_type)
    {
        try {
            \Log::info("sendNotificationToUser function called", [
                'parcel_id' => $parcel->id ?? null,
                'franchise_id' => $franchise->id ?? null
            ]);

            \Log::info("Fetching user with phone: {$parcel->pickup_mobile}");

            $userPhone = $parcel->pickup_mobile;
            $user = User::where('phone', $userPhone)->first();

            if (!$user) {
                \Log::error("User not found for phone: {$userPhone}");
                return;
            }

            \Log::info("User found", ['user_id' => $user->id]);

            $token = $user->fcm_token;
            if ($token) {
                \Log::info("FCM token found", ['user_id' => $user->id, 'fcm_token' => $token]);

                $title = FranchiseBag::getServiceType($service_type);
                $body = "✅ Order Update: Article No. {$parcel->barcode_no}\n" .
                    "📅 Date: " . date('M d, Y, h:i A', strtotime(now())) . "\n" .
                    "📍 location: {$recieverDetails->address}\n" .
                    "📍 To: {$parcel->consignee_address}\n" .
                    "🚀 Your order has reached  {$recieverDetails->address}";

                \Log::info("Preparing to send push notification", [
                    'title' => $title,
                    'body' => $body,
                    'user_id' => $user->id,
                    'parcel_id' => $parcel->id
                ]);

                $notification = new FranchisePushNotification($token, $parcel, $title, $body);
                $notification->sendPushNotification();

                \Log::info("Push notification sent successfully", ['user_id' => $user->id]);

                \Log::info("Saving user notification record");

                $parcel->userNotifications()->create([
                    'user_id' => $user->id,
                    'service_type' => get_class($parcel),
                    'parcel_id' => $parcel->id,
                    'message' => "Order Placed successfully",
                    'barcode_no' => $parcel->barcode_no,
                    'body' => $body,
                    'current_location' => $recieverDetails->address,
                ]);

                \Log::info("User notification record created successfully");
            } else {
                \Log::error("FCM token not found for user", ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            \Log::error("Error in sendNotificationToUser: " . $e->getMessage(), [
                'parcel_id' => $parcel->id ?? null,
                'user_phone' => $userPhone ?? null,
                'exception' => $e
            ]);
        }
    }

    public function sendNotificationToFranchise($recieverDetails, $parcel, $service_type)
    {
        try {
            \Log::info("sendNotificationToFranchise started", [
                'parcel_id' => $parcel->id ?? 'N/A',
                'service_type' => $service_type
            ]);

            $franchiseId = $parcel->franchise_id;
            \Log::info("Fetching franchise details", ['franchise_id' => $franchiseId]);

            $franchise = Franchise::findOrFail($franchiseId);

            if (!$franchise) {
                \Log::warning("Franchise not found", ['franchise_id' => $franchiseId]);
                return;
            }

            \Log::info("franchise details", ['$franchise' => $franchise]);


            // Get FCM Token
            $token = $franchise->fcm_token;

            \Log::info("token", ['franchise' => $token]);
            if (!$token) {
                \Log::warning("FCM token not found for franchise", ['franchise_id' => $franchiseId]);
                return;
            }

            \Log::info("Preparing notification", [
                'franchise_id' => $franchiseId,
                'fcm_token' => $token
            ]);

            // Prepare Notification
            $title = FranchiseBag::getServiceType($service_type);
            $body = "✅ Order Update: Article No. {$parcel->barcode_no}\n" .
                "📅 Date: " . date('M d, Y, h:i A', strtotime(now())) . "\n" .
                "📍 location: {$recieverDetails->address}\n" .
                "📍 To: {$parcel->consignee_address}\n" .
                "🚀 Order has reached to Franchise in {$recieverDetails->address}";

            \Log::info("Sending push notification", [
                'title' => $title,
                'body' => $body
            ]);

            // Send Push Notification
            $notification = new FranchisePushNotification($token, $parcel, $title, $body);
            $notification->sendPushNotification();

            \Log::info("Notification sent to franchise", ['franchise_id' => $franchiseId]);

            // Store Notification in Database
            $parcel->franchiseNotifications()->create([
                'franchise_id' => $franchiseId,
                'service_type' => get_class($parcel),
                'parcel_id' => $parcel->id,
                'message' => "Order has reached to CMS",
                'barcode_no' => $parcel->barcode_no,
                'body' => $body,
                'current_location' => $recieverDetails->address,
            ]);

            \Log::info("Franchise notification created in database", [
                'franchise_id' => $franchiseId,
                'parcel_id' => $parcel->id
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in sendNotificationToFranchise", [
                'parcel_id' => $parcel->id ?? 'N/A',
                'franchise_id' => $franchiseId ?? 'N/A',
                'error' => $e->getMessage(),
                'exception' => $e
            ]);
        }
    }


    public function scanBagReceived(Request $request, RateCalculator $ratecalculator)

    {

        if ($request->isMethod('post')) {

            try {

                $franchiseId = Franchise::getFranchiseId();
                $franchise_details = Franchise::findOrFail($franchiseId);

                $bagtoUpdate =  CMSBag::where('barcode_no', $request->barcode)->where("franchise_id", $franchiseId)->first();
                // Check if the parcel exists
                if (!$bagtoUpdate) {
                    return response()->json(['status' => 'error', 'message' => 'Bag not found'], 404);
                }

                // Update the record
                $bagtoUpdate->received_date =  Carbon::today()->toDateString();
                $bagtoUpdate->save();


                $Model = CMSBag::getServiceModel($request->id);

                $datas =  $Model::where('destination_cms_bag_id', $bagtoUpdate->id)
                    ->orWhere('source_cms_bag_id', $bagtoUpdate->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $service_type = $request->id;

                foreach ($datas as $data) {

                    if ($service_type == 1 || $service_type == 3 || $service_type == 4) {
                        $amount = $ratecalculator->calculateCommissionForGotogoPost($data->package_weight, 'franchise', $service_type);
                    }

                    if ($service_type == 5 || $service_type == 6 || $service_type == 7) {
                        $amount = $ratecalculator->calculateCommissionForIndiaPost($data->package_weight, 'franchise', $service_type);
                    }

                    FranchiseCommissionDetail::create([
                        "franchise_id" => Franchise::getFranchiseId(),
                        "service_type" => $bagtoUpdate->id,
                        "amount" => $data->payment_amount,
                        "commission" => $amount,
                    ]);

                    $TrackingModel = FranchiseBag::getTrackingModel($service_type);
                    $trackingModalToUpdate =  $TrackingModel::where('barcode_no', $data->barcode_no)->first();
                    $trackingModalToUpdate->destination_franchise_id = Franchise::getFranchiseId();
                    $trackingModalToUpdate->destination_franchise_receiving_datetime = now();
                    $trackingModalToUpdate->destination_franchise_location = $franchise_details->address;
                    $trackingModalToUpdate->save();


                    $this->sendNotificationToFranchise($franchise_details, $data, $service_type);
                    $this->sendNotificationToUser($franchise_details, $data, $service_type);
                }


                $bagfromCMS = CMSBag::where('service_type', $request->id)
                    ->where('franchise_id', Franchise::getFranchiseId())
                    ->whereDate('received_date', Carbon::today())
                    ->with('cms')
                    ->orderBy('received_date', 'desc')
                    ->get();

                $html = '';
                foreach ($bagfromCMS as $bag) {
                    ob_start();
                ?>
                    <div class="col-lg-2 d-flex" style="position:relative; padding:6px;">
                        <div class="profile-widget w-100 bagCard" style="position: relative;">
                            <!-- Full clickable area -->
                            <a href="<?= route('franchise.bag.viewParcelCMSReceived', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>"
                                style="position: absolute; inset: 0; z-index: 1;"></a>

                            <div class="dash-card-icon">
                                <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                            </div>

                            <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; font-weight: bold; color: #333;">
                                <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                    <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                                </span>
                            </h4>
                            <h5 class="user-name mt-1 mb-0 text-ellipsis">ID: <?= $bag->barcode_no ?></h5>
                            <h6 class="user-name mt-1 mb-0 text-ellipsis">CMSCode: <?= $bag->cms->cms_no ?></h6>
                            <h6 class="user-name mt-1 mb-0 text-ellipsis">City: <?= $bag->cms->pincode ?></h6>
                        </div>
                    </div>


            <?php
                    $html .= ob_get_clean();
                }



                return response()->json(['status' => 'success', 'html' => $html]);
            } catch (\Throwable $th) {

                return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
            }
        }



        return response()->json(['status' => 'error', 'message' => 'Invalid request method'], 400);
    }


    public function receivedBagsSearch(Request $request)
    {

        $franchiseId = Franchise::getFranchiseId();
        $serviceType = $request->input('serviceType');
        $searchKey = $request->input('searchKey');
        $createdDate = $request->input('createdDate');
        $formattedDate = $createdDate ? Carbon::parse($createdDate)->format('Y-m-d') : Carbon::today()->format('Y-m-d');


        if ($searchKey) {
            $Model = FranchiseBag::getServiceModel($serviceType);
            $parselToSearch = $Model::where('barcode_no', $searchKey)
                ->select('destination_cms_bag_id', 'source_cms_bag_id', 'id')
                ->first();

            if ($parselToSearch) {
                $source_cms_bag_id = $parselToSearch->source_cms_bag_id;
                $destination_cms_bag_id = $parselToSearch->destination_cms_bag_id;
                $desiredBags = [];

                // ✅ New Bags Search
                if ($source_cms_bag_id) {
                    $potentialBag = CMSBag::where('id', $source_cms_bag_id)
                        ->whereDate('created_at', $formattedDate)
                        ->first();

                    if ($potentialBag && $potentialBag->franchise_id == $franchiseId) {
                        $desiredBags[] = $potentialBag;
                    }
                }

                if ($destination_cms_bag_id) {
                    $potentialBag = CMSBag::where('id', $destination_cms_bag_id)
                        ->whereDate('created_at', $formattedDate)
                        ->first();

                    if ($potentialBag && $potentialBag->franchise_id == $franchiseId) {
                        $desiredBags[] = $potentialBag;
                    }
                }

                // ✅ Return Bags Search
                $returnBagParcel = ReturnedParcel::where('parcel_id', $parselToSearch->id)->first();

                if ($returnBagParcel) {
                    $return_source_cms_bag_id = $returnBagParcel->source_cms_bag_id;
                    $return_destination_cms_bag_id = $returnBagParcel->destination_cms_bag_id;

                    if ($return_source_cms_bag_id) {
                        $potentialBag = CMSBag::where('id', $return_source_cms_bag_id)
                            ->whereDate('created_at', $formattedDate)
                            ->first();

                        if ($potentialBag && $potentialBag->franchise_id == $franchiseId) {
                            $desiredBags[] = $potentialBag;
                        }
                    }

                    if ($return_destination_cms_bag_id) {
                        $potentialBag = CMSBag::where('id', $return_destination_cms_bag_id)
                            ->whereDate('created_at', $formattedDate)
                            ->first();

                        if ($potentialBag && $potentialBag->franchise_id == $franchiseId) {
                            $desiredBags[] = $potentialBag;
                        }
                    }
                }
            }
        }

        // Base query for FranchiseBag
        $bagsQuery = CMSBag::where('service_type', $serviceType)
            ->where('franchise_id', Franchise::getFranchiseId())
            ->whereDate('created_at', $formattedDate)
            ->orderBy('created_at', 'desc');
        // Apply date filter if $createdDate is provided
        $bagsQuery->when($formattedDate, function ($query) use ($formattedDate) {
            return $query->whereDate('created_at', $formattedDate);
        });


        // Determine the type of bags to query based on 'bag_type'
        $bagsQuery->whereNotNull('cms_id')
            ->with('cms')
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where(function ($q) use ($searchKey) {
                    $q->whereHas('cms', function ($q) use ($searchKey) {
                        $q->where('pincode', 'like', '%' . $searchKey . '%')
                            ->orWhere('city', 'like', '%' . $searchKey . '%');
                    })->orWhere('barcode_no', 'like', '%' . $searchKey . '%');
                });
            });


        if (!empty($desiredBags)) {
            $filteredBags = collect($desiredBags);
        } else {
            $filteredBags = $bagsQuery->get();
        }

        $html = '';
        foreach ($filteredBags as $bag) {
            ob_start();
            ?>
            <div class="col-lg-2 d-flex" style="position:relative; padding:6px;">
                <div class="profile-widget w-100 bagCard <?= $searchKey ? 'filteredColor' : '' ?>" style="position: relative;">
                    <!-- Full clickable area -->
                    <a href="<?= route('franchise.bag.viewParcelCMSCreated', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>"
                        style="position: absolute; inset: 0; z-index: 1;"></a>

                    <div class="dash-card-icon">
                        <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                    </div>

                    <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; font-weight: bold; color: #333;">
                        <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                            <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                        </span>
                    </h4>
                    <h5 class="user-name mt-1 mb-0 text-ellipsis">ID: <?= $bag->barcode_no ?></h5>
                    <h6 class="user-name mt-1 mb-0 text-ellipsis">CMSCode: <?= $bag->cms->cms_no ?></h6>
                    <h6 class="user-name mt-1 mb-0 text-ellipsis">City: <?= $bag->cms->pincode ?></h6>
                </div>
            </div>

        <?php
            $html .= ob_get_clean();
        }

        $allotherBags = CMSBag::where('franchise_id', $franchiseId)
            ->where('service_type', $serviceType)
            ->whereNotIn('id', $filteredBags->pluck('id')->toArray())
            ->orderBy('created_at', 'desc')
            ->with('cms')
            ->whereDate('created_at', $formattedDate)
            ->get();

        foreach ($allotherBags as $bag) {
            ob_start();
        ?>
            <div class="col-lg-2 d-flex" style="position:relative; padding:6px;">
                <div class="profile-widget w-100 bagCard" style="position: relative;">
                    <!-- Full clickable area -->
                    <a href="<?= route('franchise.bag.viewParcelCMSCreated', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>"
                        style="position: absolute; inset: 0; z-index: 1;"></a>

                    <div class="dash-card-icon">
                        <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                    </div>

                    <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; font-weight: bold; color: #333;">
                        <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                            <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                        </span>
                    </h4>
                    <h5 class="user-name mt-1 mb-0 text-ellipsis">ID: <?= $bag->barcode_no ?></h5>
                    <h6 class="user-name mt-1 mb-0 text-ellipsis">CMSCode: <?= $bag->cms->cms_no ?></h6>
                    <h6 class="user-name mt-1 mb-0 text-ellipsis">City: <?= $bag->cms->pincode ?></h6>
                </div>
            </div>

<?php
            $html .= ob_get_clean();
        }

        // Return HTML as response
        return response()->json(['html' => $html]);
    }





    public function store(Request $request)

    {

        if ($request->barcode_no == 'Barcode series end' || $request->barcode_no == 'Barcodes not assigned') {

            return back()->with('error', 'barcode series end for this services');
        }

        try {

            do {
                $unique_bag_id = mt_rand(10000, 9999999);
            } while (FranchiseBag::where('bag_id', $unique_bag_id)->exists());



            if ($request->role == 'cms') {

                FranchiseBag::create([

                    'franchise_id' => Franchise::getFranchiseId(),

                    'bag_id' => $unique_bag_id,

                    'return_type' => $request->cms_bag_type,

                    'cms_id' => $request->bagAssignedToCms,

                    'service_type' => $request->service_type,

                    'barcode_no' => $request->barcode_no,

                    'barcode_img_src' => $request->barcode_img_src,

                ]);



                $franchiseSeriesDetails = FranchiseBarcodeSeries::where("franchise_id", Auth::id())->first();

                $serviceTypeValue = FranchiseBarcodeSeries::getServiceTypeDB($request->service_type);

                $range_start_column = "bag_barcode_range_start_{$serviceTypeValue}";

                $last_code_issued_column = "last_bag_code_issued_{$serviceTypeValue}";



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

                $franchiseBarcode->barcodes = $request->barcode_no;

                $franchiseBarcode->franchise_barcodeseries_id = $franchiseSeriesDetails->id;

                $franchiseBarcode->save();
            }



            if ($request->role == 'delboy') {

                FranchiseBag::create([

                    'franchise_id' => Franchise::getFranchiseId(),

                    'bag_id' => $unique_bag_id,

                    'return_type' => 0,

                    'delivery_boy_id' => $request->bagAssignedTodelboy,

                    'service_type' => $request->service_type,

                ]);
            }



            return back()->with('success', 'Bag Added successfully');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }





    public function update(Request $request, $id)

    {
        try {

            $bag = FranchiseBag::findOrfail($id);

            if ($bag->cms_id) {
                $bag->cms_id = $request->cms;
            }


            if ($bag->delivery_boy_id) {
                $bag->delivery_boy_id = $request->deliveryBoy;
            }

            $bag->save();

            return back()->with('success', 'Bag Updated Successfully');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }



    public function delete($id)

    {
        try {
            FranchiseBag::findorfail($id)->delete();

            return back()->with('success', 'Bag Deleted Successfully');
        } catch (\Throwable $th) {

            return back()->with('error', $th->getMessage());
        }
    }





    public function getData(Request $request)

    {

        try {

            if ($request->role == 'cms') {

                $data = CMS::all();
            } elseif ($request->role == 'delboy') {

                $data = DeliveryBoy::where('franchise_id', Franchise::getFranchiseId())->get();
            }



            return response()->json([

                'status' => 200,

                'message' => 'Data retrieved successfully',

                'data' => $data

            ]);
        } catch (\Exception $e) {

            return response()->json([

                'status' => 500,

                'message' => 'An error occurred: ' . $e->getMessage()

            ]);
        }
    }



    public function assignParcel(Request $request, $id)
    {
     
        if (!$request->isMethod('post')) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request method'], 400);
        }

        try {

            $barcode_no = $request->barcode;
            $bag = FranchiseBag::where('id', $id)->first();
            if ($bag->delivery_boy_id) {
                $BookingModel = FranchiseBag::getBookingModelByBarcode($barcode_no);
                $service_type = FranchiseBag::getServiceTypeFromModel($BookingModel);
            } else {
                $service_type = $request->service_type;
            }


            $Model = FranchiseBag::getServiceModel($service_type);
            $TrackingModel = FranchiseBag::getTrackingModel($service_type);
             $parcelToUpdate = $Model::where('barcode_no', $barcode_no)->first();
            $trackingModalToUpdate = $TrackingModel::where('barcode_no', $barcode_no)->first();


            if (!$parcelToUpdate) {
                return response()->json(['status' => 404, 'message' => 'Parcel not found'], 404);
            }

            if (!$trackingModalToUpdate) {
                return response()->json(['status' => 404, 'message' => 'Tracking Model not found'], 404);
            }

            if (!$bag) {
                return response()->json(['status' => 404, 'message' => 'Bag not found'], 404);
            }


            if ($bag->cms_id) {
                $isReturned = $bag->return_type == 1;
            } else {
                $isReturned = ReturnedParcel::where('parcel_id', $parcelToUpdate->id)
                    ->where('service_type', $service_type)
                    ->exists();
            }

            // ✅ Return Type Check
            if ($isReturned) {
                return $this->handleReturnParcels($parcelToUpdate, $service_type, $bag);
            } else {
                // ✅ Normal Parcel Logic
                 if ($parcelToUpdate->franchise_id == $bag->franchise_id) {
                      if ($parcelToUpdate->source_franchise_bag_id) {
                         return response()->json([
                               'status' => 'error',
                               'message' => 'Parcel already exists in a bag.'
                             ], 409); // 409 Conflict is more appropriate here
                         }
                       }


                return $this->handleNormalParcels($parcelToUpdate, $trackingModalToUpdate, $service_type, $bag);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * ✅ Return Type == 1 ka logic
     */
    private function handleReturnParcels($parcelToUpdate, $service_type, $bag)
    {
        $bag_id = $bag->id;
        $parcel_id = $parcelToUpdate->id;

        // ✅ Pehle check karega ki record exist karta hai ya nahi
        $returnedParcel = ReturnedParcel::where('parcel_id', $parcel_id)
            ->where('service_type', $service_type)
            ->first();

        if (!$returnedParcel) {
            $returnedParcel = ReturnedParcel::create([
                'parcel_id' => $parcel_id,
                'service_type' => $service_type,
            ]);;
        }

        // ✅ Update parcel data
        if ($parcelToUpdate->franchise_id != $bag->franchise_id) {

            //this is source francise return bag 
            if ($bag->cms_id) {
                $returnedParcel->source_franchise_bag_id = $bag_id;
            }

            if ($bag->delivery_boy_id) {
                $this->assignParcelToDeliveryBoyBag($bag, $parcelToUpdate, $service_type);
            }
        } else {

            //this is destination franchise return bag
            if ($bag->delivery_boy_id) {
                $this->assignParcelToDeliveryBoyBag($bag, $parcelToUpdate, $service_type);
            }

            if ($bag->cms_id) {
                $returnedParcel->destination_franchise_bag_id = $bag_id;
            }
        }

        $returnedParcel->save();

        return response()->json(['status' => 'success', 'data' => $parcelToUpdate]);
    }


    /**
     * ✅ Return Type == 0 ka logic
     */
    private function handleNormalParcels($parcelToUpdate, $trackingModalToUpdate, $service_type, $bag)
    {
       
        if ($parcelToUpdate->franchise_id == $bag->franchise_id) {

            //this is source bag franchise

            $parcelToUpdate->cms_id = $bag->cms_id;
            $parcelToUpdate->source_franchise_bag_id = $bag->id;
            $trackingModalToUpdate->order_dispatch_datetime = now();

            if ($bag->delivery_boy_id) {
                $this->assignParcelToDeliveryBoyBag($bag, $parcelToUpdate, $service_type);
                $trackingModalToUpdate->delivery_boy_assigned_datetime = now();
            }
        } else {

            //this is destination franchise

            $trackingModalToUpdate->delivery_boy_assigned_datetime = now();
            $parcelToUpdate->destination_franchise_bag_id = $bag->id;

            if ($bag->delivery_boy_id) {
                $this->assignParcelToDeliveryBoyBag($bag, $parcelToUpdate, $service_type);
            }
        }

        $parcelToUpdate->save();
        $trackingModalToUpdate->save();

        return response()->json(['status' => 'success', 'data' => $parcelToUpdate]);
    }


    public static function assignParcelToDeliveryBoyBag($bag, $parcelToUpdate, $service_type)
    {
        if (!$bag->delivery_boy_id) {
            return false;
        }

        // Get or create bag parcel record
        $bagParcel = DeliveryBoyBagParcel::firstOrCreate(
            ['bag_id' => $bag->id],
            ['parcels' => json_encode([])]
        );

        // Decode existing parcels or initialize an empty array
        $parcels = json_decode($bagParcel->parcels, true) ?? [];

        // Check if parcel already exists
        foreach ($parcels as $parcel) {
            if ($parcel['parcel_id'] == $parcelToUpdate->id && $parcel['service_type'] ==  $service_type) {
                return true; // Already exists, no need to add again
            }
        }

        // Add new parcel
        $parcels[] = [
            'parcel_id' => $parcelToUpdate->id,
            'service_type' =>  $service_type
        ];

        // Save updated parcels
        $bagParcel->parcels = json_encode($parcels);
        $bagParcel->save();

        return true;
    }


    public function removeParcel(Request $request, $id)

    {

        $Model = FranchiseBag::getServiceModel($request->service_type);

        $bag = FranchiseBag::where('id', $request->bagId)->first();

        try {

            $parcelToUpdate =  $Model::where('id', $id)->first();

            // Check if the parcel exists

            if (!$parcelToUpdate) {

                return back()->with('error', 'Parcel not found');
            }

            // Update the record

            if ($parcelToUpdate->franchise_id == $bag->franchise_id) {

                $parcelToUpdate->source_franchise_bag_id = null;

                $parcelToUpdate->save();

                return back()->with('success', 'Parcel Removed Successfully from bag');
            } else {

                $parcelToUpdate->destination_franchise_bag_id = null;

                $parcelToUpdate->save();

                return back()->with('success', 'Parcel Removed Successfully from bag');
            }
        } catch (\Throwable $th) {

            return back()->with('error', $th->getMessage());
        }
    }


    public function viewParcels_of_createdBag_for_cms(Request $request, $id)
    {
        $service_type = $request->service_type;
        $Model = FranchiseBag::getServiceModel($service_type);
        $title = FranchiseBag::getServiceType($service_type);

        // ✅ Source ya Destination Franchise Bag ID match kare
        $parcelOfNewBag = $Model::where(function ($query) use ($id) {
            $query->where('source_franchise_bag_id', $id)
                ->orWhere('destination_franchise_bag_id', $id);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Return Bags ka data fetch karo
        $returnBagData = ReturnedParcel::where('service_type', $service_type)
            ->where(function ($query) use ($id) {
                $query->where('source_franchise_bag_id', $id)
                    ->orWhere('destination_franchise_bag_id', $id);
            })
            ->orderBy('created_at', 'desc')
            ->pluck('parcel_id');

        // ✅ Return Bags se linked parcels fetch karo
        $parcelOfReturnedNewBag = $Model::whereIn('id', $returnBagData)
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Merge both collections correctly
        $datas = $parcelOfNewBag->merge($parcelOfReturnedNewBag);

        // ✅ Bag details fetch karo
         $bagDetails = FranchiseBag::where('id', $id)->select('barcode_no')->first();

        return view('franchise.bag.view', [
            'datas' => $datas,
            'bagId' => $id,
            'service_type' => $service_type,
            'bagDetails' => $bagDetails,
            'showScaner' => 1,
            'title' => $title
        ]);
    }


    public function viewParcels_of_createdBag_for_deliveryBoy(Request $request, $id)
    {
        // Get Bag Parcel Record
        $bagParcel = DeliveryBoyBagParcel::where('bag_id', $id)->first();


        // Initialize Data
        $datas = [];
        $bagDetails = null;
        $title = "Bag Details";

        if ($bagParcel) {
            // Decode JSON Parcel Data
            $parcels = json_decode($bagParcel->parcels, true) ?? [];

            // Filter only required service types
            $validServiceTypes = [1, 3, 4, 5, 6];

            // Group parcel IDs by service type
            $parcelGroups = [];

            foreach ($parcels as $parcel) {
                $serviceType = $parcel['service_type'];
                $parcelId = $parcel['parcel_id'];

                if (in_array($serviceType, $validServiceTypes)) {
                    $parcelGroups[$serviceType][] = $parcelId;
                }
            }

            // Fetch all parcels in batch
            foreach ($parcelGroups as $serviceType => $parcelIds) {
                $Model = FranchiseBag::getServiceModel($serviceType);

                if ($Model) {
                    // Fetch data as collection (not array)
                    $records = $Model::whereIn('id', $parcelIds)->get();

                    // Ensure each record is converted to an object
                    foreach ($records as $record) {
                        $datas[] = (object) $record;
                    }
                }
            }
        }

        $bagDetails = FranchiseBag::where('id', $id)->select('barcode_no', 'bag_id')->first();

       
        return view('franchise.bag.view', [
            'datas' => $datas,
            'bagId' => $id,
            'bagDetails' => $bagDetails,
            'showScaner' => 1,
            'title' => $title,
            "service_type" => $request->service_type,
        ]);
    }


    public function viewParcels_of_receivedBag_from_cms(Request $request, $id)
    {
        $service_type = $request->service_type;
        $Model = CMSBag::getServiceModel($service_type);
        $title = FranchiseBag::getServiceType($service_type);

        // ✅ Source ya Destination CMS Bag ID match kare
        $parcelOfNewBag = $Model::where(function ($query) use ($id) {
            $query->where('source_cms_bag_id', $id)
                ->orWhere('destination_cms_bag_id', $id);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Return Bags ka data fetch karo
        $returnBagData = ReturnedParcel::where('service_type', $service_type)
            ->where(function ($query) use ($id) {
                $query->where('source_cms_bag_id', $id)
                    ->orWhere('destination_cms_bag_id', $id);
            })
            ->orderBy('created_at', 'desc')
            ->pluck('parcel_id');

        // ✅ Return Bags se linked parcels fetch karo
        $parcelOfReturnedNewBag = $Model::whereIn('id', $returnBagData)
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Merge both collections correctly
        $datas = $parcelOfNewBag->merge($parcelOfReturnedNewBag);

        // ✅ Bag details fetch karo
        $bagDetails = CMSBag::where('id', $id)->select('barcode_no')->first();

        return view('franchise.bag.view', [
            'datas' => $datas,
            'bagId' => $id,
            'service_type' => $service_type,
            'showScaner' => 0,
            'bagDetails' => $bagDetails,
            'title' => $title
        ]);
    }




    public function showAllDeliveredParcel(Request $request)
    {
        $service_type = $request->service_type;
        $date = $request->input('date');
        $formattedDate = $date ? Carbon::parse($date)->format('y-m-d') : Carbon::today()->format('y-m-d');

        $allData = collect();
        $searchKey = $request->input('searchKey');

        $Model = FranchiseBag::getServiceModel($service_type);
        $title = FranchiseBag::getServiceType($service_type);

        // **Franchise ID Fetch**
        $franchiseId = Franchise::getFranchiseId();
        $deliveryBoyIds = DeliveryBoy::where("franchise_id", $franchiseId)->pluck('id')->toArray();

        foreach ($deliveryBoyIds as $delivery_boy_id) {
            $allData = $allData->merge($this->showAllDeliveredParcelHelper($service_type, $formattedDate, $delivery_boy_id));
        }


        return view('franchise.bag.showAllDeliveredParcel', ['datas' => $allData, 'title' => $title]);
    }



    public function showAllDeliveredParcelHelper($service_type, $formattedDate, $delivery_boy_id)
    {

        // Step 1: Get all bags assigned to the delivery boy
        $bagIds = FranchiseBag::where('delivery_boy_id', $delivery_boy_id)->pluck('id')->toArray();
        if (empty($bagIds)) {
            return [];
        }

        // Step 2: Fetch all bag parcel records at once
        $bagParcels = DeliveryBoyBagParcel::whereIn('bag_id', $bagIds)->get();
        $validServiceTypes = [1, 3, 4, 5, 6];

        $parcelGroups = [];

        // Step 3: Process all parcels in memory
        foreach ($bagParcels as $bagParcel) {
            $parcels = json_decode($bagParcel->parcels, true) ?? [];
            foreach ($parcels as $parcel) {
                if (in_array($parcel['service_type'], $validServiceTypes)) {
                    $parcelGroups[$parcel['service_type']][] = $parcel['parcel_id'];
                }
            }
        }

        if (empty($parcelGroups)) {
            return [];
        }

        // Step 4: Pre-fetch ReturnedParcels for lookup
        $returnedParcels = ReturnedParcel::whereIn('service_type', array_keys($parcelGroups))
            ->whereIn('parcel_id', collect($parcelGroups)->flatten()->toArray())
            ->get()
            ->keyBy(fn($parcel) => "{$parcel->service_type}_{$parcel->parcel_id}");

        $allData = [];


        foreach ($parcelGroups as $serviceType => $parcelIds) {
            $Model = FranchiseBag::getServiceModel($serviceType);
            if (!$Model) {
                continue;
            }

            // Fetch delivered parcels
            $deliveredRecords = $Model::whereIn('id', $parcelIds)
                ->whereDate('delivered_date', $formattedDate)
                ->where('delivered_by', $delivery_boy_id)
                ->get();

            // Fetch canceled parcels
            $canceledRecords = $Model::whereIn('id', $parcelIds)
                ->whereHas('cancelDelivery', function ($query) use ($formattedDate, $delivery_boy_id) {
                    $query->whereDate('created_at', $formattedDate)
                        ->where('cancelled_by', $delivery_boy_id); // Fix: 'delivered_by' changed to 'cancelled_by'
                })
                ->with('cancelDelivery')
                ->get();


            // Filter canceled records where canceled_by = delivery_boy_id
            $filteredRecords = $canceledRecords->filter(function ($record) use ($delivery_boy_id) {
                // Ensure cancelDelivery is loaded and is a collection
                if ($record->cancelDelivery instanceof \Illuminate\Support\Collection) {
                    return $record->cancelDelivery->contains(function ($cancel) use ($delivery_boy_id) {
                        return $cancel->cancelled_by == $delivery_boy_id;
                    });
                }
                return false;
            });


            $allParcels = $deliveredRecords->merge($filteredRecords);

            foreach ($allParcels as $record) {
                $returnType = isset($returnedParcels["{$serviceType}_{$record->id}"]) ? 1 : 0;

                $deliveredBy = $record->deliveredBy;
                $cancelledRecord = $record->cancelDelivery->firstWhere('cancelled_by', $delivery_boy_id);
                $cancelledBy = $cancelledRecord ? $cancelledRecord->cancelledBy : null;

                // Only include cancelDelivery done by this delivery boy
                $cancelDelivery = !empty($record->cancelDelivery)
                    ? collect($record->cancelDelivery)
                    ->filter(function ($cancel) use ($delivery_boy_id) {
                        return $cancel->cancelled_by == $delivery_boy_id;
                    })
                    ->map(function ($cancel) {
                        return [
                            'id' => $cancel->id,
                            'parcel_type' => $cancel->parcel_type,
                            'cancel_reason' => $cancel->cancel_reason,
                            'cancelled_by' => $cancel->cancelled_by,
                            'parcel_id' => $cancel->parcel_id,
                            'created_at' => $cancel->created_at,
                            'updated_at' => $cancel->updated_at,
                        ];
                    })
                    ->first()
                    : (object) [];

                $allData[] = [
                    'id' => $record->id,
                    'pickup_name' => $record->pickup_name,
                    'pickup_mobile' => $record->pickup_mobile,
                    'pickup_pincode' => $record->pickup_pincode,
                    'pickup_city' => $record->pickup_city,
                    'pickup_state' => $record->pickup_state,
                    'pickup_address' => $record->pickup_address,
                    'consignee_name' => $record->consignee_name,
                    'consignee_mobile' => $record->consignee_mobile,
                    'consignee_pincode' => $record->consignee_pincode,
                    'consignee_city' => $record->consignee_city,
                    'consignee_state' => $record->consignee_state,
                    'consignee_address' => $record->consignee_address,
                    'package_weight' => $record->package_weight,
                    'payment_method' => $record->payment_method,
                    'payment_amount' => $record->payment_amount,
                    'barcode_no' => $record->barcode_no,
                    'barcode_image_src' => $record->barcode_image_src,
                    'delivery_by_name' => ($deliveredBy && $deliveredBy->id == $delivery_boy_id) ? $deliveredBy->name : null,
                    'delivery_by_generated_id' => ($deliveredBy && $deliveredBy->id == $delivery_boy_id) ? $deliveredBy->generated_id : null,
                    'delivery_by_mobile' => ($deliveredBy && $deliveredBy->id == $delivery_boy_id) ? $deliveredBy->mobile : null,
                    'cancelled_by_name' => ($cancelledBy && $cancelledBy->id == $delivery_boy_id) ? $cancelledBy->name : null,
                    'cancelled_by_generated_id' => ($cancelledBy && $cancelledBy->id == $delivery_boy_id) ? $cancelledBy->generated_id : null,
                    'cancelled_by_mobile' => ($cancelledBy && $cancelledBy->id == $delivery_boy_id) ? $cancelledBy->mobile : null,
                    'delivered_date' => $record->delivered_date ? Carbon::parse($record->delivered_date)->format('d-m-Y') : null,
                    'pickup_date' => $record->created_at ? Carbon::parse($record->created_at)->format('d-m-Y') : null,
                    'signature' => $record->signature,
                    'service_type' => $serviceType,
                    'return_type' => $returnType,
                    'cancelDelivery' => $cancelDelivery,
                ];
            }
        }

        // Filter by service_type if provided
        return isset($service_type) ? array_filter($allData, fn($item) => $item['service_type'] == $service_type) : $allData;
    }




    public function printCMSCreatedBag(Request $request)

    {

        $franchiseId = Franchise::getFranchiseId();
        $franchise = Franchise::findOrFail($franchiseId);

        $linkDetail = GotogoLink::where('franchise_no', $franchise->franchise_no)->first();
        $createdDate = $request->input('date');
        $formattedDate = $createdDate ? Carbon::parse($createdDate)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        $searchKey = $request->input('searchKey');
        $serviceType = $request->service_type;


        if ($searchKey) {
            $Model = FranchiseBag::getServiceModel($serviceType);
            $parselToSearch = $Model::where('barcode_no', $searchKey)
                ->select('destination_franchise_bag_id', 'source_franchise_bag_id')
                ->first();

            if ($parselToSearch) {
                $source_franchise_bag_id = $parselToSearch->source_franchise_bag_id;
                $destination_franchise_bag_id = $parselToSearch->destination_franchise_bag_id;
                $desiredBags = [];

                if ($source_franchise_bag_id) {
                    $potentialBag = FranchiseBag::where('id', $source_franchise_bag_id)
                        ->whereDate('created_at', $formattedDate)->first();

                    if ($potentialBag && $potentialBag->franchise_id == $franchiseId) {
                        $desiredBags[] = $potentialBag;
                    }
                }

                if ($destination_franchise_bag_id) {
                    $potentialBag = FranchiseBag::where('id', $destination_franchise_bag_id)
                        ->whereDate('created_at', $formattedDate)->first();

                    if ($potentialBag && $potentialBag->franchise_id == $franchiseId) {
                        $desiredBags[] = $potentialBag;
                    }
                }
            }
        }

        // Base query for FranchiseBag
        $bagsQuery = FranchiseBag::where('franchise_id', $franchiseId)
            ->where('service_type', $serviceType)
            ->whereNotNull('cms_id')
            ->orderBy('created_at', 'desc');

        // Apply date filter if $createdDate is provided
        $bagsQuery->when($formattedDate, function ($query) use ($formattedDate) {
            return $query->whereDate('created_at', $formattedDate);
        });

        $bagsQuery->whereNotNull('cms_id')
            ->with('cms')
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where(function ($q) use ($searchKey) {
                    $q->whereHas('cms', function ($q) use ($searchKey) {
                        $q->where('pincode', 'like', '%' . $searchKey . '%')
                            ->orWhere('city', 'like', '%' . $searchKey . '%');
                    })->orWhere('barcode_no', 'like', '%' . $searchKey . '%');
                });
            });


        if (!empty($desiredBags)) {
            $filteredBags = collect($desiredBags);
        } else {
            $filteredBags = $bagsQuery->get();
        }

        $refinedData = [];
        foreach ($filteredBags as $data) {
            $refinedData[] = (object)[
                'barcode_image_src' => $data->barcode_img_src,
                'barcode_no'        => $data->barcode_no,
                'franchise_no'      => $linkDetail->franchise_no,
                'cms_no'            => $linkDetail->cms_no,
                'pph_no'            => $linkDetail->pph_no,
            ];
        }

        $title = FranchiseBag::getServiceType($serviceType);
        $otherPageContent = View::make('print.gotogopost.printBag', ['data' => $refinedData, 'title' => $title])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }
}
