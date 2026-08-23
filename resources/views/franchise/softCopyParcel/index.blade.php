@extends('franchise.layouts.master')

@section('title') Soft Copy Parcel Management @endsection

@section('content')
@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <a href="{{route('franchise.softCopyParcel.create')}}" style="margin-left:10px;" class="btn add-btn"><i class="fa-solid fa-plus"></i>Add</a>
    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_support_tickets"><i class="fa-solid fa-plus"></i> Upload</a>
    <div class="view-icons">
        <a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
        <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa-solid fa-bars"></i></a>
    </div>
</div>
@endpush

<div class="card">
    <div class="card-body">
        <!-- Search Filter -->
        <div class="row filter-row">

            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                <div class="input-block mb-3 form-focus">
                    <div class="cal-icon">
                        <input class="form-control floating datetimepicker fromDate" type="text">
                    </div>
                    <label class="focus-label">From</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                <div class="input-block mb-3 form-focus">
                    <div class="cal-icon">
                        <input class="form-control floating datetimepicker toDate" type="text">
                    </div>
                    <label class="focus-label">To</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                <a href="#" class="btn btn-success w-100 dateSearch"> Search </a>
            </div>
        </div>
        <!-- /Search Filter -->

        <div class="row">
            <div class="col-md-12">
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
                                {{-- <td>{{$data->created_at}}</td> --}}

                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{route('franchise.softCopyParcel.edit',$data->id)}}"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item" href="{{route('franchise.softCopyParcel.view',$data->id)}}"><i class="fa-regular fa-eye m-r-5"></i> view</a>
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

<style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans:700,300);


    .center {
        height: 200px;
        border-radius: 3px;
        box-shadow: 8px 10px 15px 0 rgba(0, 0, 0, 0.2);
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        flex-direction: column;
        font-family: "Open Sans", Helvetica, sans-serif;
    }

    .title {
        width: 100%;
        height: 50px;
        border-bottom: 1px solid #999;
        text-align: center;
    }

    h1 {
        font-size: 16px;
        font-weight: 300;
        color: #666;
    }

    .dropzone {
        width: 100px;
        height: 80px;
        border: 1px dashed #999;
        border-radius: 3px;
        text-align: center;
    }

    .upload-icon {
        margin: 25px 2px 2px 2px;
    }

    .upload-input {
        position: relative;
        top: -62px;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
    }

    .download-button {
        background-color: #7b2cbf;
        color: #f7fff7;
        display: flex;
        align-items: center;
        font-size: 18px;
        border: none;
        border-radius: 20px;
        margin: 10px;
        padding: 7.5px 50px;
        cursor: pointer;
    }

    .submit-section {
        text-align: center;
        margin-top: 10px;
        float: left;
        width: 100%;
    }
</style>

<!-- Add Role Modal -->
<div id="add_support_tickets" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('franchise.softCopyParcel.storeByfile')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-files-container">
                        <div class="drag-file-area">
                            <span class="material-icons-outlined upload-icon"> file_upload </span>
                            <h3 class="dynamic-message">Drag & drop any file here</h3>
                            <label style="width: 220px;" class="label">
                                or <span class="browse-files">
                                    <input type="file" class="default-file-input" name="file" />
                                    <span class="browse-files-text">browse file</span>
                                    <span>from device</span>
                                </span>
                            </label>
                        </div>
                        <div class="file-block">
                            <div class="file-info">
                                <span class="material-icons-outlined file-icon">description</span> <span class="file-name"> </span> | <span class="file-size"> </span>
                                <span class="material-icons remove-file-icon">delete</span>
                            </div>
                            
                        </div>
                        <a href="{{route('franchise.softCopyParcel.downloadForamt')}}">
                            <button type="button" class="download-button">Download Format</button>
                        </a>

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
        const deleteUrl = "{{ route('franchise.softCopyParcel.delete', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }
    var isAdvancedUpload = function() {
        var div = document.createElement('div');
        return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
    }();

    let draggableFileArea = document.querySelector(".drag-file-area");
    let browseFileText = document.querySelector(".browse-files");
    let uploadIcon = document.querySelector(".upload-icon");
    let dragDropText = document.querySelector(".dynamic-message");
    let fileInput = document.querySelector(".default-file-input");
    let cannotUploadMessage = document.querySelector(".cannot-upload-message");
    let cancelAlertButton = document.querySelector(".cancel-alert-button");
    let uploadedFile = document.querySelector(".file-block");
    let fileName = document.querySelector(".file-name");
    let fileSize = document.querySelector(".file-size");
  
    let removeFileButton = document.querySelector(".remove-file-icon");
    let fileFlag = 0;

    fileInput.addEventListener("click", () => {
        fileInput.value = '';
        console.log(fileInput.value);
    });

    fileInput.addEventListener("change", e => {
        uploadIcon.innerHTML = 'check_circle';
        dragDropText.innerHTML = 'File Dropped Successfully!';
        fileName.innerText = fileInput.files[0].name;
        fileSize.innerText = (fileInput.files[0].size / 1024).toFixed(1) + " KB";
        uploadedFile.style.display = "block";
        fileFlag = 0;
    });

    if (isAdvancedUpload) {
        ["drag", "dragstart", "dragend", "dragover", "dragenter", "dragleave", "drop"].forEach(evt =>
            draggableFileArea.addEventListener(evt, e => {
                e.preventDefault();
                e.stopPropagation();
            })
        );

        ["dragover", "dragenter"].forEach(evt => {
            draggableFileArea.addEventListener(evt, e => {
                e.preventDefault();
                e.stopPropagation();
                uploadIcon.innerHTML = 'file_download';
                dragDropText.innerHTML = 'Drop your file here!';
            });
        });

        draggableFileArea.addEventListener("drop", e => {
            uploadIcon.innerHTML = 'check_circle';
            dragDropText.innerHTML = 'File Dropped Successfully!';
            let files = e.dataTransfer.files;
            fileInput.files = files;
            console.log(document.querySelector(".default-file-input").value);
            fileName.innerHTML = files[0].name;
            fileSize.innerHTML = (files[0].size / 1024).toFixed(1) + " KB";
            uploadedFile.style.cssText = "display: flex;";
            fileFlag = 0;
        });
    }

    removeFileButton.addEventListener("click", () => {
        uploadedFile.style.cssText = "display: none;";
        fileInput.value = '';
        uploadIcon.innerHTML = 'file_upload';
        dragDropText.innerHTML = 'Drag & drop any file here';
    });
</script>



<script>
    var datas = <?php echo json_encode($datas); ?>;
</script>

@endpush

@endsection