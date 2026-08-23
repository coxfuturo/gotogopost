@extends('franchise.layouts.master')



@section('title') Role Users @endsection



@section('content')



<style>
    table.dataTable>thead .sorting:before,
    table.dataTable>thead .sorting_asc:before,
    table.dataTable>thead .sorting_desc:before,
    table.dataTable>thead .sorting_asc_disabled:before,
    table.dataTable>thead .sorting_desc_disabled:before {
        right: 1em;
        content: "↑";
        top: 3px;
    }

    table.dataTable>thead .sorting:after,
    table.dataTable>thead .sorting_asc:after,
    table.dataTable>thead .sorting_desc:after,
    table.dataTable>thead .sorting_asc_disabled:after,
    table.dataTable>thead .sorting_desc_disabled:after {
        right: .5em;
        content: "↓";
        top: 4px;
    }

    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }
</style>



@push('add-modal-code')

<div class="col-auto float-end ms-auto">

    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_role_user"><i class="fa-solid fa-plus"></i> Add</a>
</div>

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

                                <th>Name</th>

                                <th>Phone</th>

                                <th>Email</th>

                                <th>Role</th>

                                <th>Status</th>

                                <th class="text-end">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($roleUsers as $i => $user)

                            @if(!$user->hasRole('admin'))

                            <tr>

                                <td>{{$i+1}}</td>

                                <td>

                                    <h2 class="table-avatar">

                                        @if ($user->image)

                                        <a href="#" class="avatar">

                                            <img src="{{ asset('tenancy/assets/franchise/RoleUser/' . $user->image) }}" alt="">

                                        </a>

                                        @else

                                        <a href="#" class="avatar"><img src="{{asset('admin/assets/img/profiles/avatar-19.jpg')}}" alt=""></a>

                                        @endif

                                        <a href="#">{{$user->name}}</a>

                                    </h2>

                                </td>

                                <td>{{$user->phone}}</td>

                                <td>{{$user->email}}</td>

                                @if($user->getRoleNames()->count() > 0)

                                <td>{{ $user->getRoleNames()[0] }}</td>

                                @else

                                <td>NA</td>

                                @endif



                                <td>

                                    <div class="dropdown action-label">

                                        @if($user->status == 1)

                                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>

                                        @else

                                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>

                                        @endif

                                        <div class="dropdown-menu active-inactive-menu-{{$user->id}}">

                                            <a class="dropdown-item" onclick="status_update('{{$user->id}}','1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>

                                            <a class="dropdown-item" onclick="status_update('{{$user->id}}','0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>

                                        </div>

                                    </div>

                                </td>

                                <td class="text-end">

                                    <div class="dropdown dropdown-action">

                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>

                                        <div class="dropdown-menu dropdown-menu-right">

                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_role_user{{$user->id}}"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>

                                            <a class="dropdown-item" href="#" onclick="delete_modal('{{$user->id}}')"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                            <!-- Add Client Modal -->

                            <div id="edit_role_user{{$user->id}}" class="modal custom-modal fade" role="dialog">

                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">Update Role User</h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                                                <span aria-hidden="true">&times;</span>

                                            </button>

                                        </div>

                                        <div class="modal-body">

                                            <form action="{{route('franchise.role.user.update',$user->id)}}" method="POST">

                                                @csrf

                                                <div class="row">

                                                    <div class="col-md-6">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">Name <span class="text-danger">*</span></label>

                                                            <input class="form-control" name="name" placeholder="Enter name.." type="text" value="{{old('name',$user->name)}}" required>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">Email <span class="text-danger">*</span></label>

                                                            <input class="form-control floating" name="email" placeholder="Enter email.." type="email" value="{{old('email',$user->email)}}" required>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">Phone <span class="text-danger">*</span></label>

                                                            <input class="form-control" name="phone" placeholder="Enter phone.." type="number" value="{{old('phone',$user->phone)}}" required>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">Role <span class="text-danger">*</span></label>

                                                            <select name="role" class="select" data-tags="true" data-placeholder="Select an option" required>

                                                                @foreach($roles as $role)

                                                                <option value="{{ $role }}" {{ $user->getRoleNames()->count() > 0 && $role == $user->getRoleNames()[0] ? 'selected' : '' }}>

                                                                    {{ $role }}

                                                                </option>

                                                                @endforeach

                                                            </select>

                                                        </div>

                                                    </div>

                                                </div>



                                                <div class="submit-section">

                                                    <button class="btn btn-primary submit-btn">Submit</button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- /Add Client Modal -->



                            @endif

                            @endforeach



                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Client Modal -->

<div id="add_role_user" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Add Role User</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form action="{{route('franchise.role.user.store')}}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="row">

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Name <span class="text-danger">*</span></label>

                                <input class="form-control" name="name" placeholder="Enter name.." type="text" value="{{old('name')}}" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Email <span class="text-danger">*</span></label>

                                <input class="form-control floating" name="email" placeholder="Enter email.." type="email" value="{{old('email')}}" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Phone <span class="text-danger">*</span></label>

                                <input class="form-control" name="phone" placeholder="Enter phone.." type="number" value="{{old('phone')}}" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Role <span class="text-danger">*</span></label>

                                <select name="role" class="select" data-tags="true" data-placeholder="Select an option" required>

                                    <option value="">Select an option</option>

                                    @foreach($roles as $role)

                                    <option value="{{$role}}">{{$role}}</option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Password <span class="text-danger">*</span></label>

                                <input class="form-control" name="password" placeholder="Enter password.." type="password" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Confirm Password <span class="text-danger">*</span></label>

                                <input class="form-control" name="password_confirmation" placeholder="Enter confirm password.." type="password" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label" for="fileInput5">Image</label>

                                <input type="file" class="form-control" id="fileInput5" onchange="displayFile(5)" name="image">

                                <div id="fileContainer5">

                                </div>

                            </div>



                        </div>

                    </div>

                    <div class="submit-section">

                        <button class="btn btn-primary submit-btn">Submit</button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<!-- /Add Client Modal -->





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

        const deleteUrl = "{{ route('franchise.role.user.delete', ['id' => ':id']) }}".replace(':id', id);

        $('#delete_button').attr('href', deleteUrl);

        $('#delete_modal').modal('show');

    }



    function status_update(id, status) {

        var className = "active-inactive-menu-" + id;

        $.ajax({

            url: "{{route('franchise.role.user.status')}}",

            type: "POST",

            data: {

                "_token": "{{ csrf_token() }}",

                id: id,

                status: status,

            },

            success: function(data) {

                if (data.success == true) {

                    Swal.fire('Success!', "Status updated", 'success');

                    if (data.status == 0) {



                        var newElement = $('<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>');

                        $('.' + className).prev().replaceWith(newElement);

                    } else {

                        var newElement = $('<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>');

                        $('.' + className).prev().replaceWith(newElement);

                    }

                } else {

                    Swal.fire('Error!', "Something went wrong", 'error')

                }



            }

        });

    }



    function displayFile(id) {

        const fileInput = document.getElementById(`fileInput${id}`);

        const fileContainer = document.getElementById(`fileContainer${id}`);



        // Clear any previous content

        fileContainer.innerHTML = "";



        // Check if a file is selected

        if (fileInput.files.length === 0) {

            fileContainer.innerHTML = "<p>No file selected.</p>";

            return;

        }

        const file = fileInput.files[0];

        const fileType = file.type;



        if (fileType === "application/pdf") {

            // Display PDF

            const reader = new FileReader();

            reader.onload = function(e) {

                const pdfData = e.target.result;

                const embed = document.createElement("embed");

                embed.setAttribute("src", pdfData);

                embed.setAttribute("type", "application/pdf");

                embed.style.width = "285px";

                embed.style.height = "130px";

                fileContainer.appendChild(embed);

            };

            reader.readAsDataURL(file);

        } else if (fileType.startsWith("image/")) {

            // Display image

            const img = document.createElement("img");

            img.setAttribute("src", URL.createObjectURL(file));

            img.style.width = "100%";

            img.style.height = "100%";

            fileContainer.appendChild(img);

        } else {

            fileContainer.innerHTML = "<p>Unsupported file type.</p>";

        }

    }



    $(document).ready(function() {

        $('.searchFilter').on('keyup', function() {

            console.log("reached here", this.value)

            $('.datatable').DataTable().search(this.value).draw();

        });

    });
</script>

@endpush

@endsection