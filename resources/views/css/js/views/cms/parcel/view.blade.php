@extends('cms.layouts.master')

@section('title') Parcel Management @endsection

@section('content')


<div class="row">
    <div class="col-md-6 d-flex">
        <div class="card profile-box flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Pickup Details</h5>
            </div>
            <div class="card-body">
                <ul class="personal-info">
                    <li>
                        <div class="title">Name:</div>
                        <div class="text"><a href="#">{{$data->pickup_name}}</a></div>
                    </li>
                    <li>
                        <div class="title">Phone:</div>
                        <div class="text"><a href="#">{{$data->pickup_mobile}}</a></div>
                    </li>
                    <li>
                        <div class="title">Email:</div>
                        <div class="text"><a href="#">{{$data->pickup_email}}</a></div>
                    </li>

                    <li>
                        <div class="title">City/District:</div>
                        <div class="text">{{$data->pickup_city}}</div>
                    </li>
                    <li>
                        <div class="title">State:</div>
                        <div class="text">{{$data->pickup_state}}</div>
                    </li>
                    <li>
                        <div class="title">Address:</div>
                        <div class="text">{{$data->pickup_address}}</div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 d-flex">
        <div class="card profile-box flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Consignee Details</h5>
            </div>
            <div class="card-body">
                <ul class="personal-info">
                    <li>
                        <div class="title">Name:</div>
                        <div class="text"><a href="#">{{$data->consignee_name}}</a></div>
                    </li>
                    <li>
                        <div class="title">Phone:</div>
                        <div class="text"><a href="#">{{$data->consignee_mobile}}</a></div>
                    </li>
                    <li>
                        <div class="title">Email:</div>
                        <div class="text"><a href="#">{{$data->consignee_email}}</a></div>
                    </li>

                    <li>
                        <div class="title">City/District:</div>
                        <div class="text">{{$data->consignee_city}}</div>
                    </li>
                    <li>
                        <div class="title">State:</div>
                        <div class="text">{{$data->consignee_state}}</div>
                    </li>
                    <li>
                        <div class="title">Address:</div>
                        <div class="text">{{$data->consignee_address}}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>








<div class="mt-2">
    <div class="col-md-6 d-flex">
        <div class="card profile-box flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Parcel Details</h5>
            </div>
            <div class="card-body">
                <ul class="personal-info">
                    <li>
                        <div class="title" style="width:50%">Parcel Weight:</div>
                        <div class="text">{{$data->package_weight}}</div>
                    </li>
                    <li>
                        <div class="title" style="width:50%">Parcel Length:</div>
                        <div class="text">{{$data->package_length}}</div>
                    </li>
                    <li>
                        <div class="title" style="width:50%">Parcel Width:</div>
                        <div class="text">{{$data->package_width}}</div>
                    </li>

                    <li>
                        <div class="title" style="width:50%">Parcel Height:</div>
                        <div class="text">{{$data->package_height}}</div>
                    </li>
                    <li>
                        <div class="title" style="width:50%">Payment Method:</div>
                        <div class="text">{{$data->payment_method}}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
