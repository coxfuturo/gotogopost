</html><!DOCTYPE html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="description" content="Smarthr - Bootstrap Admin Template" />

    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects" />

    <meta name="author" content="Dreamguys - Bootstrap Admin Template" />

    <title>Delivery Boy|Register</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <!-- Favicon -->

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('admin/assets/img/favicon.png')); ?>" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/bootstrap.min.css')); ?>" />

    <!-- Fontawesome CSS -->

    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/fontawesome/css/fontawesome.min.css')); ?>" />

    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/fontawesome/css/all.min.css')); ?>" />

    <!-- Lineawesome CSS -->

    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/line-awesome.min.css')); ?>" />

    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/material.css')); ?>" />

    <!-- Main CSS -->

    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/style.css')); ?>" />


</head>

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

    .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        width: 100% !important;
    }

    .select2-selection__clear {
        display: none;
    }

    /* Overlay to cover the whole viewport with a semi-transparent background */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        /* Semi-transparent black */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        /* Higher z-index to overlay the entire page */
    }

    .hidden {
        display: none;
    }

    /* Loader animation styles */
    .loader {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        position: relative;
        animation: rotate 1s linear infinite;
    }

    .loader::before,
    .loader::after {
        content: "";
        box-sizing: border-box;
        position: absolute;
        inset: 0px;
        border-radius: 50%;
        border: 5px solid #FFF;
        animation: prixClipFix 2s linear infinite;
    }

    .loader::after {
        border-color: #FF3D00;
        animation: prixClipFix 2s linear infinite, rotate 0.5s linear infinite reverse;
        inset: 6px;
    }

    /* Keyframes for loader rotation */
    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes prixClipFix {
        0% {
            clip-path: polygon(50% 50%, 0 0, 0 0, 0 0, 0 0, 0 0);
        }

        25% {
            clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 0, 100% 0, 100% 0);
        }

        50% {
            clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 100% 100%, 100% 100%);
        }

        75% {
            clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 100%);
        }

        100% {
            clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 0);
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 0px !important;
        }

        .account-page .main-wrapper .account-content .account-box .account-wrapper {
            padding: 30px 10px !important;
        }
    }

    .form-check-input[type=checkbox] {
    border-radius: .25em;
    border: 2px solid #000;
}
</style>

<body class="account-page">
    <!-- Main Wrapper -->

    <div class="main-wrapper">
        <div class="account-content">
            <div class="container">
                <!-- Account Logo -->

                <div class="account-logo">
                    <a href="#"><img src="<?php echo e(asset('website/images/logonewtransparent.png')); ?>" alt="IndiaPost" /></a>
                </div>

                <!-- /Account Logo -->

                <div class="account-box">
                    <div class="account-wrapper">
                        <h3 class="account-title mb-3">Pickup/Delivery Boy Register Form</h3>

                        <!-- Row -->

                        <div class="row">
                            <div class="col-sm-12">
                                <!-- Custom Boostrap Validation -->

                                <div class="card">
                                    <form id="franchiseForm" action="<?php echo e(route('deliveryBoy.register')); ?>" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                                        <?php echo csrf_field(); ?>

                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Basic Details</h5>
                                        </div>

                                        <div class="card-body">
                                            <?php echo $__env->make('admin.layouts.error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="name">Name <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-user"></i></span>

                                                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" placeholder="Name" value="<?php echo e(old('name')); ?>" required />

                                                                <div class="invalid-feedback">
                                                                    Please provide your name
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-4 mb-3">
                                                            <label for="father_name">Father/Husband Name <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-user"></i></span>

                                                                <input type="text" class="form-control <?php $__errorArgs = ['father_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="father_name" name="father_name" value="<?php echo e(old('father_name')); ?>" placeholder="Father/Husband Name" required />

                                                                <div class="invalid-feedback">
                                                                    Please provide father/husband name
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="mobile">Mobile <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                <input type="text" class="form-control NumberValidate <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="10" id="mobile" name="mobile" value="<?php echo e(old('mobile')); ?>" placeholder="Mobile." required />

                                                                <div class="invalid-feedback">
                                                                    Please provide mobile number
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="email">Email <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" placeholder="Email ID." value="<?php echo e(old('email')); ?>" required />

                                                                <div class="invalid-feedback">
                                                                    Please provide emailId
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="pincode">Pincode <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                <input type="text" class="form-control NumberValidate <?php $__errorArgs = ['pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="6" id="pincode" name="pincode" value="<?php echo e(old('pincode')); ?>" placeholder="Pincode." required />

                                                                <div class="invalid-feedback">
                                                                    Please provide pincode
                                                                </div>

                                                                <div class="invalid-feedback validerror"></div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="city">City <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                <input type="text" class="form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="city" name="city" placeholder="City." value="<?php echo e(old('city')); ?>" required />

                                                                <div class="invalid-feedback">
                                                                    Please provide city
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="district">District <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                <input type="text" readonly class="form-control <?php $__errorArgs = ['district'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="district" name="district" value="<?php echo e(old('district')); ?>" required />

                                                                <div class="invalid-feedback">
                                                                    Please provide district
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="state">State <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                                                <input type="text" readonly class="form-control <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="state" name="state" value="<?php echo e(old('state')); ?>" required />

                                                                <div class="invalid-feedback">
                                                                    Please provide state
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="address">Address <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="address" name="address" placeholder="Type address..." required><?php echo e(old('address')); ?></textarea>

                                                                <input type="hidden" name="latitude" value="<?php echo e(old('latitude')); ?>" id="latitude" />

                                                                <input type="hidden" name="longitude" value="<?php echo e(old('longitude')); ?>" id="longitude" />

                                                                <div class="invalid-feedback">
                                                                    Please provide address
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-4 mb-3">
                                                            <label for="generated_id">Generated ID</label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                                                <input type="text" class="form-control <?php $__errorArgs = ['generated_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="generated_id" readonly value="<?php echo e(old('generated_id')); ?>" name="generated_id" placeholder="Auto generated" required />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="adhar_card">Aadhar Number</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                                <input type="text" class="form-control NumberValidate <?php $__errorArgs = ['adhar_card'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="12" id="adhar_card" name="adhar_card" value="<?php echo e(old('adhar_card')); ?>" placeholder="Adhar Card Number..">

                                                            </div>
                                                        </div>
                                                       <div class="col-md-4 mb-3">
    <label for="generated_id">Select Your Franchise State</label>
    <div class="input-group">
        <select class="select-my" id="franchise_state" name="franchise_state" required>
            <option selected disabled>-- Select Your Franchise State --</option>
           <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
    <option value="Andhra Pradesh">Andhra Pradesh</option>
    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
    <option value="Assam">Assam</option>
    <option value="Bihar">Bihar</option>
    <option value="Chandigarh">Chandigarh</option>
    <option value="Chhattisgarh">Chhattisgarh</option>
    <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
    <option value="Delhi">Delhi</option>
    <option value="Goa">Goa</option>
    <option value="Gujarat">Gujarat</option>
    <option value="Haryana">Haryana</option>
    <option value="Himachal Pradesh">Himachal Pradesh</option>
    <option value="Jammu and Kashmir">Jammu and Kashmir</option>
    <option value="Jharkhand">Jharkhand</option>
    <option value="Karnataka">Karnataka</option>
    <option value="Kerala">Kerala</option>
    <option value="Ladakh">Ladakh</option>
    <option value="Lakshadweep">Lakshadweep</option>
    <option value="Madhya Pradesh">Madhya Pradesh</option>
    <option value="Maharashtra">Maharashtra</option>
    <option value="Manipur">Manipur</option>
    <option value="Meghalaya">Meghalaya</option>
    <option value="Mizoram">Mizoram</option>
    <option value="Nagaland">Nagaland</option>
    <option value="Odisha">Odisha</option>
    <option value="Puducherry">Puducherry</option>
    <option value="Punjab">Punjab</option>
    <option value="Rajasthan">Rajasthan</option>
    <option value="Sikkim">Sikkim</option>
    <option value="Tamil Nadu">Tamil Nadu</option>
    <option value="Telangana">Telangana</option>
    <option value="Tripura">Tripura</option>
    <option value="Uttar Pradesh">Uttar Pradesh</option>
    <option value="Uttarakhand">Uttarakhand</option>
    <option value="West Bengal">West Bengal</option>
        </select>
    </div>
</div>



                                                        <div class="col-md-4 mb-3">
                                                            <label for="generated_id">Select Nearest to your franchise/Hub</label>
                                                            <div class="input-group">
                                                                <select class="select-my franchise_res" id="status" name="franchise_id" required>
                                                                    <?php $__currentLoopData = $franchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($data->id); ?>">
                                                                        <?php echo e($data->name); ?>

                                                                    </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="age">AGE <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                <input type="text" class="form-control NumberValidate <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="10" id="age" name="age" value="<?php echo e(old('age')); ?>" placeholder="age." required />

                                                                <div class="invalid-feedback">
                                                                    Please provide age
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="generated_id">Gender</label>
                                                            <div class="input-group">
                                                                <select class="select-my" <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="gender" name="gender" required>
                                                                    <option value="">Select Gender</option>
                                                                    <option value="Male" <?php echo e(old('gender') == 'Male' ? 'selected' : ''); ?>>Male</option>
                                                                    <option value="Female" <?php echo e(old('gender') == 'Female' ? 'selected' : ''); ?>>Female</option>
                                                                    <option value="Other" <?php echo e(old('gender') == 'Other' ? 'selected' : ''); ?>>Other</option>
                                                                </select>

                                                                <div class="invalid-feedback">
                                                                    Please select gender
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Upload Documents (<small class="text-danger">Image size upto 1 MB</small>)</h5>
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="fileInput5">Photo</label>

                                                            <input type="file" class="form-control" id="fileInput5" onchange="displayFile(5)" name="photo" />

                                                            <div id="fileContainer5"></div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="fileInput1">Adhar Front Image</label>

                                                            <input type="file" class="form-control" id="fileInput1" onchange="displayFile(1)" name="adhar_front_img" />

                                                            <div id="fileContainer1"></div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="fileInput2">Adhar Back Image</label>
                                                            <input type="file" class="form-control" id="fileInput2" onchange="displayFile(2)" name="adhar_back_img" />
                                                            <div id="fileContainer2"></div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="fileInput2">Drivary Lances Image</label>
                                                            <input type="file" class="form-control" id="fileInput3" onchange="displayFile(3)" name="drivary_lances_img" />
                                                            <div id="fileContainer3"></div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="fileInput2">Pan Image</label>
                                                            <input type="file" class="form-control" id="fileInput4" onchange="displayFile(4)" name="pan_img" />
                                                            <div id="fileContainer4"></div>
                                                        </div>
                                                        
                                                        <div class="col-md-4 mb-3">
                                                            <label for="fileInput7">Video KYC</label>
                                                            <input data-bs-toggle="modal" data-bs-target="#add_role_user" type="button" class="form-control" id="startCameraButton" value="Start Recording" name="video_kycs" />
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm">

                                                    <div class="input-block mb-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required="">
                                                            <label class="form-check-label" for="invalidCheck">

                                                                <a href="<?php echo e(route('website.privacy-policy')); ?>" style="color:black">Agree to terms and conditions</a>


                                                                <a href="<?php echo e(route('website.privacy-policy')); ?>" target="_blank" class="ms-1" title="Read Terms">
                                                                    <i class="la la-info-circle" style="font-size: 1.2rem; color: #0d6efd;"></i>
                                                                </a>
                                                            </label>
                                                            <div class="invalid-feedback">
                                                                You must agree before submitting.
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="text-center">
                                                        <button class="btn btn-primary" type="submit">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- /Custom Boostrap Validation -->
                            </div>
                        </div>


                        <!-- /Row -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
            #videoOverlayText {
                position: absolute;
                top: -14%;
                left: 50%;
                transform: translateX(-50%);
                color: #f0f0f0;
                font-size: 17px;
                font-weight: 600;
                width: 96%;
                text-align: center;
                pointer-events: none;
                user-select: none;
                padding: 5px;
                background: #ff0000;
            }
        </style>
        <!-- video kyc moal -->

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
                            <div id="videoOverlayText">
                                PLEASE CLEARLY SAY YOUR FULL NAME, AADHAR NUMBER, PAN CARD NUMBER, AND GST NUMBER ON CAMERA.<br />
                                कृपया कैमरे के सामने स्पष्ट रूप से अपना पूरा नाम, आधार नंबर, पैन नंबर और जीएसटी नंबर बोलें।
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-end">
                                <div id="startRecordingButtonUpper">
                                    <div>
                                        <div id="timing" style="font-size: 17px; color: white;"></div>
                                        <svg id="startRecordingButton" width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="12" r="10" stroke="black" stroke-width="2" />
                                            <circle cx="12" cy="12" r="6" fill="red" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="col-5">
                                <button id="restartRecordingButton" class="btn btn-warning mt-2" style="color: black; font-weight: bold; width: 30%; margin-left: 5%; margin-bottom: 5%;"><i class="fa fa-refresh"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- video kyc moal -->
    <!-- /Main Wrapper -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>

    <?php if(Session::has('success')): ?>

    <script>
        Swal.fire("Yay!!!", "<?php echo e(Session::get('success')); ?>", "success");
    </script>

    <?php endif; ?> <?php if(Session::has('error')): ?>

    <script>
        Swal.fire("Oops!!!", "<?php echo e(Session::get('error')); ?>", "error");
    </script>

    <?php endif; ?>

    <!-- jQuery -->

    <script src="<?php echo e(asset('admin/assets/js/jquery-3.7.0.min.js')); ?>"></script>

    <!-- Bootstrap Core JS -->

    <script src="<?php echo e(asset('admin/assets/js/bootstrap.bundle.min.js')); ?>"></script>

    <!-- Custom JS -->

    <!-- Slimscroll JS -->
    <script src="<?php echo e(asset('admin/assets/js/jquery.slimscroll.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/select2.min.js')); ?>"></script>

    <script src="<?php echo e(asset('admin/assets/js/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/bootstrap-datetimepicker.min.js')); ?>"></script>

    <script src="<?php echo e(asset('admin/assets/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/dataTables.bootstrap4.min.js')); ?>"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
    <!-- Tagsinput JS -->


    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="<?php echo e(asset('admin/assets/js/app.js')); ?>"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Include SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script src="<?php echo e(asset('franchise/js/custom.js')); ?>"></script>
    <script>
            document.getElementById("restartRecordingButton").addEventListener("click", async () => {
                if (mediaRecorder && mediaRecorder.state === "recording") {
                    mediaRecorder.stop();
                }

                if (stream) {
                    stream.getTracks().forEach((track) => track.stop());
                }

                // Clear previous chunks
                chunks = [];

                // Restart camera stream
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                    const video = document.getElementById("video");
                    video.srcObject = stream;
                    video.onloadedmetadata = () => {
                        video.play();
                    };

                    // Start recording again
                    const options = { mimeType: "video/webm; codecs=vp9" };
                    mediaRecorder = new MediaRecorder(stream, options);

                    mediaRecorder.ondataavailable = function (event) {
                        if (event.data.size > 0) {
                            chunks.push(event.data);
                        }
                    };

                    mediaRecorder.onstart = function () {
                        updateTiming();
                        $("#startRecordingButton").css("pointer-events", "none");
                    };

                    mediaRecorder.onstop = function () {
                        clearInterval(timingInterval);
                    };

                    mediaRecorder.start();
                    startTime = Date.now();

                    setTimeout(() => {
                        if (mediaRecorder && mediaRecorder.state === "recording") {
                            mediaRecorder.stop();

                            Swal.fire({
                                icon: "success",
                                title: "KYC Instructions",
                                text: "Your video duration was 40 seconds. Make sure to speak your full Name, Aadhar, PAN, and SGST clearly. If there was any mistake, please restart and record again.",
                            });
                        }
                    }, 41000); // Runs after 41 seconds
                } catch (err) {
                    console.error("Error restarting recording:", err);
                    Swal.fire({
                        icon: "error",
                        title: "Restart Error",
                        text: "Could not restart recording. Please check your permissions.",
                    });
                }
            });
        </script>
    <script>
        $("#pincode").on("change", function() {
            var pincode = $("#pincode").val();

            var name = $("#name").val();

            console.log(name)
            $.ajax({
                type: "GET",

                url: "<?php echo e(url('admin/city-state')); ?>/" + pincode + "?type=deliveryBoy&name=" + encodeURIComponent(name),

                success: function(data) {
                    if (data.success === true) {
                        $(".customer_error").empty();

                        $("#district").empty().val(data.district);

                        $("#state").empty().val(data.state);

                        $("#generated_id").empty().val(data.generated_id);
                    } else {
                        $(".validerror").text("Please enter valid pincode");
                    }
                },
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

        var bankIfsc = $("#ifsc_code");

        var bankIfscError = $("#bank_ifsc_error");

        var bankName = $("#bank_name");

        var bankBranch = $("#branch_name");

        bankIfsc.on("input", function() {
            var ifscCode = bankIfsc.val();

            if (ifscCode.length === 11) {
                $.ajax({
                    url: "https://ifsc.razorpay.com/" + ifscCode,

                    success: function(res, status, xhr) {
                        if (xhr.status === 200) {
                            bankName.val(res.BANK);

                            bankBranch.val(res.BRANCH);

                            bankIfscError.html("");
                        }
                    },

                    error: function(xhr, status, error) {
                        if (xhr.status === 404) {
                            bankIfscError.siblings(".backendError").remove();

                            bankIfscError.html("Please enter a valid IFSC Code");
                        }
                    },
                });
            } else {
                bankName.val("");

                bankBranch.val("");

                bankIfscError.html("");
            }
        });
    </script>

    <script>
    let mediaRecorder;
    let stream;
    let chunks = [];
    let timingInterval;
    let startTime;

    // Start Camera with lower resolution for faster upload
    document.getElementById('startCameraButton').addEventListener('click', async () => {
        const constraints = {
            video: {
                width: { ideal: 640 },
                height: { ideal: 360 },
                frameRate: { ideal: 15 }
            },
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

    // Start Recording with vp8 for better compression
    document.getElementById('startRecordingButton').addEventListener('click', async () => {
        try {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                console.warn('Recording is already in progress.');
                return;
            }

            if (!stream) {
                console.error('Stream is not initialized.');
                return;
            }

            const options = {
                mimeType: 'video/webm; codecs=vp8'
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
            };

            mediaRecorder.start();

            setTimeout(() => {
                if (mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
                    Swal.fire({
                        icon: "success",
                        title: "KYC Instructions",
                        text: "Your video duration was 40 seconds. Make sure to speak your full Name, Aadhar, PAN, and GST clearly. If there was any mistake, please restart and record again.",
                    }).then(() => {
                        $("#add_role_user").modal("hide");
                        const videoElement = document.getElementById("video");
                        if (videoElement && videoElement.srcObject) {
                            videoElement.srcObject.getTracks().forEach((track) => track.stop());
                            videoElement.srcObject = null;
                        }
                    });
                }
            }, 41000);

        } catch (err) {
            console.error('Error accessing media devices.', err);
        }
    });

    // Stop media on modal close
    document.getElementById('modalCloseButton').addEventListener('click', () => {
        stopRecordingAndStream();
    });

    function stopRecordingAndStream() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
        }
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    }

    // Update recording timer
    function updateTiming() {
        timingInterval = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const minutes = Math.floor(elapsed / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);
            document.getElementById('timing').textContent =
                `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }, 1000);
    }

    // Submit with fast compressed video
    document.getElementById('franchiseForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const blob = new Blob(chunks, {
            type: 'video/webm'
        });

        if (!$('#invalidCheck').is(':checked')) {
            return;
        }

        const cphValue = document.querySelector('select[name="franchise_id"]').value;
    if (!cphValue) {
        Swal.fire({
            icon: "warning",
            title: "Franchise Not Found",
            text: "Please Select Nearest To Your Franchise/Hub",
        });
        return;
    }

        const formData = new FormData(this);
        formData.append('video_kyc', blob, 'recording.webm');

        Swal.fire({
            title: "Uploading...",
            text: "Please wait while we upload your Details.",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.close();
                window.location.href = "<?php echo e(route('deliveryBoy.otpViewPage', ['mobile' => '__MOBILE__'])); ?>".replace('__MOBILE__', response.data.mobile);

                const invalidControls = document.querySelectorAll('.form-control:invalid');
                invalidControls.forEach(control => {
                    control.style.borderColor = '';
                    control.style.backgroundImage = "url('')";
                    control.style.paddingRight = '';
                });

                const invalidFeedbackMessages = document.querySelectorAll('.invalid-feedback');
                invalidFeedbackMessages.forEach(msg => msg.style.display = 'none');

                const video = document.getElementById('video');
                if (video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                }

                chunks = [];
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                let errorMessage = jqXHR.responseJSON && jqXHR.responseJSON.message ?
                    jqXHR.responseJSON.message :
                    'Something went wrong during the upload. Please try again.';

                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });



     $("#franchise_state").on("change", function() {
            var state = $(this).val();
            $.ajax({
                type: "GET",
                data: {state:state, _token: "<?php echo e(csrf_token()); ?>"},
                url: "<?php echo e(route('deliveryBoy.franchise_state')); ?>",

                success: function(data) {
                   
                        $('.franchise_res').html(data.html);
           
                },
            });
        });
</script>

</body>

</html><?php /**PATH /home/gotogopost/public_html/resources/views/deliveryBoy/auth/register.blade.php ENDPATH**/ ?>