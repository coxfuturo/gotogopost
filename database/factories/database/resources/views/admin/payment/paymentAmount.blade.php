@extends('admin.layouts.master')

@section('title') Register Payment Amount @endsection

@section('content')
<!-- Row -->
<div class="row">
    <div class="col-sm-12">

        <!-- Custom Boostrap Validation -->
        <div class="card">
            <form action="{{route('admin.payment.amount.store')}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="card-header">
                    <h5 class="card-title mb-0">Register Payment Amount</h5>
                </div>
                <div class="card-body">
                    @include('admin.layouts.error')
                    <div class="row mt-5">
                        <div class="col-sm">

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="name">Franchise Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="la la-money"></i></span>
                                        <input type="num" class="form-control @error('franchise') is-invalid @enderror" name="franchise" placeholder="Amount" value="{{old('franchise', rtrim(rtrim($data->franchise, '0'), '.'))}}" required>
                                        <div class="invalid-feedback">
                                            Please franchise amount
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="father_name">CPH<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="la la-money"></i></span>
                                        <input type="num" class="form-control @error('cph') is-invalid @enderror" name="cph" value="{{old('cph',rtrim(rtrim($data->cph, '0'), '.'))}}" placeholder="Amount" required>
                                        <div class="invalid-feedback">
                                            Please CPH amount
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="mobile">PPH<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="la la-money"></i></span>
                                        <input type="num" class="form-control NumberValidate @error('pph') is-invalid @enderror" maxlength="10" id="mobile" name="pph" value="{{old('pph',rtrim(rtrim($data->pph, '0'), '.'))}}" placeholder="Amount" required>
                                        <div class="invalid-feedback">
                                            Please PPH amount
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="mobile">Franchise & CPH<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="la la-money"></i></span>
                                        <input type="num" class="form-control NumberValidate @error('combo') is-invalid @enderror" maxlength="10" id="mobile" name="combo" value="{{old('combo', rtrim(rtrim($data->combo, '0'), '.'))}}" placeholder="Amount" required>
                                        <div class="invalid-feedback">
                                            Please Franchise & CPH amount
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3 text-center">
                                    <label for="father_name">&nbsp;</label>
                                    <div>
                                      <button class="btn btn-primary" type="submit">Submit</button>
                                       </div>
                                </div>

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

@endpush

@endsection