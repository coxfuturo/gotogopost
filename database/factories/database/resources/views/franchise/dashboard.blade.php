@extends('franchise.layouts.master') 
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

.filterInner{
    display: flex;
    justify-content: end;
}

.filterOuter{
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

    .filter-button{
        margin-bottom: 10px;
    }
}


.booking li {
    margin-top: 5px !important;
    background: #fc6075 !important;
    /* padding: 0px; */
}
.booking li a{
    color: #fff !important;
}

.dash-widget .card-body .dash-widget-info h3 {
    font-size: 24px !important;
    font-weight: 600 !important;
    margin-bottom: 5px !important;
}
.dash-widget .card-body .dash-widget-info {
    text-align: right !important;
    width: calc(100% - 70px);
    width: fit-content !important;
    margin-left: 20px !important;
}

.card {
    border: 1px solid #ededed;
    margin-bottom: 20px !important;
}

.dash-widget .card-body .dash-widget-icon {
    background-color: rgba(255, 155, 68, 0.2);
    color: #ff9b44 !important;
    font-size: 30px !important;
    height: 15px !important;
    /* line-height: 60px; */
    margin-right: 10px !important;
    text-align: center !important;
    width: 80px !important;
    border-radius: 100%;
}

</style>
<div>
    <div class="row" style="position: relative">

        <!-- filter -->
        <div class="col-lg-8 filterOuter">
            <form action="{{ route('franchise.dashboard') }}" method="get">
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
                        <a href="{{ route('franchise.dashboard') }}" class="btn btn-danger">Remove</a>
                    </div>
                </div>
            </form>
        </div>
         <!-- filter -->
       
        <!-- Delivery Boys -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('franchise.delboy.index')}}">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}"></span>
                        <div class="dash-widget-info">
                            <h3>{{$deliveryboy}}</h3>
                            <span>Delivery Boys</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- End Delivery Boys -->

        <!-- Pickup Enquiry -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('franchise.pickup-details.index')}}">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}"></span>
                        <div class="dash-widget-info">
                            <h3>{{$pickuplist}}</h3>
                            <span>Pickup Enquiry</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- End Pickup Enquiry -->

        <!-- mail E2E Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('franchise.mailToMail.receivedMails')}}">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}"></span>
                        <div class="dash-widget-info">
                            <h3>{{$MailE2e}}</h3>
                            <span>Mail E2E Report</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!--End mail E2E Report -->

        <!-- mail E2H Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('franchise.mailTofranhchise.receivedMails')}}">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important;" class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}"></span>
                        <div class="dash-widget-info">
                            <h3>{{$MailE2h}}</h3>
                            <span>Mail E2H Report</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!--End mail E2E Report -->
        <!-- booking -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget" style="height:88%">
                <div class="card-body">
                   
                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-4">
                            <h3>{{$gotogolistList}}</h3>
                            <span>Bookings</span>
                        </div>
                        
                        <div class="col-4" style="text-align: right;"> 
                            <h3 style="display: block; white-space: nowrap;">{{$gotogolistAmount}}</h3>
                            <span style="display: block; white-space: nowrap;">Amount</span>
                        </div>
                        
                    </div>
                    <div class="text-center" style="width:100%">
                        <h4 class="text-center my-2">Gotogo Post Booking Services</h4>
                        <ul class="booking">
                            <li class="">
                                <a href="{{ route('franchise.go-speed-post-parcel.create') }}">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</a>
                            </li>

                            <li class="">
                                <a href="{{ route('franchise.go-business-parcel.create') }}">{{\App\Models\Admin::GOTOGO_POST_BUSINESS}}</a>
                            </li>

                            <li class="">
                                <a href="{{ route('franchise.go-registered.create') }}">{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    
                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-4">
                            <h3>{{$indiapostlistList}}</h3>
                            <span>Bookings</span>
                        </div>
                        
                        <div class="col-4" style="text-align: right;"> 
                            <h3 style="display: block; white-space: nowrap;">{{$gotogolistIndiaAmount}}</h3>
                            <span style="display: block; white-space: nowrap;">Amount</span>
                        </div>
                    </div>
                    <div class="text-center" style="width:100%">
                        <h4 class="text-center my-2">All India Post Booking Services</h4>
                        <ul class="booking">
                            <!-- <li class="submenu"></li> -->
                            <li class="">
                                <a href="{{ route('franchise.india-post-speed-post.create') }}">{{\App\Models\Admin::INDIA_POST_SPEED}}</a>
                            </li>
                            <li class="">
                                <a href="{{ route('franchise.india-post-business.create') }}">{{\App\Models\Admin::INDIA_POST_BUSINESS}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--End booking -->
        <!-- commission -->

        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    
                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-4">
                           <img src="{{ asset('website/images/logonewtransparent.png') }}" alt="" style="height:65px; width:70px; border-radius: 5px;"> 
                        </div>
                        
                        <div class="col-4" style="text-align: right;"> 
                            <h3 style="display: block; white-space: nowrap;">₹{{$commissionList}}.00</h3>
                            <span style="display: block; white-space: nowrap;">Commission<br> Report</span>
                        </div>
                    </div>
                    <div class="text-center" style="width:100%">
                        <h4 class="text-center my-2">Commission Report</h4>
                        <ul class="booking">
                            <!-- <li class="submenu"></li> -->
                            <li class="">
                                <a href="{{ route('franchise.commission.index') }}">Commission</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!--End commission -->

        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <a href="{{ route('franchise.mailToMail.create')}}"><div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-12">
                          <h3 style="color:blue"><span style="font-size: 38px;color:#fc6075;">e</span>2E</h3>
                        </div>
                        <div class="col-4">
                            <h3 style="font-size: 22px;">{{$MailE2e}}</h3>
                            <span>Bookings</span>
                        </div>

                        <div class="col-4">
                            <h3 style="font-size: 22px;">{{$totalEmailAmount}}</h3>
                            <span>Amount</span>
                        </div>
                  
                        <div class="col-4" style="text-align: right;"> 
                            <h3 style="font-size: 22px;">{{$totalBalance}}</h3>
                            <span>Balance</span>
                        </div>
                    </div>

                </div>
            </div></a>
        </div>
        <!-- End e2h -->

         <!-- e2h -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <a href="{{ route('franchise.mailTofranhchise.create')}}"><div class="card dash-widget" style="height:88%">
                <div class="card-body">
                    <div class="row" style="justify-content: space-between;width:100%; margin-left: 0px;">
                        <div class="col-12">
                          <h3 style="color:blue"><span style="font-size: 38px;color:#fc6075;">e</span>2H</h3>
                        </div>
                        <div class="col-4">
                            <h3 style="font-size: 22px;">{{$MailE2h}}</h3>
                            <span>Bookings</span>
                        </div>

                        <div class="col-4">
                            <h3 style="font-size: 22px;">{{$totalEmailAmounte2h}}</h3>
                            <span>Amount</span>
                        </div>
                  
                        <div class="col-4" style="text-align: right;"> 
                            <h3 style="font-size: 22px;">{{$totalBalance}}</h3>
                            <span>Balance</span>
                        </div>
                    </div>
                    
                </div>
            </div></a>
        </div>
        <!-- End e2h -->

        <!--end Report -->

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                
                <div class="col-md-12 text-center">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Gotogo Post Daily Booking Service</h3>
                            
                            <div class="table-responsive">
                                <table class="table table-striped custom-table datatable">
                                    <thead>
                                        <tr>
                                            <th>SN#</th>
                                            <th class="text-center">Barcode</th>
                                            <th class="text-center"><span>From Addresss</span></th>
                                            <th class="text-center">State</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Pincode</th>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">To Addresss</th>
                                            <th class="text-center">State</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Pincode</th>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Weight</th>
                                            <th class="text-center">amount (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- {{dd($gotogolist)}} --}} @foreach($gotogolist as $i => $data)
                                        <tr>
                                            <img style="display: none;" src="data:image/png;base64,{{  $data->barcode_image_src }}" alt="Barcode" style="height: 50px;" />
                                            <td>{{$i+1}}</td>
                                            <td>{{$data->barcode_no}}</td>
                                            <td>{{$data->pickup_address}}</td>
                                            <td>{{$data->pickup_state}}</td>
                                            <td>{{$data->pickup_city}}</td>
                                            <td>{{$data->pickup_pincode}}</td>
                                            <td>{{$data->pickup_mobile}}</td>
                                            <td>{{$data->pickup_email}}</td>
                                            <td>{{$data->consignee_address}}</td>
                                            <td>{{$data->consignee_state}}</td>
                                            <td>{{$data->consignee_city}}</td>
                                            <td>{{$data->consignee_pincode}}</td>
                                            <td>{{$data->consignee_mobile}}</td>
                                            <td>{{$data->consignee_email}}</td>
                                            <td>{{$data->package_weight}}</td>
                                            <td>{{$data->payment_amount}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 text-center">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">All India Post Daily Booking Service</h3>

                            <div class="table-responsive">
                                <table class="table table-striped custom-table datatable">
                                    <thead>
                                        <tr>
                                            <th>SN#</th>
                                            <th class="text-center">Barcode</th>
                                            <th class="text-center"><span>From Addresss</span></th>
                                            <th class="text-center">State</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Pincode</th>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">To Addresss</th>
                                            <th class="text-center">State</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Pincode</th>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Weight</th>
                                            <th class="text-center">amount (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- {{dd($indiapostlist)}} --}} @foreach($indiapostlist as $i => $data)
                                        <tr>
                                            <img style="display: none;" src="data:image/png;base64,{{  $data->barcode_image_src }}" alt="Barcode" style="height: 50px;" />
                                            <td>{{$i+1}}</td>
                                            <td>{{$data->barcode_no}}</td>
                                            <td>{{$data->pickup_address}}</td>
                                            <td>{{$data->pickup_state}}</td>
                                            <td>{{$data->pickup_city}}</td>
                                            <td>{{$data->pickup_pincode}}</td>
                                            <td>{{$data->pickup_mobile}}</td>
                                            <td>{{$data->pickup_email}}</td>
                                            <td>{{$data->consignee_address}}</td>
                                            <td>{{$data->consignee_state}}</td>
                                            <td>{{$data->consignee_city}}</td>
                                            <td>{{$data->consignee_pincode}}</td>
                                            <td>{{$data->consignee_mobile}}</td>
                                            <td>{{$data->consignee_email}}</td>
                                            <td>{{$data->package_weight}}</td>
                                            <td>{{$data->payment_amount}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- <div id="line-charts"></div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('page-javascript')
    <script src="{{asset('admin/assets/plugins/morris/morris.min.js')}}"></script>
    <script src="{{asset('admin/assets/plugins/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/chart.js')}}"></script>

    @endpush
</div>

@endsection