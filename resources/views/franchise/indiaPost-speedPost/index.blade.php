@extends('franchise.layouts.master')
@section('title') {{\App\Models\Admin::INDIA_POST_SPEED}} Bookings @endsection
@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}

        @if(session('failed_file'))
            <a href="{{ route('franchise.failed-download', session('failed_file')) }}"
               class="btn btn-md  ml-2 text-white" style="background: #ff9b44;margin-left:30px;">
               Download Failed Data
            </a>
        @endif
    </div>
@endif

<style>
.btn {
  padding: 8px 15px;
  background: #007bff;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 4px;
}

/* Dropdown Box */
.dropdowns {
  display: none;
  position: absolute;
  background: white;
  border: 1px solid #ccc;
  border-radius: 4px;
  width: 150px;
  margin-top: 5px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
  z-index: 999;
}

.dropdowns a {
  display: block;
  padding: 10px;
  color: black;
  text-decoration: none;
}

.dropdowns a:hover {
  background: #f1f1f1;
}

/* Pagination Styling */
.pagination {
    margin: 20px 0;
    display: flex;
    justify-content: center;
}

.pagination > li > a,
.pagination > li > span {
    position: relative;
    float: left;
    padding: 8px 16px;
    margin-left: -1px;
    line-height: 1.5;
    color: #007bff;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #dee2e6;
    font-size: 14px;
}

.pagination > .active > a,
.pagination > .active > span,
.pagination > .active > a:hover,
.pagination > .active > span:hover,
.pagination > .active > a:focus,
.pagination > .active > span:focus {
    z-index: 3;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
    cursor: default;
}

.pagination > li:first-child > a,
.pagination > li:first-child > span {
    margin-left: 0;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
}

.pagination > li:last-child > a,
.pagination > li:last-child > span {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}

.pagination > li > a:hover,
.pagination > li > span:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination-info {
    font-size: 14px;
    color: #6c757d;
    margin-right: 15px;
}

.ul{
    display: flex;
    padding: 5px;
}

.ul li{
    margin-left: 25px;
}

.table-responsive {
    min-height: 400px;
}
</style>

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
   {{-- सभी franchises के लिए बटन दिखाएं --}}
@if(request()->query('type') != 'cod')
    <button id="label" class="btn add-btn">Label ▼</button>
    <div id="receiptDropdown" class="dropdowns">
        <a href="#" id="barcodeOption">Barcode</a>
        <a href="#" id="addressOption">Address</a>
    </div>
    <button id="print" style="margin-right: 10px;" class="btn add-btn">Receipt</button>
@else
    {{-- COD केस के लिए --}}
    <button id="label" class="btn add-btn">Label ▼</button>
    <div id="receiptDropdown" class="dropdowns">
        <a href="#" id="barcodeOption">Barcode</a>
        <a href="#" id="addressOption">Address</a>
    </div>
@endif
    
    <!-- Download buttons -->
    <div class="btn-group">
        <a class="btn add-btn" style="margin-right:5px;" 
           href="{{ route('franchise.india-post-speed-post.downloadTableForCreatedTable', [
               'insert_type' => request()->query('insert_type'),
               'fromdate' => request()->query('fromdate'),
               'todate' => request()->query('todate'),
               'searchKey' => request()->query('searchKey'),
               'type' => request()->query('type'),
               'page' => 'all'
           ]) }}">
           Download All
        </a>
        <a class="btn add-btn" style="margin-right:10px;" 
           href="{{ route('franchise.india-post-speed-post.downloadTableForCreatedTable', [
               'insert_type' => request()->query('insert_type'),
               'fromdate' => request()->query('fromdate'),
               'todate' => request()->query('todate'),
               'searchKey' => request()->query('searchKey'),
               'type' => request()->query('type')
           ]) }}">
           Download Current
        </a>
    </div>
</div>
@endpush

<script>
// Dropdown toggle on button click
document.getElementById("label").addEventListener("click", function (e) {
    e.stopPropagation(); 
    let dropdown = document.getElementById("receiptDropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
});

// Close dropdown when clicking outside
document.addEventListener("click", function () {
    document.getElementById("receiptDropdown").style.display = "none";
});
</script>

<!-- Search Filter -->
<form action="{{ route('franchise.india-post-speed-post.index', [
    'insert_type' => request()->query('insert_type'),
    'searchKey' => request()->query('searchKey'),
    'type' => request()->query('type')
]) }}" method="GET">
    <div class="row">
        <!-- Search Key Input -->
        <input type="hidden" name="insert_type" value="{{ request()->query('insert_type') }}">
        <input type="hidden" name="type" value="{{ request()->query('type') }}">
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
                    <input id="date" name="fromdate" class="form-control floating datetimepicker" type="text" value="{{ request('fromdate') }}">
                </div>
                <label class="focus-label">From Date</label>
            </div>
        </div>

        <div class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input id="date" name="todate" class="form-control floating datetimepicker" type="text" value="{{ request('todate') }}">
                </div>
                <label class="focus-label">To Date</label>
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

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <ul class="ul">
                    <li><span class="totalParcels"><b>Total Parcel: {{$totalParcels}}</b></span></li>
                    <li><span class="totalAmount"><b>Total Amount: ₹{{$totalAmount}}</b></span></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <button type="button" class="add-btn allCheck">All Select</button>
                    <span class="text-center date-above-table" style="left: 180px;">{{ now()->format('d-m-Y') }}</span>
                    <span class="text-center date-above-table" style="left: 300px;">{{ request()->query('type') }}</span>

                    <table class="table table-striped custom-table ">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>SN#</th>
                                <th class="text-center">Barcode</th>
                                <th class="text-center"><span>From Name</span></th>
                                <th class="text-center"><span>Addresss</span></th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center"><span>To Name</span></th>
                                <th class="text-center">Addresss</th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Weight</th>
                                <th class="text-center">Amount (₹)</th>
                                <th class="text-center">GST (₹)</th>
                                <th class="text-center">Total (₹)</th>
                                <th class="text-center">Register Fees(₹)</th>
                                <th class="text-end" style="width:2px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $i => $data)
                            @php
                                $serialNumber = ($datas->currentPage() - 1) * $datas->perPage() + $i + 1;
                            @endphp
                            <tr>
                                <img style="display:none" src="data:image/png;base64,{{ $data->barcode_image_src }}" alt="Barcode" style="height:50px;" />
                                <td><input type="checkbox" class="row-check" name="select[]" value="{{$data->id}}"></td>
                                <td>{{ $serialNumber }}</td>
                                <td>{{$data->barcode_no}}</td>
                                <td>{{$data->pickup_name}}</td>
                                <td>{{$data->pickup_address}}</td>
                                <td>{{$data->pickup_state}}</td>
                                <td>{{$data->pickup_city}}</td>
                                <td>{{$data->pickup_pincode}}</td>
                                <td>{{$data->pickup_mobile}}</td>
                                <td>{{$data->consignee_name}}</td>
                                <td>{{$data->consignee_address}}</td>
                                <td>{{$data->consignee_state}}</td>
                                <td>{{$data->consignee_city}}</td>
                                <td>{{$data->consignee_pincode}}</td>
                                <td>{{$data->consignee_mobile}}</td>
                                <td>{{$data->package_weight}}</td>
                                <td>₹{{ number_format($data->payment_amount / 1.18, 2) }}</td>
                                <td>₹{{ number_format($data->payment_amount - ($data->payment_amount / 1.18), 2) }}</td>
                                <td>₹{{$data->payment_amount}}</td>
                                <td>₹{{$data->register_amount}}</td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{route('franchise.india-post-speed-post.edit',$data->id)}}"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item" href="{{route('franchise.india-post-speed-post.view',$data->id)}}"><i class="fa-regular fa-eye m-r-5"></i> View</a>
                                            <a class="dropdown-item" onclick="cancel_modal('{{$data->id}}')"><i class="fa-regular fa-trash-can m-r-5"></i> Cancel</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($datas->hasPages())
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    Showing {{ $datas->firstItem() }} to {{ $datas->lastItem() }} of {{ $datas->total() }} entries
                                </div>
                                <div>
                                    {{ $datas->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
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
                <form action="{{route('franchise.india-post-speed-post.storeByfile')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-files-container">
                        <div class="drag-file-area">
                            <span class="material-icons-outlined upload-icon"> file_upload </span>
                            <h3 class="dynamic-message">Drag & drop any file here</h3>
                            <label style="width: 220px;" class="label">
                                or <span class="browse-files">
                                    <input type="file" class="default-file-input" name="file" />
                                    <span class="browse-files-text">Browse File</span>
                                    <span>From Device</span>
                                </span>
                            </label>
                        </div>
                        <div class="file-block">
                            <div class="file-info">
                                <span class="material-icons-outlined file-icon">Description</span> <span class="file-name"> </span> | <span class="file-size"> </span>
                                <span class="material-icons remove-file-icon">Delete</span>
                            </div>
                        </div>
                        <a href="{{route('franchise.india-post-speed-post.downloadForamt')}}">
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

<!-- Delete Modal -->
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
<!-- /Delete Modal -->

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
    const cancelUrl = "{{ route('franchise.india-post-speed-post.cancel', ['id' => ':id']) }}".replace(':id', id);
    $('#cancel_form').attr('action', cancelUrl);
    $('#cancel_modal').modal('show');
}
</script>

@push('page-javascript')
<script type="text/javascript">
function delete_modal(id) {
    const deleteUrl = "{{ route('franchise.india-post-speed-post.delete', ['id' => ':id']) }}".replace(':id', id);
    $('#delete_button').attr('href', deleteUrl);
    $('#delete_modal').modal('show');
}

// File upload functionality
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

// Print functionality
$('#print').on('click', function() {
    let selectedIds = $('.row-check:checked').map(function() {
        return $(this).val();
    }).get();

    let date = $('#date').val();
    let searchKey = $('#searchKey').val();
    
    // Get current page for pagination context
    let currentPage = new URLSearchParams(window.location.search).get('page') || 1;

    $.ajax({
        url: "{!! route('franchise.india-post-speed-post.shortPrintForCreatedParcel',[
            'insert_type' => request()->query('insert_type'),
            'fromdate'    => request()->query('fromdate'),
            'todate'      => request()->query('todate'),
            'searchKey'   => request()->query('searchKey'),
            'type'        => request()->query('type')
        ]) !!}",
        method: 'GET',
        data: {
            ids: selectedIds,
            date: date,
            searchKey: searchKey,
            page: currentPage
        },
        success: function(response) {
            var iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);

            iframe.onload = function() {
                setTimeout(function() {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    document.body.removeChild(iframe);
                }, 1000);
            };

            iframe.contentDocument.open();
            iframe.contentDocument.write(response.otherPageContent);
            iframe.contentDocument.close();
        }
    });
});

// Barcode option
$('#barcodeOption').on('click', function() {
    let selectedIds = $('.row-check:checked').map(function() {
        return $(this).val();
    }).get();

    let date = $('#date').val();
    let searchKey = $('#searchKey').val();
    let currentPage = new URLSearchParams(window.location.search).get('page') || 1;

    $.ajax({
        url: "{!! route('franchise.india-post-speed-post.shortLabelForCreatedParcel', [
            'insert_type' => request()->query('insert_type'),
            'fromdate'    => request()->query('fromdate'),
            'todate'      => request()->query('todate'),
            'searchKey'   => request()->query('searchKey'),
            'type'        => request()->query('type'),
            'printType'   => 1
        ]) !!}",
        method: 'GET',
        data: {
            ids: selectedIds,
            date: date,
            searchKey: searchKey,
            page: currentPage
        },
        success: function(response) {
            var iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);

            iframe.onload = function() {
                setTimeout(function() {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    document.body.removeChild(iframe);
                }, 1000);
            };

            iframe.contentDocument.open();
            iframe.contentDocument.write(response.otherPageContent);
            iframe.contentDocument.close();
        }
    });
});

// Address option
$('#addressOption').on('click', function() {
    let selectedIds = $('.row-check:checked').map(function() {
        return $(this).val();
    }).get();

    let date = $('#date').val();
    let searchKey = $('#searchKey').val();
    let currentPage = new URLSearchParams(window.location.search).get('page') || 1;

    $.ajax({
        url: "{!! route('franchise.india-post-speed-post.shortLabelForCreatedParcel', [
            'insert_type' => request()->query('insert_type'),
            'fromdate'    => request()->query('fromdate'),
            'todate'      => request()->query('todate'),
            'searchKey'   => request()->query('searchKey'),
            'type'        => request()->query('type'),
            'printType'   => 2
        ]) !!}",
        method: 'GET',
        data: {
            ids: selectedIds,
            date: date,
            searchKey: searchKey,
            page: currentPage
        },
        success: function(response) {
            var iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);

            iframe.onload = function() {
                setTimeout(function() {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    document.body.removeChild(iframe);
                }, 1000);
            };

            iframe.contentDocument.open();
            iframe.contentDocument.write(response.otherPageContent);
            iframe.contentDocument.close();
        }
    });
});

// Barcode scanning
$(document).ready(function() {
    $('#barcode_no').focus();
    $('#barcode_no').on('keypress', function(event) {
        if (event.key === 'Enter') {
            let barcode = $(this).val();
            if (barcode.length > 0) {
                $.ajax({
                    url: "{{ route('franchise.india-post-speed-post.assignBarcode') }}",
                    type: 'POST',
                    data: {
                        barcode: barcode,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.message === "barcode assigned successfully") {
                            window.location.reload();
                        } else {
                            $('#message').text(response.message)
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                    }
                });
            }
        }
    });

    // Checkbox change event with pagination support
    $('.row-check').on('change', function() {
        let selectedIds = $('.row-check:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            $('.totalParcels').html('<span><b>Total Parcels: {{ $totalParcels }}</b></span>');
            $('.totalAmount').html('<span><b>Total Amount: ₹{{ $totalAmount }}</b></span>');
            return;
        }

        $.ajax({
            url: "{{ route('franchise.india-post-speed-post.parcel.count') }}",
            type: 'POST',
            data: {
                id: selectedIds,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.status === "success") {
                    $('.totalParcels').html('<span><b>Total Parcels: ' + response.totalParcels + '</b></span>');
                    $('.totalAmount').html('<span><b>Total Amount: ₹' + response.totalAmount + '</b></span>');
                } else {
                    $('#message').text(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#message').text('Something went wrong. Please try again.');
            }
        });
    });
});

// Select all functionality
let allSelected = false;

$('.allCheck').on('click', function() {
    allSelected = !allSelected;
    $('.row-check').prop('checked', allSelected);
    $(this).text(allSelected ? 'Deselect All' : 'All Select');

    let selectedIds = $('.row-check:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
        $('.totalParcels').html('<span><b>Total Parcels: {{ $totalParcels }}</b></span>');
        $('.totalAmount').html('<span><b>Total Amount: ₹{{ $totalAmount }}</b></span>');
        return;
    }

    $.ajax({
        url: "{{ route('franchise.india-post-speed-post.parcel.count') }}",
        type: 'POST',
        data: {
            id: selectedIds,
            _token: '{{ csrf_token() }}',
        },
        success: function(response) {
            if (response.status === "success") {
                $('.totalParcels').html('<span><b>Total Parcels: ' + response.totalParcels + '</b></span>');
                $('.totalAmount').html('<span><b>Total Amount: ₹' + response.totalAmount + '</b></span>');
            } else {
                $('#message').text(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#message').text('Something went wrong. Please try again.');
        }
    });
});

// Pass data to JavaScript
var datas = <?php echo json_encode($datas->items()); ?>;
</script>
@endpush
@endsection 