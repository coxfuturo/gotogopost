<?php $__env->startSection('title'); ?> Dashboard <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
    .booking li {
        padding: 2px;

    }

    .dash-widget .card-body .dash-widget-icon2 {
        background-color: rgba(255, 155, 68, 0.2);
        color: #ff9b44;
        font-size: 30px;
        height: 60px;
        line-height: 60px;
        margin-right: 10px;
        text-align: center;
        width: 100%;
        border-radius: 100%;
        /* width: 20px; */
    }

    .filterInner {
        display: flex;
        justify-content: end;
    }

    .filterOuter {
        position: absolute;
        z-index: 100;
        top: -65px;
        right: 0;
    }

    .form-focus .form-control {
        height: 40px;
        padding: 21px 12px 6px;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
    }

    .dataTables_length {
        text-align: left !important;
    }

    @media screen and (max-width: 900px) {
        .filterOuter {
            position: unset;
        }

        .filter-button {
            margin-bottom: 10px;
        }
    }
</style>
<div>
    <div class="row" style="position: relative">

        <!-- filter -->
        <div class="col-lg-8 filterOuter">
            <form action="<?php echo e(route('admin.dashboard')); ?>" method="get">
                <?php echo csrf_field(); ?>
                <div class="row p-2 filterInner">
                    <div class="col-sm-3">
                        <div class="input-block mb-3 form-focus focused">
                            <div class="cal-icon">
                                <input name="start" id="startDate" class="form-control floating datetimepicker fromDate" type="text"
                                    value="<?php echo e(request()->query('start') ? \Carbon\Carbon::parse(request()->query('start'))->format('d-m-Y') : ''); ?>">
                            </div>
                            <label class="focus-label">From</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-block mb-3 form-focus focused">
                            <div class="cal-icon">
                                <input name="end" id="endDate" class="form-control floating datetimepicker toDate" type="text"
                                    value="<?php echo e(request()->query('end') ? \Carbon\Carbon::parse(request()->query('end'))->format('d-m-Y') : ''); ?>">
                            </div>
                            <label class="focus-label">To</label>
                        </div>
                    </div>
                    <div class="col-sm-3 filter-button">
                        <button type="submit" class="btn btn-success">Filter</button>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-danger">Remove</a>
                    </div>
                </div>
            </form>
        </div>
        <!-- filter -->

        <!-- Pickup Enquiry -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="<?php echo e(route('cms.pickup-details.index')); ?>">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important; position: relative; top: 0;" class="dash-widget-icon">
                            <img src="<?php echo e(asset('website/images/icon/gotogo logo3.png')); ?>" style="height: 20px; width: auto;">
                        </span>

                        <div class="dash-widget-info">
                            <h3><?php echo e($franchise_count); ?></h3>
                            <span> Franchise </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- End Pickup Enquiry -->

        <!-- mail E2E Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="<?php echo e(route('cms.mailToMail.receivedMails')); ?>">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important; position: relative; top: 0;" class="dash-widget-icon">
                            <img src="<?php echo e(asset('website/images/icon/gotogo logo3.png')); ?>" style="height: 20px; width: auto;">
                        </span>
                        <div class="dash-widget-info">
                            <h3><?php echo e($cms_count); ?></h3>
                            <span> CPH</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!--End mail E2E Report -->

        <!-- mail E2H Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="<?php echo e(route('cms.mailTofranhchise.receivedMails')); ?>">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important; position: relative; top: 0;" class="dash-widget-icon">
                            <img src="<?php echo e(asset('website/images/icon/gotogo logo3.png')); ?>" style="height: 20px; width: auto;">
                        </span>
                        <div class="dash-widget-info">
                            <h3><?php echo e($pph_count); ?></h3>
                            <span> PPH</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!--End mail E2E Report -->

        <!-- pick up list -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="<?php echo e(route('cms.mailTofranhchise.receivedMails')); ?>">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important; position: relative;" class="dash-widget-icon">
                            <img src="<?php echo e(asset('website/images/icon/gotogo logo3.png')); ?>" style="height: 20px; width: auto; margin-bottom:8px;">
                        </span>
                        <!-- <span style="background:none !important;" class="dash-widget-icon"><img src="<?php echo e(asset('website/images/icon/gotogo logo3.png')); ?>"></span> -->
                        <div class="dash-widget-info">
                            <h3><?php echo e($deliveryboy_count); ?></h3>
                            <span> Delivery Boy</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- End pick up list -->
        <!-- booking -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget" style="height:88%">
                <div class="card-body">

                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-4">
                            <h3><?php echo e($gotogolistList); ?></h3>
                            <span>Bookings</span>
                        </div>

                        <div class="col-4" style="text-align: right;">
                            <h3 style="display: block; white-space: nowrap;"><?php echo e($gotogolistAmount); ?></h3>
                            <span style="display: block; white-space: nowrap;">Amount</span>
                        </div>

                    </div>
                    <div class="text-center" style="width:100%">
                        <h4 class="text-center my-3">Gotogo Post Booking Services</h4>
                        <ul class="booking">
                            <li class="">
                                <a href=""><?php echo e(\App\Models\Admin::GOTOGO_POST_SPEED); ?></a>
                            </li>

                            <li class="">
                                <a href=""><?php echo e(\App\Models\Admin::GOTOGO_POST_BUSINESS); ?></a>
                            </li>

                            <li class="">
                                <a href=""><?php echo e(\App\Models\Admin::GOTOGO_POST_REGISTERED); ?></a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget" style="height:88%">
                <div class="card-body">

                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-4">
                            <h3><?php echo e($indiapostlistList); ?></h3>
                            <span>Bookings</span>
                        </div>

                        <div class="col-4" style="text-align: right;">
                            <h3 style="display: block; white-space: nowrap;"><?php echo e($gotogolistIndiaAmount); ?></h3>
                            <span style="display: block; white-space: nowrap;">Amount</span>
                        </div>
                    </div>
                    <div class="text-center" style="width:100%">
                        <h4 class="text-center my-3">Indian Post Booking Services</h4>
                        <ul class="booking">
                            <li class="submenu"></li>
                            <li class="">
                                <a href=""><?php echo e(\App\Models\Admin::INDIA_POST_SPEED); ?></a>
                            </li>
                            <li class="">
                                <a href=""><?php echo e(\App\Models\Admin::INDIA_POST_BUSINESS); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--End booking -->
        <!-- commission -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    <img src="<?php echo e(asset('website/images/logonewtransparent.png')); ?>" alt="" style="height:65px; width:70px; border-radius: 5px;">
                    <div class="dash-widget-info ">
                        <h3><?php echo e($commissionList); ?></h3>
                        <span>Commission Report </span>
                    </div>
                    <div class="text-center" style="width:100%">
                        <h4 class="text-center my-3">Commission Report</h4>
                        <ul class="booking">
                            <li class="submenu"></li>

                            <li class="">
                                <a href="">Commission</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--End commission -->

        <!-- Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-4">
                            <h3><?php echo e($totalMailCount); ?></h3>
                            <span>Bookings Report</span>
                        </div>

                        <div class="col-4" style="text-align: right;">
                            <h3 style="display: block; white-space: nowrap;"><?php echo e($totalEmailAmount); ?></h3>
                            <span style="display: block; white-space: nowrap;">Amount</span>
                        </div>
                    </div>

                    <div class="text-center" style="width:100%">
                        <h4 class="text-center my-3">Mail Report</h4>
                        <ul class="booking">
                            <li>
                                <a href=""><?php echo e(\App\Models\Admin::E2E); ?></a>
                            </li>
                            <li>
                                <a href=""><?php echo e(\App\Models\Admin::E2H); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!--end Report -->

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">

                <div class="col-md-12 text-center">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Gotogo Post Daily Booking Service</h3>

                            <div class="table-responsive">
                                <table class="table table-striped custom-table datatable">
                                    <thead>
                                        <tr>
                                            <th>SN#</th>
                                            <th class="text-center">Barcode</th>
                                            <th class="text-center"><span>From Addresss</span></th>
                                            <th class="text-center">State</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Pincode</th>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">To Addresss</th>
                                            <th class="text-center">State</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Pincode</th>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Weight</th>
                                            <th class="text-center">amount (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $gotogoList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <img style="display: none;" src="data:image/png;base64,<?php echo e($data->barcode_image_src); ?>" alt="Barcode" style="height: 50px;" />
                                            <td><?php echo e($i+1); ?></td>
                                            <td><?php echo e($data->barcode_no); ?></td>
                                            <td><?php echo e($data->pickup_address); ?></td>
                                            <td><?php echo e($data->pickup_state); ?></td>
                                            <td><?php echo e($data->pickup_city); ?></td>
                                            <td><?php echo e($data->pickup_pincode); ?></td>
                                            <td><?php echo e($data->pickup_mobile); ?></td>
                                            <td><?php echo e($data->pickup_email); ?></td>
                                            <td><?php echo e($data->consignee_address); ?></td>
                                            <td><?php echo e($data->consignee_state); ?></td>
                                            <td><?php echo e($data->consignee_city); ?></td>
                                            <td><?php echo e($data->consignee_pincode); ?></td>
                                            <td><?php echo e($data->consignee_mobile); ?></td>
                                            <td><?php echo e($data->consignee_email); ?></td>
                                            <td><?php echo e($data->package_weight); ?></td>
                                            <td><?php echo e($data->payment_amount); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- India Post Speed Post with Pagination -->
<div class="col-md-12 text-center">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Indian Post Speed Post Daily Booking Service</h3>
            
            <!-- Showing records info -->
            <div class="row mb-3">
                <div class="col-md-6 text-start">
                    Showing <strong><?php echo e($indiapostlist->firstItem()); ?></strong> to <strong><?php echo e($indiapostlist->lastItem()); ?></strong> of <strong><?php echo e($indiapostlist->total()); ?></strong> records
                </div>
                <div class="col-md-6 text-end">
                    <span class="text-muted">Page <?php echo e($indiapostlist->currentPage()); ?> of <?php echo e($indiapostlist->lastPage()); ?></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped custom-table">
                    <thead>
                        <tr>
                            <th>SN#</th>
                            <th class="text-center">Barcode</th>
                            <th class="text-center"><span>From Addresss</span></th>
                            <th class="text-center">State</th>
                            <th class="text-center">City</th>
                            <th class="text-center">Pincode</th>
                            <th class="text-center">Mobile</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">To Addresss</th>
                            <th class="text-center">State</th>
                            <th class="text-center">City</th>
                            <th class="text-center">Pincode</th>
                            <th class="text-center">Mobile</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Weight</th>
                            <th class="text-center">amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $indiapostlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($indiapostlist->firstItem() + $i); ?></td>
                            <td><?php echo e($data->barcode_no); ?></td>
                            <td><?php echo e($data->pickup_address); ?></td>
                            <td><?php echo e($data->pickup_state); ?></td>
                            <td><?php echo e($data->pickup_city); ?></td>
                            <td><?php echo e($data->pickup_pincode); ?></td>
                            <td><?php echo e($data->pickup_mobile); ?></td>
                            <td><?php echo e($data->pickup_email); ?></td>
                            <td><?php echo e($data->consignee_address); ?></td>
                            <td><?php echo e($data->consignee_state); ?></td>
                            <td><?php echo e($data->consignee_city); ?></td>
                            <td><?php echo e($data->consignee_pincode); ?></td>
                            <td><?php echo e($data->consignee_mobile); ?></td>
                            <td><?php echo e($data->consignee_email); ?></td>
                            <td><?php echo e($data->package_weight); ?></td>
                            <td><?php echo e($data->payment_amount); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="16" class="text-center">No records found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Links -->
            <div class="row mt-4">
                <div class="col-md-12 d-flex justify-content-center">
                    <?php echo e($indiapostlist->appends(request()->query())->links('pagination::bootstrap-5')); ?>

                </div>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
    <?php $__env->startPush('page-javascript'); ?>
    <script src="<?php echo e(asset('admin/assets/plugins/morris/morris.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/plugins/raphael/raphael.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/chart.js')); ?>"></script>

    <?php $__env->stopPush(); ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>