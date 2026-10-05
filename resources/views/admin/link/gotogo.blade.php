@extends('admin.layouts.master')
@section('title') Link @endsection
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
                                <th>Business Associate Name</th>
                                <th>Business Associate No</th>
                                <th>Business Associate Pincode</th>
                                
                                <th>CMS Name</th>
                                <th>CMS No</th>
                                <th>CMS Pincode</th>
                                
                                <th>PPH Name</th>
                                <th>PPH No</th>
                                <th>PPH Pincode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($link as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-start">{{ data_get($item, 'franchise.name', '-') }}</td>
                                <td class="text-start">{{ $item->franchise_no ?? '-' }}</td>
                                <td class="text-start">{{ data_get($item, 'franchise.pincode', '-') }}</td>

                                <td class="text-start">{{ data_get($item, 'cms.name', '-') }}</td>
                                <td class="text-start">{{ $item->cms_no ?? '-' }}</td>
                                <td class="text-start">{{ data_get($item, 'cms.pincode', '-') }}</td>

                                <td class="text-start">{{ data_get($item, 'pph.name', '-') }}</td>
                                <td class="text-start">{{ $item->pph_no ?? '-' }}</td>
                                <td class="text-start">{{ data_get($item, 'pph.pincode', '-') }}</td>  
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