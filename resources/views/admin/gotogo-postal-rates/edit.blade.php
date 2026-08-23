@extends('admin.layouts.master') @section('title') Gotogo Postal Rates @endsection @section('content')
<!-- Row -->
<div class="row">
    <div class="col-sm-12">
        <!-- Custom Boostrap Validation -->
        @if (count($rates) > 0)
        <div class="card">


            @if (request()->query('type') == "1")
            <form action="{{ route('admin.gotogo-postal-rates.edit', ['type' => request()->query('type')]) }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                @csrf
                @php
                    $weightRanges = [
                        'Upto 250gm',
                        'Additional 250gm to 500gm',
                        'Additional 500gm to 1kg',
                        'Additional 1kg to 1.5kg',
                        'Additional 1.5kg to 2kg',
                        'Additional 2kg to 2.5kg',
                        'Additional 2.5kg to 3kg',
                        'Additional 3kg to 3.5kg',
                        'Additional 3.5kg to 4kg',
                        'Additional 4kg to 4.5kg',
                        'Additional 4.5kg to 5kg'
                    ];
                @endphp

                @foreach ($weightRanges as $range)
                @php
                $inputPrefix = str_replace([' ', 'kg'], '', str_replace('.', '_', $range));

                $rate = $rates->where('weight', $range)->first();
                @endphp

                <div class="card-header">
                    <h5 class="card-title mb-0">Additional {{ $range }}</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label for="w{{ $inputPrefix }}local">Local <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('w'.$inputPrefix.'local') is-invalid @enderror"
                                        id="w{{ $inputPrefix }}local"
                                        name="w{{ $inputPrefix }}local"
                                        placeholder="₹"
                                        value="{{ old('w'.$inputPrefix.'local', $rate ? $rate->Local : '') }}" required />
                                    <div class="invalid-feedback">Please Enter Postal rate</div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w{{ $inputPrefix }}200km">Upto 200 Kms <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('w'.$inputPrefix.'200km') is-invalid @enderror"
                                        id="w{{ $inputPrefix }}200km"
                                        name="w{{ $inputPrefix }}200km"
                                        placeholder="₹"
                                        value="{{ old('w'.$inputPrefix.'200km', $rate ? $rate->upto_200_kms : '') }}" required />
                                    <div class="invalid-feedback">Please Enter Postal rate</div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w{{ $inputPrefix }}201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('w'.$inputPrefix.'201to1000km') is-invalid @enderror"
                                        id="w{{ $inputPrefix }}201to1000km"
                                        name="w{{ $inputPrefix }}201to1000km"
                                        placeholder="₹"
                                        value="{{ old('w'.$inputPrefix.'201to1000km', $rate ? $rate->{"201_to_1000_kms"} : '') }}" required />
                                    <div class="invalid-feedback">Please Enter Postal rate</div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w{{ $inputPrefix }}1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('w'.$inputPrefix.'1001to2000km') is-invalid @enderror"
                                        id="w{{ $inputPrefix }}1001to2000km"
                                        name="w{{ $inputPrefix }}1001to2000km"
                                        placeholder="₹"
                                        value="{{ old('w'.$inputPrefix.'1001to2000km', $rate ? $rate->{"1001_to_2000_kms"} : '') }}" required />
                                    <div class="invalid-feedback">Please Enter Postal rate</div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w{{ $inputPrefix }}Above2000km">Above 2000 Kms <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('w'.$inputPrefix.'Above2000km') is-invalid @enderror"
                                        id="w{{ $inputPrefix }}Above2000km"
                                        name="w{{ $inputPrefix }}Above2000km"
                                        placeholder="₹"
                                        value="{{ old('w'.$inputPrefix.'Above2000km', $rate ? $rate->above_2000_kms : '') }}" required />
                                    <div class="invalid-feedback">Please Enter Postal rate</div>
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





            @if (request()->query('type') == 3)
            <form action="{{ route('admin.gotogo-postal-rates.edit', ['type' => request()->query('type')]) }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                @csrf

                {{-- First 2 kg --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">First 1 kg</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                @php
                                $first2kg = $rates[0] ?? []; // Ensure $rates[0] exists
                                @endphp

                                <div class="col-md-2 mb-3">
                                    <label for="w2000gm_local">Local <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w2000gm_local" name="w2000gm_local"
                                        value="{{ old('w2000gm_local', $first2kg['Local'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w2000gm_200km">Upto 200 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w2000gm_200km" name="w2000gm_200km"
                                        value="{{ old('w2000gm_200km', $first2kg['upto_200_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w2000gm_201to1000km">201 to 1000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w2000gm_201to1000km" name="w2000gm_201to1000km"
                                        value="{{ old('w2000gm_201to1000km', $first2kg['201_to_1000_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w2000gm_1001to2000km">1001 to 2000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w2000gm_1001to2000km" name="w2000gm_1001to2000km"
                                        value="{{ old('w2000gm_1001to2000km', $first2kg['1001_to_2000_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w2000gm_above2000km">Above 2000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w2000gm_above2000km" name="w2000gm_above2000km"
                                        value="{{ old('w2000gm_above2000km', $first2kg['above_2000_kms'] ?? '') }}" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Additional 1 kg --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">Additional 500 g</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                @php
                                $additional1kg = $rates[1] ?? [];
                                @endphp

                                <div class="col-md-2 mb-3">
                                    <label for="w3000gm_local">Local <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w3000gm_local" name="w3000gm_local"
                                        value="{{ old('w3000gm_local', $additional1kg['Local'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w3000gm_200km">Upto 200 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w3000gm_200km" name="w3000gm_200km"
                                        value="{{ old('w3000gm_200km', $additional1kg['upto_200_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w3000gm_201to1000km">201 to 1000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w3000gm_201to1000km" name="w3000gm_201to1000km"
                                        value="{{ old('w3000gm_201to1000km', $additional1kg['201_to_1000_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w3000gm_1001to2000km">1001 to 2000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w3000gm_1001to2000km" name="w3000gm_1001to2000km"
                                        value="{{ old('w3000gm_1001to2000km', $additional1kg['1001_to_2000_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w3000gm_above2000km">Above 2000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w3000gm_above2000km" name="w3000gm_above2000km"
                                        value="{{ old('w3000gm_above2000km', $additional1kg['above_2000_kms'] ?? '') }}" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="card-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
            @endif



            @if (request()->query('type') == 4)
            <form action="{{ route('admin.gotogo-postal-rates.edit', ['type' => request()->query('type')]) }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                @csrf

                {{-- Upto 100gms --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">Upto 100gms</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                @php
                                $upto100gms = $rates[0] ?? [];
                                @endphp

                                <div class="col-md-2 mb-3">
                                    <label for="w100gm_local">Local <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w100gm_local" name="w100gm_local"
                                        value="{{ old('w100gm_local', $upto100gms['Local'] ?? '') }}" required />
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="w100gm_200km">Upto 200 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w100gm_200km" name="w100gm_200km"
                                        value="{{ old('w100gm_200km', $upto100gms['upto_200_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w100gm_1000km">201 - 1000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w100gm_1000km" name="w100gm_1000km"
                                        value="{{ old('w100gm_1000km', $upto100gms['201_to_1000_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w100gm_2000km">1001 - 2000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w100gm_2000km" name="w100gm_2000km"
                                        value="{{ old('w100gm_2000km', $upto100gms['1001_to_2000_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="w100gm_above2000">Above 2000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="w100gm_above2000" name="w100gm_above2000"
                                        value="{{ old('w100gm_above2000', $upto100gms['above_2000_kms'] ?? '') }}" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="card-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
            @endif



            @if (request()->query('type') == 9)
            <form action="{{ route('admin.gotogo-postal-rates.edit', ['type' => request()->query('type')]) }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                @csrf

                @php
                $first2pages = $rates[0] ?? [];
                $additionalPages = $rates[1] ?? [];
                @endphp

                {{-- First 2 Pages --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">First 2 Pages</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label for="first2pages_local">Local <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first2pages_local" name="first2pages_local"
                                        value="{{ old('first2pages_local', $first2pages['Local'] ?? '') }}" required />
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="first2pages_200km">Upto 200 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first2pages_200km" name="first2pages_200km"
                                        value="{{ old('first2pages_200km', $first2pages['upto_200_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="first2pages_1000km">201 - 1000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first2pages_1000km" name="first2pages_1000km"
                                        value="{{ old('first2pages_1000km', $first2pages['201_to_1000_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="first2pages_2000km">1001 - 2000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first2pages_2000km" name="first2pages_2000km"
                                        value="{{ old('first2pages_2000km', $first2pages['1001_to_2000_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="first2pages_above2000">Above 2000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first2pages_above2000" name="first2pages_above2000"
                                        value="{{ old('first2pages_above2000', $first2pages['above_2000_kms'] ?? '') }}" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Each Additional Page for 3 & Above --}}
                <div class="card-header">
                    <h5 class="card-title mb-0">Each Additional Page for 6 & Above</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label for="additional_page_local">Local <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="additional_page_local" name="additional_page_local"
                                        value="{{ old('additional_page_local', $additionalPages['Local'] ?? '') }}" required />
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="additional_page_200km">Upto 200 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="additional_page_200km" name="additional_page_200km"
                                        value="{{ old('additional_page_200km', $additionalPages['upto_200_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="additional_page_1000km">201 - 1000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="additional_page_1000km" name="additional_page_1000km"
                                        value="{{ old('additional_page_1000km', $additionalPages['201_to_1000_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="additional_page_2000km">1001 - 2000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="additional_page_2000km" name="additional_page_2000km"
                                        value="{{ old('additional_page_2000km', $additionalPages['1001_to_2000_kms'] ?? '') }}" required />
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="additional_page_above2000">Above 2000 km <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="additional_page_above2000" name="additional_page_above2000"
                                        value="{{ old('additional_page_above2000', $additionalPages['above_2000_kms'] ?? '') }}" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="card-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
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