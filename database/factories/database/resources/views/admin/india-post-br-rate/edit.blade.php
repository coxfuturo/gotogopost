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
                    <h5 class="card-title mb-0">Local</h5>
                </div>
                <div class="card-body">
                    @include('admin.layouts.error')
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Upto 2 kgs<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="local_upto_2kg" name="local_upto_2kg" placeholder="₹" value="{{ old('local_upto_2kg', $rates[0]['upto_2kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Upto 2 kgs
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every addl kg upto 5kg<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="addl_upto_5kg" name="local_addl_upto_5kg" placeholder="₹" value="{{ old('local_addl_upto_5kg',$rates[0]['addl_upto_5kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Addl kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every above kg upto 5kg <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="above_upto_5kg" name="local_above_upto_5kg" placeholder="₹" value="{{ old('local_above_upto_5kg', $rates[0]['above_upto_5kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Above kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h5 class="card-title mb-0">Within State</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Upto 2 kgs<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="upto_2kg" name="within_upto_2kg" placeholder="₹" value="{{ old('within_upto_2kg', $rates[1]['upto_2kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Upto 2 kgs
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every addl kg upto 5kg<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="addl_upto_5kg" name="within_addl_upto_5kg" placeholder="₹" value="{{ old('within_addl_upto_5kg', $rates[1]['addl_upto_5kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Addl kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every above kg upto 5kg <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="above_upto_5kg" name="within_above_upto_5kg" placeholder="₹" value="{{ old('within_above_upto_5kg', $rates[1]['above_upto_5kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Above kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h5 class="card-title mb-0">Neighbouring State</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Upto 2 kgs<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="neighbouring_upto_2kg" name="neighbouring_upto_2kg" placeholder="₹" value="{{ old('neighbouring_upto_2kg', $rates[2]['upto_2kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Upto 2 kgs
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every addl kg upto 5kg<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="addl_upto_5kg" name="neighbouring_addl_upto_5kg" placeholder="₹" value="{{ old('neighbouring_addl_upto_5kg', $rates[2]['addl_upto_5kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Addl kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every above kg upto 5kg <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="above_upto_5kg" name="neighbouring_above_upto_5kg" placeholder="₹" value="{{ old('neighbouring_above_upto_5kg', $rates[2]['above_upto_5kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Above kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h5 class="card-title mb-0">Other State</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Upto 2 kgs<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="upto_2kg" name="other_upto_2kg" placeholder="₹" value="{{ old('other_upto_2kg', $rates[3]['upto_2kg']) }}"  required>
                                        <div class="invalid-feedback">
                                            Please Enter Upto 2 kgs
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every addl kg upto 5kg<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="addl_upto_5kg" name="other_addl_upto_5kg" placeholder="₹" value="{{ old('other_addl_upto_5kg', $rates[3]['addl_upto_5kg']) }}"  required>
                                        <div class="invalid-feedback">
                                            Please Enter Addl kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every above kg upto 5kg <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="above_upto_5kg" name="other_above_upto_5kg" placeholder="₹" value="{{ old('other_above_upto_5kg', $rates[3]['above_upto_5kg']) }}"  required>
                                        <div class="invalid-feedback">
                                            Please Enter Above kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h5 class="card-title mb-0">Between Metro and  State Capitals</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Upto 2 kgs<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="upto_2kg" name="between_capital_upto_2kg" placeholder="₹" value="{{ old('between_capital_upto_2kg', $rates[4]['upto_2kg']) }}"  required>
                                        <div class="invalid-feedback">
                                            Please Enter Upto 2 kgs
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every addl kg upto 5kg<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="addl_upto_5kg" name="between_capital_addl_upto_5kg" placeholder="₹" value="{{ old('between_capital_addl_upto_5kg', $rates[4]['addl_upto_5kg']) }}"  required>
                                        <div class="invalid-feedback">
                                            Please Enter Addl kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every above kg upto 5kg <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="above_upto_5kg" name="between_capital_above_upto_5kg" placeholder="₹" value="{{ old('between_capital_above_upto_5kg', $rates[4]['above_upto_5kg']) }}"  required>
                                        <div class="invalid-feedback">
                                            Please Enter Above kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h5 class="card-title mb-0">NCR-Delhi/ Ghaziabad/ Noida/ Greater Noida/ Faridabad </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Upto 2 kgs<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="upto_2kg" name="ncr_delhi_upto_2kg" placeholder="₹" value="{{ old('ncr_delhi_upto_2kg', $rates[4]['upto_2kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Upto 2 kgs
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every addl kg upto 5kg<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="addl_upto_5kg" name="ncr_delhi_addl_upto_5kg" placeholder="₹" value="{{ old('ncr_delhi_addl_upto_5kg', $rates[4]['addl_upto_5kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Addl kg upto 5kg
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PickupName">Every above kg upto 5kg <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('name') is-invalid @enderror" id="above_upto_5kg" name="ncr_delhi_above_upto_5kg" placeholder="₹" value="{{ old('ncr_delhi_above_upto_5kg', $rates[4]['above_upto_5kg']) }}" required>
                                        <div class="invalid-feedback">
                                            Please Enter Above kg upto 5kg
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
        @endif
        <!-- /Custom Boostrap Validation -->

    </div>
</div>
<!-- /Row -->

@endsection
