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

        th,
        td {
            padding: 10px;

            text-align: left;
        }

        .border {
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #f1f1f1;
        }

        .table-container {
            margin-bottom: 20px;
        }

        @media print {

            body,
            .container {
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
        <h1>Commission and P.O/CPH Details</h1>

        <!-- pph Details Table -->
        <div class="table-container">
            <h2>P.O/CPH Details</h2>

            <table class="border">
                <tr>
                    <td class="border">
                        <table>
                            <tr>
                                <th>P.O/CPH No</th>
                                <td>{{ $pphDetails->cms_no }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $pphDetails->name }}</td>
                            </tr>
                            <tr>
                                <th>Director/Owner Name</th>
                                <td>{{ $pphDetails->father_name }}</td>
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td>{{ $pphDetails->mobile }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $pphDetails->email }}</td>
                            </tr>

                            <tr>
                                <th>Pincode</th>
                                <td>{{ $pphDetails->pincode }}</td>
                            </tr>

                        </table>
                    </td>
                    <td class="border">
                        <table>

                            <tr>
                                <th>City</th>
                                <td>{{ $pphDetails->city }}</td>
                            </tr>

                            <tr>
                                <th>District</th>
                                <td>{{ $pphDetails->district }}</td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>{{ $pphDetails->state }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $pphDetails->address }}</td>
                            </tr>

                            <tr>
                                <th>Commission</th>
                                <td>₹ {{ $data['commission'] }} </td>
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

        <!-- Commission Details Table -->
        <div class="table-container">
            <h2>Commission Details</h2>
            <table>
                <thead>
                    <tr class="border">
                        <th class="border">Commission</th>
                        <th class="border">GST</th>
                        <th class="border">TDS</th>
                        <th class="border">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border">{{ $data['commission'] }}</td>
                        <td class="border">₹ {{ $data['gst'] }}</td>
                        <td class="border">₹ {{ $data['tds'] }}</td>
                        <td class="border">₹ {{ $data['total'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>