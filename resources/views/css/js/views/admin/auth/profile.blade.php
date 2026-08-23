@extends('admin.layouts.master')

@section('title') Profile Management @endsection

@section('content')


<style>
    .profile-view .profile-img-wrap img {
        border-radius: 50%;
        height: 120px;
        width: 120px;
        object-fit: cover !important;
    }

    .personal-info li .text {
        color: #bb2828;
        display: block;
        overflow: hidden;
        width: 70%;
        float: left;
    }


    .tab-content {
        padding-top: 0px;
    }

    .personal-info li .text {
        color: #bb2828;
        display: block;
        overflow: hidden;
        width: 70%;
        float: left;
    }


    table.table-new.dataTable>thead .sorting:after,
    table.table-new.dataTable>thead .sorting_asc:after,
    table.table-new.dataTable>thead .sorting_desc:after,
    table.table-new.dataTable>thead .sorting_asc_disabled:after,
    table.table-new.dataTable>thead .sorting_desc_disabled:after {
        right: 0.5em;
        content: "\f0d7";
        font-family: "FontAwesome";
        top: 15px;
        color: #C1CCDB;
        font-size: 12px;
        opacity: 1;
    }

    table.table-new.dataTable>thead .sorting:before,
    table.table-new.dataTable>thead .sorting_asc:before,
    table.table-new.dataTable>thead .sorting_desc:before,
    table.table-new.dataTable>thead .sorting_asc_disabled:before,
    table.table-new.dataTable>thead .sorting_desc_disabled:before {
        right: 0.5em;
        content: "\f0d8";
        font-family: "FontAwesome";
        top: 5px;
        color: #C1CCDB;
        font-size: 12px;
        opacity: 1;
    }
</style>


<div class="card mb-0">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <img style="object-fit: cover;" class="inline-block" src="{{isset($data->image) ? asset('admin/profileImage/'.$data->image) : asset('admin/assets/img/profiles/avatar-02.jpg')}}">
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0">{{$data->name}}</h3>
                                    <h5 class="">Name : {{$data->name}}</h5>
                                    <h5 class="">Phone : {{$data->phone }}</h5>
                                    <div class="staff-id">Email : {{$data->email}}</div>
                                    <div class="doj ">Date of registration : {{date('d M Y',strtotime($data->created_at))}}</div>
                                    <div class="pro-edit"><a data-bs-target="#profile_info" data-bs-toggle="modal" class="edit-icon" href="#"><i class="fa-solid fa-pencil"></i></a></div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<!-- Profile Modal -->
<div id="profile_info" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="franchiseForm" method="post" action="{{route('admin.profile')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-img-wrap edit-img">
                                <div id="fileContainer1">
                                    <img style="object-fit: cover;" class="inline-block" src="{{isset($data->image) ? asset('admin/profileImage/'.$data->image) : asset('admin/assets/img/profiles/avatar-02.jpg')}}">
                                </div>
                                <div class="fileupload btn">
                                    <span class="btn-text">Edit</span>
                                    <input class="upload" id="fileInput1" name="photo" onchange="displayFile(1)" type="file">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{$data->name}}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="mobile" value="{{$data->phone}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Email</label>
                                        <input type="text" class="form-control" name="email" value="{{$data->email}}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Password</label>
                                        <input type="text" class="form-control" id="password" name="password">
                                    </div>
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
<!-- /Profile Modal -->



@push('page-javascript')

<script>
    function displayFile(id) {
        const fileInput = document.getElementById(`fileInput${id}`);
        console.log("reached here", fileInput.files.length)
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
            img.style.height = "120px";
            fileContainer.appendChild(img);
        } else {
            fileContainer.innerHTML = "<p>Unsupported file type.</p>";
        }
    }
</script>

@endpush
@endsection