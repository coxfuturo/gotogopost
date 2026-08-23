@extends('admin.layouts.master')

@section('title') Update Franchise @endsection

@section('content')
<!-- Row -->
<div class="row">
    <div class="col-sm-12">

        <!-- Custom Boostrap Validation -->
        <div class="card">
            <form action="{{route('admin.franchise.edit',$data->id)}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Details</h5>
                </div>
                <div class="card-body">
                    @include('admin.layouts.error')
                    <div class="row">
                        <div class="col-sm">

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{old('name',$data->name)}}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="father_name">Father/Husband Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{old('father_name',$data->father_name)}}" placeholder="Father/Husband Name" required>
                                        <div class="invalid-feedback">
                                            Please provide father/husband name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile">Mobile <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('mobile') is-invalid @enderror" maxlength="10" id="mobile" name="mobile" value="{{old('mobile',$data->mobile)}}" placeholder="Mobile." required>
                                        <div class="invalid-feedback">
                                            Please provide mobile number
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email ID." value="{{old('email',$data->email)}}" required>

                                        <div class="invalid-feedback">
                                            Please provide emailId
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pincode">Pincode <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('pincode') is-invalid @enderror" maxlength="6" id="pincode" name="pincode" value="{{old('pincode',$data->pincode)}}" placeholder="Pincode." required>
                                        <div class="invalid-feedback">
                                            Please provide pincode
                                        </div>
                                        <div class="invalid-feedback validerror">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="city">City <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="City." value="{{old('city',$data->city)}}" required>
                                        <div class="invalid-feedback">
                                            Please provide city
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="district">District <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" readonly class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{old('district',$data->district)}}" required>
                                        <div class="invalid-feedback">
                                            Please provide district
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="state">State <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="text" readonly class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{old('state',$data->state)}}" required>
                                        <div class="invalid-feedback">
                                            Please provide state
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Type address..." required>{{old('address',$data->address)}}</textarea>
                                        <input type="hidden" name="latitude" value="{{old('latitude',$data->latitude)}}" id="latitude">
                                        <input type="hidden" name="longitude" value="{{old('longitude',$data->longitude)}}" id="longitude">
                                        <div class="invalid-feedback">
                                            Please provide address
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label for="society_name">Society/Company Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control @error('society_name') is-invalid @enderror" id="society_name" name="society_name" value="{{old('society_name',$data->society)}}" placeholder="Society/Company" required>
                                        <div class="invalid-feedback">
                                            Please provide society/company name
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="sector">Sector/Street No.</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="number" class="form-control @error('sector') is-invalid @enderror" id="sector" name="sector" value="{{old('sector',$data->sector)}}" placeholder="Sector/Street No." required>
                                        <div class="invalid-feedback">
                                            Please provide sector/street no
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="generated_id">Generated ID</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="text" class="form-control @error('generated_id') is-invalid @enderror" id="generated_id" readonly value="{{old('generated_id',$data->generated_id)}}" name="generated_id" required>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="wallet_status">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="select" required>
                                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>                                 
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="wallet_status">Payment Status<span class="text-danger">*</span></label>
                                    <select name="payment_status" class="select" required>
                                        <option value="1" {{ $data->payment_status == 1 ? 'selected' : '' }}>Paid</option>
                                        <option value="0" {{ $data->payment_status == 0 ? 'selected' : '' }}>Pending</option>
                                    </select>                                 
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit">Update</button>
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
        var generateID = name+firstThreeNumbers+'@post.in';
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
