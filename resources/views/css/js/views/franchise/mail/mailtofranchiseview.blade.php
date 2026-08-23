<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOTOGO Post Mail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
            font-size: 14px;
        }
        .container {
            max-width: 600px;
            margin: 0px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #e7feff; 
            color: #333;
            text-align: center;
            padding: 10px;
            border-bottom: 1px solid #ddd; 
        }
        .header img {
            width: 90px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            text-align: center;
            background: #063b4a;
        }
        .content p {
            font-size: 14px;
            color: #fff;
            line-height: 1.6;

        }
        .btn {
            display: inline-block;
            background-color: #5cb85c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 14px;
        }
        .account-info {
            background-color: #f5f5f5;
            padding: 20px;
            margin-top: 20px;
            text-align: center;
        }
        .account-info p {
            font-size: 14px;
            color:#fff;
            margin: 5px 0;
        }
        .support {
            padding: 20px;
            background-color: white;
        }
        .support p {
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }
        .support img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 10px;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: white;
            background: #063b4a;
            
        }
        .footer a {
            color: #999;
            text-decoration: none;
        }

        .social-icons {
            margin-top: 10px;
        }
        .social-icons a {
            color: #ffffff;
            margin: 0 5px;
            font-size: 24px;
            text-decoration: none;
        }
    </style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    

<div class="container">
    <div class="header">
        <img src="{{asset('website/images/logonewtransparent.png')}}" alt="Campaign Monitor" width="150">
    </div>

    <div class="support">

         <!-- Sender (From) Details -->
         <div class="details-section">
            <p><strong>Sender Details:</strong></p>
            <p><strong>Name:</strong> {{ $fromFranchiseDetails['name'] ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $fromFranchiseDetails['email'] ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $fromFranchiseDetails['mobile'] ?? 'N/A' }}</p>
            <p><strong>Pincode:</strong> {{ $fromFranchiseDetails['pincode'] ?? 'N/A' }}</p>
        </div>

        <hr>
        <!-- Receiver (To) Details -->
        <div class="details-section">
            <p><strong>Receiver Details:</strong></p>
            <p><strong>Phone:</strong> {{ $toPhone }}</p>
            <p><strong>Email:</strong> {{ $toEmail }}</p>
            <p><strong>Address:</strong> {{ $toAddress }}</p>
        </div>
        <hr>
        <!-- Email Content -->

        <div class="highlight">
                {!! $emailbody !!}
        </div>
   
    </div>

    <div class="footer">

        <div class="social-icons">
            <a href="https://www.facebook.com/share/BVJeAPEcHNBwCnN9/?mibextid=qi2Omg" target="_blank"><i class="fa fa-facebook"></i></a>
            <a href="" target="_blank"><i class="fa fa-whatsapp"></i></a>
            <a href="https://www.instagram.com/gotogopost/?utm_source=qr&igsh=MWQ3aDV6NGlkNmZvZg%3D%3D" target="_blank"><i class="fa fa-instagram"></i></a>
            <a href="https://www.youtube.com/channel/UCkLQWa6_bv4CnuQZqdh-l4g" target="_blank"><i class="fa fa-youtube"></i></a>
        </div>
        <p>Ofice No. 955,9th Floor,Gaur City Mall CO1,BHG,Sec-4,Greater Noida West,Gautam Bddha Nagar,(U.P)-201318</p>
    </div>
    
</div>

</body>
</html>
