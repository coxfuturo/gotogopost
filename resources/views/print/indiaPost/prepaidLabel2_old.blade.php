<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    @page {
      size: 7.6cm 5.08cm;
      margin: 0;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    .label {
      width: 7.4cm;
      height: 5.06cm;
      box-sizing: border-box;
      overflow: hidden;
      page-break-after: always;
      margin: auto;
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
    }

    .qrs img {
      width: 80px;
      height: 70px;
      display: block;
      margin: auto;
      margin-top: 30px;
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
      margin-top: 1px;
      margin-right: 30px;
      float: right;
    }

    .footer {
      font-size: 8px;
      text-align: left;
      line-height: 1.8;
      margin-top: 0px;
      border: 1px solid #000;
      padding: 2px;
      text-transform: uppercase;
    }

    .footers {
      font-size: 7.8px;
      text-align: center;
      border: 1px solid #000;
      border-top: none;
      padding: 2px;
      letter-spacing: 1px;
      text-transform: uppercase;
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
      <th rowspan="2">
        <span style="width:70px; float:right; font-size: 8px; text-transform: uppercase;">
          <b>(DEMO LABEL)<br>SHIP TO</b><br>
          <span style="font-size: 6px">{{ $parcel->consignee_name }}</span><br>
          PINCODE:-{{ $parcel->consignee_pincode }}
        </span>
      </th>

      <th style="text-align:center; font-size:11px; width:60%; text-transform: uppercase;">
        <!-- Consignee PIN/City removed (kept intentionally empty) -->
      </th>

      <th style="width:25%">
        <div class="logo"></div>
      </th>
    </tr>

    <tr>
      <td colspan="2">
        <div class="barcode">
          <span style="margin-left: 52px; font-size: 8px; text-transform: uppercase;">
            <b>
              @switch($type)
                  @case(5)
                      SPEED POST-INLAND DOCUMENT
                  @break
                  @default
                      {{ \App\Models\Admin::INDIA_POST_BUSINESS }} DOMESTIC
              @endswitch
            </b>
          </span>

          <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}">
          <div class="barcode-number">{{ $parcel->barcode_no }}</div>
        </div>
      </td>
    </tr>
  </table>

  <!-- Footer -->
  <div class="footer">
    <b>From Address</b><br>
    Gotogo Post Courier Services<br>
    {{ $franchise->address }}, {{ $franchise->district }}<br>
    {{ $franchise->city }} {{ $franchise->consignee_state }} - {{ $franchise->pincode }}<br>
    Contact No. {{ $franchise->mobile }}
  </div>

  <div class="footers">
    <div style="display:flex; align-items:center; justify-content:center; font-size:8px;">
      <span><b>Customer ID: 1644026318</b></span>
      <span style="width:20px;"></span>
      <span><b>Contact ID: 41477007</b></span>
    </div>
  </div>

</div>

@endforeach
@endforeach

</body>
</html>
