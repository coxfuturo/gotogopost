<!DOCTYPE html>

<html>

    <head>

        <style>

            body {

                font-family: Arial, sans-serif;

                background-color: #ffffff;

                color: #000000;

                margin: 0;

                padding: 0;

            }



            .container-upper{

                display: flex;

                justify-content: space-between;

                flex-wrap: wrap;

            }

            .container-top {

                width: fit-content;

                margin: 0;

                border: 2px solid #c21414;

                border-radius: 5px;

                box-sizing: border-box;

                page-break-inside: avoid;

                padding: 10px 10px 6px 10px;

                display: flex;

                margin-top:10px;

            }

           

            .details-container {

                justify-content: space-between;

                flex-wrap: wrap;

                width: 250px;

            }



            .right{

                width:200px;

            }

            .details {

                box-sizing: border-box;

            }

            .details h4 {

                margin: 0px 0 0px 0;

                font-size: 14px;

                text-transform: uppercase;

                display: inline-block;

            }



            p {

                margin: 0;

                font-size: 16px;

                display: inline-block;

                width: 100%;

                box-sizing: border-box;

            }

            .details p:last-child {

                border-bottom: none;

            }

            .details strong {

                color: #000000;

                font-weight: bold;

            }

            .details-inner {

            

            }

            @media print {

                body,

                .container {

                    box-shadow: none;

                    margin: 0;

                    padding: 0;

                    background-color: #ffffff;

                    width: auto;

                }

                .details p {

                    border-bottom: none;

                }

            }

        </style>

    </head>

    <body>

        <div class="container-upper">



            <div class="container-top">

                <div class="details-container">

                    <div class="details">

                        <div class="details-inner">

                            <h4>From : </h4><span> {{ $parcel->pickup_name }}</span>

                            <p><span>{{ $parcel->pickup_address }}</span></p>

                            <p>{{ $parcel->pickup_pincode }}</p>

                        </div>

                    </div>

                    <div class="details">

                        <div class="details-inner">

                            <h4>To : </h4><span> {{ $parcel->consignee_name }}</span>

                            <p>{{ $parcel->consignee_address }}</p>

                            <p>{{ $parcel->consignee_pincode }}</p>

                        </div>

                    </div>

                </div>



                <div class="right">

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

    </body>

</html>

