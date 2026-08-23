<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100vw;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #495057;
        }
        td {
            color: #343a40;
        }
        .barcode-container {
            text-align: center;
        }
        @media print {
            body, .container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                background-color: #ffffff;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Parcel Details</h1>

        <!-- Pickup Details -->
        <table>
            <tr>
                <th colspan="2">Pickup Details</th>
            </tr>
            <tr>
                <td><strong>Pickup Name:</strong></td>
                <td>{{ $parcel->pickup_name }}</td>
            </tr>
            <tr>
                <td><strong>Pickup Mobile:</strong></td>
                <td>{{ $parcel->pickup_mobile }}</td>
            </tr>
            <tr>
                <td><strong>Pickup Email:</strong></td>
                <td>{{ $parcel->pickup_email }}</td>
            </tr>
            <tr>
                <td><strong>Pickup Pincode:</strong></td>
                <td>{{ $parcel->pickup_pincode }}</td>
            </tr>
            <tr>
                <td><strong>Pickup City:</strong></td>
                <td>{{ $parcel->pickup_city }}</td>
            </tr>
            <tr>
                <td><strong>Pickup State:</strong></td>
                <td>{{ $parcel->pickup_state }}</td>
            </tr>
            <tr>
                <td><strong>Pickup Address:</strong></td>
                <td>{{ $parcel->pickup_address }}</td>
            </tr>
        </table>

        <!-- Consignee Details -->
        <table>
            <tr>
                <th colspan="2">Consignee Details</th>
            </tr>
            <tr>
                <td><strong>Consignee Name:</strong></td>
                <td>{{ $parcel->consignee_name }}</td>
            </tr>
            <tr>
                <td><strong>Consignee Mobile:</strong></td>
                <td>{{ $parcel->consignee_mobile }}</td>
            </tr>
            <tr>
                <td><strong>Consignee Email:</strong></td>
                <td>{{ $parcel->consignee_email }}</td>
            </tr>
            <tr>
                <td><strong>Consignee Pincode:</strong></td>
                <td>{{ $parcel->consignee_pincode }}</td>
            </tr>
            <tr>
                <td><strong>Consignee City:</strong></td>
                <td>{{ $parcel->consignee_city }}</td>
            </tr>
            <tr>
                <td><strong>Consignee State:</strong></td>
                <td>{{ $parcel->consignee_state }}</td>
            </tr>
            <tr>
                <td><strong>Consignee Address:</strong></td>
                <td>{{ $parcel->consignee_address }}</td>
            </tr>
        </table>

        <!-- Package Details -->
        <table>
            <tr>
                <th colspan="2">Package Details</th>
            </tr>
            <tr>
                <td><strong>Package Weight:</strong></td>
                <td>{{ $parcel->package_weight }} gm</td>
            </tr>
            <tr>
                <td><strong>Package Length:</strong></td>
                <td>{{ $parcel->package_length }} cm</td>
            </tr>
            <tr>
                <td><strong>Package Width:</strong></td>
                <td>{{ $parcel->package_width }} cm</td>
            </tr>
            <tr>
                <td><strong>Package Height:</strong></td>
                <td>{{ $parcel->package_height }} cm</td>
            </tr>
            <tr>
                <td><strong>Payment Method:</strong></td>
                <td>{{ $parcel->payment_method }}</td>
            </tr>
            <tr>
                <td><strong>Amount:</strong></td>
                <td>INR {{ $rateDetails['amount'] }}</td>
            </tr>
            @if($rateDetails['fuel_charge'] > 0)
            <tr>
                <td><strong>Fuel Charge:</strong></td>
                <td>INR {{ $rateDetails['fuel_charge'] }}</td>
            </tr>
            @endif
            @if($rateDetails['pickup_charge'] > 0)
            <tr>
                <td><strong>Pickup Charge:</strong></td>
                <td>INR {{ $rateDetails['pickup_charge'] }}</td>
            </tr>
            @endif
            @if($rateDetails['other_service_charge'] > 0)
            <tr>
                <td><strong>Other Service Charge:</strong></td>
                <td>INR {{ $rateDetails['other_service_charge'] }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>GST:</strong></td>
                <td>INR {{ $rateDetails['gst'] }}</td>
            </tr>
            <tr>
                <td><strong>Total Payment Amount:</strong></td>
                <td>INR {{ $rateDetails['total_payment_amount'] }}</td>
            </tr>
            <tr>
                <td><strong>Order Date:</strong></td>
                <td>{{ \Carbon\Carbon::parse($parcel->created_at)->format('d/m/Y') }}</td>
            </tr>
        </table>

        <!-- Barcode -->
        <div class="barcode-container">
            <label for="PickupAddress">Barcode <span class="text-danger">*</span></label>
            <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" alt="Barcode" style="height: 50px; width: 200px;" />
            <p class="text-center">{{ $parcel->barcode_no }}</p>
        </div>

    </div>
</body>
</html>
