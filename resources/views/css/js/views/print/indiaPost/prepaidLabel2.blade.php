<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    @page {
  size: 9.8cm 7.3cm; 
  /* size:  9.8cm 5.0cm;  */
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
  height: 5.0cm;
  box-sizing: border-box;
      overflow: hidden;
      page-break-after: always;
  margin: auto; /* Helps ensure centering in print */
}


    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
    }

    th, td {
      padding: 2px;
      vertical-align: top;
      line-height: 13px;
    }

    .logo img {
      width: 42px;
      height: 20px;
      display: block;
      margin: auto;
    }

    .qr img {
      width: 60px;
      height: 40px;
      display: block;
      margin: auto;
      /* margin-top: 15px; */
    }

.qrs img {
      width: 80px;
      height: 70px;
      display: block;
      margin: auto;
      margin-top:30px;
    }

    .barcode img {
       width: 150px;
      height: 23px;
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
      font-size: 9px;
      text-align: left;
      line-height: 1.8;
      margin-top:0px;
      border: 1px solid #000;
      padding: 2px;
      /* letter-spacing: 1px; */
      font-family: Arial, sans-serif;
      font-weight: 700px;
    }
    .footers {
      font-size: 7.8px;
      text-align: center;
      border: 1px solid #000;
      border-top: none;
      padding: 2px;
      letter-spacing: 1px;
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
      <th rowspan="2" >
        <!-- <div class="qr"><img src="{{asset('website/images/logonewtransparent.png')}}"></div> -->
        <br><br><br>
        <span style="width:60px;float:right;font-size: 8px"><b>SNo. {{ str_pad($parcel->id, 6, '0', STR_PAD_LEFT) }}</b></span>
      </th>
      <th style="text-align:center; font-size:11px; width:60%;text-transform: uppercase;">Delivery PINCODE <br>{{ $parcel->consignee_pincode }}</th>
      <th style="width:25%"><div class="logo"></div></th>
      <!-- <th style="text-align:center; font-size:11px; width:60%;">{{ $parcel->consignee_city }} HO <br>{{ $parcel->consignee_pincode }}</th>
      <th style="width:25%"><div class="logo"><img src="{{asset('admin/assets/img/India_Post_Logo.webp')}}" style="width:100%;height:30px"></div></th> -->
    </tr>
    
    <tr>
      <td colspan="2">
        <div class="barcode">
          <span Style="margin-left: 60px;font-size: 8px;text-transform: uppercase;"><b> @switch($type)
            @case(5)
           SPEED POST-INLAND DOCUMENT
        @break

    @default
        {{ \App\Models\Admin::INDIA_POST_BUSINESS }} DOMESTIC
@endswitch </b></span>
          <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}">
          <div class="barcode-number">{{ $parcel->barcode_no }}</div>
        </div>
      </td>
    </tr>
  </table>

  <!-- Footer -->
  <div class="footer">
    Noida BNPL SP Hub ({{ $franchise->pincode }}) {{ $parcel->created_at->format('d-m-y') }}, {{ $parcel->created_at->format('H:i:s') }} <br>
    Weight (gms): {{ $parcel->package_weight ?? 0 }}
    L: {{ $type == 6 ? ($parcel->package_length ?? 0) : 0 }}
    B: {{ $type == 6 ? ($parcel->package_width ?? 0) : 0 }}
    H: {{ $type == 6 ? ($parcel->package_height ?? 0) : 0 }}
     @if($type == 6) (Vol.Wt: 0.00) @endif
    <br>
<span style="display:flex"><span style="width:50%;">
  Amount Paid: Rs{{ $parcel->payment_amount }}(CO)<br>

Customer ID: 1698669308</span> <span style="width: 50%;font-size: 9px;text-align:center">In case of any grievance - <br>DIAL-18001231617</span></spna>

  </div>
  <div class="footers">
     <div style="display: flex; align-items: center; justify-content: center; font-size: 10px; ">
  <span>POWERED BY:</span>
  <img src="{{asset('website/images/logonewtransparent.png')}}" 
       style="width: 15px; height: 15px; margin: 0 5px;" alt="logo">
  <span>GOTOGOPOST</span>
</div>
  </div>

      <!-- <div style="display: flex; align-items: center; justify-content: center; font-size: 10px; margin-top:5px;">
  <span>POWERED BY:</span>
  <img src="{{asset('website/images/logonewtransparent.png')}}" 
       style="width: 15px; height: 15px; margin: 0 5px;" alt="logo">
  <span>GOTOGOPOST</span>
</div> -->

</div>
@endforeach
@endforeach
</body>
</html>
