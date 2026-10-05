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

    <meta name="description" content="Sales Marketing Manager Management" />

    <meta name="author" content="Admin" />

    <title>Sales Marketing Manager | Create</title>

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
            animation:
                prixClipFix 2s linear infinite,
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

            .account-page .main-wrapper .account-content .account-box .account-wrapper {
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

        .card-header {
            background: #f62d51 !important;
        }

        .card-title {
            color: #fff !important;
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

<div class="main-wrapper">

    <div class="account-content">

        <div class="container">

            <!-- Logo -->
            <div class="account-logo">
                <a href="#">
                    <img src="{{ asset('website/images/logonewtransparent.png') }}"
                         alt="IndiaPost" />
                </a>
            </div>

            <!-- Account Box -->
            <div class="account-box">

                <div class="account-wrapper">

                    <h4 class="account-title text-center"
                        style="font-size: 22px;">
                        Create Sales Marketing Manager
                    </h4>

                    <p class="text-center mb-3"
                       style="font-size: 17px;color:#f62d51 !important">
                        Commission-Based Sales Marketing Manager
                    </p>

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="card">

                                <!-- Loader -->
                                <div id="overlay" class="overlay hidden">
                                    <div class="loader"></div>
                                </div>

                                <!-- FORM -->

                                <form id="mManagerForm"
                                      action="{{ route('admin.m_manager.store') }}"
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

                                            <!-- Full Name -->
                                            <div class="col-md-4 mb-3">

                                                <label for="name">
                                                    Full Name
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <div class="input-group">

                                                    <span class="input-group-text">
                                                        <i class="fa fa-user"></i>
                                                    </span>

                                                    <input type="text"
                                                           class="form-control @error('name') is-invalid @enderror"
                                                           id="name"
                                                           name="name"
                                                           value="{{ old('name') }}"
                                                           placeholder="Enter full name"
                                                           required>

                                                    <div class="invalid-feedback">
                                                        Please provide full name
                                                    </div>

                                                </div>

                                            </div>

                                            <!-- Father/Husband -->
                                            <div class="col-md-4 mb-3">

                                                <label for="father_name">
                                                    Father/Husband Name
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
                                                           placeholder="Father/Husband Name"
                                                           required>

                                                    <div class="invalid-feedback">
                                                        Please provide Father/Husband Name
                                                    </div>

                                                </div>

                                            </div>

                                            <!-- Mobile -->
                                            <div class="col-md-4 mb-3">

                                                <label for="mobile">
                                                    Mobile
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <div class="input-group">

                                                    <span class="input-group-text">
                                                        <i class="fa fa-mobile"></i>
                                                    </span>

                                                    <input type="text"
                                                           class="form-control NumberValidate @error('mobile') is-invalid @enderror"
                                                           maxlength="10"
                                                           id="mobile"
                                                           name="mobile"
                                                           value="{{ old('mobile') }}"
                                                           placeholder="Mobile"
                                                           required>

                                                    <div class="invalid-feedback">
                                                        Please provide mobile number
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

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
                                                           value="{{ old('email') }}"
                                                           placeholder="Email ID"
                                                           required>

                                                    <div class="invalid-feedback">
                                                        Please provide email
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
                                                        <i class="fa fa-map-marker"></i>
                                                    </span>

                                                    <input type="text"
                                                           class="form-control NumberValidate @error('pincode') is-invalid @enderror"
                                                           maxlength="6"
                                                           id="pincode"
                                                           name="pincode"
                                                           value="{{ old('pincode') }}"
                                                           placeholder="Pincode"
                                                           required>

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
                                                           value="{{ old('city') }}"
                                                           placeholder="City"
                                                           required>

                                                    <div class="invalid-feedback">
                                                        Please provide city
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

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
                                                           required>

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
                                                        <i class="fa fa-map"></i>
                                                    </span>

                                                    <input type="text"
                                                           readonly
                                                           class="form-control @error('state') is-invalid @enderror"
                                                           id="state"
                                                           name="state"
                                                           value="{{ old('state') }}"
                                                           required>

                                                </div>

                                            </div>

                                            <!-- Address -->
                                            <div class="col-md-4 mb-3">

                                                <label for="address">
                                                    Residential/Office Address
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <textarea class="form-control @error('address') is-invalid @enderror"
                                                          id="address"
                                                          name="address"
                                                          placeholder="Type address..."
                                                          required>{{ old('address') }}</textarea>

                                                <input type="hidden"
                                                       name="latitude"
                                                       value="{{ old('latitude') }}"
                                                       id="latitude">

                                                <input type="hidden"
                                                       name="longitude"
                                                       value="{{ old('longitude') }}"
                                                       id="longitude">

                                            </div>

                                        </div>

                                        <div class="row">

                                            <!-- Nationality -->
                                            <div class="col-md-4 mb-3">

                                                <label for="natality">
                                                    Nationality
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select class="form-select @error('natality') is-invalid @enderror"
                                                        name="natality"
                                                        id="natality"
                                                        required>

                                                    <option value="india"
                                                        {{ old('natality', 'india') == 'india' ? 'selected' : '' }}>
                                                        India
                                                    </option>

                                                </select>

                                            </div>

                                            <!-- Age -->
                                            <div class="col-md-4 mb-3">

                                                <label for="age">
                                                    Age
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="number"
                                                       class="form-control @error('age') is-invalid @enderror"
                                                       id="age"
                                                       name="age"
                                                       value="{{ old('age') }}"
                                                       placeholder="Age"
                                                       min="18"
                                                       required>

                                            </div>

                                            <!-- Gender -->
                                            <div class="col-md-4 mb-3">

                                                <label for="gender">
                                                    Gender
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select class="form-select @error('gender') is-invalid @enderror"
                                                        name="gender"
                                                        id="gender"
                                                        required>

                                                    <option value="" disabled
                                                        {{ old('gender') ? '' : 'selected' }}>
                                                        Select Gender
                                                    </option>

                                                    <option value="male"
                                                        {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                        Male
                                                    </option>

                                                    <option value="female"
                                                        {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                        Female
                                                    </option>

                                                    <option value="other"
                                                        {{ old('gender') == 'other' ? 'selected' : '' }}>
                                                        Other
                                                    </option>

                                                </select>

                                            </div>

                                        </div>

                                        <!-- Hidden Generated ID -->
                                        <input type="hidden"
                                               name="generated_id"
                                               id="generated_id"
                                               value="{{ old('generated_id') }}">

                                    </div>


                                    <!-- ================= BUSINESS ASSOCIATE ================= -->

                                    <div class="card-header">

                                        <h5 class="card-title mb-0">
                                            Business Associate Details
                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-4 mb-3">

                                                <label for="franchise_id">
                                                    Link:- Office Near By Business Associate
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select name="franchise_id"
                                                        id="franchise_id"
                                                        class="form-control location_dropdown"
                                                        required>

                                                    <option value="">
                                                        Please Select Business Associate
                                                    </option>

                                                </select>

                                                <div class="invalid-feedback">
                                                    Please select Business Associate
                                                </div>

                                            </div>

                                            <div class="col-md-4 mb-3">

                                                <label for="register_type">
                                                    Register Type
                                                </label>

                                                <select name="register_type"
                                                        id="register_type"
                                                        class="form-control">

                                                    <option value="m_manager"
                                                        {{ old('register_type', 'm_manager') == 'm_manager' ? 'selected' : '' }}>
                                                        Sales Marketing Manager
                                                    </option>

                                                </select>

                                            </div>

                                            <div class="col-md-4 mb-3">

                                                <label for="status">
                                                    Status
                                                </label>

                                                <select name="status"
                                                        id="status"
                                                        class="form-control">

                                                    <option value="1"
                                                        {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                                        Active
                                                    </option>

                                                    <option value="0"
                                                        {{ old('status') === '0' ? 'selected' : '' }}>
                                                        Inactive
                                                    </option>

                                                </select>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- ================= EDUCATION ================= -->

                                    <div class="card-header">

                                        <h5 class="card-title mb-0">
                                            Education Details
                                            <small class="text-white">
                                                (Registration for Graduates Only)
                                            </small>
                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- College -->
                                            <div class="col-md-3 mb-3">

                                                <label for="collage">
                                                    College Name
                                                </label>

                                                <input type="text"
                                                       class="form-control"
                                                       id="collage"
                                                       name="collage"
                                                       value="{{ old('collage') }}"
                                                       placeholder="College Name">

                                            </div>

                                            <!-- Marksheet Type -->
                                            <div class="col-md-3 mb-3">

                                                <label for="marksheetType">
                                                    Grade / Percentage
                                                </label>

                                                <select class="marksheetType form-select"
                                                        name="marksheetType"
                                                        id="marksheetType">

                                                    <option value="">
                                                        Select Option
                                                    </option>

                                                    <option value="grade"
                                                        {{ old('marksheetType') == 'grade' ? 'selected' : '' }}>
                                                        Grade
                                                    </option>

                                                    <option value="percentage"
                                                        {{ old('marksheetType') == 'percentage' ? 'selected' : '' }}>
                                                        Percentage
                                                    </option>

                                                </select>

                                            </div>

                                            <!-- Grade -->
                                            <div class="col-md-3 mb-3 gradeDiv"
                                                 style="{{ old('marksheetType') == 'percentage' ? 'display:none' : '' }}">

                                                <label for="grade">
                                                    Grade
                                                </label>

                                                <input type="text"
                                                       class="form-control"
                                                       id="grade"
                                                       name="grade"
                                                       value="{{ old('grade') }}"
                                                       placeholder="Grade">

                                            </div>

                                            <!-- Percentage -->
                                            <div class="col-md-3 mb-3 percentageDiv"
                                                 style="{{ old('marksheetType') == 'percentage' ? '' : 'display:none' }}">

                                                <label for="percentage">
                                                    Percentage
                                                </label>

                                                <input type="number"
                                                       step="0.01"
                                                       class="form-control"
                                                       id="percentage"
                                                       name="percentage"
                                                       value="{{ old('percentage') }}"
                                                       placeholder="Percentage %">

                                            </div>

                                            <!-- Marksheet -->
                                            <div class="col-md-3 mb-3">

                                                <label for="marksheet">
                                                    Marksheet
                                                </label>

                                                <input type="file"
                                                       class="form-control"
                                                       id="marksheet"
                                                       name="marksheet"
                                                       accept=".jpg,.jpeg,.png,.pdf">

                                            </div>

                                        </div>

                                    </div>


                                    <!-- ================= EXPERIENCE ================= -->

                                    <div class="card-header">

                                        <h5 class="card-title mb-0">
                                            Experience Details
                                            <small class="text-white">
                                                (Minimum One Year of Experience)
                                            </small>
                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Experience -->
                                            <div class="col-md-3 mb-3">

                                                <label for="experience">
                                                    Total Experience
                                                </label>

                                                <input type="number"
                                                       class="form-control"
                                                       id="experience"
                                                       name="experience"
                                                       value="{{ old('experience') }}"
                                                       placeholder="Total Experience">

                                            </div>

                                            <!-- Start Date -->
                                            <div class="col-md-3 mb-3">

                                                <label for="start_date">
                                                    Experience Start Date
                                                </label>

                                                <input type="date"
                                                       class="form-control"
                                                       id="start_date"
                                                       name="start_date"
                                                       value="{{ old('start_date') }}">

                                            </div>

                                            <!-- End Date -->
                                            <div class="col-md-3 mb-3">

                                                <label for="end_date">
                                                    Experience End Date
                                                </label>

                                                <input type="date"
                                                       class="form-control"
                                                       id="end_date"
                                                       name="end_date"
                                                       value="{{ old('end_date') }}">

                                            </div>

                                            <!-- Resume -->
                                            <div class="col-md-3 mb-3">

                                                <label for="resume">
                                                    Resume
                                                </label>

                                                <input type="file"
                                                       class="form-control"
                                                       id="resume"
                                                       name="resume"
                                                       accept=".jpg,.jpeg,.png,.pdf">

                                            </div>

                                        </div>

                                    </div>


                                    <!-- ================= DOCUMENTS ================= -->

                                    <div class="card-header">

                                        <h5 class="card-title mb-0">
                                            Upload Documents
                                            <small class="text-white">
                                                (Image size upto 1 MB)
                                            </small>
                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Aadhar Front -->
                                            <div class="col-md-4 mb-3">

                                                <label for="adhar_front_img">
                                                    Aadhar Front Image
                                                </label>

                                                <input type="file"
                                                       class="form-control"
                                                       id="adhar_front_img"
                                                       name="adhar_front_img"
                                                       accept=".jpg,.jpeg,.png,.pdf">

                                            </div>

                                            <!-- Aadhar Back -->
                                            <div class="col-md-4 mb-3">

                                                <label for="adhar_back_img">
                                                    Aadhar Back Image
                                                </label>

                                                <input type="file"
                                                       class="form-control"
                                                       id="adhar_back_img"
                                                       name="adhar_back_img"
                                                       accept=".jpg,.jpeg,.png,.pdf">

                                            </div>

                                            <!-- PAN -->
                                            <div class="col-md-4 mb-3">

                                                <label for="pan_img">
                                                    PAN Card Image
                                                </label>

                                                <input type="file"
                                                       class="form-control"
                                                       id="pan_img"
                                                       name="pan_img"
                                                       accept=".jpg,.jpeg,.png,.pdf">

                                            </div>

                                            <!-- Cheque -->
                                            <div class="col-md-4 mb-3">

                                                <label for="cheque_img">
                                                    Cancel Cheque Image
                                                </label>

                                                <input type="file"
                                                       class="form-control"
                                                       id="cheque_img"
                                                       name="cheque_img"
                                                       accept=".jpg,.jpeg,.png,.pdf">

                                            </div>

                                            <!-- Photo -->
                                            <div class="col-md-4 mb-3">

                                                <label for="photo">
                                                    Photo
                                                </label>

                                                <input type="file"
                                                       class="form-control"
                                                       id="photo"
                                                       name="photo"
                                                       accept=".jpg,.jpeg,.png,.pdf">

                                            </div>

                                            <!-- Other -->
                                            <div class="col-md-4 mb-3">

                                                <label for="other_document">
                                                    Other Document
                                                </label>

                                                <input type="file"
                                                       class="form-control"
                                                       id="other_document"
                                                       name="other_document"
                                                       accept=".jpg,.jpeg,.png,.pdf">

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

                                            <!-- Aadhar Number -->
                                            <div class="col-md-4 mb-3">

                                                <label for="adhar_card">
                                                    Aadhar Number
                                                </label>

                                                <div class="input-group">

                                                    <span class="input-group-text">
                                                        <i class="fa fa-user"></i>
                                                    </span>

                                                    <input type="text"
                                                           class="form-control NumberValidate"
                                                           maxlength="12"
                                                           id="adhar_card"
                                                           name="adhar_card"
                                                           value="{{ old('adhar_card') }}"
                                                           placeholder="Aadhar Card Number">

                                                </div>

                                            </div>

                                            <!-- PAN Number -->
                                            <div class="col-md-4 mb-3">

                                                <label for="pan_card">
                                                    PAN Number
                                                </label>

                                                <div class="input-group">

                                                    <span class="input-group-text">
                                                        <i class="fa fa-id-card"></i>
                                                    </span>

                                                    <input type="text"
                                                           class="form-control"
                                                           id="pan_card"
                                                           name="pan_card"
                                                           value="{{ old('pan_card') }}"
                                                           placeholder="PAN Card Number"
                                                           onkeyup="return forceUpper(this);">

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
                                                           class="form-control"
                                                           maxlength="11"
                                                           id="ifsc_code"
                                                           name="ifsc_code"
                                                           value="{{ old('ifsc_code') }}"
                                                           placeholder="IFSC Code"
                                                           onkeyup="return forceUpper(this);">

                                                </div>

                                                <div class="text-danger"
                                                     id="bank_ifsc_error">
                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <!-- Bank -->
                                            <div class="col-md-4 mb-3">

                                                <label for="bank_name">
                                                    Bank Name
                                                </label>

                                                <input type="text"
                                                       class="form-control"
                                                       readonly
                                                       id="bank_name"
                                                       name="bank_name"
                                                       value="{{ old('bank_name') }}"
                                                       placeholder="Auto detect after IFSC">

                                            </div>

                                            <!-- Branch -->
                                            <div class="col-md-4 mb-3">

                                                <label for="branch_name">
                                                    Branch Name
                                                </label>

                                                <input type="text"
                                                       class="form-control"
                                                       readonly
                                                       id="branch_name"
                                                       name="branch_name"
                                                       value="{{ old('branch_name') }}"
                                                       placeholder="Auto detect after IFSC">

                                            </div>

                                            <!-- Account -->
                                            <div class="col-md-4 mb-3">

                                                <label for="account_number">
                                                    Account Number
                                                </label>

                                                <input type="text"
                                                       class="form-control NumberValidate"
                                                       maxlength="16"
                                                       id="account_number"
                                                       name="account_number"
                                                       value="{{ old('account_number') }}"
                                                       placeholder="Account Number">

                                            </div>

                                        </div>

                                        <div class="row">

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
                                                       value="Start Recording">

                                            </div>

                                        </div>

                                    </div>


                                    <!-- ================= PAYMENT STATUS ================= -->

                                    <div class="card-header">

                                        <h5 class="card-title mb-0">
                                            Payment Details
                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-4 mb-3">

                                                <label for="payment_status">
                                                    Payment Status
                                                </label>

                                                <select name="payment_status"
                                                        id="payment_status"
                                                        class="form-control">

                                                    <option value="0"
                                                        {{ old('payment_status', 0) == 0 ? 'selected' : '' }}>
                                                        Pending
                                                    </option>

                                                    <option value="1"
                                                        {{ old('payment_status') == 1 ? 'selected' : '' }}>
                                                        Paid
                                                    </option>

                                                </select>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- ================= AGREEMENT ================= -->

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-sm-12">

                                                <div class="input-block mb-3">

                                                    <div class="form-check">

                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               value="1"
                                                               id="agreementCheck"
                                                               required>

                                                        <label class="form-check-label"
                                                               for="agreementCheck">

                                                            <a href="{{ route('website.agreement') }}"
                                                               target="_blank"
                                                               style="color: black;">
                                                                Agreement
                                                            </a>

                                                            <i class="la la-info-circle"
                                                               style="font-size: 1.2rem;color:#0d6efd;">
                                                            </i>

                                                        </label>

                                                        <div class="invalid-feedback">
                                                            You must agree before submitting.
                                                        </div>

                                                    </div>


                                                    <div class="form-check mt-2">

                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               value="1"
                                                               id="privacyCheck"
                                                               required>

                                                        <label class="form-check-label"
                                                               for="privacyCheck">

                                                            <a href="{{ route('website.privacy-policy') }}"
                                                               target="_blank"
                                                               style="color: black;">
                                                                Agree to terms and conditions
                                                            </a>

                                                            <i class="la la-info-circle"
                                                               style="font-size: 1.2rem;color:#0d6efd;">
                                                            </i>

                                                        </label>

                                                        <div class="invalid-feedback">
                                                            You must agree before submitting.
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        <div class="text-center">

                                            <button class="btn"
                                                    type="submit"
                                                    style="background:#f62d51 !important;color:#fff;">

                                                Save Sales Marketing Manager

                                            </button>

                                            <a href="{{ route('admin.m_manager.index') }}"
                                               class="btn btn-secondary ms-2">

                                                Cancel

                                            </a>

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

                        PLEASE CLEARLY SAY YOUR FULL NAME, AADHAR NUMBER, PAN CARD NUMBER ON CAMERA.
                        <br>

                        कृपया कैमरे के सामने स्पष्ट रूप से अपना पूरा नाम, आधार नंबर, पैन नंबर बोलें।

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


<!-- ================= SCRIPTS ================= -->

<script src="{{ asset('admin/assets/js/jquery-3.7.0.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/jquery.slimscroll.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/moment.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/bootstrap-datetimepicker.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/app.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<script>

    // =====================================================
    // VIDEO KYC
    // =====================================================

    let mediaRecorder;
    let stream;
    let chunks = [];
    let timingInterval;
    let startTime;


    // Start Camera

    document.getElementById("startCameraButton")
        ?.addEventListener("click", async function () {

            try {

                stream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: true
                });

                const video = document.getElementById("video");

                video.srcObject = stream;

                video.onloadedmetadata = function () {
                    video.play();
                };

            } catch (err) {

                console.error(err);

                Swal.fire({
                    icon: "error",
                    title: "Camera Access Error",
                    text: "Please allow camera and microphone permission."
                });

            }

        });


    // Start Recording

    document.getElementById("startRecordingButton")
        ?.addEventListener("click", function () {

            try {

                if (mediaRecorder &&
                    mediaRecorder.state === "recording") {

                    return;

                }

                if (!stream) {

                    Swal.fire({
                        icon: "warning",
                        title: "Camera Not Started",
                        text: "Please start camera first."
                    });

                    return;

                }

                const options = {
                    mimeType: "video/webm; codecs=vp9"
                };

                mediaRecorder = new MediaRecorder(
                    stream,
                    options
                );

                chunks = [];

                startTime = Date.now();


                mediaRecorder.ondataavailable =
                    function (event) {

                        if (event.data.size > 0) {
                            chunks.push(event.data);
                        }

                    };


                mediaRecorder.onstart =
                    function () {

                        updateTiming();

                        $("#startRecordingButton")
                            .css("pointer-events", "none");

                    };


                mediaRecorder.onstop =
                    function () {

                        clearInterval(timingInterval);

                    };


                mediaRecorder.start();


                setTimeout(function () {

                    if (mediaRecorder &&
                        mediaRecorder.state === "recording") {

                        mediaRecorder.stop();

                        Swal.fire({
                            icon: "success",
                            title: "Recording Complete",
                            text: "Video KYC recorded successfully."
                        });

                    }

                }, 40000);


            } catch (err) {

                console.error(err);

            }

        });


    // Restart Recording

    document.getElementById("restartRecordingButton")
        ?.addEventListener("click", async function () {

            if (mediaRecorder &&
                mediaRecorder.state === "recording") {

                mediaRecorder.stop();

            }

            if (stream) {

                stream.getTracks().forEach(
                    track => track.stop()
                );

            }

            chunks = [];

            try {

                stream =
                    await navigator.mediaDevices.getUserMedia({
                        video: true,
                        audio: true
                    });

                const video =
                    document.getElementById("video");

                video.srcObject = stream;

                video.onloadedmetadata = function () {
                    video.play();
                };

                const options = {
                    mimeType: "video/webm; codecs=vp9"
                };

                mediaRecorder =
                    new MediaRecorder(
                        stream,
                        options
                    );

                mediaRecorder.ondataavailable =
                    function (event) {

                        if (event.data.size > 0) {
                            chunks.push(event.data);
                        }

                    };

                mediaRecorder.onstart =
                    function () {

                        updateTiming();

                        $("#startRecordingButton")
                            .css("pointer-events", "none");

                    };

                mediaRecorder.onstop =
                    function () {

                        clearInterval(timingInterval);

                    };

                mediaRecorder.start();

                startTime = Date.now();

            } catch (err) {

                console.error(err);

            }

        });


    // Timing

    function updateTiming() {

        timingInterval = setInterval(function () {

            const elapsed =
                Date.now() - startTime;

            const seconds =
                Math.floor(
                    (elapsed % 60000) / 1000
                );

            document.getElementById("timing")
                .textContent =
                `00:${String(seconds).padStart(2, "0")}`;

        }, 1000);

    }


    // Stop Recording

    function stopRecordingAndStream() {

        if (mediaRecorder &&
            mediaRecorder.state === "recording") {

            mediaRecorder.stop();

        }

        if (stream) {

            stream.getTracks().forEach(
                track => track.stop()
            );

        }

    }


    // =====================================================
    // PINCODE AJAX
    // =====================================================

    $("#pincode").on("change", function () {

        let pincode = $(this).val();

        if (pincode.length !== 6) {
            return;
        }

        $.ajax({

            type: "GET",

            url:
                "{{ url('admin/city-state') }}/"
                + pincode
                + "?type=m_manager",

            success: function (data) {

                if (data.success === true) {

                    $("#district")
                        .val(data.district);

                    $("#state")
                        .val(data.state);

                    $("#generated_id")
                        .val(data.generated_id);

                } else {

                    $(".validerror")
                        .text("Please enter valid pincode");

                }

            },

            error: function () {

                $(".validerror")
                    .text("Unable to fetch pincode details.");

            }

        });

    });


    // =====================================================
    // FORCE UPPERCASE
    // =====================================================

    function forceUpper(strInput) {

        strInput.value =
            strInput.value.toUpperCase();

    }


    // =====================================================
    // IFSC AJAX
    // =====================================================

    var bankIfsc =
        $("#ifsc_code");

    var bankIfscError =
        $("#bank_ifsc_error");

    var bankName =
        $("#bank_name");

    var bankBranch =
        $("#branch_name");


    bankIfsc.on("input", function () {

        var ifscCode =
            bankIfsc.val().trim().toUpperCase();

        bankIfsc.val(ifscCode);


        if (ifscCode.length === 11) {

            $.ajax({

                url:
                    "https://ifsc.razorpay.com/"
                    + ifscCode,

                success: function (res) {

                    bankName.val(res.BANK);

                    bankBranch.val(res.BRANCH);

                    bankIfscError.html("");

                },

                error: function () {

                    bankName.val("");

                    bankBranch.val("");

                    bankIfscError.html(
                        "Please enter a valid IFSC Code"
                    );

                }

            });

        } else {

            bankName.val("");

            bankBranch.val("");

            bankIfscError.html("");

        }

    });


    // =====================================================
    // LOCATION BY CITY
    // =====================================================

    $(document).ready(function () {

        $("#city").on("keyup", function () {

            let city =
                $(this).val();

            if (city.length < 2) {
                return;
            }

            var csrfToken =
                "{{ csrf_token() }}";


            $.ajax({

                url:
                    "{{ route('customer.get.location') }}",

                type: "POST",

                data: {

                    city: city,

                    _token: csrfToken

                },

                success: function (response) {

                    $(".location_dropdown")
                        .html(response);

                    $(".location_col")
                        .show();

                },

                error: function (xhr) {

                    console.log(
                        xhr.responseText
                    );

                }

            });

        });

    });


    // =====================================================
    // MARKSHEET TYPE
    // =====================================================

    $(".marksheetType").on("change", function () {

        let select =
            $(this).val();

        if (select === "grade") {

            $(".gradeDiv").show();

            $(".percentageDiv").hide();

        } else if (select === "percentage") {

            $(".gradeDiv").hide();

            $(".percentageDiv").show();

        } else {

            $(".gradeDiv").show();

            $(".percentageDiv").hide();

        }

    });


    // =====================================================
    // FORM SUBMIT
    // =====================================================

    document
        .getElementById("mManagerForm")
        .addEventListener("submit", function (event) {

            event.preventDefault();


            // Business Associate

            const franchiseValue =
                document.querySelector(
                    'select[name="franchise_id"]'
                )?.value;


            if (!franchiseValue) {

                Swal.fire({

                    icon: "warning",

                    title: "Business Associate Required",

                    text:
                        "Please select a nearby Business Associate.",

                    confirmButtonColor:
                        "#f62d51"

                });

                return;

            }


            // Agreement

            if (!$("#agreementCheck").is(":checked")) {

                Swal.fire({

                    icon: "warning",

                    title: "Agreement Required",

                    text:
                        "Please accept the agreement.",

                    confirmButtonColor:
                        "#f62d51"

                });

                return;

            }


            // Privacy

            if (!$("#privacyCheck").is(":checked")) {

                Swal.fire({

                    icon: "warning",

                    title: "Terms Required",

                    text:
                        "Please accept terms and conditions.",

                    confirmButtonColor:
                        "#f62d51"

                });

                return;

            }


            // Form Data

            const formData =
                new FormData(this);


            // Add Video KYC

            if (chunks.length > 0) {

                const blob =
                    new Blob(
                        chunks,
                        {
                            type: "video/webm"
                        }
                    );

                formData.append(
                    "video_kyc",
                    blob,
                    "recording.webm"
                );

            }


            // Loader

            Swal.fire({

                title: "Processing...",

                text:
                    "Sales Marketing Manager is being created.",

                allowOutsideClick: false,

                didOpen: function () {

                    Swal.showLoading();

                }

            });


            // AJAX Submit

            $.ajax({

                url:
                    $(this).attr("action"),

                type: "POST",

                data: formData,

                processData: false,

                contentType: false,


            success: function (response) {

    Swal.close();

    if (response.success) {

        Swal.fire({

            icon: "success",

            title: "Registration Successful!",

            html: `
                <div style="text-align:left;padding:15px;background:#f8f9fa;border-radius:8px;">

                    <p style="font-size:16px;">

                        <strong>Name:</strong>
                        ${response.data?.name ?? ''}

                        <br>

                        <strong>Username:</strong>
                        ${response.data?.username ?? response.data?.generated_id ?? ''}

                        <br>

                        <strong>Mobile:</strong>
                        ${response.data?.mobile ?? ''}

                    </p>

                    <div style="background:#d1e7dd;padding:10px;margin-top:10px;">
                        Manager created successfully.
                    </div>

                </div>
            `,

            confirmButtonText: "Go to Manager List",

            confirmButtonColor: "#f62d51",

            allowOutsideClick: false

        }).then(function () {

            // Successfully created ke baad
            // admin/m-manager/index par redirect
            window.location.href = "{{ url('admin/m-manager/index') }}";

        });

        chunks = [];

        const video = document.getElementById("video");

        if (video?.srcObject) {

            video.srcObject
                .getTracks()
                .forEach(track => track.stop());

            video.srcObject = null;
        }

    } else {

        Swal.fire({

            icon: "success",

            title: "Success",

            text: response.message ??
                "Manager created successfully.",

            confirmButtonColor: "#f62d51"

        }).then(function () {

            window.location.href =
                "{{ url('admin/m-manager/index') }}";

        });

    }

},

                error: function (jqXHR) {

                    Swal.close();


                    let errorMsg =
                        "Manager creation failed. Please try again.";


                    if (
                        jqXHR.responseJSON?.message
                    ) {

                        errorMsg =
                            jqXHR.responseJSON.message;

                    } else if (
                        jqXHR.responseJSON?.errors
                    ) {

                        errorMsg =
                            Object.values(
                                jqXHR.responseJSON.errors
                            )
                            .flat()
                            .join("\n");

                    }


                    Swal.fire({

                        icon: "error",

                        title:
                            "Creation Failed!",

                        text: errorMsg,

                        confirmButtonText:
                            "OK",

                        confirmButtonColor:
                            "#f62d51"

                    });

                }

            });

        });


    // =====================================================
    // MODAL CLOSE
    // =====================================================

    document
        .getElementById("modalCloseButton")
        ?.addEventListener("click", function () {

            stopRecordingAndStream();

        });

</script>

</body>
</html>