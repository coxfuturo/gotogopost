<!DOCTYPE html>
<html>
<head>
    <style>
        .sticker {
            width: 5cm;
            height: 3.8cm;
            border: 2px solid rgb(167, 20, 20);
            padding: 2px;
            font-family: Arial, sans-serif;
            font-size: 10px;
            text-align: left;
        }

        table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px;
            vertical-align: middle;
        }

        .left-column {
            width: 40%; 
            text-align: center;
        }

        .right-column {
            width: 60%; 
            text-align: left;
        }

        .logo img {
            width: 50px;
            height: auto;
        }

        .bold-text {
            font-weight: bold;
        }

        .barcode img {
            width: 100px;
            height: 40px;
        }

        .tracking-number {
            text-align: center;
            font-weight: bold;
        }

        p {
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <div class="sticker">
        <table>
            <tr>
                <!-- Left Column (Logo) -->
                <td class="left-column">
                    <img src="{{asset('website/images/logonewtransparent.png')}}" style="width: 50px;" alt="Logo">
                </td>

                <!-- Right Column (Content + Barcode) -->
                <td class="right-column">
                    <p class="bold-text">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</p>
                    <p class="bold-text" >Business Associate NO: <b>{{ $linkDetail->franchise_no }}</b></p>
                    <p class="bold-text" >CPH NO: <b>{{ $linkDetail->cms_no }}</b></p>
                    <p class="bold-text" >PPH NO: <b>{{ $linkDetail->pph_no }}</b></p>

                    <!-- Barcode and Tracking Number Inside Right Column -->
                    <div class="barcode" style="text-align: center;">
                        <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" alt="Barcode" style="height: 50px; width: 100px;" />

                    </div>
                    <p class="tracking-number">{{$parcel->barcode_no}}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
