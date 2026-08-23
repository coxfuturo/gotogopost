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
    }
    .sapnpadding{
        line-height: 15px;
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
                    <td colspan="2" style="font-weight: 700; color: #d02626; font-size: 10px;">ALL INDIA POST</td>
                    <td style="text-align: right; font-size: 10px;">SR. NO.: 000{{$parcel->id}}</td>
                </tr>
                <tr><td colspan="3"><hr style="margin: 2px 0;"></td></tr>
                <tr>
                   <td colspan="2" style="font-size: 8px; width:60%">
                   
       
       <span class="sapnpadding"><strong>BNPL CODE: NOIDA SP-</strong>  {{ $linkDetail->bnpl_no }}</span><br>
        <span class="sapnpadding"><strong>CONTRACT ID:</strong> {{ $linkDetail->contract_id }}</span>,<br>
        <span class="sapnpadding"><strong>CUSTOMER ID:</strong> 0000056219</span>,<br>
        <span style="font-size: 8px;"><strong>GST:</strong>{{$franchise->gst_number}}</span>
    <td style="width: 40%;text-align: right;border:none">
       
        <img src="{{asset('website/images/logo4.jpeg')}}" style="width:70px; height: 50px;border:none" alt="Logo"><br>
       
       <span style="text-align: right;">TIME: {{ $parcel->created_at->format('h:i A') }} </span>
    </td>
                </tr>
</table>
                    <table style="width: 100%;">
                
                <tr><td colspan="3"><hr style="margin: 2px 0;"></td></tr>
                <tr>
                    <td colspan="2" style="font-size: 8px;width:40%">
                        <!-- <span class="sapnpadding">From</span><br> -->
                        <!-- <span class="sapnpadding">From Name: {{ $parcel->pickup_name }}</span><br> -->
                        <span class="sapnpadding">FROM : {{ $parcel->pickup_city }}</span>
                    </td>
                    <td style="font-size: 8px;width:60%">
                        <!-- <span class="sapnpadding">To Name: {{ $parcel->consignee_name }}</span><br>-->
                         <!-- <span class="sapnpadding">To </span><br> -->
                        <span class="sapnpadding">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TO : {{ $parcel->consignee_city }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 8px;">
                       <span class="sapnpadding"> WEIGHT: {{ $parcel->package_weight }}</span><br>
                       <span class="sapnpadding">AMOUNT: ₹{{ number_format($parcel->payment_amount / 1.18, 2) }}</span><br>
                       <span class="sapnpadding">GST: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;₹{{ number_format($parcel->payment_amount - ($parcel->payment_amount / 1.18), 2) }}</span><br>
                        <strong>TOTAL RS.: ₹{{ $parcel->payment_amount }}</strong>
                    </td>

                    <td  style="text-align: center;">
                        <span style="font-size: 10px; font-weight: bold;">
                          @switch($type)
    @case(5)
        {{ \App\Models\Admin::INDIA_POST_SPEED }}
        @break

    @default
        {{ \App\Models\Admin::INDIA_POST_BUSINESS }}
@endswitch

                            
                        </span><br>
                        <img src="data:image/png;base64,{{ $parcel->barcode_image_src }}" style="width:100%;height: 20px;padding:2px"><br>
                        <span style="font-size: 10px;">{{ $parcel->barcode_no }}</span> <br><hr>
                        <span style="font-size: 9px; text-align: center;"> Tracking: www.indiapost.gov.in</span>
                    </td>
                </tr>
               
                
            </table>
        </div>
     @endforeach
    </div>
@endforeach

</body>
</html>
