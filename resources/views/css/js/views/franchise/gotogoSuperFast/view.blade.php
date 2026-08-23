@extends('franchise.layouts.master')



@section('title') GOTOGO Super Fast @endsection



@push('add-modal-code')

<div class="col-auto float-end ms-auto">  

    <button id="fullPrint" style="margin-right:10px;" class="btn add-btn">Print</button>

</div>

@endpush



@section('content')

<style>
    .personal-info li .text {
        color: #aa1111 !important;
        display: block;
        overflow: hidden;
        width: 70%;
        float: left;
    }
</style>

<div class="tab-content">

    <div id="emp_profile" class="pro-overview tab-pane fade show active">

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

                                <div class="title">Pincode:</div>

                                <div class="text">{{$data->pickup_pincode}}</div>

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

                                <div class="title">Pincode:</div>

                                <div class="text">{{$data->consignee_pincode}}</div>

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

        <div class="row">

            <div class="col-md-6 d-flex">

                <div class="card profile-box flex-fill">

                    <div class="card-header">

                        <h5 class="card-title mb-0">Parcel Details</h5>

                    </div>

                    <div class="card-body row">

                        <div class="col-md-8 mb-3">

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
    
                            </ul>
                        </div>

                        <div class="col-md-4 mb-3">

                            <label for="PickupAddress">Barcode <span class="text-danger">*</span></label>

                            <div class="input-group">

                                <img  src="data:image/png;base64,{{  $data->barcode_image_src }}" alt="Barcode" style="height:50px;"/>

                                <p class="mt-2 text-center w-100">{{$data->barcode_no}}</p>

                            </div>
                        </div>

                    </div>

                </div>

            </div>

            

            <div class="col-md-6 d-flex">

                <div class="card profile-box flex-fill">

                    <div class="card-header">

                        <h5 class="card-title mb-0">Payment Details</h5>

                    </div>

                    <div class="card-body row">

                        <div class="col-md-6 mb-3">

                            <ul class="personal-info">

                                <li>
                                    <div class="title" style="width:100%">Payment Method:</div>
    
                                    <div class="text" style="width:50%">{{$data->payment_method}}</div>
                                </li>

                                @if($rateDetails['fuel_charge']>0)
                                    <li>
                                        <div class="title" style="width:100%">Fuel Charge:</div>
        
                                        <div class="text" style="width:50%">₹ {{$rateDetails['fuel_charge']}}</div>
                                    </li>
                                @endif

                                @if($rateDetails['other_service_charge']>0)
                            <li>
                                <div class="title" style="width:100%">Other Service Charge:</div>

                                <div class="text" style="width:50%">₹ {{$rateDetails['other_service_charge']}}</div>
                            </li>
                            @endif

                                
                                
                            </ul>
                        </div>

                        <div class="col-md-6 mb-3">

                            <ul class="personal-info">

                            @if($rateDetails['amount']>0)
                                <li>
                                    <div class="title" style="width:100%">Amount:</div>
    
                                    <div class="text" style="width:50%">₹ {{$rateDetails['amount']}}</div>
                                </li>
                            @endif

                            @if($rateDetails['pickup_charge']>0)
                            <li>
                                <div class="title" style="width:100%">Pickup Charge:</div>

                                <div class="text" style="width:50%">₹ {{$rateDetails['pickup_charge']}}</div>
                            </li>
                            @endif

                            
      
                            <li>
                                <div class="title" style="width:100%">GST:</div>
                                <div class="text" style="width:50%">₹ {{$rateDetails['gst']}}</div>
                            </li>

                                    <li>
                                        <div class="title" style="width:100%">Total Payment Amount:</div>
                                        <div class="text" style="width:50%">₹ {{$rateDetails['total_payment_amount']}}</div>
                                    </li>

    
                            </ul>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- /Profile Info Tab -->
</div>


@push('page-javascript')

    

<script>



    $(document).ready(function() {

        $('#fullPrint').on('click', function() {

            $.ajax({

                url: "{{ route('franchise.go-super-fast-parcel.full-print', ['id' => $data->id]) }}", 

                method: 'GET',

                success: function(response) {

                    var iframe = document.createElement('iframe');

                        iframe.style.position = 'absolute';

                        iframe.style.width = '0';

                        iframe.style.height = '0';

                        iframe.style.border = 'none';

                        document.body.appendChild(iframe);



                        iframe.contentDocument.open();

                        iframe.contentDocument.write(response.otherPageContent);

                        iframe.contentDocument.close();



                        iframe.contentWindow.focus();

                        iframe.contentWindow.print();



                        document.body.removeChild(iframe);

                }

            });

        });

    });

</script>

@endpush



@endsection

