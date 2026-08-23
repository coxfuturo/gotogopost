@extends('deliveryBoy.layouts.master')

@section('title') Dashboard @endsection

@section('content')
<style>
    .booking li {
        padding: 2px;

    }

    .dash-widget .card-body .dash-widget-icon2 {
        background-color: rgba(255, 155, 68, 0.2);
        color: #ff9b44;
        font-size: 30px;
        height: 60px;
        line-height: 60px;
        margin-right: 10px;
        text-align: center;
        width: 100%;
        border-radius: 100%;
        /* width: 20px; */
    }

    .filterInner {
        display: flex;
        justify-content: end;
    }

    .filterOuter {
        position: absolute;
        z-index: 100;
        top: -65px;
        right: 0;
    }

    .form-focus .form-control {
        height: 40px;
        padding: 21px 12px 6px;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
    }

    .dataTables_length {
        text-align: left !important;
    }

    @media screen and (max-width: 900px) {
        .filterOuter {
            position: unset;
        }

        .filter-button {
            margin-bottom: 10px;
        }
    }
</style>
<div>
    <div class="row" style="position: relative">

        <!-- filter -->
        <div class="col-lg-8 filterOuter">
            <form action="{{ route('deliveryBoy.dashboard') }}" method="get">
                @csrf
                <div class="row p-2 filterInner">
                    <div class="col-sm-3">
                        <div class="input-block mb-3 form-focus focused">
                            <div class="cal-icon">
                                <input name="start" id="startDate" class="form-control floating datetimepicker fromDate" type="text"
                                    value="{{ request()->query('start') ? \Carbon\Carbon::parse(request()->query('start'))->format('d-m-Y') : '' }}">
                            </div>
                            <label class="focus-label">From</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-block mb-3 form-focus focused">
                            <div class="cal-icon">
                                <input name="end" id="endDate" class="form-control floating datetimepicker toDate" type="text"
                                    value="{{ request()->query('end') ? \Carbon\Carbon::parse(request()->query('end'))->format('d-m-Y') : '' }}">
                            </div>
                            <label class="focus-label">To</label>
                        </div>
                    </div>
                    <div class="col-sm-3 filter-button">
                        <button type="submit" class="btn btn-success">Filter</button>
                        <a href="{{ route('deliveryBoy.dashboard') }}" class="btn btn-danger">Remove</a>
                    </div>
                </div>
            </form>
        </div>
        <!-- filter -->

        <!-- Delivery Boys -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}"></span>
                        <div class="dash-widget-info">
                            <h3>{{$pickup_count}}</h3>
                            <span>Pickups</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- End Delivery Boys -->

        <!-- Pickup Enquiry -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}"></span>
                        <div class="dash-widget-info">
                            <h3>{{$delivered_parcel_count}}</h3>
                            <span>Delivered</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- End Pickup Enquiry -->

        <!-- mail E2E Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}"></span>
                        <div class="dash-widget-info">
                            <h3>{{$pending_count}}</h3>
                            <span>Pending</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!--End mail E2E Report -->

        <!-- mail E2H Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}"></span>
                        <div class="dash-widget-info">
                            <h3>{{$cancel_count}}</h3>
                            <span>Cancle Parcels</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!--End mail E2E Report -->

        <!-- commission -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    <img src="{{ asset('website/images/logonewtransparent.png') }}" alt="" style="height:65px; width:70px; border-radius: 5px;">
                    <div class="dash-widget-info ">
                        <h3>{{$payment_collected}}</h3>
                        <span>Pamyment Collection Report </span>
                    </div>
                    <div class="text-center" style="width:100%">
                        <h4 class="text-center my-3">Payment Collected</h4>
                        <ul class="booking">
                            <li class="submenu"></li>

                            <li class="">
                                <a href="">Payment Collected</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--End commission -->
        <!-- commission -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    <img src="{{ asset('website/images/logonewtransparent.png') }}" alt="" style="height:65px; width:70px; border-radius: 5px;">
                    <div class="dash-widget-info ">
                        <h3>{{$payment_to_collect}}</h3>
                        <span>Payment Collection Report </span>
                    </div>
                    <div class="text-center" style="width:100%">
                        <h4 class="text-center my-3">Payment To Collect</h4>
                        <ul class="booking">
                            <li class="submenu"></li>

                            <li class="">
                                <a href="">Payment To Collect</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--End commission -->
        <!-- commission -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    <img src="{{ asset('website/images/logonewtransparent.png') }}" alt="" style="height:65px; width:70px; border-radius: 5px;">
                    <div class="dash-widget-info ">
                        <h3>{{$commission_count}}</h3>
                        <span>Commission Report </span>
                    </div>
                    <div class="text-center" style="width:100%">
                        <h4 class="text-center my-3">Commission Report</h4>
                        <ul class="booking">
                            <li class="submenu"></li>

                            <li class="">
                                <a href="">Commission</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--End commission -->


    </div>


    @push('page-javascript')
    <script src="{{asset('admin/assets/plugins/morris/morris.min.js')}}"></script>
    <script src="{{asset('admin/assets/plugins/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/chart.js')}}"></script>

    @endpush
</div>

@endsection