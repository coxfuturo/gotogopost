<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
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
      height: 5.0cm;
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
      width: 49%;
    }

    .col-right {
      width: 49%;
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
      height: 25px;
      display: block;
      margin: auto;
    }

    .barcode-number {
      font-weight: bold;
      font-size: 12px;
      letter-spacing: 2px;
      text-align: center;
      margin-top:2px;
      
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
       
      <div class="bold">Ship To ADDRESS  <span style="float:right">000{{$parcel->id}}</span></div>
      <div style="height:50px;">
        <div>{{ $parcel->consignee_name }}</div>
        <div>{{ $parcel->consignee_mobile }}</div>
        <div>{{ $parcel->consignee_address }}</div>
        <div>{{ $parcel->consignee_city }}, {{ $parcel->consignee_state }} - {{ $parcel->consignee_pincode }}</div>
      </div>
      <div class="section">
        <div class="bold">If UNDELIVERED, RETURN TO:</div>
        <!-- @if($parcel->pickup_gst_number) <span style="text-transform: uppercase;"><b>GSTIN: {{ $parcel->pickup_gst_number ?? NULL }}</br></b></span> @endif -->
      <div>{{ $parcel->pickup_name }}</div>
      <!-- <div>{{ $parcel->pickup_mobile }}</div> -->
      <div>{{ $parcel->pickup_address }}</div>
      <div>{{ $parcel->pickup_city }}, {{ $parcel->pickup_state }} - {{ $parcel->pickup_pincode }}</div>
      <div>{{ $parcel->pickup_mobile }}</div>
      </div>
    </div>

    <!-- Right Side -->
    <div class="col-right">
      <div class="black-bar" style="redground: #000">CASH ON DELIVERY</div>
      <center><div style="font-size:12px"><b>Rs.{{ $parcel->cod_amount }}.00</b></div></center><hr>
      <!-- <div class="bold" style="font-size:normal;margin-top: 5px">NAF ACCOUNT DETAILS</div> -->

           <div style="width:100%;font-size: 9px; font-weight: bold;margin-top:2px;line-height: 12px;">
          <!-- BNPL CODE: NOIDA SP-532<span style="font-weight: normal;"> </span>,<br>
  CONTRACT ID: <span style="font-weight: normal;">41506299</span>,<br>
  CUSTOMER ID: <span style="font-weight: normal;">1698669308</span>,<br>
  GST NO: <span style="font-weight: normal;">09AALCG1559N1ZY</span><br><hr> -->
  <span style="font-size: 12px;margin-top:5px;letter-spacing: 2px;text-align:center"><center>INDIA POST</center></span><hr>
  <div class="barcode" style="padding: 10px">
        <span class="bold" style="font-size:9px;text-transform: uppercase;margin-left:px">Speed Post Parcel DOMESTIC</span>
        <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" alt="Barcode" style="margin-top:3px;">
        <div class="barcode-number">{{ $parcel->barcode_no }}</div>
  </div>

       </div>


      
    </div>
  </div>

  <!-- Product Table -->
  
  <table>
    <thead>
      
      <tr>
        <th>WEIGHT</th>
        <th>LENGTH</th>
        <th>WIDTH</th>
        <th>HEIGHT</th>
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
      
        <td><b>₹{{ $parcel->cod_amount }}.00</b></td>
        <td>₹{{ number_format($parcel->payment_amount / 1.18, 2) }}</td>
        <td>₹{{ number_format($parcel->payment_amount - ($parcel->payment_amount / 1.18), 2) }}</td>
        <td><b>₹{{ $parcel->payment_amount }}</b></td>
      </tr>
      <tr>
        <td colspan="9" style="text-align: center;">
          <!-- <hr> -->
          
        </td></tr>
        <!-- <tr>
        <td colspan="4" style="border-right: 2px solid #000">
            <span  style="font-size: 10px;line-height:13px">
            <center> TIME: {{ $parcel->created_at->format('h:i A') }}<br>
            DATE:{{ $parcel->created_at->format('d-m-Y') }} <br>
             www.gotogopost.com<br>
            <span style="margin-top:10px;font-size:10px">Have A Nice Day</span></center>
            
          </span>
        </td>

         <td colspan="5">
             <div class="barcode" style="padding: 10px">
        <span class="bold" style="font-size:8px;text-transform: uppercase;">India Post - Speed Post Parcel DOMESTIC</span>
        <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" alt="Barcode" style="margin-top:3px;">
        <div class="barcode-number">{{ $parcel->barcode_no }}</div>

      </div>
        </td>
      </tr> -->
    </tbody>
  </table>

</div>
@endforeach
@endforeach
</body>
</html>
