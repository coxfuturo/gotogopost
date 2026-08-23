@extends('website.layouts.master')

@section('content')

<style>
    .custom-search-box {
        max-width: 400px; /* Box ka width */
        margin: auto; /* Center align */
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Soft shadow */
        display: flex;
    }
    .custom-search-box input {
        border-radius: 5px;
        padding: 10px;
        font-size: 14px;
    }
    .custom-search-box button {
        margin-left: 25px;
        border-radius: 8px;
        width: 100px;
        padding-top: 6px;
    }
</style>


<!-- Page Header -->
<div class="page-header title-area">
    <div class="header-title">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h1 class="page-title">Track Shipment</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12 site-breadcrumb">
                    <nav class="breadcrumb">
                        <a class="home" href="#"><span>Home</span></a>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                        <span>Track Shipment</span>
                    </nav>
                </div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <form action="{{route('website.trackOrder')}}" class="custom-search-box">
                                <div class="mb-2">
                                    <input type="text" class="form-control" id="trackOrder" name="trackOrder" placeholder="Enter Article No." required>
                                </div>
                                <button type="submit" style="background: #1a2674;" class="btn btn-primary">submit</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page Header end -->

<section class="aboutsec-2 secpaddbig">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-sm-12">
                <div class="abotinforgt">
                    <div class="fh-section-title clearfix text-left version-dark paddbtm30">
                        <h2>Tracking Details</h2>
                    </div>


                    @if(!empty($trackingDetails) && !empty($bookingDetails))
                        <table class="table table-bordered">
                            <tbody>

                                @if(!empty($trackingDetails['mail_code']))
                                <tr>
                                    <th>Mail Code:</th>
                                    <td>{{ $trackingDetails['mail_code'] }}</td>
                                </tr>
                                <tr>
                                    <th>Service Type:</th>
                                    <td>{{$serviceTypeName}}</td>
                                </tr>

                                <tr>
                                    <th>Address:</th>
                                    <td>{{$trackingDetails["destinationFranchise"]['address']}}</td>
                                </tr>

                                @endif
                
                                @if(!empty($trackingDetails->source_franchise_location))
                                <tr>
                                    <th>Packet Booked At:</th>
                                    <td>{{ $trackingDetails->source_franchise_location}}</td>
                                </tr>
                                @endif

                                @if(!empty($trackingDetails->created_at))
                                <tr>
                                    <th>Booked On:</th>
                                    <td>{{ \Carbon\Carbon::parse($trackingDetails->created_at)->format('M d, Y, h:i A') }}</td>
                                </tr>
                                @endif

                                @if(!empty($bookingDetails->consignee_pincode))
                                <tr>
                                    <th>Destination Pincode:</th>
                                    <td>{{ $bookingDetails->consignee_pincode }}</td>
                                </tr>
                                <tr>
                                    <th>Tariff:</th>
                                    <td>{{ $bookingDetails->payment_amount }}</td>
                                </tr>
                                <tr>
                                    <th>Service:</th>
                                    <td>{{$serviceTypeName}}</td>
                                </tr>
                                @endif

                                @if(!empty($trackingDetails->source_cms_location))
                                <tr>
                                    <th>Delivery Location:</th>
                                    <td>{{ $trackingDetails->source_cms_location }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        {{-- Order Dispatched --}}
                        @if(!empty($trackingDetails->order_dispatch_datetime))
                        <h5>Event Details For: <span class="text-primary">{{ $bookingDetails->barcode_no }}</span></h5>
                        <h6>Current Status: <span class="text-success">Order Dispatched</span></h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="table-secondary">
                                    <th>Date Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($trackingDetails->order_dispatch_datetime)->format('M d, Y, h:i A') }}</td>
                                </tr>
                                @if(!empty($trackingDetails->source_cms_location))
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $trackingDetails->source_cms_location }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Event:</th>
                                    <td>Order Dispatched</td>
                                </tr>
                            </tbody>
                        </table>
                        @endif

                        {{-- CPH Receiving --}}
                        @if(!empty($trackingDetails->source_cms_receiving_datetime))
                        <h6>Current Status: <span class="text-success">CPH Receiving</span></h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="table-secondary">
                                    <th>Date Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($trackingDetails->source_cms_receiving_datetime)->format('M d, Y, h:i A') }}</td>
                                </tr>
                                @if(!empty($trackingDetails->source_cms_location))
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $trackingDetails->source_cms_location }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Event:</th>
                                    <td>CPH Receiving</td>
                                </tr>
                            </tbody>
                        </table>  
                        @endif

                        {{-- CPH Dispatched (Destination) --}}
                        @if(!empty($trackingDetails->destination_cms_dispatch_datetime))
                        <h6>Current Status: <span class="text-success">CPH Dispatched</span></h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="table-secondary">
                                    <th>Date Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($trackingDetails->destination_cms_dispatch_datetime)->format('M d, Y, h:i A') }}</td>
                                </tr>
                                @if(!empty($trackingDetails->destination_cms_location))
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $trackingDetails->destination_cms_location }}</td>
                                </tr>
                                @endif  
                                <tr>
                                    <th>Event:</th>
                                    <td>CPH Dispatched</td>
                                </tr>
                            </tbody>
                        </table>  
                        @endif

                        {{-- PPH Receiving --}}
                        @if(!empty($trackingDetails->pph_receiving_datetime))
                        <h6>Current Status: <span class="text-success">PPH Receiving</span></h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="table-secondary">
                                    <th>Date Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($trackingDetails->pph_receiving_datetime)->format('M d, Y, h:i A') }}</td>
                                </tr>
                                @if(!empty($trackingDetails->pph_location))
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $trackingDetails->pph_location }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Event:</th>
                                    <td>PPH Receiving</td>
                                </tr>
                            </tbody>
                        </table>  
                        @endif

                        {{-- PPH Dispatched --}}
                        @if(!empty($trackingDetails->pph_dispatch_datetime))
                        <h6>Current Status: <span class="text-success">PPH Dispatched</span></h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="table-secondary">
                                    <th>Date Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($trackingDetails->pph_dispatch_datetime)->format('M d, Y, h:i A') }}</td>
                                </tr>
                                @if(!empty($trackingDetails->pph_location))
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $trackingDetails->pph_location }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Event:</th>
                                    <td>PPH Dispatched</td>
                                </tr>
                            </tbody>
                        </table>  
                        @endif

                        {{-- CPH Dispatched (Source) --}}
                        @if(!empty($trackingDetails->source_cms_dispatch_datetime))
                        <h6>Current Status: <span class="text-success">CPH Dispatched</span></h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="table-secondary">
                                    <th>Date Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($trackingDetails->source_cms_dispatch_datetime)->format('M d, Y, h:i A') }}</td>
                                </tr>
                                @if(!empty($trackingDetails->source_cms_location))
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $trackingDetails->source_cms_location }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Event:</th>
                                    <td>CPH Dispatched</td>
                                </tr>
                            </tbody>
                        </table>  
                        @endif

                        {{-- Franchise Receiving --}}
                        @if(!empty($trackingDetails->destination_franchise_receiving_datetime))
                        <h6>Current Status: <span class="text-success">Franchise Receiving</span></h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="table-secondary">
                                    <th>Date Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($trackingDetails->destination_franchise_receiving_datetime)->format('M d, Y, h:i A') }}</td>
                                </tr>
                                @if(!empty($trackingDetails->destination_franchise_location))
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $trackingDetails->destination_franchise_location }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Event:</th>
                                    <td>Franchise Receiving</td>
                                </tr>
                            </tbody>
                        </table>  
                        @endif

                        {{-- Assigned To Delivery Boy --}}
                        @if(!empty($trackingDetails->delivery_boy_assigned_datetime))
                        <h6>Current Status: <span class="text-success">Assigned To Delivery Boy</span></h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="table-secondary">
                                    <th>Date Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($trackingDetails->delivery_boy_assigned_datetime)->format('M d, Y, h:i A') }}</td>
                                </tr>
                                @if(!empty($trackingDetails->destination_franchise_location))
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $trackingDetails->destination_franchise_location }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Event:</th>
                                    <td>Assigned To Delivery Boy</td>
                                </tr>
                            </tbody>
                        </table>  
                        @endif

                        {{-- Delivered --}}
                        @if(!empty($trackingDetails->delivery_datetime))
                        <h6>Current Status: <span class="text-success">Delivered</span></h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="table-secondary">
                                    <th>Date Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($trackingDetails->delivery_datetime)->format('M d, Y, h:i A') }}</td>
                                </tr>
                                @if(!empty($trackingDetails->delivery_location))
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $trackingDetails->delivery_location }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Event:</th>
                                    <td>Delivered</td>
                                </tr>
                            </tbody>
                        </table>  
                        @endif
                    @else
                        <p class="text-danger">No tracking details found for the given barcode.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>



@endsection