<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
     @page {
      size: 11.0cm 8.8cm;
      margin: 0;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      font-size: 10px;
    }

    .label {
      width: 10.5cm;
      height: 8.0cm;
      border: 2px solid #000;
      box-sizing: border-box;
      overflow: hidden;
      page-break-after: always;
      padding: 4px;
      margin: auto;
      margin-top: 2%;
      
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
      /* border-top: 2px solid black; */
    }

    th, td {
      padding: 2px;
      text-align: left;
      /* line-height: 10px; */
      font-size: 8px;
    }

    table th, table td {
      width: calc(100% / 8);
       white-space: nowrap; /* same line me rakhega */
  vertical-align: middle;
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

<div class="label">
  <!-- Product Table -->
  
  <table>
    <thead>
      <tr>
      <th colspan="6"><div class="bold">Ship To ADDRESS  </div>
      <div style="height:50px;">
        <div>{{ $parcel->consignee_name }}</div>
        <div>{{ $parcel->consignee_mobile }}</div>
        <div>{{ $parcel->consignee_address }}</div>
        <div>{{ $parcel->consignee_city }}, {{ $parcel->consignee_state }} - {{ $parcel->consignee_pincode }}</div>
      </div>
      <div class="section">
        <div class="bold">If UNDELIVERED, RETURN TO:</div>
        @if($parcel->pickup_gst_number) <span style="text-transform: uppercase;"><b>GSTIN: {{ $parcel->pickup_gst_number ?? NULL }}</br></b></span> @endif
      <div>{{ $parcel->pickup_name }}</div>
      <div>{{ $parcel->pickup_mobile }}</div>
      <div>{{ $parcel->pickup_address }}</div>
      <div>{{ $parcel->pickup_city }}, {{ $parcel->pickup_state }} - {{ $parcel->pickup_pincode }}</div>
      </div></th>

      <th colspan="2" style="border-left: 2px solid black;">
        <div class="black-bar" style="background:#000;">CASH ON DELIVERY</div>
      <center><div style="font-size:12px"><b>Rs.{{ $parcel->cod_amount }}.00</b></div></center><hr>
      <div class="bold" style="font-size:normal;margin-top: 5px;text-align:center">ACCOUNT DETAILS</div><hr>
 <!-- <span style="font-size: 12px;margin-top:5px;letter-spacing: 2px;text-align:center;font-weight: 600"><center>INDIA POST</center></span><hr> -->
           <div style="width:100%;font-size: 8px; font-weight: bold;margin-top:5px;line-height: 18px;">
          <!-- BNPL CODE: NOIDA SP-532<span style="font-weight: normal;"></span>,<br> -->
  CONTRACT ID: <span style="font-weight: normal;">41506299</span>,<br>
  CUSTOMER ID: <span style="font-weight: normal;">1698669308</span>,<br>
  GST NO: <span style="font-weight: normal;">09AALCG1559N1ZY</span><br>
  <!-- <span style="font-size: 11px;margin-top:5px;letter-spacing: 2px;text-align:center"><center>INDIA POST</center></span> -->
       </div>
      </th>
  </tr>

     <tr>
      <th style="border-top: 2px solid black;" colspan="8"></th>
     </tr>
      
      <tr>
        <th style="text-align: center;">WEIGHT</th>
        <th style="text-align: center;">LENGTH</th>
        <th style="text-align: center;">WIDTH</th>
        <th style="text-align: center;">HEIGHT</th>
        <th style="text-align: center;">COD</th>
        <th style="text-align: center;">AMOUNT</th>
        <th style="text-align: center;">GST</th>
        <th style="text-align: center;">TOTAL</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="text-align: center;">{{ number_format($parcel->package_weight, 0) }} gm</td>
<td style="text-align: center;">{{ number_format($parcel->package_length ?? 0, 0) }} cm</td>
<td style="text-align: center;">{{ number_format($parcel->package_width ?? 0, 0) }} cm</td>
<td style="text-align: center;">{{ number_format($parcel->package_height ?? 0, 0) }} cm</td>

        <td ><b>Rs.{{ $parcel->cod_amount }}.00</b></td>
        <td style="text-align: center;">Rs.{{ number_format($parcel->payment_amount / 1.18, 2) }}</td>
        <td style="text-align: center;">Rs.{{ number_format($parcel->payment_amount - ($parcel->payment_amount / 1.18), 2) }}</td>
        <td style="text-align: center;"><b>Rs.{{ $parcel->payment_amount }}</b></td>
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
        <td colspan="4" style="border-right: 2px solid #000">
            <span  style="font-size: 10px;line-height:13px">
            <center> TIME: {{ $parcel->created_at->format('h:i A') }}<br>
            DATE:{{ $parcel->created_at->format('d-m-Y') }}<br>
             www.gotogopost.com<br>
            <span style="margin-top:1px">Toll Free Dail: 18001231617</span></center>
             <div style="display: flex; align-items: center; justify-content: center; font-size: 6px; ">
  <span>POWERED BY:</span>
  <img src="{{asset('website/images/logonewtransparent.png')}}" 
       style="width: 15px; height: 15px; margin: 0 5px;" alt="logo">
  <span>GOTOGOPOST</span>
</div>
          </span>
        </td>

        <td colspan="5">
 <div class="barcode" style="padding: 10px;text-align:center;">

    <div style="font-size: 11px; font-weight:bold; text-align:center; text-transform:uppercase; margin-top:-10px;">
      @if($parcel->parcel_type == 1)
          Surface
      @else
          Air
      @endif
    </div>
    <hr style="margin: 2px 0;">

    <div style="font-size: 11px; font-weight:bold; text-align:center; text-transform:uppercase;">
      INDIA POST
    </div>

        <span class="bold" style="font-size:8px;text-transform: uppercase;">Speed Post Parcel DOMESTIC</span>
        <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" alt="Barcode" style="margin-top:3px;">
        <div class="barcode-number">{{ $parcel->barcode_no }}</div>

        <!-- <div style="display: flex; align-items: center; justify-content: center; font-size: 8px; margin-top:6px;">
  <span>POWERED BY:</span>
  <img src="{{asset('website/images/logonewtransparent.png')}}" 
       style="width: 15px; height: 15px; margin: 0 5px;" alt="logo">
  <span>GOTOGOPOST</span>
</div> -->

      </div>
        </td>
      </tr>
    </tbody>
  </table>

</div>

</body>
</html>
