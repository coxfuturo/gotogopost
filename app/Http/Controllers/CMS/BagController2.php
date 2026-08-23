<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Controllers\franchise\RateCalculator;
use App\Models\CMSBag;
use App\Models\FranchiseBag;
use App\Models\User;
use App\Models\CMS;
use App\Models\PPH;
use App\Models\PPHBag;
use App\Models\CMSCommissionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\CMSBarcodeSeries;
use App\Models\CMSBarcodes;
use App\Models\Franchise;
use App\Models\GotogoSpeedPostParcel;
use Illuminate\Support\Facades\View;
use App\Models\GotogoLink;
use App\Models\IndiaPostLink;
use App\Models\ReturnedParcel;
use DB;
use Carbon\Carbon;
use App\Notifications\FranchisePushNotification;
use App\Notifications\SMSNotification;



class BagController extends Controller

{


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->service_type) {
                $serviceStatuses = CMS::checkServiceStatus($request->service_type);
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

        $serviceTypeValue = CMSBarcodeSeries::getServiceTypeDB($serviceType);

        $range_start_column = "bag_barcode_range_start_{$serviceTypeValue}";

        $range_end_column = "bag_barcode_range_end_{$serviceTypeValue}";

        $last_code_issued_column = "last_bag_code_issued_{$serviceTypeValue}";



        $cmsSeriesDetails = CMSBarcodeSeries::where("cms_id", Auth::guard('cms')->user()->id)->first();



        if ($cmsSeriesDetails) {

            if ($cmsSeriesDetails->{$range_end_column} != null && $cmsSeriesDetails->{$range_end_column} > $cmsSeriesDetails->{$last_code_issued_column}) {

                if ($cmsSeriesDetails->{$last_code_issued_column}) {

                    $last_bag_code_issued = $cmsSeriesDetails->{$last_code_issued_column};

                    $seriesNum = str_pad($last_bag_code_issued + 1, 7, '0', STR_PAD_LEFT);
                } else {

                    $seriesNum = str_pad($cmsSeriesDetails->{$range_start_column}, 7, '0', STR_PAD_LEFT);
                }

                $serviceCode = CMSBarcodeSeries::getServiceCode($serviceType);

                $code =  CMSBarcodeSeries::$BAGCODE . $serviceCode . $seriesNum . $randomNumber . 'ND';

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

        $cms_id = Auth::guard('cms')->user()->id;
        $franchiseSeriesDetails = CMSBarcodeSeries::where("cms_id", $cms_id)->first();

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

        $generator = new BarcodeGeneratorPNG();

        $service_type = $request->service_type;

        $code =   $this->getUniqueCode($service_type);

        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        $barcode = base64_encode($barcode);

         $availableBarcodes = $this->getBarcodeAvailableCount($service_type);

        // Fetch bags for Franchise
        $bagsforFranchise = CMSBag::where('cms_id', Auth::guard('cms')->user()->id)

            ->where('service_type', $service_type)

            ->whereNotNull('franchise_id')

            ->whereDate('created_at', Carbon::today())

            ->orderBy('created_at', 'desc')

            ->with('franchise')

            ->get();



        // Fetch bags for CMS
        $bagsforCMS = CMSBag::where('cms_id', Auth::guard('cms')->user()->id)

            ->where('service_type', $service_type)

            ->whereNotNull('pph_id')

            ->whereDate('created_at', Carbon::today())

            ->orderBy('created_at', 'desc')

            ->with('pph')

            ->get();


        // return $bag;
         $linkDetail = NULL;
        $allpph = PPH::where('status', 1)->get();

        $allfranchise = Franchise::where('status', 1)->get();

        $franchise_details = CMS::where('id', Auth::guard('cms')->user()->id)->select('cms_no', 'wallet_balance', 'remaining_balance')->first();

        if ($service_type == 1 || $service_type == 3 || $service_type == 4) {
            $linkDetail = GotogoLink::where('cms_no', $franchise_details->cms_no)->first();
        }

        if (($service_type == 5 || $service_type == 6 || $service_type == 7) && !empty($franchise_details->cms_no)) {
            $gotogoLink = GotogoLink::where('cms_no', $franchise_details->cms_no)->first();

            if ($gotogoLink) {
                $linkDetail = IndiaPostLink::where('franchise_no', $gotogoLink->franchise_no)->first();
            }
        }

        $title = CMSBag::getServiceType($service_type);

        return view('cms.bag.createdBags', compact('bagsforFranchise', 'bagsforCMS', 'barcode', 'code', 'franchise_details', 'allpph', 'allfranchise', 'title', 'linkDetail', 'availableBarcodes'));
    }


    public function cretedBagsSearch(Request $request)
    {

        $allFranchise = Franchise::all();
        $allpph = PPH::all();

        $CMSId = Auth::guard('cms')->user()->id;
        $bagType = $request->input('bag_type');
        $serviceType = $request->input('serviceType');
        $searchKey = $request->input('searchKey');
        $createdDate = $request->input('createdDate');
        $formattedDate = $createdDate ? Carbon::parse($createdDate)->format('Y-m-d') : Carbon::today()->format('Y-m-d');

        if ($bagType === "bagsCreatedForFrancise") {
            if ($searchKey) {
                $Model = CMSBag::getServiceModel($serviceType);
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

                        if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                            $desiredBags[] = $potentialBag;
                        }
                    }

                    if ($destination_cms_bag_id) {
                        $potentialBag = CMSBag::where('id', $destination_cms_bag_id)
                            ->whereDate('created_at', $formattedDate)
                            ->first();

                        if ($potentialBag && $potentialBag->cms_id == $CMSId) {
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

                            if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                                $desiredBags[] = $potentialBag;
                            }
                        }

                        if ($return_destination_cms_bag_id) {
                            $potentialBag = CMSBag::where('id', $return_destination_cms_bag_id)
                                ->whereDate('created_at', $formattedDate)
                                ->first();

                            if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                                $desiredBags[] = $potentialBag;
                            }
                        }
                    }
                }
            }
            // Base query for FranchiseBag
            $bagsQuery = CMSBag::where('cms_id', $CMSId)
                ->where('service_type', $serviceType)
                ->whereNotNull('franchise_id')
                ->with('franchise')
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
                <div class="col-lg-2 d-flex position-relative" style="padding: 6px;">
                    <!-- Dropdown Menu (Outside Clickable Area) -->
                    <div class="dropdown dropdown-action position-absolute" style="right: 6px; z-index: 5; top: 4px;">
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
                    <a href="<?= route('cms.bag.viewParcelfranchiseCreated', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>" class="w-100 text-decoration-none">
                        <div class="profile-widget w-100 bagCard <?= $searchKey ? 'filteredColor' : '' ?>" style="padding: 10px;">
                            <div class="dash-card-icon">
                                <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                            </div>

                            <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; color: #333;">
                                <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                    <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                                </span>
                            </h4>

                            <img src="data:image/png;base64,<?= $bag->barcode_img_src ?>" alt="Barcode" style="height: 50px; display: none;" />

                            <h5 class="user-name mt-2 mb-0 text-ellipsis">ID: <?= $bag->barcode_no ?></h5>
                            <h6 class="user-name mb-0 text-ellipsis" style="padding: 2px;">Fo-Code: <?= $bag->franchise->franchise_no ?></h6>
                            <h6 class="user-name mb-0 text-ellipsis">City: <?= $bag->franchise->pincode ?></h6>
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
                                <form action="<?= route('cms.bag.update', ['id' => $bag->id]) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Franchise <span class="text-danger">*</span></label>
                                        <select name="franchise" class="floating">
                                            <option value=""> -- Select Franchise -- </option>
                                            <?php foreach ($allFranchise as $key => $value) : ?>
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

            $allotherBags = CMSBag::where('cms_id', $CMSId)
                ->where('service_type', $serviceType)
                ->whereNotNull('franchise_id')
                ->whereNotIn('id', $filteredBags->pluck('id')->toArray())
                ->orderBy('created_at', 'desc')
                ->with('franchise')
                ->whereDate('created_at', $formattedDate)
                ->get();

            foreach ($allotherBags as $bag) {
                ob_start();
            ?>
                <div class="col-lg-2 d-flex position-relative" style="padding: 6px;">
                    <!-- Dropdown Menu (Outside Clickable Area) -->
                    <div class="dropdown dropdown-action position-absolute" style="right: 0px; z-index: 5; top: 9px;">
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
                    <a href="<?= route('cms.bag.viewParcelfranchiseCreated', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>" class="w-100 text-decoration-none">
                        <div class="profile-widget w-100 bagCard"
                            style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; background: #fff; transition: 0.3s;">

                            <div class="dash-card-icon">
                                <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                            </div>

                            <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; color: #333;">
                                <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                    <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                                </span>
                            </h4>

                            <img src="data:image/png;base64,<?= $bag->barcode_img_src ?>" alt="Barcode" style="height: 50px; display: none;" />

                            <h6 class="user-name mt-2 mb-0 text-ellipsis">ID: <?= $bag->barcode_no ?></h6>
                            <h6 class="user-name mb-0 text-ellipsis" style="padding: 2px;">Fo-Code: <?= $bag->franchise->franchise_no ?></h6>
                            <h6 class="user-name mb-0 text-ellipsis">City: <?= $bag->franchise->pincode ?></h6>
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
                                <form action="<?= route('cms.bag.update', ['id' => $bag->id]) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Franchsie <span class="text-danger">*</span></label>
                                        <select name="franchise" class="floating">
                                            <option value=""> -- Select Franchise -- </option>
                                            <?php foreach ($allFranchise as $key => $value) : ?>
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
        } elseif ($bagType === "bagsCreatedForCMS") {


            if ($searchKey) {
                $Model = CMSBag::getServiceModel($serviceType);

                // ✅ Search parcel by barcode number
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

                        if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                            $desiredBags[] = $potentialBag;
                        }
                    }

                    if ($destination_cms_bag_id) {
                        $potentialBag = CMSBag::where('id', $destination_cms_bag_id)
                            ->whereDate('created_at', $formattedDate)
                            ->first();

                        if ($potentialBag && $potentialBag->cms_id == $CMSId) {
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

                            if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                                $desiredBags[] = $potentialBag;
                            }
                        }

                        if ($return_destination_cms_bag_id) {
                            $potentialBag = CMSBag::where('id', $return_destination_cms_bag_id)
                                ->whereDate('created_at', $formattedDate)
                                ->first();

                            if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                                $desiredBags[] = $potentialBag;
                            }
                        }
                    }
                }
            }


            // Base query for FranchiseBag
            $bagsQuery = CMSBag::where('cms_id', $CMSId)
                ->where('service_type', $serviceType)
                ->whereNotNull('pph_id')
                ->with('pph')
                ->orderBy('created_at', 'desc');


            // Apply date filter if $createdDate is provided
            $bagsQuery->when($formattedDate, function ($query) use ($formattedDate) {
                return $query->whereDate('created_at', $formattedDate);
            });

            $bagsQuery->whereNotNull('pph_id')
                ->with('pph')
                ->when($searchKey, function ($query) use ($searchKey) {
                    $query->where(function ($q) use ($searchKey) {
                        $q->whereHas('otherCMS', function ($q) use ($searchKey) {
                            $q->where('pincode', 'like', '%' . $searchKey . '%')
                                ->orWhere('city', 'like', '%' . $searchKey . '%');
                        })->orWhere('barcode_no', 'like', '%' . $searchKey . '%');
                    });
                });


            $filteredBags = $bagsQuery->get();

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

                    <a href="<?= route('cms.bag.viewParcelCMSCreated', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>" class="w-100 text-decoration-none">
                        <div class="profile-widget w-100 bagCard <?= $searchKey ? 'filteredColor' : '' ?>"
                            style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; background: #fff; transition: 0.3s;">

                            <div class="dash-card-icon">
                                <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                            </div>

                            <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; color: #333;">
                                <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                    <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                                </span>
                            </h4>

                            <img src="data:image/png;base64,<?= $bag->barcode_img_src ?>" alt="Barcode" style="height: 50px; display:none;" />

                            <h6 class="user-name mt-2 mb-0 text-ellipsis">ID: <?= $bag->barcode_no ?></h6>
                            <h6 class="user-name mb-0 text-ellipsis" style="padding:2px">PPHCode: <?= $bag->pph->pph_no ?></h6>
                            <h6 class="user-name mb-0 text-ellipsis">City: <?= $bag->pph->pincode ?></h6>
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
                                <form action="<?= route('cms.bag.update', ['id' => $bag->id]) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">CMS <span class="text-danger">*</span></label>
                                        <select name="cms" class="floating">
                                            <option value=""> -- Select CMS -- </option>
                                            <?php foreach ($allpph as $key => $value) : ?>
                                                <option value="<?= $value->id ?>" <?= $bag->pph_id == $value->id ? 'selected' : '' ?>>Name:<?= $value->name ?> || City:<?= $value->pincode ?></option>
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

            $allotherBags = CMSBag::where('cms_id', $CMSId)
                ->where('service_type', $serviceType)
                ->whereNotNull('pph_id')
                ->whereNotIn('id', $filteredBags->pluck('id')->toArray())
                ->orderBy('created_at', 'desc')
                ->with('pph')
                ->whereDate('created_at', $formattedDate)
                ->get();

            foreach ($allotherBags as $bag) {
                ob_start();
            ?>
                <div class="col-lg-2 d-flex position-relative" style="padding: 6px;">
                    <div class="dropdown dropdown-action" style="position: absolute; right: 6px; z-index: 5; top: 4px;">
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

                    <a href="<?= route('cms.bag.viewParcelCMSCreated', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>">
                        <div style="padding: 10px;" class="profile-widget w-100 bagCard">
                            <div class="dash-card-icon">
                                <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                            </div>

                            <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; color: #333;">
                                <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                    <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                                </span>
                            </h4> <!-- ✅ Fixed missing closing tag here -->

                            <img src="data:image/png;base64,<?= $bag->barcode_img_src ?>" alt="Barcode" style="height: 50px; display:none;" />

                            <h6 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">ID: <?= $bag->barcode_no ?></a></h6>
                            <h6 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">PPHCode: <?= $bag->pph->pph_no ?></a></h6>
                            <h6 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">City: <?= $bag->pph->pincode ?></a></h6>
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
                                <form action="<?= route('cms.bag.update', ['id' => $bag->id]) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">CMS <span class="text-danger">*</span></label>
                                        <select name="cms" class="floating">
                                            <option value=""> -- Select CMS -- </option>
                                            <?php foreach ($allpph as $key => $value) : ?>
                                                <option value="<?= $value->id ?>" <?= $bag->pph_id == $value->id ? 'selected' : '' ?>>Name:<?= $value->name ?> || City:<?= $value->pincode ?></option>
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

        $bagfromfranchise = FranchiseBag::where('service_type', $request->service_type)

            ->where('cms_id',  Auth::guard('cms')->user()->id)

            ->whereDate('received_date', Carbon::today())

            ->orderBy('created_at', 'desc')

            ->with('franchise')

            ->get();


        $bagfromCMS = PPHBag::where('service_type', $request->service_type)

            ->where('service_type', $request->service_type)

            ->where('cms_id',  Auth::guard('cms')->user()->id)

            ->whereDate('received_date', Carbon::today())

            ->orderBy('created_at', 'desc')

            ->with('pph')

            ->get();


        // return  $franchise_details;

        return view('cms.bag.recievedBags', ['bagfromfranchise' => $bagfromfranchise, 'bagfromCMS' => $bagfromCMS, 'service_type' => $request->service_type]);
    }



    public function receivedBagsSearch(Request $request)
    {

        $CMSId = Auth::guard('cms')->user()->id;
        $bagType = $request->input('bag_type');
        $serviceType = $request->input('serviceType');
        $searchKey = $request->input('searchKey');
        $createdDate = $request->input('createdDate');
        $formattedDate = $createdDate ? Carbon::parse($createdDate)->format('Y-m-d') : Carbon::today()->format('Y-m-d');

        if ($bagType === "bagsReceivedFromFranchise") {
            if ($searchKey) {
                $Model = FranchiseBag::getServiceModel($serviceType);
                $parselToSearch = $Model::where('barcode_no', $searchKey)
                    ->select('destination_franchise_bag_id', 'source_franchise_bag_id', 'id')
                    ->first();


                if ($parselToSearch) {
                    $source_franchise_bag_id = $parselToSearch->source_franchise_bag_id;
                    $destination_franchise_bag_id = $parselToSearch->destination_franchise_bag_id;
                    $desiredBags = [];

                    // ✅ New Bags Search
                    if ($source_franchise_bag_id) {
                        $potentialBag = FranchiseBag::where('id', $source_franchise_bag_id)
                            ->whereDate('received_date', $formattedDate)
                            ->first();

                        if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                            $desiredBags[] = $potentialBag;
                        }
                    }

                    if ($destination_franchise_bag_id) {
                        $potentialBag = FranchiseBag::where('id', $destination_franchise_bag_id)
                            ->whereDate('received_date', $formattedDate)
                            ->first();

                        if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                            $desiredBags[] = $potentialBag;
                        }
                    }

                    // ✅ Return Bags Search
                    $returnBagParcel = ReturnedParcel::where('parcel_id', $parselToSearch->id)->first();

                    if ($returnBagParcel) {
                        $return_source_franchise_bag_id = $returnBagParcel->source_franchise_bag_id;
                        $return_destination_franchise_bag_id = $returnBagParcel->destination_franchise_bag_id;

                        if ($return_source_franchise_bag_id) {
                            $potentialBag = FranchiseBag::where('id', $return_source_franchise_bag_id)
                                ->whereDate('received_date', $formattedDate)
                                ->first();

                            if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                                $desiredBags[] = $potentialBag;
                            }
                        }

                        if ($return_destination_franchise_bag_id) {
                            $potentialBag = FranchiseBag::where('id', $return_destination_franchise_bag_id)
                                ->whereDate('received_date', $formattedDate)
                                ->first();

                            if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                                $desiredBags[] = $potentialBag;
                            }
                        }
                    }
                }
            }


            // Base query for FranchiseBag
            $bagsQuery = FranchiseBag::where('cms_id', $CMSId)
                ->where('service_type', $serviceType)
                ->with('franchise')
                ->orderBy('received_date', 'desc');

            // Apply date filter if $createdDate is provided
            $bagsQuery->when($formattedDate, function ($query) use ($formattedDate) {
                return $query->whereDate('received_date', $formattedDate);
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
                    <!-- Clickable Profile Card -->
                    <div class="profile-widget w-100 bagCard <?= $searchKey ? 'filteredColor' : '' ?>" style="position: relative; padding: 10px;">
                        <!-- Full clickable area -->
                        <a href="<?= route('cms.bag.viewParcelfranchiseReceived', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>"
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

            $allotherBags = FranchiseBag::where('cms_id', $CMSId)
                ->where('service_type', $serviceType)
                ->whereNotIn('id', $filteredBags->pluck('id')->toArray())
                ->orderBy('received_date', 'desc')
                ->with('franchise')
                ->whereDate('received_date', $formattedDate)
                ->get();

            foreach ($allotherBags as $bag) {
                ob_start();
            ?>
                <div class="col-lg-2 d-flex position-relative" style="padding: 6px;">
                    <div class="profile-widget w-100 bagCard"
                        style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; background: #fff; transition: 0.3s; cursor: pointer;"
                        onclick="window.location.href='<?= route('cms.bag.viewParcelfranchiseReceived', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>'">

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
        } elseif ($bagType === "bagsReceivedFromCMS") {


            if ($searchKey) {
                $Model = CMSBag::getServiceModel($serviceType);

                $parselToSearch = $Model::where('barcode_no', $searchKey)
                    ->select('source_pph_bag_id', 'id')
                    ->first();

                if ($parselToSearch) {
                    $source_pph_bag_id = $parselToSearch->source_pph_bag_id;
                    $desiredBags = [];

                    // ✅ New Bags Search
                    if ($source_pph_bag_id) {
                        $potentialBag = PPHBag::where('id', $source_pph_bag_id)
                            ->whereDate('received_date', $formattedDate)
                            ->first();

                        if ($potentialBag) {
                            $desiredBags[] = $potentialBag;
                        }
                    }

                    // ✅ Return Bags Search
                    $returnBagParcel = ReturnedParcel::where('parcel_id', $parselToSearch->id)->first();

                    if ($returnBagParcel) {
                        $return_source_pph_bag_id = $returnBagParcel->source_pph_bag_id;

                        if ($return_source_pph_bag_id) {
                            $potentialBag = PPHBag::where('id', $return_source_pph_bag_id)
                                ->whereDate('received_date', $formattedDate)
                                ->first();

                            if ($potentialBag) {
                                $desiredBags[] = $potentialBag;
                            }
                        }
                    }
                }
            }


            // Base query for FranchiseBag
            $bagsQuery = PPHBag::where('cms_id', $CMSId)
                ->where('service_type', $serviceType)
                ->with('pph')
                ->orderBy('received_date', 'desc');


            $bagsQuery->when($formattedDate, function ($query) use ($formattedDate) {
                return $query->whereDate('received_date', $formattedDate);
            });


            $bagsQuery->whereNotNull('pph_id')
                ->with('pph')
                ->when($searchKey, function ($query) use ($searchKey) {
                    $query->where(function ($q) use ($searchKey) {
                        $q->whereHas('pph', function ($q) use ($searchKey) {
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
                    <div onclick="window.location.href='<?= route('cms.bag.viewParcelCMSReceived', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>'"
                        class="profile-widget w-100 bagCard <?= $searchKey ? 'filteredColor' : '' ?>"
                        style="padding: 10px; cursor: pointer; border: 1px solid #ddd; border-radius: 8px; transition: 0.3s;">

                        <div class="dash-card-icon">
                            <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                        </div>

                        <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; font-weight: bold; color: #333;">
                            <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                            </span>
                        </h4>

                        <h5 class="user-name mt-1 mb-0 text-ellipsis">ID: <?= $bag->barcode_no ?></h5>
                        <h6 class="user-name mt-1 mb-0 text-ellipsis">CMSCode: <?= $bag->pph->pph_no ?></h6>
                        <h6 class="user-name mt-1 mb-0 text-ellipsis">City: <?= $bag->pph->pincode ?></h6>

                    </div>
                </div>

            <?php
                $html .= ob_get_clean();
            }

            $allotherBags = PPHBag::where('cms_id', $CMSId)
                ->where('service_type', $serviceType)
                ->whereNotIn('id', $filteredBags->pluck('id')->toArray())
                ->orderBy('received_date', 'desc')
                ->with('pph')
                ->whereDate('received_date', $formattedDate)
                ->get();

            foreach ($allotherBags as $bag) {
                ob_start();
            ?>
                <div class="col-lg-2 d-flex position-relative" style="padding: 6px;">
                    <div onclick="window.location.href='<?= route('cms.bag.viewParcelCMSReceived', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>'"
                        class="profile-widget w-100 bagCard"
                        style="padding: 10px; cursor: pointer; border: 1px solid #ddd; border-radius: 8px; background: #fff; transition: 0.3s;">

                        <div class="dash-card-icon">
                            <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                        </div>

                        <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; font-weight: bold; color: #333;">
                            <span id="<?= $bag->return_type == 1 ? 'returned-bag' : 'new-bag' ?>">
                                <?= $bag->return_type == 1 ? 'Return Bag' : 'New Bag' ?>
                            </span>
                        </h4>

                        <h6 class="user-name mt-1 mb-0 text-ellipsis">ID: <?= $bag->barcode_no ?></h6>
                        <h6 class="user-name mt-1 mb-0 text-ellipsis">CMSCode: <?= $bag->pph->pph_no ?></h6>
                        <h6 class="user-name mt-1 mb-0 text-ellipsis">City: <?= $bag->pph->pincode ?></h6>

                    </div>
                </div>

                <?php
                $html .= ob_get_clean();
            }

            // Return HTML as response
            return response()->json(['html' => $html]);
        }
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
                    "🚀 Your order has reached {$recieverDetails->address}";

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
                "🚀 Order has reached to CPH in {$recieverDetails->address}";

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



    public function scanFranchiseBagReceived(Request $request, $id, RateCalculator $ratecalculator)

    {
// return 34567;
        if ($request->isMethod('post')) {

            try {

                $cms = Auth::guard('cms')->user();

                $bagtoUpdate = FranchiseBag::where([
                    'barcode_no' => $request->barcode,
                    'cms_id' => $cms->id
                ])->first();

                if (!$bagtoUpdate) {
                    return response()->json(['status' => 'error', 'message' => 'Bag not found'], 404);
                }

                if (isset($bagtoUpdate->received_date)) {
                    return response()->json(['status' => 'error', 'message' => 'Bag Already Received'], 404);
                }

                $bagtoUpdate->received_date =  Carbon::today()->toDateString();
                $bagtoUpdate->save();


                $Model = CMSBag::getServiceModel($request->service_type);
                $datas =  $Model::where('source_franchise_bag_id', $bagtoUpdate->id)
                    ->orWhere('destination_franchise_bag_id', $bagtoUpdate->id)
                    ->get();

                // dd($datas);
                $service_type = $request->service_type;
                foreach ($datas as $data) {

                    if ($service_type == 1 || $service_type == 3 || $service_type == 4) {
                        $amount = $ratecalculator->calculateCommissionForGotogoPost($data->package_weight, 'cph', $request->service_type);
                    }

                    if ($service_type == 5 || $service_type == 6 || $service_type == 7) {
                        $amount = $ratecalculator->calculateCommissionForIndiaPost($data->package_weight, 'cph', $request->service_type);
                    }

                    if ($bagtoUpdate->return_type == 0) {
                        CMSCommissionDetail::create([
                            "cms_id" => Auth::guard('cms')->user()->id,
                            "service_type" => $request->service_type,
                            "amount" => $data->payment_amount,
                            "commission" => $amount,
                        ]);

                        $TrackingModel = FranchiseBag::getTrackingModel($request->service_type);
                        $trackingModalToUpdate =  $TrackingModel::where('barcode_no', $data->barcode_no)->first();
                        $trackingModalToUpdate->source_cms_id = Auth::guard('cms')->user()->id;
                        $trackingModalToUpdate->source_cms_receiving_datetime = now();
                        $trackingModalToUpdate->source_cms_location = Auth::guard('cms')->user()->address;
                        $trackingModalToUpdate->save();
                    }

                    $this->sendNotificationToUser($cms, $data, $service_type);
                    $notification = new SMSNotification($data->pickup_mobile, 'BYCPH', [$data->barcode_no, now(), $cms->address, $cms->name]);
                    $notification->sendMessage();
                }

                $bagfromfranchise = FranchiseBag::where('service_type', $request->service_type)

                    ->where('cms_id',  Auth::guard('cms')->user()->id)

                    ->whereDate('received_date', Carbon::today())

                    ->orderBy('updated_at', 'desc')

                    ->with('franchise')

                    ->get();


                $html = '';
                foreach ($bagfromfranchise as $bag) {
                    ob_start();
                ?>
                    <div class="col-lg-2 d-flex" style="position:relative; padding:6px;">
                        <div class="profile-widget w-100 bagCard" style="position: relative; padding: 10px;">
                            <!-- Full clickable area -->
                            <a href="<?= route('cms.bag.viewParcelfranchiseReceived', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>"
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
                            <h6 class="user-name mt-1 mb-0 text-ellipsis">Fo-Code: <?= $bag->franchise->franchise_no ?></h6>
                            <h6 class="user-name mt-1 mb-0 text-ellipsis">City: <?= $bag->franchise->pincode ?></h6>
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



    public function scanCMSBagReceived(Request $request, RateCalculator $ratecalculator)

    {

        if ($request->isMethod('post')) {

            try {

                $bagtoUpdate =  PPHBag::where('barcode_no', $request->barcode)->first();
                $cms = Auth::guard('cms')->user();
                // Check if the parcel exists

                if (!$bagtoUpdate) {

                    return response()->json(['status' => 'error', 'message' => 'Bag not found'], 404);
                }

                if (isset($bagtoUpdate->received_date)) {
                    return response()->json(['status' => 'error', 'message' => 'Bag Already Received'], 404);
                }

                // Update the record

                $bagtoUpdate->received_date =  Carbon::today()->toDateString();

                $bagtoUpdate->save();


                $Model = PPHBag::getServiceModel($request->service_type);

                $datas =  $Model::where('source_pph_bag_id', $bagtoUpdate->id)

                    ->orderBy('created_at', 'desc')

                    ->get();

                //dd($datas);
                $service_type = $request->service_type;

                foreach ($datas as $data) {

                    if ($service_type == 1 || $service_type == 3 || $service_type == 4) {
                        $amount = $ratecalculator->calculateCommissionForGotogoPost($data->package_weight, 'cph', $request->service_type);
                    }

                    if ($service_type == 5 || $service_type == 6 || $service_type == 7) {
                        $amount = $ratecalculator->calculateCommissionForIndiaPost($data->package_weight, 'cph', $request->service_type);
                    }

                    CMSCommissionDetail::create([
                        "cms_id" => Auth::guard('cms')->user()->id,
                        "service_type" => $request->service_type,
                        "amount" => $data->payment_amount,
                        "commission" => $amount,
                    ]);

                    $TrackingModel = FranchiseBag::getTrackingModel($request->service_type);
                    $trackingModalToUpdate =  $TrackingModel::where('barcode_no', $data->barcode_no)->first();
                    $trackingModalToUpdate->destination_cms_id = Auth::guard('cms')->user()->id;
                    $trackingModalToUpdate->destination_cms_receiving_datetime = now();
                    $trackingModalToUpdate->destination_cms_location = Auth::guard('cms')->user()->address;
                    $trackingModalToUpdate->save();

                    $notification = new SMSNotification($data->pickup_mobile, 'ORDER', [$data->pickup_mobile, now()]);
                    $notification->sendMessage();

                    $this->sendNotificationToFranchise($cms, $data, $service_type);
                    $this->sendNotificationToUser($cms, $data, $service_type);

                    $notification = new SMSNotification($data->pickup_mobile, 'BYCPH', [$data->barcode_no, now(), $cms->address, $cms->name]);
                    $notification->sendMessage();
                }



                $bagfromCMS = PPHBag::where('service_type', $request->service_type)

                    ->where('service_type', $request->service_type)

                    ->where('cms_id',  Auth::guard('cms')->user()->id)

                    ->whereDate('received_date', Carbon::today())

                    ->orderBy('created_at', 'desc')

                    ->with('pph')

                    ->get();


                $html = '';
                foreach ($bagfromCMS as $bag) {
                    ob_start();
                ?>
                    <div class="col-lg-2 d-flex" style="position:relative; padding:6px;">
                        <div class="profile-widget w-100 bagCard" style="position: relative; padding: 10px;">
                            <!-- Full clickable area -->
                            <a href="<?= route('cms.bag.viewParcelCMSReceived', ['id' => $bag->id, 'service_type' => $bag->service_type]) ?>"
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
                            <h6 class="user-name mt-1 mb-0 text-ellipsis">PPHCode: <?= $bag->pph->pph_no ?></h6>
                            <h6 class="user-name mt-1 mb-0 text-ellipsis">City: <?= $bag->pph->pincode ?></h6>
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




    public function store(Request $request)
    {

        if ($request->barcode_no == 'Barcode series end' || $request->barcode_no == 'Barcodes not assigned') {

            return back()->with('error', 'barcode series end for this services');
        }



        try {

            do {

                $unique_bag_id = mt_rand(10000, 9999999);
            } while (CMSBag::where('bag_id', $unique_bag_id)->exists());









            if ($request->role == 'pph') {

                CMSBag::create([

                    'cms_id' => Auth::guard('cms')->user()->id,

                    'bag_id' => $unique_bag_id,

                    'pph_id' => $request->bagAssignedTo,

                    'return_type' => $request->return_type,

                    'service_type' => $request->service_type,

                    'barcode_no' => $request->barcode_no,

                    'barcode_img_src' => $request->barcode_img_src,

                ]);
            }


            if ($request->role == 'franchise') {

                CMSBag::create([

                    'cms_id' => Auth::guard('cms')->user()->id,

                    'bag_id' => $unique_bag_id,

                    'franchise_id' => $request->bagAssignedTo,

                    'return_type' => $request->return_type,

                    'service_type' => $request->service_type,

                    'barcode_no' => $request->barcode_no,

                    'barcode_img_src' => $request->barcode_img_src,

                ]);
            }





            $franchiseSeriesDetails = CMSBarcodeSeries::where("cms_id", Auth::id())->first();

            $serviceTypeValue = CMSBarcodeSeries::getServiceTypeDB($request->service_type);

            $range_start_column = "bag_barcode_range_start_{$serviceTypeValue}";

            $last_code_issued_column = "last_bag_code_issued_{$serviceTypeValue}";



            $cmsId = Auth::guard('cms')->user()->id;

            $franchiseSeriesDetails = CMSBarcodeSeries::where("cms_id", $cmsId)->first();

            if ($franchiseSeriesDetails->{$last_code_issued_column}) {

                $last_parcel_code_issued = $franchiseSeriesDetails->{$last_code_issued_column};

                $seriesNum = $last_parcel_code_issued + 1;
            } else {

                $seriesNum = $franchiseSeriesDetails->{$range_start_column};
            }



            $franchiseSeriesDetails->{$last_code_issued_column} = $seriesNum;

            $franchiseSeriesDetails->save();



            $franchiseBarcode = new CMSBarcodes;

            $franchiseBarcode->barcodes = $request->barcode_no;

            $franchiseBarcode->cms_barcodeseries_id = $franchiseSeriesDetails->id;

            $franchiseBarcode->save();

            return back()->with('success', 'Bag Added successfully');
        } catch (\Exception $th) {

            return back()->with('error', $th->getMessage());
        }
    }





    public function update(Request $request, $id)

    {

        try {

            $bag = CMSBag::findOrfail($id);

            // return $bag;

            if ($bag->franchise_id) {
                $bag->franchise_id = $request->franchise;
            }


            if ($bag->destination_cms_id) {
                $bag->destination_cms_id = $request->cms;
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

            CMSBag::findorfail($id)->delete();

            return back()->with('success', 'Bag Deleted Successfully');
        } catch (\Throwable $th) {

            return back()->with('error', $th->getMessage());
        }
    }





    public function getData(Request $request)

    {

        try {

            if ($request->role == 'pph') {

                $data = PPH::where('status', 1)->get();
            } elseif ($request->role == 'franchise') {

                $data = Franchise::where('status', 1)->get();
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

            $service_type = $request->service_type;

            $Model = CMSBag::getServiceModel($request->service_type);
            $TrackingModel = FranchiseBag::getTrackingModel($request->service_type);

            $parcelToUpdate =  $Model::where('barcode_no', $request->barcode)->first();
            $trackingModalToUpdate =  $TrackingModel::where('barcode_no', $request->barcode)->first();

            $bag = CMSBag::where('id', $id)->first();

            if (!$parcelToUpdate) {
                return response()->json(['status' => 404, 'message' => 'Parcel not found'], 404);
            }

            if (!$trackingModalToUpdate) {
                return response()->json(['status' => 404, 'message' => 'Tracking Model not found'], 404);
            }

            if (!$bag) {
                return response()->json(['status' => 404, 'message' => 'Bag not found'], 404);
            }

            // ✅ Return Type Check
            if ($bag->return_type == 1) {
                return $this->handleReturnParcels($parcelToUpdate, $service_type, $bag);
            } else {
                return $this->handleNormalParcels($parcelToUpdate, $trackingModalToUpdate, $bag, $id);
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
        if ($parcelToUpdate->cms_id != $bag->franchise_id) {

            $returnedParcel->source_cms_bag_id = $bag_id;
        } else {
            $returnedParcel->destination_cms_bag_id = $bag_id;
        }

        $returnedParcel->save();

        return response()->json(['status' => 'success', 'data' => $parcelToUpdate]);
    }

    /**
     * ✅ Return Type == 0 ka logic
     */
    private function handleNormalParcels($parcelToUpdate, $trackingModalToUpdate, $bag, $id)
    {
        // Update the record
        if ($parcelToUpdate->cms_id == $bag->cms_id) {
            $parcelToUpdate->source_cms_bag_id = $id;
            $trackingModalToUpdate->source_cms_dispatch_datetime = now();
        } else {
            $parcelToUpdate->destination_cms_bag_id = $id;
            $trackingModalToUpdate->destination_cms_dispatch_datetime = now();
        }

        $parcelToUpdate->save();
        $trackingModalToUpdate->save();

        return response()->json(['status' => 'success', 'data' => $parcelToUpdate]);
    }



    public function removeParcel(Request $request, $id)

    {

        $Model = CMSBag::getServiceModel($request->service_type);
        $bag = CMSBag::where('id', 14)->first();
        try {

            $parcelToUpdate =  $Model::where('id', $id)->first();

            // Check if the parcel exists

            if (!$parcelToUpdate) {

                return back()->with('error', 'Parcel not found');
            }

            // Update the record

            if ($parcelToUpdate->cms_id == $bag->cms_id) {

                $parcelToUpdate->source_cms_bag_id = null;

                $parcelToUpdate->save();

                return back()->with('success', 'Parcel Removed Successfully from bag');
            } else {

                $parcelToUpdate->destination_cms_bag_id = null;

                $parcelToUpdate->save();

                return back()->with('success', 'Parcel Removed Successfully from bag');
            }
        } catch (\Throwable $th) {

            return back()->with('error', $th->getMessage());
        }
    }



    public function viewParcels_of_createdBag_for_franchise(Request $request, $id)
    {
        $service_type = $request->service_type;
        $Model = CMSBag::getServiceModel($service_type);
        $title = CMSBag::getServiceType($service_type);

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

        return view('cms.bag.view', [
            'datas' => $datas,
            'bagId' => $id,
            'service_type' => $service_type,
            'showScaner' => 1,
            'bagDetails' => $bagDetails,
            'title' => $title,
            'bagType' => 'created'
        ]);
    }


    public function viewParcels_of_createdBag_for_cms(Request $request, $id)
    {
        $service_type = $request->service_type;
        $Model = CMSBag::getServiceModel($service_type);
        $title = CMSBag::getServiceType($service_type);

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

        return view('cms.bag.view', [
            'datas' => $datas,
            'bagId' => $id,
            'service_type' => $service_type,
            'showScaner' => 1,
            'bagDetails' => $bagDetails,
            'title' => $title,
            'bagType' => 'created'
        ]);
    }


    public function viewParcels_of_receivedBag_from_franchise(Request $request, $id)
    {
        $service_type = $request->service_type;
        $Model = CMSBag::getServiceModel($service_type);
        $title = CMSBag::getServiceType($service_type);

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

        return view('cms.bag.view', [
            'datas' => $datas,
            'bagId' => $id,
            'service_type' => $service_type,
            'showScaner' => 0,
            'bagDetails' => $bagDetails,
            'title' => $title,
            'bagType' => 'received'
        ]);
    }

    // ✅ CMS se received bags ke liye
    public function viewParcels_of_receivedBag_from_cms(Request $request, $id)
    {
        $service_type = $request->service_type;
        $Model = PPHBag::getServiceModel($service_type);
        $title = CMSBag::getServiceType($service_type);

        // ✅ Source PPH Bag ID match kare
        $parcelOfNewBag = $Model::where('source_pph_bag_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Return Bags ka data fetch karo
        $returnBagData = ReturnedParcel::where('service_type', $service_type)
            ->where('source_pph_bag_id', $id)
            ->orderBy('created_at', 'desc')
            ->pluck('parcel_id');

        // ✅ Return Bags se linked parcels fetch karo
        $parcelOfReturnedNewBag = $Model::whereIn('id', $returnBagData)
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Merge both collections correctly
        $datas = $parcelOfNewBag->merge($parcelOfReturnedNewBag);

        // ✅ Bag details fetch karo
        $bagDetails = PPHBag::where('id', $id)->select('barcode_no')->first();

        return view('cms.bag.view', [
            'datas' => $datas,
            'bagId' => $id,
            'service_type' => $service_type,
            'showScaner' => 0,
            'bagDetails' => $bagDetails,
            'title' => $title,
            'bagType' => 'received'
        ]);
    }

    public function printFranchiseCreatedBag(Request $request)
    {


        $CMSId = Auth::guard('cms')->user()->id;
        $createdDate = $request->input('date');

        $formattedDate = $createdDate ? Carbon::parse($createdDate)->format('Y-m-d') : Carbon::today()->format('Y-m-d');

        $searchKey = $request->input('searchKey');

        $serviceType = $request->service_type;

        if ($searchKey) {
            $Model = CMSBag::getServiceModel($serviceType);
            $parselToSearch = $Model::where('barcode_no', $searchKey)
                ->select('destination_cms_bag_id', 'source_cms_bag_id')
                ->first();


            if ($parselToSearch) {
                $source_cms_bag_id = $parselToSearch->source_cms_bag_id;
                $destination_cms_bag_id = $parselToSearch->destination_cms_bag_id;
                $desiredBags = [];

                if ($source_cms_bag_id) {
                    $potentialBag = CMSBag::where('id', $source_cms_bag_id)
                        ->whereDate('created_at', $formattedDate)->first();

                    if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                        $desiredBags[] = $potentialBag;
                    }
                }

                if ($destination_cms_bag_id) {

                    $potentialBag = CMSBag::where('id', $destination_cms_bag_id)
                        ->whereDate('created_at', $formattedDate)->first();

                    if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                        $desiredBags[] = $potentialBag;
                    }
                }
            }
        }
        // Base query for FranchiseBag
        $bagsQuery = CMSBag::where('cms_id', $CMSId)
            ->where('service_type', $serviceType)
            ->whereNotNull('franchise_id')
            ->with('franchise')
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

        $refinedData = [];
        foreach ($filteredBags as $data) {
            $franchiseId = $data->franchise_id;
            $franchise = Franchise::findOrFail($franchiseId);
            $linkDetail = GotogoLink::where('franchise_no', $franchise->franchise_no)->first();
            $refinedData[] = (object)[
                'barcode_image_src' => $data->barcode_img_src,
                'barcode_no'        => $data->barcode_no,
                'franchise_no'      => $linkDetail->franchise_no,
                'cms_no'            => $linkDetail->cms_no,
                'pph_no'            => $linkDetail->pph_no,
            ];
        }

        $title = FranchiseBag::getServiceType($serviceType);
        // Pass data to the view
        $otherPageContent = View::make('print.gotogopost.printBag', ['data' => $refinedData, 'title' => $title])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }


    public function printPPHCreatedBag(Request $request)

    {

        $CMSId = Auth::guard('cms')->user()->id;
        $createdDate = $request->input('date');

        $formattedDate = $createdDate ? Carbon::parse($createdDate)->format('Y-m-d') : Carbon::today()->format('Y-m-d');

        $searchKey = $request->input('searchKey');

        $serviceType = $request->service_type;

        if ($searchKey) {
            $Model = CMSBag::getServiceModel($serviceType);
            $parselToSearch = $Model::where('barcode_no', $searchKey)
                ->select('destination_cms_bag_id', 'source_cms_bag_id')
                ->first();

            if ($parselToSearch) {
                $source_cms_bag_id = $parselToSearch->source_cms_bag_id;
                $destination_cms_bag_id = $parselToSearch->destination_cms_bag_id;
                $desiredBags = [];

                if ($source_cms_bag_id) {
                    $potentialBag = CMSBag::where('id', $source_cms_bag_id)
                        ->whereDate('created_at', $formattedDate)->first();

                    if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                        $desiredBags[] = $potentialBag;
                    }
                }

                if ($destination_cms_bag_id) {

                    $potentialBag = CMSBag::where('id', $destination_cms_bag_id)
                        ->whereDate('created_at', $formattedDate)->first();

                    if ($potentialBag && $potentialBag->cms_id == $CMSId) {
                        $desiredBags[] = $potentialBag;
                    }
                }
            }
        }

        // Base query for FranchiseBag
        $bagsQuery = CMSBag::where('cms_id', $CMSId)
            ->where('service_type', $serviceType)
            ->whereNotNull('pph_id')
            ->with('pph')
            ->orderBy('created_at', 'desc');


        // Apply date filter if $createdDate is provided
        $bagsQuery->when($formattedDate, function ($query) use ($formattedDate) {
            return $query->whereDate('created_at', $formattedDate);
        });

        $bagsQuery->whereNotNull('pph_id')
            ->with('pph')
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where(function ($q) use ($searchKey) {
                    $q->whereHas('otherCMS', function ($q) use ($searchKey) {
                        $q->where('pincode', 'like', '%' . $searchKey . '%')
                            ->orWhere('city', 'like', '%' . $searchKey . '%');
                    })->orWhere('barcode_no', 'like', '%' . $searchKey . '%');
                });
            });


        $filteredBags = $bagsQuery->get();

        if (!empty($desiredBags)) {
            $filteredBags = collect($desiredBags);
        } else {
            $filteredBags = $bagsQuery->get();
        }

        $refinedData = [];


        foreach ($filteredBags as $data) {
            $cmsId = $data->cms_id;
            $cms = CMS::findOrFail($cmsId);
            $linkDetail = GotogoLink::where('cms_no', $cms->cms_no)->first();
            $refinedData[] = (object)[
                'barcode_image_src' => $data->barcode_img_src,
                'barcode_no'        => $data->barcode_no,
                'franchise_no'      => $linkDetail->franchise_no,
                'cms_no'            => $linkDetail->cms_no,
                'pph_no'            => $linkDetail->pph_no,
            ];
        }

        $title = FranchiseBag::getServiceType($serviceType);
        // Pass data to the view
        $otherPageContent = View::make('print.gotogopost.printBag', ['data' => $refinedData, 'title' => $title])->render();

        return response()->json([

            'otherPageContent' => $otherPageContent

        ]);
    }
}
