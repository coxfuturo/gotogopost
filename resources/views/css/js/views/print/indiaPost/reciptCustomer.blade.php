<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
 <style>
    @page {
  size: 10.8cm 10.8cm; /* width x height ko swap kar do */
  margin: 0;
}


body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      font-size: 10px;
    }

.label {
  width: 9.8cm;
  height: 9.8cm;
    box-sizing: border-box;
      overflow: hidden;
      page-break-after: avoid;
  margin: auto; /* Helps ensure centering in print */
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

    .logo img {
      width: 100%;
      height: 30px;
      display: block;
      margin-left: auto;
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
      margin-left: 50px;
      margin-right: 20px;
    }

    .barcode-number {
      font-weight: bold;
      font-size: 10px;
      letter-spacing: 2px;
      /* text-align: center; */
      margin-top: 1px;
       margin-left: 15px;
      /* float: right;  */
    }

    .section {
      border: 1px solid #000;
      /* border-top: none; */
      padding: 1px 2px;
      font-size: 7px;
      line-height: 1.2;
    }

    .section:first-of-type {
      border-top: 1px solid #000;
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
      margin-top:2px;
    }

  </style>
</head>
<body>

<div class="label">

  <!-- Header -->
  <table style="margin-top:5px">
    <tr>
      <th >
        <div class="qr"><img src="{{asset('website/images/logonewtransparent.png')}}"></div>
        
      </th>
      <th style="text-align:center; font-size:8px;text-transform: uppercase;">
           <span style="margin-left:50px;letter-spacing: 2px;font-size:12px">INDIA POST</span><br>
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
  <!-- <div class="section" style="margin-top:10px;">Dely Office & Pincode: Air Force SO(122001)  <span style="font-size: 10px;float:right"><b>SNo. {{ str_pad($parcel->id, 6, '0', STR_PAD_LEFT) }}</b></span></div> -->
  <div class="section" style="margin-top:5px;">Booking Customer ID: 1698669308, <span style="text-transform: uppercase;">{{ $franchise->city }}</span> PIN: ({{ $franchise->pincode }})<br>
      Franchise ID: {{$franchise_no ?? NULL}}, {{ $parcel->created_at->format('d-m-y') }}, {{ $parcel->created_at->format('H:i:s') }}
<br>GST No. 09AALCG1559N1ZY</div>
  <div class="section" style="border-top: none; border-bottom: none;">
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
Contact ID:  41506299</div>

  <!-- Parties -->
  <table class="parties">
    <tr>
      <td style="text-align:center"><b>Sender</b></td>
      <td style="text-align:center"><b>Receiver</b></td>
    </tr>
    <tr>
      <td>
        
        @if($parcel->pickup_gst_number) <span style="text-transform: uppercase;"><b>{{ $parcel->pickup_gst_number ?? NULL }}</br></b></span> @endif
        {{ $parcel->pickup_name }}-<br>
        Mobile No.{{ $parcel->pickup_mobile }}<br>
        {{ $parcel->pickup_address }}<br>
        {{ $parcel->pickup_city }}<br>
        {{ $parcel->pickup_state }}-{{ $parcel->pickup_pincode }}
      </td>
      <td>
        {{ $parcel->consignee_name }}<br>
        Mobile No.{{ $parcel->consignee_mobile }}<br>
        {{ $parcel->consignee_address }}<br>
        {{ $parcel->consignee_city }}<br>
        {{ $parcel->consignee_state }}-{{ $parcel->consignee_pincode }}
      </td>
    </tr>
  </table>

  <!-- Footer -->
  <div class="section" style="border-top:none;">
    Track on www.gotogopost.com OR www.indiapost.gov.in Dial 18001231617 OR <br> Dial 18002666868<br>
    In case of any complaint, please visit: www.gotogopost.com OR https://crm.indiapost.gov.in/customer<br>
    Go Green!!! Opt for eReceipts<br>
    <b>This Is System Generated Document, No Manual Signature Required</b><br>
   </div>

   <div style="display: flex; align-items: center; justify-content: center; font-size: 8px; margin-top:3px;margin-left:100px">
  <span>POWERED BY:</span>
  <img src="{{asset('website/images/logonewtransparent.png')}}" 
       style="width: 15px; height: 15px; margin: 0 5px;" alt="logo">
  <span>GOTOGOPOST</span>
</div>

  </div>

</div>

</body>
</html>
