<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    /* ==== PRINT SETTINGS ==== */
    @page {
      size: 9.8cm 7.3cm; /* Print hamesha 4 x 3 (landscape) niklega */
      margin: 0;
    }

    /* ===== COMMON STYLES ===== */
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      font-size: 7px;
    }

    .label {
      box-sizing: border-box;
      overflow: hidden;
      page-break-after: always;
      padding: 2px 3px;
      display: flex;
      flex-direction: column;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 7px;
    }

    th, td {
      padding: 2px;
      vertical-align: top;
    }

    .logo img { width: 42px; height: 20px; display: block; margin: auto; }
    .qr img { width: 35px; height: 35px; display: block; margin: auto; }
    .barcode img { width: 130px; height: 12px; display: block; margin: auto; }
    .barcode-number {
      font-weight: bold;
      font-size: 7px;
      letter-spacing: 2px;
      text-align: center;
      margin-top: 1px;
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
      margin-top:5px;
    }

    /* ==== SCREEN LAYOUT ==== */
    @media screen {
      .label {
        width: 7.3cm;   /* portrait for screen (3 x 4 look) */
        height: 9.8cm;
        border: 1px dashed red; /* sirf testing ke liye border */
      }
    }

    /* ==== PRINT LAYOUT ==== */
    @media print {
      .label {
        width: 9.8cm;   /* landscape for print (4 x 3) */
        height: 7.3cm;
      }
    }
  </style>
</head>
<body>
@php
    $chunks = $data->chunk(1);
@endphp

@foreach($chunks as $page)
@foreach($page as $parcel)
<div class="label">

  <!-- Header -->
  <table>
    <tr>
      <th rowspan="2" style="width:15%"><div class="qr"><img src="{{asset('admin/assets/img/qr.png')}}"></div></th>
      <th style="text-align:center; font-size:8px; width:70%">Inland Speed Post<br>DropOff</th>
      <th style="width:15%"><div class="logo"><img src="{{asset('admin/assets/img/India_Post_Logo.webp')}}"></div></th>
    </tr>
    <tr>
      <td colspan="2">
        <div class="barcode">
          <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}">
          <div class="barcode-number">{{ $parcel->barcode_no }}</div>
        </div>
      </td>
    </tr>
  </table>

  <!-- Sections -->
  <div class="section">Dely Office & Pincode: Air Force SO(122001)</div>
  <div class="section" style="margin-top:5px;">Booking Office: Noida BNPL SP Hub ({{ $linkDetail->bnpl_no }})<br>
      Counter No. 000{{$parcel->id}}, {{ $parcel->created_at->format('d-m-y') }}, {{ $parcel->created_at->format('H:i:s') }}
<br>GST No.</div>
  <div class="section" style="border-top: none;border-bottom:none;">Weight(gms):20.00 L:0 B:0 H:0 (Vol.Wt:0.00)<br>
      Amount/Paid:17.70 (Tax:2.70 (CGST-1.349565 SGST-1.349565))<br>
      ModeofPayment: CO Customer ID: 000{{$parcel->id}}</div>

  <!-- Parties -->
  <table class="parties">
    <tr>
      <td style="text-align:center"><b>Sender</b></td>
      <td style="text-align:center"><b>Receiver</b></td>
    </tr>
    <tr>
      <td>
        40324379<br>
        {{ $parcel->pickup_name }}-<br>
        Mobile No.{{ $parcel->pickup_mobile }}<br>
        {{ $parcel->pickup_address }}<br>
        {{ $parcel->pickup_city }}<br>
        {{ $parcel->pickup_state }}-{{ $parcel->pickup_pincode }}
      </td>
      <td>
        {{ $parcel->consignee_name }}<br>
        Digiplin-<br>
        Mobile No.{{ $parcel->consignee_mobile }}<br>
        {{ $parcel->consignee_address }}<br>
        {{ $parcel->consignee_city }}<br>
        {{ $parcel->consignee_state }}-{{ $parcel->consignee_pincode }}
      </td>
    </tr>
  </table>

  <!-- Footer -->
  <div class="footer">
    Track on www.indiapost.gov.in OR Dial 18002666868<br>
    In case of any complaint, please visit: https://crm.indiapost.gov.in/customer<br>
    Go Green!!! Opt for eReceipts, ePOD<br>
    This is system generated document, no manual signature required<br>
    28-08-2025 12:59:46<br>

   <div style="display: flex; align-items: center; justify-content: center; font-size: 10px; margin-top:5px;">
  <span>POWERED BY:</span>
  <img src="{{asset('website/images/logonewtransparent.png')}}" 
       style="width: 15px; height: 15px; margin: 0 5px;" alt="logo">
  <span>GOTOGOPOST</span>
</div>

  </div>

</div>
@endforeach
@endforeach
</body>
</html>
