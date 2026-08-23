<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    @page {
  size: 9.8cm 7.3cm; /* width x height ko swap kar do */
  margin: 0;
}


body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      font-size: 10px;
    }

.label {
  width: 7.3cm;
  height: 7.3cm;
    box-sizing: border-box;
      overflow: hidden;
      page-break-after: always;
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
    }

    .barcode img {
      width: 150px;
      height: 15px;
      display: block;
      margin-left: auto;
      margin-right: 10px;
    }

    .barcode-number {
      font-weight: bold;
      font-size: 10px;
      letter-spacing: 2px;
      /* text-align: center; */
      margin-top: 1px;
       margin-right: 30px;
      float: right; 
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
@php
    $chunks = $data->chunk(1);
@endphp

@foreach($chunks as $page)
@foreach($page as $parcel)
<div class="label">

  <!-- Header -->
  <table style="margin-top:10px">
    <tr>
      <th rowspan="2" ><div class="qr"><img src="{{asset('website/images/logonewtransparent.png')}}"></div></span></th>
      <th style="text-align:center; font-size:8px;">
         @switch($type)
            @case(5)
        {{ \App\Models\Admin::INDIA_POST_SPEED }}
        @break

    @default
        {{ \App\Models\Admin::INDIA_POST_BUSINESS }}
@endswitch
        <br>
     @if ($parcel->status == 2)
    <span style="color: #de0114;">Cancel Parcel</span>
@else
    <span>DropOff</span>
@endif

     
      </th>
      <th ><div class="logo" style="width:100%"><img src="{{asset('admin/assets/img/India_Post_Logo.jpg')}}" style="float:right"></div></th>
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
  @php
     $franchise = NULL;
      $franchises = NULL;
  @endphp
  <!-- Sections -->
  <div class="section">Dely Office & Pincode: Air Force SO(122001) <span style="font-size: 8px;float:right"><b>SNo. {{ str_pad($parcel->id, 6, '0', STR_PAD_LEFT) }}</b></span></div>
  <div class="section" style="margin-top:5px;">Booking Office: Noida BNPL SP Hub (
   @if($franchise)
    {{ $franchise->pincode }}
   @endif
   @if($franchises)
    {{ $franchises->franchise_pincode[0] ?? '' }}
    @endif

    )
<br>
      Counter No. 0, {{ $parcel->created_at->format('d-m-y') }}, {{ $parcel->created_at->format('H:i:s') }}
<br>GST No. 09AALCG1559N1ZY</div>
  <div class="section" style="border-top: none; border-bottom: none;">
    Weight (gms): {{ $parcel->package_weight ?? 0 }}
    L: {{ $type == 6 ? ($parcel->package_length ?? 0) : 0 }}
    B: {{ $type == 6 ? ($parcel->package_width ?? 0) : 0 }}
    H: {{ $type == 6 ? ($parcel->package_height ?? 0) : 0 }}
    (Vol.Wt: 0.00)
<br>

     Amount/Paid:
{{ $parcel->totalOtherAmount ?? $parcel->payment_amount }}
(Tax:
@php
    $amount = $parcel->totalOtherAmount ?? $parcel->payment_amount;
    $tax = $amount - ($amount / 1.18);
@endphp
{{ number_format($tax, 2) }} (CGST-1.349565 SGST-1.349565))<br>
Mode of Payment: CO 
Customer ID: 1698669308</div>

  <!-- Parties -->
  <table class="parties">
    <tr>
      <td style="text-align:center"><b>Sender</b></td>
      <td style="text-align:center"><b>Receiver</b></td>
    </tr>
    <tr>
      <td>
        1698669308<br>
        {{ $parcel->pickup_name }}-<br>
        Mobile No.18001231617<br>
        <!-- {{ $parcel->pickup_address }}<br> -->
        {{ $parcel->pickup_city }}<br>
        {{ $parcel->pickup_state }}-{{ $parcel->pickup_pincode }}
      </td>
      <td>
        {{ $parcel->consignee_name }}<br>
        Digiplin-<br>
        Mobile No.{{ $parcel->consignee_mobile }}<br>
        <!-- {{ $parcel->consignee_address }}<br> -->
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
    {{ $parcel->created_at->format('d-m-y') }} {{ $parcel->created_at->format('H:i:s') }}<br>

   <div style="display: flex; align-items: center; justify-content: center; font-size: 8px; margin-top:3px;">
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
