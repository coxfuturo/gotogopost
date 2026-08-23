@extends('pph.layouts.master')

@section('title'){{\App\Models\Admin::E2E}} @endsection

@section('content')
@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <a href="{{route('pph.mailToMail.create')}}" style="margin-left:10px;" class="btn add-btn"><i class="fa-solid fa-plus"></i>compose</a>
</div>
@endpush

<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }
</style>

<div class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="email-header mb-2">
                        <div class="row">
                            <div class="col top-action-left">
                                <div class="float-start">
                                    {{-- <div class="btn-group dropdown-action">
                                        <button type="button" class="btn btn-white dropdown-toggle" data-bs-toggle="dropdown">Select <i class="fa-solid fa-angle-down "></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">All</a>
                                            <a class="dropdown-item" href="#">None</a>
                                            <div class="dropdown-divider"></div> 
                                            <a class="dropdown-item" href="#">Read</a>
                                            <a class="dropdown-item" href="#">Unread</a>
                                        </div>
                                    </div> --}}
                                    <a class="btn-white btn" href="{{ route('pph.mailToMail.sentMails', ['service_type' => 'mail_to_mail_single']) }}">Sent Mails Single</a>
                                    <a class="btn-white btn" href="{{ route('pph.mailToMail.sentMails', ['service_type' => 'mail_to_mail_bulk']) }}">Sent Mails Bulk</a>

                                </div>

                                <!-- Search Filter -->
                                <form action="{{ route('pph.mailToMail.receivedMails') }}" method="GET">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 col-12">
                                            <div class="input-block mb-3 form-focus">
                                                <div class="cal-icon">
                                                    <input class="form-control floating datetimepicker bookingdate" type="text" name="date" required>
                                                </div>
                                                <label class="focus-label">Date</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-1">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-success">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- /Search Filter -->

                            </div>
                            <div class="col-auto top-action-right">

                                {{-- <div class="text-end">
                                    <span class="text-muted d-none d-md-inline-block">Showing 10 of 112 </span>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="email-content">
                        <div class="table-responsive">
                            <table class="table table-inbox table-hover  datatable">
                                <thead>
                                    <tr>

                                        <th class="text-center"></th>
                                        <th class="text-center">Phone</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Subject</th>
                                        <th class="text-center"></th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($recievedMails as $mail)
                                    <tr class="unread clickable-row">

                                        <td><span class="mail-important"><i class="fa fa-star starred"></i></span></td>

                                        @if($mail->user)
                                        <td class="name">{{ $mail->user->phone }}</td>
                                        <td class="name">{{ $mail->user->email }}</td>
                                        @endif


                                        @if($mail->franchise)
                                        <td class="name">{{ $mail->franchise->mobile }}</td>
                                        <td class="name">{{ $mail->franchise->email }}</td>
                                        @endif

                                        @if($mail->cms)
                                        <td class="name">{{ $mail->cms->mobile }}</td>
                                        <td class="name">{{ $mail->cms->email }}</td>

                                        @endif


                                        @if($mail->pph)
                                        <td class="name">{{ $mail->pph->mobile }}</td>
                                        <td class="name">{{ $mail->pph->email }}</td>
                                        @endif


                                        <td class="subject">{{ $mail->subject }}</td>
                                        <td>
                                            @if($mail->attachments->count() > 0)
                                            <i class="fa-solid fa-paperclip"></i>
                                            @endif
                                        </td>
                                        <td class="mail-date">
                                            @if($mail->created_at->isToday())
                                            {{ $mail->created_at->format('H:i') }}
                                            @else
                                            {{ $mail->created_at->format('d-m-Y') }}
                                            @endif
                                        </td>

                                        <td> <a class="dropdown-item" href="{{route('pph.mailToMail.receivedview', ['id' => $mail->id])}}"><i class="fa-regular fa-eye m-r-5"></i> view</a></td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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




@endpush

@endsection