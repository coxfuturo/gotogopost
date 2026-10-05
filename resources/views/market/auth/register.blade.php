<!DOCTYPE html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
    <head>
        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <meta name="description" content="Smarthr - Bootstrap Admin Template" />

        <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects" />

        <meta name="author" content="Dreamguys - Bootstrap Admin Template" />

        <title>Sales Marketing Manager|Register</title>

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
        .card-header {
            background: #f62d51 !important;
        }
        .card-title{
            color: #fff !important;
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
                            <h4 class="account-title " style="font-size: 22px;">Registration Form for Sales Marketing Manager</h4>
                            <center><p class=" text-center mb-3" style="font-size: 17px;color:#f62d51 !important">Open to Commission-Based Candidates Only</p></center>

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

                                        <form id="franchiseForm" action="{{route('market.register')}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                                            @csrf

                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Basic Details</h5>
                                            </div>

                                            <div class="card-body">
                                                @include('admin.layouts.error')

                                                <div class="row">
                                                    <div class="col-sm">
                                                        <div class="row">
                                                           <div class="col-md-4 mb-3">
                                                                <label for="fileInput1">Full Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="name" placeholder="Enter full name" />
                                                                <div id="fileContainer1"></div>
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
                                                        </div>

                                                        <div class="row">
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

                                                    
                                                                     <input
                                                                        type="hidden"
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

                                            <!-- Education -->

                                             <div class="card-header">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <h5 class="card-title mb-0">Education Details (<small class="text-white">Registration for Graduates Only</small>)</h5>
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                            <div class="card-body" id="document-section">
                                                <div class="row mt-4" id="document-row-1">
                                                    <div class="col-sm">
                                                        <div class="row">
                                                            
                                                           <div class="col-md-3 mb-3" >
                                                                <label for="collage">Collage Name</label>
                                                                <div class="input-group">
                                                                 <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                                <input type="Text" class="form-control" id="collage" name="collage" placeholder="Collage Name">
                                                                <div class="invalid-feedback">
                                                                        Please provide Collage Name
                                                                    </div>
                                                                </div>
                                                            </div>

                                                           <div class="col-md-3 mb-3">
                                                                <label for="sector">Grade/Precentage</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                                    <select class="marksheetType form-select @error('marksheetType') is-invalid @enderror" name="marksheetType" required>
                                                                        <option selected disabled>Select Option</option>
                                                                        <option value="grade">Grade</option>
                                                                        <option value="percentage">percentage</option>
                                                                        
                                                                    </select>

                                                                    <div class="invalid-feedback">
                                                                        Please provide Grade/Precentage
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 mb-3 gradeDiv">
                                                                <label for="grade">Grade</label>
                                                                <div class="input-group">
                                                                     <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                                <input type="text" class="form-control" id="grade" name="grade" placeholder="Grade">
                                                                <div class="invalid-feedback">
                                                                        Please provide Gender
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 mb-3 percentageDiv" style="display:none">
                                                                <label for="percentage">Percentage</label>
                                                                <input type="numer" class="form-control" id="percentage" name="percentage" placeholder="Percentage %">
                                                                <div class="invalid-feedback">
                                                                        Please provide Percentage
                                                                    </div>
                                                            </div>

                                                            <div class="col-md-3 mb-3">
                                                                <label for="marksheet">Marksheet</label>
                                                                <input type="file" class="form-control" id="marksheet" name="marksheet" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="marksheet"></div>
                                                            </div>


                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- End Education -->

                                            <!-- Exprenace -->

                                             <div class="card-header">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <h5 class="card-title mb-0">Experience Details (<small class="text-white">Minimum One Year of Experience</small>)</h5>
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                            <div class="card-body" id="document-section">
                                                <div class="row mt-4" id="document-row-1">
                                                    <div class="col-sm">
                                                        <div class="row">
                                                            
                                                           <div class="col-md-3 mb-3" >
                                                                <label for="collage">Total Experience</label>
                                                                <div class="input-group">
                                                                 <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                                <input type="number" class="form-control" id="experience" name="experience" placeholder="Total Experience">
                                                                <div class="invalid-feedback">
                                                                        Please provide Experience Total
                                                                    </div>
                                                                </div>
                                                            </div>

                                                           <div class="col-md-3 mb-3">
                                                                <label for="sector">Experience Start Date</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                                   
                                                                <input type="date" class="form-control" id="start_date" name="start_date" >

                                                                    <div class="invalid-feedback">
                                                                        Please provide Experience Start Date
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 mb-3">
                                                                <label for="sector">Experience End Date</label>

                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                                    <input type="date" class="form-control" id="end_date" name="end_date" >

                                                                    <div class="invalid-feedback">
                                                                        Please provide Experience End Date
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 mb-3">
                                                                <label for="marksheet">Resume</label>
                                                                <input type="file" class="form-control" id="resume" name="resume" accept=".jpg,.jpeg,.png,.pdf" />
                                                              <div class="invalid-feedback">
                                                                        Please provide Resume
                                                                    </div>
                                                            </div>
                                                              

                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- End Exprenace -->

                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <h5 class="card-title mb-0">Upload Documents (<small class="text-white">Image size upto 1 MB</small>)</h5>
                                                    </div>
                                                   
                                                </div>
                                            </div>

                                            <div class="card-body" id="document-section">
                                                <div class="row mt-4" id="document-row-1">
                                                    <div class="col-sm">
                                                        <div class="row">
                                                            
                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput1">Adhar Front Image</label>
                                                                <input type="file" class="form-control" id="fileInput1" name="adhar_front_img" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer1"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput2">Adhar Back Image</label>
                                                                <input type="file" class="form-control" id="fileInput2" name="adhar_back_img" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer2"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput3">Pan Card Image</label>
                                                                <input type="file" class="form-control" id="fileInput3" name="pan_img" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer3"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput4">Cancel Cheque Image</label>
                                                                <input type="file" class="form-control" id="fileInput4" name="cheque_img" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer4"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput5">Photo</label>
                                                                <input type="file" class="form-control" id="fileInput5" name="photo" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer5"></div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput6">Other Document</label>
                                                                <input type="file" class="form-control" id="fileInput6" name="other_document" accept=".jpg,.jpeg,.png,.pdf" />
                                                                <div id="fileContainer6"></div>
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

                                                             <div class="col-md-4 mb-3 location_col">
                                                                <label for="gst_number">Link:-Office Near By Business Associate</label>

                                                                <div class="input-group">
                                                                    <select name="cph_link" class="form-control location_dropdown">
                                                                        <option selected disabled>Please Select Business Associate</option>
                                                                    </select>
                                                                    
                                                                </div>
                                                            </div>


                                                            <div class="col-md-4 mb-3">
                                                                <label for="fileInput7">Video KYC</label>
                                                                <input data-bs-toggle="modal" data-bs-target="#add_role_user" type="button" class="form-control" id="startCameraButton" value="Start Recording" name="video_kyc" />
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
                                                            <button class="btn" type="submit" style="background: #f62d51 !important;color: #fff;">Submit</button>
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
                                PLEASE CLEARLY SAY YOUR FULL NAME, AADHAR NUMBER, PAN CARD NUMBER ON CAMERA.<br />
                                कृपया कैमरे के सामने स्पष्ट रूप से अपना पूरा नाम, आधार नंबर, पैन नंबर बोलें।
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
    // ===========================================
    // 🚀 DIRECT REGISTRATION - NO OTP VERIFICATION
    // ===========================================

    let mediaRecorder;
    let stream;
    let chunks = [];
    let timingInterval;
    let startTime;

    // 📹 Start Camera
    document.getElementById("startCameraButton")?.addEventListener("click", async () => {
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

    // 🎥 Start Recording
    document.getElementById("startRecordingButton")?.addEventListener("click", async () => {
        try {
            if (mediaRecorder && mediaRecorder.state === "recording") {
                return;
            }

            if (!stream) {
                Swal.fire({
                    icon: "warning",
                    title: "Camera Not Started",
                    text: "Please start camera first.",
                });
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
            };

            mediaRecorder.start();

            setTimeout(() => {
                if (mediaRecorder && mediaRecorder.state === "recording") {
                    mediaRecorder.stop();
                    Swal.fire({
                        icon: "success",
                        title: "Recording Complete",
                        text: "Your video has been recorded successfully.",
                    });
                }
            }, 40000);

        } catch (err) {
            console.error("Error starting recording:", err);
        }
    });

    // 🔄 Restart Recording
    document.getElementById("restartRecordingButton")?.addEventListener("click", async () => {
        if (mediaRecorder && mediaRecorder.state === "recording") {
            mediaRecorder.stop();
        }
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
        }
        chunks = [];

        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            const video = document.getElementById("video");
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                video.play();
            };

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

        } catch (err) {
            console.error("Error restarting recording:", err);
        }
    });

    // ⏱️ Update Timing
    function updateTiming() {
        timingInterval = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const seconds = Math.floor((elapsed % 60000) / 1000);
            document.getElementById("timing").textContent = `00:${String(seconds).padStart(2, "0")}`;
        }, 1000);
    }

    // 🛑 Stop Recording & Stream
    function stopRecordingAndStream() {
        if (mediaRecorder && mediaRecorder.state === "recording") {
            mediaRecorder.stop();
        }
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
        }
    }

    // 📝 Pincode AJAX
    $("#pincode").on("change", function () {
        var pincode = $("#pincode").val();
        $.ajax({
            type: "GET",
            url: "{{ url('admin/city-state') }}/" + pincode + "?type=franchise",
            success: function (data) {
                if (data.success === true) {
                    $("#district").empty().val(data.district);
                    $("#state").empty().val(data.state);
                    $("#generated_id").empty().val(data.generated_id);
                } else {
                    $(".validerror").text("Please enter valid pincode");
                }
            },
        });
    });

    // 🔼 Force Upper Case
    function forceUpper(strInput) {
        strInput.value = strInput.value.toUpperCase();
    }

    // 🏦 IFSC Code AJAX
    var bankIfsc = $("#ifsc_code");
    var bankIfscError = $("#bank_ifsc_error");
    var bankName = $("#bank_name");
    var bankBranch = $("#branch_name");

    bankIfsc.on("input", function () {
        var ifscCode = bankIfsc.val();
        if (ifscCode.length === 11) {
            $.ajax({
                url: "https://ifsc.razorpay.com/" + ifscCode,
                success: function (res) {
                    bankName.val(res.BANK);
                    bankBranch.val(res.BRANCH);
                    bankIfscError.html("");
                },
                error: function () {
                    bankIfscError.html("Please enter a valid IFSC Code");
                },
            });
        } else {
            bankName.val("");
            bankBranch.val("");
            bankIfscError.html("");
        }
    });

    // 🏙️ Filter Location by City
    $(document).ready(function () {
        $("#city").on("keyup", function () {
            let city = $(this).val();
            var csrfToken = "{{ csrf_token() }}";

            $.ajax({
                url: "{{route('customer.get.location')}}",
                type: "POST",
                data: {
                    city: city,
                    _token: csrfToken,
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

    // 📊 Marksheet Type Toggle
    $('.marksheetType').on('change', function(){
        var select = $(this).val();
        if(select == 'grade'){
            $('.gradeDiv').show();
            $('.percentageDiv').hide();
        } else {
            $('.gradeDiv').hide();
            $('.percentageDiv').show();
        }
    });

    // 🔠 GST Number Upper Case
    document.getElementById('gst_number')?.addEventListener('input', function (e) {
        e.target.value = e.target.value.toUpperCase();
    });

    // ===========================================
    // 🚀 MAIN FORM SUBMISSION - DIRECT REGISTRATION
    // ===========================================
    document.getElementById("franchiseForm").addEventListener("submit", function (event) {
        event.preventDefault();

        // ✅ Franchise Selection Check
        const cphValue = document.querySelector('select[name="cph_link"]')?.value;
        if (!cphValue || cphValue === "Select Franchise" || cphValue === "Please Select Franchise") {
            Swal.fire({
                icon: "warning",
                title: "Franchise Required",
                text: "Please select a nearby franchise.",
                confirmButtonColor: "#f62d51",
            });
            return;
        }

        // ✅ Agreement Check
        if (!$("#invalidCheck").is(":checked")) {
            Swal.fire({
                icon: "warning",
                title: "Agreement Required",
                text: "Please accept the agreement and terms & conditions.",
                confirmButtonColor: "#f62d51",
            });
            return;
        }

        // ✅ Video KYC - Optional (सिर्फ warning)
        if (chunks.length === 0) {
            Swal.fire({
                icon: "info",
                title: "Video KYC Optional",
                text: "You haven't recorded video KYC. You can record later.",
                confirmButtonText: "Continue Anyway",
                confirmButtonColor: "#f62d51",
            });
        }

        // ✅ Prepare Form Data
        const formData = new FormData(this);
        
        // Add video if available
        if (chunks.length > 0) {
            const blob = new Blob(chunks, { type: "video/webm" });
            formData.append("video_kyc", blob, "recording.webm");
        }

        // ✅ Show Loader
        Swal.fire({
            title: "Processing...",
            text: "Your registration is being processed.",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        // ✅ AJAX Request - Direct Registration (No OTP)
        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.close();
                
                if (response.success) {
                    // 🎉 SUCCESS POPUP - Username & Password Display
                    Swal.fire({
                        icon: "success",
                        title: "🎉 Registration Successful!",
                        html: `
                            <div style="text-align: left; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                <p style="font-size: 16px; margin-bottom: 12px;">
                                    <strong>👤 Name:</strong> ${response.data.name}<br>
                                    <strong style="color: #f62d51;">📧 Username:</strong> 
                                    <span style="background: #fff; padding: 5px 10px; border-radius: 4px; border: 1px dashed #f62d51; display: inline-block; margin-top: 5px;">
                                        ${response.data.username}
                                    </span><br>
                                    <strong style="color: #f62d51;">🔑 Password:</strong> 
                                    <span style="background: #fff; padding: 5px 10px; border-radius: 4px; border: 1px dashed #f62d51; display: inline-block; margin-top: 5px;">
                                        ${response.data.password}
                                    </span><br>
                                    <strong>📱 Mobile:</strong> ${response.data.mobile}
                                </p>
                                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin-top: 15px;">
                                    <p style="color: #856404; margin: 0; font-weight: bold;">
                                        ⚠️ Please save these credentials for login!
                                    </p>
                                </div>
                            </div>
                        `,
                        confirmButtonText: "🔐 Go to Login Page",
                        confirmButtonColor: "#f62d51",
                        allowOutsideClick: false,
                        showCloseButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to Login Page
                            window.location.href = "{{ route('market.login') }}";
                        }
                    });

                    // ✅ Clear video chunks
                    chunks = [];

                    // ✅ Stop camera if running
                    const video = document.getElementById("video");
                    if (video?.srcObject) {
                        video.srcObject.getTracks().forEach(track => track.stop());
                        video.srcObject = null;
                    }
                }
            },
            error: function (jqXHR) {
                Swal.close();
                
                let errorMsg = "Registration failed. Please try again.";
                if (jqXHR.responseJSON?.message) {
                    errorMsg = jqXHR.responseJSON.message;
                } else if (jqXHR.responseJSON?.errors) {
                    errorMsg = Object.values(jqXHR.responseJSON.errors).flat().join("\n");
                }

                Swal.fire({
                    icon: "error",
                    title: "❌ Registration Failed!",
                    text: errorMsg,
                    confirmButtonText: "OK",
                    confirmButtonColor: "#f62d51",
                });
            },
        });
    });

    // Modal Close Event
    document.getElementById("modalCloseButton")?.addEventListener("click", () => {
        stopRecordingAndStream();
    });
</script>
    </body>
</html>
