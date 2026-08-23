@extends('admin.layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }

    .table-newdatatable .dataTables_length {
        display: block;
    }

    .table {
        margin-bottom: 0px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th>Name</th>
                                <th>Services & Barcodes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($franchises as $i => $franchise)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $franchise['name'] }}</td>
                                <td>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Service</th>
                                                <th>Barcode Range</th>
                                                <th>Allotted</th>
                                                <th>Available</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($franchise['services'] as $service)
                                            <tr>
                                                <td>{{ $service['service_name'] }}</td>
                                                <td>{{ $service['barcode_range'] }}</td>
                                                <td>{{ $service['allotted'] }}</td>
                                                <td>{{ $service['available'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-javascript')

@endpush
@endsection