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
        width: 7.0cm;   /* Adjust width for 3 columns */
        height: 7.0cm;  /* Adjust height for 4 rows on A4 */
        border: 1px solid #000;
        font-size: 10px;
        margin: 0.3cm;
        box-sizing: border-box;
        padding: 4px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        vertical-align: top;
        padding: 2px;
        font-weight: bold;
    }

    hr {
        border: none;
        border-top: 1px solid #d02626;
    }
    .sapnpadding{
        line-height: 15px;
    }
</style>

</head>
<body>

@php
    $chunks = $data->chunk(6); // Break data into pages of 15 stickers
@endphp

@foreach($chunks as $page)
    <div class="page">
        @foreach($page as $parcel)
        <div class="sticker">
            <table style="width: 100%;">
                <tr>
                    <td colspan="2" style="font-weight: 700; color: #d02626; font-size: 10px;">{{ \App\Models\Admin::GOTOGO_POST_SPEED }}</td>
                    <td colspan="1" style="text-align: right; font-size: 10px;">Sr. No.: 000{{$parcel->id}}</td>
                </tr>
                <tr><td colspan="3"><hr style="margin: 2px 0;"></td></tr>
                <tr>
                    <td colspan="2" style="font-size: 10px;">
                        <span class="sapnpadding">Franchise Id: {{ $linkDetail->franchise_no }}</span><br>
                        <span class="sapnpadding">CPH Id: {{ $linkDetail->cms_no }}</span><br>
                        <span class="sapnpadding">PPH Id: {{ $linkDetail->pph_no }}</span>
                    </td>
                    <td colspan="1" style="text-align: right;">
                        <img src="{{asset('website/images/logonewtransparent.png')}}" style="width:50px; height: 50px;" alt="Logo">
                    </td>
                </tr>
                <tr>
                    
                </tr>
                <tr><td colspan="3"><hr style="margin: 2px 0;"></td></tr>
                <tr>
                    <td colspan="2" style="font-size: 10px;">
                        <span class="sapnpadding">From Name: {{ $parcel->pickup_name }}</span><br>
                        <span class="sapnpadding">City: {{ $parcel->pickup_city }}</span>
                    </td>
                    <td style="font-size: 10px;">
                        <span class="sapnpadding">To Name: {{ $parcel->consignee_name }}</span><br>
                        <span class="sapnpadding">City: {{ $parcel->consignee_city }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 10px;">
                       <span class="sapnpadding"> Weight: {{ $parcel->package_width }}</span><br>
                       <span class="sapnpadding">Amount: ₹{{ $parcel->payment_amount }}</span><br>
                       <span class="sapnpadding">GST: ₹{{ $parcel->payment_amount }}</span><br>
                        <strong>Total Rs.: ₹{{ $parcel->payment_amount }}</strong>
                    </td>

                    <td  style="text-align: center;">
                        <span style="font-size: 10px; font-weight: bold;">Post Speed Packet</span><br>
                        <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" style="width:100%;height: 20px;padding:2px"><br>
                        <span style="font-size: 10px;">{{ $parcel->barcode_no }}</span>
                    </td>
                </tr>
               
                <tr>
                    <td colspan="3" style="font-size: 10px; text-align: center;">
                        <hr>
                        Tracking: www.gotogopost.com
                    </td>
                </tr>
            </table>
        </div>
     @endforeach
    </div>
@endforeach

</body>
</html>
