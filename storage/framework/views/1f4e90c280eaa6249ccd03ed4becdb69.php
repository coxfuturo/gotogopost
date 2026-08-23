<?php $__env->startSection('title'); ?> Profile Management <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<style>
    .profile-view .profile-img-wrap img {
        border-radius: 50%;
        height: 120px;
        width: 120px;
        object-fit: cover;
    }

    .personal-info li .text {
        color: #bb2828;
        display: block;
        overflow: hidden;
        width: 70%;
        float: left;
    }


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
</style>

<style>
    .account-page .main-wrapper .account-content .account-box {
        background-color: #ffffff;

        border: 1px solid #ededed;

        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);

        margin: 0 auto;

        overflow: hidden;

        width: 100%;

        border-radius: 4px;
    }

    .account-page .main-wrapper .account-content .account-logo img {
        width: 140px !important;
    }

    #startRecordingButtonUpper {
        position: absolute;
        bottom: 10px;
        display: flex;
        justify-content: center;
    }

    #startRecordingButton {
        cursor: pointer;
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
                                    <h5 class="">Name : <?php echo e($franchise->name); ?></h5>
                                    <h5 class="">Father Name : <?php echo e($franchise->father_name); ?></h5>
                                    <div class="staff-id">ID : <?php echo e($franchise->generated_id); ?></div>
                                    <div class="staff-id">FO NO : <?php echo e($franchise->franchise_no); ?></div>
                                    <div class="doj ">Date of registration : <?php echo e(date('d M Y',strtotime($franchise->created_at))); ?></div>
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
            <!-- <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item"><a href="#emp_profile" data-bs-toggle="tab" class="nav-link active">Profile</a></li>
                <li class="nav-item"><a href="#parcel" data-bs-toggle="tab" class="nav-link">Parcels</a></li>
                <li class="nav-item"><a href="#franchise_daily_booking_report" data-bs-toggle="tab" class="nav-link">Daily Booking Report</a></li>
            </ul> -->
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
                                <div class="text"><?php echo e($franchise->managerkyc->bank_name); ?></div>
                            </li>
                            <li>
                                <div class="title">Branch name</div>
                                <div class="text"><?php echo e($franchise->managerkyc->branch_name); ?></div>
                            </li>
                            <li>
                                <div class="title">Bank account No.</div>
                                <div class="text"><?php echo e($franchise->managerkyc->account_number); ?></div>
                            </li>
                            <li>
                                <div class="title">IFSC Code</div>
                                <div class="text"><?php echo e($franchise->managerkyc->ifsc_code); ?></div>
                            </li>
                            <li>
                                <div class="title">PAN No</div>
                                <div class="text"><?php echo e($franchise->managerkyc->pan_card); ?></div>
                            </li>
                            <li>
                                <div class="title">Adhar No</div>
                                <div class="text"><?php echo e($franchise->managerkyc->adhar_card); ?></div>
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
                                        <div class="document-title">Customer Image</div>
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
                                <source src="<?php echo e(asset('admin/franchise/' . $franchise->generated_id . '/' . $franchise->managerkyc->video_kyc)); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- video_kyc  Modal -->


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
                <form id="franchiseForm" method="post" action="<?php echo e(route('customer.profile.update',['id' => $franchise->id])); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-img-wrap edit-img">
                                <div id="fileContainer1">
                                    <img style="object-fit: cover;" class="inline-block" src="<?php echo e(isset($franchise->kyc->photo) ? asset('admin/franchise/'.$franchise->generated_id.'/'.$franchise->kyc->photo) : asset('admin/assets/img/profiles/avatar-02.jpg')); ?>">
                                </div>
                                <div class="fileupload btn">
                                    <span class="btn-text">Edit</span>
                                    <input class="upload" id="fileInput1" name="photo" onchange="displayFile(1)" type="file">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo e($franchise->name); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Father/Husband Name</label>
                                        <input type="text" class="form-control" name="father_name" value="<?php echo e($franchise->father_name); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="mobile" value="<?php echo e($franchise->mobile); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Email</label>
                                        <input type="text" class="form-control" name="email" value="<?php echo e($franchise->email); ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Password</label>
                                        <input type="text" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Pin Code</label>
                                        <input type="text" class="form-control NumberValidate" id="pincode" maxlength="6" name="pincode" value="<?php echo e($franchise->pincode); ?>" required>
                                        <div class="invalid-feedback validerror">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">State</label>
                                        <input type="text" class="form-control" id="state" readonly name="state" value="<?php echo e($franchise->state); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">District</label>
                                        <input type="text" class="form-control" id="district" readonly name="district" value="<?php echo e($franchise->district); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city" value="<?php echo e($franchise->city); ?>">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Address</label>
                                        <textarea name="address" class="form-control" id="address" required><?php echo e(old('address',$franchise->address)); ?></textarea>
                                        <input type="hidden" name="latitude" value="<?php echo e(old('latitude',$franchise->latitude)); ?>" id="latitude">
                                        <input type="hidden" name="longitude" value="<?php echo e(old('longitude',$franchise->longitude)); ?>" id="longitude">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12 my-4">
                            <h3 class="card-title mb-0">KYC Details</h3>
                        </div>

                        <div class="col-md-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Aadhar Number</label>
                                <input type="text" class="form-control" id="adhar_card" name="adhar_card" value="<?php echo e($franchise->managerkyc->adhar_card); ?>">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="col-form-label" for="fileInput7">Video KYC</label>
                            <input data-bs-toggle="modal" data-bs-target="#add_role_user" type="button" class="form-control" id="startCameraButton" value="Start Recording" name="video_kyc" />
                        </div>

                        <div class="col-md-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Pan Number</label>
                                <input type="text" class="form-control" id="pan_card" name="pan_card" value="<?php echo e($franchise->managerkyc->pan_card); ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">IFSC Code</label>
                                <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" value="<?php echo e($franchise->managerkyc->ifsc_code); ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Bank Name</label>
                                <input type="text" class="form-control" id="bank_name" readonly name="bank_name" value="<?php echo e($franchise->managerkyc->bank_name); ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Branch Name</label>
                                <input type="text" class="form-control" id="branch_name" readonly name="branch_name" value="<?php echo e($franchise->managerkyc->branch_name); ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Account Number</label>
                                <input type="text" class="form-control" id="account_number" name="account_number" value="<?php echo e($franchise->managerkyc->account_number); ?>">
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


<!-- open camera kyc modal -->

<div id="add_role_user" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="">
                <button type="button" id="modalCloseButton" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="">
                <div class="row" style="position: relative;">

                    <video id="video" autoplay></video>
                    <div id="startRecordingButtonUpper">
                        <div>
                            <div id="timing" style="font-size: 17px;color:white;"></div>
                            <svg id="startRecordingButton" width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" stroke="black" stroke-width="2" />
                                <circle cx="12" cy="12" r="6" fill="red" />
                            </svg>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- open camera kyc modal -->


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
        console.log("reached here", fileInput.files.length)
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
            img.style.height = "120px";
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


<script>
    let mediaRecorder;
    let stream;
    let chunks = [];
    let timingInterval;
    let startTime;

    // Start Camera
    document.getElementById('startCameraButton').addEventListener('click', async () => {
        const constraints = {
            video: true,
            audio: true
        };

        try {
            stream = await navigator.mediaDevices.getUserMedia(constraints);
            const video = document.getElementById('video');
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                video.play();
            };
        } catch (err) {
            console.error('Error accessing media devices.', err);
        }
    });

    // Start Recording
    document.getElementById('startRecordingButton').addEventListener('click', async () => {
        const constraints = {
            video: true,
            audio: true
        };

        try {
            // Check if mediaRecorder already exists
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                console.warn('Recording is already in progress.');
                return;
            }

            if (!stream) {
                console.error('Stream is not initialized.');
                return;
            }

            const options = {
                mimeType: 'video/webm; codecs=vp9'
            };
            mediaRecorder = new MediaRecorder(stream, options);

            chunks = [];
            startTime = Date.now();

            mediaRecorder.ondataavailable = function(event) {
                if (event.data.size > 0) {
                    chunks.push(event.data);
                }
            };

            mediaRecorder.onstart = function() {
                updateTiming();
                $("#startRecordingButton").css('pointer-events', 'none');
            };

            mediaRecorder.onstop = function() {
                clearInterval(timingInterval);
                const blob = new Blob(chunks, {
                    type: 'video/webm'
                });
                // Blob is available here, but you might want to handle it when form is submitted
            };

            mediaRecorder.start();

            setTimeout(() => {
                if (mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
                }
            }, 60000 * 2);

        } catch (err) {
            console.error('Error accessing media devices.', err);
        }
    });

    // Function to stop recording and streaming
    function stopRecordingAndStream() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
        }
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    }

    // Listen for modal close event
    document.getElementById('modalCloseButton').addEventListener('click', () => {
        stopRecordingAndStream();
    });


    document.getElementById('franchiseForm').addEventListener('submit', function(event) {

        event.preventDefault();

        const blob = new Blob(chunks, {
            type: 'video/webm'
        });

        const formData = new FormData(this);
        formData.append('video_kyc', blob, 'recording.webm');
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                })

                window.location.reload();

                const form = document.getElementById('franchiseForm');

                // Hide invalid styles from form controls
                const invalidControls = document.querySelectorAll('.form-control:invalid');
                invalidControls.forEach(control => {
                    control.style.borderColor = ''; // Reset border color
                    control.style.backgroundImage = "url('')"; // Remove background image
                    control.style.paddingRight = ''; // Reset padding-right
                });



                const invalidFeedbackMessages = document.querySelectorAll('.invalid-feedback');
                invalidFeedbackMessages.forEach(msg => msg.style.display = 'none');
                // Clear the video element and stop the stream
                const video = document.getElementById('video');
                if (video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                }

                // Optionally clear the chunks array
                chunks = [];
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Video upload failed:', textStatus, errorThrown);

                // Extract error message from the backend response
                let errorMessage = jqXHR.responseJSON && jqXHR.responseJSON.message ?
                    jqXHR.responseJSON.message :
                    'Something went wrong during the upload. Please try again.';

                Swal.fire({
                    title: 'Error!',
                    text: errorMessage, // Display backend error message if available
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });

    });

    function updateTiming() {
        timingInterval = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const minutes = Math.floor(elapsed / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);
            document.getElementById('timing').textContent =
                `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }, 1000);
    }
</script>


<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('market.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/market/auth/profile.blade.php ENDPATH**/ ?>