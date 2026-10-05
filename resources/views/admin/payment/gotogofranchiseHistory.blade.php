@extends('admin.layouts.master')
@section('title') Gotogo Payment Report @endsection
@section('content')
@push('add-modal-code')
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

    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    } 

    .table-newdatatable .dataTables_length {
        display: block;
    }

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
<form action="{{ route('admin.franchise.gotogo.paymentHistory', ['membertype' => 'franchise']) }}" method="GET">
    <div class="row">
        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <!-- Add 'name' attribute to input field -->
                    <input class="form-control floating datetimepicker start_date" value="{{ request()->get('start_date') }}" type="text" name="start_date" />
                </div>
                <label class="focus-label">Start Date</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <!-- Add 'name' attribute to input field -->
                    <input class="form-control floating datetimepicker end_date" value="{{ request()->get('end_date') }}" type="text" name="end_date" />
                </div>
                <label class="focus-label">End Date</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-1">
            <div class="d-grid d-flex">
                <button type="submit" class="btn btn-success">Search</button> &nbsp;&nbsp;
                <a href="{{ route('admin.franchise.paymentHistory', ['membertype' => 'franchise']) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-original-title="Reset">
                    <i class="fa-regular fa-trash-can m-r-5 mt-2"></i>
                </a>
            </div>
        </div>
        <div class="col-3 text-end">
           <h4><b>Total Amount: <span style="color:#fc6075">{{$total}}</b></span></h4>
       </div>
   </div>
</form>
</div>

<div class="row">
    <div class="col-md-12">        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive table-newdatatable" id="franchise_daily_booking_report">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Transaction Id</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Invoice</th>
                            </tr>
                        </thead>

                        @foreach($paymentHistory as $index => $value)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $value->franchise->name }}</td>
                            <td>{{ $value->franchise->email }}</td>
                            <td>{{ $value['amount'] }}</td>
                            <td>{{ $value['razorpay_payment_id'] }}</td>
                            <td>{{ $value['status'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y') }}</td>
                            <td>
                               <button  onClick="print({{$value->id}})" style="margin-right:10px;" class="btn add-btn">Print</button>
                           </td>
                       </tr>
                       @endforeach
                   </table>
               </div>
           </div>
       </div>
   </div>
</div>











@push('page-javascript')

<script>
    function print(id){
        $.ajax({
            url: "{{ route('admin.franchise.printpaymentHistory') }}",
            method: 'GET',
            data: {
                id: id,
            },
            success: function(response) {
                var iframe = document.createElement('iframe');
                iframe.style.position = 'absolute';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = 'none';
                document.body.appendChild(iframe);

                iframe.contentDocument.open();
                iframe.contentDocument.write(response.otherPageContent);
                iframe.contentDocument.close();

                iframe.contentWindow.focus();
                iframe.contentWindow.print();

                document.body.removeChild(iframe);
            }
        });
    }
</script>


@endpush

@endsection