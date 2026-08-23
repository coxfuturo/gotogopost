@extends('franchise.layouts.master')

@section('title') {{\App\Models\Admin::GOTOGO_POST_REGISTERED}} @endsection


@section('header-right-part')


<style>
    .input-group .btn {
        position: relative;
        z-index: 2;
        top: 25px;
    }

    .page-wrapper .content .page-header .page-title {
        color: #1f1f1f;
        font-size: 20px !important;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .card .card-title {
        color: #1f1f1f;
        font-size: 16px !important;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .btn-price {
        --bs-btn-padding-x: 0.4rem !important;
        --bs-btn-padding-y: 0.275rem !important;
    }


    @media screen and (max-width: 400px) {
        .get-price-details-upper {
            flex-direction: column;
        }
    }
</style>
<div class="col-lg-6" style="padding: 0">
    <div class="get-price-details-upper d-flex" style="gap:10px">
        <div class="">
            <label for="FromPincode">From</label>
            <div class="input-group">
                <input type="number" class="@error('FromPincode') is-invalid @enderror" id="FromPincode" name="FromPincode" placeholder="Pincode" value="{{ old('FromPincode') }}" required>
                <div class="invalid-feedback">
                    Please provide pincode
                </div>
                <div class="invalid-feedback validerror-origin">
                </div>
            </div>
        </div>
        <div class="">
            <label for="ToPincode">To</label>
            <div class="input-group">
                <input type="text" class="@error('ToPincode') is-invalid @enderror" id="ToPincode" name="ToPincode" maxlength="6" placeholder="Pincode" value="{{ old('ToPincode') }}" required>
                <div class="invalid-feedback">
                    Please provide pincode
                </div>
                <div class="invalid-feedback validerror-destination">
                </div>
            </div>
        </div>
        <div class="">
            <label for="Weight">Weight </label>
            <div class="input-group">
                <input type="text" class="@error('Weight') is-invalid @enderror" id="Weight" name="Weight" placeholder="Weight" value="{{ old('Weight') }}" required>
                <div class="invalid-feedback">
                    Please provide weight
                </div>
            </div>
        </div>
        <div class="">
            <label for="Length">Length </label>
            <div class="input-group">
                <input type="text" class="@error('package_length') is-invalid @enderror" id="length" name="length" placeholder="length" value="{{ old('length') }}">
                <div class="invalid-feedback">
                    Please provide length
                </div>
            </div>
        </div>
        <div class="">
            <label for="width">width </label>
            <div class="input-group">
                <input type="text" class="@error('width') is-invalid @enderror" id="width" name="width" placeholder="width" value="{{ old('width') }}" required>
                <div class="invalid-feedback">
                    Please provide width
                </div>
            </div>
        </div>
        <div class="">
            <label for="height">height</label>
            <div class="input-group">
                <input type="text" class="@error('height') is-invalid @enderror" id="height" name="height" placeholder="height" value="{{ old('height') }}" required>
                <div class="invalid-feedback">
                    Please provide height
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-2 d-flex" style="min-height:50px;padding:0">
    <div class="d-flex align-items-start" style="width: 100%;margin-top:10px;gap:10px;">
        <h4 class="totalp" style="margin-top:8px "></h4>
        <button class="btn-price  btn btn-primary" id="parcel-rate-submit" type="submit">Submit</button>
    </div>

</div>
@endsection


@section('content')


<style>
    input {
        border: none;
        outline: none;
        box-shadow: 0px 1.5px 0px rgba(0, 0, 0, 0.5);
        width: 100%;
    }

    input:focus {
        border: none;
        outline: none;
        box-shadow: 0px 1.5px 0px rgba(0, 0, 0, 0.5);
    }

    input::placeholder {
        font-size: 14px;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        cursor: pointer;
        float: right;
        font-weight: bold;
        height: 26px;
        margin-right: 20px;
        padding-right: 0px;
        display: none;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 26px;
        position: absolute;
        top: -1px;
        right: 1px;
        width: 20px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #272b41;
        line-height: 30px;
    }

    .select2-container .select2-selection.select2-selection--single {
        border: 1px solid #dcdcdc;
        height: 30px;
    }

    body>div.main-wrapper>div.page-wrapper>div>form>div:nth-child(3)>div:nth-child(1)>div>div.card-header.d-flex>div:nth-child(2)>span {
        width: 100% !important;
    }

    .page-wrapper .content .page-header {
        margin-bottom: 1rem !important;
    }

    .page-wrapper .content {
        padding: 20px 25px;
    }

    .google-map-image {
        position: absolute;
        right: -12px;
        top: -8px;
        cursor: pointer;
    }


    .custom-modal .modal-content .modal-body {
        padding: 20px;
    }


    .switch span {
        position: relative;
        width: 50px;
        height: 25px;
        background-color: #ef5c5c;
        display: inline-block;
        -webkit-transition: all 0.2s ease;
        -ms-transition: all 0.2s ease;
        transition: all 0.2s ease;
        border-radius: 30px;
    }

    .switch span:after {
        content: "";
        background-color: #ffffff;
        width: 16px;
        height: 15px;
        -webkit-box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.25);
        box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.25);
        position: absolute;
        top: 4px;
        bottom: 1px;
        left: 6px;
        border-radius: 30px;
        -webkit-transition: all 0.2s ease;
        -ms-transition: all 0.2s ease;
        transition: all 0.2s ease;
    }

    .barcode-top {
        display: flex;
        align-items: center;
        width: fit-content;
        background: #55ce63;
        color: white;
        padding: 1px 7px;
        border-radius: 3px;
    }
</style>

{{-- new form start --}}

<div class="row">
    <div class="col-md-4 d-flex justify-content-between align-items-center">
        <div class="">FO-CODE-{{$franchise_details->franchise_no}}-SP-532-CI-3000059283</div>
    </div>

    <div class="col-md-4 d-flex justify-content-between align-items-center">
        <div name="">Wallet Amount : {{$franchise_details->wallet_balance}}</div>
        <div name="">Wallet Balance : {{$franchise_details->remaining_balance}}</div>
    </div>
    <div class="col-md-4 d-flex justify-content-between align-items-center">

        <label>Keep Pickup Details </label>
        <label class="switch">
            <input type="hidden" value="off" name="auto_backup_db">
            <input type="checkbox" id="auto_backup_db" name="auto_backup_db">
            <span></span>
        </label>
    </div>
</div>


<form id="parcel-form" action="{{route('franchise.go-registered.store')}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
    @csrf
    <input type="text" name='barcodeCode' value="{{$code}}" hidden>
    <input type="text" name="barcodeImageSrc" value="{{ $barcode }}" hidden>
    <div class="row mt-2">
        <div class="col-md-4 d-flex">
            <div class="card profile-box flex-fill">
                <div class="card-header d-flex justify-content-between p-1">
                    <div class="col-md-6">
                        <select name="pickup-details" id="pickup-details">
                            <option value="">Select an option</option>
                            @foreach($pickupDetails as $user)
                            <option value="{{$user->id}}">{{$user->name}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 barcode-top">
                        {{$code}}
                    </div>
                </div>
                <div class="card-body">
                    <ul class="personal-info">
                        <li>
                            <div class="col-lg-3 title">Name:</div>
                            <div class="col-lg-8">
                                <input type="text" id="PickupName" name="PickupName" placeholder="Enter Name" class="@error('name') is-invalid @enderror" value="{{ old('PickupName') }}" required>
                                <div class="invalid-feedback">
                                    Please provide your name
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Phone:</div>
                            <div class="col-lg-8">
                                <input type="text" id="PickupMobile" name="PickupMobile" placeholder="Enter Phone" class="NumberValidate @error('mobile') is-invalid @enderror" maxlength="10" value="{{ old('PickupMobile') }}" required>
                                <div class="invalid-feedback">
                                    Please provide mobile number
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Email:</div>
                            <div class="col-lg-8">
                                <input type="email" id="PickupEmail" name="PickupEmail" placeholder="Enter Email" class="@error('email') is-invalid @enderror" value="{{ old('PickupEmail') }}" required>
                                <div class="invalid-feedback">
                                    Please provide emailId
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Pincode:</div>
                            <div class="col-lg-8">
                                <input type="text" class="pincode" id="PickupPincode" name="PickupPincode" placeholder="Enter Pincode" class="@error('name') is-invalid @enderror" value="{{ old('PickupPincode') }}" required>
                                <div class="invalid-feedback">
                                    Please provide your name
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">City:</div>
                            <div class="col-lg-8 d-flex" style="position: relative">
                                <input type="text" id="PickupCity" name="PickupCity" placeholder="Enter City" class="@error('city') is-invalid @enderror" value="{{ old('PickupCity') }}" required>
                                <div>
                                    <img data-bs-toggle="modal" data-bs-target="#add_role" class="google-map-image" src="https://i.pinimg.com/736x/66/1e/98/661e98a8e38f681575da93d0a1c3f4fc.jpg" alt="" width="30">
                                </div>
                                <div class="invalid-feedback">
                                    Please provide city
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">State:</div>
                            <div class="col-lg-8">
                                <input type="text" id="PickupState" name="PickupState" placeholder="Enter State" class="@error('state') is-invalid @enderror" value="{{ old('PickupState') }}" required>
                                <div class="invalid-feedback">
                                    Please provide state
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Address:</div>
                            <div class="col-lg-8">
                                <input type="text" id="PickupAddress" name="PickupAddress" placeholder="Enter Address" class="@error('name') is-invalid @enderror" value="{{ old('PickupAddress') }}" required>
                                <div class="invalid-feedback">
                                    Please provide your name
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card profile-box flex-fill">
                <div class="card-header d-flex justify-content-between">
                    <div class="col-md-6">
                        <h5 class="card-title mb-0">Consignee Details</h5>
                    </div>
                    <div class="col-md-6 text-end d-flex justify-content-end align-items-center">
                        <h5 class="card-title mb-0 from-submitted text-success" style="display: none">Formm Submitted</h5>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="personal-info">
                        <li>
                            <div class="col-lg-3 title">Name:</div>
                            <div class="col-lg-8">
                                <input type="text" id="ConsigneeName" name="ConsigneeName" placeholder="Enter Name" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeName') }}" required>
                                <div class="invalid-feedback">
                                    Please provide your name
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Phone:</div>
                            <div class="col-lg-8">
                                <input type="text" id="ConsigneeMobile" name="ConsigneeMobile" placeholder="Enter Phone" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeMobile') }}" required>
                                <div class="invalid-feedback">
                                    Please provide your name
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Email:</div>
                            <div class="col-lg-8">
                                <input type="text" id="ConsigneeEmail" name="ConsigneeEmail" placeholder="Enter Email" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeEmail') }}" required>
                                <div class="invalid-feedback">
                                    Please provide your name
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Pincode:</div>
                            <div class="col-lg-8">
                                <input type="text" class="pincode" id="ConsigneePincode" name="ConsigneePincode" placeholder="Enter Pincode" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneePincode') }}" required>
                                <div class="invalid-feedback">
                                    Please provide your name
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">City</div>
                            <div class="col-lg-8" style="position: relative">
                                <input type="text" id="ConsigneeCity" name="ConsigneeCity" placeholder="Enter City" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeCity') }}" required>
                                <div>
                                    <img data-bs-toggle="modal" data-bs-target="#add_role" class="google-map-image" src="https://i.pinimg.com/736x/66/1e/98/661e98a8e38f681575da93d0a1c3f4fc.jpg" alt="" width="30">
                                </div>
                                <div class="invalid-feedback">
                                    Please provide your name
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">State:</div>
                            <div class="col-lg-8">
                                <input type="text" id="ConsigneeState" name="ConsigneeState" placeholder="Enter State" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeState') }}" required>
                                <div class="invalid-feedback">
                                    Please provide your name
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Address:</div>
                            <div class="col-lg-8">
                                <input type="text" id="ConsigneeAddress" name="ConsigneeAddress" placeholder="Enter Address" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeAddress') }}" required>
                                <div class="invalid-feedback">
                                    Please provide your name
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card profile-box flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Parcel Details</h5>
                    <a href="#" style="min-width: 90px;font-size:14px;" class="btn-price btn add-btn" data-bs-toggle="modal" data-bs-target="#add_support_tickets"></i> Upload</a>
                </div>
                <div class="card-body">
                    <ul class="personal-info">
                        <li>
                            <div class="col-lg-3 title">Weight:</div>
                            <div class="col-lg-8">
                                <input type="number" id="package_weight" name="package_weight" placeholder="Package Weight" class="@error('package_weight') is-invalid @enderror" value="{{ old('package_weight') }}" required>
                                <div class="invalid-feedback">
                                    Please provide Package Weight
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Length:</div>
                            <div class="col-lg-8">
                                <input type="number" id="package_length" name="package_length" placeholder="Package Length" class="@error('package_length') is-invalid @enderror" value="{{ old('package_length') }}" required>
                                <div class="invalid-feedback">
                                    Please provide Package Length
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Width:</div>
                            <div class="col-lg-8">
                                <input type="number" id="package_width" name="package_width" placeholder="Package Width" class="@error('package_width') is-invalid @enderror" value="{{ old('package_width') }}" required>
                                <div class="invalid-feedback">
                                    Please provide Package Width
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-lg-3 title">Height:</div>
                            <div class="col-lg-8">
                                <input type="number" id="package_height" name="package_height" placeholder="Package Height" class="@error('package_height') is-invalid @enderror" value="{{ old('package_height') }}" required>
                                <div class="invalid-feedback">
                                    Please provide Package Height
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="col-lg-3 title"></div>
                            <div class="col-md-6 mb-3">
                                <label class="text-dark mb-1">Payment Method<span class="text-danger">*</span></label>
                                <select name="payment_method" id="paymentMethod" class="select" data-tags="true" data-placeholder="Select an option" required>
                                    <option value="">Select an option</option>
                                    <option value="{{'cod'}}">COD</option>
                                    <option value="{{'prepaid'}}">Prepaid</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please provide Payment Method
                                </div>
                            </div>
                        </li>


                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <div class="text-center">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>



<style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans:700,300);


    .center {
        height: 200px;
        border-radius: 3px;
        box-shadow: 8px 10px 15px 0 rgba(0, 0, 0, 0.2);
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        flex-direction: column;
        font-family: "Open Sans", Helvetica, sans-serif;
    }

    /* .title {
        width: 100%;
        height: 50px;
        border-bottom: 1px solid #999;
        text-align: center;
    } */

    h1 {
        font-size: 16px;
        font-weight: 300;
        color: #666;
    }

    .dropzone {
        width: 100px;
        height: 80px;
        border: 1px dashed #999;
        border-radius: 3px;
        text-align: center;
    }

    .upload-icon {
        margin: 25px 2px 2px 2px;
    }

    .upload-input {
        position: relative;
        top: -62px;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
    }

    .download-button {
        background-color: #7b2cbf;
        color: #f7fff7;
        display: flex;
        align-items: center;
        font-size: 18px;
        border: none;
        border-radius: 20px;
        margin: 10px;
        padding: 7.5px 50px;
        cursor: pointer;
    }

    .submit-section {
        text-align: center;
        margin-top: 10px;
        float: left;
        width: 100%;
    }
</style>

<!-- upload -->
<div id="add_support_tickets" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('franchise.go-registered.storeByfile')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-files-container">
                        <div class="drag-file-area">
                            <span class="material-icons-outlined upload-icon"> file_upload </span>
                            <h3 class="dynamic-message">Drag & drop any file here</h3>
                            <label style="width: 220px;" class="label">
                                or <span class="browse-files">
                                    <input type="file" class="default-file-input" name="file" />
                                    <span class="browse-files-text">browse file</span>
                                    <span>from device</span>
                                </span>
                            </label>
                        </div>
                        <div class="file-block">
                            <div class="file-info">
                                <span class="material-icons-outlined file-icon">description</span> <span class="file-name"> </span> | <span class="file-size"> </span>
                                <span class="material-icons remove-file-icon">delete</span>
                            </div>

                        </div>
                        <a href="{{route('franchise.go-registered.downloadForamt')}}">
                            <button type="button" class="download-button">Download Format</button>
                        </a>

                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- /upload -->


<!-- Add Role Modal -->
<div id="add_role" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 300px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>


@push('page-javascript')


<script>
    $('#ConsigneePincode').on('change', function() {
        var pincode = $('#ConsigneePincode').val();
        $.ajax({
            type: 'GET',
            url: "{{url('admin/city-state')}}" + '/' + pincode,
            success: function(data) {
                if (data.success === true) {
                    $('.customer_error').empty();
                    $('#ConsigneeCity').empty().val(data.district);
                    $('#ConsigneeState').empty().val(data.state);
                } else {
                    $('.validerror').text('Please enter valid pincode');
                }
            }
        });
    });

    $('#pickup-details').on('change', function(e) {
        var csrfToken = "{{ csrf_token() }}";

        $.ajax({
            type: 'POST',
            url: "{{ route('franchise.pickup-details.details') }}",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                id: e.target.value
            },
            success: function(data) {
                if (data) {
                    $('#PickupName').val(data.name);
                    $('#PickupMobile').val(data.phone);
                    $('#PickupEmail').val(data.email);
                    $('#PickupPincode').val(data.pincode);
                    $('#PickupCity').val(data.city);
                    $('#PickupState').val(data.state);
                    $('#PickupAddress').val(data.address);

                } else {
                    $('.validerror').text('Please enter a valid pincode');
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

<script src="https://maps.google.com/maps/api/js?key=AIzaSyARf505VVJ_bn-5BnQ5qFbyKqWGF4DRn9U&libraries=places&callback=initAutocomplete" type="text/javascript"></script>

<script>
    google.maps.event.addDomListener(window, 'load', initialize);

    function initialize() {
        var input = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            $('#latitude').val(place.geometry['location'].lat());
            $('#longitude').val(place.geometry['location'].lng());
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('#parcel-rate-submit').on('click', function() {
            const from = $('#FromPincode').val();
            const to = $('#ToPincode').val();
            const weight = $('#Weight').val();
            $('.validerror-destination').text('Please enter a valid pincode').hide()
            $('.validerror-origin').text('Please enter a valid pincode').hide()

            if (!from) {
                $('#FromPincode').next('.invalid-feedback').show();
                return;
            } else {
                $('#FromPincode').next('.invalid-feedback').hide();
            }

            if (!to) {
                $('#ToPincode').next('.invalid-feedback').show();
                return;
            } else {
                $('#ToPincode').next('.invalid-feedback').hide();
            }

            if (!weight) {
                $('#Weight').next('.invalid-feedback').show();
                return;
            } else {
                $('#Weight').next('.invalid-feedback').hide();
            }

            const data = {
                originPincode: from,
                destinationPincode: to,
                packageWeight: weight
            };
            var csrfToken = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: "{{ route('franchise.rateCalculator.calculate') }}",
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: data,
                success: function(response) {
                    if (response.status === 'success') {
                        $(".total-text").text('Total :')
                        $('.totalp').text(`₹ ${response.total}`);
                    } else {
                        if (response.message === 'fill valid distination pincode') {
                            console.log("reached here inside else")
                            $('.validerror-destination').text('Please enter a valid pincode').show();
                        }
                        if (response.message === 'fill valid origin pincode') {
                            $('.validerror-origin').text('Please enter a valid pincode').show();
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                    $('.error-message').text("An error occurred. Please try again.").show();
                }
            });
        });
    });
</script>


<script>
    $(document).ready(function() {


        function getCoordinates(pincode) {
            var geocodingApiUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + pincode + '&key=AIzaSyARf505VVJ_bn-5BnQ5qFbyKqWGF4DRn9U';

            $.get(geocodingApiUrl, function(data) {
                if (data.status === 'OK') {
                    var location = data.results[0].geometry.location;
                    showMap(location.lat, location.lng);
                } else {
                    alert('wrong pincode');
                }
            });
        }

        function showMap(lat, lng) {
            var map;
            var mapOptions = {
                center: new google.maps.LatLng(lat, lng),
                zoom: 8,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map"), mapOptions);

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                map: map
            });
        }

        $('.google-map-image').on('click', function() {

            let pincode = $(this).closest('ul').find('.pincode').val();
            getCoordinates(pincode);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#auto_backup_db').change(function() {

            var form = $('#parcel-form');
            var currentAction = form.attr('action');

            if ($(this).is(':checked')) {
                form.submit(function(event) {
                    $('.from-submitted').css('display', 'none')
                    event.preventDefault();

                    // Get form data
                    var formData = form.serializeArray();
                    formData.push({
                        name: 'static',
                        value: 1
                    });
                    var csrfToken = "{{ csrf_token() }}";

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('franchise.go-registered.store') }}",
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: formData,
                        success: function(response) {
                            if (response.status == 200) {
                                form[0].reset();
                                $('.invalid-feedback').hide()
                                $('#PickupName').val(response.data.PickupName)
                                $('#PickupMobile').val(response.data.PickupMobile)
                                $('#PickupEmail').val(response.data.PickupEmail)
                                $('#PickupPincode').val(response.data.PickupPincode)
                                $('#PickupState').val(response.data.PickupState)
                                $('#PickupCity').val(response.data.PickupCity)
                                $('#PickupAddress').val(response.data.PickupAddress)
                                $('#package_weight').val(response.data.package_weight)
                                $('#package_length').val(response.data.package_length)
                                $('#package_width').val(response.data.package_width)
                                $('#package_height').val(response.data.package_height)
                                $('.barcode-top').text(response.code)

                                $('#paymentMethod').val(response.data.payment_method);

                                $('.from-submitted').css('display', 'block')
                            } else if (response.status == 400) {

                                if (response.message == 'fill valid origin pincode') {
                                    $('#PickupPincode').siblings('.invalid-feedback').text('Fill valid origin pincode').show();
                                }
                                if (response.message == 'fill valid distination pincode') {
                                    $('#ConsigneePincode').siblings('.invalid-feedback').text('Fill valid destination pincode').show();
                                }

                                if (response.message == 'barcode series end') {
                                    $('.from-submitted').text('barcode series end')
                                    $('.from-submitted').css('display', 'block')
                                }

                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("1", jqXHR.responseJSON.errors)

                            if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                                var errors = jqXHR.responseJSON.errors;
                                $.each(errors, function(key, messages) {
                                    var input = $(`#${key}`);
                                    var errorMessage = messages[0];
                                    if (input.length) {
                                        input.siblings('.invalid-feedback').text(errorMessage).show();
                                    }
                                });

                            }

                            console.error('AJAX error:', textStatus, errorThrown);
                            $('.error-message').text("An error occurred. Please try again.").show();
                        }
                    });

                    return false; // Prevent default form submission
                });
            } else {
                form.off('submit');
            }
        });
    });
</script>



<script type="text/javascript">
    function delete_modal(id) {
        const deleteUrl = "{{ route('franchise.softCopyParcel.delete', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }
    var isAdvancedUpload = function() {
        var div = document.createElement('div');
        return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
    }();

    let draggableFileArea = document.querySelector(".drag-file-area");
    let browseFileText = document.querySelector(".browse-files");
    let uploadIcon = document.querySelector(".upload-icon");
    let dragDropText = document.querySelector(".dynamic-message");
    let fileInput = document.querySelector(".default-file-input");
    let cannotUploadMessage = document.querySelector(".cannot-upload-message");
    let cancelAlertButton = document.querySelector(".cancel-alert-button");
    let uploadedFile = document.querySelector(".file-block");
    let fileName = document.querySelector(".file-name");
    let fileSize = document.querySelector(".file-size");

    let removeFileButton = document.querySelector(".remove-file-icon");
    let fileFlag = 0;

    fileInput.addEventListener("click", () => {
        fileInput.value = '';
        console.log(fileInput.value);
    });

    fileInput.addEventListener("change", e => {
        uploadIcon.innerHTML = 'check_circle';
        dragDropText.innerHTML = 'File Dropped Successfully!';
        fileName.innerText = fileInput.files[0].name;
        fileSize.innerText = (fileInput.files[0].size / 1024).toFixed(1) + " KB";
        uploadedFile.style.display = "block";
        fileFlag = 0;
    });

    if (isAdvancedUpload) {
        ["drag", "dragstart", "dragend", "dragover", "dragenter", "dragleave", "drop"].forEach(evt =>
            draggableFileArea.addEventListener(evt, e => {
                e.preventDefault();
                e.stopPropagation();
            })
        );

        ["dragover", "dragenter"].forEach(evt => {
            draggableFileArea.addEventListener(evt, e => {
                e.preventDefault();
                e.stopPropagation();
                uploadIcon.innerHTML = 'file_download';
                dragDropText.innerHTML = 'Drop your file here!';
            });
        });

        draggableFileArea.addEventListener("drop", e => {
            uploadIcon.innerHTML = 'check_circle';
            dragDropText.innerHTML = 'File Dropped Successfully!';
            let files = e.dataTransfer.files;
            fileInput.files = files;
            console.log(document.querySelector(".default-file-input").value);
            fileName.innerHTML = files[0].name;
            fileSize.innerHTML = (files[0].size / 1024).toFixed(1) + " KB";
            uploadedFile.style.cssText = "display: flex;";
            fileFlag = 0;
        });
    }

    removeFileButton.addEventListener("click", () => {
        uploadedFile.style.cssText = "display: none;";
        fileInput.value = '';
        uploadIcon.innerHTML = 'file_upload';
        dragDropText.innerHTML = 'Drag & drop any file here';
    });
</script>

@endpush

@endsection