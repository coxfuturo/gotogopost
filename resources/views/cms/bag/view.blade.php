@extends('cms.layouts.master')

@section('title') {{$title}} @endsection

@section('content')

<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block !important;
    }

    .page-wrapper .content .page-header {
        margin-bottom: 0.875rem;
    }

    .group {
        display: flex;
        line-height: 28px;
        align-items: center;
        position: relative;
        max-width: 192px;
    }

    .barcode_no_style {
        width: 100%;
        height: 35px;
        line-height: 28px;
        padding: 0 1rem;
        padding-left: 2.5rem;
        border: 2px solid transparent;
        border-radius: 6px;
        outline: none;
        background-color: #fff;
        color: #0d0c22;
        transition: .3s ease;
        border-color: rgba(234, 76, 137, 0.4);
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4p
    }

    .barcode_no_style::placeholder {
        color: #9e9ea7;
    }




    .icon {
        position: absolute;
        left: 1rem;
        fill: #9e9ea7;
        width: 1rem;
        height: 1rem;
    }

    .barcode_message {
        color: #da4c4c;
    }
</style>


<div class="row">

    <div class="col-md-12">


        @if($showScaner == 1)
        <div class="mb-2 d-flex align-items-center">

            <div class="group">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <g>
                        <path d="M12,2C6.486,2,2,6.486,2,12s4.486,10,10,10s10-4.486,10-10S17.514,2,12,2z M17.408,8.707l-7.75,8.75l-3.66-3.66L6.5,13.914 l4.5,5.086l8.5-9.5L17.408,8.707z" />
                        <path fill="none" d="M0,0h24v24H0V0z" />
                    </g>
                </svg>

                <input placeholder="Scan" id="barcode_no" name="barcode_no" class="barcode_no_style">
            </div>

            <span class="p-2 barcode_message"></span>

        </div>
        @endif

        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <span class="text-center date-above-table">{{ now()->format('d-m-Y') }}</span>
                    <span class="text-center code-above-table"> {{$bagDetails->barcode_no}}</span>

                    <table class="table table-striped custom-table datatable">

                        <thead>

                            <tr>

                                <th>SN#</th>

                                <th class="text-center">Barcode</th>

                                <th class="text-center"><span>From Addresss</span></th>

                                <th class="text-center">State</th>

                                <th class="text-center">City</th>

                                <th class="text-center">Pincode</th>

                                <th class="text-center">Mobile</th>

                                <th class="text-center">Email</th>

                                <th class="text-center">To Addresss</th>

                                <th class="text-center">State</th>

                                <th class="text-center">City</th>

                                <th class="text-center">Pincode</th>

                                <th class="text-center">Mobile</th>

                                <th class="text-center">Email</th>

                                <th class="text-center">Weight</th>

                                <th class="text-center">amount (₹)</th>

                                <!-- <th class="text-end" style="width:2px"></th> -->

                            </tr>

                        </thead>

                        <tbody>

                            {{-- {{dd($datas)}} --}}

                            @foreach($datas as $i => $data)

                            <tr>

                                <img style="display:none" src="data:image/png;base64,{{  $data->barcode_image_src }}" alt="Barcode" style="height:50px;" />

                                <td>{{$i+1}}</td>

                                <td>{{$data->barcode_no}}</td>

                                <td> {{$data->pickup_address}}</td>

                                <td>{{$data->pickup_state}}</td>

                                <td>{{$data->pickup_city}}</td>

                                <td>{{$data->pickup_pincode}}</td>

                                <td>{{$data->pickup_mobile}}</td>

                                <td>{{$data->pickup_email}}</td>

                                <td>{{$data->consignee_address}}</td>

                                <td>{{$data->consignee_state}}</td>

                                <td>{{$data->consignee_city}}</td>

                                <td>{{$data->consignee_pincode}}</td>

                                <td>{{$data->consignee_mobile}}</td>

                                <td>{{$data->consignee_email}}</td>

                                <td>{{$data->package_weight}}</td>

                                <td>{{$data->payment_amount}}</td>

                                <!-- <td class="text-end">

                                    <div class="dropdown dropdown-action">

                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>

                                        <div class="dropdown-menu dropdown-menu-right">

                                            <a class="dropdown-item" href="{{route('cms.go-speed-post-parcel.view',$data->id)}}"><i class="fa-regular fa-eye m-r-5"></i> view</a>

                                            @if($bagType=="created") 
                                            <a class="dropdown-item" href="#" onclick="delete_modal('{{$data->id}}')"><i class="fa-regular fa-trash-can m-r-5"></i> Remove</a>
                                            @endif




                                        </div>

                                    </div>

                                </td> -->

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

                <form action="{{route('cms.go-speed-post-parcel.storeByfile')}}" method="POST" enctype="multipart/form-data">

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

                        <a href="{{route('cms.go-speed-post-parcel.downloadForamt')}}">

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

                    <h3>Remove</h3>

                    <p>Are you sure want to remove from this bag?</p>

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

        const deleteUrl = "{{ route('cms.bag.removeParcel', ['id' => ':id']) }}".replace(':id', id);

        const serviceType = "{{ $service_type }}";

        const bagId = "{{ $bagId }}";

        const finalUrl = `${deleteUrl}?service_type=${serviceType}&bagId=${bagId}`;

        $('#delete_button').attr('href', finalUrl);

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
    $('#print').on('click', function() {

        $.ajax({

            url: "{{ route('cms.go-speed-post-parcel.short-print') }}",

            method: 'GET',

            success: function(response) {

                var iframe = document.createElement('iframe');

                iframe.style.position = 'absolute';

                iframe.style.width = '0';

                iframe.style.height = '0';

                iframe.style.border = 'none';

                document.body.appendChild(iframe);



                iframe.contentDocument.open();

                iframe.contentDocument.write(response.otherPageContent);

                iframe.contentDocument.close();



                iframe.contentWindow.focus();

                iframe.contentWindow.print();



                document.body.removeChild(iframe);

            }

        });

    });
</script>



<script>
    $(document).ready(function() {

        $('#barcode_no').focus();

        $('#barcode_no').on('keypress', function(event) {

            if (event.key === 'Enter') {

                let barcode = $(this).val();

                if (barcode.length > 0) { // Make sure there is data to send

                    $.ajax({

                        url: "{{ route('cms.bag.assignParcel',['id' => $bagId,'service_type'=>$service_type]) }}",

                        type: 'POST',

                        data: {

                            barcode: barcode,

                            _token: '{{ csrf_token() }}',

                        },

                        success: function(response) {

                            // Create the table row

                            var newRow = `<tr>

                                        <td>${response.data.id}</td>

                                        <td>${response.data.barcode_no}</td>

                                        <td>${response.data.pickup_address}</td>

                                        <td>${response.data.pickup_state}</td>

                                        <td>${response.data.pickup_city}</td>

                                        <td>${response.data.pickup_pincode}</td>

                                        <td>${response.data.pickup_mobile}</td>

                                        <td>${response.data.pickup_email}</td>

                                        <td>${response.data.consignee_address}</td>

                                        <td>${response.data.consignee_state}</td>

                                        <td>${response.data.consignee_city}</td>

                                        <td>${response.data.consignee_pincode}</td>

                                        <td>${response.data.consignee_mobile}</td>

                                        <td>${response.data.consignee_email}</td>

                                        <td>${response.data.package_weight}</td>

                                        <td>${response.data.payment_amount}</td>

                                        <td class="text-end">

                                            <div class="dropdown dropdown-action">

                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>

                                                <div class="dropdown-menu dropdown-menu-right">

                                                    <a class="dropdown-item" href="/cms/go-speed-post-parcel/view/${response.data.id}"><i class="fa-regular fa-eye m-r-5"></i> View</a>

                                                    <a class="dropdown-item" href="#" onclick="delete_modal('${response.data.id}')"><i class="fa-regular fa-trash-can m-r-5"></i> Remove</a>

                                                </div>

                                            </div>

                                        </td>

                                    </tr>`;



                            // Append the new row to the table

                            $('table tbody').append(newRow);

                            $('.dataTables_empty').hide();



                        },

                        error: function(xhr, status, error) {
                            var response = JSON.parse(xhr.responseText);
                            if (xhr.status === 404) {
                                $('.barcode_message').text(response.message)
                            }
                        }


                    });

                }

            }

        });



        // $('#barcode_no').on('blur', function() {

        //         setTimeout(() => {

        //             $(this).focus();

        //         }, 10);

        //     });

    });
</script>





<script>
    var datas = <?php echo json_encode($datas); ?>;
</script>



@endpush







@endsection