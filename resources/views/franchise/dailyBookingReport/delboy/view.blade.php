@extends('franchise.layouts.master')



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

                            <a href="#">
                                <img src="{{ isset($data->kyc->photo) ? asset('tenancy/assets/delboy/'.$data->generated_id.'/'.$data->kyc->photo) : asset('admin/assets/img/profiles/avatar-02.jpg') }}" alt="Profile Image">
                            </a>
                            

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

                    {{-- <div class="pro-edit"><a data-bs-target="#profile_info" data-bs-toggle="modal" class="edit-icon" href="#"><i class="fa-solid fa-pencil"></i></a></div> --}}

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
                <li class="nav-item"><a href="#cms_daily_booking_report" data-bs-toggle="tab" class="nav-link">Daily Delivered Report</a></li>
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

                                <div class="text">{{$data->kyc->bank_name}}</div>

                            </li>

                            <li>

                                <div class="title">Bank account No.</div>

                                <div class="text">{{$data->kyc->account_number}}</div>

                            </li>

                            <li>

                                <div class="title">IFSC Code</div>

                                <div class="text">{{$data->kyc->ifsc_code}}</div>

                            </li>

                            <li>

                                <div class="title">PAN No</div>

                                <div class="text">{{$data->kyc->pan_card}}</div>

                            </li>

                            <li>

                                <div class="title">Adhar No</div>

                                <div class="text">{{$data->kyc->adhar_card}}</div>

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
                                <a class="delete-table me-2" href="{{ route('franchise.delboy.view.detail', ['id' => $item->barcode_no, 'service_type' => $item->service_number]) }}">
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

                        <img src="{{asset('tenancy/assets/delboy/'.$data->generated_id.'/'.$data->kyc->adhar_front_img)}}">

                    </div>

                    <div class="row">

                        <img src="{{asset('tenancy/assets/delboy/'.$data->generated_id.'/'.$data->kyc->adhar_back_img)}}">

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
                        <img src="{{asset('tenancy/assets/delboy/'.$data->generated_id.'/'.$data->kyc->pan_img)}}">
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
                    <h3>Business Associate Image</h3>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <img src="{{asset('tenancy/assets/delboy/'.$data->generated_id.'/'.$data->kyc->photo)}}">
                       
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

            url: "{{route('admin.franchise.status')}}",

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

</script>

<script>
    function filterDailyBookingsReportByDate() {
        var date = $('.bookingdate').val();

        // Construct the URL with query parameters
        var url = "{{ route('franchise.delboy.view', ['id' => $data->id]) }}";
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
    function filterParcelsByDate() {
        var date = $('.date').val();

        // Construct the URL with query parameters
        var url = "{{ route('franchise.delboy.view', ['id' => $data->id]) }}";
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

