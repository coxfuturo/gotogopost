<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Labels</title>
  <style>
    @page {
      size: 5.08cm 2.54cm; /* Width x Height */
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
      table-layout: fixed;
    }

    td {
      width: 50%;
      height: 2.54cm;
      padding: 0;
      text-align: center;
      vertical-align: middle;
      box-sizing: border-box;
    }

    .label {
      width: 100%;
      height: 100%;
      padding: 2px;
      box-sizing: border-box;
      text-align: center;
    }

    .label-title {
      font-size: 10px;
      font-weight: bold;
      margin: 2px 0;
      text-transform: uppercase;
    }

    .barcode {
      margin: 2px 0;
    }

    .barcode img {
      max-width: 90%;
      height: 30px;
      display: block;
      margin: 0 auto;
    }

    .barcode-number {
      font-weight: bold;
      font-size: 12px;
      letter-spacing: 1px;
      margin-top: 2px;
    }

    hr {
      width: 100%;
      border: 0;
      border-top: 2px solid #000;
      margin: 2px auto;
    }
  </style>
</head>
<body>

@php
  // Split data into rows with 2 labels per row
  $rows = $data->chunk(1);
@endphp

@foreach($rows as $row)
  <table>
    <tr>
      @foreach($row as $parcel)
        <td>
          <div class="label">
            <span style="font-size: 13px; font-weight: bold; display:block; margin-top: 10px;text-transform: uppercase;">{{ $parcel->consignee_name }}</span>
            <hr>
            <span class="label-title">SPEED POST: PARCEL DOMESTIC</span>
            <div class="barcode">
              <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" alt="Barcode">
            </div>
            <div class="barcode-number">{{ $parcel->barcode_no }}</div>
          </div>
        </td>
      @endforeach

      {{-- If only 1 label in this row, add empty cell --}}
      @if(count($row) < 1)
        <td></td>
      @endif
    </tr>
  </table>
@endforeach

</body>
</html>
