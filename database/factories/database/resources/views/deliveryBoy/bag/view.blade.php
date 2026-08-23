@extends('deliveryBoy.layouts.master')

@section('title') {{$title}} @endsection

@section('content')



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

                    <span class="text-center date-above-table">{{ now()->format('d-m-Y') }}</span>
                    <span class="text-center code-above-table"> Bag ID : {{$bagDetails->bag_id}}</span>

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

                                <th class="text-end" style="width:2px"></th>

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

                                <td class="text-end">

                                    <div class="dropdown dropdown-action">

                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if($service_type == 1)
                                            <a class="dropdown-item" href="{{ route('deliveryBoy.go-speed-post-parcel.view', $data->id) }}">
                                                <i class="fa-regular fa-eye m-r-5"></i> View
                                            </a>
                                            @elseif($service_type == 3)
                                            <a class="dropdown-item" href="{{ route('deliveryBoy.go-business-parcel.view', $data->id) }}">
                                                <i class="fa-regular fa-eye m-r-5"></i> View
                                            </a>
                                            @elseif($service_type == 4)
                                            <a class="dropdown-item" href="{{ route('deliveryBoy.go-registered.view', $data->id) }}">
                                                <i class="fa-regular fa-eye m-r-5"></i> View
                                            </a>
                                            @elseif($service_type == 5)
                                            <a class="dropdown-item" href="{{ route('deliveryBoy.india-post-speed-post.view', $data->id) }}">
                                                <i class="fa-regular fa-eye m-r-5"></i> View
                                            </a>
                                            @elseif($service_type == 6)
                                            <a class="dropdown-item" href="{{ route('deliveryBoy.india-post-business.view', $data->id) }}">
                                                <i class="fa-regular fa-eye m-r-5"></i> View
                                            </a>
                                            @elseif($service_type == 7)
                                            <a class="dropdown-item" href="{{ route('deliveryBoy.india-post-registered.view', $data->id) }}">
                                                <i class="fa-regular fa-eye m-r-5"></i> View
                                            </a>
                                            @endif

                                            <a class="dropdown-item" href="#" onclick="delete_modal('{{ $data->id }}')">
                                                <i class="fa-regular fa-trash-can m-r-5"></i> Remove
                                            </a>
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