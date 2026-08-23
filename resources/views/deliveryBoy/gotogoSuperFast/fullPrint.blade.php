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
            .details-container {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }
            .details {
                width: calc(50% - 10px);

            }
            .details h4 {
                margin: 0px 0;
                padding: 0px;
                width: 200px;

            }

            .details p {
                margin: 0px 0;
                padding: 0px;

            }
            .details p:last-child {
                border-bottom: none;
            }
            .details strong {
                color: #495057;
                display: inline-block;
                width: 150px;
            }

            .details-inner{
                padding: 10px;
                display: flex;
            }
            @media print {
                body,
                .container {
                    box-shadow: none;
                    margin: 0;
                    padding: 0;
                    background-color: #ffffff;
                }
                .details p {
                    border-bottom: none;
                    page-break-inside: avoid;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Parcel Details</h1>
            <div class="details-container">
                <div class="details">
                    <div class="details-inner" >
                        <h4>Pickup Name:</h4>
                        <p>{{ $parcel->pickup_name }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Pickup Mobile:</h4>
                        <p>{{ $parcel->pickup_mobile }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Pickup Email:</h4>
                        <p>{{ $parcel->pickup_email }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Pickup Pincode:</h4>
                        <p>{{ $parcel->pickup_pincode }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Pickup City:</h4>
                        <p>{{ $parcel->pickup_city }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Pickup State:</h4>
                        <p>{{ $parcel->pickup_state }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Pickup Address:</h4>
                        <p>{{ $parcel->pickup_address }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Consignee Name:</h4>
                        <p>{{ $parcel->consignee_name }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Consignee Mobile:</h4>
                        <p>{{ $parcel->consignee_mobile }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Consignee Email:</h4>
                        <p>{{ $parcel->consignee_email }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Consignee Pincode:</h4>
                        <p>{{ $parcel->consignee_pincode }}</p>
                    </div>
                </div>
                <div class="details">
                    <div class="details-inner">
                        <h4>Consignee City:</h4>
                        <p>{{ $parcel->consignee_city }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Consignee State:</h4>
                        <p>{{ $parcel->consignee_state }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Consignee Address:</h4>
                        <p>{{ $parcel->consignee_address }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Package Weight:</h4>
                        <p>{{ $parcel->package_weight }} kg</p>
                    </div>
                    <div class="details-inner">
                        <h4>Package Length:</h4>
                        <p>{{ $parcel->package_length }} cm</p>
                    </div>
                    <div class="details-inner">
                        <h4>Package Width:</h4>
                        <p>{{ $parcel->package_width }} cm</p>
                    </div>
                    <div class="details-inner">
                        <h4>Package Height:</h4>
                        <p>{{ $parcel->package_height }} cm</p>
                    </div>
                    <div class="details-inner">
                        <h4>Payment Method:</h4>
                        <p>{{ $parcel->payment_method }}</p>
                    </div>
                    <div class="details-inner">
                        <h4>Service Type:</h4>
                        <p>{{ $parcel->service_type }}</p>
                    </div>

                    <div class="details-inner">
                        <h4>Order Date:</h4>
                        <p>{{ \Carbon\Carbon::parse($parcel->created_at)->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="details-inner">
                        <div class="mb-3">
                            <label for="PickupAddress">Barcode <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" alt="Barcode" style="height: 50px; width: 200px;" />
                                <p class="text-center">{{$parcel->barcode_no}}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>
