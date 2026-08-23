@extends('admin.layouts.master')



@section('title')Parcel Management @endsection



@section('content')

<style>
    .personal-info li .text {
        color: #b31616;
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

                                <img src="data:image/png;base64,{{  $data->barcode_image_src }}" alt="Barcode" style="height:50px;" />

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

@endpush

@endsection