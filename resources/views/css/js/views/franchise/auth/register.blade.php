<!DOCTYPE html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
    <head>
        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <meta name="description" content="Smarthr - Bootstrap Admin Template" />

        <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects" />

        <meta name="author" content="Dreamguys - Bootstrap Admin Template" />

        <title>Franchise|Register</title>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet" type="text/css" />

        <!-- Favicon -->

        <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin/assets/img/favicon.png')}}" />

        <!-- Bootstrap CSS -->

        <link rel="stylesheet" href="{{asset('admin/assets/css/bootstrap.min.css')}}" />

        <!-- Fontawesome CSS -->

        <link rel="stylesheet" href="{{asset('admin/assets/plugins/fontawesome/css/fontawesome.min.css')}}" />

        <link rel="stylesheet" href="{{asset('admin/assets/plugins/fontawesome/css/all.min.css')}}" />

        <!-- Lineawesome CSS -->

        <link rel="stylesheet" href="{{asset('admin/assets/css/line-awesome.min.css')}}" />

        <link rel="stylesheet" href="{{asset('admin/assets/css/material.css')}}" />

        <!-- Main CSS -->

        <link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}" />
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
            bottom: 5px;
            display: flex;
            justify-content: center;
        }

        #startRecordingButton {
            cursor: pointer;
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
            border: 5px solid #fff;
            animation: prixClipFix 2s linear infinite;
        }

        .loader::after {
            border-color: #ff3d00;
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
            .mobilemarginbottom{
                margin-bottom: 15px !important;
            }
        }

        .custom-modal .modal-content .btn-close {
            top: 25px !important;
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
                        <a href="#"><img src="{{asset('website/images/logonewtransparent.png')}}" alt="IndiaPost" /></a>
                    </div>

                    <!-- /Account Logo -->

                    <div class="account-box">
                        <div class="account-wrapper">
                            <h3 class="account-title mb-3">Franchise Register Form</h3>

                            <!-- Row -->

                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- Custom Boostrap Validation -->

                                    <div class="card">
                                        {{-- ==============loader================= --}}
                                        <div id="overlay" class="overlay hidden">
                                            <div class="loader"></div>
                                        </div>
                                        {{-- ==============loader================= --}}

                                        <form id="franchiseForm" action="{{route('franchise.register')}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                                            @csrf

                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Basic Details</h5>
                                            </div>

                                            <div class="card-body">
                                                @include('admin.layouts.error')

                                                <div class="row">
                                                    <div class="col-sm">
                                                        <div class="row">
                                                            <div class="col-sm-4 mobilemarginbottom" >
                                                                <label for="mobile"> Partner Type <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <select class="form-select type" name="register_type">
                                                                        <option value="proprietor">Proprietorship Firm</option>
                                                                        <option value="partner">Partnership Firm</option>
                                                                        <option value="private">Private Limited</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="father_name">Father/Husband Name <span class="text-danger">*</span></label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-user"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control @error('father_name') is-invalid @enderror"
                                                                        id="father_name"
                                                                        name="father_name"
                                                                        value="{{old('father_name')}}"
                                                                        placeholder="Father/Husband Name"
                                                                        required
                                                                    />

                                                                    <div class="invalid-feedback">
                                                                        Please provide Father/Husband Name
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="mobile">Mobile <span class="text-danger">*</span></label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control NumberValidate @error('mobile') is-invalid @enderror"
                                                                        maxlength="10"
                                                                        id="mobile"
                                                                        name="mobile"
                                                                        value="{{old('mobile')}}"
                                                                        placeholder="Mobile."
                                                                        required
                                                                    />

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

                                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email ID." value="{{old('email')}}" required />

                                                                    <div class="invalid-feedback">
                                                                        Please provide emailId
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="pincode">Pincode <span class="text-danger">*</span></label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control NumberValidate @error('pincode') is-invalid @enderror"
                                                                        maxlength="6"
                                                                        id="pincode"
                                                                        name="pincode"
                                                                        value="{{old('pincode')}}"
                                                                        placeholder="Pincode."
                                                                        required
                                                                    />

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

                                                                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="City." value="{{old('city')}}" required />

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

                                                                    <input type="text" readonly class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{old('district')}}" required />

                                                                    <div class="invalid-feedback">
                                                                        Please provide district
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="state">State <span class="text-danger">*</span></label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                                                    <input type="text" readonly class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{old('state')}}" required />

                                                                    <div class="invalid-feedback">
                                                                        Please provide state
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="address">Residential/Office Address <span class="text-danger">*</span></label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Type address..." required>{{old('address')}}</textarea>

                                                                    <input type="hidden" name="latitude" value="{{old('latitude')}}" id="latitude" />

                                                                    <input type="hidden" name="longitude" value="{{old('longitude')}}" id="longitude" />

                                                                    <div class="invalid-feedback">
                                                                        Please provide address
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                             <div class="col-md-4 mb-3">
                                                                <label for="sector">Nationality </label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                                    <select class="form-select @error('natality') is-invalid @enderror" name="natality" required>
                                                                        <option selected value="india">India</option>
                                                                       
                                                                    </select>

                                                                    <div class="invalid-feedback">
                                                                        Please provide Nationality
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="society_name">Age <span class="text-danger">*</span></label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                    <input
                                                                        type="number"
                                                                        class="form-control @error('age') is-invalid @enderror"
                                                                        id="age"
                                                                        name="age"
                                                                        value="{{old('age')}}"
                                                                        placeholder="Age"
                                                                        required
                                                                    />

                                                                    <div class="invalid-feedback">
                                                                        Please provide Age
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="sector">Gender</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender" required>
                                                                        <option selected disabled>Select Option</option>
                                                                        <option value="male">Male</option>
                                                                        <option value="female">Female</option>
                                                                        <option value="other">Other</option>
                                                                    </select>

                                                                    <div class="invalid-feedback">
                                                                        Please provide Gender
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="society_name">Firm/Company Name <span class="text-danger">*</span></label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control @error('society_name') is-invalid @enderror"
                                                                        id="society_name"
                                                                        name="society_name"
                                                                        value="{{old('society_name')}}"
                                                                        placeholder="Firm/Company"
                                                                        required
                                                                    />

                                                                    <div class="invalid-feedback">
                                                                        Please provide society/company name
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 mb-3">
                                                                <label for="sector">Sector/Street No.</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control @error('sector') is-invalid @enderror"
                                                                        id="sector"
                                                                        name="sector"
                                                                        value="{{old('sector')}}"
                                                                        placeholder="Sector/Street No."
                                                                        required
                                                                    />

                                                                    <div class="invalid-feedback">
                                                                        Please provide sector/street no
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="generated_id">Generated ID</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control @error('generated_id') is-invalid @enderror"
                                                                        id="generated_id"
                                                                        readonly
                                                                        value="{{old('generated_id')}}"
                                                                        name="generated_id"
                                                                        placeholder="Auto generated"
                                                                        required
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <h5 class="card-title mb-0">Upload Documents (<small class="text-danger">Image size upto 1 MB</small>)</h5>
                                                    </div>
                                                    <div class="col-sm-4 text-end secound_names" style="display: none;">
                                                        <button type="button" class="btn btn-dark" id="add-more-btn">Add More</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body" id="document-section">
                                                <div class="row mt-4" id="document-row-1">
                                                    <div class="col-sm">
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput1">Full Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="name[]" placeholder="Enter full name" />
                                                                <div id="fileContainer1"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput1">Adhar Front Image</label>
                                                                <input type="file" class="form-control" id="fileInput1" name="adhar_front_img[]" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer1"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput2">Adhar Back Image</label>
                                                                <input type="file" class="form-control" id="fileInput2" name="adhar_back_img[]" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer2"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput3">Pan Card Image</label>
                                                                <input type="file" class="form-control" id="fileInput3" name="pan_img[]" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer3"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput4">Cancel Cheque Image</label>
                                                                <input type="file" class="form-control" id="fileInput4" name="cheque_img[]" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer4"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput5">Photo</label>
                                                                <input type="file" class="form-control" id="fileInput5" name="photo[]" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer5"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput6">Other Document</label>
                                                                <input type="file" class="form-control" id="fileInput6" name="other_document[]" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer6"></div>
                                                            </div>

                                                            <!-- Remove button added to each row -->
                                                            <div class="col-4 mb-3">
                                                                <button type="button" class="btn btn-danger remove-btn" style="margin-top: 30px; display: none;">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-header">
                                                <h5 class="card-title mb-0">KYC Details</h5>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm">
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="adhar_card">Aadhar Number</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-user"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control NumberValidate @error('adhar_card') is-invalid @enderror"
                                                                        maxlength="12"
                                                                        id="adhar_card"
                                                                        name="adhar_card"
                                                                        value="{{old('adhar_card')}}"
                                                                        placeholder="Adhar Card Number.."
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="pan_card">Pan Number</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-user"></i></span>

                                                                    <input type="text" class="form-control @error('pan_card') is-invalid @enderror" id="pan_card" name="pan_card" value="{{old('pan_card')}}" placeholder="Pan Card Number" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="ifsc_code">IFSC Code</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control @error('ifsc_code') is-invalid @enderror"
                                                                        maxlength="11"
                                                                        onkeyup="return forceUpper(this);"
                                                                        id="ifsc_code"
                                                                        name="ifsc_code"
                                                                        value="{{old('ifsc_code')}}"
                                                                        placeholder="IFSC Code"
                                                                    />

                                                                    <div class="text-danger" id="bank_ifsc_error"></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="bank_name">Bank Name</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control @error('bank_name') is-invalid @enderror"
                                                                        readonly
                                                                        id="bank_name"
                                                                        name="bank_name"
                                                                        value="{{old('bank_name')}}"
                                                                        placeholder="Auto detect after filling IFSC code"
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="branch_name">Branch Name</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-user"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control @error('branch_name') is-invalid @enderror"
                                                                        readonly
                                                                        id="branch_name"
                                                                        name="branch_name"
                                                                        value="{{old('branch_name')}}"
                                                                        placeholder="Auto detect after filling IFSC code"
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="account_no">Account Number</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-user"></i></span>

                                                                    <input
                                                                        type="text"
                                                                        class="form-control NumberValidate @error('account_number') is-invalid @enderror"
                                                                        maxlength="16"
                                                                        id="account_no"
                                                                        value="{{old('account_number')}}"
                                                                        name="account_number"
                                                                        placeholder="Account Number"
                                                                    />
                                                                </div>
                                                            </div>

                                                           

                                                            <div class="col-md-4 mb-3">
                                                                <label for="gst_number">GST Number</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-user"></i></span>

                                                                    <input type="text"
    class="form-control text-uppercase @error('gst_number') is-invalid @enderror"
                                                                        id="gst_number"
                                                                        name="gst_number"
                                                                        value="{{old('gst_number')}}"
                                                                        placeholder="Gst  Number.."
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput7">Video KYC</label>
                                                                <input data-bs-toggle="modal" data-bs-target="#add_role_user" type="button" class="form-control" id="startCameraButton" value="Start Recording" name="video_kyc" />
                                                            </div>

                                                            <div class="col-md-4 mb-3 location_col">
                                                                <label for="gst_number">Link:-Office Near By CPH</label>

                                                                <div class="input-group">
                                                                    <select name="cph_link" class="form-control location_dropdown">
                                                                        <option selected disabled>Please Select CPH</option>
                                                                    </select>
                                                                    <!-- <input  type="text" class="form-control location_dropdown"  name="cph_link" placeholder="Please Select Location" readonly/> -->
                                                                </div>
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
                                                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required="" />
                                                                <label class="form-check-label" for="invalidCheck">
                                                                    <a href="{{ route('website.agreement') }}" style="color: black;">Agreement</a>

                                                                    <a href="{{ route('website.agreement') }}" target="_blank" class="ms-1" title="Read Terms">
                                                                        <i class="la la-info-circle" style="font-size: 1.2rem; color: #0d6efd;"></i>
                                                                    </a>
                                                                </label>
                                                                <div class="invalid-feedback">
                                                                    You must agree before submitting.
                                                                </div>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required="" />
                                                                <label class="form-check-label" for="invalidCheck">
                                                                    <a href="{{ route('website.privacy-policy') }}" style="color: black;">Agree to terms and conditions</a>

                                                                    <a href="{{ route('website.privacy-policy') }}" target="_blank" class="ms-1" title="Read Terms">
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

        @if(Session::has('success'))

        <script>
            Swal.fire("Yay!!!", "{{ Session::get('success')}}", "success");
        </script>

        @endif @if(Session::has('error'))

        <script>
            Swal.fire("Oops!!!", "{{ Session::get('error')}}", "error");
        </script>

        @endif

        <!-- jQuery -->

        <script src="{{asset('admin/assets/js/jquery-3.7.0.min.js')}}"></script>

        <!-- Bootstrap Core JS -->

        <script src="{{asset('admin/assets/js/bootstrap.bundle.min.js')}}"></script>

        <!-- Custom JS -->

        <!-- Slimscroll JS -->
        <script src="{{asset('admin/assets/js/jquery.slimscroll.min.js')}}"></script>
        {{--
        <script src="{{asset('admin/assets/js/select2.min.js')}}"></script>
        --}}

        <script src="{{asset('admin/assets/js/moment.min.js')}}"></script>
        <script src="{{asset('admin/assets/js/bootstrap-datetimepicker.min.js')}}"></script>

        <script src="{{asset('admin/assets/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('admin/assets/js/dataTables.bootstrap4.min.js')}}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
        <!-- Tagsinput JS -->

        <!-- Select2 JS -->
        {{--
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        --}}

        <script src="{{asset('admin/assets/js/app.js')}}"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

        <!-- Include SweetAlert JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

        <script src="{{asset('franchise/js/custom.js')}}"></script>

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
            $("#pincode").on("change", function () {
                var pincode = $("#pincode").val();

                var name = $("#name").val();

                $.ajax({
                    type: "GET",

                    url: "{{ url('admin/city-state') }}/" + pincode + "?type=franchise",

                    success: function (data) {
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

                    reader.onload = function (e) {
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

            bankIfsc.on("input", function () {
                var ifscCode = bankIfsc.val();

                if (ifscCode.length === 11) {
                    $.ajax({
                        url: "https://ifsc.razorpay.com/" + ifscCode,

                        success: function (res, status, xhr) {
                            if (xhr.status === 200) {
                                bankName.val(res.BANK);

                                bankBranch.val(res.BRANCH);

                                bankIfscError.html("");
                            }
                        },

                        error: function (xhr, status, error) {
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

            // Start Camera
            document.getElementById("startCameraButton").addEventListener("click", async () => {
                const constraints = {
                    video: true,
                    audio: true,
                };

                try {
                    stream = await navigator.mediaDevices.getUserMedia(constraints);
                    const video = document.getElementById("video");
                    video.srcObject = stream;
                    video.onloadedmetadata = () => {
                        video.play();
                    };
                } catch (err) {
                    console.error("Error accessing media devices:", err);
                    Swal.fire({
                        icon: "error",
                        title: "Camera Access Error",
                        text: "Please ensure that your camera and microphone are properly connected and permissions are granted.",
                        confirmButtonText: "Okay",
                    });
                }
            });

            // Start Recording
            document.getElementById("startRecordingButton").addEventListener("click", async () => {
                const constraints = {
                    video: true,
                    audio: true,
                };

                try {
                    if (mediaRecorder && mediaRecorder.state === "recording") {
                        console.warn("Recording is already in progress.");
                        return;
                    }

                    if (!stream) {
                        console.error("Stream is not initialized.");
                        return;
                    }

                    const options = {
                        mimeType: "video/webm; codecs=vp9",
                    };
                    mediaRecorder = new MediaRecorder(stream, options);

                    chunks = [];
                    startTime = Date.now();

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
                        const blob = new Blob(chunks, {
                            type: "video/webm",
                        });
                        // Blob is available here, but you might want to handle it when form is submitted
                    };

                    mediaRecorder.start();

                    setTimeout(() => {
                        if (mediaRecorder.state === "recording") {
                            mediaRecorder.stop();

                            Swal.fire({
                                icon: "success",
                                title: "KYC Instructions",
                                text: "Your video duration was 40 seconds. Make sure to speak your full Name, Aadhar, PAN, and GST clearly. If there was any mistake, please restart and record again.",
                            }).then(() => {
                                // OK button pressed, now hide modal
                                $("#add_role_user").modal("hide");
                                const videoElement = document.getElementById("video");
                                if (videoElement && videoElement.srcObject) {
                                    videoElement.srcObject.getTracks().forEach((track) => track.stop());
                                    videoElement.srcObject = null;
                                }
                            });
                        }
                    }, 41000); // Stop recording after 2 minutes
                } catch (err) {
                    console.error("Error starting recording:", err);
                }
            });

            // Function to stop recording and streaming
            function stopRecordingAndStream() {
                if (mediaRecorder && mediaRecorder.state === "recording") {
                    mediaRecorder.stop();
                }
                if (stream) {
                    stream.getTracks().forEach((track) => track.stop());
                }
            }

            // Listen for modal close event
            document.getElementById("modalCloseButton").addEventListener("click", () => {
                stopRecordingAndStream();
            });

            // Form submission
           // Form submission
document.getElementById("franchiseForm").addEventListener("submit", function (event) {
    event.preventDefault();

    const cphValue = document.querySelector('select[name="cph_link"]').value;
    if (!cphValue) {
        Swal.fire({
            icon: "warning",
            title: "CPH Not Available",
            text: "No CPH is available in this city. Please choose another city.",
        });
        return;
    }

    const blob = new Blob(chunks, {
        type: "video/webm",
    });

    if (!$("#invalidCheck").is(":checked")) {
        return;
    }

    const formData = new FormData(this);
    formData.append("video_kyc", blob, "recording.webm");

    // Show "Please wait" loader
    Swal.fire({
        title: "Uploading...",
        text: "Please wait while we upload your Details.",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    $.ajax({
        url: $(this).attr("action"),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            Swal.close(); // Close loading alert
            hideLoader();
            console.log(response);
            window.location.href = "{{ route('franchise.otpViewPage', ['mobile' => '__MOBILE__']) }}".replace("__MOBILE__", response.data.mobile);

            chunks = [];
            const video = document.getElementById("video");
            if (video.srcObject) {
                video.srcObject.getTracks().forEach((track) => track.stop());
                video.srcObject = null;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.close(); // Close loading alert
            hideLoader();
            console.error("Video upload failed:", textStatus, errorThrown);
            $(".overlay").css("display", "none");

            let errorMessage = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : "Something went wrong during the upload. Please try again.";

            Swal.fire({
                title: "Error!",
                text: errorMessage,
                icon: "error",
                confirmButtonText: "OK",
            });
        },
    });
});


            // Update timing during recording
            function updateTiming() {
                timingInterval = setInterval(() => {
                    const elapsed = Date.now() - startTime;
                    const minutes = Math.floor(elapsed / 60000);
                    const seconds = Math.floor((elapsed % 60000) / 1000);
                    document.getElementById("timing").textContent = `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
                }, 1000);
            }
        </script>

        <script>
            $(".type").on("change", function () {
                var select = $(this).val();
                if (select == "proprietor") {
                    $(".secound_names").hide();
                    $(".names").show(); // Show '.names' if 'proprietor' is selected
                } else {
                    $(".names").hide(); // Hide '.names' if not 'proprietor'
                    $(".secound_names").show(); // Show '.secound_names' if not 'proprietor'
                }
            });

            let rowCount = 1; // Initial row count

            // Event listener for the "Add More" button
            document.getElementById("add-more-btn").addEventListener("click", function () {
                rowCount++; // Increment row count

                // Create a new row as an HTML string
                const newRow = `
        <div class="row mt-4" id="document-row-${rowCount}">
            <div class="col-sm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="fileInput${rowCount}_1">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name[]" placeholder="Enter full name" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="fileInput${rowCount}_2">Adhar Front Image</label>
                        <input type="file" class="form-control" id="fileInput${rowCount}_2" name="adhar_front_img[]" accept=".jpg,.jpeg,.png,.pdf" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="fileInput${rowCount}_3">Adhar Back Image</label>
                        <input type="file" class="form-control" id="fileInput${rowCount}_3" name="adhar_back_img[]" accept=".jpg,.jpeg,.png,.pdf" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="fileInput${rowCount}_4">Pan Card Image</label>
                        <input type="file" class="form-control" id="fileInput${rowCount}_4" name="pan_img[]" accept=".jpg,.jpeg,.png,.pdf" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="fileInput${rowCount}_5">Cancel Cheque Image</label>
                        <input type="file" class="form-control" id="fileInput${rowCount}_5" name="cheque_img[]" accept=".jpg,.jpeg,.png,.pdf" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="fileInput${rowCount}_6">Photo</label>
                        <input type="file" class="form-control" id="fileInput${rowCount}_6" name="photo[]" accept=".jpg,.jpeg,.png,.pdf" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="fileInput${rowCount}_7">Other Document</label>
                        <input type="file" class="form-control" id="fileInput${rowCount}_7" name="other_document[]" accept=".jpg,.jpeg,.png,.pdf" />
                    </div>

                    <!-- Remove button added to each row -->
                    <div class="col-4 mb-3">
                        <button type="button" class="btn btn-danger remove-btn" style="margin-top: 30px;">Remove</button>
                    </div>
                </div>
            </div>
        </div>
    `;

                // Append the new row to the document section
                document.getElementById("document-section").insertAdjacentHTML("beforeend", newRow);
            });

            // Event delegation for remove buttons
            document.getElementById("document-section").addEventListener("click", function (event) {
                // Check if the clicked element is a "Remove" button
                if (event.target && event.target.classList.contains("remove-btn")) {
                    // Find the parent row of the "Remove" button and remove it
                    const rowToRemove = event.target.closest(".row");
                    if (rowToRemove) {
                        rowToRemove.remove();
                    }
                }
            });
        </script>

        <script src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places&callback=initAutocomplete" type="text/javascript"></script>

        <script>
            google.maps.event.addDomListener(window, "load", initialize);

            function initialize() {
                var input = document.getElementById("address");
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.addListener("place_changed", function () {
                    var place = autocomplete.getPlace();
                    $("#latitude").val(place.geometry["location"].lat());
                    $("#longitude").val(place.geometry["location"].lng());
                });
            }
        </script>

        <!-- Filter Location -->

        <script>
            $(".nearBy").click(function () {
                $(".filter_city_toggle").show();
            });

            $(document).ready(function () {
                $("#city").on("keyup", function () {
                    let city = $(this).val();
                    var csrfToken = "{{ csrf_token() }}";

                    $.ajax({
                        url: "{{route('franchise.get.location')}}",
                        type: "POST",
                        data: {
                            city: city,
                            _token: csrfToken, // token yahan hai
                        },
                        success: function (response) {
                            $(".location_col").show();
                            $(".location_dropdown").html(response);
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                        },
                    });
                });
            });
        </script>
        <script>
    document.getElementById('gst_number').addEventListener('input', function (e) {
        e.target.value = e.target.value.toUpperCase();
    });
</script>
        <!--End Filter Location -->
    </body>
</html>
