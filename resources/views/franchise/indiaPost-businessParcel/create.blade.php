@extends('franchise.layouts.master')
@section('title') {{\App\Models\Admin::INDIA_POST_BUSINESS}} @endsection
@section('header-right-part')
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

        box-shadow: 0px 1.5px 0px rgb(51 30 102 / 83%);

        width: 100%;
        color: darkred;

    }

    input:focus {

        border: none;

        outline: none;

        box-shadow: 0px 1.5px 0px rgb(51 30 102 / 83%);

    }

    input::placeholder {

        font-size: 14px;
        color: #2e00ff;
        /* color: darkred; */
        color: #123e64;


    }

    .card {
        border: 1px solid #ededed;
        margin-bottom: 30px;
        /* box-shadow: 0px 0px 0px 1.5px rgb(109, 133, 205); */
        box-shadow: 0px 0px 0px 1.5px rgb(49 79 167);

    }

    .card-header {
        border-bottom: 1.5px solid rgb(49 79 167);
    }


    /* .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #2e00ff !important;
    }

    .select2-container .select2-selection.select2-selection--single {
        border: 1.5px solid #e07676;
        height: 30px
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

    } */

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

        right: -6px;

        top: -2px;

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

    .personal-info li {

        margin-bottom: 5px;

    }

    .showpricedetails {
        padding: 10px;
    }

    .showpricedetails .showpricedetails-left {
        border-width: 1.5px 0px 0px 1.5px;
        border-color: #544e80;
        border-style: solid;
        padding: 4px 8px;

    }

    .showpricedetails .showpricedetails-right {
        border-width: 1.5px 1.5px 0px 1.5px;
        border-color: #544e80;
        border-style: solid;
        padding: 4px 8px;

    }

    .showpricedetails .showpricedetails-total {
        border: 2px solid #544e80;
        padding: 4px 8px;

    }

    .showpricedetails p {
        display: flex;
        justify-content: space-between;
        line-height: 18px;

    }

    .showpricedetails p span {
        flex-grow: 1;
        /* Expands the span to take remaining space */
    }

    .showpricedetails p span:first-child {
        color: #333333;
        font-weight: 500;
        font-size: 13px;
        margin-right: 10px;
    }

    .showpricedetails p span.float-right {
        text-align: right;
    }


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



{{-- new form start --}}

<form id="parcel-form" action="{{route('franchise.india-post-business.store')}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>

    @csrf

    <img style="display:block" id="barcodeImage" src="" alt="Barcode" style="height:50px;" hidden />
      <div class="row">
         <div class="col-md-6 d-flex">

            <div class="card profile-box flex-fill">


                <div class="card-body" style="padding-bottom:0px !important">

                    <ul class="personal-info">

                        <li>

                            <div class="col-lg-3 title">Booking ID:</div>

                            <div class="col-lg-8">

                                <select name="payment_method" id="paymentMethod" class="select form-select"  data-tags="true" data-placeholder="Select an option" required>

                                    <option value="">Select an option</option>
                                    <option value="{{'franchise'}}">Franchise</option>
                                    <option value="{{'manager'}}">S.M. Manager</option>
                                    <!-- <option value="{{'customer'}}">Customer</option> -->
                                    <option value="{{'pickup'}}">Pickup Boy</option>

                                </select>

                                <div class="invalid-feedback">

                                    Please provide Booking Method

                                </div>

                            </div>

                        </li>

                         <li>

                            <div class="col-lg-3 title"><span id="mobilespan"></span> </div>

                            <div class="col-lg-8">
                                <select name="payment_type" id="payment_type" class="select form-select"  data-tags="true" data-placeholder="Select an option" >
                                    <option selected disabled>Select an option</option>
                                </select>
                            </div>

                          </li>

                            <li>

                            <div class="col-lg-3 title"><span id="mobilespan"></span> Customer</div>

                            <div class="col-lg-8">
                                <select name="payment_type_cod" id="paymentType" class="select form-select"  data-tags="true" data-placeholder="Select an option" required>

                                    <option disabled selected>Select an option</option>

                                    <option value="{{'cod'}}">COD Parcel</option>

                                    <option value="{{'prepaid'}}" >Non Cod Parcel</option>

                                </select>
                               
                            </div>

                          </li>
                    </ul>

                </div>

            </div>

        </div>

        <div class="col-md-6 d-flex">

            <div class="card profile-box flex-fill">


                <div class="card-body" style="padding-bottom:0px !important">

                   <div class="row">
                       <div class="col-sm-4">
                              <div class="d-flex flex-column">
                                <span>Franchise</span>
                                  <span>Credit Account</span>
                                  <span class="credit-balance">{{ number_format($franchise_details->india_credit_amount, 2) }}</span>
                              </div>
        
                       </div>

                       <div class="col-sm-4">
                              <div class="d-flex flex-column text-right">
                                 <span>Franchise</span>
                                 <span>Advance  Account</span>
                                 <span class="gotogo-balance">{{ number_format($franchise_details->indiapost_balance, 2) }}</span>
                              </div>
                       </div>

                       <div class="col-sm-4 d-flex justify-content-between align-items-center">
                              <div name="">Barcode Available: <span class="barcode-available">{{ $barcodeAvailable }}</span></div>
                       </div>
                       </div>
                      <div class="row" >
                        <hr>
                       <div class="col-sm-8 my-2" >
                               @if(!empty($linkDetails))
        <div class="">
            <div class="">FO-CODE-{{$linkDetails->franchise_no}}-BNPL-{{$linkDetails->bnpl_no}}-CI-{{$linkDetails->contract_id}}</div>
        </div>
        @else
        <div class="">

        </div>
        @endif
        <section style="display: flex; width: 100%; padding: 10px; gap: 10px;">

  <label class="form-check getPrice" style="flex: 1; display: flex; align-items: center; justify-content: space-between; border: 1px solid gray; padding: 10px; border-radius: 5px; cursor: pointer;">
    <div>
      Surface <span id="texteconomic" style="color: green;">Rs.0</span>
    </div>
    <input class="form-check-input economic" type="radio" name="fastest" value="1">
  </label>

  <label class="form-check getPrice" style="flex: 1; display: flex; align-items: center; justify-content: space-between; border: 1px solid gray; padding: 10px; border-radius: 5px; cursor: pointer;">
    <div>
      Air <span id="textfastest" style="color: blue;">Rs.0</span>
    </div>
    <input class="form-check-input fastest" type="radio" name="fastest" value="2" >
  </label>

</section>

                       </div>

                       <div class="col-sm-4 text-end" >
                           <button class="btn btn-primary allprint " id="allprint" style="display:none" type="button">Print Now</button> &nbsp;&nbsp;
                                  
                         <input type="hidden" class="printNumber form-control" id="printNumber" name="printNumber" class="@error('printNumber') is-invalid @enderror" value="0">
                         
                         <input type="hidden" name="parcel_id[]" id="parcel_id">
                       </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row mt-2">
        <div class="col-md-4 d-flex">

            <div class="card profile-box flex-fill pb-4">

                <div class="card-header d-flex justify-content-between p-1">

                    <div class="col-md-6">
                        <select name="pickupDetails" id="pickup-details" class="select form-select"  data-tags="true" data-placeholder="Select an option" >
                           <option selected disabled>Select an option</option>
                        </select>
                    </div>

                    <div class="card-title mb-0">

                        {{-- {{$code}} --}} Form Pickup Details

                    </div>

                </div>

                <div class="card-body" style="padding-bottom:0px !important">

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

                            <div class="col-lg-3 title">GST No:</div>

                            <div class="col-lg-8">

                                <input type="text" id="PickupGstNo" name="PickupGstNo" placeholder="Enter Gst no" class=" @error('PickupGstNo') is-invalid @enderror" value="{{old('gst_number')}}" >
                                <div class="invalid-feedback">
                                    Please provide Gst number
                                </div>

                            </div>

                        </li>

                        <li>

                            <div class="col-lg-3 title">Email:</div>

                            <div class="col-lg-8">

                                <input type="email" id="PickupEmail" name="PickupEmail" placeholder="Enter Email" class="@error('email') is-invalid @enderror" value="{{ old('PickupEmail') }}" >

                                <div class="invalid-feedback">

                                    Please provide emailId

                                </div>

                            </div>

                        </li>

                        <li>

                            <div class="col-lg-3 title">Pincode:</div>

                            <div class="col-lg-8">

                                <input type="text" class="pincode charge-input" id="PickupPincode" name="PickupPincode" placeholder="Enter Pincode" class="@error('name') is-invalid @enderror" value="{{ old('PickupPincode') }}" required>

                                <div class="invalid-feedback">

                                    Please provide Pickup pincode

                                </div>

                            </div>

                        </li>

                        <li>

                            <div class="col-lg-3 title">City:</div>

                            <div class="col-lg-8 d-flex" style="position: relative">

                                <input type="text" id="PickupCity" name="PickupCity" placeholder="Enter City" class="@error('city') is-invalid @enderror" value="{{ old('PickupCity') }}" required>

                                <div>

                                    <img data-bs-toggle="modal" data-bs-target="#add_role" class="google-map-image" src="https://i.pinimg.com/736x/66/1e/98/661e98a8e38f681575da93d0a1c3f4fc.jpg" alt="" width="25">

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

                                    Please provide Pickup Address

                                </div>

                            </div>

                        </li>

                    </ul>

                </div>

            </div>

        </div>


        <div class="col-md-4 d-flex">

            <div class="card profile-box flex-fill pb-4">

                <div class="card-header d-flex justify-content-between">

                    <div class="col-md-4">

                        <h5 class="card-title mb-0">To Consignee Details</h5>

                    </div>
                    <div class="col-md-2">
                        <a class="btn Consigneereset" style="background:blue;color:#fff;margin-left: 5px;display:none" ><i class="la la-refresh"></i></a>
                    </div>

                    <div class="col-md-6 text-end d-flex justify-content-end align-items-center">

                         <h5 class=" mb-0 from-submitted text-success" style="display: none">Form Submitted</h5> &nbsp;<b><span class="allprint clickcountshow" style="display:none;color:darkred">(<span id=" " style="color:darkred"></span>)</span></b>

                         <h4 class="totalp" style="margin-top:8px "></h4>

                    </div>

                </div>

                <div class="card-body" style="padding-bottom:0px !important">

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

                                <input type="text" id="ConsigneeMobile" name="ConsigneeMobile" placeholder="Enter Phone" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeMobile') }}" >

                                <div class="invalid-feedback">

                                    Please provide consignee mobile

                                </div>

                            </div>

                        </li>
                        <!-- <li>

                            <div class="col-lg-3 title">GST No:</div>

                            <div class="col-lg-8">

                                <input type="text" id="ConsigneeGstNo" name="ConsigneeGstNo" placeholder="Enter Gst no" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeGstNo') }}">

                                <div class="invalid-feedback">

                                    Please provide gst number

                                </div>

                            </div>

                        </li> -->

                        <li>

                            <div class="col-lg-3 title">Email:</div>

                            <div class="col-lg-8">

                                <input type="text" id="ConsigneeEmail" name="ConsigneeEmail" placeholder="Enter Email" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeEmail') }}">

                                <div class="invalid-feedback">

                                    Please provide consingee email

                                </div>

                            </div>

                        </li>

                        <li>

                            <div class="col-lg-3 title">Pincode:</div>

                            <div class="col-lg-8">

                                <input type="text" class="pincode charge-input" id="ConsigneePincode" name="ConsigneePincode" placeholder="Enter Pincode" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneePincode') }}" required>

                                <div class="invalid-feedback">

                                    Please provide consignee pincode

                                </div>

                            </div>

                        </li>

                        <li>

                            <div class="col-lg-3 title">City</div>

                            <div class="col-lg-8" style="position: relative">

                                <input type="text" id="ConsigneeCity" name="ConsigneeCity" placeholder="Enter City" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeCity') }}" required>

                                <div>

                                    <img data-bs-toggle="modal" data-bs-target="#add_role" class="google-map-image" src="https://i.pinimg.com/736x/66/1e/98/661e98a8e38f681575da93d0a1c3f4fc.jpg" alt="" width="25">

                                </div>

                                <div class="invalid-feedback">

                                    Please provide Consignee City

                                </div>

                            </div>

                        </li>

                        <li>

                            <div class="col-lg-3 title">State:</div>

                            <div class="col-lg-8">

                                <input type="text" id="ConsigneeState" name="ConsigneeState" placeholder="Enter State" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeState') }}" required>

                                <div class="invalid-feedback">

                                    Please provide consingnee state

                                </div>

                            </div>

                        </li>

                         <li>

                            <div class="col-lg-3 title">Landmark:</div>

                            <div class="col-lg-8">

                                <select class="form-select Consigneeaddress2">
                                    <option disabled selected>Select Option</option>
                                </select>

                                <div class="invalid-feedback">

                                    Please provide consingnee landmark

                                </div>

                            </div>

                        </li>

                        <li>

                            <div class="col-lg-3 title">Address:</div>

                            <div class="col-lg-8">

                                <input type="text" id="ConsigneeAddress" name="ConsigneeAddress" placeholder="Enter Address" class="@error('name') is-invalid @enderror" value="{{ old('ConsigneeAddress') }}" required>

                                <div class="invalid-feedback">

                                    Please provide consignee address

                                </div>

                            </div>

                        </li>

                    </ul>

                </div>

            </div>

        </div>



        <div class="col-md-4 d-flex">

            <div class="card profile-box flex-fill pb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="card-title mb-0">Parcel Details</h5>

                    <a href="#" style="min-width: 90px;font-size:14px;" class="btn-price btn add-btn" data-bs-toggle="modal" data-bs-target="#add_support_tickets"></i> Upload</a>

                </div>

                <div class="card-body" style="padding-bottom:0px !important">

                    <ul class="personal-info">
                        <li class="d-flex justify-content-between">
                            <div class="col-lg-12">
                                <input type="text" id="barcodeInput" name="scanned_barcode_no" placeholder="Scan Barcode" class="@error('barcode') is-invalid @enderror" value="{{ old('barcode') }}">
                                 <input type="hidden" id="computed_weight" name="total_weight" placeholder="Computed Weight (grams)" readonly >
                                <div class="invalid-feedback">
                                    Please provide a valid Barcode
                                </div>
                            </div>
                        </li>
                        <li class="d-flex justify-content-between">
                            <div class="col-lg-5">
                                <input type="text" id="package_weight" name="package_weight" placeholder="Package Weight" class="charge-input @error('package_weight') is-invalid @enderror" value="{{ old('package_weight') }}" required>
                                <div class="invalid-feedback">
                                    Please provide Package Weight
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <input type="text" id="package_length" name="package_length" placeholder="Package Length" class="@error('package_length') is-invalid @enderror" value="{{ old('package_length') }}">
                                <div class="invalid-feedback">
                                    Please provide Package Length
                                </div>
                            </div>
                        </li>
                        <li class="d-flex justify-content-between">
                            <div class="col-lg-5">
                                <input type="text" id="package_width" name="package_width" placeholder="Package Width" class="@error('package_width') is-invalid @enderror" value="{{ old('package_width') }}">
                                <div class="invalid-feedback">
                                    Please provide Package Width
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <input type="text" id="package_height" name="package_height" placeholder="Package Height" class="@error('package_height') is-invalid @enderror" value="{{ old('package_height') }}">
                                <div class="invalid-feedback">
                                    Please provide Package Height
                                </div>
                            </div>
                        </li>
                        <li class="d-flex justify-content-between">
                            <div class="col-lg-5">
                                <input type="text" id="amount" name="amount" placeholder="Product Cost" class="@error('amount') is-invalid @enderror charge-input" value="{{ old('amount') }}">

                            </div>

                            <div class="col-lg-5">
                                <input type="text" id="pickup_charge" name="pickup_charge" placeholder="Pickup Charge" class="@error('pickup_charge') is-invalid @enderror charge-input" value="{{ old('pickup_charge') }}">

                            </div>

                            
                        </li>
                        <li class="d-flex justify-content-between">
                            <!-- <div class="col-lg-5">
                                <input type="text" id="pickup_charge" name="pickup_charge" placeholder="Pickup Charge" class="@error('pickup_charge') is-invalid @enderror charge-input" value="{{ old('pickup_charge') }}">

                            </div> -->
                            <div class="col-lg-5">
                                <input type="text" id="fuel_charge" name="fuel_charge" placeholder="Fuel Charge" class="@error('fuel_charge') is-invalid @enderror charge-input" value="{{ old('fuel_charge') }}">

                            </div>
                            <div class="col-lg-5">
                                <input type="text" id="other_service_charge" name="other_service_charge" placeholder="CPH Service Charge" class="@error('other_service_charge') is-invalid @enderror charge-input" >
                                 <input type="hidden" id="totalOther" name="totalOther"  class="@error('totalOther') is-invalid @enderror charge-input" >
                            </div>
                        </li>
                        <li class="d-flex justify-content-between">
                            <div class="col-lg-5">
                                <input type="text" id="register_amount" name="register_amount" placeholder="Register Fees" class="@error('register_amount') is-invalid @enderror charge-input" >
                               
                                 <input type="hidden" name="type" id="showtype">
                            </div>
                        </li>
                    </ul>

                    <div class="showpricedetails row" style="display:none">
                        <div class="col-lg-6 showpricedetails-left" style="border-top-left-radius: 5px;">
                            <p class="m-0"><span>Amount: </span>

                                <span id="showamount" class="float-right">

                                </span>
                            </p>
                        </div>
                        <div class="col-lg-6 showpricedetails-right" style="border-top-right-radius: 5px;">
                            <p class="m-0"><span>Fuel Charge:</span> <span id="showfuelcharge" class="float-right"></span></p>
                        </div>
                        <div class="col-lg-6 showpricedetails-left">
                            <p class="m-0"><span>Pickup Charge:</span> <span id="showpickupcharge" class="float-right"></span></p>
                        </div>
                        <div class="col-lg-6 showpricedetails-right">
                            <p class="m-0"><span>Other Charge:</span> <span id="showothercharge" class="float-right"></span></p>
                        </div>
                        <div class="col-lg-6 showpricedetails-left">
                            <p class="m-0"><span>GST:</span> <span id="showgst" class="float-right"></span></p>
                        </div>
                        <div class="col-lg-6 showpricedetails-right">
                            <p class="m-0"><span>COD Amount:</span> <span id="showcodamount" class="float-right"></span></p>
                        </div>
                        <!-- <div class="col-lg-6 showpricedetails-right"> -->
                            <!-- <p class="m-0"><span>GST:</span> <span id="showgst" class="float-right">100</span></p> -->
                        <!-- </div> -->
                         <div class="col-lg-12 showpricedetails-right">
                            <p class="m-0"><span>Register Fees:</span> <span id="showregister_amount" class="float-right"></span></p>
                        </div>
                        <div class="col-lg-12 showpricedetails-total" style="border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">
                            <p class="m-0"><span>Total:</span> <span id="showtotal" class="float-right"></span></p>
                        </div>
                    </div>




                </div>

            </div>

        </div>

    </div>



    <div class="card-body">

        <div class="row">

            <div class="col-sm">

                <div class="text-center">

                    <button class="btn btn-primary parcel-submit-btn" type="submit">Book Now</button>

                </div>

            </div>

        </div>

    </div>

</form>







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

                <form action="{{route('franchise.india-post-business.storeByfile')}}" method="POST" enctype="multipart/form-data">

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

                        <a href="{{route('franchise.india-post-business.downloadForamt')}}">

                            <button type="button" class="download-button">Download Format</button>

                        </a>



                    </div>



                    <div class="d-flex justify-content-center">

                        <div class="form-check form-check-inline">

                            <input class="form-check-input" type="radio" name="barcode_option" id="barcode_auto" value="barcode_auto" checked>

                            <label class="form-check-label" for="barcode_auto">Barcode Auto Generate</label>

                        </div>

                        <div class="form-check form-check-inline">

                            <input class="form-check-input" type="radio" name="barcode_option" id="barcode_custom" value="barcode_custom">

                            <label class="form-check-label" for="barcode_custom">Barcode Custom</label>

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

                    $('.Consigneeaddress2').html(data.html);

                } else if (data.success === false) {
                $('.printButton').prop('disabled', true);
                $('.validerror').text('Please enter a valid pincode.');

                Swal.fire({
                    icon: "error",
                    title: "Pincode Not Allowed",
                    text: "The pincode you entered is currently not serviceable. Please try a different pincode or contact support for assistance.",
                    confirmButtonText: "Okay",
                });
            }

           },
        error: function () {
            $('.validerror').text('Something went wrong. Please try again later.');

            Swal.fire({
                icon: "error",
                title: "Server Error",
                text: "Unable to fetch location details at the moment. Please try again later.",
                confirmButtonText: "Close",
            });
        }

        });

    });


     $('.Consigneeaddress2').on('change', function() {
       var data = $(this).val();
       $('#ConsigneeAddress').val(data);
    });



    $('#PickupPincode').on('change', function() {

        var pincode = $('#PickupPincode').val();

        $.ajax({

            type: 'GET',

            url: "{{url('admin/city-state')}}" + '/' + pincode,

            success: function(data) {

                if (data.success === true) {

                    $('.customer_error').empty();

                    $('#PickupCity').empty().val(data.district);

                    $('#PickupState').empty().val(data.state);

                }  else {
                      $('.printButton').prop('disabled', true);
                    $('.validerror').text('Please enter valid pincode');
                     Swal.fire({
                    icon: "error",
                    title: "Pincode Not Allowed",
                    text: "The pincode you entered is currently not serviceable. Please try a different pincode or contact support for assistance.",
                    confirmButtonText: "Okay",
                });

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

<!-- Include Leaflet.js (Open Source) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>


<script>
    $(document).ready(function() {

        // Function to get coordinates from Pincode using OpenStreetMap Nominatim API
        function getCoordinates(pincode) {
            var geocodingApiUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + pincode;

            $.get(geocodingApiUrl, function(data) {
                if (data.length > 0) {
                    var location = data[0];
                    showMap(location.lat, location.lon);
                } else {
                    alert('Wrong pincode');
                }
            });
        }

        // Function to show the map using Leaflet.js
        function showMap(lat, lng) {
            // Remove existing map instance if exists
            if (window.myMap) {
                window.myMap.remove();
            }

            // Initialize Leaflet map
            window.myMap = L.map('map').setView([lat, lng], 10);

            // Add OpenStreetMap Tile Layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(window.myMap);

            // Add marker at the location
            L.marker([lat, lng]).addTo(window.myMap)
                .bindPopup("Pincode Location")
                .openPopup();
        }

        // Click event to fetch and display map
        $('.google-map-image').on('click', function() {
            let pincode = $(this).closest('ul').find('.pincode').val();
            getCoordinates(pincode);
        });

    });
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

                from: from,

                to: to,

                weight: weight

            };

            var csrfToken = "{{ csrf_token() }}";

            $.ajax({

                type: 'POST',

                url: "{{ route('franchise.india-post-business.showPrice') }}",

                headers: {

                    'X-CSRF-TOKEN': csrfToken

                },

                data: data,

                success: function(response) {

                    if (response.status === 'success') {

                        $(".total-text").text('Total :')

                        $('.totalp').text(`₹ ${response.data.total}`);

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
    let count = 0; // Global counter for successful submissions
    let parcelIdList = []; // Array to store parcel IDs

    $(document).ready(function() {
        $("#parcel-form").submit(function(event) {
            console.log("Form Submitted");
            event.preventDefault();

            var form = $(this);
            var formData = form.serializeArray();
            var keepPickupDetails = false;

             var cphpaymentMethod = $('#paymentMethod').val();
             var cphpaymentType = $('#paymentType').val();
             var cphValue = $('#pickup-details').val();

// Show alert for all conditions EXCEPT when both franchise + prepaid are selected
if (!(cphpaymentMethod === 'franchise')) {

    if (!cphValue) {
        Swal.fire({
            icon: 'warning',
            title: 'Customer Not Registered',
            text: 'No customer is available for the selected pickup option. Please register the customer or choose a different option.',
        });

        return; // Prevent further execution
    }
}

            // Check if #auto_backup_db is checked
            if ($("#auto_backup_db").is(":checked")) {
                formData.push({ name: "static", value: 1 });
                keepPickupDetails = true;
            }

            var csrfToken = "{{ csrf_token() }}";

            $.ajax({
                type: "POST",
                url: "{{ route('franchise.india-post-business.store') }}",
                headers: { "X-CSRF-TOKEN": csrfToken },
                data: formData,

                 beforeSend: function() {
                     $(".parcel-submit-btn").text("Processing...");
                     $(".parcel-submit-btn").prop("disabled", true);
                    $(".invalid-feedback, .error-message").hide();
                    $(".from-submitted").hide();
                 },

                 success: function (response) {
                    if (response.status == 200) {
                        // Reset form unless checkbox is checked
                        if (!keepPickupDetails) {
                            form[0].reset();
                        }

                        $(".parcel-submit-btn").text("Booking now").prop("disabled", false);
                        $(".from-submitted").text(response.message).show();
                        $(".gotogo-balance").text(parseFloat(response.franchiseDetails.indiapost_balance).toFixed(2));
                        $(".credit-balance").text(parseFloat(response.franchiseDetails.india_credit_amount).toFixed(2));
                        $(".barcode-available").text(parseFloat(response.barcode_available));

                        // Refill pickup details
                        $("#PickupName").val(response.data.PickupName);
                        $("#PickupMobile").val(response.data.PickupMobile);
                        $("#PickupEmail").val(response.data.PickupEmail);
                        $("#PickupPincode").val(response.data.PickupPincode);
                        $("#PickupState").val(response.data.PickupState);
                        $("#PickupCity").val(response.data.PickupCity);
                        $("#PickupAddress").val(response.data.PickupAddress);
                        $("#PickupGstNo").val(response.data.PickupGstNo);

                        //  $("#ConsigneeName").val(response.data.ConsigneeName);
                        $("#ConsigneePincode").val(response.data.ConsigneePincode);
                        $("#ConsigneeCity").val(response.data.ConsigneeCity);
                        $("#ConsigneeState").val(response.data.ConsigneeState);
                        $("#ConsigneeAddress").val(response.data.ConsigneeAddress);
                        $("#ConsigneeMobile").val(response.data.ConsigneeMobile);
                        $("#ConsigneeEmail").val(response.data.ConsigneeEmail);
                        $(".Consigneereset").show();

                        $(".Consigneeaddress2").val(response.data.ConsigneeAddress);

                         $('#package_weight').val(response.data.package_weight)

                         $('#package_length').val(response.data.package_length)

                         $('#package_width').val(response.data.package_width)

                         $('#package_height').val(response.data.package_height)

                         $('#register_amount').val(response.data.register_amount)
                        // $('#other_service_charge').val(response.data.other_service_charge)
                        // $('#fuel_charge').val(response.data.fuel_charge)
                        // $('#pickup_charge').val(response.data.pickup_charge)

                        $(".showpricedetails").hide();

                        $(".clickcountshow").show();
                    

                        // Optional: Update payment method, mobile, and email fields
                        if (response.payment_method) {
                            $("#paymentMethod").val(response.payment_method);
                        }


                        if (response.customer_id) {
                            $("#pickup-details").val(response.customer_id);
                        }

                        if (response.payment_type) {
                            $("#payment_type").val(response.payment_type);
                        }

                        if (response.payment_type_cod) {
                            $("#paymentType").val(response.payment_type_cod);
                        }

                        if (response.payment_type_cod == 'cod') {
                            $("#codallprint").show();
                        }else{
                            $("#allprint").show();
                        }

                        if (response.mobile) {
                            $("#mobile").val(response.mobile).trigger("change");
                        }

                        if (response.email) {
                            $("#email").val(response.email).trigger("change");
                        }

                        // Update counter
                        $('.aa').show();
                        count++;
                        $("#clickCount").text(count);
                        $("#printNumber").val(count);

                        // Track parcel IDs
                        if (response.parcel_id) {
                            parcelIdList.push(response.parcel_id);
                            $("#parcel_id").val(parcelIdList.join(",")); // Join if needed as comma-separated string
                        }
                    } else if (response.status == 400) {
                        $(".parcel-submit-btn").text("Book Now").prop("disabled", false);

                        // Handle various validation error messages
                        if (response.message === "fill valid origin pincode") {
                            $("#PickupPincode").siblings(".invalid-feedback").text("Fill valid origin pincode").show();
                        }

                        if (response.message === "fill valid distination pincode") {
                            $("#ConsigneePincode").siblings(".invalid-feedback").text("Fill valid destination pincode").show();
                        }

                        if (response.message === "barcode series end") {
                            $(".from-submitted").text("Barcode series end").show();
                        }

                        if (response.message === "Balance Low") {
                            $(".from-submitted").text(response.message).show();
                        }
                    }
                },

                error: function (jqXHR) {
                    $(".parcel-submit-btn").text("Booking now").prop("disabled", false);

                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        $.each(jqXHR.responseJSON.errors, function (key, messages) {
                            $(`#${key}`).siblings(".invalid-feedback").text(messages[0]).show();
                        });
                    }
                    $(".error-message").text("An error occurred. Please try again.").show();
                    console.error("AJAX error:", jqXHR);
                }
            });

            return false; // Prevent default form submit
        });
    });

     $('.Consigneereset').on('click',function(){
                        $("#ConsigneePincode").val('');
                        $("#ConsigneeCity").val('');
                        $("#ConsigneeState").val('');
                        $("#ConsigneeAddress").val('');
                        $("#ConsigneeMobile").val('');
                        $("#ConsigneeEmail").val('');
                        $("#ConsigneeName").val('');
                        $(".Consigneeaddress2").val('');
                        
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


<script>
    $(document).ready(function() {
        let debounceTimer;

        $('.charge-input').on('input', function() {
            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(function() {
                const from = $('#PickupPincode').val();
                const to = $('#ConsigneePincode').val();
                // const weight = $('#package_weight').val();
                const weight = $('#computed_weight').val();
                const amount = $('#amount').val();
                const fuel_charge = $('#fuel_charge').val();
                const pickup_charge = $('#pickup_charge').val();
                const other_service_charge = $('#other_service_charge').val();
                const register_amount = $('#register_amount').val();
                   
                if (!weight || !from || !to) {
                    return;
                }

                $.ajax({
                    url: "{{ route('franchise.india-post-business.showPrice') }}",
                    type: 'POST',
                    data: {
                        amount: amount,
                        from: from,
                        to: to,
                        weight: weight,
                        fuel_charge: fuel_charge,
                        pickup_charge: pickup_charge,
                        other_service_charge: other_service_charge,
                        register_amount:register_amount,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            // $('#showamount').text(response.data.price ? '₹ ' + response.data.price : '');
                            // $('#showfuelcharge').text(response.data.fuel_charge ? '₹ ' + response.data.fuel_charge : '');
                            // $('#showpickupcharge').text(response.data.pickup_charge ? '₹ ' + response.data.pickup_charge : '');
                            // $('#showothercharge').text(response.data.other_service_charge ? '₹ ' + response.data.other_service_charge : '');
                            // $('#showgst').text(response.data.gst ? '₹ ' + response.data.gst : '');
                            // $('#showtotal').text(response.data.total ? '₹ ' + response.data.total : '');
                            // $('.showpricedetails').css('display', 'flex');
                        } else {
                            $('#message').text(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                    }
                });
            }, 2000); // 3 seconds debounce
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let barcodeInput = document.getElementById("barcodeInput");
        let lastInputTime = 0; // Track karega input aane ka time

        barcodeInput.addEventListener("input", function(event) {
            let barcodeValue = event.target.value.trim();
            lastInputTime = Date.now(); // Har input pe time update karo

            clearTimeout(window.barcodeTimeout);
            window.barcodeTimeout = setTimeout(() => {
                if (barcodeValue.length > 0) {
                    event.target.value = barcodeValue; // Input box me set karo
                    // ✅ Barcode input ka error hatao
                    barcodeInput.classList.remove("is-invalid");
                    let errorMessage = barcodeInput.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains("invalid-feedback")) {
                        errorMessage.style.display = "none"; // Hide error message
                    }
                }
            }, 300); // 300ms debounce taaki scanner ke complete hone ka wait kare
        });

        // ✅ Enter key filter: Scanner ki automatic Enter ignore kare, manual allow kare
        barcodeInput.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                let timeDiff = Date.now() - lastInputTime;

                if (timeDiff < 100) {
                    // Scanner ne Enter press kiya, ignore karo
                    event.preventDefault();
                    console.log("Scanner Enter ignored.");
                } else {
                    // Manual Enter press hua, allow karo
                    console.log("Manual Enter detected.");
                    // Yahan API call ya form submission kar sakte ho
                }
            }
        });
    });
</script>

<script>
     function clearPickupFields() {
        $('#PickupName, #PickupMobile, #PickupEmail, #PickupPincode, #PickupCity, #PickupState, #PickupAddress, #PickupGstNo').val('');
    }
    
       $('#paymentMethod').on('change', function (e) {
    var id = $(this).val();
    var csrfToken = "{{ csrf_token() }}";
    // Reset payment_type and pickup-details dropdowns
    $('#payment_type').html('<option selected disabled>Select an option</option>');
    $('#pickup-details').html('<option selected disabled>Select an option</option>');

    // Show mobile label accordingly
    let label = 'Pickup Boy';
    if (id === 'franchise') label = 'Franchise';
    else if (id === 'manager') label = 'Market Manager';

    $('#mobile').show();
    $('#mobilespan').html(`<span>${label}</span>`);
    $('#mobile').prop('readonly', false);

    clearPickupFields();

    // AJAX to populate #payment_type
    $.ajax({
        type: 'POST',
        url: "{{ route('franchise.india-post-business.booking.get.details') }}",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            id: id,
            select: id
        },
        success: function (data) {
            if (data && data.html) {
                $('#payment_type').html(data.html);
            } else {
                $('.validerror').text('Please enter a valid pincode');
            }
        }
    });
});

// On change of payment_type → populate pickup-details
$('#paymentType').on('change', function (e) {
    var bookingType = $(this).val();
    var select = $('#payment_type').val();
    var type = $('#paymentMethod').val();
    var csrfToken = "{{ csrf_token() }}";
     clearPickupFields();
    // Reset pickup-details
    $('#pickup-details').html('<option selected disabled>Select an option</option>');

    $.ajax({
        type: 'POST',
        url: "{{ route('franchise.india-post-business.mobile.pickup-details.details') }}",
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            id: select,
            select: select,
            type: type,
            bookingType:bookingType,
        },
        success: function (data) {
            if (data && data.html) {
                $('#pickup-details').html(data.html);
            } else {
                $('.validerror').text('Please enter a valid pincode');
            }
        }
    });
});


    $('#pickup-details').on('change', function(e) {
         var select = $(this).val();
         var bookingType = $('#paymentType').val();
         var csrfToken = "{{ csrf_token() }}";
        $.ajax({
             type: 'POST',
             url: "{{ route('franchise.india-post-business.email.pickup-details.details') }}",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                id: e.target.value,select:select,bookingType:bookingType,
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
                    $('#PickupGstNo').val(data.gst_number);
                } else {
                    $('.validerror').text('Please enter a valid pincode');
                    $('#PickupMobile').val(e.target.value);
                }
            }
        });
    });

</script>

<script type="text/javascript">

    $(document).ready(function () {
    $("#allprint").on('click', function (event) {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: "{{ route('franchise.india-post-business.allprint') }}",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            data: { id: parcelIdList }, // ✅ Correct format

            success: function(response) {
    var iframe = document.createElement('iframe');
    iframe.style.position = 'absolute';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);

    iframe.onload = function () {
        setTimeout(function () {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            document.body.removeChild(iframe);
        }, 1000); // 🔁 1 second delay (or use image onload)
    };

    iframe.contentDocument.open();
    iframe.contentDocument.write(response.otherPageContent);
    iframe.contentDocument.close();
}


            
        });

    });
});


// Weight price

     $(document).ready(function () {
  let debounceTimer;

  // any time actual weight changes, store that base value and recalc
  $('#package_weight').on('input', function () {
    recalcWeightAndPrice();
  });

  // any time L/W/H changes, recalc
  $('#package_length, #package_width, #package_height')
    .on('input', function () {
      recalcWeightAndPrice();
    });  

  // any other .charge-input (pincodes, charges) should also trigger price
  $('.charge-input').not('#package_weight, #package_length, #package_width, #package_height')
    .on('input', function () {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(fetchPrice, 800);
    });

  function recalcWeightAndPrice() {
    clearTimeout(debounceTimer);
    // get base actual weight
    const actual = parseFloat($('#package_weight').val()) || 0;
    const L = parseFloat($('#package_length').val()) || 0;
    const W = parseFloat($('#package_width').val()) || 0;
    const H = parseFloat($('#package_height').val()) || 0;

    let total = actual;
    if (L && W && H) {
      // volumetric in grams
      total += ((L * W * H) / 6000) * 1000;
    }

    $('#computed_weight').val(Math.round(total));
    // $('#package_weight').show();
    // now fetch price with new computed_weight
    debounceTimer = setTimeout(fetchPrice, 800);
  }

  function fetchPrice() {
    const type = 3;
    const from = $('#PickupPincode').val();
    const to   = $('#ConsigneePincode').val();
    const weight = $('#computed_weight').val();
    const amount = $('#amount').val();
    const fuel   = $('#fuel_charge').val();
    const pick   = $('#pickup_charge').val();
    const other  = $('#other_service_charge').val();
    const register_amount = $('#register_amount').val();

    if (!from || !to || !weight) return;

    $.ajax({
      url: "{{ route('franchise.india-post-business.showPrice') }}",
      method: 'POST',
      data: {from, to, weight, amount,fuel_charge: fuel,pickup_charge: pick,other_service_charge: other,register_amount:register_amount,type:type,
        _token: '{{ csrf_token() }}'
      },
      beforeSend() {
        $('#showtotal').text('...');
      },
      success(resp) {
        if (resp.status === 'success') {

            // $('.economic').val(resp.data.economictotal ? '₹'+resp.data.economictotal : '');
            // $('.fastest').val(resp.data.economictotal ? '₹'+resp.data.economictotal : '');
            $('#textfastest').html('<span style="color: blue;">' + resp.data.total + '</span>');
            $('#texteconomic').html('<span style="color: blue;">' + resp.data.economictotal + '</span>');

        //   $('#showamount').text(resp.data.price ? '₹'+resp.data.price : '');
        //   $('#showfuelcharge').text(resp.data.fuel_charge? '₹'+resp.data.fuel_charge:'');
        //   $('#showpickupcharge').text(resp.data.pickup_charge? '₹'+resp.data.pickup_charge:'');
        //   $('#showothercharge').text(resp.data.other_service_charge? '₹'+resp.data.other_service_charge:'');
        //   $('#showgst').text(resp.data.gst? '₹'+resp.data.gst:'');
        //   $('#showtotal').text(resp.data.total? '₹'+resp.data.total:'');
        //   $('#totalOther').val(resp.data.total? resp.data.total:'');
        //   $('.showpricedetails').show();
        } else {
          $('#message').text(resp.message);
        }
      },
      error() {
        $('#message').text('Failed to fetch price');
      }
    });
  }
});

  $('.economic').on('click', function() {
    const type = 1;
    const from = $('#PickupPincode').val();
    const to   = $('#ConsigneePincode').val();
    const weight = $('#computed_weight').val();
    const amount = $('#amount').val();
    const fuel   = $('#fuel_charge').val();
    const pick   = $('#pickup_charge').val();
    const other  = $('#other_service_charge').val();
    const register_amount = $('#register_amount').val();

    if (!from || !to || !weight) return;

    $.ajax({
      url: "{{ route('franchise.india-post-business.showPrice') }}",
      method: 'POST',
      data: {from, to, weight, amount,fuel_charge: fuel,pickup_charge: pick,other_service_charge: other,register_amount:register_amount,type:type,
        _token: '{{ csrf_token() }}'
      },
      beforeSend() {
        $('#showtotal').text('...');
      },
      success(resp) {
        if (resp.status === 'success') {
          $('#showamount').text(resp.data.economicprice ? '₹'+resp.data.economicprice : '');
          $('#showfuelcharge').text(resp.data.fuel_charge? '₹'+resp.data.fuel_charge:'');
          $('#showpickupcharge').text(resp.data.pickup_charge? '₹'+resp.data.pickup_charge:'');
          $('#showothercharge').text(resp.data.other_service_charge? '₹'+resp.data.other_service_charge:'');
          $('#showgst').text(resp.data.economicgst? '₹'+resp.data.economicgst:'');
          $('#showtotal').text(resp.data.economictotal? '₹'+resp.data.economictotal:'');
          $('#totalOther').val(resp.data.economictotal? resp.data.economictotal:'');
          $('.showpricedetails').show();
          $('#showtype').val(type);
        } else {
          $('#message').text(resp.message);
        }
      },
      error() {
        $('#message').text('Failed to fetch price');
      }
    });
  });


  $('.fastest').on('click', function() {
    const type = 2;
    const from = $('#PickupPincode').val();
    const to   = $('#ConsigneePincode').val();
    const weight = $('#computed_weight').val();
    const amount = $('#amount').val();
    const fuel   = $('#fuel_charge').val();
    const pick   = $('#pickup_charge').val();
    const other  = $('#other_service_charge').val();
    const register_amount = $('#register_amount').val();

    if (!from || !to || !weight) return;

    $.ajax({
      url: "{{ route('franchise.india-post-business.showPrice') }}",
      method: 'POST',
      data: {from, to, weight, amount,fuel_charge: fuel,pickup_charge: pick,other_service_charge: other,register_amount:register_amount,type:type,
        _token: '{{ csrf_token() }}'
      },
      beforeSend() {
        $('#showtotal').text('...');
      },
      success(resp) {
        if (resp.status === 'success') {
          $('#showamount').text(resp.data.price ? '₹'+resp.data.price : '');
          $('#showfuelcharge').text(resp.data.fuel_charge? '₹'+resp.data.fuel_charge:'');
          $('#showpickupcharge').text(resp.data.pickup_charge? '₹'+resp.data.pickup_charge:'');
          $('#showothercharge').text(resp.data.other_service_charge? '₹'+resp.data.other_service_charge:'');
          $('#showgst').text(resp.data.gst? '₹'+resp.data.gst:'');
          $('#showtotal').text(resp.data.total? '₹'+resp.data.total:'');
          $('#totalOther').val(resp.data.total? resp.data.total:'');
          $('.showpricedetails').show();
          $('#showtype').val(type);
        } else {
          $('#message').text(resp.message);
        }
      },
      error() {
        $('#message').text('Failed to fetch price');
      }
    });
  });


$('#paymentType').on('change', function() {
    var id = $(this).val();

    if (id == 'cod') {
        $('.codselect').html('<span>Cod</span>');
       $('#amount').prop('readonly', false);
    } else {
        $('.codselect').html('<span>Prepaid</span>');
       $('#amount').prop('readonly', true);
        $('#amount').val('');
        $('#showcodamount').html('<span class="float-right"> </span>');
    }
});

 $('#amount').on('keyup', function() {
    var id = $(this).val();
    $('#showcodamount').html('<span class="float-right">₹ ' + id + '</span>');
});

 $('#register_amount').on('keyup', function() {
    var amount = $(this).val();
    if(amount){
    var id = 5;
    $('#showregister_amount').html('<span class="float-right">₹ ' + id + '</span>');
    $('#register_amount').val(5);
    }else{
        $('#showregister_amount').hide();
    }
});
</script>
<script>
    $('.economic').on('click', function(){
        
    });
</script>

<script>
    document.getElementById('PickupGstNo').addEventListener('input', function (e) {
        e.target.value = e.target.value.toUpperCase();
    });
</script>

@endpush

@endsection