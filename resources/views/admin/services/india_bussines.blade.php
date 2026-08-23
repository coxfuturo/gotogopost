@extends('admin.layouts.master')
@section('title')  {{\App\Models\Admin::INDIA_POST_BUSINESS}} @endsection
@section('content')

<!-- Search Filter -->

<form action="{{ route('admin.franchise.service.india-post-business', $franchise->id) }}" method="GET">
    <div class="row">

        <!-- Hidden Filters -->
        <input type="hidden" name="insert_type" value="{{ request('insert_type') }}">
        <input type="hidden" name="type" value="{{ request('type') }}">
        <input type="hidden" name="air_type" value="2">

        <!-- Search Key Input -->
        <div class="col-sm-3">
            <div class="input-block mb-3 form-focus">
                <input type="text" name="searchKey" class="form-control floating input2"
                       value="{{ request('searchKey') }}">
                <label class="focus-label">Search</label>
            </div>
        </div>

        <!-- From Date -->
        <div class="col-sm-3">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input name="fromdate" class="form-control floating datetimepicker"
                           type="text" value="{{ request('fromdate') }}">
                </div>
                <label class="focus-label">From Date</label>
            </div>
        </div>

        <!-- To Date -->
        <div class="col-sm-3">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input name="todate" class="form-control floating datetimepicker"
                           type="text" value="{{ request('todate') }}">
                </div>
                <label class="focus-label">To Date</label>
            </div>
        </div>

        <!-- Search Button -->
        <div class="col-sm-3">
            <button type="submit" class="btn btn-success w-100 ">Search</button>
        </div>

    </div>
</form>


<!-- Search Filter -->

<style>
    .ul{
        display: flex;
        padding: 5px;
    }

    .ul li{
        margin-left: 25px;
    }
</style>


<div class="row">
      <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <ul class="ul">
                    <li><span class="totalParcels"><b>Total Parcel: {{$totalParcels}}</b></span></li>
                    <li><span class="totalAmount"><b>Total Amount: ₹{{$totalAmount}}</b></span></li>
                    <li><span class="codAmount"><b>Total COD Amount: ₹{{$CodtotalAmount}}</b></span></li>
                     <li style="margin: auto;color:#fff;background: green;text-align: center;width: 20%;border-radius: 5px"><h3 style="padding-top:6px">SURFACE</h3></li>
                    <li style="margin-left: auto;">
                    <a href="{{route('admin.franchise.service.india-post-business.air', ['air_type' => 1, 'id'=>$franchise->id, 'type' => request()->query('type')])}}" style="margin-left: 20px;background: #1e3d59" class="btn add-btn" ><i class="la la-arrow-left" style="font-size: 18px"></i>AIR</a></li>
                    
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <!-- <button type="button" class="add-btn allCheck">All Select</button> -->
                    <span class="text-center date-above-table" style="left: 180px;">{{ now()->format('d-m-Y') }}</span>
                    
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <!-- <th>Select</th> -->
                                <th>SN#</th>
                                <th>Parcel</th>
                                <th class="text-center">Barcode</th>
                                <th class="text-center"><span>From Name</span></th>
                                <th class="text-center"><span>Addresss</span></th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <!-- <th class="text-center">Email</th> -->
                                 <th class="text-center">To Name</th>
                                <th class="text-center"> Addresss</th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <!-- <th class="text-center">Email</th> -->
                                <th class="text-center">Weight</th>
                                <th class="text-center">Amount (₹)</th>
                                <th class="text-center">GST (₹)</th>
                                <th class="text-center">Total (₹)</th>
                                <!-- <th class="text-end" style="width:2px"></th> -->
                            </tr>
                        </thead>
                        <tbody>
                           
                            @foreach($datas as $i => $data)
                            <tr>
                                <img style="display:none" src="data:image/png;base64,{{  $data->barcode_image_src }}" alt="Barcode" style="height:50px;" />
                                <!-- <td><input type="checkbox" class="row-check" name="select[]" value="{{$data->id}}"></td> -->
                                <td>{{$i+1}}</td>
                                <td>@if($data->parcel_type == 1)
   Surface
@else
     Air
@endif</td>
                                <td>{{$data->barcode_no}}</td>
                                <td> {{$data->pickup_name}}</td>
                                <td> {{$data->pickup_address}}</td>
                                <td>{{$data->pickup_state}}</td>
                                <td>{{$data->pickup_city}}</td>
                                <td>{{$data->pickup_pincode}}</td>
                                <td>{{$data->pickup_mobile}}</td>
                                <!-- <td>{{$data->pickup_email}}</td> -->
                                 <td>{{$data->consignee_name}}</td>
                                <td>{{$data->consignee_address}}</td>
                                <td>{{$data->consignee_state}}</td>
                                <td>{{$data->consignee_city}}</td>
                                <td>{{$data->consignee_pincode}}</td>
                                <td>{{$data->consignee_mobile}}</td>
                                <!-- <td>{{$data->consignee_email}}</td> -->
                                <td>{{$data->package_weight}}</td>
                               <td>₹{{ number_format($data->payment_amount / 1.18, 2) }}</td>
                                <td>₹{{ number_format($data->payment_amount - ($data->payment_amount / 1.18), 2) }}</td>
                                <td>₹{{$data->payment_amount}}</td>
                                
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
                <form action="{{route('franchise.india-post-business.storeByfile')}}" method="POST" enctype="multipart/form-data">
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
                        <a href="{{route('franchise.india-post-business.downloadForamt')}}">
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

<!-- Cancel Modal -->
<div class="modal custom-modal fade" id="cancel_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Cancel</h3>
                </div>
                <div class="modal-btn delete-action">
                    <form method="POST" id="cancel_form">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea name="cancel_reason" rows="4" cols="50" class="form-control" placeholder="Enter cancellation reason"></textarea>
                            </div>
                            <div class="col-6 mt-3">
                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-secondary cancel-btn">Close</a>
                            </div>
                            <div class="col-6 mt-3">
                                
                                <button type="submit" id="cancel_button" class="btn btn-primary continue-btn">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function cancel_modal(id) {
        const cancelUrl = "{{ route('franchise.india-post-business.cancel', ['id' => ':id']) }}".replace(':id', id);
        $('#cancel_form').attr('action', cancelUrl);
        $('#cancel_modal').modal('show');
    }
</script>

@push('page-javascript')
<script type="text/javascript">
    function delete_modal(id) {
        const deleteUrl = "{{ route('franchise.india-post-business.delete', ['id' => ':id']) }}".replace(':id', id);
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