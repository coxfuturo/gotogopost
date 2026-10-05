@extends('admin.layouts.master')

@section('title') Update Sales Marketing @endsection

@section('content')

<!-- Row -->
<div class="row">
    <div class="col-sm-12">

        <div class="card">

            <form action="{{ route('admin.m_manager.update', $manager->id) }}"
                  enctype="multipart/form-data"
                  method="POST"
                  class="needs-validation"
                  novalidate>

                @csrf

                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Details</h5>
                </div>

                <div class="card-body">

                    @include('admin.layouts.error')

                    <div class="row">
                        <div class="col-sm">

                            <!-- Name / Father Name / Mobile -->
                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label for="name">
                                        Name <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </span>

                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               placeholder="Name"
                                               value="{{ old('name', $manager->name) }}"
                                               required>

                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="father_name">
                                        Father Name / Director Name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </span>

                                        <input type="text"
                                               class="form-control @error('father_name') is-invalid @enderror"
                                               id="father_name"
                                               name="father_name"
                                               placeholder="Father Name / Director Name"
                                               value="{{ old('father_name', $manager->father_name) }}"
                                               required>

                                        <div class="invalid-feedback">
                                            Please provide father/director name
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="mobile">
                                        Mobile <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-mobile"></i>
                                        </span>

                                        <input type="text"
                                               class="form-control NumberValidate @error('mobile') is-invalid @enderror"
                                               maxlength="10"
                                               id="mobile"
                                               name="mobile"
                                               value="{{ old('mobile', $manager->mobile) }}"
                                               placeholder="Mobile"
                                               required>

                                        <div class="invalid-feedback">
                                            Please provide mobile number
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- Email / Pincode / City -->
                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label for="email">
                                        Email <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-envelope"></i>
                                        </span>

                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               placeholder="Email ID"
                                               value="{{ old('email', $manager->email) }}"
                                               required>

                                        <div class="invalid-feedback">
                                            Please provide email ID
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="pincode">
                                        Pincode <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-building"></i>
                                        </span>

                                        <input type="text"
                                               class="form-control NumberValidate @error('pincode') is-invalid @enderror"
                                               maxlength="6"
                                               id="pincode"
                                               name="pincode"
                                               placeholder="Pincode"
                                               value="{{ old('pincode', $manager->pincode) }}"
                                               required>

                                        <div class="invalid-feedback">
                                            Please provide pincode
                                        </div>

                                        <div class="invalid-feedback validerror"></div>
                                    </div>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="city">
                                        City <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-building"></i>
                                        </span>

                                        <input type="text"
                                               class="form-control @error('city') is-invalid @enderror"
                                               id="city"
                                               name="city"
                                               placeholder="City"
                                               value="{{ old('city', $manager->city) }}"
                                               required>

                                        <div class="invalid-feedback">
                                            Please provide city
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- District / State / Address -->
                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label for="district">
                                        District <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-building"></i>
                                        </span>

                                        <input type="text"
                                               readonly
                                               class="form-control @error('district') is-invalid @enderror"
                                               id="district"
                                               name="district"
                                               value="{{ old('district', $manager->district) }}"
                                               required>

                                        <div class="invalid-feedback">
                                            Please provide district
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="state">
                                        State <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-map"></i>
                                        </span>

                                        <input type="text"
                                               readonly
                                               class="form-control @error('state') is-invalid @enderror"
                                               id="state"
                                               name="state"
                                               value="{{ old('state', $manager->state) }}"
                                               required>

                                        <div class="invalid-feedback">
                                            Please provide state
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="address">
                                        Address <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-building"></i>
                                        </span>

                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                                  id="address"
                                                  name="address"
                                                  placeholder="Type address..."
                                                  required>{{ old('address', $manager->address) }}</textarea>

                                        <input type="hidden"
                                               name="latitude"
                                               id="latitude"
                                               value="{{ old('latitude', $manager->latitude) }}">

                                        <input type="hidden"
                                               name="longitude"
                                               id="longitude"
                                               value="{{ old('longitude', $manager->longitude) }}">

                                        <div class="invalid-feedback">
                                            Please provide address
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- Society / Sector / Generated ID -->
                            <div class="row">

                                <div class="col-md-5 mb-3">
                                    <label for="society_name">
                                        Society / Company Name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-building"></i>
                                        </span>

                                        <input type="text"
                                               class="form-control @error('society_name') is-invalid @enderror"
                                               id="society_name"
                                               name="society_name"
                                               placeholder="Society / Company"
                                               value="{{ old('society_name', $manager->society) }}"
                                               required>

                                        <div class="invalid-feedback">
                                            Please provide society/company name
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3 mb-3">
                                    <label for="sector">
                                        Sector / Street No.
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-map-marker"></i>
                                        </span>

                                        <input type="text"
                                               class="form-control @error('sector') is-invalid @enderror"
                                               id="sector"
                                               name="sector"
                                               placeholder="Sector / Street No."
                                               value="{{ old('sector', $manager->sector) }}">

                                        <div class="invalid-feedback">
                                            Please provide sector/street no
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="generated_id">
                                        Generated ID
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-id-card"></i>
                                        </span>

                                        <input type="text"
                                               class="form-control"
                                               id="generated_id"
                                               name="generated_id"
                                               value="{{ old('generated_id', $manager->generated_id) }}"
                                               readonly>
                                    </div>
                                </div>

                            </div>


                            <!-- Status / Payment Status / GST -->
                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label for="status">
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status"
                                            id="status"
                                            class="select"
                                            required>

                                        <option value="1"
                                            {{ old('status', $manager->status) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0"
                                            {{ old('status', $manager->status) == 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="payment_status">
                                        Payment Status
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="payment_status"
                                            id="payment_status"
                                            class="select"
                                            required>

                                        <option value="1"
                                            {{ old('payment_status', $manager->payment_status) == 1 ? 'selected' : '' }}>
                                            Paid
                                        </option>

                                        <option value="0"
                                            {{ old('payment_status', $manager->payment_status) == 0 ? 'selected' : '' }}>
                                            Pending
                                        </option>

                                    </select>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="gst_number">
                                        GST Number
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-file-text"></i>
                                        </span>

                                        <input type="text"
                                               class="form-control @error('gst_number') is-invalid @enderror"
                                               id="gst_number"
                                               name="gst_number"
                                               placeholder="GST Number"
                                               value="{{ old('gst_number', $manager->gst_number) }}">
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


                <!-- Update Button -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="text-center">

                                <button class="btn btn-primary" type="submit">
                                    Update
                                </button>

                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>

    </div>
</div>
<!-- /Row -->


@push('page-javascript')

<script>

    /*
    |--------------------------------------------------------------------------
    | Pincode -> District / State
    |--------------------------------------------------------------------------
    */

    $('#pincode').on('change', function () {

        var pincode = $(this).val();
        var name = $('#name').val();

        if (pincode.length !== 6) {
            return;
        }

        var firstThreeNumbers = pincode.substring(0, 3);
        var generateID = name + firstThreeNumbers + '@post.in';

        $.ajax({
            type: 'GET',

            url: "{{ url('admin/city-state') }}/" + pincode + "?type=m_manager",

            success: function (data) {

                if (data.success === true) {

                    $('.customer_error').empty();

                    $('#district').val(data.district);
                    $('#state').val(data.state);
                    $('#generated_id').val(generateID);

                    $('.validerror').empty();

                } else {

                    $('.validerror').text(
                        'Please enter valid pincode'
                    );
                }
            },

            error: function () {

                $('.validerror').text(
                    'Unable to fetch city/state'
                );
            }
        });

    });


    /*
    |--------------------------------------------------------------------------
    | Google Maps Address Autocomplete
    |--------------------------------------------------------------------------
    */

    function initialize() {

        var input = document.getElementById('address');

        if (!input) {
            return;
        }

        var autocomplete =
            new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function () {

            var place = autocomplete.getPlace();

            if (place.geometry && place.geometry.location) {

                $('#latitude').val(
                    place.geometry.location.lat()
                );

                $('#longitude').val(
                    place.geometry.location.lng()
                );
            }

        });
    }

</script>


<script src="https://maps.google.com/maps/api/js?key=AIzaSyARf505VVJ_bn-5BnQ5qFbyKqWGF4DRn9U&libraries=places&callback=initialize"
        type="text/javascript">
</script>

@endpush

@endsection