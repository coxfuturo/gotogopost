@extends('admin.layouts.master')

@section('title') Security Detail @endsection

@section('content')

@push('add-modal-code')

<div class="col-auto float-end ms-auto">
    <button id="priceModalBtn" style="margin-right:10px;" class="btn add-btn">Add Security</button>
</div>
@endpush


@push('add-modal-code')


@endpush

<style>
    .custom-button {
        display: inline-block;
        padding: 3px 10px;
        font-size: 13px;
        font-weight: 500;
        text-align: center;
        text-decoration: none;
        color: #f9f9f9 ! IMPORTANT;
        background-color: #1b64b1;
        border-radius: 4px;
        border: 1px solid transparent;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    }

    .custom-button:hover {
        background-color: #0056b3;
        /* Darker shade on hover */
        border-color: #004085;
        color: #fff;
    }

    /* div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    } */

    /* .table-newdatatable .dataTables_length {
        display: block;
    } */

    table.table-new.dataTable>thead .sorting:after,
    table.table-new.dataTable>thead .sorting_asc:after,
    table.table-new.dataTable>thead .sorting_desc:after,
    table.table-new.dataTable>thead .sorting_asc_disabled:after,
    table.table-new.dataTable>thead .sorting_desc_disabled:after {
        right: 0.5em;
        content: "\f0d7";
        font-family: "FontAwesome";
        top: 15px;
        color: #C1CCDB;
        font-size: 12px;
        opacity: 1;
    }

    table.table-new.dataTable>thead .sorting:before,
    table.table-new.dataTable>thead .sorting_asc:before,
    table.table-new.dataTable>thead .sorting_desc:before,
    table.table-new.dataTable>thead .sorting_asc_disabled:before,
    table.table-new.dataTable>thead .sorting_desc_disabled:before {
        right: 0.5em;
        content: "\f0d8";
        font-family: "FontAwesome";
        top: 5px;
        color: #C1CCDB;
        font-size: 12px;
        opacity: 1;
    }

    .table-newdatatable{
        margin: 0 !important;
    }
</style>
<form action="{{ route('admin.franchise.securityDetails', ['id' => request()->route('id')]) }}" method="GET">
    <div class="row">
        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <!-- Add 'name' attribute to input field -->
                    <input   class="form-control floating datetimepicker start_date" type="text" name="start_date">
                </div>
                <label class="focus-label">Start Date</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <!-- Add 'name' attribute to input field -->
                    <input class="form-control floating datetimepicker end_date" type="text" name="end_date">
                </div>
                <label class="focus-label">End Date</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-1">
            <div class="d-grid d-flex">
                <button type="submit" class="btn btn-success">Search</button> &nbsp;&nbsp;

                @if(request()->query('start_date') || request()->query('end_date'))
                <a href="{{ route('admin.franchise.securityDetails', ['id' => request()->route('id')]) }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Reset">
                    <i class="fa-regular fa-trash-can m-r-5 align-middle"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
</form>



<div class="row">
    <div class="col-md-12">

        
        <div class="card">
            <div class="card-body">
                <ul class="list-unstyled d-flex justify-content-between" style="flex-wrap:wrap">
                    <li><strong>Franchise No:</strong> {{ $franchiseDetails->franchise_no }}</li>
                    <li><strong>Name:</strong> {{ $franchiseDetails->name }}</li>
                    <li><strong>Pincode:</strong> {{ $franchiseDetails->pincode }}</li>
            
                </ul>
                <ul class="list-unstyled d-flex justify-content-between" style="flex-wrap:wrap">
                    <li><strong>Mobile:</strong> {{ $franchiseDetails->mobile }}</li>
                    <li><strong>IndiaPost Balance:</strong> {{ $franchiseDetails->indiapost_balance }}</li>
                    <li><strong>GOTOGO Balance:</strong> {{ $franchiseDetails->gotogo_balance  }}</li>
                </ul>
                <div class="table-responsive table-newdatatable" id="franchise_daily_booking_report">
                    <table class="table table-new custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Payment Type</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $value)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y') }}</td>
                                <td>{{ $value['type'] }}</td>
                                <td>{{ $value['amount'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    
                </div>
            </div>
        </div>
       
    </div>
</div>


<!-- Price Modal -->
<div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="priceModalLabel">Add Credit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.franchise.securityDetails', ['id' => request()->route('id')]) }}" method = "Post">
                    @csrf
                     <div class="mb-3">
                        <label for="select" class="form-label">Select Credit Type</label><br>
                        <select name="type" class="form-form" style="width:100%">
                            <option value="gotogo">Gotogo Credit</option>
                            <option value="indiaPost">India Post Credit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Credit</label>
                        <input type="text" class="form-control" name="amount" id="price" placeholder="Enter amount">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>










@push('page-javascript')
<script>
    $(document).ready(function(){
        $("#priceModalBtn").click(function(){
            $("#priceModal").modal('show'); // Open modal on button click
        });
    });
</script>




@endpush

@endsection