@extends('admin.layouts.master')
@section('title') India Post Commission Management @endsection
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
                <div class="row">
                    <h4 colspan="1"> Commission for booking of inland speed post articals (Document & Parcel)</h4>
                    <div class="col-md-4 table-container">
                        <table class="table-custom w-100">
                            <thead>
                                <tr>
                                    <th colspan="2">Speed Packet 
                                        @if ($india_commission->count()>0)
                                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#edit_speed">Edit</a>
                                        @else
                                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_speed">Add</a> 
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <td>Monthly Revenue</td>
                                    <td>Commission</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($india_commission as $rates)
                                <tr>
                                    <td>{{$rates->india_post_monthly_revenue}}</td>
                                    <td>{{$rates->india_post_commission}}%</td>
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
                <form action="{{route('admin.india-post-commission.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">Up To RS. 2,00,000/-  <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('upto_2_lakh') is-invalid @enderror" id="upto_2_lakh" name="upto_2_lakh" placeholder="Up To RS. 2,00,000" value="{{ old('upto_2_lakh') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">RS. 2,00,001 to 5,00,000/- <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('between_2_to_5_lakh') is-invalid @enderror" id="between_2_to_5_lakh" name="between_2_to_5_lakh" placeholder="RS. 2,00,001 to 5,00,000" value="{{ old('between_2_to_5_lakh') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">RS. 5,00,001 to 10,00,000/-<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('between_5_to_10_lakh') is-invalid @enderror" id="between_5_to_10_lakh" name="between_5_to_10_lakh" placeholder="RS. 5,00,001 to 10,00,000" value="{{ old('between_5_to_10_lakh') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">RS. 10,00,001 to And Above <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('between_10_to_25_lakh_and_above') is-invalid @enderror" id="between_10_to_25_lakh_and_above" name="between_10_to_25_lakh_and_above" placeholder="RS. 10,00,001 to and above" value="{{ old('between_10_to_25_lakh_and_above') }}" required>
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
@if ($india_commission->count()>0)
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
                <form action="{{ route('admin.india-post-commission.edit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">Up To RS. 2,00,000/-  <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('upto_2_lakh') is-invalid @enderror" id="upto_2_lakh" name="upto_2_lakh" placeholder="Up To RS. 2,00,000" value="{{ old('upto_2_lakh', $india_commission[0]['india_post_commission']) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">RS. 2,00,001 to 5,00,000/- <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('between_2_to_5_lakh') is-invalid @enderror" id="between_2_to_5_lakh" name="between_2_to_5_lakh" placeholder="RS. 2,00,001 to 5,00,000" value="{{ old('between_2_to_5_lakh', $india_commission[1]['india_post_commission']) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">RS. 5,00,001 to 10,00,000/-<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('between_5_to_10_lakh') is-invalid @enderror" id="between_5_to_10_lakh" name="between_5_to_10_lakh" placeholder="RS. 5,00,001 to 10,00,000" value="{{ old('between_5_to_10_lakh', $india_commission[2]['india_post_commission']) }}" required>
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
<div class="row">
    <div class="col-sm-6">
        <h4 style="border:1px solid #e4e4e4;padding: 7px;font-size:16px;margin-left:10px">1. If the amount is less than ₹35, a commission of ₹3 will be given.</h4>
        <h4 style="border:1px solid #e4e4e4;padding: 7px;font-size:16px;margin-left:10px">
        2. If the amount is ₹35 or more, a commission of ₹5 per parcel will be given.</h4>
    </div>
</div>
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
@push('page-javascript')
@endpush
@endsection