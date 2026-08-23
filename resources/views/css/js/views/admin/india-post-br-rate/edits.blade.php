@extends('admin.layouts.master')

@section('title') India Post  Rates Management @endsection

@section('content')
<!-- Row -->
<div class="row">
    <div class="col-sm-12">

        <!-- Custom Boostrap Validation -->
        @if (count($rates) > 0)
        <div class="card">
            @if(request()->query('type')==1 || request()->query('type')==2)     
                <form action="{{route('admin.india-post-br-rate.edit',['type'=>request()->query('type')])}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="card-header">
                    <h5 class="card-title mb-0">India Post  Rates Management</h5>
                </div>
                <div class="card-body">
                    @include('admin.layouts.error')
                    <!-- <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label for="weight">Weight<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="weight" name="weight" placeholder="₹" value="{{ old('weight', $rates[0]['weight']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Weight
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="local">Local<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="local" name="local" placeholder="₹" value="{{ old('local',$rates[0]['local']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Local
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="upto_200_km">Upto 200 KM <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="upto_200_km" name="upto_200_km" placeholder="₹" value="{{ old('upto_200_km', $rates[0]['upto_200_km']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Upto 200 KM
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="rate_201_to_1000_km">201 To 1000 KM <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="rate_201_to_1000_km" name="rate_201_to_1000_km" placeholder="₹" value="{{ old('rate_201_to_1000_km', $rates[0]['201_to_1000_km']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter 201 To 1000 KM
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="rate_1001_to_2000_km">1001 To 2000 KM <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="rate_1001_to_2000_km" name="rate_1001_to_2000_km" placeholder="₹" value="{{ old('rate_1001_to_2000_km', $rates[0]['1001_to_2000_km']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter 1001 To 2000 KM
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="above_2000_km">Upto 200 KM <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="above_2000_km" name="above_2000_km" placeholder="₹" value="{{ old('above_2000_km', $rates[0]['above_2000_km']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Above 2000 KM
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div> -->

                 <!-- 5001 to 1000 -->
                 @foreach($rates as $index => $val)
    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <div class="row">

                    <div class="col-md-2 mb-3">
                        <label for="weight_{{ $index }}">Weight <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('weight_'.$index) is-invalid @enderror" id="weight_{{ $index }}" name="weight_{{ $index }}" placeholder="₹" value="{{ old('weight_'.$index, $val['weight']) }}" required>
                            <input type="hidden" name="id_{{ $index }}" value="{{ $val['id'] }}">

                            <div class="invalid-feedback">
                                Please Enter Weight
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="local_{{ $index }}">Local <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('local_'.$index) is-invalid @enderror" id="local_{{ $index }}" name="local_{{ $index }}" placeholder="₹" value="{{ old('local_'.$index, $val['local']) }}" required>
                            <div class="invalid-feedback">
                                Please Enter Local
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="upto_200_km_{{ $index }}">Upto 200 KM <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('upto_200_km_'.$index) is-invalid @enderror" id="upto_200_km_{{ $index }}" name="upto_200_km_{{ $index }}" placeholder="₹" value="{{ old('upto_200_km_'.$index, $val['upto_200_km']) }}" required>
                            <div class="invalid-feedback">
                                Please Enter Upto 200 KM
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="201_to_1000_km_{{ $index }}">201 To 1000 KM <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('201_to_1000_km_'.$index) is-invalid @enderror" id="201_to_1000_km_{{ $index }}" name="201_to_1000_km_{{ $index }}" placeholder="₹" value="{{ old('201_to_1000_km_'.$index, $val['201_to_1000_km']) }}" required>
                            <div class="invalid-feedback">
                                Please Enter 201 To 1000 KM
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="1001_to_2000_km_{{ $index }}">1001 To 2000 KM <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('1001_to_2000_km_'.$index) is-invalid @enderror" id="1001_to_2000_km_{{ $index }}" name="1001_to_2000_km_{{ $index }}" placeholder="₹" value="{{ old('1001_to_2000_km_'.$index, $val['1001_to_2000_km']) }}" required>
                            <div class="invalid-feedback">
                                Please Enter 1001 To 2000 KM
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="above_2000_km_{{ $index }}">Above 2000 KM <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('above_2000_km_'.$index) is-invalid @enderror" id="above_2000_km_{{ $index }}" name="above_2000_km_{{ $index }}" placeholder="₹" value="{{ old('above_2000_km_'.$index, $val['above_2000_km']) }}" required>
                            <div class="invalid-feedback">
                                Please Enter Above 2000 KM
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endforeach


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
            @endif
        </div>
        @endif
        <!-- /Custom Boostrap Validation -->

    </div>
</div>
<!-- /Row -->

@endsection
