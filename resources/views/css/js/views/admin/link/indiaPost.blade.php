@extends('admin.layouts.master')
@section('title') India Post Link @endsection
@section('content')

<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
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
                                <th>City</th>
                                <th>Franchise Name</th>
                                <th>Franchise No</th>
                                <th>Franchise Pincode</th>
                                <th>BNPL No</th>
                                <th>Customer ID</th>
                                <th>Contract ID</th>
                       
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($link as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item['city'] }}</td>
                                    <td>{{ $item['franchise']['name'] }}</td>
                                    <td>{{ $item['franchise_no'] }}</td>
                                    <td>{{ $item['franchise']['pincode'] }}</td>
                                    <td>{{ $item['bnpl_no'] }}</td>
                                    <td>{{ $item['customer_id'] }}</td>
                                    <td>{{ $item['contract_id'] }}</td>
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