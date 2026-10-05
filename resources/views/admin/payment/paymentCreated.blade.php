@extends('admin.layouts.master')
@section('title') Franchise Credit Payment @endsection
@section('content')
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonText: 'Okay'
    });
</script>
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
                <h5 class="card-title mb-0">Payment</h5>
            </div>
            <form method="POST" action="{{ route('admin.franchise-payment.store') }}">
                @csrf
                <div class="card-body">
                    <div class="col-md-12 mb-3">
                        <label for="amount">Amount <span class="text-danger">*</span></label>
                        <div class="input-group mt-2">
                            <span class="input-group-text"><i class="fa fa-money-bill"></i></span>
                            <input type="number" class="form-control NumberValidate" id="amount" name="amount"
                            placeholder="Enter Amount" required min="1">
                            <div class="invalid-feedback">
                                Please Enter a Valid Amount
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="amount">Business Associate <span class="text-danger">*</span></label>
                        <div class="input-group mt-2">
                            <span style="width:100%">
                             <select class="form-control" name="franchise">
                                <option selected disabled>Select Business Associate</option>
                                @foreach($data as $list)
                                <option value="{{$list->id}}">{{$list->name}}</option>
                                @endforeach
                            </select></span>
                            <div class="invalid-feedback">
                                Please Enter a Valid Amount
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mb-4">Pay Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection