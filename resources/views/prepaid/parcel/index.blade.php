@extends('prepaid.layouts.master')
@section('title') {{\App\Models\Admin::INDIA_POST_SPEED}} Bookings @endsection
@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        @if(session('failed_file'))
            <a href="{{ route('franchise.failed-download', session('failed_file')) }}"
               class="btn btn-md ml-2 text-white" style="background: #ff9b44;margin-left:30px;">
               Download Failed Data
            </a>
        @endif
    </div>
@endif


<!-- jQuery First - सबसे जरूरी -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Datepicker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">

<!-- Bootstrap Datepicker JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

<!-- Moment.js (Date parsing के लिए) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>



<style>
.btn {
    padding: 8px 15px;
    background: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}
.dropdown {
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
.dropdown a {
    display: block;
    padding: 10px;
    color: black;
    text-decoration: none;
}
.dropdown a:hover {
    background: #f1f1f1;
}
.ul {
    display: flex;
    padding: 5px;
}
.ul li {
    margin-left: 25px;
}

/* =========================================== */
/* ✅ FRONTEND STYLES - BILKUL SAME */
/* =========================================== */
.sticker {
    width: 5cm;
    height: 3.8cm;
    border: 2px solid rgb(167, 20, 20);
    font-family: Arial, sans-serif;
    font-size: 10px;
    padding: 5px;
    margin: 0 auto 10px auto;
    page-break-after: always;
    page-break-inside: avoid;
    box-sizing: border-box;
}
.title-p {
    text-align: center;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    margin-top: 5px;
}
.title-span {
    border-bottom: 1.5px solid black;
}
.barcode img {
    width: 160px;
    height: 60px;
}
.tracking-number {
    text-align: center;
    font-weight: bold;
    margin: 5px 0;
}
</style>

<div class="col-auto float-end ms-auto no-print">
    <button id="print" style="margin-right: 10px;" class="btn add-btn">Receipt</button>
    
</div>

<!-- Search Filter -->
<form action="{{ route('prepaid.booking.index',['type' => request()->route('type')] ) }}" method="GET" class="no-print">
    <input type="hidden" name="insert_type" value="{{ request()->query('insert_type') }}">
    <input type="hidden" name="type" value="{{ request('type') }}">   <!-- यहाँ सही जगह पर -->
    
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <div class="input-block mb-3 form-focus">
                <input type="text" id="searchKey" name="searchKey" class="form-control floating input2" value="{{ request('searchKey') }}">
                <label class="focus-label">Search</label>
            </div>
        </div>
        
        <div class="col-sm-3 col-md-3">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input id="fromdate" name="fromdate" class="form-control floating datetimepicker" type="text" value="{{ request('fromdate') }}">
                </div>
                <label class="focus-label">From Date</label>
            </div>
        </div>
        
        <div class="col-sm-3 col-md-3">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input id="todate" name="todate" class="form-control floating datetimepicker" type="text" value="{{ request('todate') }}">
                </div>
                <label class="focus-label">To Date</label>
            </div>
        </div>
        
        <div class="col-sm-6 col-md-1">
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Search</button>
            </div>
        </div>
    </div>
</form>

<div class="row no-print">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <ul class="ul">
                    <li><span class="totalParcels"><b>Total Booking: {{$count}}</b></span></li>
                    <li><span class="totalAmount"><b>Total Amount: ₹{{$total_amount}}</b></span></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="mb-3">
                        <button type="button" class="btn add-btn" id="allSelectBtn">All Select</button>
                        <button type="button" class="btn add-btn" id="deselectAllBtn" style="display:none;">Deselect All</button>
                    </div>
                    
                    <table class="table table-striped custom-table " id="bookingTable">
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
                            @foreach($datas as $data)
    @php
        $serial = ($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration;
    @endphp
                            <tr>
                                 <td>
            <input type="checkbox" class="row-check" name="select[]" value="{{ $data->id }}" data-id="{{ $data->id }}">
                                </td>
                                 <td>{{ $serial }}</td> 
                                <td>{{ $data->barcode_no }}</td>
                                <td>{{ $data->pickup_name }}</td>
                                <td>{{ $data->pickup_address }}</td>
                                <td>{{ $data->pickup_state }}</td>
                                <td>{{ $data->pickup_city }}</td>
                                <td>{{ $data->pickup_pincode }}</td>
                                <td>{{ $data->pickup_mobile }}</td>
                                <td>{{ $data->consignee_name }}</td>
                                <td>{{ $data->consignee_address }}</td>
                                <td>{{ $data->consignee_state }}</td>
                                <td>{{ $data->consignee_city }}</td>
                                <td>{{ $data->consignee_pincode }}</td>
                                <td>{{ $data->consignee_mobile }}</td>
                                <td>{{ $data->package_weight }}</td>
                                <td>₹{{ number_format($data->payment_amount / 1.18, 2) }}</td>
                                <td>₹{{ number_format($data->payment_amount - ($data->payment_amount / 1.18), 2) }}</td>
                                <td>₹{{ $data->payment_amount }}</td>
                                <td>₹{{ $data->register_amount }}</td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('franchise.india-post-speed-post.edit', $data->id) }}">
                                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('franchise.india-post-speed-post.view', $data->id) }}">
                                                <i class="fa-regular fa-eye m-r-5"></i> view
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="cancel_modal('{{ $data->id }}')">
                                                <i class="fa-regular fa-trash-can m-r-5"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    
                    </table> {{-- Table closing tag के बाद --}}

<!-- ✅ PAGINATION LINKS -->
<div class="row mt-4">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite">
            Showing {{ $datas->firstItem() ?? 0 }} to {{ $datas->lastItem() ?? 0 }} of {{ $datas->total() }} entries
        </div>
    </div>
    <div class="col-sm-12 col-md-7">
        <div class="dataTables_paginate paging_simple_numbers">
            {{ $datas->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- ✅ PER PAGE DROPDOWN (Optional) -->
<div class="row mb-3 no-print">
    <div class="col-md-12">
        <form action="{{ route('prepaid.booking.index', ['type' => request()->route('type')]) }}" method="GET" class="form-inline">
            <input type="hidden" name="searchKey" value="{{ request('searchKey') }}">
            <input type="hidden" name="fromdate" value="{{ request('fromdate') }}">
            <input type="hidden" name="todate" value="{{ request('todate') }}">
            <input type="hidden" name="type" value="{{ request('type') }}">
            
            <label class="me-2">Show</label>
            <select name="per_page" class="form-control w-auto d-inline-block" onchange="this.form.submit()">
                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
            </select>
            <label class="ms-2">entries</label>
        </form>
    </div>
</div>
                    
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>





<script>
$(document).ready(function() {
    // datetimepicker initialize
    if ($('.datetimepicker').length > 0) {
        $('.datetimepicker').datetimepicker({
            format: 'DD/MM/YYYY',
            ignoreReadonly: true,
            widgetPositioning: {
                horizontal: 'left',
                vertical: 'bottom'
            }
        });
    }
});
</script>





<!-- =========================================== -->
<!-- ✅ PRINT TEMPLATE - 3x3 INCH WITH TOP MARGIN -->
<!-- =========================================== -->
<div id="printTemplate" style="display: none;">
    @foreach($datas as $parcel)
    @php
        $franchise = App\Models\Franchise::find($parcel->franchise_id);
    @endphp
    <div class="print-item" data-id="{{ $parcel->id }}">
        <style>
            /* ✅ PRINT-ONLY STYLES - WITH TOP MARGIN */
      
          
            }
        </style>
        
       <div class="label">

  <!-- Header -->
  <table style="margin-top:5px">
    <tr>
      <th >
        <div class="qr"><img src="{{asset('website/images/logonewtransparent.png')}}"></div>
        
      </th>
      <th style="text-align:center; font-size:8px;text-transform: uppercase;">
           <span style="margin-left:50px;letter-spacing: 2px;font-size:12px">ALL INDIA POST</span><br>
        <span style="margin-left:40px;font-size: 9px">
          
         @switch($type)
            @case(5)
        <!-- {{ \App\Models\Admin::INDIA_POST_SPEED }} -->
          SPEED POST-INLAND DOCUMENT
         @break

          @default
        {{ \App\Models\Admin::INDIA_POST_BUSINESS }}
         @endswitch
        <br>
      @if ($parcel->status == 2)
       <span style="color: #de0114;">Cancel Parcel</span>
      @endif
  </span>
        <div class="barcode">
          <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}">
          <div class="barcode-number">{{ $parcel->barcode_no }}</div>
        </div>
  </th>
    </tr>
    
    <!-- <tr>
      <td colspan="2">
        <div class="barcode">
          <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}">
          <div class="barcode-number">{{ $parcel->barcode_no }}</div>
        </div>
      </td>
    </tr> -->

  </table>
  <!-- Sections -->
  <!-- <div class="section">Dely Office & Pincode: Air Force SO(122001) <span style="font-size: 8px;float:right"><b>SNo. {{ str_pad($parcel->id, 6, '0', STR_PAD_LEFT) }}</b></span></div> -->
  <div class="section" style="margin-top:5px;padding-left:10px">Booking Customer ID: 1580694767, <span style="text-transform: uppercase;">{{ $franchise->city }}</span> PIN: (110046)
<br>
      Franchise ID: {{$franchise_no ?? NULL}}, {{ $parcel->created_at->format('d-m-y') }}, {{ $parcel->created_at->format('H:i:s') }}
<br>GST No. {{ $franchise->gst_number }} <span style="float:right;margin-right: 20px;font-weight: 700">
@if($parcel->parcel_type == 1)
   Surface
@else
     Air
@endif
</span></div>
  <div class="section" style="border-top: none; border-bottom: none;padding-left:10px">
    Weight (gms): {{ $parcel->package_weight ?? 0 }}
    L: {{ $type == 6 ? ($parcel->package_length ?? 0) : 0 }}
    B: {{ $type == 6 ? ($parcel->package_width ?? 0) : 0 }}
    H: {{ $type == 6 ? ($parcel->package_height ?? 0) : 0 }}
    @if($type == 6) (Vol.Wt: 0.00) @endif
<br>

     Amount/Paid:
{{ $parcel->totalOtherAmount ?? $parcel->payment_amount }}
(Tax:
@php
    $amount = $parcel->totalOtherAmount ?? $parcel->payment_amount;
    $tax = $amount - ($amount / 1.18);
@endphp
{{ number_format($tax, 2) }} (CGST- SGST-))<br>
Mode of Payment: CO 
Contact ID:  41184049</div>

  <!-- Parties -->
  <table class="parties">
    <tr>
      <td style="text-align:center"><b>Sender</b></td>
      <td style="text-align:center"><b>Receiver</b></td>
    </tr>
    <tr>
      <td style="padding-left:10px">
        
        @if($parcel->pickup_gst_number) <span style="text-transform: uppercase;"><b>{{ $parcel->pickup_gst_number ?? NULL }}</br></b></span> @endif
        {{ $parcel->pickup_name }}-<br>
        Mobile No.{{ $parcel->pickup_mobile }}<br>
        {{ $parcel->pickup_address }}<br>
        {{ $parcel->pickup_city }}<br>
        {{ $parcel->pickup_state }}-{{ $parcel->pickup_pincode }}
      </td>
      <td style="padding-left:10px">
        {{ $parcel->consignee_name }}<br>
        Mobile No.{{ $parcel->consignee_mobile }}<br>
        {{ $parcel->consignee_address }}<br>
        {{ $parcel->consignee_city }}<br>
        {{ $parcel->consignee_state }}-{{ $parcel->consignee_pincode }}
      </td>
    </tr>
  </table>

  <!-- Footer -->
  <div class="section" style="border-top:none;padding-left:10px">
    Track on www.gotogopost.com Dial 18001231617 <br>
    In case of any complaint, please visit: www.gotogopost.com <br>
    Go Green!!! Opt for eReceipts<br>
    <b>This Is System Generated Document, No Manual Signature Required</b><br>
   </div>

   <div style="display: flex; align-items: center; justify-content: center; font-size: 8px; margin-top:3px;">
  <span>POWERED BY:</span>
  <img src="{{asset('website/images/logonewtransparent.png')}}" 
       style="width: 15px; height: 15px; margin: 0 5px;" alt="logo">
  <span>GOTOGOPOST</span>
</div>

  </div>

    

</div>
    @endforeach
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
                <form action="{{ route('franchise.india-post-speed-post.storeByfile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-files-container">
                        <div class="drag-file-area">
                            <span class="material-icons-outlined upload-icon">file_upload</span>
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
                                <span class="material-icons-outlined file-icon">description</span> 
                                <span class="file-name"></span> | 
                                <span class="file-size"></span>
                                <span class="material-icons remove-file-icon">delete</span>
                            </div>
                        </div>
                        <a href="{{ route('franchise.india-post-speed-post.downloadForamt') }}">
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

<!-- Cancel Modal -->
<div class="modal custom-modal fade" id="cancel_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Cancel Booking</h3>
                </div>
                <div class="modal-btn delete-action">
                    <form method="POST" id="cancel_form">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea name="cancel_reason" rows="4" cols="50" class="form-control" placeholder="Enter cancellation reason" required></textarea>
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

<!-- ✅ PRINT IFRAME - SAME PAGE PRINT -->
<iframe id="printFrame" style="position: absolute; width: 0; height: 0; border: none; display: none;"></iframe>

@push('page-javascript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // ===========================================
    // ✅ ALL SELECT BUTTON
    // ===========================================
    $('#allSelectBtn').on('click', function() {
        $('.row-check').prop('checked', true);
        $('#allSelectBtn').hide();
        $('#deselectAllBtn').show();
    });

    $('#deselectAllBtn').on('click', function() {
        $('.row-check').prop('checked', false);
        $('#deselectAllBtn').hide();
        $('#allSelectBtn').show();
    });

    // ===========================================
    // ✅ CHECKBOX CHANGE HANDLER
    // ===========================================
    $(document).on('change', '.row-check', function() {
        let total = $('.row-check').length;
        let checked = $('.row-check:checked').length;
        if (checked === total && total > 0) {
            $('#allSelectBtn').hide();
            $('#deselectAllBtn').show();
        } else {
            $('#deselectAllBtn').hide();
            $('#allSelectBtn').show();
        }
    });

    // ===========================================
    // ✅ PRINT BUTTON - 3x3 INCH WITH TOP MARGIN
    // ===========================================
    $('#print').on('click', function(e) {
        e.preventDefault();
        
        let selectedIds = $('.row-check:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Selection',
                text: 'Please select at least one record to print!',
                confirmButtonColor: '#007bff'
            });
            return;
        }
        
        Swal.fire({
            title: 'Preparing Print...',
            text: 'Please wait while we generate receipt.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        let printContent = '';
        selectedIds.forEach(function(id) {
            let item = $(`.print-item[data-id="${id}"]`).clone();
            if (item.length) {
               printContent += '<div class="page-wrapper">' + item.html() + '</div>';

            }
        });
        
        Swal.close();
        
        // ✅ SAME PAGE PRINT - WITH TOP MARGIN
        let printFrame = document.getElementById('printFrame');
        printFrame.style.display = 'block';
        
        let frameDoc = printFrame.contentWindow.document;
        frameDoc.open();
        frameDoc.write(`
<!DOCTYPE html>
<html>
<head>
<title>India Post Speed - 3x3 Inch Label</title>

<style>
@page {
    size: 3in 3in;
    margin: 0;
}

body {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: #fff;
}




.page-wrapper {
    width: 100%;
    height: 100vh;
    display: flex;
    align-items: center;      /* vertical center */
    justify-content: center;  /* horizontal center */
    page-break-after: always;
    break-after: page;
}
/* ✅ HAR LABEL ALAG PAGE */
.label {
    width: 3in;
    height: 3in;
    box-sizing: border-box;
    overflow: hidden;
    page-break-after: always;
    break-after: page;
}

/* REMOVE FLEX — VERY IMPORTANT */
/* body flex removed */

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 7px;
}

th, td {
    padding: 2px;
    vertical-align: top;
}

.qr img {
    width: 50px;
    height: 40px;
    display: block;
    margin: auto;
    margin-top: 15px;
}

.barcode img {
    width: 150px;
    height: 20px;
    display: block;
    margin-left: auto;
    margin-right: 10px;
}

.barcode-number {
    font-weight: bold;
    font-size: 10px;
    letter-spacing: 2px;
    margin-top: 1px;
    margin-right: 30px;
    float: right;
}

.section {
    border: 1px solid #000;
    padding: 1px 2px;
    font-size: 7px;
    line-height: 1.2;
}

.parties td {
    border: 1px solid #000;
    width: 50%;
    line-height: 1.3;
}

.footer {
    font-size: 6.8px;
    text-align: center;
    line-height: 1.3;
    margin-top: 2px;
}
</style>

</head>
<body>
${printContent}
</body>
</html>
`);

        frameDoc.close();
        
        setTimeout(function() {
            printFrame.contentWindow.focus();
            printFrame.contentWindow.print();
            printFrame.style.display = 'none';
        }, 1000);
    });

    // ===========================================
    // ✅ CANCEL MODAL FUNCTION
    // ===========================================
    window.cancel_modal = function(id) {
        if (!id) {
            alert('Invalid ID');
            return false;
        }
        let cancelUrl = "{{ route('franchise.india-post-speed-post.cancel', ['id' => ':id']) }}".replace(':id', id);
        $('#cancel_form').attr('action', cancelUrl);
        $('#cancel_modal').modal('show');
    };
});
</script>

<script>
    var datas = <?php echo json_encode($datas); ?>;
</script>


@endpush
@endsection