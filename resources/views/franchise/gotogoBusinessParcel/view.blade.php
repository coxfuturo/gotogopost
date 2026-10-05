@extends('franchise.layouts.master')



@section('title') {{\App\Models\Admin::GOTOGO_POST_BUSINESS}} @endsection



@push('add-modal-code')

<div class="col-auto float-end ms-auto">
    <button style="margin-right:10px;" class="btn add-btn" id="trackOrder" data-bs-toggle="modal" data-bs-target="#track_order">Track Order</button>
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
    .personal-info li {
     margin-bottom: 5px;    
    
    }

    .tab-content {
        padding-top: 0;
    }
    .parcel-details{
        margin-top: -15px;
    }

</style>

<div class="tab-content">

    <div id="emp_profile" class="pro-overview tab-pane fade show active">

        <div class="row">

            <div class="col-md-6 d-flex">

                <div class="card profile-box flex-fill">

                 <div class="row card-header">
                    <div class="col-sm-4">
                       <h5 class="card-title mb-0">Pickup Details</h5>
                    </div>
                    <div class="col-sm-8">
                       <h5 class="card-title mb-0">Business Associate GST: {{ $data->franchise->gst_number }}</h5>
                    </div>
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
                            <li>

                                <div class="title">GST No:</div>

                                <div class="text">{{$data->consignee_gst_number}}</div>

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
                            <!-- <li>

                                <div class="title">GST No:</div>

                                <div class="text">{{$data->consignee_gst_number}}</div>

                            </li> -->

                        </ul>

                    </div>

                </div>

            </div>

        </div>

        <div class="row parcel-details">

            <div class="col-md-6 d-flex">

                <div class="card profile-box flex-fill">

                    <div class="card-header">

                        <h5 class="card-title mb-0">Parcel Details</h5>

                    </div>

                    <div class="card-body row">

                        <div class="col-md-8">

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

                        <div class="col-md-4">

                            <label for="PickupAddress">Barcode <span class="text-danger">*</span></label>

                            <div class="input-group">

                                <img src="data:image/png;base64,{{  $data->barcode_image_src }}" alt="Barcode" style="height:50px;" />

                                <p class="mt-2 text-center w-100">{{$data->barcode_no}}</p>

                            </div>
                        </div>
                      
                        @if($data->signature)
                            <li class="d-flex justify-content-between px-3">
                                <div class="document-title">Signature</div>
                                <div data-bs-toggle="modal" data-bs-target="#adhar_view">
                                    <button class="btn btn-sm btn-dark">View</button>
                                </div>
                            </li>
                        @endif

                    </div>

                </div>

            </div>



            <div class="col-md-6 d-flex">

                <div class="card profile-box flex-fill">

                    <div class="card-header">

                        <h5 class="card-title mb-0">Payment Details</h5>

                    </div>

                    <div class="card-body row">

                        <div class="col-md-6">

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

<!-- Track Order Modal -->

<div id="track_order" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Tracking Order Details</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div>
                <div class="tracking-container">
                    <ul class="tracking-steps row">

                    </ul>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Track Order Modal -->
 <!-- signature view  Modal -->
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
                    <h3>Signature</h3>
                </div>
                <div class="modal-btn delete-action">
                <div class="row" style="display: flex; justify-content: center; transform: rotate(90deg); transform-origin: center;">
                        @if (!empty($data->signature))
                            <div class="col-md-4">
                                <img src="{{ asset('tenancy/assets/signature/' . $data->signature) }}" alt="signature" class="img-thumbnail">
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /signature view Modal -->



@push('page-javascript')



<script>
    $(document).ready(function() {
        $("#fullPrint").on("click", function() {
            console.log("reached here");

            $.ajax({
                url: "{{ route('franchise.go-business-parcel.full-print', ['id' => $data->id]) }}",

                method: "GET",

                success: function(response) {
                    var iframe = document.createElement("iframe");

                    iframe.style.position = "absolute";

                    iframe.style.width = "0";

                    iframe.style.height = "0";

                    iframe.style.border = "none";

                    document.body.appendChild(iframe);

                    iframe.contentDocument.open();

                    iframe.contentDocument.write(response.otherPageContent);

                    iframe.contentDocument.close();

                    iframe.contentWindow.focus();

                    iframe.contentWindow.print();

                    document.body.removeChild(iframe);
                },
            });
        });

        $("#trackOrder").on("click", function() {
            console.log("Reached here");

            $.ajax({
                url: "{{ route('franchise.go-business-parcel.trackOrder', ['id' => $data->id]) }}",
                method: "GET",
                success: function(response) {
                    console.log(response);

                    const tracking = response.trackingDetails;

                    // Clear existing data in the modal
                    $(".tracking-steps").empty();

                    const steps = [];

                    // Source Franchise (Order Placed)
                    if (tracking.order_placed_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">&#10003;</div>
                                    <div class="step-details">
                                        <p class="step-title">Order Placed</p>
                                        <p class="step-date">${formatDate(tracking.order_placed_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.source_franchise_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Source Franchise (Order Dispatched)
                    if (tracking.order_dispatch_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">&#128640;</div>
                                    <div class="step-details">
                                        <p class="step-title">Order Dispatched</p>
                                        <p class="step-date">${formatDate(tracking.order_dispatch_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.source_franchise_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Source CMS
                    if (tracking.source_cms_receiving_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">🏢</div>
                                    <div class="step-details">
                                        <p class="step-title">CPH Receiving</p>
                                        <p class="step-date">${formatDate(tracking.source_cms_receiving_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.source_cms_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Source CMS
                    if (tracking.source_cms_dispatch_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">🏢</div>
                                    <div class="step-details">
                                        <p class="step-title">CPH Dispatched</p>
                                        <p class="step-date">${formatDate(tracking.source_cms_dispatch_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.source_cms_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // PPH
                    if (tracking.pph_receiving_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">🏢</div>
                                    <div class="step-details">
                                        <p class="step-title">PPH Receiving</p>
                                        <p class="step-date">${formatDate(tracking.pph_receiving_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.pph_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // PPH
                    if (tracking.pph_dispatch_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">🏢</div>
                                    <div class="step-details">
                                        <p class="step-title">PPH Dispatched</p>
                                        <p class="step-date">${formatDate(tracking.pph_dispatch_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.pph_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Destination CMS
                    if (tracking.destination_cms_receiving_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">🏢</div>
                                    <div class="step-details">
                                        <p class="step-title">CPH Receiving</p>
                                        <p class="step-date">${formatDate(tracking.destination_cms_receiving_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.destination_cms_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Destination CMS
                    if (tracking.destination_cms_dispatch_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">🏢</div>
                                    <div class="step-details">
                                        <p class="step-title">CPH Dispatched</p>
                                        <p class="step-date">${formatDate(tracking.destination_cms_dispatch_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.destination_cms_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Destination Franchise
                    if (tracking.destination_franchise_receiving_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">🏢</div>
                                    <div class="step-details">
                                        <p class="step-title">Franchise Receiving</p>
                                        <p class="step-date">${formatDate(tracking.destination_franchise_receiving_datetime
                                        )}</p>
                                        <p class="step-location">Location: ${tracking.destination_franchise_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Destination Franchise
                    if (tracking.delivery_boy_assigned_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">&#128640;</div>
                                    <div class="step-details">
                                        <p class="step-title">Assigned To Delivery Boy</p>
                                        <p class="step-date">${formatDate(tracking.delivery_boy_assigned_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.destination_franchise_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Delivery
                    if (tracking.delivery_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">🏠</div>
                                    <div class="step-details">
                                        <p class="step-title">Delivered</p>
                                        <p class="step-date">${formatDate(tracking.delivery_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.delivery_location || "-"}</p>
                                    </div>
                                </li>
                            `);
                    } else {
                        steps.push(`
                                <li class="tracking-item col-md-6">
                                    <div class="step-icon">&#9989;</div>
                                    <div class="step-details">
                                        <p class="step-title">Delivered</p>
                                        <p class="step-date">Pending</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Append all steps to the modal
                    $(".tracking-steps").html(steps.join(""));
                },
                error: function(error) {
                    console.error("Failed to fetch tracking details:", error);
                },
            });
        });

        // Helper function to format date
        function formatDate(dateString) {
            const options = {
                year: "numeric",
                month: "short",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit"
            };
            return new Date(dateString).toLocaleDateString("en-US", options);
        }
    });
</script>


@endpush




@endsection