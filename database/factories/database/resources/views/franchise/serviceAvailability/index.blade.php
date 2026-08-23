@extends('franchise.layouts.master')

@section('title') Service Availability @endsection

@section('content')



@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<style>
    .personal-info li .text {
        color: #0c48be;
        display: block;
        overflow: hidden;
        width: 70%;
        float: left;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-6 d-flex">
        <div class="card profile-box flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Pincode</h5>
            </div>
            <form>
                <div class="card-body">
                    <div class="col-md-12 mb-3">
                        <label for="pincode">Pincode<span class="text-danger">*</span></label>
                        <div class="input-group mt-2">
                            <span class="input-group-text"><i class="fa fa-building"></i></span>
                            <input type="text" class="form-control NumberValidate " maxlength="6" id="pincode" name="pincode" value="" placeholder="Enter Pincode">
                            <div class="invalid-feedback">
                                Please provide pincode
                            </div>
                            <div class="invalid-feedback validerror-destination">
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="text-center">
                                <button class="btn btn-primary mb-4" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-6 rate-details" style="display: none;">
        <div class="card profile-box flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Details</h5>
            </div>
            <div class="card-body">
                <ul class="personal-info">
                    <li>
                        <div class="title">City/District:</div>
                        <div class="cityp">Consignee City</div>
                    </li>
                    <li>
                        <div class="title">State:</div>
                        <div class="statep">Consignee State</div>
                    </li>
                    <li>
                        <div class="title">Zone</div>
                        <div class="zonep">456 Consignee Street</div>
                    </li>
                    <li>
                        <div class="title">PrePaid</div>
                        <div class="prepaidp">yes</div>
                    </li>
                    <li>
                        <div class="title">Cod</div>
                        <div class="codp">no</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


@push('page-javascript')


<script>
    $('form').on('submit', function(e) {
        e.preventDefault();
        if ($('#pincode').val() === '') {
            $('#pincode').next().css('display', 'block');
            return false;
        }

        var csrfToken = "{{ csrf_token() }}";
        let data = {
            pincode: $('#pincode').val(),
        }

        $.ajax({
            type: 'POST',
            url: "{{ route('franchise.availability.index') }}",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: data,
            success: function(data) {
                console.log("reached here", data)
                $('.validerror-destination').css('display', 'none');
                $('#pincode').next().css('display', 'none');
                if (data.message == 'success') {
                    $('.cityp').text(data.district)
                    $('.statep').text(data.state)
                    $('.zonep').text(data.zone)
                    $('.prepaidp').text(data.prePaid)
                    $('.codp').text(data.codp)
                    $('.rate-details').css('display', 'block');
                } else {
                    $('#pincode').next().css('display', 'none');
                    $('.validerror-destination').text('Please enter a valid pincode');
                    $('.validerror-destination').css('display', 'block');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('.error-message').text("An error occurred. Please try again.");
            }
        });
    });
</script>

@endpush


@endsection