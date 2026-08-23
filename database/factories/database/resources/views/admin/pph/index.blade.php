@extends('admin.layouts.master')

@section('title') PPH Management @endsection

@section('content')

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <a href="{{route('admin.pph.create')}}" class="btn add-btn"><i class="fa-solid fa-plus"></i> Add</a>

</div>
@endpush


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
                                <th class="text-center">Name</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Created at</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $i => $data)
                            <tr>
                                <td>{{$i+1}}</td>
                                <td>
                                    <h2 class="table-avatar">
                                        @php
                                            $photos = isset($data->kyc->photo) ? explode(',', $data->kyc->photo) : [];
                                            $firstPhoto = !empty($photos[0]) ? asset('admin/pph/' . $data->generated_id . '/' . trim($photos[0])) : asset('admin/assets/img/profiles/avatar-02.jpg');
                                        @endphp
                                        <a href="#" class="avatar"><img src="{{$firstPhoto }}" alt=""></a>
                                        <a href="#">{{$data->name}}</a>
                                    </h2>
                                </td>
                                <td>{{$data->mobile}}</td>
                                <td>{{$data->email}}</td>
                                <td>{{date('d M Y h:i A',strtotime($data->created_at))}}</td>
                                <td>
                                    <button class="btn btn-sm btn-{{$data->status == 1 ? 'success' : 'danger'}}">{{$data->status == 1 ? 'Active' : 'Inactive'}}</button>
                                </td>

                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{route('admin.pph.edit',$data->id)}}"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item" href="{{route('admin.pph.view',$data->id)}}"><i class="fa-regular fa-eye m-r-5"></i> view</a>
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
        const deleteUrl = "{{ route('admin.pph.delete', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }

    function status_update(id, status) {
        if (status === 1) {
            var update_status = 0;
        } else {
            var update_status = 1;
        }
        $.ajax({
            url: "{{route('admin.pph.status')}}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
                status: update_status,
            },
            success: function(data) {
                if (data.success == true) {
                    Swal.fire('Success!', "Status updated", 'success');
                } else {
                    Swal.fire('Error!', "Something went wrong", 'error')
                }

            }

        });
    }
</script>
@endpush
@endsection