@extends('pph.layouts.master')

@section('title') Commission Detail @endsection

@section('content')

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <button id="print" style="margin-right:10px;" class="btn add-btn">Print</button>
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

    .pagination {
        display: none;
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

    .table-newdatatable {
        margin: 0 !important;
    }
</style>

<form action="{{ route('pph.commission.index', ['id' => request()->route('id')]) }}" method="GET">
    <div class="row">
        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input class="form-control floating datetimepicker start_date"
                        value="{{ request()->query('start_date') }}"
                        type="text" name="start_date">
                </div>
                <label class="focus-label">Start Date</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input class="form-control floating datetimepicker end_date"
                        value="{{ request()->query('end_date') }}"
                        type="text" name="end_date">
                </div>
                <label class="focus-label">End Date</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-1">
            <div class="d-grid d-flex">
                <button type="submit" class="btn btn-success">Search</button> &nbsp;&nbsp;

                @if(request()->query('start_date') || request()->query('end_date'))
                <a href="{{ route('pph.commission.index', ['id' => request()->route('id')]) }}"
                    class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Reset">
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
                    <li><strong>PPH No:</strong> {{ $franchiseDetails->pph_no }}</li>
                    <li><strong>Name:</strong> {{ $franchiseDetails->name }}</li>
                    <li><strong>Pincode:</strong> {{ $franchiseDetails->pincode }}</li>
                    <li><strong>Mobile:</strong> {{ $franchiseDetails->mobile }}</li>
                </ul>

                {{-- Single Table with Gotogo & India Post Data --}}
                <div class="table-responsive table-newdatatable">
                    <table class="table table-new custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Service Type</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Gotogo Data --}}
                            <tr class="table-primary">
                                <td colspan="5" class="text-center"><strong>Gotogo Commission</strong></td>
                            </tr>
                            @php
                            $totalGotogoCommission = 0;
                            @endphp
                            @foreach($data as $index => $value)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y') }}</td>
                                <td class="text-center">{{ $value->service_type }}</td>
                                <td class="text-center">{{ number_format($value->total_amount, 2) }}</td>
                                <td class="text-center">{{ number_format($value->total_commission, 2) }}</td>
                            </tr>
                            @php
                            $totalGotogoCommission += $value->total_commission;
                            @endphp
                            @endforeach

                            {{-- Grand Total Row --}}
                            <tr class="table-success">
                                <td colspan="4" class="text-right"><strong>Grand Total:</strong></td>
                                <td class="text-center"><strong>{{ number_format($totalGotogoCommission, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>











@push('page-javascript')


<script>
    $('#print').on('click', function() {

        let start_date = $('.start_date').val();
        let end_date = $('.end_date').val();
        $.ajax({
            url: "{{ route('pph.commission.printCommissionDetail') }}",
            method: 'GET',
            data: {
                start_date: start_date,
                end_date: end_date
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
    });
</script>


@endpush

@endsection