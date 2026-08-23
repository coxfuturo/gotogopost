@extends('cms.layouts.master')

@section('title') Bag Management @endsection

@section('content')


@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_role_user"><i class="fa-solid fa-plus"></i> Add</a>
    <div class="view-icons">
        <a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
        <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa-solid fa-bars"></i></a>
    </div>
</div>
@endpush
<!-- Search Filter -->
<div class="row filter-row">
    <div class="col-sm-6 col-md-3">
        <div class="input-block mb-3 form-focus">
            <input type="text" class="form-control floating">
            <label class="focus-label">Bag ID</label>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="input-block mb-3 form-focus">
            <input type="text" class="form-control floating">
            <label class="focus-label">Bag Name</label>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="d-grid">
            <a href="#" class="btn btn-success"> Search </a>
        </div>
    </div>
</div>
<!-- Search Filter -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="row staff-grid-row">
    @if ($bag->count()>0)
    @foreach ( $bag as $data)
    <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3 d-flex">
        <div class="profile-widget w-100" style="padding:10px">   
            <div class="dash-card-icon">
                <i class="fa-solid fa-suitcase" style="color:#666363;font-size:40px"></i>
            </div>
            <h4 class="user-name mt-2 mb-0 text-ellipsis"><a href="client-profile.html">{{$data->name}}</a></h4>
            <h5 class="user-name mt-1 mb-0 text-ellipsis"><a href="client-profile.html">ID: {{$data->bag_id}}</a></h5>
            <a href="#" data-bs-toggle="modal" data-bs-target="#edit_role{{$data->id}}"  class="btn btn-white btn-sm m-t-10"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
            <a href="#" onclick="delete_modal({{$data->id}})" data-bs-toggle="modal" data-bs-target="#delete_bag" class="btn btn-white btn-sm m-t-10"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
        </div>
    </div>
    <div id="edit_role{{$data->id}}" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-md">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('cms.bag.update', ['id' => $data->id]) }}" method="POST">
                        @csrf
                        <div class="input-block mb-3">
                            <label class="col-form-label">Bag Name <span class="text-danger">*</span></label>
                            <input name="name" class="form-control" value="{{$data->name}}" type="text">
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <p>No result found</p>
    @endif
</div>



<!-- Add Role Modal -->
<div id="add_role_user" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Bag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('cms.bag.store')}}" method="POST">
                    @csrf
                    <div class="input-block mb-3">
                        <label class="col-form-label">Bag Name <span class="text-danger">*</span></label>
                        <input class="form-control" name="name" type="text" required>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Role Modal -->


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
        console.log("reached here",id)
        const deleteUrl = "{{ route('cms.bag.delete', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    } 
</script>
@endpush
@endsection
