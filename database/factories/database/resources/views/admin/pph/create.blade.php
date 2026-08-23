@extends('admin.layouts.master')

@section('title') Create PPH @endsection

@section('content')
<!-- Row -->
<div class="row">
    <div class="col-sm-12">

        <!-- Custom Boostrap Validation -->
        <div class="card">
            <form action="{{route('admin.pph.create')}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
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
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{old('name')}}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="father_name">Father/Husband Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{old('father_name')}}" placeholder="Father/Husband Name" required>
                                        <div class="invalid-feedback">
                                            Please provide father/husband name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile">Mobile <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('mobile') is-invalid @enderror" maxlength="10" id="mobile" name="mobile" value="{{old('mobile')}}" placeholder="Mobile." required>
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
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email ID." value="{{old('email')}}" required>

                                        <div class="invalid-feedback">
                                            Please provide emailId
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pincode">Pincode <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('pincode') is-invalid @enderror" maxlength="6" id="pincode" name="pincode" value="{{old('pincode')}}" placeholder="Pincode." required>
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
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="City." value="{{old('city')}}" required>
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
                                        <input type="text" readonly class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{old('district')}}" required>
                                        <div class="invalid-feedback">
                                            Please provide district
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="state">State <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="text" readonly class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{old('state')}}" required>
                                        <div class="invalid-feedback">
                                            Please provide state
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Type address..." required>{{old('address')}}</textarea>
                                        <input type="hidden" name="latitude" value="{{old('latitude')}}" id="latitude">
                                        <input type="hidden" name="longitude" value="{{old('longitude')}}" id="longitude">
                                        <div class="invalid-feedback">
                                            Please provide address
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="generated_id">Generated ID</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                            <input type="text" class="form-control @error('generated_id') is-invalid @enderror" id="generated_id" readonly value="{{old('generated_id')}}" name="generated_id" placeholder="Auto generated" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h5 class="card-title mb-0">KYC Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="adhar_card">Aadhar Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('adhar_card') is-invalid @enderror" maxlength="12" id="adhar_card" name="adhar_card" value="{{old('adhar_card')}}" placeholder="Adhar Card Number..">

                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pan_card">Pan Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control @error('pan_card') is-invalid @enderror" id="pan_card" name="pan_card" value="{{old('pan_card')}}" placeholder="Pan Card Number">

                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="ifsc_code">IFSC Code</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" maxlength="11" onkeyup="return forceUpper(this);" id="ifsc_code" name="ifsc_code" value="{{old('ifsc_code')}}" placeholder="IFSC Code">
                                        <div class="text-danger" id="bank_ifsc_error"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="bank_name">Bank Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" readonly id="bank_name" name="bank_name" value="{{old('bank_name')}}" placeholder="Auto detect after filling IFSC code">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="branch_name">Branch Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control @error('branch_name') is-invalid @enderror" readonly id="branch_name" name="branch_name" value="{{old('branch_name')}}" placeholder="Auto detect after filling IFSC code">

                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_no">Account Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control NumberValidate @error('account_number') is-invalid @enderror" maxlength="16" id="account_no" value="{{old('account_number')}}" name="account_number" placeholder="Account Number">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h5 class="card-title mb-0">Upload Documents (<small class="text-danger">Image size upto 1 MB</small>)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="fileInput1">Adhar Front Image</label>
                                    <input type="file" class="form-control" id="fileInput1" onchange="displayFile(1)" name="adhar_front_img">
                                    <div id="fileContainer1">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fileInput2">Adhar Back Image</label>
                                    <input type="file" class="form-control" id="fileInput2" onchange="displayFile(2)" name="adhar_back_img">
                                    <div id="fileContainer2">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fileInput3">Pan Card Image</label>
                                    <input type="file" class="form-control" id="fileInput3" onchange="displayFile(3)" name="pan_img">
                                    <div id="fileContainer3">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="fileInput4">Cancel Cheque Image</label>
                                    <input type="file" class="form-control" id="fileInput4" onchange="displayFile(4)" name="cheque_img">
                                    <div id="fileContainer4">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fileInput5">Photo</label>
                                    <input type="file" class="form-control" id="fileInput5" onchange="displayFile(5)" name="photo">
                                    <div id="fileContainer5">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fileInput6">Other Document</label>
                                    <input type="file" class="form-control" id="fileInput6" onchange="displayFile(6)" name="other_document">
                                    <div id="fileContainer6">
                                    </div>
                                </div>
                            </div>
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
        $.ajax({
            type: 'GET',
            url: "{{ url('admin/city-state') }}/" + pincode + "?type=pph",
            success: function(data) {
                if (data.success === true) {
                    $('.customer_error').empty();
                    $('#district').empty().val(data.district);
                    $('#state').empty().val(data.state);
                    $('#generated_id').empty().val(data.generated_id);
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

    var bankIfsc = $("#ifsc_code");

    var bankIfscError = $("#bank_ifsc_error");

    var bankName = $("#bank_name");

    var bankBranch = $("#branch_name");

    bankIfsc.on("input", function() {
        var ifscCode = bankIfsc.val();

        if (ifscCode.length === 11) {
            $.ajax({
                url: "https://ifsc.razorpay.com/" + ifscCode,

                success: function(res, status, xhr) {
                    if (xhr.status === 200) {
                        bankName.val(res.BANK);

                        bankBranch.val(res.BRANCH);

                        bankIfscError.html("");
                    }
                },

                error: function(xhr, status, error) {
                    if (xhr.status === 404) {
                        bankIfscError.siblings(".backendError").remove();

                        bankIfscError.html("Please enter a valid IFSC Code");
                    }
                },
            });
        } else {
            bankName.val("");

            bankBranch.val("");

            bankIfscError.html("");
        }
    });
</script>

<script src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places&callback=initAutocomplete" type="text/javascript"></script>

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