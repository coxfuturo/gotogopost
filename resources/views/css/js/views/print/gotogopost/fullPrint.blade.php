<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Parcel Receipt</title>
 <style>
    @page {
      margin: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background: white;
    }

    .invoice {
      width: 100%;
      max-width: 900px;
      margin: auto;
      padding: 20px 30px;
      box-sizing: border-box;
      background: #fff;
    }

    header.invoice-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #007bff;
      padding-bottom: 8px;
      position: relative;
    }

    .header-left {
      display: flex;
      flex-direction: column;
    }

    .logo {
      height: 60px;
      margin-bottom: 5px;
    }

    .company-name {
      font-size: 18px;
      font-weight: bold;
      color: #d02626;
    }

    .invoice-title {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      font-weight: bold;
      font-size: 26px;
      color: #007bff;
    }

    .invoice-date {
      text-align: right;
      font-size: 13px;
    }

    .barcode-wrapper {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 30px;
      margin-top: 15px;
      flex-wrap: wrap;
      page-break-inside: avoid;
    }

    .franchise-details {
      font-size: 14px;
      flex: 1;
    }

    .barcode {
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .barcode img {
      height: 50px;
      max-width: 250px;
    }

    .barcode p {
      font-size: 14px;
      margin: 4px 0 0 0;
      font-weight: bold;
      letter-spacing: 10px;
    }

    .section {
      margin-top: 20px;
      page-break-inside: avoid;
    }

    .section h3 {
      font-size: 16px;
      margin-bottom: 8px;
      color: #007bff;
      border-left: 4px solid #007bff;
      padding-left: 8px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4px 20px;
      font-size: 13px;
    }

    .info-label {
      font-weight: bold;
      color: #444;
    }

    .info-grid div {
      padding: 2px 0;
    }

    .charges-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
      margin-top: 10px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .charges-table thead th,
    .charges-table td {
      text-align: left;
      padding: 12px;
    }

    .charges-table thead th {
      background-color: #007bff;
      color: #fff;
      font-weight: 600;
      letter-spacing: 0;
    }

    .charges-table tbody tr {
      transition: background-color 0.3s ease;
    }

    .charges-table tbody tr:hover {
      background-color: #f1f9ff;
    }

    .charges-table td {
      border-top: 1px solid #e0e0e0;
    }

    .total-row td {
      font-weight: bold;
      background-color: #e6f2ff;
      border-top: 2px solid #007bff;
    }

    @media print {
      html, body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
      }

      body * {
        visibility: hidden;
      }

      .invoice, .invoice * {
        visibility: visible;
      }

      .invoice {
        position: absolute;
        left: 0;
        top: 0;
        box-shadow: none;
        border: none;
        width: 100%;
      }
    }
  </style>
</head>
<body>

<div class="invoice">
  <!-- HEADER -->
  <header class="invoice-header">
    <div class="header-left">
      <img class="logo" src="{{ asset('website/images/logonewtransparent.png') }}" alt="Gotogo Post Logo">
      <div class="company-name">Gotogo Post</div>
    </div>
    <div class="invoice-title">Parcel Receipt</div>
    <div class="invoice-date">
      <strong>Invoice Date:</strong><br>
      {{ \Carbon\Carbon::parse($parcel->created_at)->format('d/m/Y') }} <br>
      {{ $parcel->created_at->format('h:i A') }}
    </div>
  </header>

  <!-- Franchise + Barcode -->
  <div class="barcode-wrapper">
    <div class="franchise-details">
      <h3>Franchise Details</h3>
      <div>Frame Name: {{ $parcel->franchise->society }}</div>
      <div>{{ $parcel->franchise->generated_id }}</div>
      
      <div style="font-size:18px;letter-spacing: 1px;margin-top:8px"><b>{{ $parcel->franchise->gst_number ?? ''}}</b></div>
    </div>
    <div class="barcode">
        <div style="font-size: 22px;font-weight: 600;margin-bottom:5px">
            @switch($type)
    @case(1)
        {{ \App\Models\Admin::GOTOGO_POST_SPEED }}
        @break

    @case(2)
        {{ \App\Models\Admin::GOTOGO_POST_BUSINESS }}
        @break

    @default
        {{ \App\Models\Admin::GOTOGO_POST_REGISTERED }}
@endswitch

        </div>
      <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" alt="Parcel Barcode">
      <p>{{ $parcel->barcode_no }}</p>
    </div>
  </div>

  <!-- Customer -->
  <div class="section">
    <h3>Customer Details</h3>
    <div class="info-grid">
      <div><b>{{ $parcel->ecustomer->gst_number ?? '' }}</b></div>
      <div><span class="info-label">State:</span> {{ $parcel->pickup_state }}</div>
      <div><span class="info-label">Name:</span> {{ $parcel->pickup_name }}</div>
      <div><span class="info-label">City:</span> {{ $parcel->pickup_city }}</div>
      <div><span class="info-label">Email:</span> {{ $parcel->pickup_email }}</div>
      <div><span class="info-label">Pincode:</span> {{ $parcel->pickup_pincode }}</div>
      <div><span class="info-label">Mobile:</span> {{ $parcel->pickup_mobile }}</div>
      <div style="grid-column: span 2;"><span class="info-label">Address:</span> {{ $parcel->pickup_address }}</div>
    </div>
  </div>

  <!-- Consignee -->
  <div class="section">
    <h3>Consignee Details</h3>
    <div class="info-grid">
      <div><span class="info-label">Name:</span> {{ $parcel->consignee_name }}</div>
      <div><span class="info-label">Mobile:</span> {{ $parcel->consignee_mobile }}</div>
      <div><span class="info-label">Email:</span> {{ $parcel->consignee_email }}</div>
      <div><span class="info-label">City:</span> {{ $parcel->consignee_city }}</div>
      <div><span class="info-label">State:</span> {{ $parcel->consignee_state }}</div>
      <div><span class="info-label">Pincode:</span> {{ $parcel->consignee_pincode }}</div>
      <div style="grid-column: span 2;"><span class="info-label">Address:</span> {{ $parcel->consignee_address }}</div>
    </div>
  </div>

  <!-- Package Info -->
  <div class="section">
    <h3>Package Information</h3>
    <div class="info-grid">
      <div><span class="info-label">Weight:</span> {{ $parcel->package_weight }} gm</div>
      <div><span class="info-label">Length:</span> {{ $parcel->package_length ?? 0}} cm</div>
      <div><span class="info-label">Width:</span> {{ $parcel->package_width ?? 0}} cm</div>
      <div><span class="info-label">Height:</span> {{ $parcel->package_height ?? 0 }} cm</div>
      <div><span class="info-label">Fuel:</span> ₹{{ $parcel->fuel_charge ?? 0}}</div>
      @if($parcel->payment_method == 'cod')
        <div><span class="info-label">COD Amount:</span> ₹{{ $parcel->cod_amount }}</div>
      @endif
      <div><span class="info-label">Payment Method:</span> {{ ucfirst($parcel->payment_method) }}</div>
    </div>
  </div>

  <!-- Charges -->
  <div class="section">
    <h3>Charges Summary</h3>
    <table class="charges-table">
      <tr>
        <td style="font-weight:700">Charge Type</td>
        <td style="font-weight:700">Amount (₹)</td>
      </tr>
      <tr><td>Base Amount</td><td>{{ $rateDetails['amount'] }}</td></tr>
      @if($rateDetails['fuel_charge'] > 0)
        <tr><td>Fuel Charge</td><td>{{ $rateDetails['fuel_charge'] }}</td></tr>
      @endif
      @if($rateDetails['pickup_charge'] > 0)
        <tr><td>Pickup Charge</td><td>{{ $rateDetails['pickup_charge'] }}</td></tr>
      @endif
      @if($rateDetails['other_service_charge'] > 0)
        <tr><td>Other Charges</td><td>{{ $rateDetails['other_service_charge'] }}</td></tr>
      @endif
      <tr><td>GST</td><td>{{ $rateDetails['gst'] }}</td></tr>
      <tr class="total-row"><td>Total Amount</td><td>{{ $rateDetails['total_payment_amount'] }}</td></tr>
    </table>
  </div>
</div>

</body>
</html>
