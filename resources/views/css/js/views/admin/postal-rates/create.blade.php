@extends('admin.layouts.master')

@section('title') Postal Rates Management @endsection

@section('content')
<!-- Row -->
<div class="row">
    <div class="col-sm-12">

        <!-- Custom Boostrap Validation -->
        <div class="card">
            @if(request()->query('type')==1 || request()->query('type')==2)
            <form action="{{route('admin.postal-rates.store',['type'=>request()->query('type')])}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="card-header">
                    <h5 class="card-title mb-0">Upto 50 Grams</h5>
                </div>
                <div class="card-body">
                    @include('admin.layouts.error')
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Local (within muncipal Limits) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="p50gmlocal" name="p50gmlocal" placeholder="₹" value="{{ old('p50gmlocal') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Upto 200 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="p50gm200km" name="p50gm200km" placeholder="₹" value="{{ old('p50gm200km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">201 - 1000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="p50gm201to1000km" name="p50gm201to1000km" placeholder="₹" value="{{ old('p50gm201to1000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">1001 - 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="p50gm1001to2000km" name="p50gm1001to2000km" placeholder="₹" value="{{ old('p50gm1001to2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">above 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="p50gmAbove2000km" name="p50gmAbove2000km" placeholder="₹" value="{{ old('p50gmAbove2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- 50 t0 200gm --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">51 Grams to 200 Grams</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Local (within muncipal Limits) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="p51gmTo200gmlocal" name="p51gmTo200gmlocal" placeholder="₹" value="{{ old('p51gmTo200gmlocal') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Upto 200 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="p51gmTo200gm200km" name="p51gmTo200gm200km" placeholder="₹" value="{{ old('p51gmTo200gm200km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">201 - 1000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="p51gmTo200gm201to1000km" name="p51gmTo200gm201to1000km" placeholder="₹" value="{{ old('p51gmTo200gm201to1000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">1001 - 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="p51gmTo200gm1001to2000km" name="p51gmTo200gm1001to2000km" placeholder="₹" value="{{ old('p51gmTo200gm1001to2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">above 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="p51gmTo200gmAbove2000km" name="p51gmTo200gmAbove2000km" placeholder="₹" value="{{ old('p51gmTo200gmAbove2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 201 t0 500gm --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">201 Grams to 500 Grams</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Local (within muncipal Limits) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="p201gmTo500gmlocal" name="p201gmTo500gmlocal" placeholder="₹" value="{{ old('p201gmTo500gmlocal') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Upto 200 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="p201gmTo500gm200km" name="p201gmTo500gm200km" placeholder="₹" value="{{ old('p201gmTo500gm200km') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">201 - 1000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="p201gmTo500gm201to1000km" name="p201gmTo500gm201to1000km" placeholder="₹" value="{{ old('p201gmTo500gm201to1000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">1001 - 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="p201gmTo500gm1001to2000km" name="p201gmTo500gm1001to2000km" placeholder="₹" value="{{ old('p201gmTo500gm1001to2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">above 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="p201gmTo500gmAbove2000km" name="p201gmTo500gmAbove2000km" placeholder="₹" value="{{ old('p201gmTo500gmAbove2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h5 class="card-title mb-0">Additional 500 Grams or Part thereof</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Local (within muncipal Limits) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="additional500gmlocal" name="additional500gmlocal" placeholder="₹" value="{{ old('additional500gmlocal') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Upto 200 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="additional500gm200km" name="additional500gm200km" placeholder="₹" value="{{ old('additional500gm200km') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">201 - 1000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="additional500gm201to1000km" name="additional500gm201to1000km" placeholder="₹" value="{{ old('additional500gm201to1000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">1001 - 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="additional500gm1001to2000km" name="additional500gm1001to2000km" placeholder="₹" value="{{ old('additional500gm1001to2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">above 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="additional500gmAbove2000km" name="additional500gmAbove2000km" placeholder="₹" value="{{ old('additional500gmAbove2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please provide your name
                                        </div>
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
            @endif

            @if(request()->query('type')==3)
            <form action="{{route('admin.postal-rates.store',['type'=>request()->query('type')])}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="card-body">
                    @include('admin.layouts.error')
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="p20gm">Up to 20gms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p20gm') is-invalid @enderror" id="p20gm" name="p20gm" placeholder="₹" value="{{ old('p20gm') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p40gm">21 to 40 gms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p40gm') is-invalid @enderror" id="p40gm" name="p40gm" placeholder="₹" value="{{ old('p40gm') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p60gm">41 to 60 gms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p60gm') is-invalid @enderror" id="p60gm" name="p60gm" placeholder="₹" value="{{ old('p60gm') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p80gm">61 to 80 gms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p80gm') is-invalid @enderror" id="p80gm" name="p80gm" placeholder="₹" value="{{ old('p80gm') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p100gm">81 to 100 gms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p100gm') is-invalid @enderror" id="p100gm" name="p100gm" placeholder="₹" value="{{ old('p100gm') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p120gm">101 to 120 gms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p120gm') is-invalid @enderror" id="p120gm" name="p120gm" placeholder="₹" value="{{ old('p120gm') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p140gm">121 to 140 gms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p140gm') is-invalid @enderror" id="p140gm" name="p140gm" placeholder="₹" value="{{ old('p140gm') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p160gm">141 to 160 gms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p160gm') is-invalid @enderror" id="p160gm" name="p160gm" placeholder="₹" value="{{ old('p160gm') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p180gm">161 to 180 gms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p180gm') is-invalid @enderror" id="p180gm" name="p180gm" placeholder="₹" value="{{ old('p180gm') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p200gm">181 to 200 gms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p200gm') is-invalid @enderror" id="p200gm" name="p200gm" placeholder="₹" value="{{ old('p200gm') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
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
            @endif

            @if(request()->query('type')==4)
            <form action="{{route('admin.postal-rates.store',['type'=>request()->query('type')])}}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="card-header">
                    <h5 class="card-title mb-0">Upto 50 Grams</h5>
                </div>
                <div class="card-body">
                    @include('admin.layouts.error')
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="p50gmlocal">Local (within municipal Limits) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p50gmlocal') is-invalid @enderror" id="p50gmlocal" name="p50gmlocal" placeholder="₹" value="{{ old('p50gmlocal') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p50gm200km">Upto 200 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p50gm200km') is-invalid @enderror" id="p50gm200km" name="p50gm200km" placeholder="₹" value="{{ old('p50gm200km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p50gm201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p50gm201to1000km') is-invalid @enderror" id="p50gm201to1000km" name="p50gm201to1000km" placeholder="₹" value="{{ old('p50gm201to1000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p50gm1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p50gm1001to2000km') is-invalid @enderror" id="p50gm1001to2000km" name="p50gm1001to2000km" placeholder="₹" value="{{ old('p50gm1001to2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p50gmAbove2000km">above 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p50gmAbove2000km') is-invalid @enderror" id="p50gmAbove2000km" name="p50gmAbove2000km" placeholder="₹" value="{{ old('p50gmAbove2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 51 to 100gm --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">51 Grams to 100 Grams</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="p51gmTo100gmlocal">Local (within municipal Limits) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p51gmTo100gmlocal') is-invalid @enderror" id="p51gmTo100gmlocal" name="p51gmTo100gmlocal" placeholder="₹" value="{{ old('p51gmTo100gmlocal') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p51gmTo100gm200km">Upto 200 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p51gmTo100gm200km') is-invalid @enderror" id="p51gmTo100gm200km" name="p51gmTo100gm200km" placeholder="₹" value="{{ old('p51gmTo100gm200km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p51gmTo100gm201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p51gmTo100gm201to1000km') is-invalid @enderror" id="p51gmTo100gm201to1000km" name="p51gmTo100gm201to1000km" placeholder="₹" value="{{ old('p51gmTo100gm201to1000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p51gmTo100gm1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p51gmTo100gm1001to2000km') is-invalid @enderror" id="p51gmTo100gm1001to2000km" name="p51gmTo100gm1001to2000km" placeholder="₹" value="{{ old('p51gmTo100gm1001to2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p51gmTo100gmAbove2000km">above 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p51gmTo100gmAbove2000km') is-invalid @enderror" id="p51gmTo100gmAbove2000km" name="p51gmTo100gmAbove2000km" placeholder="₹" value="{{ old('p51gmTo100gmAbove2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 101 to 150gm --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">101 Grams to 150 Grams</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="p101gmTo150gmlocal">Local (within municipal Limits) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p101gmTo150gmlocal') is-invalid @enderror" id="p101gmTo150gmlocal" name="p101gmTo150gmlocal" placeholder="₹" value="{{ old('p101gmTo150gmlocal') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p101gmTo150gm200km">Upto 200 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p101gmTo150gm200km') is-invalid @enderror" id="p101gmTo150gm200km" name="p101gmTo150gm200km" placeholder="₹" value="{{ old('p101gmTo150gm200km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p101gmTo150gm201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p101gmTo150gm201to1000km') is-invalid @enderror" id="p101gmTo150gm201to1000km" name="p101gmTo150gm201to1000km" placeholder="₹" value="{{ old('p101gmTo150gm201to1000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p101gmTo150gm1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p101gmTo150gm1001to2000km') is-invalid @enderror" id="p101gmTo150gm1001to2000km" name="p101gmTo150gm1001to2000km" placeholder="₹" value="{{ old('p101gmTo150gm1001to2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p101gmTo150gmAbove2000km">above 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p101gmTo150gmAbove2000km') is-invalid @enderror" id="p101gmTo150gmAbove2000km" name="p101gmTo150gmAbove2000km" placeholder="₹" value="{{ old('p101gmTo150gmAbove2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 151 to 200gm --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">151 Grams to 200 Grams</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="p151gmTo200gmlocal">Local (within municipal Limits) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p151gmTo200gmlocal') is-invalid @enderror" id="p151gmTo200gmlocal" name="p151gmTo200gmlocal" placeholder="₹" value="{{ old('p151gmTo200gmlocal') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p151gmTo200gm200km">Upto 200 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p151gmTo200gm200km') is-invalid @enderror" id="p151gmTo200gm200km" name="p151gmTo200gm200km" placeholder="₹" value="{{ old('p151gmTo200gm200km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p151gmTo200gm201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p151gmTo200gm201to1000km') is-invalid @enderror" id="p151gmTo200gm201to1000km" name="p151gmTo200gm201to1000km" placeholder="₹" value="{{ old('p151gmTo200gm201to1000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p151gmTo200gm1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p151gmTo200gm1001to2000km') is-invalid @enderror" id="p151gmTo200gm1001to2000km" name="p151gmTo200gm1001to2000km" placeholder="₹" value="{{ old('p151gmTo200gm1001to2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p151gmTo200gmAbove2000km">above 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p151gmTo200gmAbove2000km') is-invalid @enderror" id="p151gmTo200gmAbove2000km" name="p151gmTo200gmAbove2000km" placeholder="₹" value="{{ old('p151gmTo200gmAbove2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 201 to 250gm --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">201 Grams to 250 Grams</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="p201gmTo250gmlocal">Local (within municipal Limits) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p201gmTo250gmlocal') is-invalid @enderror" id="p201gmTo250gmlocal" name="p201gmTo250gmlocal" placeholder="₹" value="{{ old('p201gmTo250gmlocal') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p201gmTo250gm200km">Upto 200 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p201gmTo250gm200km') is-invalid @enderror" id="p201gmTo250gm200km" name="p201gmTo250gm200km" placeholder="₹" value="{{ old('p201gmTo250gm200km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p201gmTo250gm201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p201gmTo250gm201to1000km') is-invalid @enderror" id="p201gmTo250gm201to1000km" name="p201gmTo250gm201to1000km" placeholder="₹" value="{{ old('p201gmTo250gm201to1000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p201gmTo250gm1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p201gmTo250gm1001to2000km') is-invalid @enderror" id="p201gmTo250gm1001to2000km" name="p201gmTo250gm1001to2000km" placeholder="₹" value="{{ old('p201gmTo250gm1001to2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="p201gmTo250gmAbove2000km">above 2000 Kms <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('p201gmTo250gmAbove2000km') is-invalid @enderror" id="p201gmTo250gmAbove2000km" name="p201gmTo250gmAbove2000km" placeholder="₹" value="{{ old('p201gmTo250gmAbove2000km') }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Postal rate
                                        </div>
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
            @endif
        </div>
        <!-- /Custom Boostrap Validation -->

    </div>
</div>
<!-- /Row -->

@endsection