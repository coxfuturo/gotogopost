<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOTOGO Post Registration Mail</title>
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
            color:#fff;
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
    <div class="header">
        <img src="{{asset('website/images/logonewtransparent.png')}}" alt="Campaign Monitor" width="150">
        <h1>Welcome to GOTOGO Post</h1>
        <p>You're all set. Become a part of our leading parcel delivery network — join a successful network and expand your business with confidence!</p>
        <p>You can Login your account after activation</p>
        <a href="{{$route}}" class="btn">LOG IN TO YOUR NEW ACCOUNT</a>
    </div>

    <div class="content">
        <p>Your new account</p>
        <div class="inline-info">
            <p><strong>Username:</strong>  <span style="color: white">{{$username}}</span> </p>
            <p><strong>Password:</strong> {{$password}}</p>
        </div>
    </div>

    <div class="support">
        <p>We're here to help!</p>
        <p>To talk with our  experts, call <strong>+91 9810657990</strong> or email us at <strong>  <span>corp.office@gotogopost.in</span></strong></p>
        <img src="{{asset('website/images/emp1.jpg')}}" alt="Support Team">
        <img src="{{asset('website/images/emp2.png')}}" alt="Support Team">
        <img src="{{asset('website/images/emp3.jpg')}}" alt="Support Team">
    </div>

    <div class="footer">
        <p>Ofice No. 955,9th Floor,Gaur City Mall CO1,BHG,Sec-4,Greater Noida West,Gautam Bddha Nagar,(U.P)-201318</p>
        <p>Thank you for joining us! We’re excited to have you on board and can’t wait for you to experience all the benefits of our service.</p>
    </div>
    
</div>

</body>
</html>
