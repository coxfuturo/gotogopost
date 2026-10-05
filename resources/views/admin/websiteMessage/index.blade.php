@extends('admin.layouts.master')
@section('title') Website Message @endsection
@section('content')
@push('add-modal-code')
<div class="col-auto float-end ms-auto">
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
                    <table class="table table-bordered custom-table datatable table-hover">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Services</th>
                                <th class="text-center">Message</th>
                                <th class="text-end">Action</th>  <!-- Missing header added -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($website_message as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $data->name }}</td>
                                <td class="text-start">{{ $data->phone }}</td>
                                <td class="text-start">{{ $data->email }}</td>
                                <td class="text-start">{{ $data->option }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($data->message, 100) }}</td>
                                <td class="text-end">
                                    <a href="javascript:void(0);" class="text-danger" onclick="delete_modal({{ $data->id }})">
                                        <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                                    </a>   
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
        const deleteUrl = "{{ route('websideMessage.delete', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }
</script>
@endpush
@endsection
