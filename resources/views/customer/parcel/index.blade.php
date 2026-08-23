@extends('customer.layouts.master')
@section('title') {{\App\Models\Admin::GOTOGO_POST_BUSINESS}} @endsection
@section('content')

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <!-- <button id="print" style="margin-right:10px;" class="btn add-btn">Print</button> -->
    <a class="btn add-btn" style="margin-right:10px;" href="{{ route('customer.parcel.downloadTableForCreatedTable', ['insert_type' => request()->query('insert_type'),'date' => request()->query('date'),'searchKey' => request()->query('searchKey')]) }}">Download</a>
</div>
@endpush

<!-- Search Filter -->

<form action="{{ route('customer.booking.parcel.cod') }}" method="GET">
    <div class="row">
        <!-- Search Key Input -->

        <input type="hidden" name="insert_type" value="{{ request()->query('insert_type') }}">
        <div class="col-sm-3 col-md-3">
            <div class="input-block mb-3 form-focus">
                <input type="text" id="searchKey" name="searchKey" class="form-control floating input2" value="{{ request('searchKey') }}">
                <label class="focus-label">Search</label>
            </div>
        </div>

        <!-- To Date Input -->
        <div class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input id="date" name="date" class="form-control floating datetimepicker" type="text" value="{{ request('date') }}">
                </div>
                <label class="focus-label">Date</label>
            </div>
        </div>

        <!-- Search Button -->
        <div class="col-sm-6 col-md-1">
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Search</button>
            </div>
        </div>
    </div>
</form>

<!-- Search Filter -->

<style>
    /* table setting end */
</style>


<div class="row">
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <span class="text-center date-above-table">{{ now()->format('d-m-Y') }}</span>
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
                                            
                                            <a class="dropdown-item" href="{{route('customer.parcel.view',$data->id)}}"><i class="fa-regular fa-eye m-r-5"></i> view</a>
                                            
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