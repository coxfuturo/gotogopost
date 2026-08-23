 
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

.filterInner{
    display: flex;
    justify-content: end;
}

.filterOuter{
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

    .filter-button{
        margin-bottom: 10px;
    }
}


.booking li {
    margin-top: 5px !important;
    background: #de0114  !important;
    /* padding: 0px; */
}
.booking li a{
    color: #fff !important;
}

.dash-widget .card-body .dash-widget-info h3 {
    font-size: 24px !important;
    font-weight: 600 !important;
    margin-bottom: 5px !important;
}
.dash-widget .card-body .dash-widget-info {
    text-align: right !important;
    width: calc(100% - 70px);
    width: fit-content !important;
    /* margin-left: 20px !important; */
}

.card {
    border: 1px solid #ededed;
    margin-bottom: 20px !important;
}

.dash-widget .card-body .dash-widget-icon {
    background-color: rgba(255, 155, 68, 0.2);
    color: #ff9b44 !important;
    font-size: 30px !important;
    height: 15px !important;
    /* line-height: 60px; */
    margin-right: 10px !important;
    text-align: center !important;
    /* width: 80px !important; */
    border-radius: 100%;
}

</style>
<div>
    <div class="row" style="position: relative">

        <!-- filter -->
        <div class="col-lg-8 filterOuter">
            <form action="<?php echo e(route('franchise.dashboard')); ?>" method="get">
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
                    <div class="col-sm-5 filter-button">
                        <button type="submit" class="btn btn-success">Filter</button>
                        <a href="<?php echo e(route('franchise.dashboard')); ?>" class="btn btn-danger">Remove</a>
                    </div>
                </div>
            </form>
        </div>
         <!-- filter -->
       
        <!-- Delivery Boys -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="<?php echo e(route('franchise.delboy.index')); ?>">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="<?php echo e(asset('website/images/icon/gotogo logo3.png')); ?>"></span>
                        <div class="dash-widget-info">
                            <h3><?php echo e($deliveryboy); ?></h3>
                            <span>Delivery Boys</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- End Delivery Boys -->

        <!-- Pickup Enquiry -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="<?php echo e(route('franchise.pickup-details.index')); ?>">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="<?php echo e(asset('website/images/icon/gotogo logo3.png')); ?>"></span>
                        <div class="dash-widget-info">
                            <h3><?php echo e($pickuplist); ?></h3>
                            <span>Pickup Enquiry</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- End Pickup Enquiry -->

        <!-- mail E2E Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="<?php echo e(route('franchise.mailToMail.receivedMails')); ?>">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="<?php echo e(asset('website/images/icon/gotogo logo3.png')); ?>"></span>
                        <div class="dash-widget-info">
                            <h3><?php echo e($MailE2e); ?></h3>
                            <span>Mail E2E Report</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!--End mail E2E Report -->

        <!-- mail E2H Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="<?php echo e(route('franchise.mailTofranhchise.receivedMails')); ?>">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="<?php echo e(asset('website/images/icon/gotogo logo3.png')); ?>"></span>
                        <div class="dash-widget-info">
                            <h3><?php echo e($MailE2h); ?></h3>
                            <span>Mail E2H Report</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!--End mail E2E Report -->
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
                        <h4 class="text-center my-2">Gotogo Post Booking Services</h4>
                        <ul class="booking">
                            <li class="">
                                <a href="<?php echo e(route('franchise.go-speed-post-parcel.create')); ?>"><?php echo e(\App\Models\Admin::GOTOGO_POST_SPEED); ?></a>
                            </li>

                            <li class="">
                                <a href="<?php echo e(route('franchise.go-business-parcel.create')); ?>"><?php echo e(\App\Models\Admin::GOTOGO_POST_BUSINESS); ?></a>
                            </li>

                            <li class="">
                                <a href="<?php echo e(route('franchise.go-registered.create')); ?>"><?php echo e(\App\Models\Admin::GOTOGO_POST_REGISTERED); ?></a>
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
                        <h4 class="text-center my-2">All India Post Booking Services</h4>
                        <ul class="booking">
                            <!-- <li class="submenu"></li> -->
                            <li class="">
                                <a href="<?php echo e(route('franchise.india-post-speed-post.create')); ?>"><?php echo e(\App\Models\Admin::INDIA_POST_SPEED); ?></a>
                            </li>
                            <li class="">
                                <a href="<?php echo e(route('franchise.india-post-business.create')); ?>"><?php echo e(\App\Models\Admin::INDIA_POST_BUSINESS); ?></a>
                            </li>
                            <!-- <li><h4 class="text-center my-2">Commission Report</h4></li> -->
                            <li><a href="<?php echo e(route('franchise.commission.index')); ?>">Discount </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--End booking -->
        <!-- commission -->

        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4" >
            <div class="card dash-widget" style="height:88%;background: linear-gradient(to bottom, #FF671F, #046A38) !important;" >
                <div class="card-body">
                    
                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-4">
                           <!-- <img src="<?php echo e(asset('website/images/logonewtransparent.png')); ?>" alt="" style="height:65px; width:70px; border-radius: 5px;">  -->
                            
                            <h3 style="color: #fff">0</h3>
                            <span style="color: #fff">Bookings</span>
                        </div>
                        
                        <div class="col-4" style="text-align: right;"> 
                            <h3 style="display: block; white-space: nowrap;color:#fff">0</h3>
                            <span style="display: block; white-space: nowrap;color:#fff">Amount</span>
                        </div>
                    </div>
                    <div class="text-center" style="width:100%">
                        <h4 style="color: #fff !important; class="text-center my-2">Movin Booking Services</h4>
                        <ul class="booking" >
                            <!-- <li class="submenu"></li> -->
                            <li class="" style="background: #fff !important;color:#000 !important;">
                                <a style="color: #000 !important;" href="<?php echo e(route('franchise.india-post-speed-post.create')); ?>"><?php echo e(\App\Models\Admin::INDIA_POST_SPEED); ?></a>
                            </li>
                            <li class="" style="background: #fff !important;color:#000 !important;">
                                <a style="color: #000 !important;" href="<?php echo e(route('franchise.india-post-business.create')); ?>"><?php echo e(\App\Models\Admin::INDIA_POST_BUSINESS); ?></a>
                            </li>
                            <!-- <li><h4 class="text-center my-2">Commission Report</h4></li> -->
                            <li style="background: #fff !important;color:#000 !important;"><a style="color: #000 !important;" href="<?php echo e(route('franchise.commission.index')); ?>">Discount </a></li>
                        </ul>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!--End commission -->

        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <a href="<?php echo e(route('franchise.mailToMail.create')); ?>"><div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-12">
                          <h3 style="color:#1e3d59"><span style="font-size: 38px;color:#de0114;">e</span>2E</h3>
                        </div>
                        <div class="col-4">
                            <h3 style="font-size: 22px;"><?php echo e($MailE2e); ?></h3>
                            <span>Bookings</span>
                        </div>

                        <div class="col-4">
                            <h3 style="font-size: 22px;"><?php echo e($totalEmailAmount); ?></h3>
                            <span>Amount</span>
                        </div>
                  
                        <div class="col-4" style="text-align: right;"> 
                            <h3 style="font-size: 22px;"><?php echo e($totalBalance); ?></h3>
                            <span>Balance</span>
                        </div>
                    </div>

                </div>
            </div></a>
        </div>
        <!-- End e2h -->

         <!-- e2h -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <a href="<?php echo e(route('franchise.mailTofranhchise.create')); ?>"><div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-12">
                          <h3 style="color:#1e3d59"><span style="font-size: 38px;color:#de0114;">e</span>2H</h3>
                        </div>
                        <div class="col-4">
                            <h3 style="font-size: 22px;"><?php echo e($MailE2h); ?></h3>
                            <span>Bookings</span>
                        </div>

                        <div class="col-4">
                            <h3 style="font-size: 22px;"><?php echo e($totalEmailAmounte2h); ?></h3>
                            <span>Amount</span>
                        </div>
                  
                        <div class="col-4" style="text-align: right;"> 
                            <h3 style="font-size: 22px;"><?php echo e($totalBalance); ?></h3>
                            <span>Balance</span>
                        </div>
                    </div>
                    
                </div>
            </div></a>
        </div>
        <!-- End e2h -->

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
                                         <?php $__currentLoopData = $gotogolist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

                <div class="col-md-12 text-center">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">All India Post Daily Booking Service</h3>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-striped custom-table" id="indiaPostTable">
                          <thead>
                            <tr>
                                <th>SN#</th>
                                <th class="text-center">Barcode</th>
                                <th class="text-center">From Address</th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">To Address</th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Weight</th>
                                <th class="text-center">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $indiapostlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(($indiapostlist->currentPage() - 1) * $indiapostlist->perPage() + $i + 1); ?></td>
                                <td><?php echo e($data->barcode_no ?? 'N/A'); ?></td>
                                <td><?php echo e(Str::limit($data->pickup_address ?? 'N/A', 20)); ?></td>
                                <td><?php echo e($data->pickup_state ?? 'N/A'); ?></td>
                                <td><?php echo e($data->pickup_city ?? 'N/A'); ?></td>
                                <td><?php echo e($data->pickup_pincode ?? 'N/A'); ?></td>
                                <td><?php echo e($data->pickup_mobile ?? 'N/A'); ?></td>
                                <td><?php echo e($data->pickup_email ?? 'N/A'); ?></td>
                                <td><?php echo e(Str::limit($data->consignee_address ?? 'N/A', 20)); ?></td>
                                <td><?php echo e($data->consignee_state ?? 'N/A'); ?></td>
                                <td><?php echo e($data->consignee_city ?? 'N/A'); ?></td>
                                <td><?php echo e($data->consignee_pincode ?? 'N/A'); ?></td>
                                <td><?php echo e($data->consignee_mobile ?? 'N/A'); ?></td>
                                <td><?php echo e($data->consignee_email ?? 'N/A'); ?></td>
                                <td><?php echo e($data->package_weight ?? 'N/A'); ?></td>
                                <td>₹<?php echo e($data->payment_amount ?? '0'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="16" class="text-center">No records found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination for India Post List -->
                <div class="pagination-container" id="indiaPostPagination">
                    <?php echo e($indiapostlist->links()); ?>

                </div>
                
                <div class="pagination-info">
                    Showing <?php echo e($indiapostlist->firstItem()); ?> to <?php echo e($indiapostlist->lastItem()); ?> of <?php echo e($indiapostlist->total()); ?> entries
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('page-javascript'); ?>
<script>
$(document).ready(function() {
    // Fix pagination links
    $('#indiaPostPagination').on('click', '.pagination a', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        
        // Get current query parameters
        var start = $('input[name="start"]').val();
        var end = $('input[name="end"]').val();
        
        // Add parameters to URL
        if(start || end) {
            if(url.indexOf('?') > -1) {
                url += '&start=' + start + '&end=' + end;
            } else {
                url += '?start=' + start + '&end=' + end;
            }
        }
        
        // Add gotogo_page parameter if exists
        var gotogoPage = getParameterByName('gotogo_page');
        if(gotogoPage) {
            url += '&gotogo_page=' + gotogoPage;
        }
        
        // Load the page
        window.location.href = url;
    });
    
    // Function to get query parameter by name
    function getParameterByName(name) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(window.location.search);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
    
    // Fix existing pagination links on page load
    $('#indiaPostPagination .pagination a').each(function() {
        var href = $(this).attr('href');
        if(href) {
            // Get current query parameters
            var start = $('input[name="start"]').val();
            var end = $('input[name="end"]').val();
            var gotogoPage = getParameterByName('gotogo_page');
            
            // Update href with all parameters
            var newHref = href;
            if(start || end) {
                if(newHref.indexOf('?') > -1) {
                    newHref += '&start=' + start + '&end=' + end;
                } else {
                    newHref += '?start=' + start + '&end=' + end;
                }
            }
            
            if(gotogoPage) {
                newHref += '&gotogo_page=' + gotogoPage;
            }
            
            $(this).attr('href', newHref);
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
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
<?php echo $__env->make('franchise.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/franchise/dashboard.blade.php ENDPATH**/ ?>