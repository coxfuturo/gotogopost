@extends('admin.layouts.master')

@section('title') Pickup Details Management @endsection

@section('content')


@push('add-modal-code')
{{-- <div class="col-auto float-end ms-auto">
    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_role_user"><i class="fa-solid fa-plus"></i> Add</a>
    <div class="view-icons">
        <a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
        <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa-solid fa-bars"></i></a>
    </div>
</div> --}}
@endpush
<!-- Search Filter -->
<div class="row filter-row">
    <div class="col-sm-6 col-md-3">
        <div class="input-block mb-3 form-focus">
            <input type="text" class="form-control floating">
            <label class="focus-label">Client ID</label>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="input-block mb-3 form-focus">
            <input type="text" class="form-control floating">
            <label class="focus-label">Client Name</label>
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


<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped custom-table datatable">
                <thead>
                    <tr>
                        <th>SN#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Address</th>
                        <th>Pincode</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pickupdetails as $i => $user)   
                    <tr>
                        <td>{{$i+1}}</td>
                        <td>
                            <h2 class="table-avatar">
                                <a href="#">{{$user->name}}</a>
                            </h2>
                        </td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->phone}}</td>
                        <td>{{$user->city}}</td>
                        <td>{{$user->state}}</td>
                        <td>{{$user->address}}</td>
                        <td>{{$user->pincode}}</td>
                       
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
                    <!-- update Client Modal -->
                    <div id="edit_role_user{{$user->id}}" class="modal custom-modal fade" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Pickup Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('admin.pickup-details.update',$user->id)}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="name" placeholder="Enter name" type="text" value="{{old('name',$user->name)}}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                                    <input class="form-control floating" name="email" placeholder="Enter email" type="email" value="{{old('email',$user->email)}}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">Phone <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="phone" placeholder="Enter phone" type="number" value="{{old('phone',$user->phone)}}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">City <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="city" placeholder="Enter city" type="text" value="{{old('city',$user->city)}}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">State <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="state" placeholder="Enter State" type="text" value="{{old('state',$user->state)}}" required>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">Pincode <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="pincode" placeholder="Enter Pincode" type="text" value="{{old('pincode',$user->pincode)}}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">Address <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="address" placeholder="Enter Address" type="text" value="{{old('address',$user->address)}}" required>
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
                    <!-- update Client Modal -->
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Add Client Modal -->
<div id="add_role_user" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Pickup Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.pickup-details.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="name" placeholder="Enter name" type="text" value="{{old('name')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                <input class="form-control floating" name="email" placeholder="Enter email" type="email" value="{{old('email')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Phone <span class="text-danger">*</span></label>
                                <input class="form-control" name="phone" placeholder="Enter phone" type="number" value="{{old('phone')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">City <span class="text-danger">*</span></label>
                                <input class="form-control" name="city" placeholder="Enter city" type="text" value="{{old('city')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">State <span class="text-danger">*</span></label>
                                <input class="form-control" name="state" placeholder="Enter City" type="text" value="{{old('city')}}" required>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Pincode <span class="text-danger">*</span></label>
                                <input class="form-control" name="pincode" placeholder="Enter Pincode" type="text" value="{{old('pincode')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Address <span class="text-danger">*</span></label>
                                <input class="form-control" name="address" placeholder="Enter Address" type="text" value="{{old('address')}}" required>
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
        console.log("reached here",id)
        const deleteUrl = "{{ route('admin.pickup-details.delete', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
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

    function status_update(id,status)
    {
        var className = "active-inactive-menu-" + id;
        $.ajax({
            url: "{{route('admin.user.status')}}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
                status: status,
            },   
            success: function(data) {
                console.log("reached here",data)
                if (data.success == true)
                {
                Swal.fire('Success!', "Status updated", 'success');
                if (data.status == 0) {       
                    var newElement = $('<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>');
                    $('.' + className).prev().replaceWith(newElement);
                    } else {                     
                    var newElement = $('<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>');
                    $('.' + className).prev().replaceWith(newElement);
                    }
                }else{
                    Swal.fire('Error!', "Something went wrong", 'error')
                }

            }

        });
    }

    
</script>
@endpush
@endsection
