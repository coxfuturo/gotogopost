<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .page {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            page-break-after: always;
        }

        .sticker {
            width: 5cm;
            height: 3.8cm;
            border: 2px solid rgb(167, 20, 20);
            font-size: 10px;
            text-align: left;
            margin: 0.3cm;
            box-sizing: border-box;
        }

        table {
            width: 100%;
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

        .title-p {
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .title-span {
            border-bottom: 1.5px solid black;
        }
    </style>
</head>
<body>

@php
    $chunks = $data->chunk(15); // Break data into pages of 15 stickers
@endphp

@foreach($chunks as $page)
    <div class="page">
        @foreach($page as $parcel)
        <div class="sticker">
            <p class="title-p"><span class="title-span">{{ \App\Models\Admin::GOTOGO_POST_SPEED }}</span></p>
            <p class="bold-text" style="padding-left: 14px;">
                Franchise NO: <b>{{ $linkDetail->franchise_no }}</b>
            </p>
            <p class="bold-text" style="padding-left: 14px;">
                CPH NO: <b>{{ $linkDetail->cms_no }}</b>
            </p>
            <p class="bold-text" style="padding-left: 14px;">
                PPH NO: <b>{{ $linkDetail->pph_no }}</b>
            </p>

            <table>
                <tr>
                    <td class="left-column">
                        <div>
                            <img src="{{ asset('website/images/logonewtransparent.png') }}" style="width: 50px; height: 40px;" alt="Logo">
                        </div>
                        <p class="tracking-number">amount: ₹{{ $parcel->payment_amount }}</p>
                    </td>
                    <td class="right-column">
                        <div class="barcode" style="text-align: center;">
                            <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" alt="Barcode">
                        </div>
                        <p class="tracking-number">{{ $parcel->barcode_no }}</p>
                    </td>

                    
                </tr>
            </table>

            <p class="bold-text" style="text-align: center; margin-top: -8px;">
                Trade - <b>gotogopost.com</b>
            </p>
        </div>
        @endforeach
    </div>
@endforeach

</body>
</html>
