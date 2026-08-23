<!DOCTYPE html>
<html>
<head>
    <style>

        body{
            box-sizing: border-box;
        }

        .sticker {
            width: 5cm;
            height: 3.8cm;
            border: 2px solid rgb(167, 20, 20);
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
            width: 43%; 
            text-align: center;
        }

        .right-column {
            width: 57%; 
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

        .title-p{
            text-align: center;
            font-size:13px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .title-span{
            border-bottom: 1.5px solid black;
        }

        table{
            height: auto;
            width:auto;
        }
    </style>
</head>
<body>
    <div class="sticker">
        <p class="title-p"><span class="title-span">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</span></p>
        <p class="bold-text" style="padding-left: 14px;">
            <span style="display: inline-block; ">Franchise NO:</span> <b>12345</b>
        </p>
        <p class="bold-text" style="padding-left: 14px;">
            <span style="display: inline-block; ">CPH NO:</span> <b>32345</b>
        </p>
        <p class="bold-text" style="padding-left: 14px;">
            <span style="display: inline-block; ">PPH NO:</span> <b>43252</b>
        </p>
        
        <table>
            <tr>
                <!-- Left Column (Logo) -->
                {{-- <td class="left-column">
                    <img src="{{asset('website/images/logonewtransparent.png')}}" style="width: 50px;" alt="Logo">
                </td> --}}
                <td class="left-column">
                    <!-- Barcode and Tracking Number Inside Right Column -->
                    <div  style="text-align: center;">
                        <img src="{{asset('website/images/logonewtransparent.png')}}" style="width: 50px;height:40px" alt="Logo">
                    </div>
                    <p class="tracking-number">amount:₹ 1550</p>
                </td>

                <!-- Right Column (Content + Barcode) -->
                <td class="right-column">
                    <!-- Barcode and Tracking Number Inside Right Column -->
                    <div class="barcode" style="text-align: center;">
                        <img src="https://profit.pakistantoday.com.pk/wp-content/uploads/2023/04/Barcode_32896.jpg" alt="Barcode" style="height: 40px; width: 100px;" />
                    </div>
                    <p class="tracking-number">US100004744CO</p>
                </td>
            </tr>
        </table>
        <p class="bold-text" style="padding-left: 14px;text-align:center;margin-top:-1px">
            <span style="display: inline-block;">Trade - </span> <b>gotogopost.com</b>
        </p>
    </div>
</body>
</html>
