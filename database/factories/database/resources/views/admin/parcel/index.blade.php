@extends('admin.layouts.master')
@section('title') Parcel Management @endsection
@section('content')

@push('add-modal-code')
{{-- <div class="col-auto float-end ms-auto">
    <a href="{{route('admin.parcel.create')}}" class="btn add-btn"><i class="fa-solid fa-plus"></i> Add</a>
    <div class="view-icons">
        <a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
        <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa-solid fa-bars"></i></a>
    </div>
</div> --}}
@endpush


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th class="text-center">Pickup Name</th>
                                <th class="text-center">Pickup Pincode</th>
                                <th class="text-center">Consignee Name</th>
                                <th class="text-center">Consignee Pincode</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- {{dd($datas)}} --}}
                            @foreach($datas as $i => $data)
                            <tr>
                                <td>{{$i+1}}</td>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="#">{{$data->pickup_name}}</a>
                                    </h2>
                                </td>
                                <td>{{$data->pickup_pincode}}</td>
                                <td>{{$data->consignee_name}}</td>
                                <td>{{$data->consignee_pincode}}</td>

                                
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{route('admin.parcel.edit',$data->id)}}"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item" href="{{route('admin.parcel.view',$data->id)}}"><i class="fa-regular fa-eye m-r-5"></i> view</a>
                                            <a class="dropdown-item" href="#" onclick="delete_modal('{{$data->id}}')"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>  
                                        </div>
                                    </div>
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


<!-- Delete  Modal -->
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
<!-- /Delete  Modal -->

@push('page-javascript')
<script type="text/javascript">
    function delete_modal(id) {
        const deleteUrl = "{{ route('admin.parcel.delete', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }

</script>
@endpush
@endsection
