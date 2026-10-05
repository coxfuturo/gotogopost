@extends('admin.layouts.master')

@section('title') Business Associate Management @endsection

@section('content')

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


{{-- ================= PROFILE ================= --}}

<div class="card mb-0">
    <div class="card-body">

        <div class="row">
            <div class="col-md-12">

                <div class="profile-view">

                    <div class="profile-img-wrap">
                        <div class="profile-img">

                            @php
                                $photos = !empty(optional($customer->kyc)->photo)
                                    ? explode(',', $customer->kyc->photo)
                                    : [];

                                $firstPhoto = !empty($photos[0])
                                    ? asset('admin/e_customer/' . $customer->generated_id . '/' . trim($photos[0]))
                                    : asset('admin/assets/img/profiles/avatar-02.jpg');
                            @endphp

                            <a href="#">
                                <img src="{{ $firstPhoto }}" alt="Profile Image">
                            </a>

                        </div>
                    </div>

                    <div class="profile-basic">

                        <div class="row">

                            <div class="col-md-5">

                                <div class="profile-info-left">

                                    <h3 class="user-name m-t-0 mb-0">
                                        {{ $customer->name }}
                                    </h3>

                                    <h5>
                                        Director/Owner Name :
                                        {{ $customer->father_name }}
                                    </h5>

                                    <div class="staff-id">
                                        ID : {{ $customer->generated_id }}
                                    </div>

                                    <div class="doj">
                                        Date of Registration :
                                        {{ date('d M Y', strtotime($customer->created_at)) }}
                                    </div>

                                    <div class="staff-msg">

                                        <a class="btn btn-custom"
                                           href="{{ route('admin.support-ticket.get_details', $customer->id) }}">
                                            Send Message
                                        </a>

                                    </div>

                                </div>

                            </div>


                            <div class="col-md-7">

                                <ul class="personal-info">

                                    <li>
                                        <div class="title">Phone:</div>
                                        <div class="text">
                                            <a href="#">{{ $customer->mobile }}</a>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="title">Email:</div>
                                        <div class="text">
                                            <a href="#">{{ $customer->email }}</a>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="title">City/District:</div>
                                        <div class="text">
                                            {{ $customer->city }}/{{ $customer->district }}
                                        </div>
                                    </li>

                                    <li>
                                        <div class="title">State:</div>
                                        <div class="text">
                                            {{ $customer->state }}
                                        </div>
                                    </li>

                                    <li>
                                        <div class="title">Address:</div>
                                        <div class="text">
                                            {{ $customer->address }}
                                        </div>
                                    </li>

                                    <li>
                                        <div class="title">Payment:</div>
                                        <div class="text">

                                            @if($customer->payment_status == 1)
                                                <span class="badge bg-success">
                                                    Paid
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    Pending
                                                </span>
                                            @endif

                                        </div>
                                    </li>

                                    <li>
                                        <div class="title">Status:</div>

                                        <div class="text">

                                            <select class="select"
                                                    id="status"
                                                    onchange="status_update('{{ $customer->id }}')">

                                                <option value="1"
                                                    {{ $customer->status == 1 ? 'selected' : '' }}>
                                                    Active
                                                </option>

                                                <option value="0"
                                                    {{ $customer->status == 0 ? 'selected' : '' }}>
                                                    Inactive
                                                </option>

                                            </select>

                                        </div>
                                    </li>

                                </ul>

                            </div>

                        </div>

                    </div>

                    <div class="pro-edit">
                        <a data-bs-target="#profile_info"
                           data-bs-toggle="modal"
                           class="edit-icon"
                           href="#">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>


{{-- ================= TABS ================= --}}

<div class="card tab-box">

    <div class="row user-tabs">

        <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">

            <ul class="nav nav-tabs nav-tabs-bottom">

                <li class="nav-item">
                    <a href="#emp_profile"
                       data-bs-toggle="tab"
                       class="nav-link active">
                        Profile
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#assigned_services"
                       data-bs-toggle="tab"
                       class="nav-link">
                        Assigned Services
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#customer_commission"
                       data-bs-toggle="tab"
                       class="nav-link">
                        Business Associate Commission
                    </a>
                </li>

            </ul>

        </div>

    </div>

</div>


<div class="tab-content">


{{-- ================= PROFILE TAB ================= --}}

<div id="emp_profile"
     class="pro-overview tab-pane fade show active">

    <div class="row">


        {{-- BANK INFORMATION --}}

        <div class="col-md-6 d-flex">

            <div class="card profile-box flex-fill">

                <div class="card-body">

                    <h3 class="card-title">
                        Bank information
                    </h3>

                    <ul class="personal-info">

                        <li>
                            <div class="title">Bank name</div>
                            <div class="text">
                                {{ optional($customer->kyc)->bank_name }}
                            </div>
                        </li>

                        <li>
                            <div class="title">Branch name</div>
                            <div class="text">
                                {{ optional($customer->kyc)->branch_name }}
                            </div>
                        </li>

                        <li>
                            <div class="title">Bank account No.</div>
                            <div class="text">
                                {{ optional($customer->kyc)->account_number }}
                            </div>
                        </li>

                        <li>
                            <div class="title">IFSC Code</div>
                            <div class="text">
                                {{ optional($customer->kyc)->ifsc_code }}
                            </div>
                        </li>

                        <li>
                            <div class="title">Pan No</div>
                            <div class="text">
                                {{ optional($customer->kyc)->pan_card }}
                            </div>
                        </li>

                        <li>
                            <div class="title">Adhar No</div>
                            <div class="text">
                                {{ optional($customer->kyc)->adhar_card }}
                            </div>
                        </li>

                    </ul>

                </div>

            </div>

        </div>


        {{-- DOCUMENTS --}}

        <div class="col-md-6 d-flex">

            <div class="card profile-box flex-fill">

                <div class="card-body">

                    <h3 class="card-title">
                        Documents
                    </h3>

                    <ul class="personal-info">

                        <div class="d-flex justify-content-between">

                            <div style="width:100%;text-align:center">

                                <li class="d-flex justify-content-between px-3">

                                    <div class="document-title">
                                        Adhar Card
                                    </div>

                                    <div data-bs-toggle="modal"
                                         data-bs-target="#adhar_view">

                                        <button class="btn btn-sm btn-dark">
                                            View
                                        </button>

                                    </div>

                                </li>


                                <li class="d-flex justify-content-between px-3">

                                    <div class="document-title">
                                        Pan Card
                                    </div>

                                    <div data-bs-toggle="modal"
                                         data-bs-target="#pan_view">

                                        <button class="btn btn-sm btn-dark">
                                            View
                                        </button>

                                    </div>

                                </li>


                                <li class="d-flex justify-content-between px-3">

                                    <div class="document-title">
                                        Cancel Cheque
                                    </div>

                                    <div data-bs-toggle="modal"
                                         data-bs-target="#cheque_view">

                                        <button class="btn btn-sm btn-dark">
                                            View
                                        </button>

                                    </div>

                                </li>

                            </div>


                            <div style="width:100%;text-align:center">

                                <li class="d-flex justify-content-between px-3">

                                    <div class="document-title">
                                        Business Associate Image
                                    </div>

                                    <div data-bs-toggle="modal"
                                         data-bs-target="#customer_view">

                                        <button class="btn btn-sm btn-dark">
                                            View
                                        </button>

                                    </div>

                                </li>


                                <li class="d-flex justify-content-between px-3">

                                    <div class="document-title">
                                        Other Document
                                    </div>

                                    <div data-bs-toggle="modal"
                                         data-bs-target="#other_document">

                                        <button class="btn btn-sm btn-dark">
                                            View
                                        </button>

                                    </div>

                                </li>


                                <li class="d-flex justify-content-between px-3">

                                    <div class="document-title">
                                        Video KYC
                                    </div>

                                    <div data-bs-toggle="modal"
                                         data-bs-target="#video_kyc">

                                        <button class="btn btn-sm btn-dark">
                                            View
                                        </button>

                                    </div>

                                </li>

                            </div>

                        </div>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================= AADHAR MODAL ================= --}}

<div class="modal custom-modal fade"
     id="adhar_view"
     role="dialog">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="form-header">
                    <h3>Adhar Card</h3>
                </div>

                <div class="modal-btn delete-action">

                    <div class="row">

                        @if(!empty(optional($customer->kyc)->adhar_front_img))

                            @foreach(explode(',', $customer->kyc->adhar_front_img) as $image)

                                <div class="col-md-4">

                                    <img src="{{ asset('admin/e_customer/' . $customer->generated_id . '/' . trim($image)) }}"
                                         alt="Adhar Front"
                                         class="img-thumbnail">

                                </div>

                            @endforeach

                        @endif

                    </div>


                    <div class="row">

                        @if(!empty(optional($customer->kyc)->adhar_back_img))

                            @foreach(explode(',', $customer->kyc->adhar_back_img) as $image)

                                <div class="col-md-4">

                                    <img src="{{ asset('admin/e_customer/' . $customer->generated_id . '/' . trim($image)) }}"
                                         alt="Adhar Back"
                                         class="img-thumbnail">

                                </div>

                            @endforeach

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================= PAN MODAL ================= --}}

<div class="modal custom-modal fade"
     id="pan_view"
     role="dialog">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="form-header">
                    <h3>Pan Card</h3>
                </div>

                <div class="modal-btn delete-action">

                    <div class="row">

                        @if(!empty(optional($customer->kyc)->pan_img))

                            @foreach(explode(',', $customer->kyc->pan_img) as $image)

                                <div class="col-md-4">

                                    <img src="{{ asset('admin/e_customer/' . $customer->generated_id . '/' . trim($image)) }}"
                                         alt="PAN Image"
                                         class="img-thumbnail">

                                </div>

                            @endforeach

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================= CHEQUE MODAL ================= --}}

<div class="modal custom-modal fade"
     id="cheque_view"
     role="dialog">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="form-header">
                    <h3>Cancel Cheque</h3>
                </div>

                <div class="modal-btn delete-action">

                    <div class="row">

                        @if(!empty(optional($customer->kyc)->cheque_img))

                            @foreach(explode(',', $customer->kyc->cheque_img) as $image)

                                <div class="col-md-4">

                                    <img src="{{ asset('admin/e_customer/' . $customer->generated_id . '/' . trim($image)) }}"
                                         alt="Cheque Image"
                                         class="img-thumbnail">

                                </div>

                            @endforeach

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================= CUSTOMER PHOTO MODAL ================= --}}

<div class="modal custom-modal fade"
     id="customer_view"
     role="dialog">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="form-header">
                    <h3>Business Associate Image</h3>
                </div>

                <div class="modal-btn delete-action">

                    <div class="row">

                        @if(!empty(optional($customer->kyc)->photo))

                            @foreach(explode(',', $customer->kyc->photo) as $image)

                                <div class="col-md-4">

                                    <img src="{{ asset('admin/e_customer/' . $customer->generated_id . '/' . trim($image)) }}"
                                         alt="Photo"
                                         class="img-thumbnail">

                                </div>

                            @endforeach

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================= OTHER DOCUMENT ================= --}}

<div class="modal custom-modal fade"
     id="other_document"
     role="dialog">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="form-header">
                    <h3>Other Document</h3>
                </div>

                <div class="modal-btn delete-action">

                    <div class="row">

                        @if(!empty(optional($customer->kyc)->other_document))

                            @foreach(explode(',', $customer->kyc->other_document) as $image)

                                <div class="col-md-4">

                                    <img src="{{ asset('admin/e_customer/' . $customer->generated_id . '/' . trim($image)) }}"
                                         alt="Other Document"
                                         class="img-thumbnail">

                                </div>

                            @endforeach

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================= VIDEO KYC ================= --}}

<div class="modal custom-modal fade"
     id="video_kyc"
     role="dialog">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="form-header">
                    <h3>Video KYC</h3>
                </div>

                <div class="modal-btn delete-action">

                    @if(!empty(optional($customer->kyc)->video_kyc))

                        <video width="640"
                               height="480"
                               controls>

                            <source src="{{ asset('admin/e_customer/' . $customer->generated_id . '/' . $customer->kyc->video_kyc) }}"
                                    type="video/mp4">

                            Your browser does not support the video tag.

                        </video>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================= ASSIGNED SERVICES ================= --}}

<div class="tab-pane fade"
     id="assigned_services">

    <div class="table-responsive table-newdatatable">

        <table class="table table-new custom-table mb-0 datatable">

            <thead class="text-start">

                <tr>

                    <th>Sr.No.</th>
                    <th class="text-start">Service</th>
                    <th class="text-start">Discount Commission</th>
                    <th class="text-start">Status</th>

                </tr>

            </thead>

            <tbody class="text-start">


                {{-- GOTOGO BUSINESS --}}

                <tr>

                    <td>1</td>

                    <td>Gotogo Post Business Parcel</td>

                    <td>

                        <a href="{{ route('admin.commission.index', ['membertype' => 'e_customer']) }}"
                           class="btn btn-white btn-sm btn-rounded">

                            <i class="fa-regular fa-circle-dot text-success"></i>
                            View Commission

                        </a>

                    </td>

                    <td>

                        <div class="dropdown action-label">

                            @if($customer->gotogo_business_parcel == 1)

                                <a href="#"
                                   class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                   data-bs-toggle="dropdown">

                                    <i class="fa-regular fa-circle-dot text-success"></i>
                                    Active

                                </a>

                            @else

                                <a href="#"
                                   class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                   data-bs-toggle="dropdown">

                                    <i class="fa-regular fa-circle-dot text-danger"></i>
                                    Inactive

                                </a>

                            @endif

                            <div class="dropdown-menu active-inactive-menu-3">

                                <a class="dropdown-item"
                                   onclick="service_status_update('{{ $customer->id }}','3','1')"
                                   href="#">

                                    <i class="fa-regular fa-circle-dot text-success"></i>
                                    Active

                                </a>

                                <a class="dropdown-item"
                                   onclick="service_status_update('{{ $customer->id }}','3','0')"
                                   href="#">

                                    <i class="fa-regular fa-circle-dot text-danger"></i>
                                    Inactive

                                </a>

                            </div>

                        </div>

                    </td>

                </tr>


                {{-- INDIA POST SPEED --}}

                <tr>

                    <td>2</td>

                    <td>India Post Speed</td>

                    <td>

                        <a href="{{ route('admin.commission.index', ['membertype' => 'e_customer']) }}"
                           class="btn btn-white btn-sm btn-rounded">

                            <i class="fa-regular fa-circle-dot text-success"></i>
                            View Commission

                        </a>

                    </td>

                    <td>

                        <div class="dropdown action-label">

                            @if($customer->india_post_speed == 1)

                                <a href="#"
                                   class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                   data-bs-toggle="dropdown">

                                    <i class="fa-regular fa-circle-dot text-success"></i>
                                    Active

                                </a>

                            @else

                                <a href="#"
                                   class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                   data-bs-toggle="dropdown">

                                    <i class="fa-regular fa-circle-dot text-danger"></i>
                                    Inactive

                                </a>

                            @endif

                            <div class="dropdown-menu active-inactive-menu-5">

                                <a class="dropdown-item"
                                   onclick="service_status_update('{{ $customer->id }}','5','1')"
                                   href="#">

                                    <i class="fa-regular fa-circle-dot text-success"></i>
                                    Active

                                </a>

                                <a class="dropdown-item"
                                   onclick="service_status_update('{{ $customer->id }}','5','0')"
                                   href="#">

                                    <i class="fa-regular fa-circle-dot text-danger"></i>
                                    Inactive

                                </a>

                            </div>

                        </div>

                    </td>

                </tr>


                {{-- INDIA POST BUSINESS --}}

                <tr>

                    <td>3</td>

                    <td>India Post Business</td>

                    <td>

                        <a href="{{ route('admin.commission.index', ['membertype' => 'e_customer']) }}"
                           class="btn btn-white btn-sm btn-rounded">

                            <i class="fa-regular fa-circle-dot text-success"></i>
                            View Commission

                        </a>

                    </td>

                    <td>

                        <div class="dropdown action-label">

                            @if($customer->india_post_business == 1)

                                <a href="#"
                                   class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                   data-bs-toggle="dropdown">

                                    <i class="fa-regular fa-circle-dot text-success"></i>
                                    Active

                                </a>

                            @else

                                <a href="#"
                                   class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                   data-bs-toggle="dropdown">

                                    <i class="fa-regular fa-circle-dot text-danger"></i>
                                    Inactive

                                </a>

                            @endif

                            <div class="dropdown-menu active-inactive-menu-6">

                                <a class="dropdown-item"
                                   onclick="service_status_update('{{ $customer->id }}','6','1')"
                                   href="#">

                                    <i class="fa-regular fa-circle-dot text-success"></i>
                                    Active

                                </a>

                                <a class="dropdown-item"
                                   onclick="service_status_update('{{ $customer->id }}','6','0')"
                                   href="#">

                                    <i class="fa-regular fa-circle-dot text-danger"></i>
                                    Inactive

                                </a>

                            </div>

                        </div>

                    </td>

                </tr>


                {{-- INDIA POST REGISTERED --}}

                <tr>

                    <td>4</td>

                    <td>India Post Registered</td>

                    <td>

                        <a href="{{ route('admin.commission.index', ['membertype' => 'e_customer']) }}"
                           class="btn btn-white btn-sm btn-rounded">

                            <i class="fa-regular fa-circle-dot text-success"></i>
                            View Commission

                        </a>

                    </td>

                    <td>

                        <div class="dropdown action-label">

                            @if($customer->india_post_registered == 1)

                                <a href="#"
                                   class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                   data-bs-toggle="dropdown">

                                    <i class="fa-regular fa-circle-dot text-success"></i>
                                    Active

                                </a>

                            @else

                                <a href="#"
                                   class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                   data-bs-toggle="dropdown">

                                    <i class="fa-regular fa-circle-dot text-danger"></i>
                                    Inactive

                                </a>

                            @endif

                            <div class="dropdown-menu active-inactive-menu-7">

                                <a class="dropdown-item"
                                   onclick="service_status_update('{{ $customer->id }}','7','1')"
                                   href="#">

                                    <i class="fa-regular fa-circle-dot text-success"></i>
                                    Active

                                </a>

                                <a class="dropdown-item"
                                   onclick="service_status_update('{{ $customer->id }}','7','0')"
                                   href="#">

                                    <i class="fa-regular fa-circle-dot text-danger"></i>
                                    Inactive

                                </a>

                            </div>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>


{{-- ================= COMMISSION ================= --}}

<div class="card mt-4"
     id="customer_commission">

    <div class="card-header">

        <h4 class="mb-0">
            Business Associate Commission
        </h4>

    </div>

    <div class="card-body">

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        <form method="POST"
              action="{{ route('admin.e_customer.commission.save', $customer->id) }}">

            @csrf

            <div class="row">

                @foreach([
                    3 => 'Gotogo Business',
                    5 => 'India Post Speed Post',
                    6 => 'India Post Business',
                    7 => 'India Post Registered'
                ] as $serviceType => $serviceName)

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            {{ $serviceName }}
                        </label>

                        <select name="commission[{{ $serviceType }}]"
                                class="form-select"
                                required>

                            <option value="">
                                Select Commission
                            </option>

                            @foreach([5,10,15,20] as $rate)

                                <option value="{{ $rate }}"
                                    {{ isset($customerCommission[$serviceType])
                                        && $customerCommission[$serviceType]->commission_rate == $rate
                                        ? 'selected'
                                        : '' }}>

                                    {{ $rate }}%

                                </option>

                            @endforeach

                        </select>

                    </div>

                @endforeach

            </div>

            <button type="submit"
                    class="btn btn-primary">

                Save Commission

            </button>

        </form>

    </div>

</div>


{{-- ================= PROFILE EDIT MODAL ================= --}}

<div id="profile_info"
     class="modal custom-modal fade"
     role="dialog">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Profile Information
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form>

                    <div class="row">

                        <div class="col-md-6">

                            <label class="col-form-label">
                                Name
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $customer->name }}">

                        </div>

                        <div class="col-md-6">

                            <label class="col-form-label">
                                Father/Husband Name
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $customer->father_name }}">

                        </div>

                        <div class="col-md-6">

                            <label class="col-form-label">
                                Phone Number
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $customer->mobile }}">

                        </div>

                        <div class="col-md-6">

                            <label class="col-form-label">
                                Email
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $customer->email }}">

                        </div>

                        <div class="col-md-12">

                            <label class="col-form-label">
                                Address
                            </label>

                            <textarea class="form-control">{{ $customer->address }}</textarea>

                        </div>

                        <div class="col-md-4">

                            <label class="col-form-label">
                                Pin Code
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $customer->pincode }}">

                        </div>

                        <div class="col-md-4">

                            <label class="col-form-label">
                                State
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $customer->state }}">

                        </div>

                        <div class="col-md-4">

                            <label class="col-form-label">
                                District
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $customer->district }}">

                        </div>

                        <div class="col-md-4">

                            <label class="col-form-label">
                                City
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $customer->city }}">

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


</div>


@push('page-javascript')

<script>

function status_update(id)
{
    var update_status = $('#status').find('option:selected').val();

    $.ajax({

        url: "{{ route('admin.e-customer.status') }}",

        type: "POST",

        data: {

            "_token": "{{ csrf_token() }}",

            id: id,

            status: update_status

        },

        success: function(data)
        {

            if(data.success == true)
            {

                Swal.fire(
                    'Success!',
                    'Status updated',
                    'success'
                );

            }
            else
            {

                Swal.fire(
                    'Error!',
                    'Something went wrong',
                    'error'
                );

            }

        }

    });

}


function service_status_update(customerId, serviceType, status)
{

    $.ajax({

        url: "{{ route('admin.e-customer.serviceStatus') }}",

        method: 'POST',

        data: {

            customer_id: customerId,

            service_type: serviceType,

            status: status,

            _token: '{{ csrf_token() }}'

        },

        success: function(data)
        {

            if(data.success)
            {

                Swal.fire(
                    'Success!',
                    'Status updated',
                    'success'
                );


                var newElement = $(
                    '<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></a>'
                );


                var iconClass =
                    data.status == '0'
                    ? 'text-danger'
                    : 'text-success';


                var statusText =
                    data.status == '0'
                    ? 'Inactive'
                    : 'Active';


                newElement.html(
                    '<i class="fa-regular fa-circle-dot '
                    + iconClass +
                    '"></i> '
                    + statusText
                );


                switch(data.serviceType)
                {

                    case '3':

                        $('.active-inactive-menu-3')
                            .prev()
                            .replaceWith(newElement);

                        break;


                    case '5':

                        $('.active-inactive-menu-5')
                            .prev()
                            .replaceWith(newElement);

                        break;


                    case '6':

                        $('.active-inactive-menu-6')
                            .prev()
                            .replaceWith(newElement);

                        break;


                    case '7':

                        $('.active-inactive-menu-7')
                            .prev()
                            .replaceWith(newElement);

                        break;

                }

            }
            else
            {

                Swal.fire(
                    'Error!',
                    'Something went wrong',
                    'error'
                );

            }

        },

        error: function(xhr)
        {

            console.log(xhr.responseText);

            Swal.fire(
                'Error!',
                'Something went wrong',
                'error'
            );

        }

    });

}

</script>

@endpush

@endsection