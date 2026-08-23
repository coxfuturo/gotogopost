@extends('admin.layouts.master')



@section('title') Delivery Boy Management @endsection


@section('content')


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



<div class="card mb-0">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <img src="{{ isset($data->kyc->photo) ? asset('tenancy/assets/delboy/'.$data->generated_id.'/'.$data->kyc->photo) : asset('admin/assets/img/profiles/avatar-02.jpg') }}" alt="Profile Image">
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0">{{$data->name}}</h3>
                                    <h5 class="">Name : {{$data->name}}</h5>
                                    <h5 class="">Father Name : {{$data->father_name}}</h5>
                                    <div class="staff-id">ID : {{$data->generated_id}}</div>
                                    <div class="staff-id">Delivery Boy : {{$data->delivery_boy_no}}</div>
                                    <div class="doj ">Date of registration : {{date('d M Y',strtotime($data->created_at))}}</div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Phone:</div>
                                        <div class="text"><a href="#">{{$data->mobile}}</a></div>
                                    </li>
                                    <li>
                                        <div class="title">Email:</div>
                                        <div class="text"><a href="#">{{$data->email}}</a></div>
                                    </li>

                                    <li>
                                        <div class="title">City/District:</div>
                                        <div class="text">{{$data->city}}/{{$data->district}}</div>
                                    </li>
                                    <li>
                                        <div class="title">State:</div>
                                        <div class="text">{{$data->state}}</div>
                                    </li>
                                    <li>
                                        <div class="title">Address:</div>
                                        <div class="text">{{$data->address}}</div>
                                    </li>

                                    <li>
                                        <div class="title">Status:</div>
                                        <div class="text">
                                            <select class="select" id="status" onchange="status_update('{{$data->id}}')">
                                                <option value="1" {{$data->status == 1 ? 'selected':''}}>Active</option>
                                                <option value="0" {{$data->status == 0 ? 'selected':''}}>Inactive</option>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="card tab-box">

    <div class="row user-tabs">

        <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">

            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item"><a href="#emp_profile" data-bs-toggle="tab" class="nav-link active">Profile</a></li>
                <li class="nav-item"><a href="#parcel" data-bs-toggle="tab" class="nav-link">Parcels</a></li>
                <li class="nav-item"><a href="#delboy_daily_booking_report" data-bs-toggle="tab" class="nav-link">Daily Booking Report</a></li>
                <li class="nav-item"><a href="#delboy_assigned_services" data-bs-toggle="tab" class="nav-link">Assigned Services</a></li>
            </ul>

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

                                <div class="text">{{$data->kyc->bank_name ?? 'N/A' }}</div>

                            </li>

                            <li>

                                <div class="title">Bank account No.</div>

                                <div class="text">{{$data->kyc->account_number ?? 'N/A' }}</div>

                            </li>

                            <li>

                                <div class="title">IFSC Code</div>

                                <div class="text">{{$data->kyc->ifsc_code ?? 'N/A' }}</div>

                            </li>

                            <li>

                                <div class="title">PAN No</div>

                                <div class="text">{{$data->kyc->pan_card ?? 'N/A' }}</div>

                            </li>

                            <li>

                                <div class="title">Adhar No</div>

                                <div class="text">{{$data->kyc->adhar_card ?? 'N/A' }}</div>

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
                                </div>

                                <div style="width:100%;text-align:center">
                                    <li class="d-flex justify-content-between px-3">
                                        <div class="document-title">Delivery Boy Image</div>
                                        <div data-bs-toggle="modal" data-bs-target="#franchise_view"><button class="btn btn-sm btn-dark">View</button></div>
                                    </li>
                                    <li class="d-flex justify-content-between px-3">
                                        <div class="document-title">Drivary Lances  Image</div>
                                        <div data-bs-toggle="modal" data-bs-target="#view_dl_img"><button class="btn btn-sm btn-dark">View</button></div>
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

    <!-- Parcel Tab -->
    <div class="tab-pane fade" id="parcel">

        <!-- Search Filter -->
        <div class="row">

            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                <div class="input-block mb-3 form-focus">
                    <div class="cal-icon">
                        <input class="form-control floating datetimepicker date" type="text">
                    </div>
                    <label class="focus-label">Date</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-1">
                <div class="d-grid">
                    <button type="submit" onclick="filterParcelsByDate()" class="btn btn-success">Search</button>
                </div>
            </div>
        </div>
        <!-- /Search Filter -->
        <div class="table-responsive table-newdatatable m-0">
            <table class="table table-new custom-table mb-0 datatable">
                <thead>
                    <tr>
                        <th>SN#</th>
                        <th>Service</th>
                        <th>Pickup Name</th>
                        <th>Pickup Pincode</th>
                        <th>Consignee Name</th>
                        <th>Consignee Pincode</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($parcel->count()>0)
                    @foreach ($parcel as $i=>$item)
                    <tr>
                        <td>{{$i+1}}</td>
                        <td>{{$item->service_type}}</td>
                        <td>{{$item->pickup_name}}</td>
                        <td>{{$item->pickup_pincode}}</td>
                        <td>{{$item->consignee_name}}</td>
                        <td>{{$item->consignee_pincode}}</td>
                        <td>
                            <div class="table-actions d-flex">
                                <a class="delete-table me-2" href="{{ route('admin.parcel.view', ['id' => $item->barcode_no, 'service_type' => $item->service_number]) }}">
                                    <img src="{{ asset('admin/assets/img/icons/eye.svg') }}" alt="Eye Icon">
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
    <!-- /Parcel Tab -->


    <!-- Daily booking report -->
    <div class="tab-pane fade" id="delboy_daily_booking_report">
        <div class="table-responsive table-newdatatable">
            <!-- Search Filter -->
            <div class="row">

                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                    <div class="input-block mb-3 form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker bookingdate" type="text">
                        </div>
                        <label class="focus-label">Date</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-1">
                    <div class="d-grid">
                        <button type="submit" onclick="filterDailyBookingsReportByDate()" class="btn btn-success">Search</button>
                    </div>
                </div>
            </div>
            <!-- /Search Filter -->
            <table class="table table-new custom-table mb-0 datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Service</th>
                        <th>No of Articles</th>
                        <th>Total Value</th>
                        <th>Wallet Amount</th>
                        <th>Wallet Balalnce</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($bookingData as $key=>$value)
                    <tr>
                        <td>{{$key}}</td>
                        <td>{{$value['service_type']}}</td>
                        <td>{{$value['No_of_article']}}</td>
                        <td>{{$value['total_value']}}</td>
                        <td>{{$value['wallet_balance']}}</td>
                        <td>{{$value['remaining_balance']}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <!-- Daily booking report -->


    <!-- Assigned Services -->
    <div class="tab-pane fade" id="delboy_assigned_services">
        <div class="table-responsive table-newdatatable">

            <table class="table table-new custom-table mb-0 datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">Service</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- GOTOGO Post Speed -->
                    <tr>
                        <td>1</td>
                        <td>GOTOGO Post Speed</td>
                        <td>
                            <div class="dropdown action-label goto-post-speed-class">
                                @if($data->gotogo_speed_post == 1)
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                @else
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                @endif
                                <div class="dropdown-menu active-inactive-menu-1">
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '1', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '1', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- GOTOGO Post Business Parcel -->
                    <tr>
                        <td>2</td>
                        <td>GOTOGO Post Business Parcel</td>
                        <td>
                            <div class="dropdown action-label goto-post-business-class">
                                @if($data->gotogo_business_parcel == 1)
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                @else
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                @endif
                                <div class="dropdown-menu active-inactive-menu-3">
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '3', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '3', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>


                    <!-- GOTOGO Post Registered -->
                    <tr>
                        <td>3</td>
                        <td>GOTOGO Post Registered</td>
                        <td>
                            <div class="dropdown action-label active-inactive-menu-3">
                                @if($data->gotogo_post_registered == 1)
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                @else
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                @endif
                                <div class="dropdown-menu active-inactive-menu-4">
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '4', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '4', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>


                    <!-- Additional rows -->
                    <!-- India Post Speed -->
                    <tr>
                        <td>4</td>
                        <td>India Post Speed</td>
                        <td>
                            <div class="dropdown action-label india-post-speed-class">
                                @if($data->india_post_speed == 1)
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                @else
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                @endif
                                <div class="dropdown-menu active-inactive-menu-5">
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '5', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '5', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- India Post Business -->
                    <tr>
                        <td>5</td>
                        <td>India Post Business</td>
                        <td>
                            <div class="dropdown action-label india-post-business-class">
                                @if($data->india_post_business == 1)
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                @else
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                @endif
                                <div class="dropdown-menu active-inactive-menu-6">
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '6', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '6', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- India Post Registered -->
                    <tr>
                        <td>6</td>
                        <td>India Post Registered</td>
                        <td>
                            <div class="dropdown action-label india-post-registered-class">
                                @if($data->india_post_registered == 1)
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                @else
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                @endif
                                <div class="dropdown-menu active-inactive-menu-7">
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '7', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '7', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- E2E -->
                    <tr>
                        <td>7</td>
                        <td>E2E</td>
                        <td>
                            <div class="dropdown action-label india-post-registered-class">
                                @if($data->e2e == 1)
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                @else
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                @endif
                                <div class="dropdown-menu active-inactive-menu-8">
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '8', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '8', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- E2E -->
                    <tr>
                        <td>8</td>
                        <td>E2H</td>
                        <td>
                            <div class="dropdown action-label india-post-registered-class">
                                @if($data->e2h == 1)
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
                                @else
                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
                                @endif
                                <div class="dropdown-menu active-inactive-menu-9">
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '9', '1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                    <a class="dropdown-item" onclick="service_status_update('{{$data->id}}', '9', '0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Assigned Services -->
</div>



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

                    @if($data->kyc && $data->kyc->adhar_front_img)
           <img src="{{ asset('tenancy/assets/delboy/' . $data->generated_id . '/' . $data->kyc->adhar_front_img) }}">
@else
    <img src="{{ asset('admin/assets/img/default-adhar.png') }}"> 
@endif


                    </div>

                    <div class="row">

                        

                        @if($data->kyc && $data->kyc->adhar_back_img)
    <img src="{{ asset('tenancy/assets/delboy/' . $data->generated_id . '/' . $data->kyc->adhar_back_img) }}">
@else
    <img src="{{ asset('admin/assets/img/default-adhar.png') }}"> 
@endif


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
                        @if($data->kyc && $data->kyc->pan_img)
    <img src="{{ asset('tenancy/assets/delboy/' . $data->generated_id . '/' . $data->kyc->pan_img) }}">
@else
    <img src="{{ asset('admin/assets/img/default-adhar.png') }}"> 
@endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Pan view Modal -->




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
                    <h3>Delivery Boy Image</h3>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        @if($data->kyc && $data->kyc->photo)
    <img src="{{ asset('tenancy/assets/delboy/' . $data->generated_id . '/' . $data->kyc->photo) }}">
@else
    <img src="{{ asset('admin/assets/img/default-adhar.png') }}"> 
@endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- faranchise view  Modal -->

<!-- faranchise view  Modal -->
<div class="modal custom-modal fade" id="view_dl_img" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-header">
                    <h3>Drivary Lances Image</h3>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">

                        @if($data->kyc && $data->kyc->drivary_lances_img)
    <img src="{{ asset('tenancy/assets/delboy/' . $data->generated_id . '/' . $data->kyc->drivary_lances_img) }}">
@else
    <img src="{{ asset('admin/assets/img/default-adhar.png') }}"> 
@endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- faranchise view  Modal -->








@push('page-javascript')

<script>
    function status_update(id) {

        var update_status = $('#status').find('option:selected').val();

        $.ajax({

            url: "{{route('admin.deliveryBoy.status')}}",

            type: "POST",

            data: {

                "_token": "{{ csrf_token() }}",

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


    function service_status_update(delivery_boy_id, serviceType, status) {
        $.ajax({
            url: "{{ route('admin.deliveryBoy.serviceStatus') }}",
            method: 'POST',
            data: {
                delivery_boy_id: delivery_boy_id,
                service_type: serviceType,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    Swal.fire('Success!', "Status updated", 'success');

                    // Determine the new button based on the status
                    var newElement = $('<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></a>');
                    var iconClass = data.status == '0' ? 'text-danger' : 'text-success';
                    var statusText = data.status == '0' ? 'Inactive' : 'Active';

                    newElement.html('<i class="fa-regular fa-circle-dot ' + iconClass + '"></i> ' + statusText);

                    // Log the new element for debugging
                    console.log($(newElement).get(0));

                    // Determine which element to update based on serviceType
                    switch (data.serviceType) {
                        case '1':
                            // Handle GOTOGO Post Speed specific update
                            $('.active-inactive-menu-1').prev().replaceWith(newElement);
                            break;
                        case '3':
                            // Handle GOTOGO Post SuperFast specific update
                            $('.active-inactive-menu-3').prev().replaceWith(newElement);
                            break;
                        case '4':
                            // Handle GOTOGO Post Business Parcel specific update
                            $('.active-inactive-menu-4').prev().replaceWith(newElement);
                            break;
                        case '5':
                            // Handle India Post Speed specific update
                            $('.active-inactive-menu-5').prev().replaceWith(newElement);
                            break;
                        case '6':
                            // Handle India Post Business specific update
                            $('.active-inactive-menu-6').prev().replaceWith(newElement);
                            break;
                        case '7':
                            // Handle India Post Registered specific update
                            $('.active-inactive-menu-7').prev().replaceWith(newElement);
                            break;
                        case '8':
                            // Handle E2E specific update
                            $('.active-inactive-menu-8').prev().replaceWith(newElement);
                            break;
                        case '9':
                            // Handle E2H specific update
                            $('.active-inactive-menu-9').prev().replaceWith(newElement);
                            break;
                        default:
                            console.error('Unknown service type:', data.serviceType);
                            break;
                    }
                } else {
                    Swal.fire('Error!', "Something went wrong", 'error');
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

            url: "{{url('admin/city-state')}}" + '/' + pincode,

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
    function filterDailyBookingsReportByDate() {
        var date = $('.bookingdate').val();

        // Construct the URL with query parameters
        var url = "{{ route('admin.deliveryBoy.view', ['id' => $data->id]) }}";
        url += "?date=" + encodeURIComponent(date) + "&type=bookings";

        $.ajax({
            url: url,
            type: "GET", // Use GET request
            success: function(response) {
                if (response.status == 200) {
                    var data = response.data;


                    // Clear existing table rows
                    $('#cms_daily_booking_report tbody').empty();

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
                        $('#cms_daily_booking_report tbody').append(row);
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
        var url = "{{ route('admin.deliveryBoy.view', ['id' => $data->id]) }}";
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

@endpush

@endsection