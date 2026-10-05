@extends('admin.layouts.master')

@section('title') Commission Management @endsection

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
    body {
        font-family: Arial, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 4px;
        text-align: center;
    }

    th {
        background-color: #e4e4e4;
    }

    caption {
        caption-side: top;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .add-btn {
        background-color: #ff9b44;
        border: 1px solid #ff9b44;
        color: #ffffff;
        float: right;
        font-weight: 500;
        min-width: 74px;
        border-radius: 50px;
        padding: 3px;
    }
</style>



<div class="row">

    <div class="col-md-12">

        <div class="table-responsive">
            <div class="container-fluid">
                <caption>{{$title}}</caption>
                <div class="row">
                    <div class="col-md-4 table-container">
                        <table class="table-custom w-100">
                            <thead>
                                <tr>
                                    <th colspan="2">Speed Packet
                                        @if ($speedrates->count()>0)
                                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#edit_speed"><i class="fa-solid fa-pen"></i> Edit</a>
                                        @else
                                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_speed"><i class="fa-solid fa-plus"></i> Add</a>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <td>Weight Slabs</td>
                                    <td>Amount</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($speedrates as $rates)
                                <tr>
                                    <td>{{$rates->weight}}</td>
                                    <td>{{$rates->amount}}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4 table-container">
                        <table class="table-custom w-100">
                            <thead>
                                <tr>
                                    <th colspan="2">Business Package
                                        @if ($businessrates->count()>0)
                                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#edit_business"><i class="fa-solid fa-pen"></i> Edit</a>
                                        @else
                                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_business"><i class="fa-solid fa-plus"></i> Add</a>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <td>Weight Slabs</td>
                                    <td>Amount</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($businessrates as $rates)
                                <tr>
                                    <td>{{$rates->weight}}</td>
                                    <td>{{$rates->amount}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <table class="table-custom w-100">
                            <thead>
                                <tr>
                                    <th colspan="2">Legal Document Delivery
                                        @if ($legalrates->count()>0)
                                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#edit_legal"><i class="fa-solid fa-pen"></i> Edit</a>
                                        @else
                                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_legal"><i class="fa-solid fa-plus"></i> Add</a>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <td>Weight Slabs</td>
                                    <td>Amount</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($legalrates as $rates)
                                <tr>
                                    <td>{{$rates->weight}}</td>
                                    <td>{{$rates->amount}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4 table-container">
                        <table class="table-custom w-100">
                            <thead>
                                <tr>
                                    <th colspan="2">E2H
                                        @if ($e2hrates->count()>0)
                                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#edit_e2h"><i class="fa-solid fa-pen"></i> Edit</a>
                                        @else
                                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_e2h"><i class="fa-solid fa-plus"></i> Add</a>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <td>Digital</td>
                                    <td>Amount</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($e2hrates as $rates)
                                <tr>
                                    <td>{{$rates->weight}}</td>
                                    <td>{{$rates->amount}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Add speed Modal -->
<div id="add_speed" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Add Amount For Speed Packet</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form action="{{route('admin.commission.store',['membertype'=>request()->query('membertype'),'serviceType'=>1])}}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="row">

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Upto 250gm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w250gm') is-invalid @enderror" id="w250gm" name="w250gm" placeholder="₹" value="{{ old('w250gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">.25kg to .5kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w250gm500gm') is-invalid @enderror" id="w250gm500gm" name="w250gm500gm" placeholder="₹" value="{{ old('w250gm500gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">500gm to 1kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w500gm1000gm') is-invalid @enderror" id="w500gm1000gm" name="w500gm1000gm" placeholder="₹" value="{{ old('w500gm1000gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">1kg to 1.5kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w1000gm1500gm') is-invalid @enderror" id="w1000gm1500gm" name="w1000gm1500gm" placeholder="₹" value="{{ old('w1000gm1500gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">1.5kg to 2kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w1500gm2000gm') is-invalid @enderror" id="w1500gm2000gm" name="w1500gm2000gm" placeholder="₹" value="{{ old('w1500gm2000gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">2kg to 2.5kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w2000gm2500gm') is-invalid @enderror" id="w2000gm2500gm" name="w2000gm2500gm" placeholder="₹" value="{{ old('w2000gm2500gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">2.5kg to 3kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w2500gm3000gm') is-invalid @enderror" id="w2500gm3000gm" name="w2500gm3000gm" placeholder="₹" value="{{ old('w2500gm3000gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">3kg to 3.5kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w3000gm3500gm') is-invalid @enderror" id="w3000gm3500gm" name="w3000gm3500gm" placeholder="₹" value="{{ old('w3000gm3500gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">3.5kg to 4kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w3500gm4000gm') is-invalid @enderror" id="w3500gm4000gm" name="w3500gm4000gm" placeholder="₹" value="{{ old('w3500gm4000gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">4kg to 4.5kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w4000gm4500gm') is-invalid @enderror" id="w4000gm4500gm" name="w4000gm4500gm" placeholder="₹" value="{{ old('w4000gm4500gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">4.5kg to 5kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w4500gm5000gm') is-invalid @enderror" id="w4500gm5000gm" name="w4500gm5000gm" placeholder="₹" value="{{ old('w4500gm5000gm') }}" required>
                            </div>
                        </div>

                    </div>

                    <div class="submit-section">

                        <button class="btn btn-primary submit-btn">Submit</button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
<!-- /Add speed Modal -->

<!-- Add business Modal -->
<div id="add_business" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Add Amount For Business Packet</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <form action="{{route('admin.commission.store',['membertype'=>request()->query('membertype'),'serviceType'=>3])}}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="row">

                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">First 2 KG <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w2000gm') is-invalid @enderror" id="w2000gm" name="w2000gm" placeholder="₹" value="{{ old('w2000gm') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Additional 1kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w2000gm3000gm') is-invalid @enderror" id="w2000gm3000gm" name="w2000gm3000gm" placeholder="₹" value="{{ old('w2000gm3000gm') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">

                        <button class="btn btn-primary submit-btn">Submit</button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
<!-- /Add business Modal -->


<!-- Add legal Modal -->
<div id="add_legal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Add Amount For Legal Document Delivery</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <form action="{{route('admin.commission.store',['membertype'=>request()->query('membertype'),'serviceType'=>4])}}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="row">

                        <div class="col-md-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Upto 100gms <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w100gm') is-invalid @enderror" id="w100gm" name="w100gm" placeholder="₹" value="{{ old('w100gm') }}" required>
                            </div>
                        </div>

                    </div>

                    <div class="submit-section">

                        <button class="btn btn-primary submit-btn">Submit</button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
<!-- /Add legal Modal -->

<!-- Add add_e2h Modal -->
<div id="add_e2h" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered " role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Add Amount For E2H</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <form action="{{ route('admin.commission.store', ['membertype' => request()->query('membertype'), 'serviceType' => 9]) }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="row">

                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">First 5 Pages <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first2pages') is-invalid @enderror" id="first2pages" name="first2pages" placeholder="₹" value="{{ old('first2pages') }}" required>
                                <div class="invalid-feedback">Please enter the rate for the first 5 pages.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Each Additional Pages <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('additional_pages') is-invalid @enderror" id="additional_pages" name="additional_pages" placeholder="₹" value="{{ old('additional_pages') }}" required>
                                <div class="invalid-feedback">Please enter the rate for additional pages.</div>
                            </div>
                        </div>

                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
<!-- /Add add_e2h Modal -->



<!-- edit speed Modal -->
@if ($speedrates->count()>0)

<div id="edit_speed" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Edit Amount For Speed Packet</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form action="{{ route('admin.commission.edit', ['membertype' => request()->query('membertype'), 'serviceType' => 1]) }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="row">
                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Upto 250gm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w250gm') is-invalid @enderror" id="w250gm" name="w250gm" placeholder="₹" value="{{ old('w250gm', $speedrates[0]['amount']) }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">.25kg to .5kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w250gm500gm') is-invalid @enderror" id="w250gm500gm" name="w250gm500gm" placeholder="₹" value="{{ old('w250gm500gm', $speedrates[1]['amount']) }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-block mb-3">
                                <label class="col-form-label">500gm to 5kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w500gm1000gm') is-invalid @enderror" id="w500gm1000gm" name="w500gm1000gm" placeholder="₹" value="{{ old('w500gm1000gm', $speedrates[2]['amount']) }}" required>
                            </div>
                        </div>

                      
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>


            </div>

        </div>

    </div>

</div>
@endif
<!-- /edit speed Modal -->


<!-- edit  business Modal -->
@if ($businessrates->count()>0)
<div id="edit_business" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Edit Amount For Business Packet</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <form action="{{ route('admin.commission.edit', ['membertype' => request()->query('membertype'), 'serviceType' => 3]) }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">First 2 KG <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w2000gm') is-invalid @enderror" id="w2000gm" name="w2000gm" placeholder="₹" value="{{ old('w2000gm', $businessrates[0]['amount']) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Additional 1kg <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w2000gm3000gm') is-invalid @enderror" id="w2000gm3000gm" name="w2000gm3000gm" placeholder="₹" value="{{ old('w2000gm3000gm', $businessrates[1]['amount']) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>

                </form>


            </div>

        </div>

    </div>

</div>
@endif
<!-- /edit  business Modal -->


<!-- edit legal Modal -->
@if ($legalrates->count()>0)
<div id="edit_legal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Edit Amount For Legal Document Delivery</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <form action="{{ route('admin.commission.edit', ['membertype' => request()->query('membertype'), 'serviceType' => 4]) }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Upto 100gms <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('w100gm') is-invalid @enderror" id="w100gm" name="w100gm" placeholder="₹" value="{{ old('w100gm', $legalrates[0]['amount']) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>

                </form>


            </div>

        </div>
    </div>
</div>
@endif
<!-- /edit  legal Modal -->


<!-- edit legal Modal -->
@if ($e2hrates->count()>0)
<div id="edit_e2h" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit E2H Rates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('admin.commission.edit', ['membertype' => request()->query('membertype'), 'serviceType' => 9]) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">First 5 Pages <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first2pages') is-invalid @enderror" id="first2pages" name="first2pages" placeholder="₹" value="{{ old('first2pages', $e2hrates[0]['amount'] ?? '') }}" required>
                                <div class="invalid-feedback">Please enter a valid amount.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Additional Pages <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('additional_pages') is-invalid @enderror" id="additional_pages" name="additional_pages" placeholder="₹" value="{{ old('additional_pages', $e2hrates[1]['amount'] ?? '') }}" required>
                                <div class="invalid-feedback">Please enter a valid amount.</div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Update</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endif
<!-- /edit  legal Modal -->

















<!-- Delete  Modal -->

<div class="modal custom-modal fade" id="delete_modal" role="dialog">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-body">

                <div class="form-header">

                    <h3>Delete</h3>

                    <p>Are you sure want to delete?</p>

                </div>

                <div class="modal-btn delete-action">

                    <div class="row">

                        <div class="col-6">

                            <a href="" id="delete_button" class="btn btn-primary continue-btn">Delete</a>

                        </div>

                        <div class="col-6">

                            <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- /Delete  Modal -->



@push('page-javascript')



@endpush

@endsection