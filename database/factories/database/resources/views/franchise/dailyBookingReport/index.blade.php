@extends('franchise.layouts.master')
@section('title') Daily Booking Report @endsection
@section('content')

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_support_tickets"><i class="fa-solid fa-plus"></i> Upload</a>
    <button id="print" style="margin-right:10px;" class="btn add-btn">Print</button>
    <div class="view-icons">
        <a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
        <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa-solid fa-bars"></i></a>
    </div>
</div>
@endpush



<!-- Search Filter -->
<div class="row filter-row">
    <div class="col-sm-6 col-md-3">
        <div class="input-block mb-3 form-focus">
            <input type="text" class="form-control floating input1">
            <label class="focus-label">Pin Code</label>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="input-block mb-3 form-focus">
            <input type="text" class="form-control floating input2">
            <label class="focus-label">Name</label>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="d-grid">
            <a href="#" class="btn btn-success search"> Search </a>
        </div>
    </div>
</div>
<!-- Search Filter -->


<div class="row">
    <div class="col-md-12">
        <div class="table-responsive table-newdatatable">
            <table class="table table-new custom-table mb-0 datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Service</th>
                        <th>No of Articles</th>
                        <th>Total Value</th>
                        <th>Wallet Amount</th>
                        <th>Wallet Balalnce</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($data as $key=>$value)
                    <tr>
                        <td>{{$key}}</td>
                        <td>{{$value['service_type']}}</td>
                        <td>{{$value['No_of_arcticle']}}</td>
                        <td>{{$value['total_value']}}</td>
                        <td>{{$value['wallet_balance']}}</td>
                        <td>{{$value['remaining_balance']}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>





@push('page-javascript')



@endpush
@endsection