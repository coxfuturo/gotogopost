<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOTOGO Post OTP Varification</title>
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
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        .header img {
            margin-bottom: 10px;
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
            color: #fff;
            margin: 5px 0;
        }

        .support {
            padding: 20px;
            text-align: center;
            background-color: #e7feff;
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
            color: #777777;
            background-color: #f9f9f9;
        }

        .footer a {
            color: #999;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header" style="text-align: center; padding: 20px; background-color: #f4f4f4;">
            <img src="{{asset('website/images/logonewtransparent.png')}}" alt="GOTOGO Post" width="150">
            <h1>Forgot Password Confirmation</h1>
            <p>Please use the OTP below to reset your password.</p>
        </div>

        <div class="content" style="text-align: center; padding: 20px; border: 1px solid #dddddd;">
            <p>We've received a request to reset your password!</p>
            <p>To proceed with resetting your password, please use the OTP provided below:</p>

            <div class="inline-info" style="margin: 20px 0;">
                <p style="font-size: 20px;"><strong>OTP:</strong>
                    <span style="color: #ffffff; background-color: #4CAF50; padding: 10px 20px; border-radius: 5px;">{{$otp}}</span>
                </p>
            </div>

            <p>If you did not request a password reset, please ignore this email.</p>
        </div>

        <div class="support">
            <p>We're here to help!</p>
            <p>For assistance, call <strong>+91 9810657990</strong> or email us at <strong><span>corp.office@gotogopost.in</span></strong></p>
            <img src="{{asset('website/images/emp1.jpg')}}" alt="Support Team">
            <img src="{{asset('website/images/emp2.png')}}" alt="Support Team">
            <img src="{{asset('website/images/emp3.jpg')}}" alt="Support Team">
        </div>

        <div class="footer">
            <p>Office No. 955, 9th Floor, Gaur City Mall CO1, BHG, Sec-4, Greater Noida West, Gautam Buddha Nagar, (U.P)-201318</p>
            <p>Thank you for being with us! We’re excited to assist you with your account security.</p>
        </div>
    </div>
</body>


</html>