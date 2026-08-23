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
    {{-- <img src="https://th.bing.com/th/id/R.6724169ea2d15e10dcdb5e958620b39d?rik=XHwmK0LKFqMYeg&riu=http%3a%2f%2fwww.sagedata.com%2fimages%2f2007%2fCode_128_Barcode_Graphic.jpg&ehk=Q6ceH0NgJ0bIdULXJwXXk3kkbeFuzYKAqhrhnah4hOM%3d&risl=&pid=ImgRaw&r=0" alt="Barcode" style="height: 100px; width: 300px;" /> --}}

    <img src="data:image/png;base64,{{ $barcode_image_src }}" alt="Barcode" style="height: 50px; width: 200px;" />
</body>
</html>
