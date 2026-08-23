<?php $__env->startSection('title'); ?> Franchise Management <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<style>
    .tab-content {
        padding-top: 0px;
    }

    .personal-info li .text {
        color: #bb2828;
        display: block;
        overflow: hidden;
        width: 70%;
        float: left;
    }

    table.table-new.dataTable>thead .sorting:after,
    table.table-new.dataTable>thead .sorting_asc:after,
    table.table-new.dataTable>thead .sorting_desc:after,
    table.table-new.dataTable>thead .sorting_asc_disabled:after,
    table.table-new.dataTable>thead .sorting_desc_disabled:after {
        right: 0.5em;
        content: "\f0d7";
        font-family: "FontAwesome";
        top: 15px;
        color: #C1CCDB;
        font-size: 12px;
        opacity: 1;
    }

    table.table-new.dataTable>thead .sorting:before,
    table.table-new.dataTable>thead .sorting_asc:before,
    table.table-new.dataTable>thead .sorting_desc:before,
    table.table-new.dataTable>thead .sorting_asc_disabled:before,
    table.table-new.dataTable>thead .sorting_desc_disabled:before {
        right: 0.5em;
        content: "\f0d8";
        font-family: "FontAwesome";
        top: 5px;
        color: #C1CCDB;
        font-size: 12px;
        opacity: 1;
    }

    .personal-info li .document-title {
        color: #333333;
        float: left;
        font-weight: 500;
        width: auto;
    }
</style>


<div class="card mb-0">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                        <?php
                            $photos = isset($franchise->kyc->photo) ? explode(',', $franchise->kyc->photo) : [];
                            $firstPhoto = !empty($photos[0]) ? asset('admin/franchise/' . $franchise->generated_id . '/' . trim($photos[0])) : asset('admin/assets/img/profiles/avatar-02.jpg');
                        ?>
                    
                        <a href="#"><img src="<?php echo e($firstPhoto); ?>" alt="Profile Image"></a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0"><?php echo e($franchise->name); ?></h3>
                                    <h5 class="">Father/Husband Name : <?php echo e($franchise->father_name); ?></h5>
                                    <!-- <small class="text-muted">Web Designer</small> -->
                                    <div class="staff-id">ID : <?php echo e($franchise->generated_id); ?></div>
                                    <div class="doj">Date of registration : <?php echo e(date('d M Y',strtotime($franchise->created_at))); ?></div>
                                    <div class="staff-msg"><a class="btn btn-custom" href="<?php echo e(route('admin.support-ticket.get_details',$franchise->id)); ?>">Send Message</a></div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Phone:</div>
                                        <div class="text"><a href="#"><?php echo e($franchise->mobile); ?></a></div>
                                    </li>
                                    <li>
                                        <div class="title">Email:</div>
                                        <div class="text"><a href="#"><?php echo e($franchise->email); ?></a></div>
                                    </li>

                                    <li>
                                        <div class="title">City/District:</div>
                                        <div class="text"><?php echo e($franchise->city); ?>/<?php echo e($franchise->district); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">State:</div>
                                        <div class="text"><?php echo e($franchise->state); ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Address:</div>
                                        <div class="text"><?php echo e($franchise->address); ?></div>
                                    </li>

                                    <li>
                                        <div class="title">Status:</div>
                                        <div class="text">
                                            <select class="select" id="status" onchange="status_update('<?php echo e($franchise->id); ?>')">
                                                <option value="1" <?php echo e($franchise->status == 1 ? 'selected':''); ?>>Active</option>
                                                <option value="0" <?php echo e($franchise->status == 0 ? 'selected':''); ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pro-edit"><a data-bs-target="#profile_info" data-bs-toggle="modal" class="edit-icon" href="#"><i class="fa-solid fa-pencil"></i></a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card tab-box">
    <div class="row user-tabs">
        <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item"><a href="#emp_profile" data-bs-toggle="tab" class="nav-link active">Profile</a></li>
                <li class="nav-item"><a href="#parcel" data-bs-toggle="tab" class="nav-link">Parcels</a></li>
                <li class="nav-item"><a href="#franchise_daily_booking_report" data-bs-toggle="tab" class="nav-link">Daily Booking Report</a></li>
                <li class="nav-item"><a href="#franchise_assigned_services" data-bs-toggle="tab" class="nav-link">Assigned Services</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="tab-content">

    <!-- Profile Info Tab -->
    <div id="emp_profile" class="pro-overview tab-pane fade show active">
        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <div class="card-body">
                        <h3 class="card-title">Bank information</h3>
                        <ul class="personal-info">
                            <li>
                                <div class="title">Bank name</div>
                                <div class="text"><?php echo e($franchise->kyc->bank_name); ?></div>
                            </li>
                            <li>
                                <div class="title">Branch name</div>
                                <div class="text"><?php echo e($franchise->kyc->branch_name); ?></div>
                            </li>
                            <li>
                                <div class="title">Bank account No.</div>
                                <div class="text"><?php echo e($franchise->kyc->account_number); ?></div>
                            </li>
                            <li>
                                <div class="title">IFSC Code</div>
                                <div class="text"><?php echo e($franchise->kyc->ifsc_code); ?></div>
                            </li>
                            <li>
                                <div class="title">PAN No</div>
                                <div class="text"><?php echo e($franchise->kyc->pan_card); ?></div>
                            </li>
                            <li>
                                <div class="title">Adhar No</div>
                                <div class="text"><?php echo e($franchise->kyc->adhar_card); ?></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <div class="card-body">
                        <h3 class="card-title">Documents</h3>
                        <ul class="personal-info">
                            <div class="d-flex justify-content-between">
                                <div style="width:100%;text-align:center">
                                    <li class="d-flex justify-content-between px-3">
                                        <div class="document-title">Adhar Card</div>
                                        <div data-bs-toggle="modal" data-bs-target="#adhar_view"><button class="btn btn-sm btn-dark">View</button></div>
                                    </li>
                                    <li class="d-flex justify-content-between px-3">
                                        <div class="document-title">Pan Card</div>
                                        <div data-bs-toggle="modal" data-bs-target="#pan_view"><button class="btn btn-sm btn-dark">View</button></div>
                                    </li>
                                    <li class="d-flex justify-content-between px-3">
                                        <div class="document-title">Cancel Cheque</div>
                                        <div data-bs-toggle="modal" data-bs-target="#cheque_view"><button class="btn btn-sm btn-dark">View</button></div>
                                    </li>
                                </div>

                                <div style="width:100%;text-align:center">
                                    <li class="d-flex justify-content-between px-3">
                                        <div class="document-title">Franchise Image</div>
                                        <div data-bs-toggle="modal" data-bs-target="#franchise_view"><button class="btn btn-sm btn-dark">View</button></div>
                                    </li>

                                    <li class="d-flex justify-content-between px-3">
                                        <div class="document-title">Other Document</div>
                                        <div data-bs-toggle="modal" data-bs-target="#other_document"><button class="btn btn-sm btn-dark">View</button></div>
                                    </li>

                                    <li class="d-flex justify-content-between px-3">
                                        <div class="document-title">Video Kyc</div>
                                        <div data-bs-toggle="modal" data-bs-target="#video_kyc"><button class="btn btn-sm btn-dark">View</button></div>
                                    </li>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- /Profile Info Tab -->

    <!-- Adhar view  Modal -->
    <div class="modal custom-modal fade" id="adhar_view" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Adhar Card</h3>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <?php if(!empty($franchise->kyc->adhar_front_img)): ?>
                                <?php
                                    $frontImages = explode(',', $franchise->kyc->adhar_front_img);
                                ?>
                    
                                <?php $__currentLoopData = $frontImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4">
                                        <img src="<?php echo e(asset('admin/franchise/' . $franchise->generated_id . '/' . $image)); ?>" alt="Adhar Front" class="img-thumbnail">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    
                        <div class="row">
                            <?php if(!empty($franchise->kyc->adhar_back_img)): ?>
                                <?php
                                    $backImages = explode(',', $franchise->kyc->adhar_back_img);
                                ?>
                    
                                <?php $__currentLoopData = $backImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4">
                                        <img src="<?php echo e(asset('admin/franchise/' . $franchise->generated_id . '/' . $image)); ?>" alt="Adhar Back" class="img-thumbnail">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- /Adhar view Modal -->

    <!-- Pan view  Modal -->
    <div class="modal custom-modal fade" id="pan_view" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Pan Card</h3>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <?php if(!empty($franchise->kyc->pan_img)): ?>
                                <?php
                                    $panImages = explode(',', $franchise->kyc->pan_img);
                                ?>
                    
                                <?php $__currentLoopData = $panImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4">
                                        <img src="<?php echo e(asset('admin/franchise/' . $franchise->generated_id . '/' . $image)); ?>" alt="PAN Image" class="img-thumbnail">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- /Pan view Modal -->

    <!-- Cheque view  Modal -->
    <div class="modal custom-modal fade" id="cheque_view" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Cancel Cheque</h3>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <?php if(!empty($franchise->kyc->cheque_img)): ?>
                                <?php
                                    $chequeImages = explode(',', $franchise->kyc->cheque_img);
                                ?>
                    
                                <?php $__currentLoopData = $chequeImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4">
                                        <img src="<?php echo e(asset('admin/franchise/' . $franchise->generated_id . '/' . $image)); ?>" alt="Cheque Image" class="img-thumbnail">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- /Cheque view Modal -->


    <!-- faranchise view  Modal -->
    <div class="modal custom-modal fade" id="franchise_view" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Franchise Image</h3>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <?php if(!empty($franchise->kyc->photo)): ?>
                                <?php
                                    $photos = explode(',', $franchise->kyc->photo);
                                ?>
                    
                                <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4">
                                        <img src="<?php echo e(asset('admin/franchise/' . $franchise->generated_id . '/' . $image)); ?>" alt="Photo" class="img-thumbnail">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <!-- faranchise view  Modal -->

    <!-- other document kyc  Modal -->
    <div class="modal custom-modal fade" id="other_document" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Other Document</h3>
                    </div>

                    <div class="modal-btn delete-action">
                        <div class="row">
                            <?php if(!empty($franchise->kyc->other_document)): ?>
                                <?php
                                    $otherDocuments = explode(',', $franchise->kyc->other_document);
                                ?>
                    
                                <?php $__currentLoopData = $otherDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4">
                                        <img src="<?php echo e(asset('admin/franchise/' . $franchise->generated_id . '/' . $image)); ?>" alt="Other Document" class="img-thumbnail">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <!-- other document Modal -->

    <!-- video_kyc  Modal -->
    <div class="modal custom-modal fade" id="video_kyc" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Video KYC</h3>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <video width="640" height="480" controls>
                                <source src="<?php echo e(asset('admin/franchise/' . $franchise->generated_id . '/' . $franchise->kyc->video_kyc)); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- video_kyc  Modal -->



    <!-- Parcel Tab -->
    <div class="tab-pane fade" id="parcel">

        <!-- Search Filter -->
        <div class="row">

            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                <div class="input-block mb-3 form-focus">
                    <div class="cal-icon">
                        <input class="form-control floating datetimepicker date" type="text">
                    </div>
                    <label class="focus-label">Date</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-1">
                <div class="d-grid">
                    <button type="submit" onclick="filterParcelsByDate()" class="btn btn-success">Search</button>
                </div>
            </div>
        </div>
        <!-- /Search Filter -->
        <div class="table-responsive table-newdatatable m-0">
            <table class="table table-new custom-table mb-0 datatable">
                <thead>
                    <tr>
                        <th>SN#</th>
                        <th>Service</th>
                        <th>Pickup Name</th>
                        <th>Pickup Pincode</th>
                        <th>Consignee Name</th>
                        <th>Consignee Pincode</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($parcel->count()>0): ?>
                    <?php $__currentLoopData = $parcel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($i+1); ?></td>
                        <td><?php echo e($item->service_type); ?></td>
                        <td><?php echo e($item->pickup_name); ?></td>
                        <td><?php echo e($item->pickup_pincode); ?></td>
                        <td><?php echo e($item->consignee_name); ?></td>
                        <td><?php echo e($item->consignee_pincode); ?></td>
                        <td>
                            <div class="table-actions d-flex">
                                <a class="delete-table me-2" href="<?php echo e(route('admin.parcel.view', ['id' => $item->barcode_no, 'service_type' => $item->service_number])); ?>">
                                    <img src="<?php echo e(asset('admin/assets/img/icons/eye.svg')); ?>" alt="Eye Icon">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
    <!-- /Parcel Tab -->


    <!-- Daily booking report -->
    <div class="tab-pane fade" id="franchise_daily_booking_report">
        <div class="table-responsive table-newdatatable">
            <!-- Search Filter -->
            <div class="row">

                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                    <div class="input-block mb-3 form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker bookingdate" type="text">
                        </div>
                        <label class="focus-label">Date</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-1">
                    <div class="d-grid">
                        <button type="submit" onclick="filterDailyBookingsReportByDate()" class="btn btn-success">Search</button>
                    </div>
                </div>
            </div>
            <!-- /Search Filter -->
            <table class="table table-new custom-table mb-0 datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Service</th>
                        <th>No of Articles</th>
                        <th>Total Value</th>
                        <th>Wallet Amount</th>
                        <th>Wallet Balalnce</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $__currentLoopData = $bookingData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($key); ?></td>
                        <td><?php echo e($value['service_type']); ?></td>
                        <td><?php echo e($value['No_of_article']); ?></td>
                        <td><?php echo e($value['total_value']); ?></td>
                        <td><?php echo e($value['wallet_balance']); ?></td>
                        <td><?php echo e($value['remaining_balance']); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>
            </table>
        </div>
    </div>
    <!-- Daily booking report -->


    <!-- Assigned Services -->
    <div class="tab-pane fade" id="franchise_assigned_services">
        <div class="table-responsive table-newdatatable">

            <table class="table table-new custom-table mb-0 datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">Service</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- GOTOGO Post Speed -->
                    <tr>
                        <td>1</td>
                        <td>GOTOGO Post Speed</td>
                        <td>
                            <div class="dropdown action-label goto-post-speed-class">
                                <?php if($franchise->gotogo_speed_post == 1): ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                <?php else: ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                <?php endif; ?>
                                <div class="dropdown-menu active-inactive-menu-1">
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '1', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '1', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- GOTOGO Post Business Parcel -->
                    <tr>
                        <td>2</td>
                        <td>GOTOGO Post Business Parcel</td>
                        <td>
                            <div class="dropdown action-label goto-post-business-class">
                                <?php if($franchise->gotogo_business_parcel == 1): ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                <?php else: ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                <?php endif; ?>
                                <div class="dropdown-menu active-inactive-menu-3">
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '3', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '3', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>


                    <!-- GOTOGO Post Registered -->
                    <tr>
                        <td>3</td>
                        <td>GOTOGO Post Registered</td>
                        <td>
                            <div class="dropdown action-label active-inactive-menu-3">
                                <?php if($franchise->gotogo_post_registered == 1): ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                <?php else: ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                <?php endif; ?>
                                <div class="dropdown-menu active-inactive-menu-4">
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '4', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '4', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>


                    <!-- Additional rows -->
                    <!-- India Post Speed -->
                    <tr>
                        <td>4</td>
                        <td>India Post Speed</td>
                        <td>
                            <div class="dropdown action-label india-post-speed-class">
                                <?php if($franchise->india_post_speed == 1): ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                <?php else: ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                <?php endif; ?>
                                <div class="dropdown-menu active-inactive-menu-5">
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '5', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '5', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- India Post Business -->
                    <tr>
                        <td>5</td>
                        <td>India Post Business</td>
                        <td>
                            <div class="dropdown action-label india-post-business-class">
                                <?php if($franchise->india_post_business == 1): ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                <?php else: ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                <?php endif; ?>
                                <div class="dropdown-menu active-inactive-menu-6">
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '6', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '6', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- India Post Registered -->
                    <tr>
                        <td>6</td>
                        <td>India Post Registered</td>
                        <td>
                            <div class="dropdown action-label india-post-registered-class">
                                <?php if($franchise->india_post_registered == 1): ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                <?php else: ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                <?php endif; ?>
                                <div class="dropdown-menu active-inactive-menu-7">
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '7', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '7', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- E2E -->
                    <tr>
                        <td>7</td>
                        <td>E2E</td>
                        <td>
                            <div class="dropdown action-label india-post-registered-class">
                                <?php if($franchise->e2e == 1): ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                <?php else: ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                <?php endif; ?>
                                <div class="dropdown-menu active-inactive-menu-8">
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '8', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '8', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- E2E -->
                    <tr>
                        <td>8</td>
                        <td>E2H</td>
                        <td>
                            <div class="dropdown action-label india-post-registered-class">
                                <?php if($franchise->e2h == 1): ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                <?php else: ?>
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                <?php endif; ?>
                                <div class="dropdown-menu active-inactive-menu-9">
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '9', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('<?php echo e($franchise->id); ?>', '9', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Assigned Services -->

</div>
<!-- Profile Modal -->
<div id="profile_info" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-img-wrap edit-img">
                                <div id="fileContainer1">
                                    <img class="inline-block" src="<?php echo e(isset($franchise->kyc->photo) ? asset('admin/franchise/'.$franchise->generated_id.'/'.$franchise->kyc->photo) : asset('admin/assets/img/profiles/avatar-02.jpg')); ?>">
                                </div>
                                <div class="fileupload btn">
                                    <span class="btn-text">Edit</span>
                                    <input class="upload" id="fileInput1" onchange="displayFile(1)" type="file">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo e($franchise->name); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Father/Husband Name</label>
                                        <input type="text" class="form-control" name="father_name" value="<?php echo e($franchise->father_name); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="mobile" value="<?php echo e($franchise->mobile); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Email</label>
                                        <input type="text" class="form-control" name="email" value="<?php echo e($franchise->email); ?>" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Address</label>
                                <textarea name="address" class="form-control" id="address" required><?php echo e(old('address',$franchise->address)); ?></textarea>
                                <input type="hidden" name="latitude" value="<?php echo e(old('latitude',$franchise->latitude)); ?>" id="latitude">
                                <input type="hidden" name="longitude" value="<?php echo e(old('longitude',$franchise->longitude)); ?>" id="longitude">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Pin Code</label>
                                <input type="text" class="form-control NumberValidate" id="pincode" maxlength="6" name="pincode" value="<?php echo e($franchise->pincode); ?>" required>
                                <div class="invalid-feedback validerror">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">State</label>
                                <input type="text" class="form-control" id="state" readonly name="state" value="<?php echo e($franchise->state); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">District</label>
                                <input type="text" class="form-control" id="district" readonly name="district" value="<?php echo e($franchise->district); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo e($franchise->city); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Profile Modal -->

<!-- Bank Modal -->
<div id="bank_info" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bank Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Pan Card No.</label>
                                        <input type="text" class="form-control" name="pan_card" value="<?php echo e($franchise->kyc->pan_card); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Adhar Card No.</label>
                                        <input type="text" class="form-control" name="adhar_card" value="<?php echo e($franchise->kyc->adhar_card); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Bank Account No.</label>
                                        <input type="text" class="form-control" name="account_number" value="<?php echo e($franchise->kyc->account_number); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">IFSC Code</label>
                                        <input type="text" class="form-control" name="ifsc_code" maxlength="11" onkeyup="return forceUpper(this);" value="<?php echo e($franchise->kyc->ifsc_code); ?>" required>
                                        <div class="text-danger" id="bank_ifsc_error"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Bank Name</label>
                                        <input type="text" class="form-control" name="bank_name" value="<?php echo e($franchise->kyc->bank_name); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Branch Name</label>
                                        <input type="text" class="form-control" name="branch_name" value="<?php echo e($franchise->kyc->branch_name); ?>" required>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Bank Modal -->


<?php $__env->startPush('page-javascript'); ?>

<script>
    function status_update(id) {
        var update_status = $('#status').find('option:selected').val();
        $.ajax({
            url: "<?php echo e(route('admin.franchise.status')); ?>",
            type: "POST",
            data: {
                "_token": "<?php echo e(csrf_token()); ?>",
                id: id,
                status: update_status,
            },
            success: function(data) {
                if (data.success == true) {
                    Swal.fire('Success!', "Status updated", 'success');
                } else {
                    Swal.fire('Error!', "Something went wrong", 'error')
                }

            }

        });
    }



    function service_status_update(franchiseId, serviceType, status) {
        $.ajax({
            url: "<?php echo e(route('admin.franchise.serviceStatus')); ?>",
            method: 'POST',
            data: {
                franchise_id: franchiseId,
                service_type: serviceType,
                status: status,
                _token: '<?php echo e(csrf_token()); ?>'
            },
            success: function(data) {
                if (data.success) {
                    Swal.fire('Success!', "Status updated", 'success');

                    // Determine the new button based on the status
                    var newElement = $('<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></a>');
                    var iconClass = data.status == '0' ? 'text-danger' : 'text-success';
                    var statusText = data.status == '0' ? 'Inactive' : 'Active';

                    newElement.html('<i class="fa-regular fa-circle-dot ' + iconClass + '"></i> ' + statusText);

                    // Log the new element for debugging
                    console.log($(newElement).get(0));

                    // Determine which element to update based on serviceType
                    switch (data.serviceType) {
                        case '1':
                            // Handle GOTOGO Post Speed specific update
                            $('.active-inactive-menu-1').prev().replaceWith(newElement);
                            break;
                        case '3':
                            // Handle GOTOGO Post SuperFast specific update
                            $('.active-inactive-menu-3').prev().replaceWith(newElement);
                            break;
                        case '4':
                            // Handle GOTOGO Post Business Parcel specific update
                            $('.active-inactive-menu-4').prev().replaceWith(newElement);
                            break;
                        case '5':
                            // Handle India Post Speed specific update
                            $('.active-inactive-menu-5').prev().replaceWith(newElement);
                            break;
                        case '6':
                            // Handle India Post Business specific update
                            $('.active-inactive-menu-6').prev().replaceWith(newElement);
                            break;
                        case '7':
                            // Handle India Post Registered specific update
                            $('.active-inactive-menu-7').prev().replaceWith(newElement);
                            break;
                        case '8':
                            // Handle E2E specific update
                            $('.active-inactive-menu-8').prev().replaceWith(newElement);
                            break;
                        case '9':
                            // Handle E2H specific update
                            $('.active-inactive-menu-9').prev().replaceWith(newElement);
                            break;
                        default:
                            console.error('Unknown service type:', data.serviceType);
                            break;
                    }
                } else {
                    Swal.fire('Error!', "Something went wrong", 'error');
                }
            }

        });
    }
</script>





<script>
    $('#pincode').on('change', function() {
        var pincode = $('#pincode').val();

        $.ajax({
            type: 'GET',
            url: "<?php echo e(url('admin/city-state')); ?>" + '/' + pincode,
            success: function(data) {
                if (data.success === true) {
                    $('.customer_error').empty();
                    $('#district').empty().val(data.district);
                    $('#state').empty().val(data.state);
                } else {
                    $('.validerror').text('Please enter valid pincode');
                }
            }
        });
    });

    function displayFile(id) {
        const fileInput = document.getElementById(`fileInput${id}`);
        const fileContainer = document.getElementById(`fileContainer${id}`);

        // Clear any previous content
        fileContainer.innerHTML = "";

        // Check if a file is selected
        if (fileInput.files.length === 0) {
            fileContainer.innerHTML = "<p>No file selected.</p>";
            return;
        }
        const file = fileInput.files[0];
        const fileType = file.type;

        if (fileType === "application/pdf") {
            // Display PDF
            const reader = new FileReader();
            reader.onload = function(e) {
                const pdfData = e.target.result;
                const embed = document.createElement("embed");
                embed.setAttribute("src", pdfData);
                embed.setAttribute("type", "application/pdf");
                embed.style.width = "285px";
                embed.style.height = "130px";
                fileContainer.appendChild(embed);
            };
            reader.readAsDataURL(file);
        } else if (fileType.startsWith("image/")) {
            // Display image
            const img = document.createElement("img");
            img.setAttribute("src", URL.createObjectURL(file));
            img.style.width = "100%";
            img.style.height = "100%";
            fileContainer.appendChild(img);
        } else {
            fileContainer.innerHTML = "<p>Unsupported file type.</p>";
        }
    }

    function forceUpper(strInput) {
        strInput.value = strInput.value.toUpperCase();
    }

    var bankIfsc = $('#ifsc_code');
    var bankIfscError = $('#bank_ifsc_error');
    var bankName = $('#bank_name');
    var bankBranch = $('#branch_name');

    bankIfsc.on('input', function() {
        var ifscCode = bankIfsc.val();
        if (ifscCode.length === 11) {
            $.ajax({
                'url': 'https://ifsc.razorpay.com/' + ifscCode,
                'success': function(res, status, xhr) {
                    if (xhr.status === 200) {
                        bankName.val(res.BANK);
                        bankBranch.val(res.BRANCH);
                        bankIfscError.html('');

                    }
                },
                'error': function(xhr, status, error) {
                    if (xhr.status === 404) {
                        bankIfscError.siblings('.backendError').remove();
                        bankIfscError.html('Please enter a valid IFSC Code');
                    }
                }
            })
        } else {
            bankName.val('');
            bankBranch.val('');
            bankIfscError.html('');
        }
    });
</script>









<script>
    function filterDailyBookingsReportByDate() {
        var date = $('.bookingdate').val();

        console.log("reached here", )
        // Construct the URL with query parameters
        var url = "<?php echo e(route('admin.franchise.view', ['id' => $franchise->id])); ?>";
        url += "?date=" + encodeURIComponent(date) + "&type=bookings";

        $.ajax({
            url: url,
            type: "GET", // Use GET request
            success: function(response) {
                if (response.status == 200) {
                    var data = response.data;


                    // Clear existing table rows
                    $('#franchise_daily_booking_report tbody').empty();

                    // Iterate over the data and create rows

                    $.each(data, function(index, item) {
                        var row = $('<tr>');

                        row.append($('<td>').text(index)); // Adding serial number
                        row.append($('<td>').text(item.service_type));
                        row.append($('<td>').text(item.No_of_article));
                        row.append($('<td>').text(item.total_value));
                        row.append($('<td>').text(item.wallet_balance));
                        row.append($('<td>').text(item.remaining_balance));
                        // Append the row to the table
                        $('#franchise_daily_booking_report tbody').append(row);
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', "Something went wrong", 'error');
            }
        });
    }
</script>


<script>
    function filterParcelsByDate() {
        var date = $('.date').val();

        // Construct the URL with query parameters
        var url = "<?php echo e(route('admin.franchise.view', ['id' => $franchise->id])); ?>";
        url += "?date=" + encodeURIComponent(date) + "&type=parcel";


        $.ajax({
            url: url,
            type: "GET", // Use GET request
            success: function(response) {
                if (response.status == 200) {
                    var data = response.html;

                    $('#parcel tbody').empty();

                    $('#parcel tbody').html(data);

                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', "Something went wrong", 'error');
            }
        });
    }
</script>


<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/franchise/view.blade.php ENDPATH**/ ?>