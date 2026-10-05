@extends('admin.layouts.master')
@section('title')Parcel Management @endsection
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
<div id="profile_info" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('franchise.delboy.edit',$data->id)}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-img-wrap edit-img">
                                <div id="fileContainer1">
                                    <img class="inline-block" src="{{isset($data->kyc->photo) ? asset('admin/franchise/'.$data->generated_id.'/'.$data->kyc->photo) : asset('admin/assets/img/profiles/avatar-02.jpg')}}">
                                </div>
                                <div class="fileupload btn">
                                    <span class="btn-text">Edit</span>
                                    <input class="upload" id="fileInput1" onchange="displayFile(1)" type="file">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{$data->name}}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="mobile" value="{{$data->mobile}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Email</label>
                                        <input type="text" class="form-control" name="email" value="{{$data->email}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Address</label>
                                        <textarea name="address" class="form-control" id="address" required>{{old('address',$data->address)}}</textarea>
                                        <input type="hidden" name="latitude" value="{{old('latitude',$data->latitude)}}" id="latitude">
                                        <input type="hidden" name="longitude" value="{{old('longitude',$data->longitude)}}" id="longitude">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">         
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Pin Code</label>
                                <input type="text" class="form-control NumberValidate" id="pincode" maxlength="6" name="pincode" value="{{$data->pincode}}" required>
                                <div class="invalid-feedback validerror">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">State</label>
                                <input type="text" class="form-control" id="state" readonly name="state" value="{{$data->state}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">District</label>
                                <input type="text" class="form-control" id="district" readonly name="district" value="{{$data->district}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{$data->city}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">         
                        <div class="col-md-6">
                            <label for="wallet_amount">Wallet Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-inr"></i></span>
                                <input type="text" class="form-control NumberValidate @error('wallet_amount') is-invalid @enderror" id="wallet_amount" name="wallet_amount" value="{{old('name',$data->wallet_balance)}}" placeholder="Wallet Amount" required>
                                <div class="invalid-feedback">
                                    Please provide wallet amount
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="bank_info" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bank Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Pan Card No.</label>
                                        {{-- <input type="text" class="form-control" name="pan_card" value="{{$data->kyc->pan_card}}" required> --}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Adhar Card No.</label>
                                        {{-- <input type="text" class="form-control" name="adhar_card" value="{{$data->kyc->adhar_card}}" required> --}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Bank Account No.</label>
                                        {{-- <input type="text" class="form-control" name="account_number" value="{{$data->kyc->account_number}}" required> --}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">IFSC Code</label>
                                        {{-- <input type="text" class="form-control" name="ifsc_code" maxlength="11" onkeyup="return forceUpper(this);" value="{{$data->kyc->ifsc_code}}" required> --}}
                                        <div class="text-danger" id="bank_ifsc_error"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Bank Name</label>
                                        {{-- <input type="text" class="form-control" name="bank_name" value="{{$data->kyc->bank_name}}" required> --}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Branch Name</label>
                                        {{-- <input type="text" class="form-control" name="branch_name" value="{{$data->kyc->branch_name}}" required> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
        fileContainer.innerHTML = "";
        if (fileInput.files.length === 0) {
            fileContainer.innerHTML = "<p>No file selected.</p>";
            return;
        }
        const file = fileInput.files[0];
        const fileType = file.type;
        if (fileType === "application/pdf") {
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
