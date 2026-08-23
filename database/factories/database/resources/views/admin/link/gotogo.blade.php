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
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th>Franchise Name</th>
                                <th>Franchise No</th>
                                <th>Franchise Pincode</th>
                 
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
                                <td>{{ $item['franchise']['name'] }}</td>
                                <td>{{ $item['franchise_no'] }}</td>
                                <td>{{ $item['franchise']['pincode'] }}</td>
                  
                                <td>{{ $item['cms']['name'] }}</td>
                                <td>{{ $item['cms_no'] }}</td>
                                <td>{{ $item['cms']['pincode'] }}</td>
                        
                                <td>{{ $item['pph']['name'] }}</td>
                                <td>{{ $item['pph_no'] }}</td>
                                <td>{{ $item['pph']['pincode'] }}</td>
                               
                                
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