<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #212529;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        h1,
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        .border {
            border: 1px solid #dee2e6;
        }

        th {

            font-weight: bold;
        }

        .table-container {
            margin-bottom: 30px;
        }


        .amount {
            text-align: right;
        }

        .grand-total {
            font-weight: bold;
        }

        @media print {

            body,
            .container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                background-color: #ffffff;
            }

            .container {
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Franchise Details Table -->
        <div class="table-container">
            <h2>Business Associate</h2>
            <table>
                <tr>
                    <td class="border">
                        <table>
                            <tr>
                                <th>Society/Company Name</th>
                                <td>{{ $franchiseDetails->society }}</td>
                            </tr>
                            <tr>
                                <th>Business Associate No</th>
                                <td>{{ $franchiseDetails->franchise_no }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $franchiseDetails->name }}</td>
                            </tr>
                            <tr>
                                <th>Director/Owner Name</th>
                                <td>{{ $franchiseDetails->father_name }}</td>
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td>{{ $franchiseDetails->mobile }}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{ $franchiseDetails->city }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="border">
                        <table>
                            <tr>
                                <th>Pincode</th>
                                <td>{{ $franchiseDetails->pincode }}</td>
                            </tr>
                            <tr>
                                <th>District</th>
                                <td>{{ $franchiseDetails->district }}</td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>{{ $franchiseDetails->state }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $franchiseDetails->address }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ \Carbon\Carbon::today()->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>


        <!-- Commissions Table -->
        <div class="table-container">
            <h2>Commissions</h2>
            <table class="border">
                <thead>
                    <tr>
                        <th style="text-align: center;">Service</th>
                        <th style="text-align: center;">Commission</th>
                        <th style="text-align: center;">GST</th>
                        <th style="text-align: center;">TDS</th>
                        <th style="text-align: center;">Final Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="highlight-gotogo border">
                        <td style="text-align: center;"><strong>Gotogo Post</strong></td>
                        <td class="amount" style="text-align: center;">₹ {{ number_format($totalGotogoCommission, 2) }}</td>
                        <td class="amount" style="text-align: center;">₹ {{ number_format($gstGotogo, 2) }}</td>
                        <td class="amount" style="text-align: center;">₹ {{ number_format($tdsGotogo, 2) }}</td>
                        <td class="amount" style="text-align: center;">₹ {{ number_format($totalGotogo, 2) }}</td>
                    </tr>
                    <tr class="highlight-india-post border">
                        <td style="text-align: center;"><strong>India Post</strong></td>
                        <td class="amount" style="text-align: center;">₹ {{ number_format($totalIndiaPostCommission, 2) }}</td>
                        <td class="amount" style="text-align: center;">₹ {{ number_format($gstIndiaPost, 2) }}</td>
                        <td class="amount" style="text-align: center;">₹ {{ number_format($tdsIndiaPost, 2) }}</td>
                        <td class="amount" style="text-align: center;">₹ {{ number_format($totalIndiaPost, 2) }}</td>
                    </tr>
                    <tr class="grand-total">
                        <td style="text-align: center;"><strong>Grand Total</strong></td>
                        <td class="amount" style="text-align: center;"></td>
                        <td class="amount" style="text-align: center;"></td>
                        <td class="amount" style="text-align: center;"></td>
                        <td class="amount" style="text-align: center;">₹ <strong>{{ number_format(($totalGotogo) + ($totalIndiaPost), 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>