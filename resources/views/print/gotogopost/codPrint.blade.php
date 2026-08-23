<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>XpressBees Shipping Label</title>
  <style>
    @page {
      size: 9.8cm 7.3cm;
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
      height: 7.3cm;
      border: 2px solid #000;
      box-sizing: border-box;
      overflow: hidden;
      page-break-after: always;
      padding: 4px;
    }

    .row {
      display: flex;
      justify-content: space-between;
    }

    .col-left {
      width: 59%;
    }

    .col-right {
      width: 39%;
      border-left: 2px solid black;
      padding-left: 5px;
      box-sizing: border-box;
    }
.col-rights {
      width: 49%;
      border-left: 2px solid black;
      padding-left: 5px;
      box-sizing: border-box;
    }

    .col-lefts {
      width: 49%;
      border-left: 2px solid black;
      padding-left: 5px;
      box-sizing: border-box;
    }

    .black-bar {
      background-color: black;
      color: white;
      font-weight: bold;
      padding: 4px;
      font-size: 10px;
      margin-bottom: 4px;
      text-align: center;
    }

    .section {
      margin-top: 5px;
      border-top: 1px solid #000;
      padding-top: 4px;
    }

    .barcode img {
      width: 100%;
      height: 20px;
      display: block;
      margin: auto;
    }

    .barcode-number {
      font-weight: bold;
      font-size: 10px;
      letter-spacing: 2px;
      text-align: center;
      
    }

    .qrcode img {
      width: 80px;
      height: 50px;
      margin: auto;
      display: block;
      margin-top: 10px;
      text-align:center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 9px;
      margin-top: 2px;
      border-top: 2px solid black;
    }

    th, td {
      padding: 2px;
      text-align: left;
      /* line-height: 10px; */
      font-size: 8px;
    }

    .bold {
      font-weight: bold;
    }

    .line-heights {
      line-height: 12px;
    }

    hr {
      margin: 4px 0;
      border: 1px solid black;
    }
  </style>
</head>
<body>
@php
    $chunks = $data->chunk(1); // Each label per page
@endphp

@foreach($chunks as $page)
@foreach($page as $parcel)
<div class="label">

  <!-- Top Section -->
  <div class="row">
    <!-- Left Side -->
    <div class="col-left">
      <div class="bold">CUSTOMER ADDRESS <span style="float:right">000{{$parcel->id}}</span></div>
      <div>{{ $parcel->pickup_name }}</div>
      <div>{{ $parcel->pickup_mobile }}</div>
      <div>{{ $parcel->pickup_address }}</div>
      <div>{{ $parcel->pickup_city }}, {{ $parcel->pickup_state }} - {{ $parcel->pickup_pincode }}</div>

      <div class="section">
        <div class="bold">IF UNDELIVERED, RETURN TO:</div>
        <div>{{ $parcel->consignee_name }}</div>
        <div>{{ $parcel->consignee_mobile }}</div>
        <div>{{ $parcel->consignee_address }}</div>
        <div>{{ $parcel->consignee_city }}, {{ $parcel->consignee_state }} - {{ $parcel->consignee_pincode }}</div>
      </div>
    </div>

    <!-- Right Side -->
    <div class="col-right">
      <div class="black-bar">DO NOT COLLECT CASH</div>

      <div class="row">
        <div class="col-left" style="border: none;">
          <div class="row">
            <div class="col-lefts" style="border: none;">
            <img src="{{ asset('website/images/logo4.jpeg') }}" style="width:60px; height:50px;" alt="Logo">
           </div>
           <div class="col-rights" style="border: none;">
              <div style="font-size:10px;margin-top:8px;margin-left:22px">WE DELIVER EXCELLENCE</div>
           </div>
           </div>
         <!-- <center> <div class="qrcode" >
            <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ $parcel->barcode_no }}&size=100x100" alt="QR Code" style="margin-left:20px">
          </div></center> -->

          
        </div>
      </div>
<div class="bold" style="font-size:normal;margin-top: 5px">FRANCHISE DETAILS</div><hr>

           <div style="width:100%;font-size: 9px; font-weight: bold;margin-top:2px;line-height: 15px;">
          FRANCHISE ID:<span style="font-weight: normal;"> {{ $linkDetail->franchise_no }}</span>,<br>
  CPH ID: <span style="font-weight: normal;">{{ $linkDetail->cms_no }}</span>,<br>
  GST NO: <span style="font-weight: normal;">{{$franchise->gst_number}}</span><br>
       </div>
      
    </div>
  </div>

  <!-- Product Table -->
  <table>
    <thead>
      <!-- <tr>
        <td colspan="9" class="bold">Product Details</td>
      </tr> -->
      <tr>
        <th>WEIGHT</th>
        <th>LENGTH</th>
        <th>WIDTH</th>
        <th>HEIGHT</th>
        <!-- <th>Fuel</th> -->
        <th>COD</th>
        <th>AMOUNT</th>
        <th>GST</th>
        <th>TOTAL</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $parcel->package_weight }} gm</td>
        <td>{{ $parcel->package_length ?? 0 }} cm</td>
        <td>{{ $parcel->package_width ?? 0 }} cm</td>
        <td>{{ $parcel->package_height ?? 0 }} cm</td>
        <!-- <td>₹{{ $parcel->fuel_charge ?? 0 }}</td> -->
        <td>₹{{ $parcel->cod_amount }}</td>
        <td>₹{{ number_format($parcel->payment_amount / 1.18, 2) }}</td>
        <td>₹{{ number_format($parcel->payment_amount - ($parcel->payment_amount / 1.18), 2) }}</td>
        <td>₹{{ $parcel->payment_amount }}</td>
      </tr>
      <tr>
        <td colspan="9" style="text-align: center;">
          <hr>
          <!-- <div style="font-size: 9px;">
            Tracking: www.gotogopost.com<br>
            Toll Free No: 18001231617
          </div> -->
        </td></tr>
        <tr>
        <td colspan="4" style="border-right: 2px solid #000;">
            <span  style="font-size: 9px;line-height:13px">
            <center> TIME: {{ $parcel->created_at->format('h:i A') }}<br>
            DATE:{{ $parcel->created_at->format('d-m-Y') }}<br>
             www.gotogopost.com<br>
            <span style="margin-top:2px">TOLL FREE NO: 18001231617 </span></center>
          </span>
        </td>
         <td colspan="5">
             <div class="barcode" style="padding: 5px">
        <center><span class="bold" style="margin-bottom:15px;font-size:10px">POST EXPRESS PACKAGE</span></center>
        <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" alt="Barcode">
        <div class="barcode-number">{{ $parcel->barcode_no }}</div>
      </div>
        </td>
      </tr>
    </tbody>
  </table>

</div>
@endforeach
@endforeach
</body>
</html>
