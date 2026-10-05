@extends('admin.layouts.master') @section('title') Gotogo Postal Rates @endsection @section('content')
<div class="row">
   <div class="col-sm-12">
      <div class="card">
         @if (request()->query('type') == "1" )
         <form action="{{ route('admin.gotogo-postal-rates.store', ['type' => request()->query('type')]) }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
            @csrf 
            @php
            $weightRanges = [
            '0kg to 250gm',
            '250gm to 500gm',
            '500gm to 1kg',
            '1kg to 1.5kg',
            '1.5kg to 2kg',
            '2kg to 2.5kg',
            '2.5kg to 3kg',
            '3kg to 3.5kg',
            '3.5kg to 4kg',
            '4kg to 4.5kg',
            '4.5kg to 5kg'
            ];
            @endphp
            @foreach ($weightRanges as $range)
            @php
            $inputPrefix = str_replace([' ', 'kg'], '', str_replace('.', '_', $range)); 
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
                           value="{{ old('w'.$inputPrefix.'local') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w{{ $inputPrefix }}200km">Upto 200 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w'.$inputPrefix.'200km') is-invalid @enderror" 
                           id="w{{ $inputPrefix }}200km" 
                           name="w{{ $inputPrefix }}200km" 
                           placeholder="₹" 
                           value="{{ old('w'.$inputPrefix.'200km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w{{ $inputPrefix }}201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w'.$inputPrefix.'201to1000km') is-invalid @enderror" 
                           id="w{{ $inputPrefix }}201to1000km" 
                           name="w{{ $inputPrefix }}201to1000km" 
                           placeholder="₹" 
                           value="{{ old('w'.$inputPrefix.'201to1000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w{{ $inputPrefix }}1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w'.$inputPrefix.'1001to2000km') is-invalid @enderror" 
                           id="w{{ $inputPrefix }}1001to2000km" 
                           name="w{{ $inputPrefix }}1001to2000km" 
                           placeholder="₹" 
                           value="{{ old('w'.$inputPrefix.'1001to2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w{{ $inputPrefix }}Above2000km">Above 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w'.$inputPrefix.'Above2000km') is-invalid @enderror" 
                           id="w{{ $inputPrefix }}Above2000km" 
                           name="w{{ $inputPrefix }}Above2000km" 
                           placeholder="₹" 
                           value="{{ old('w'.$inputPrefix.'Above2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
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
         <form action="{{ route('admin.gotogo-postal-rates.store', ['type' => request()->query('type')]) }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
            @csrf
            {{-- First 2kg --}}
            <div class="card-header">
               <h5 class="card-title mb-0">First 2kg</h5>
            </div>
            <div class="card-body">
               <div class="row">
                  <div class="col-sm">
                     <div class="row">
                        <div class="col-md-2 mb-3">
                           <label for="w2000gmLocal">Local  <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w2000gmLocal') is-invalid @enderror" id="w2000gmLocal" name="w2000gmLocal" placeholder="₹" value="{{ old('w2000gmLocal') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w2000gm200km">Upto 200 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w2000gm200km') is-invalid @enderror" id="w2000gm200km" name="w2000gm200km" placeholder="₹" value="{{ old('w2000gm200km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w2000gm201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w2000gm201to1000km') is-invalid @enderror" id="w2000gm201to1000km" name="w2000gm201to1000km" placeholder="₹" value="{{ old('w2000gm201to1000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w2000gm1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w2000gm1001to2000km') is-invalid @enderror" id="w2000gm1001to2000km" name="w2000gm1001to2000km" placeholder="₹" value="{{ old('w2000gm1001to2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w2000gmAbove2000km">Above 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w2000gmAbove2000km') is-invalid @enderror" id="w2000gmAbove2000km" name="w2000gmAbove2000km" placeholder="₹" value="{{ old('w2000gmAbove2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            {{-- Additional 1kg --}}
            <div class="card-header">
               <h5 class="card-title mb-0">Additional 1kg</h5>
            </div>
            <div class="card-body">
               <div class="row">
                  <div class="col-sm">
                     <div class="row">
                        <div class="col-md-2 mb-3">
                           <label for="w3000gmLocal">Local  <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w3000gmLocal') is-invalid @enderror" id="w3000gmLocal" name="w3000gmLocal" placeholder="₹" value="{{ old('w3000gmLocal') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w3000gm200km">Upto 200 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w3000gm200km') is-invalid @enderror" id="w3000gm200km" name="w3000gm200km" placeholder="₹" value="{{ old('w3000gm200km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w3000gm201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w3000gm201to1000km') is-invalid @enderror" id="w3000gm201to1000km" name="w3000gm201to1000km" placeholder="₹" value="{{ old('w3000gm201to1000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w3000gm1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w3000gm1001to2000km') is-invalid @enderror" id="w3000gm1001to2000km" name="w3000gm1001to2000km" placeholder="₹" value="{{ old('w3000gm1001to2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w3000gmAbove2000km">Above 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w3000gmAbove2000km') is-invalid @enderror" id="w3000gmAbove2000km" name="w3000gmAbove2000km" placeholder="₹" value="{{ old('w3000gmAbove2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            {{-- Submit Button --}}
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
         @if (request()->query('type') == 4)
         <form action="{{ route('admin.gotogo-postal-rates.store', ['type' => request()->query('type')]) }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
            @csrf
            {{-- Upto 100gms --}}
            <div class="card-header">
               <h5 class="card-title mb-0">Upto 100gms</h5>
            </div>
            <div class="card-body">
               <div class="row">
                  <div class="col-sm">
                     <div class="row">
                        <div class="col-md-2 mb-3">
                           <label for="w100gmLocal">Local  <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w100gmLocal') is-invalid @enderror" id="w100gmLocal" name="w100gmLocal" placeholder="₹" value="{{ old('w100gmLocal') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w100gm200km">Upto 200 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w100gm200km') is-invalid @enderror" id="w100gm200km" name="w100gm200km" placeholder="₹" value="{{ old('w100gm200km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w100gm201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w100gm201to1000km') is-invalid @enderror" id="w100gm201to1000km" name="w100gm201to1000km" placeholder="₹" value="{{ old('w100gm201to1000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w100gm1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w100gm1001to2000km') is-invalid @enderror" id="w100gm1001to2000km" name="w100gm1001to2000km" placeholder="₹" value="{{ old('w100gm1001to2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="w100gmAbove2000km">Above 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('w100gmAbove2000km') is-invalid @enderror" id="w100gmAbove2000km" name="w100gmAbove2000km" placeholder="₹" value="{{ old('w100gmAbove2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            {{-- Submit Button --}}
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
         @if (request()->query('type') == 9)
         <form action="{{ route('admin.gotogo-postal-rates.store', ['type' => request()->query('type')]) }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
            @csrf
            {{-- First 2 Pages --}}
            <div class="card-header">
               <h5 class="card-title mb-0">First 5 Pages</h5>
            </div>
            <div class="card-body">
               <div class="row">
                  <div class="col-sm">
                     <div class="row">
                        <div class="col-md-2 mb-3">
                           <label for="first2pages_local">Local  <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('first2pages_local') is-invalid @enderror" id="first2pages_local" name="first2pages_local" placeholder="₹" value="{{ old('first2pages_local') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="first2pages_200km">Upto 200 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('first2pages_200km') is-invalid @enderror" id="first2pages_200km" name="first2pages_200km" placeholder="₹" value="{{ old('first2pages_200km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="first2pages_201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('first2pages_201to1000km') is-invalid @enderror" id="first2pages_201to1000km" name="first2pages_201to1000km" placeholder="₹" value="{{ old('first2pages_201to1000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="first2pages_1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('first2pages_1001to2000km') is-invalid @enderror" id="first2pages_1001to2000km" name="first2pages_1001to2000km" placeholder="₹" value="{{ old('first2pages_1001to2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="first2pages_above2000km">Above 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('first2pages_above2000km') is-invalid @enderror" id="first2pages_above2000km" name="first2pages_above2000km" placeholder="₹" value="{{ old('first2pages_above2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
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
                           <label for="additional_page_local">Local  <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('additional_page_local') is-invalid @enderror" id="additional_page_local" name="additional_page_local" placeholder="₹" value="{{ old('additional_page_local') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="additional_page_200km">Upto 200 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('additional_page_200km') is-invalid @enderror" id="additional_page_200km" name="additional_page_200km" placeholder="₹" value="{{ old('additional_page_200km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="additional_page_201to1000km">201 - 1000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('additional_page_201to1000km') is-invalid @enderror" id="additional_page_201to1000km" name="additional_page_201to1000km" placeholder="₹" value="{{ old('additional_page_201to1000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="additional_page_1001to2000km">1001 - 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('additional_page_1001to2000km') is-invalid @enderror" id="additional_page_1001to2000km" name="additional_page_1001to2000km" placeholder="₹" value="{{ old('additional_page_1001to2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                        <div class="col-md-2 mb-3">
                           <label for="additional_page_above2000km">Above 2000 Kms <span class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('additional_page_above2000km') is-invalid @enderror" id="additional_page_above2000km" name="additional_page_above2000km" placeholder="₹" value="{{ old('additional_page_above2000km') }}" required />
                           <div class="invalid-feedback">Please Enter Postal Rate</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            {{-- Submit Button --}}
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
   </div>
</div>
@endsection