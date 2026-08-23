<!DOCTYPE html>
<html>
<head>
<style>
     @page {
      margin: 0;
    }
    
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
        height: 5.5cm;  /* Adjust height for 4 rows on A4 */
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
        /* font-weight: 900; */
    }
    .sapnpadding{
        line-height: 15px;
        /* font-weight: 900; */
    }
</style>

</head>
<body>

@php
    $chunks = $data->chunk(1); // Break data into pages of 15 stickers
@endphp

@foreach($chunks as $page)
    <div class="page">
        @foreach($page as $parcel)
        <div class="sticker">
            <table style="width: 100%;">
                <tr>
                    <td colspan="2" style="font-weight: 700; color: #d02626; font-size: 10px;">GOTOGO POST</td>
                    <td style="text-align: right; font-size: 10px;">SNo. {{ str_pad($parcel->id, 6, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr><td colspan="3"><hr style="margin: 2px 0;"></td></tr>
                <tr>
                   <td colspan="2" style="font-size: 10px;">
        <span class="sapnpadding"><strong>Franchise Id:</strong> {{ $linkDetail->franchise_no }}</span>,
        <span class="sapnpadding"><strong>CPH Id:</strong> {{ $linkDetail->cms_no }}</span>,<br>
        <span class="sapnpadding"><strong>PPH Id:</strong> {{ $linkDetail->pph_no }}</span><br>
        <span style="text-align: right;"><b>Time: {{ $parcel->created_at->format('h:i A') }} </b></span>
    </td>
    <td style="text-align: right;">
       
        <img src="{{asset('website/images/logonewtransparent.png')}}" style="width:50px; height: 50px;" alt="Logo">
    </td>
                </tr>
                <tr>
                    
                </tr>
                <tr><td colspan="3"><hr style="margin: 2px 0;"></td></tr>
                <tr>
                    <td colspan="2" style="font-size: 10px;">
                        <!-- <span class="sapnpadding">From</span><br> -->
                        <!-- <span class="sapnpadding">From Name: {{ $parcel->pickup_name }}</span><br> -->
                        <span class="sapnpadding">From City: {{ $parcel->pickup_city }}</span>
                    </td>
                    <td style="font-size: 10px;">
                        <!-- <span class="sapnpadding">To Name: {{ $parcel->consignee_name }}</span><br>-->
                         <!-- <span class="sapnpadding">To </span><br> -->
                        <span class="sapnpadding">To City: {{ $parcel->consignee_city }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 10px;">
                       <span class="sapnpadding"> Weight: {{ $parcel->package_weight }}</span><br>
                       <span class="sapnpadding">Amount: ₹{{ number_format($parcel->payment_amount / 1.18, 2) }}</span><br>
                       <span class="sapnpadding">GST: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;₹{{ number_format($parcel->payment_amount - ($parcel->payment_amount / 1.18), 2) }}</span><br>
                        <strong>Total Rs.: ₹{{ $parcel->payment_amount }}</strong>
                    </td>

                    <td  style="text-align: center;">
                        <span style="font-size: 10px; font-weight: bold;">
                          @switch($type)
    @case(1)
        {{ \App\Models\Admin::GOTOGO_POST_SPEED }}
        @break

    @case(2)
        {{ \App\Models\Admin::GOTOGO_POST_BUSINESS }}
        @break

    @default
        {{ \App\Models\Admin::GOTOGO_POST_REGISTERED }}
@endswitch

                            
                        </span><br>
                        <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" style="width:100%;height: 20px;padding:2px"><br>
                        <span style="font-size: 10px;">{{ $parcel->barcode_no }}</span> <br><hr>
                        <span style="font-size: 9px; text-align: center;"> Tracking: www.gotogopost.com</span>
                    </td>
                </tr>
               
                <!-- <tr>
                    <td colspan="3" style="font-size: 10px; text-align: center;">
                        <hr>
                        Tracking: www.gotogopost.com<br>
                         Toll Free No: 18001231617 
                    </td>
                </tr> -->
            </table>
        </div>
     @endforeach
    </div>
@endforeach

</body>
</html>
