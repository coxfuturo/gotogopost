@extends('franchise.layouts.master')
@section('title'){{\App\Models\Admin::GOTOGO_POST_BUSINESS}} @endsection
@section('content')
<!-- Row -->
<div class="row">
    <div class="col-sm-12">

        <!-- Custom Boostrap Validation -->
        <div class="card">
            <form action="{{ route('franchise.go-business-parcel.edit', $data->id) }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="card-header">
                    <h5 class="card-title mb-0">Pickup Details</h5>
                </div>
                <div class="card-body">
                    @include('admin.layouts.error')
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="text-dark">Select Pickup details<span class="text-danger">*</span></label>
                                    <select name="pickup-details" id="pickup-details" class="select" data-tags="true" data-placeholder="Select an option">
                                        <option value="">Select an option</option>
                                        @foreach($pickupDetails as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="PickupName" name="PickupName" placeholder="Name" value="{{ old('PickupName', $data ? $data->pickup_name : '') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupMobile">Mobile <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('mobile') is-invalid @enderror" maxlength="10" id="PickupMobile" name="PickupMobile" value="{{ old('PickupMobile', $data ? $data->pickup_mobile : '') }}" placeholder="Mobile." required>
                                        <div class="invalid-feedback">
                                            Please provide mobile number
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupEmail">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="PickupEmail" name="PickupEmail" placeholder="Email ID." value="{{ old('PickupEmail', $data ? $data->pickup_email : '') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide emailId
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupPincode">Pincode <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('pincode') is-invalid @enderror" maxlength="6" id="PickupPincode" name="PickupPincode" value="{{ old('PickupPincode', $data ? $data->pickup_pincode : '') }}" placeholder="Pincode." required>
                                        <div class="invalid-feedback">
                                            Please provide pincode
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupCity">City <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="PickupCity" name="PickupCity" placeholder="City." value="{{ old('PickupCity', $data ? $data->pickup_city : '') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide city
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupState">State <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="text" readonly class="form-control @error('state') is-invalid @enderror" id="PickupState" name="PickupState" value="{{ old('PickupState', $data ? $data->pickup_state : '') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide state
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupAddress">Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="PickupAddress" name="PickupAddress" placeholder="Type address..." required>{{ old('PickupAddress', $data ? $data->pickup_address : '') }}</textarea>
                                        <input type="hidden" name="latitude" value="{{ old('PickupLatitude', $data ? $data->pickup_latitude : '') }}" id="latitude">
                                        <input type="hidden" name="longitude" value="{{ old('PickupLongitude', $data ? $data->pickup_longitude : '') }}" id="longitude">
                                        <div class="invalid-feedback">
                                            Please provide address
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h5 class="card-title mb-0">Consignee Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="ConsigneeName">Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="ConsigneeName" name="ConsigneeName" placeholder="Name" value="{{ old('ConsigneeName', $data ? $data->consignee_name : '') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ConsigneeMobile">Mobile <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('mobile') is-invalid @enderror" maxlength="10" id="ConsigneeMobile" name="ConsigneeMobile" value="{{ old('ConsigneeMobile', $data ? $data->consignee_mobile : '') }}" placeholder="Mobile." required>
                                        <div class="invalid-feedback">
                                            Please provide mobile number
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ConsigneeEmail">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="ConsigneeEmail" name="ConsigneeEmail" placeholder="Email ID." value="{{ old('ConsigneeEmail', $data ? $data->consignee_email : '') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide emailId
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="ConsigneePincode">Pincode <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('pincode') is-invalid @enderror" maxlength="6" id="ConsigneePincode" name="ConsigneePincode" value="{{ old('ConsigneePincode', $data ? $data->consignee_pincode : '') }}" placeholder="Pincode." required>
                                        <div class="invalid-feedback">
                                            Please provide pincode
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ConsigneeCity">City <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="ConsigneeCity" name="ConsigneeCity" placeholder="City." value="{{ old('ConsigneeCity', $data ? $data->consignee_city : '') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide city
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ConsigneeState">State <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="text" readonly class="form-control @error('state') is-invalid @enderror" id="ConsigneeState" name="ConsigneeState" value="{{ old('ConsigneeState', $data ? $data->consignee_state : '') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide state
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="ConsigneeAddress">Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="ConsigneeAddress" name="ConsigneeAddress" placeholder="Type address..." required>{{ old('ConsigneeAddress', $data ? $data->consignee_address : '') }}</textarea>
                                        <input type="hidden" name="latitude" value="{{ old('ConsigneeLatitude', $data ? $data->consignee_latitude : '') }}" id="latitude">
                                        <input type="hidden" name="longitude" value="{{ old('ConsigneeLongitude', $data ? $data->consignee_longitude : '') }}" id="longitude">
                                        <div class="invalid-feedback">
                                            Please provide address
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-dark">Payment Method<span class="text-danger">*</span></label>
                                    <select name="payment_method" id="paymentMethod" class="select" data-tags="true" data-placeholder="Select an option" required>
                                        <option value="">Select an option</option>
                                        <option {{ $data && $data->payment_method == 'Cod' ? 'selected' : '' }} value="cod">Cash on delivery</option>
                                        <option {{ $data && $data->payment_method == 'Prepaid' ? 'selected' : '' }} value="prepaid">Prepaid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h5 class="card-title mb-0">Parcel Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="package_weight">Package Weight</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-box"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('package_weight') is-invalid @enderror" maxlength="12" id="package_weight" name="package_weight" value="{{ old('package_weight', $data ? $data->package_weight : '') }}" placeholder="Package Weight">
                                        @error('package_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="package_length">Package Length</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-box"></i></span>
                                        <input type="text" class="form-control @error('package_length') is-invalid @enderror" id="package_length" name="package_length" value="{{ old('package_length', $data ? $data->package_length : '') }}" placeholder="Package Length">
                                        @error('package_length')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="package_width">Package Width</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-box"></i></span>
                                        <input type="text" class="form-control @error('package_width') is-invalid @enderror" id="package_width" name="package_width" value="{{ old('package_width', $data ? $data->package_width : '') }}" placeholder="Package Width">
                                        @error('package_width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="package_height">Package Height</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-box"></i></span>
                                        <input type="text" class="form-control @error('package_height') is-invalid @enderror" id="package_height" name="package_height" value="{{ old('package_height', $data ? $data->package_height : '') }}" placeholder="Package Height">
                                        @error('package_height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /Custom Boostrap Validation -->

    </div>
</div>
<!-- /Row -->

@push('page-javascript')


<script>
    $('#pincode').on('change', function() {
        var pincode = $('#pincode').val();
        var name = $('#name').val();
        var firstThreeNumbers = pincode.substring(0, 3);
        var generateID = name + firstThreeNumbers + '@post.in';
        $.ajax({
            type: 'GET',
            url: "{{url('admin/city-state')}}" + '/' + pincode,
            success: function(data) {
                if (data.success === true) {
                    $('.customer_error').empty();
                    $('#district').empty().val(data.district);
                    $('#state').empty().val(data.state);
                    $('#generated_id').empty().val(generateID);
                } else {
                    $('.validerror').text('Please enter valid pincode');
                }
            }
        });
    });


    $('#pickup-details').on('change', function(e) {
        var csrfToken = "{{ csrf_token() }}";

        $.ajax({
            type: 'POST',
            url: "{{ route('franchise.pickup-details.details') }}",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                id: e.target.value
            },
            success: function(data) {
                if (data) {
                    $('#PickupName').val(data.name);
                    $('#PickupMobile').val(data.phone);
                    $('#PickupEmail').val(data.email);
                    $('#PickupPincode').val(data.pincode);
                    $('#PickupCity').val(data.city);
                    $('#PickupState').val(data.state);
                    $('#PickupAddress').val(data.address);

                } else {
                    $('.validerror').text('Please enter a valid pincode');
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