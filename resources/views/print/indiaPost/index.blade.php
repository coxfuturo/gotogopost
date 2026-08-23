<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Courier Slip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
        }
        .slip {
            width: 520px;
            border: 1px solid #000;
            padding: 10px;
        }
        .center {
            text-align: center;
        }
        .qr-barcode {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .barcode {
            text-align: center;
        }
        .barcode-number {
            font-weight: bold;
            margin: 4px 0;
        }
        .box {
            border: 1px solid #000;
            padding: 5px;
            margin-top: 10px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        td {
            vertical-align: top;
            padding: 5px;
            border: 1px solid #000;
            width: 50%;
            font-size: 12px;
        }
        .footer {
            margin-top: 10px;
            font-size: 11px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="slip">
        <div class="center">
            <strong>Inland Speed Post</strong><br>
            DropOff
        </div>

        <div class="qr-barcode">
            <div>
                {{-- QR Code --}}
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $barcode ?? '' }}" alt="QR Code">
            </div>
            <div class="barcode">
                <div class="barcode-number">{{ $barcode ?? 'EU704806666IN' }}</div>
                {{-- Barcode --}}
                <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $barcode ?? 'EU704806666IN' }}&code=Code128&dpi=96" alt="Barcode">
            </div>
        </div>

        <div class="box">
            Dely Office & Pincode: {{ $delivery_office ?? 'Pune H.O(411001)' }} <br>
            Booking Office: {{ $booking_office ?? 'Noida BNPL SP Hub (201301)' }}<br>
            Counter No. {{ $counter_no ?? '0' }}, {{ $booking_date ?? '17/08/2025' }}, {{ $booking_time ?? '12:09:15' }}<br>
            GST No. {{ $gst_no ?? '' }}<br>
            Weight(gms):{{ $weight ?? '50.00' }} L:0 B:0 H:0 (Vol.Weight:0.00)<br>
            AmountPaid:{{ $amount ?? '41.30' }} Tax:{{ $tax ?? '6.30' }}(CGST-{{ $cgst ?? '3.15014' }} SGST-{{ $sgst ?? '3.15014' }})<br>
            ModeofPayment: {{ $payment_mode ?? 'CO' }} Customer ID: {{ $customer_id ?? '3000059283' }}
        </div>

        <table>
            <tr>
                <td>
                    <strong>Sender</strong><br>
                    {{ $sender_code ?? '40324379' }}<br>
                    {{ $sender_name ?? 'Digipin-' }}<br>
                    Mobile No.{{ $sender_mobile ?? '9812345600' }}<br>
                    {{ $sender_city ?? 'NOIDA' }}<br>
                    {{ $sender_district ?? 'NOIDA' }}<br>
                    {{ $sender_pincode ?? 'NOIDA-201301' }}
                </td>
                <td>
                    <strong>Receiver</strong><br>
                    {{ $receiver_name ?? 'PRITI' }}<br>
                    {{ $receiver_org ?? 'Digipin-' }}<br>
                    Mobile No.{{ $receiver_mobile ?? '9812345600' }}<br>
                    {{ $receiver_city ?? 'pune' }}<br>
                    {{ $receiver_district ?? 'pune' }}<br>
                    {{ $receiver_state ?? 'pune' }}<br>
                    {{ $receiver_pincode ?? 'pune-411001' }}
                </td>
            </tr>
        </table>

        <div class="footer">
            Track on www.indiapost.gov.in OR Dial 18002666868 <br>
            In case of any complaint, please visit https://crm.indiapost.gov.in/customer <br><br>
            Go Green!!! Opt for eReceipts, ePOD <br><br>
            This is system generated document, no manual signature required <br>
            {{ $print_date ?? '21-08-2025 17:04:43' }}
        </div>
    </div>
</body>
</html>
