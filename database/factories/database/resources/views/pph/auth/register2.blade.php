<!DOCTYPE html>

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

    <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin/assets/img/favicon.png')}}" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/select2.min.css')}}">
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
                    <a href="#"><img src="{{asset('website/images/logonewtransparent.png')}}" alt="IndiaPost" /></a>
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
                                    <form id="franchiseForm" action="{{route('deliveryBoy.register')}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
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
                                                            <label for="name">Name <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-user"></i></span>

                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{old('name')}}" required />

                                                                <div class="invalid-feedback">
                                                                    Please provide your name
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-4 mb-3">
                                                            <label for="father_name">Father/Husband Name <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-user"></i></span>

                                                                <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{old('father_name')}}" placeholder="Father/Husband Name" required />

                                                                <div class="invalid-feedback">
                                                                    Please provide father/husband name
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="mobile">Mobile <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                <input type="text" class="form-control NumberValidate @error('mobile') is-invalid @enderror" maxlength="10" id="mobile" name="mobile" value="{{old('mobile')}}" placeholder="Mobile." required />

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

                                                                <input type="text" class="form-control NumberValidate @error('pincode') is-invalid @enderror" maxlength="6" id="pincode" name="pincode" value="{{old('pincode')}}" placeholder="Pincode." required />

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
                                                            <label for="address">Address <span class="text-danger">*</span></label>

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
                                                            <label for="generated_id">Generated ID</label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                                                <input type="text" class="form-control @error('generated_id') is-invalid @enderror" id="generated_id" readonly value="{{old('generated_id')}}" name="generated_id" placeholder="Auto generated" required />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="adhar_card">Aadhar Number</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                                <input type="text" class="form-control NumberValidate @error('adhar_card') is-invalid @enderror" maxlength="12" id="adhar_card" name="adhar_card" value="{{old('adhar_card')}}" placeholder="Adhar Card Number..">

                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="generated_id">Select Nearest to your franchise/Hub</label>
                                                            <div class="input-group">
                                                                <select class="select-my" id="status" name="franchise_id" required>
                                                                    @foreach($franchise as $data)
                                                                    <option value="{{ $data->id }}">
                                                                        {{ $data->city ? $data->city : $data->pincode }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="age">AGE <span class="text-danger">*</span></label>

                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                                                <input type="text" class="form-control NumberValidate @error('age') is-invalid @enderror" maxlength="10" id="age" name="age" value="{{old('age')}}" placeholder="age." required />

                                                                <div class="invalid-feedback">
                                                                    Please provide age
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <label for="generated_id">Gender</label>
                                                            <div class="input-group">
                                                                <select class="select-my" @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                                                    <option value="">Select Gender</option>
                                                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
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

                                                            <input type="hidden" id="video_kyc" name="video_kyc">


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

                                                                <a href="{{ route('website.privacy-policy') }}" style="color:black">Agree to terms and conditions</a>


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
    <script src="{{asset('admin/assets/js/select2.min.js')}}"></script>

    <script src="{{asset('admin/assets/js/moment.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{asset('admin/assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/dataTables.bootstrap4.min.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
    <!-- Tagsinput JS -->


    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{asset('admin/assets/js/app.js')}}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Include SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script src="{{asset('franchise/js/custom.js')}}"></script>
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

                url: "{{ url('admin/city-state') }}/" + pincode + "?type=deliveryBoy&name=" + encodeURIComponent(name),

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

document.getElementById('startCameraButton').addEventListener('click', async () => {
    const constraints = { video: true, audio: true };
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

document.getElementById('startRecordingButton').addEventListener('click', async () => {
    if (mediaRecorder && mediaRecorder.state === 'recording') return;
    if (!stream) return;

    const options = { mimeType: 'video/webm; codecs=vp9' };
    mediaRecorder = new MediaRecorder(stream, options);
    chunks = [];
    startTime = Date.now();

    mediaRecorder.ondataavailable = function (event) {
        if (event.data.size > 0) chunks.push(event.data);
    };

    mediaRecorder.onstart = function () {
        updateTiming();
        document.getElementById("startRecordingButton").style.pointerEvents = "none";
    };

    mediaRecorder.onstop = function () {
        clearInterval(timingInterval);
        const blob = new Blob(chunks, { type: 'video/webm' });
        const reader = new FileReader();

        reader.onloadend = function () {
            const base64data = reader.result;
            const filename = `kyc_${Date.now()}.webm`;
            document.getElementById('video_kyc').value = JSON.stringify({
                filename: filename,
                data: base64data
            });
        };

        reader.readAsDataURL(blob);

        Swal.fire({
            icon: "success",
            title: "KYC Instructions",
            text: "Your video duration was 40 seconds. Make sure to speak clearly.",
        }).then(() => {
            $("#add_role_user").modal("hide");
            const video = document.getElementById('video');
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
        });
    };

    mediaRecorder.start();

    setTimeout(() => {
        if (mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
        }
    }, 41000); // 41 seconds
});

function updateTiming() {
    timingInterval = setInterval(() => {
        const elapsed = Date.now() - startTime;
        const minutes = Math.floor(elapsed / 60000);
        const seconds = Math.floor((elapsed % 60000) / 1000);
        document.getElementById('timing').textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }, 1000);
}

function stopRecordingAndStream() {
    if (mediaRecorder && mediaRecorder.state === 'recording') mediaRecorder.stop();
    if (stream) stream.getTracks().forEach(track => track.stop());
}

document.getElementById('modalCloseButton').addEventListener('click', () => {
    stopRecordingAndStream();
});
</script>



</body>

</html>