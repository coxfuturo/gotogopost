@extends('franchise.layouts.master')



@section('title') {{\App\Models\Admin::E2E}} @endsection



@push('add-modal-code')
<div class="col-auto float-end ms-auto">

    <a style="margin-left:10px;" class="btn-price btn add-btn" data-bs-toggle="modal" data-bs-target="#add_support_tickets">Upload</a>

</div>
@endpush


@section('content')

<!-- Row -->



<style>
    body {

        font-family: Arial, sans-serif;

        margin: 0;

        padding: 20px;

        background-color: #f1f3f4;

    }



    .email-container {

        max-width: 600px;

        margin: 0 auto;

        background: #fff;

        border: 1px solid #dadce0;

        border-radius: 8px;

        box-shadow: 0 1px 3px rgba(60, 64, 67, 0.3), 0 4px 8px rgba(60, 64, 67, 0.15);

        padding: 15px 0px;

    }



    .form-group {

        display: flex;

        align-items: center;

        padding: 8px 16px;

        border-bottom: 1px solid #dadce0;

    }



    .form-group label {

        flex: 0 0 60px;

        font-weight: bold;

        color: #202124;

        font-size: 14px;

    }



    .form-group input {

        flex: 1;

        padding: 6px 10px;

        border: none;

        border-radius: 4px;

        background-color: #f1f3f4;

    }



    .form-group input:focus {

        outline: none;

        border: none;

    }



    .form-group input::placeholder {

        font-size: 14px;

    }



    .form-group input[type="file"] {

        padding: 0;

        background-color: #fff;

    }



    .form-group textarea {

        flex: 1;

        padding: 10px;

        border: 1px solid #dadce0;

        border-radius: 4px;

        background-color: #fff;

        resize: vertical;

    }



    .form-actions {

        text-align: right;

        padding: 16px;

        border-top: 1px solid #dadce0;

    }



    .form-actions button {

        padding: 6px 30px;

        border: none;

        background-color: #fb9a14;

        color: white;

        border-radius: 5px;

        cursor: pointer;

    }



    .form-actions button:disabled {

        background-color: #ccc;

    }



    .hidden {

        display: none;

    }



    .show-cc-bcc {

        margin: 0 16px;

        cursor: pointer;

        color: #1a73e8;

        font-size: 14px;

    }



    .editor-container {

        padding: 0px 16px;

        margin-top: 20px;

    }



    .attachment-label {

        display: block !important;

        flex: unset !important;

        align-items: center;

        cursor: pointer;

        color: #1a73e8 !important;

    }



    .attachment-label i {

        margin-right: 8px;

    }



    .file-list {

        padding: 8px 16px;

        border-top: 1px solid #dadce0;

        background-color: #f1f3f4;

        font-size: 14px;

        color: #202124;

        margin-top: 15px;

    }



    .file-item {

        display: flex;

        align-items: center;

        margin-top: 4px;

    }



    .file-item i {

        margin-right: 8px;

    }



    .page-wrapper .content {

        padding: 0px;

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

    .file-container {
        display: flex;
        justify-content: space-between;
    }

    .remove-file{
        cursor: pointer;            
    }
</style>





<style>
    .from-div {



        flex-wrap: wrap;

        align-items: center;

    }



    .from-div span {

        background-color: #e0e0e0;

        border-radius: 2px;

        padding: 5px;

        margin: 2px;

        display: flex;

        align-items: center;

        font-size: 13px;

    }





    .from-div input {

        border: none;

        outline: none;

        flex-grow: 1;

        padding: 5px;

    }



    .from-div input:focus {

        border: none;

    }



    .from-div span .remove-email {

        margin-left: 5px;

        cursor: pointer;

        margin-right: 8px;

        font-size: 13px;

    }
</style>





<div class="row">

    <div class="col-sm-12">

        <div class="email-container">

            <form id="mailtomailform" action="{{ route('franchise.mailToMail.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="form-group to-div from-div">

                    <label for="to">To:</label>

                    <input type="number" id="to" name="to" placeholder="Enter Mobile">

                </div>

                <div class="form-group from-div" id="email-group" >
                    <label for="email">Email</label>

                    <input type="email" id="email" name="email" placeholder="Enter Email">
                </div>

                <div class="form-group">

                    <label for="subject">Subject:</label>

                    <input type="text" id="subject" name="subject" placeholder="Email Subject">

                </div>


                <div class="input-block editor-container mb-3">
                    <div id="editor" class="editor-container"></div>
                </div>

                <div id="file-list"></div>



                <div class="form-group mt-3" style="border-top: 1.5px solid #dadce0;">

                    <label for="attachments" class="attachment-label">

                        Attach files <i class="fas fa-paperclip"></i>

                    </label>



                    <input type="file" id="attachments" name="" multiple hidden>

                </div>



                <div class="form-actions">

                    <button id="submitButton" type="submit">Send</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- /Row -->

<!-- upload -->

<div id="add_support_tickets" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form action="{{route('franchise.mailToMail.storeByfile')}}" method="POST" enctype="multipart/form-data">

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

                        <a href="{{route('franchise.mailToMail.downloadFormat')}}">

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

<!-- /upload -->



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
    $(document).ready(function() {

        
        // Function to handle the appending of emails or phone numbers
        function appendValue(selector, valueClass, inputField) {
            var emailValue = $(inputField).val();

            if (emailValue) {
                var span = $('<span class="' + valueClass + '"></span>').text(emailValue);

                var removeIcon = $('<i class="fas fa-times remove-email"></i>').click(function() {
                    $(this).parent().remove();
                });

                span.prepend(removeIcon);
                $(selector).append(span);
            }

            $(inputField).val(''); // Clear input field
        }

        // Handle "Enter" key or "focusout" event for #to field
        $('#to').on('focusout keypress', function(e) {
            if (e.type === 'focusout' || e.which === 13) {  // Trigger on focusout or Enter key press
                e.preventDefault();  // Prevent default Enter key action
                appendValue('.to-div', 'to-phone', this); // Append value to '.to-div'
            }
        });

    
        // Handle "Enter" key or "focusout" event for #email field
        $('#email').on('focusout keypress', function(e) {
            if (e.type === 'focusout' || e.which === 13) {
                e.preventDefault();
                appendValue('#email-group', 'email', this); // Append value to '#email-group'
            }
        });
    });
</script>





<script>
    $(document).ready(function() {

   
        var selectedFiles = [];

        $('#attachments').on('change', function () {
            var fileList = $('#file-list');
            fileList.addClass('file-list');

            var files = this.files;
            
            if (files.length > 0) {
                for (var i = 0; i < files.length; i++) {
                    let fileIndex = selectedFiles.length;
                    selectedFiles.push(files[i]);

                    fileList.append(`
                        <div class="file-container" data-index="${fileIndex}">
                            <div class="file-item">
                                <i class="fas fa-paperclip"></i> ${files[i].name}
                            </div>
                            <span class="remove-file" data-index="${fileIndex}">❌</span>
                        </div>
                    `);
                }
            }
        });

        // Remove file when ❌ is clicked
        $(document).on('click', '.remove-file', function () {
            var index = $(this).data('index');

            // Remove file from selectedFiles array
            selectedFiles.splice(index, 1);

            // Remove the file item from UI
            $(this).parent().remove();
        });


        // Prevent form submission on "Enter" key press
        $(document).on('keypress', 'form input', function(e) {
            if (e.which === 13) {
                e.preventDefault();
            }
        });

        // Form submission only on clicking the "Send" button
        $('#submitButton').on('click', function(e) {
            e.preventDefault();

            var formData = new FormData($('#mailtomailform')[0]);
            if (window.editor) {
                var editorContent = window.editor.getData();
                formData.append('message', editorContent);
            }

            $('.to-phone').each(function() {
                var phone = $(this).text().trim();
                formData.append('to-phone[]', phone);
            });

            $('.email').each(function() {
                var email = $(this).text().trim();
                formData.append('to-email[]', email);
            });

            var files = selectedFiles;

            console.log("reached here", files);

            for (var i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            $.ajax({
                url: "{{ route('franchise.mailToMail.store') }}",  // Ensure the route is rendered as a string in Blade
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                beforeSend: function() {
                            $('#submitButton').text('Sending...');
                            $('#submitButton').prop('disabled', true);
                        },
                success: function(response) {
                    $('#submitButton').text('Send')
                    $('#submitButton').prop('disabled', false);
                    if (response.status === true) {
                        window.location.href = "{{ route('franchise.mailToMail.sentMails', ['service_type' => 'mail_to_mail_single']) }}";
                    }
                },
                error: function(xhr, status, error) {
                    $('#submitButton').text('Send');
                    $('#submitButton').prop('disabled', false);
                    console.error(xhr.responseText);  // Log any errors
                }
            });

        });
    });
</script>




@endpush



@endsection