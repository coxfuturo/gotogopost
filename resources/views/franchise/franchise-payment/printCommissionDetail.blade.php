<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Shriram Housing Finance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .receipt {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #000;
            background: #fff;
            font-size: 14px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .header-left {
            font-size: 24px;
            font-weight: bold;
            line-height: 1.2;
        }

        .header-right {
            text-align: right;
        }

        .header-right p {
            margin: 2px 0;
            line-height: 1.4;
            font-size: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .info-row div {
            width: 50%;
        }

        .section {
            margin-bottom: 15px;
        }

        .section p {
            margin: 4px 0;
        }

        .bold {
            font-weight: bold;
        }

        .details-table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 15px;
        }

        .details-table td {
            padding: 6px 0;
        }

        .details-table td:first-child {
            width: 40%;
        }

        .footer {
            margin-top: 15px;
            font-size: 12px;
            color: #333;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
        }

        .logo span {
            color: #0047ab;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="header-left logo">
            <img src="{{asset('website/images/logonewtransparent.png')}}" style="width: 90px;height:50px;" alt="Logo">
            </div>
            <div class="header-right">
                <p><strong>Receipt</strong></p>
                <p>{{$franchiseDetails->name}}</p>
                <p>{{$franchiseDetails->email}}</p>
            </div>
        </div>
        <div class="info-row">
            <div>
                <p><strong>Receipt No:</strong> SHF20250106483927</p>
            </div>
            <div>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</p>
                <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }} {{ \Carbon\Carbon::parse($data->created_at)->format('h-i') }} pm</p>
            </div>
        </div>
        <div class="section">
            <table class="details-table">
                <tr>
                    <td><strong>Paid to:</strong></td>
                    <td>Shriram Housing Finance</td>
                </tr>
                <tr>
                    <td><strong>Received with thanks from:</strong></td>
                    <td>Mr./Mrs. RAJESHSINGHSAGAR</td>
                </tr>
                <tr>
                    <td><strong>Loan Number:</strong></td>
                    <td>2025010600334-For GP PF</td>
                </tr>
                <tr>
                    <td><strong>The sum of Rupees:</strong></td>
                    <td><span class="bold">₹ {{$data->amount}}</span></td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Payment By:</strong></td>
                    <td>Online with {{$data->method}}</td>
                </tr>
                <tr>
                    <td><strong>Transaction ID:</strong></td>
                    <td><span class="bold">{{$data->razorpay_payment_id}}</span></td>
                </tr>
                <tr>
                    <td><strong>Payment Status:</strong></td>
                    <td><span class="bold">{{$data->status}}</span></td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>This is a system-generated receipt; hence no signature is required.</p>
        </div>
    </div>
</body>
</html>
