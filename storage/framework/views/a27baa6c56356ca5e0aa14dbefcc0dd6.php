

<?php $__env->startSection('title'); ?> Customer Booking List <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startPush('add-modal-code'); ?>
<?php $__env->stopPush(); ?>

<!-- Search Filter -->
<form action="<?php echo e(route('market.customer.view.index', ['id' => $id, 'type' => $type])); ?>" method="GET">
    <div class="card">
        <div class="row card-body">
            <!-- Search Key Input -->
            <div class="col-sm-3 col-md-3">
                <div class="input-block mb-3 form-focus">
                    <input type="text" id="searchKey" name="searchKey" class="form-control floating input2" value="<?php echo e(request('searchKey')); ?>">
                    <label class="focus-label">Search</label>
                </div>
            </div>

            <!-- To Date Input -->
            <div class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-12">
                <div class="input-block mb-3 form-focus">
                    <div class="cal-icon">
                        <input id="date" name="date" class="form-control floating datetimepicker" type="text" value="<?php echo e(request('date')); ?>">
                    </div>
                    <label class="focus-label">Date</label>
                </div>
            </div>

            <!-- Search Button -->
            <div class="col-sm-3">
                <div class="d-grid">
                    <button type="submit" class="btn marketGreenBackground" style="width:fit-content;">Search</button>
                </div>
            </div>

            <div class="col-sm-4 text-end">
                <a class="btn marketOrangeBackground" style="margin-right: 20px"
                   href="<?php echo e(route('market.customer.export.parcel', array_merge(['id' => $id, 'type' => $type], request()->only('date', 'searchKey')))); ?>">
                   Download
                </a>

                <a href="<?php echo e(route('market.customer.index')); ?>">
                    <button type="button" class="btn marketGreenBackground" style="width:fit-content;float:right;">Back</button>
                </a>
            </div>
        </div>
    </div>
</form>
<!-- End Search Filter -->

<div class="row">
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <span class="text-center date-above-table"><?php echo e(now()->format('d-m-Y')); ?></span>
                    <span class="text-center date-above-table" style="margin-left: 150px;">Total Parcel: <?php echo e($count); ?></span>
                    <span class="text-center date-above-table" style="margin-left: 400px;">Total Amount: ₹<?php echo e($total_amount); ?></span>

                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th class="text-end" style="width:2px">Booking<br>View </th>
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
                                <th class="text-center">GST (₹)</th>
                                <th class="text-center">Total (₹)</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <img style="display:none" src="data:image/png;base64,<?php echo e($data->barcode_image_src); ?>" alt="Barcode" />
                                    <td class="text-end">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="material-icons text-white">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right marketOrangeBackground">
                                                <a class="dropdown-item" href="<?php echo e(route('market.customer.parcel.details', ['id' => $data->id, 'type'=>$type])); ?>">
                                                    <i class="fa-regular fa-eye m-r-5"></i> View
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profile_info_<?php echo e($data->id); ?>">
                                                    <i class="fa-regular fa-trash-can m-r-5"></i> Cancel
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                      <td><?php echo e(($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration); ?></td>
                                   
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
                                    <td>₹<?php echo e(number_format($data->payment_amount / 1.18, 2)); ?></td>
                                    <td>₹<?php echo e(number_format($data->payment_amount - ($data->payment_amount / 1.18), 2)); ?></td>
                                    <td>₹<?php echo e($data->payment_amount); ?></td>
                                    
                                </tr>

                                <!-- Cancel Modal (inside loop) -->
                                <div id="profile_info_<?php echo e($data->id); ?>" class="modal custom-modal fade" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Cancel Parcel Request</h5>
                                                
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <center> <h4>Your Parcel Status <?php if($data->status == 1): ?>
                                                                         <span style="color:#FF671F ">Cancel Pending</span>

                                                                        <?php else: ?> if($data->status == 2)
                                                                           <span style="color:#046A38"> Cancel successfully</span>

                                                                         <?php endif; ?>
                                                </h4></center><hr>
                                            <div class="modal-body">
                                                <form id="franchiseForm_<?php echo e($data->id); ?>" method="post" action="<?php echo e(route('market.customer.parcel.cancel', ['id' => $data->id, 'type' => $type])); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="input-block mb-3">
                                                                <label class="col-form-label">Reason for Cancellation</label>
                                                                <textarea name="discription" class="form-control" required><?php echo e($data->discription); ?></textarea>
                                                                
                                                        </div>
                                                    </div>
                                                    <div class="submit-section">
                                                        <button class="btn submit-btn" style="background:#FF671F;color:#fff ">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <!-- Table ke baad yeh add karo -->
<div class="row mt-4">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info">
            Showing <?php echo e($datas->firstItem()); ?> to <?php echo e($datas->lastItem()); ?> of <?php echo e($datas->total()); ?> entries
        </div>
    </div>
    <div class="col-sm-12 col-md-7">
        <div class="dataTables_paginate">
            <?php echo e($datas->appends(request()->query())->links('pagination::bootstrap-4')); ?>

        </div>
    </div>
</div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('page-javascript'); ?>
<script>
    // JS functions or variables (if needed)
    var datas = <?php echo json_encode($datas, 15, 512) ?>;
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('market.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/market/customer/view.blade.php ENDPATH**/ ?>