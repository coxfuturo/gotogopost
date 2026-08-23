<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    @page {
      size: 10cm 3cm; /* Two 5x3 labels per row */
      margin: 0;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      font-size: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td {
      width: 50%;
      height: 3cm;
      padding: 0;
      text-align: center;
      vertical-align: middle;
    }

    .label {
      width: 100%;
      height: 100%;
      box-sizing: border-box;
      padding-top: 4px;  /* move down slightly */
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .label-title {
      font-size: 8px;
      text-transform: uppercase;
      font-weight: bold;
      margin-bottom: 2px;
    }

    .barcode {
      margin: 2px 0;
    }

    .barcode img {
      width: 95%;
      /* padding: 5px; */
      height: 25px;
      display: block;
      margin: 0 auto;
    }

    .barcode-number {
      font-weight: bold;
      font-size: 10px;
      letter-spacing: 1.2px;
      margin-top: 2px;
    }

    .page-break {
      page-break-after: always;
    }
  </style>
</head>
<body>


@php
  $rows = $data->chunk(2);
@endphp

@foreach($rows as $row)
  <table>
    <tr>
      @foreach($row as $parcel)
        <td>
          <center><div class="label">
            <span style="font-size: 12px;font-weight: 700px">INDIA POST</span>
            <span class="label-title">
              SPEED POST: PARCEL DOMESTIC
            </span>
            <div class="barcode">
              <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}">
            </div>
            <div class="barcode-number">{{ $parcel->barcode_no }}</div>
          </div></center>
        </td>
      @endforeach
      @if(count($row) < 2)
        <td></td>
      @endif
    </tr>
  </table>
@endforeach

</body>
</html>
