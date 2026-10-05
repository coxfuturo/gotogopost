```blade
<!DOCTYPE html>
<html lang="en"
      data-layout="vertical"
      data-topbar="light"
      data-sidebar="dark"
      data-sidebar-size="lg"
      data-sidebar-image="none">

<head>

    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="description" content="Smarthr - Bootstrap Admin Template" />

    <meta name="keywords"
          content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects" />

    <meta name="author" content="Dreamguys - Bootstrap Admin Template" />

    <title>Business Bulk Customer | Register</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css"
          rel="stylesheet"
          type="text/css" />

    <!-- Favicon -->
    <link rel="shortcut icon"
          type="image/x-icon"
          href="{{ asset('admin/assets/img/favicon.png') }}" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="{{ asset('admin/assets/css/bootstrap.min.css') }}" />

    <!-- Fontawesome CSS -->
    <link rel="stylesheet"
          href="{{ asset('admin/assets/plugins/fontawesome/css/fontawesome.min.css') }}" />

    <link rel="stylesheet"
          href="{{ asset('admin/assets/plugins/fontawesome/css/all.min.css') }}" />

    <!-- Lineawesome CSS -->
    <link rel="stylesheet"
          href="{{ asset('admin/assets/css/line-awesome.min.css') }}" />

    <link rel="stylesheet"
          href="{{ asset('admin/assets/css/material.css') }}" />

    <!-- Main CSS -->
    <link rel="stylesheet"
          href="{{ asset('admin/assets/css/style.css') }}" />

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

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .hidden {
            display: none;
        }

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
            animation: prixClipFix 2s linear infinite,
                       rotate 0.5s linear infinite reverse;
            inset: 6px;
        }

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
                clip-path: polygon(
                    50% 50%,
                    0 0,
                    0 0,
                    0 0,
                    0 0,
                    0 0
                );
            }

            25% {
                clip-path: polygon(
                    50% 50%,
                    0 0,
                    100% 0,
                    100% 0,
                    100% 0,
                    100% 0
                );
            }

            50% {
                clip-path: polygon(
                    50% 50%,
                    0 0,
                    100% 0,
                    100% 100%,
                    100% 100%,
                    100% 100%
                );
            }

            75% {
                clip-path: polygon(
                    50% 50%,
                    0 0,
                    100% 0,
                    100% 100%,
                    0 100%,
                    0 100%
                );
            }

            100% {
                clip-path: polygon(
                    50% 50%,
                    0 0,
                    100% 0,
                    100% 100%,
                    0 100%,
                    0 0
                );
            }
        }

        @media (max-width: 768px) {

            .container {
                padding: 0px !important;
            }

            .account-page .main-wrapper
            .account-content .account-box
            .account-wrapper {
                padding: 30px 10px !important;
            }

            .mobilemarginbottom {
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

</head>


<body class="account-page">

<!-- Main Wrapper -->
<div class="main-wrapper">

    <div class="account-content">

        <div class="container">

            <!-- Account Logo -->
            <div class="account-logo">
                <a href="#">
                    <img src="{{ asset('website/images/logonewtransparent.png') }}"
                         alt="IndiaPost" />
                </a>
            </div>
            <!-- /Account Logo -->


            <div class="account-box">

                <div class="account-wrapper">

                    <h3 class="account-title mb-3">
                        Business Bulk Customer Form
                    </h3>


                    <div class="row">

                        <div class="col-sm-12">

                            <div class="card">

                                <!-- Loader -->
                                <div id="overlay" class="overlay hidden">
                                    <div class="loader"></div>
                                </div>
                                <!-- /Loader -->


                                {{-- IMPORTANT:
                                     Admin store route --}}
                                <form id="franchiseForm"
                                      action="{{ route('admin.e-customer.store') }}"
                                      enctype="multipart/form-data"
                                      method="POST"
                                      class="needs-validation"
                                      novalidate>

                                    @csrf


                                    <!-- ================= BASIC DETAILS ================= -->

                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            Basic Details
                                        </h5>
                                    </div>


                                    <div class="card-body">

                                        @include('admin.layouts.error')


                                        <div class="row">

                                            <div class="col-sm">

                                                <!-- Row 1 -->
                                                <div class="row">

                                                    <!-- Partner Type -->
                                                    <div class="col-sm-4 mobilemarginbottom">

                                                        <label for="register_type">
                                                            Partner Type
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="input-group">

                                                            <select class="form-select type"
                                                                    name="register_type"
                                                                    id="register_type"
                                                                    required>

                                                                <option value="proprietor"
                                                                    {{ old('register_type', 'proprietor') == 'proprietor' ? 'selected' : '' }}>
                                                                    Proprietorship Firm
                                                                </option>

                                                                <option value="partner"
                                                                    {{ old('register_type') == 'partner' ? 'selected' : '' }}>
                                                                    Partnership Firm
                                                                </option>

                                                                <option value="private"
                                                                    {{ old('register_type') == 'private' ? 'selected' : '' }}>
                                                                    Private Limited
                                                                </option>

                                                            </select>

                                                        </div>

                                                    </div>


                                                    <!-- Director / Owner -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="father_name">
                                                            Director/Owner Name
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-user"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control @error('father_name') is-invalid @enderror"
                                                                   id="father_name"
                                                                   name="father_name"
                                                                   value="{{ old('father_name') }}"
                                                                   placeholder="Director/Owner Name"
                                                                   required />

                                                            <div class="invalid-feedback">
                                                                Please provide Director/Owner Name
                                                            </div>

                                                        </div>

                                                    </div>


                                                    <!-- Mobile -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="mobile">
                                                            Firm/Company/Mobile
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-building"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control NumberValidate @error('mobile') is-invalid @enderror"
                                                                   maxlength="10"
                                                                   id="mobile"
                                                                   name="mobile"
                                                                   value="{{ old('mobile') }}"
                                                                   placeholder="Firm/Company/Mobile"
                                                                   required />

                                                            <div class="invalid-feedback">
                                                                Please provide Firm/Company/Mobile
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                                <!-- Row 2 -->
                                                <div class="row">

                                                    <!-- Email -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="email">
                                                            Email
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-envelope"></i>
                                                            </span>

                                                            <input type="email"
                                                                   class="form-control @error('email') is-invalid @enderror"
                                                                   id="email"
                                                                   name="email"
                                                                   placeholder="Email ID"
                                                                   value="{{ old('email') }}"
                                                                   required />

                                                            <div class="invalid-feedback">
                                                                Please provide Email ID
                                                            </div>

                                                        </div>

                                                    </div>


                                                    <!-- Pincode -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="pincode">
                                                            Pincode
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-building"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control NumberValidate @error('pincode') is-invalid @enderror"
                                                                   maxlength="6"
                                                                   id="pincode"
                                                                   name="pincode"
                                                                   value="{{ old('pincode') }}"
                                                                   placeholder="Pincode"
                                                                   required />

                                                            <div class="invalid-feedback">
                                                                Please provide pincode
                                                            </div>

                                                            <div class="invalid-feedback validerror"></div>

                                                        </div>

                                                    </div>


                                                    <!-- City -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="city">
                                                            City
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-building"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control @error('city') is-invalid @enderror"
                                                                   id="city"
                                                                   name="city"
                                                                   placeholder="City"
                                                                   value="{{ old('city') }}"
                                                                   required />

                                                            <div class="invalid-feedback">
                                                                Please provide city
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                                <!-- Row 3 -->
                                                <div class="row">

                                                    <!-- District -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="district">
                                                            District
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-building"></i>
                                                            </span>

                                                            <input type="text"
                                                                   readonly
                                                                   class="form-control @error('district') is-invalid @enderror"
                                                                   id="district"
                                                                   name="district"
                                                                   value="{{ old('district') }}"
                                                                   required />

                                                            <div class="invalid-feedback">
                                                                Please provide district
                                                            </div>

                                                        </div>

                                                    </div>


                                                    <!-- State -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="state">
                                                            State
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-envelope"></i>
                                                            </span>

                                                            <input type="text"
                                                                   readonly
                                                                   class="form-control @error('state') is-invalid @enderror"
                                                                   id="state"
                                                                   name="state"
                                                                   value="{{ old('state') }}"
                                                                   required />

                                                            <div class="invalid-feedback">
                                                                Please provide state
                                                            </div>

                                                        </div>

                                                    </div>


                                                    <!-- Address -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="address">
                                                            Residential/Office Address
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-building"></i>
                                                            </span>

                                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                                      id="address"
                                                                      name="address"
                                                                      placeholder="Type address..."
                                                                      required>{{ old('address') }}</textarea>


                                                            <input type="hidden"
                                                                   name="latitude"
                                                                   value="{{ old('latitude') }}"
                                                                   id="latitude" />

                                                            <input type="hidden"
                                                                   name="longitude"
                                                                   value="{{ old('longitude') }}"
                                                                   id="longitude" />

                                                            <div class="invalid-feedback">
                                                                Please provide address
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                                <!-- Row 4 -->
                                                <div class="row">

                                                    <!-- Company Name -->
                                                    <div class="col-md-6 mb-3">

                                                        <label for="society_name">
                                                            Firm/Company Name
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-building"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control @error('society_name') is-invalid @enderror"
                                                                   id="society_name"
                                                                   name="society_name"
                                                                   value="{{ old('society_name') }}"
                                                                   placeholder="Firm/Company"
                                                                   required />

                                                            <div class="invalid-feedback">
                                                                Please provide company name
                                                            </div>

                                                        </div>

                                                    </div>


                                                    <!-- Sector -->
                                                    <div class="col-md-6 mb-3">

                                                        <label for="sector">
                                                            Sector/Street No.
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-envelope"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control @error('sector') is-invalid @enderror"
                                                                   id="sector"
                                                                   name="sector"
                                                                   value="{{ old('sector') }}"
                                                                   placeholder="Sector/Street No." />

                                                        </div>

                                                    </div>


                                                    <!-- Generated ID -->
                                                    <input type="hidden"
                                                           id="generated_id"
                                                           name="generated_id"
                                                           value="{{ old('generated_id') }}" />

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- ================= DOCUMENTS ================= -->

                                    <div class="card-header">

                                        <div class="row">

                                            <div class="col-sm-8">

                                                <h5 class="card-title mb-0">

                                                    Upload Documents

                                                    <small class="text-danger">
                                                        (Image size upto 1 MB)
                                                    </small>

                                                </h5>

                                            </div>


                                            <div class="col-sm-4 text-end secound_names"
                                                 style="display: none;">

                                                <button type="button"
                                                        class="btn btn-dark"
                                                        id="add-more-btn">

                                                    Add More

                                                </button>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="card-body"
                                         id="document-section">

                                        <div class="row mt-4"
                                             id="document-row-1">

                                            <div class="col-sm">

                                                <div class="row">

                                                    <!-- Full Name -->
                                                    <div class="col-md-4 mb-3">

                                                        <label>
                                                            Full Name
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <input type="text"
                                                               class="form-control"
                                                               name="name[]"
                                                               placeholder="Enter full name"
                                                               required />

                                                    </div>


                                                    <!-- Aadhaar Front -->
                                                    <div class="col-md-4 mb-3">

                                                        <label>
                                                            Adhar Front Image
                                                        </label>

                                                        <input type="file"
                                                               class="form-control"
                                                               id="fileInput1"
                                                               name="adhar_front_img[]"
                                                               accept=".jpg,.jpeg,.png,.pdf" />

                                                        <div id="fileContainer1"></div>

                                                    </div>


                                                    <!-- Aadhaar Back -->
                                                    <div class="col-md-4 mb-3">

                                                        <label>
                                                            Adhar Back Image
                                                        </label>

                                                        <input type="file"
                                                               class="form-control"
                                                               id="fileInput2"
                                                               name="adhar_back_img[]"
                                                               accept=".jpg,.jpeg,.png,.pdf" />

                                                        <div id="fileContainer2"></div>

                                                    </div>


                                                    <!-- PAN -->
                                                    <div class="col-md-4 mb-3">

                                                        <label>
                                                            Pan Card Image
                                                        </label>

                                                        <input type="file"
                                                               class="form-control"
                                                               id="fileInput3"
                                                               name="pan_img[]"
                                                               accept=".jpg,.jpeg,.png,.pdf" />

                                                        <div id="fileContainer3"></div>

                                                    </div>


                                                    <!-- Cheque -->
                                                    <div class="col-md-4 mb-3">

                                                        <label>
                                                            Cancel Cheque Image
                                                        </label>

                                                        <input type="file"
                                                               class="form-control"
                                                               id="fileInput4"
                                                               name="cheque_img[]"
                                                               accept=".jpg,.jpeg,.png,.pdf" />

                                                        <div id="fileContainer4"></div>

                                                    </div>


                                                    <!-- Photo -->
                                                    <div class="col-md-4 mb-3">

                                                        <label>
                                                            Photo
                                                        </label>

                                                        <input type="file"
                                                               class="form-control"
                                                               id="fileInput5"
                                                               name="photo[]"
                                                               accept=".jpg,.jpeg,.png,.pdf" />

                                                        <div id="fileContainer5"></div>

                                                    </div>


                                                    <!-- Other Document -->
                                                    <div class="col-md-4 mb-3">

                                                        <label>
                                                            Other Document
                                                        </label>

                                                        <input type="file"
                                                               class="form-control"
                                                               id="fileInput6"
                                                               name="other_document[]"
                                                               accept=".jpg,.jpeg,.png,.pdf" />

                                                        <div id="fileContainer6"></div>

                                                    </div>


                                                    <!-- Remove -->
                                                    <div class="col-4 mb-3">

                                                        <button type="button"
                                                                class="btn btn-danger remove-btn"
                                                                style="margin-top: 30px; display:none;">

                                                            Remove

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- ================= KYC ================= -->

                                    <div class="card-header">

                                        <h5 class="card-title mb-0">
                                            KYC Details
                                        </h5>

                                    </div>


                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-sm">

                                                <!-- Row -->
                                                <div class="row">

                                                    <!-- Aadhaar -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="adhar_card">
                                                            Aadhar Number
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-user"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control NumberValidate @error('adhar_card') is-invalid @enderror"
                                                                   maxlength="12"
                                                                   id="adhar_card"
                                                                   name="adhar_card"
                                                                   value="{{ old('adhar_card') }}"
                                                                   placeholder="Adhar Card Number" />

                                                        </div>

                                                    </div>


                                                    <!-- PAN -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="pan_card">
                                                            Pan Number
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-user"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control @error('pan_card') is-invalid @enderror"
                                                                   id="pan_card"
                                                                   name="pan_card"
                                                                   value="{{ old('pan_card') }}"
                                                                   placeholder="Pan Card Number" />

                                                        </div>

                                                    </div>


                                                    <!-- IFSC -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="ifsc_code">
                                                            IFSC Code
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-building"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control @error('ifsc_code') is-invalid @enderror"
                                                                   maxlength="11"
                                                                   id="ifsc_code"
                                                                   name="ifsc_code"
                                                                   value="{{ old('ifsc_code') }}"
                                                                   placeholder="IFSC Code" />

                                                            <div class="text-danger"
                                                                 id="bank_ifsc_error">
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                                <!-- Row -->
                                                <div class="row">

                                                    <!-- Bank -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="bank_name">
                                                            Bank Name
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-building"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control @error('bank_name') is-invalid @enderror"
                                                                   readonly
                                                                   id="bank_name"
                                                                   name="bank_name"
                                                                   value="{{ old('bank_name') }}"
                                                                   placeholder="Auto detect after IFSC" />

                                                        </div>

                                                    </div>


                                                    <!-- Branch -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="branch_name">
                                                            Branch Name
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-user"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control @error('branch_name') is-invalid @enderror"
                                                                   readonly
                                                                   id="branch_name"
                                                                   name="branch_name"
                                                                   value="{{ old('branch_name') }}"
                                                                   placeholder="Auto detect after IFSC" />

                                                        </div>

                                                    </div>


                                                    <!-- Account -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="account_no">
                                                            Account Number
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-user"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control NumberValidate @error('account_number') is-invalid @enderror"
                                                                   maxlength="16"
                                                                   id="account_no"
                                                                   value="{{ old('account_number') }}"
                                                                   name="account_number"
                                                                   placeholder="Account Number" />

                                                        </div>

                                                    </div>


                                                    <!-- Location -->
                                                    <div class="col-md-4 mb-3">

                                                        <div class="nearBy"
                                                             style="display:flex;align-items:center;">

                                                            <label for="filter_city"
                                                                   style="margin-right:10px;">

                                                                Location

                                                            </label>

                                                            <span style="font-size:8px;"
                                                                  class="input-group-text">

                                                                <i class="fa fa-map-marker"></i>

                                                            </span>

                                                        </div>


                                                        <div class="input-group filter_city_toggle"
                                                             style="display:none;">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-building"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control @error('location') is-invalid @enderror"
                                                                   id="filter_city"
                                                                   name="location"
                                                                   placeholder="Search city."
                                                                   value="{{ old('location') }}" />

                                                        </div>

                                                    </div>


                                                    <!-- GST -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="gst_number">
                                                            GST Number
                                                        </label>

                                                        <div class="input-group">

                                                            <span class="input-group-text">
                                                                <i class="fa fa-user"></i>
                                                            </span>

                                                            <input type="text"
                                                                   class="form-control @error('gst_number') is-invalid @enderror"
                                                                   maxlength="15"
                                                                   id="gst_number"
                                                                   name="gst_number"
                                                                   value="{{ old('gst_number') }}"
                                                                   placeholder="GST Number" />

                                                        </div>

                                                    </div>


                                                    <!-- Video KYC -->
                                                    <div class="col-md-4 mb-3">

                                                        <label for="startCameraButton">
                                                            Video KYC
                                                        </label>

                                                        <input data-bs-toggle="modal"
                                                               data-bs-target="#add_role_user"
                                                               type="button"
                                                               class="form-control"
                                                               id="startCameraButton"
                                                               value="Start Recording" />

                                                    </div>


                                                    <!-- CPH -->
                                                    <div class="col-md-4 mb-3 location_col">

                                                        <label>
                                                            Link:- Office Near By Business Associate/L.P.O.
                                                        </label>

                                                        <div class="input-group">

                                                            <select name="cph_link"
                                                                    class="form-control location_dropdown"
                                                                    required>

                                                                <option value="" selected disabled>
                                                                    Please Select Business Associate/L.P.O.
                                                                </option>

                                                            </select>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- ================= AGREEMENT ================= -->

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-sm">

                                                <div class="input-block mb-3">


                                                    <!-- Agreement -->

                                                    <div class="form-check">

                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               value="1"
                                                               id="agreementCheck"
                                                               required />

                                                        <label class="form-check-label"
                                                               for="agreementCheck">

                                                            <a href="{{ route('website.agreement') }}"
                                                               style="color:black;">

                                                                Agreement

                                                            </a>

                                                            <a href="{{ route('website.agreement') }}"
                                                               target="_blank"
                                                               class="ms-1"
                                                               title="Read Terms">

                                                                <i class="la la-info-circle"
                                                                   style="font-size:1.2rem;color:#0d6efd;">
                                                                </i>

                                                            </a>

                                                        </label>

                                                        <div class="invalid-feedback">
                                                            You must agree before submitting.
                                                        </div>

                                                    </div>


                                                    <!-- Privacy -->

                                                    <div class="form-check">

                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               value="1"
                                                               id="privacyCheck"
                                                               required />

                                                        <label class="form-check-label"
                                                               for="privacyCheck">

                                                            <a href="{{ route('website.privacy-policy') }}"
                                                               style="color:black;">

                                                                Agree to terms and conditions

                                                            </a>

                                                            <a href="{{ route('website.privacy-policy') }}"
                                                               target="_blank"
                                                               class="ms-1"
                                                               title="Read Terms">

                                                                <i class="la la-info-circle"
                                                                   style="font-size:1.2rem;color:#0d6efd;">
                                                                </i>

                                                            </a>

                                                        </label>

                                                        <div class="invalid-feedback">
                                                            You must agree before submitting.
                                                        </div>

                                                    </div>

                                                </div>


                                                <div class="text-center">

                                                    <button class="btn btn-primary"
                                                            type="submit"
                                                            id="submitBtn">

                                                        Submit

                                                    </button>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- /Main Wrapper -->


<!-- ================= VIDEO KYC MODAL ================= -->

<div id="add_role_user"
     class="modal custom-modal fade"
     role="dialog">

    <div class="modal-dialog modal-dialog-centered"
         role="document">

        <div class="modal-content">

            <div>

                <button type="button"
                        id="modalCloseButton"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>


            <div>

                <div class="row"
                     style="position:relative;">

                    <video id="video"
                           autoplay
                           style="width:100%;">
                    </video>


                    <div id="videoOverlayText">

                        PLEASE CLEARLY SAY YOUR FULL NAME, AADHAR NUMBER, PAN CARD NUMBER, AND GST NUMBER ON CAMERA.

                        <br />

                        कृपया कैमरे के सामने स्पष्ट रूप से अपना पूरा नाम, आधार नंबर, पैन नंबर और जीएसटी नंबर बोलें।

                    </div>


                    <div class="col-6 d-flex justify-content-end align-items-end">

                        <div id="startRecordingButtonUpper">

                            <div>

                                <div id="timing"
                                     style="font-size:17px;color:white;">
                                </div>


                                <svg id="startRecordingButton"
                                     width="45"
                                     height="45"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     xmlns="http://www.w3.org/2000/svg">

                                    <circle cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="black"
                                            stroke-width="2" />

                                    <circle cx="12"
                                            cy="12"
                                            r="6"
                                            fill="red" />

                                </svg>

                            </div>

                        </div>

                    </div>


                    <div class="col-5">

                        <button id="restartRecordingButton"
                                type="button"
                                class="btn btn-warning mt-2"
                                style="color:black;font-weight:bold;width:30%;margin-left:5%;margin-bottom:5%;">

                            <i class="fa fa-refresh"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- /VIDEO KYC MODAL -->


<!-- ================= JAVASCRIPT ================= -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>

<script src="{{ asset('admin/assets/js/jquery-3.7.0.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/jquery.slimscroll.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/moment.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/bootstrap-datetimepicker.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/app.js') }}"></script>

<script src="{{ asset('franchise/js/custom.js') }}"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places"
        type="text/javascript">
</script>


<script>

    /*
    |--------------------------------------------------------------------------
    | Number Validation
    |--------------------------------------------------------------------------
    */

    $(document).on('input', '.NumberValidate', function () {

        this.value = this.value.replace(/[^0-9]/g, '');

    });


    /*
    |--------------------------------------------------------------------------
    | Mobile max 10
    |--------------------------------------------------------------------------
    */

    $('#mobile').on('input', function () {

        this.value = this.value.substring(0, 10);

    });


    /*
    |--------------------------------------------------------------------------
    | Pincode
    |--------------------------------------------------------------------------
    */

    $("#pincode").on("change", function () {

        var pincode = $("#pincode").val();

        if (pincode.length !== 6) {

            return;

        }

        $.ajax({

            type: "GET",

            url: "{{ url('admin/city-state') }}/" +
                 pincode +
                 "?type=franchise",

            success: function (data) {

                if (data.success === true) {

                    $(".validerror").empty();

                    $("#district").val(data.district);

                    $("#state").val(data.state);

                    $("#generated_id").val(data.generated_id);

                    /*
                     * If city comes from API then fill city.
                     */

                    if (data.city) {

                        $("#city").val(data.city);

                    }

                } else {

                    $(".validerror").text(
                        "Please enter valid pincode"
                    );

                }

            },

            error: function () {

                $(".validerror").text(
                    "Unable to fetch pincode details"
                );

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | IFSC
    |--------------------------------------------------------------------------
    */

    function forceUpper(strInput) {

        strInput.value = strInput.value.toUpperCase();

    }


    $("#ifsc_code").on("input", function () {

        var ifscCode = $(this).val().toUpperCase();

        $(this).val(ifscCode);

        if (ifscCode.length === 11) {

            $.ajax({

                url: "https://ifsc.razorpay.com/" + ifscCode,

                success: function (res) {

                    $("#bank_name").val(res.BANK);

                    $("#branch_name").val(res.BRANCH);

                    $("#bank_ifsc_error").html("");

                },

                error: function () {

                    $("#bank_name").val("");

                    $("#branch_name").val("");

                    $("#bank_ifsc_error").html(
                        "Please enter a valid IFSC Code"
                    );

                }

            });

        } else {

            $("#bank_name").val("");

            $("#branch_name").val("");

            $("#bank_ifsc_error").html("");

        }

    });


    /*
    |--------------------------------------------------------------------------
    | GST Uppercase
    |--------------------------------------------------------------------------
    */

    $('#gst_number').on('input', function () {

        this.value = this.value.toUpperCase();

    });


    /*
    |--------------------------------------------------------------------------
    | File Preview
    |--------------------------------------------------------------------------
    */

    function displayFile(id) {

        const fileInput =
            document.getElementById(`fileInput${id}`);

        const fileContainer =
            document.getElementById(`fileContainer${id}`);

        if (!fileContainer || !fileInput) {

            return;

        }

        fileContainer.innerHTML = "";

        if (fileInput.files.length === 0) {

            return;

        }

        const file = fileInput.files[0];

        const fileType = file.type;


        if (fileType === "application/pdf") {

            const reader = new FileReader();

            reader.onload = function (e) {

                const embed =
                    document.createElement("embed");

                embed.setAttribute(
                    "src",
                    e.target.result
                );

                embed.setAttribute(
                    "type",
                    "application/pdf"
                );

                embed.style.width = "285px";

                embed.style.height = "130px";

                fileContainer.appendChild(embed);

            };

            reader.readAsDataURL(file);

        } else if (fileType.startsWith("image/")) {

            const img =
                document.createElement("img");

            img.setAttribute(
                "src",
                URL.createObjectURL(file)
            );

            img.style.width = "100%";

            img.style.maxHeight = "130px";

            img.style.objectFit = "contain";

            fileContainer.appendChild(img);

        } else {

            fileContainer.innerHTML =
                "<p>Unsupported file type.</p>";

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Google Address Autocomplete
    |--------------------------------------------------------------------------
    */

    function initialize() {

        var input =
            document.getElementById("address");

        if (!input) {

            return;

        }

        var autocomplete =
            new google.maps.places.Autocomplete(input);

        autocomplete.addListener(
            "place_changed",
            function () {

                var place =
                    autocomplete.getPlace();

                if (place.geometry) {

                    $("#latitude").val(
                        place.geometry.location.lat()
                    );

                    $("#longitude").val(
                        place.geometry.location.lng()
                    );

                }

            }
        );

    }


    google.maps.event.addDomListener(
        window,
        "load",
        initialize
    );


    /*
    |--------------------------------------------------------------------------
    | Location / CPH
    |--------------------------------------------------------------------------
    */

    $(".nearBy").click(function () {

        $(".filter_city_toggle").show();

    });


    $(document).ready(function () {

        $("#filter_city").on("keyup", function () {

            let city = $(this).val();

            if (city.length < 2) {

                return;

            }

            var csrfToken =
                "{{ csrf_token() }}";


            $.ajax({

                url: "{{ route('customer.get.location') }}",

                type: "POST",

                data: {

                    city: city,

                    _token: csrfToken

                },

                success: function (response) {

                    $(".location_col").show();

                    $(".location_dropdown")
                        .html(response);

                },

                error: function (xhr) {

                    console.log(
                        xhr.responseText
                    );

                }

            });

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Partner Type
    |--------------------------------------------------------------------------
    */

    $(".type").on("change", function () {

        var select = $(this).val();

        if (select === "proprietor") {

            $(".secound_names").hide();

        } else {

            $(".secound_names").show();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Add More Documents
    |--------------------------------------------------------------------------
    */

    let rowCount = 1;


    document
        .getElementById("add-more-btn")
        .addEventListener("click", function () {

            rowCount++;


            const newRow = `

                <div class="row mt-4"
                     id="document-row-${rowCount}">

                    <div class="col-sm">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label>
                                    Full Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       class="form-control"
                                       name="name[]"
                                       placeholder="Enter full name"
                                       required />

                            </div>


                            <div class="col-md-4 mb-3">

                                <label>
                                    Adhar Front Image
                                </label>

                                <input type="file"
                                       class="form-control"
                                       name="adhar_front_img[]"
                                       accept=".jpg,.jpeg,.png,.pdf" />

                            </div>


                            <div class="col-md-4 mb-3">

                                <label>
                                    Adhar Back Image
                                </label>

                                <input type="file"
                                       class="form-control"
                                       name="adhar_back_img[]"
                                       accept=".jpg,.jpeg,.png,.pdf" />

                            </div>


                            <div class="col-md-4 mb-3">

                                <label>
                                    Pan Card Image
                                </label>

                                <input type="file"
                                       class="form-control"
                                       name="pan_img[]"
                                       accept=".jpg,.jpeg,.png,.pdf" />

                            </div>


                            <div class="col-md-4 mb-3">

                                <label>
                                    Cancel Cheque Image
                                </label>

                                <input type="file"
                                       class="form-control"
                                       name="cheque_img[]"
                                       accept=".jpg,.jpeg,.png,.pdf" />

                            </div>


                            <div class="col-md-4 mb-3">

                                <label>
                                    Photo
                                </label>

                                <input type="file"
                                       class="form-control"
                                       name="photo[]"
                                       accept=".jpg,.jpeg,.png,.pdf" />

                            </div>


                            <div class="col-md-4 mb-3">

                                <label>
                                    Other Document
                                </label>

                                <input type="file"
                                       class="form-control"
                                       name="other_document[]"
                                       accept=".jpg,.jpeg,.png,.pdf" />

                            </div>


                            <div class="col-4 mb-3">

                                <button type="button"
                                        class="btn btn-danger remove-btn"
                                        style="margin-top:30px;">

                                    Remove

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            `;


            document
                .getElementById("document-section")
                .insertAdjacentHTML(
                    "beforeend",
                    newRow
                );

        });


    /*
    |--------------------------------------------------------------------------
    | Remove Document Row
    |--------------------------------------------------------------------------
    */

    document
        .getElementById("document-section")
        .addEventListener(
            "click",
            function (event) {

                if (
                    event.target &&
                    event.target.classList.contains(
                        "remove-btn"
                    )
                ) {

                    const rowToRemove =
                        event.target.closest(
                            '[id^="document-row-"]'
                        );

                    if (rowToRemove) {

                        rowToRemove.remove();

                    }

                }

            }
        );


    /*
    |--------------------------------------------------------------------------
    | VIDEO KYC
    |--------------------------------------------------------------------------
    */

    let mediaRecorder;

    let stream;

    let chunks = [];

    let timingInterval;

    let startTime;


    /*
    | Start Camera
    */

    document
        .getElementById("startCameraButton")
        .addEventListener(
            "click",
            async function () {

                const constraints = {

                    video: true,

                    audio: true

                };


                try {

                    stream =
                        await navigator
                            .mediaDevices
                            .getUserMedia(
                                constraints
                            );


                    const video =
                        document
                            .getElementById("video");


                    video.srcObject =
                        stream;


                    video.onloadedmetadata =
                        function () {

                            video.play();

                        };


                } catch (err) {

                    console.error(
                        "Camera Error:",
                        err
                    );


                    Swal.fire({

                        icon: "error",

                        title: "Camera Access Error",

                        text:
                            "Please ensure that your camera and microphone are properly connected and permissions are granted."

                    });

                }

            }
        );


    /*
    | Start Recording
    */

    document
        .getElementById("startRecordingButton")
        .addEventListener(
            "click",
            async function () {

                try {

                    if (
                        mediaRecorder &&
                        mediaRecorder.state === "recording"
                    ) {

                        return;

                    }


                    if (!stream) {

                        Swal.fire({

                            icon: "warning",

                            title: "Camera Not Started",

                            text:
                                "Please start the camera first."

                        });

                        return;

                    }


                    const options = {

                        mimeType:
                            "video/webm; codecs=vp9"

                    };


                    mediaRecorder =
                        new MediaRecorder(
                            stream,
                            options
                        );


                    chunks = [];

                    startTime =
                        Date.now();


                    mediaRecorder.ondataavailable =
                        function (event) {

                            if (
                                event.data.size > 0
                            ) {

                                chunks.push(
                                    event.data
                                );

                            }

                        };


                    mediaRecorder.onstart =
                        function () {

                            updateTiming();

                            $("#startRecordingButton")
                                .css(
                                    "pointer-events",
                                    "none"
                                );

                        };


                    mediaRecorder.onstop =
                        function () {

                            clearInterval(
                                timingInterval
                            );

                        };


                    mediaRecorder.start();


                    setTimeout(
                        function () {

                            if (
                                mediaRecorder &&
                                mediaRecorder.state === "recording"
                            ) {

                                mediaRecorder.stop();


                                Swal.fire({

                                    icon: "success",

                                    title: "KYC Instructions",

                                    text:
                                        "Your video duration was 40 seconds. Make sure to speak your full Name, Aadhar, PAN, and GST clearly."

                                }).then(
                                    function () {

                                        $("#add_role_user")
                                            .modal("hide");


                                        const videoElement =
                                            document.getElementById(
                                                "video"
                                            );


                                        if (
                                            videoElement &&
                                            videoElement.srcObject
                                        ) {

                                            videoElement
                                                .srcObject
                                                .getTracks()
                                                .forEach(
                                                    function (track) {

                                                        track.stop();

                                                    }
                                                );


                                            videoElement.srcObject =
                                                null;

                                        }

                                    }
                                );

                            }

                        },
                        41000
                    );


                } catch (err) {

                    console.error(
                        "Recording Error:",
                        err
                    );

                }

            }
        );


    /*
    | Restart Recording
    */

    document
        .getElementById("restartRecordingButton")
        .addEventListener(
            "click",
            async function () {

                if (
                    mediaRecorder &&
                    mediaRecorder.state === "recording"
                ) {

                    mediaRecorder.stop();

                }


                if (stream) {

                    stream
                        .getTracks()
                        .forEach(
                            function (track) {

                                track.stop();

                            }
                        );

                }


                chunks = [];


                try {

                    stream =
                        await navigator
                            .mediaDevices
                            .getUserMedia({

                                video: true,

                                audio: true

                            });


                    const video =
                        document
                            .getElementById("video");


                    video.srcObject =
                        stream;


                    video.onloadedmetadata =
                        function () {

                            video.play();

                        };


                    const options = {

                        mimeType:
                            "video/webm; codecs=vp9"

                    };


                    mediaRecorder =
                        new MediaRecorder(
                            stream,
                            options
                        );


                    mediaRecorder.ondataavailable =
                        function (event) {

                            if (
                                event.data.size > 0
                            ) {

                                chunks.push(
                                    event.data
                                );

                            }

                        };


                    mediaRecorder.onstart =
                        function () {

                            updateTiming();

                        };


                    mediaRecorder.onstop =
                        function () {

                            clearInterval(
                                timingInterval
                            );

                        };


                    mediaRecorder.start();

                    startTime =
                        Date.now();


                    setTimeout(
                        function () {

                            if (
                                mediaRecorder &&
                                mediaRecorder.state === "recording"
                            ) {

                                mediaRecorder.stop();


                                Swal.fire({

                                    icon: "success",

                                    title: "KYC Instructions",

                                    text:
                                        "Your video duration was 40 seconds. Make sure to speak your full Name, Aadhar, PAN, and GST clearly."

                                });

                            }

                        },
                        41000
                    );


                } catch (err) {

                    console.error(
                        "Restart Error:",
                        err
                    );


                    Swal.fire({

                        icon: "error",

                        title: "Restart Error",

                        text:
                            "Could not restart recording. Please check your permissions."

                    });

                }

            }
        );


    /*
    | Stop Camera
    */

    function stopRecordingAndStream() {

        if (
            mediaRecorder &&
            mediaRecorder.state === "recording"
        ) {

            mediaRecorder.stop();

        }


        if (stream) {

            stream
                .getTracks()
                .forEach(
                    function (track) {

                        track.stop();

                    }
                );

        }

    }


    /*
    | Close Video Modal
    */

    document
        .getElementById("modalCloseButton")
        .addEventListener(
            "click",
            function () {

                stopRecordingAndStream();

            }
        );


    /*
    |--------------------------------------------------------------------------
    | ADMIN FORM SUBMIT
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Public form me AJAX + OTP tha.
    | Admin form me OTP nahi hai.
    |
    | Isliye yahan normal POST hone diya ja raha hai.
    | Sirf recorded video ko form me add kar rahe hain.
    |--------------------------------------------------------------------------
    */

    document
        .getElementById("franchiseForm")
        .addEventListener(
            "submit",
            function (event) {

                /*
                 * Browser validation
                 */

                if (!this.checkValidity()) {

                    event.preventDefault();

                    event.stopPropagation();

                    this.classList.add("was-validated");

                    return;

                }


                /*
                 * Agreement
                 */

                if (
                    !$("#agreementCheck").is(":checked") ||
                    !$("#privacyCheck").is(":checked")
                ) {

                    event.preventDefault();

                    Swal.fire({

                        icon: "warning",

                        title: "Agreement Required",

                        text:
                            "Please agree to Agreement and Terms & Conditions before submitting."

                    });

                    return;

                }


                /*
                 * CPH
                 */

                const cphValue =
                    document.querySelector(
                        'select[name="cph_link"]'
                    ).value;


                if (!cphValue) {

                    event.preventDefault();

                    Swal.fire({

                        icon: "warning",

                        title: "CPH Not Available",

                        text:
                            "No CPH is available in this city. Please choose another city."

                    });

                    return;

                }


                /*
                 * Add Video Blob
                 */

                if (chunks.length > 0) {

                    const blob =
                        new Blob(
                            chunks,
                            {
                                type: "video/webm"
                            }
                        );


                    /*
                     * Hidden file input create
                     */

                    const oldInput =
                        document.getElementById(
                            "video_kyc_file"
                        );


                    if (oldInput) {

                        oldInput.remove();

                    }


                    const dataTransfer =
                        new DataTransfer();


                    const videoFile =
                        new File(
                            [blob],
                            "recording.webm",
                            {
                                type:
                                    "video/webm"
                            }
                        );


                    dataTransfer.items.add(
                        videoFile
                    );


                    const input =
                        document.createElement(
                            "input"
                        );


                    input.type = "file";

                    input.name = "video_kyc";

                    input.id =
                        "video_kyc_file";

                    input.style.display =
                        "none";

                    input.files =
                        dataTransfer.files;


                    this.appendChild(input);

                }


                /*
                 * Show loader
                 */

                $("#submitBtn")
                    .prop(
                        "disabled",
                        true
                    )
                    .html(
                        '<i class="fa fa-spinner fa-spin"></i> Saving...'
                    );


                /*
                 * IMPORTANT:
                 * event.preventDefault() nahi karna.
                 * Form normal POST hoga.
                 */

            }
        );


    /*
    |--------------------------------------------------------------------------
    | Timer
    |--------------------------------------------------------------------------
    */

    function updateTiming() {

        clearInterval(
            timingInterval
        );


        timingInterval =
            setInterval(
                function () {

                    const elapsed =
                        Date.now() -
                        startTime;


                    const minutes =
                        Math.floor(
                            elapsed / 60000
                        );


                    const seconds =
                        Math.floor(
                            (elapsed % 60000) / 1000
                        );


                    document
                        .getElementById(
                            "timing"
                        )
                        .textContent =
                        `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;

                },
                1000
            );

    }

</script>


<!-- SweetAlert Messages -->

@if(Session::has('success'))

<script>

    Swal.fire(
        "Yay!!!",
        @json(Session::get('success')),
        "success"
    );

</script>

@endif


@if(Session::has('error'))

<script>

    Swal.fire(
        "Oops!!!",
        @json(Session::get('error')),
        "error"
    );

</script>

@endif


</body>
</html>
```
