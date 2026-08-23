@extends('cms.layouts.master')

@section('title') Role & Permission @endsection

@section('content')

<div>
    <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3">
            <a href="#" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#add_role"><i class="fa-solid fa-plus"></i> Add Roles</a>
            <div class="roles-menu">
                <ul>
                    @foreach($roles as $role)
                    <li class="{{request()->url() == route('cms.role.index',$role->id) ? 'active' : ''}}">
                        <a href="{{route('cms.role.index',$role->id)}}">{{ucfirst($role->name)}}
                            @if($role->name != 'admin')
                            <span class="role-action">
                                <span class="action-circle large edit-btn" data-bs-toggle="modal" data-bs-target="#edit_role{{$role->id}}">
                                    <i class="material-icons ">edit</i>
                                </span>
                                <span class="action-circle large delete-btn" data-bs-toggle="modal" data-bs-target="#delete_role" onclick="delete_modal('{{$role->id}}')">
                                    <i class="material-icons">delete</i>
                                </span>
                            </span>
                            @endif
                        </a>
                    </li>
                    <div id="edit_role{{$role->id}}" class="modal custom-modal fade" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content modal-md">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Role</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('cms.role.update', ['id' => $role->id]) }}" method="POST">
                                        @csrf
                                        <div class="input-block mb-3">
                                            <label class="col-form-label">Role Name <span class="text-danger">*</span></label>
                                            <input name="name" class="form-control" value="{{$role->name}}" type="text">
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
                </ul>
            </div>
        </div>
        <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9">

            <div class="table-responsive">
                @php
                $url = url()->current();
                $parsed_url = parse_url($url);
                $path = $parsed_url['path'];
                $path_segments = explode('/', $path);
                $last_segment = end($path_segments);
                $role = Spatie\Permission\Models\Role::find($last_segment);
                @endphp
                <form action="{{route('cms.store.permission',$last_segment)}}" method="post">
                    @csrf
                    <table class="table table-striped custom-table">
                        <thead>
                            <tr>
                                <th>Permission</th>
                                <th>All</th>
                                <th>Read</th>
                                <th>Create</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($permissions as $name => $permission)
                            <tr>
                                <td>{{ $name }}</td>
                                <td>
                                    <label for="c{{ $name }}" class="custom_check">
                                        <input id="c{{ $name }}" class="checkbox-row" {{ $role->permissions->whereIn('id', $permission->pluck('id'))->count() === 4 ? 'checked="checked"' : '' }} type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <label for="c{{ $permission[0]->id }}" class="custom_check">
                                        <input id="c{{ $permission[0]->id }}" type="checkbox" class="checkbox-item" value="{{ $permission[0]->name }}" name="permissions[]" @if(in_array($permission[0]->id,$rolePermissions)) checked @endif >
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <label for="c{{ $permission[1]->id }}" class="custom_check">
                                        <input id="c{{ $permission[1]->id }}" type="checkbox" class="checkbox-item" value="{{ $permission[1]->name }}" name="permissions[]" @if(in_array($permission[1]->id,$rolePermissions)) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <label for="c{{ $permission[2]->id }}" class="custom_check">
                                        <input id="c{{ $permission[2]->id }}" type="checkbox" class="checkbox-item" value="{{ $permission[2]->name }}" name="permissions[]" @if(in_array($permission[2]->id,$rolePermissions)) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <label for="c{{ $permission[3]->id }}" class="custom_check">
                                        <input id="c{{ $permission[3]->id }}" type="checkbox" class="checkbox-item" value="{{ $permission[3]->name }}" name="permissions[]" @if(in_array($permission[3]->id,$rolePermissions)) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button class="btn btn-primary">Submit</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div id="add_role" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('cms.role.create')}}" method="POST">
                    @csrf
                    <div class="input-block mb-3">
                        <label class="col-form-label">Role Name <span class="text-danger">*</span></label>
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
<script>
    $('.checkbox-row').on('change', function() {
        toggleRow($(this));
    });

    $('.checkbox-item').on('change', function() {
        let checked = true;

        $(this).parents('tr').first()
            .children('td')
            .children('label')
            .children('.checkbox-item')
            .each(function(index, el) {
                if (!$(el).prop('checked')) {
                    checked = false;
                }
            });

        $(this).parents('tr').first()
            .children('td')
            .children('label')
            .children('.checkbox-row')
            .prop('checked', checked);
    });

    function toggleRow(el) {
        if (el.prop('checked')) {
            el.parents('td').siblings('td').children('label').children('input').prop('checked', true);
        } else {
            el.parents('td').siblings('td').children('label').children('input').prop('checked', false);
        }
    }
</script>


<script>
    function delete_modal(id) {
        event.preventDefault();
        const deleteUrl = "{{ route('cms.role.delete', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }

    $(document).ready(function() {
        $('.edit-btn').click(function(event) {
            event.preventDefault();
            var roleId = $(this).data('role-id');
            var roleLink = $('#role_link_' + roleId);
            roleLink.addClass('disabled').removeAttr('href').click();
        });
    });
</script>

@endpush

@endsection