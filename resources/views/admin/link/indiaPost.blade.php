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
                    <table class="table table-bordered custom-table datatable table-hover">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>City</th>
                                <th>Business Associate Name</th>
                                <th>Business Associate No</th>
                                <th>Business Associate Pincode</th>
                                <th>BNPL No</th>
                                <th>Customer ID</th>
                                <th>Contract ID</th>             
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($link as $index => $item)
                            <tr>
                                <td class="text-start">{{ $index + 1 }}</td>
                                <td class="text-start">{{ $item->city ?? '-' }}</td>
                                <td class="text-start">{{ data_get($item, 'franchise.name', '-') }}</td>
                                <td class="text-start">{{ $item->franchise_no ?? '-' }}</td>
                                <td class="text-start">{{ data_get($item, 'franchise.pincode', '-') }}</td>
                                <td class="text-start">{{ $item->bnpl_no ?? '-' }}</td>
                                <td class="text-start">{{ $item->customer_id ?? '-' }}</td>
                                <td class="text-start">{{ $item->contract_id ?? '-' }}</td>
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