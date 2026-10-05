@extends('admin.layouts.master')

@section('title')
    Update Business Bulk
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">

        <div class="card">

            <form action="{{ route('admin.e-customer.update', $customer->id) }}"
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

                        {{-- Name --}}
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
                                       value="{{ old('name', $customer->name) }}"
                                       placeholder="Name"
                                       required>
                            </div>

                            @error('name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Father / Owner --}}
                        <div class="col-md-4 mb-3">
                            <label for="father_name">
                                Director/Owner Name <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-user"></i>
                                </span>

                                <input type="text"
                                       class="form-control @error('father_name') is-invalid @enderror"
                                       id="father_name"
                                       name="father_name"
                                       value="{{ old('father_name', $customer->father_name) }}"
                                       placeholder="Director/Owner Name"
                                       required>
                            </div>

                            @error('father_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Mobile --}}
                        <div class="col-md-4 mb-3">
                            <label for="mobile">
                                Mobile <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-phone"></i>
                                </span>

                                <input type="text"
                                       class="form-control NumberValidate @error('mobile') is-invalid @enderror"
                                       maxlength="10"
                                       id="mobile"
                                       name="mobile"
                                       value="{{ old('mobile', $customer->mobile) }}"
                                       placeholder="Mobile"
                                       required>
                            </div>

                            @error('mobile')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Email --}}
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
                                       value="{{ old('email', $customer->email) }}"
                                       placeholder="Email ID"
                                       required>
                            </div>

                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Pincode --}}
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
                                       value="{{ old('pincode', $customer->pincode) }}"
                                       placeholder="Pincode"
                                       required>
                            </div>

                            <div class="invalid-feedback validerror"></div>

                            @error('pincode')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- City --}}
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
                                       value="{{ old('city', $customer->city) }}"
                                       placeholder="City"
                                       required>
                            </div>

                            @error('city')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- District --}}
                        <div class="col-md-4 mb-3">
                            <label for="district">
                                District <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-building"></i>
                                </span>

                                <input type="text"
                                       class="form-control @error('district') is-invalid @enderror"
                                       id="district"
                                       name="district"
                                       value="{{ old('district', $customer->district) }}"
                                       placeholder="District"
                                       required>
                            </div>

                            @error('district')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- State --}}
                        <div class="col-md-4 mb-3">
                            <label for="state">
                                State <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-map"></i>
                                </span>

                                <input type="text"
                                       class="form-control @error('state') is-invalid @enderror"
                                       id="state"
                                       name="state"
                                       value="{{ old('state', $customer->state) }}"
                                       placeholder="State"
                                       required>
                            </div>

                            @error('state')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Address --}}
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
                                          required>{{ old('address', $customer->address) }}</textarea>
                            </div>

                            @error('address')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Latitude --}}
                        <input type="hidden"
                               name="latitude"
                               id="latitude"
                               value="{{ old('latitude', $customer->latitude) }}">


                        {{-- Longitude --}}
                        <input type="hidden"
                               name="longitude"
                               id="longitude"
                               value="{{ old('longitude', $customer->longitude) }}">


                        {{-- Society --}}
                        <div class="col-md-4 mb-3">
                            <label for="society_name">
                                Society/Company Name <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-building"></i>
                                </span>

                                <input type="text"
                                       class="form-control @error('society_name') is-invalid @enderror"
                                       id="society_name"
                                       name="society_name"
                                       value="{{ old('society_name', $customer->society) }}"
                                       placeholder="Society/Company"
                                       required>
                            </div>

                            @error('society_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Sector --}}
                        <div class="col-md-4 mb-3">
                            <label for="sector">
                                Sector/Street No.
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-map-marker"></i>
                                </span>

                                <input type="text"
                                       class="form-control @error('sector') is-invalid @enderror"
                                       id="sector"
                                       name="sector"
                                       value="{{ old('sector', $customer->sector) }}"
                                       placeholder="Sector/Street No.">
                            </div>

                            @error('sector')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Generated ID --}}
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
                                       value="{{ old('generated_id', $customer->generated_id) }}"
                                       readonly>
                            </div>
                        </div>


                        {{-- Register Type --}}
                        <div class="col-md-4 mb-3">
                            <label for="register_type">
                                Register Type
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-list"></i>
                                </span>

                                <input type="text"
                                       class="form-control"
                                       id="register_type"
                                       name="register_type"
                                       value="{{ old('register_type', $customer->register_type) }}"
                                       placeholder="Register Type">
                            </div>
                        </div>


                        {{-- GST --}}
                        <div class="col-md-4 mb-3">
                            <label for="gst_number">
                                GST Number
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-file-text"></i>
                                </span>

                                <input type="text"
                                       class="form-control"
                                       id="gst_number"
                                       name="gst_number"
                                       value="{{ old('gst_number', $customer->gst_number) }}"
                                       placeholder="GST Number">
                            </div>
                        </div>


                        {{-- Location --}}
                        <div class="col-md-4 mb-3">
                            <label for="location">
                                Location <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-map-marker"></i>
                                </span>

                                <input type="text"
                                       class="form-control @error('location') is-invalid @enderror"
                                       id="location"
                                       name="location"
                                       value="{{ old('location', $customer->location) }}"
                                       placeholder="Location"
                                       required>
                            </div>

                            @error('location')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Status --}}
                        <div class="col-md-4 mb-3">
                            <label for="status">
                                Status <span class="text-danger">*</span>
                            </label>

                            <select name="status"
                                    id="status"
                                    class="select"
                                    required>

                                <option value="1"
                                    {{ old('status', $customer->status) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ old('status', $customer->status) == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
    <label for="payment_status">
        Payment Status <span class="text-danger">*</span>
    </label>

    <select name="payment_status"
            id="payment_status"
            class="select"
            required>

        <option value="1"
            {{ old('payment_status', $customer->payment_status) == 1 ? 'selected' : '' }}>
            Paid
        </option>

        <option value="0"
            {{ old('payment_status', $customer->payment_status) == 0 ? 'selected' : '' }}>
            Pending
        </option>

    </select>

    @error('payment_status')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>


                        {{-- Password --}}
                        <div class="col-md-4 mb-3">
                            <label for="password">
                                Password
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-lock"></i>
                                </span>

                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Leave blank to keep current password">
                            </div>

                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- KYC --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">KYC Details</h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label for="adhar_card">Aadhaar Card</label>
                            <input type="text"
                                   class="form-control"
                                   id="adhar_card"
                                   name="adhar_card"
                                   value="{{ old('adhar_card', optional($customer->kyc)->adhar_card) }}"
                                   placeholder="Aadhaar Card">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="pan_card">PAN Card</label>
                            <input type="text"
                                   class="form-control"
                                   id="pan_card"
                                   name="pan_card"
                                   value="{{ old('pan_card', optional($customer->kyc)->pan_card) }}"
                                   placeholder="PAN Card">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="ifsc_code">IFSC Code</label>
                            <input type="text"
                                   class="form-control"
                                   id="ifsc_code"
                                   name="ifsc_code"
                                   value="{{ old('ifsc_code', optional($customer->kyc)->ifsc_code) }}"
                                   placeholder="IFSC Code">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="bank_name">Bank Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="bank_name"
                                   name="bank_name"
                                   value="{{ old('bank_name', optional($customer->kyc)->bank_name) }}"
                                   placeholder="Bank Name">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="branch_name">Branch Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="branch_name"
                                   name="branch_name"
                                   value="{{ old('branch_name', optional($customer->kyc)->branch_name) }}"
                                   placeholder="Branch Name">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="account_number">Account Number</label>
                            <input type="text"
                                   class="form-control"
                                   id="account_number"
                                   name="account_number"
                                   value="{{ old('account_number', optional($customer->kyc)->account_number) }}"
                                   placeholder="Account Number">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="adhar_front_img">Aadhaar Front</label>
                            <input type="file"
                                   class="form-control"
                                   id="adhar_front_img"
                                   name="adhar_front_img">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="adhar_back_img">Aadhaar Back</label>
                            <input type="file"
                                   class="form-control"
                                   id="adhar_back_img"
                                   name="adhar_back_img">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="pan_img">PAN Image</label>
                            <input type="file"
                                   class="form-control"
                                   id="pan_img"
                                   name="pan_img">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="cheque_img">Cancelled Cheque</label>
                            <input type="file"
                                   class="form-control"
                                   id="cheque_img"
                                   name="cheque_img">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="photo">Photo</label>
                            <input type="file"
                                   class="form-control"
                                   id="photo"
                                   name="photo">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="other_document">Other Document</label>
                            <input type="file"
                                   class="form-control"
                                   id="other_document"
                                   name="other_document">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="video_kyc">Video KYC</label>
                            <input type="file"
                                   class="form-control"
                                   id="video_kyc"
                                   name="video_kyc">
                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row">
                        <div class="col-sm">
                            <div class="text-center">

                                <button class="btn btn-primary"
                                        type="submit">
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


@push('page-javascript')

<script>

    $('#pincode').on('change', function () {

        var pincode = $('#pincode').val();

        $.ajax({

            type: 'GET',

            url: "{{ url('admin/city-state') }}/" + pincode + "?type=e_customer",

            success: function (data) {

                if (data.success === true) {

                    $('.validerror').empty();

                    $('#district').val(data.district);
                    $('#state').val(data.state);

                } else {

                    $('.validerror').text(
                        'Please enter valid pincode'
                    );

                }

            },

            error: function () {

                $('.validerror').text(
                    'Unable to fetch city/state details'
                );

            }

        });

    });

</script>

@endpush

@endsection