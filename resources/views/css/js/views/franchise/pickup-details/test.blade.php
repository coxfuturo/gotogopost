<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Placed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #001f3f;
            color: #ffffff;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .header img {
            max-width: 100px;
        }
        .content {
            padding: 20px;
            text-align: left;
        }
        .content h2 {
            text-align: center;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
        }
        .details {
            margin: 20px 0;
        }
        .details h3 {
            border-bottom: 1px solid #cccccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .details p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #001f3f;
            color: #ffffff;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .footer p {
            margin: 5px 0;
            font-size: 14px;
        }
        .footer a {
            color: #ffffff;
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
    <div class="email-container">
        <div class="header">
            <img src="{{asset('website/images/logonew.jpeg')}}" style="width: 90px;height:50px;"  alt="Logo">
        </div>
        <div class="content">
            <h2>Order Placed</h2>
            <p>Hello,</p>
            <p>Your order has been successfully placed.</p>
            <p>Thank you for choosing our courier services. We are delighted to serve you and ensure that your package is delivered safely and on time. Below, you will find the details of your order, including sender and receiver information, package specifics, and pricing.</p>
            <p>Our team is dedicated to providing top-notch service and will keep you informed throughout the delivery process. You will receive updates via email and SMS as your package progresses through our system.</p>
            <div class="details">
                <h3>Sender Details</h3>
                <p><strong>Name:</strong> John Doe</p>
                <p><strong>Address:</strong> 123 Sender Street, Sender City, ST 12345</p>
                <p><strong>Phone:</strong> (123) 456-7890</p>
                <p><strong>Email:</strong> sender@example.com</p>
            </div>
            <div class="details">
                <h3>Receiver Details</h3>
                <p><strong>Name:</strong> Jane Smith</p>
                <p><strong>Address:</strong> 456 Receiver Avenue, Receiver City, ST 67890</p>
                <p><strong>Phone:</strong> (987) 654-3210</p>
                <p><strong>Email:</strong> receiver@example.com</p>
            </div>
            <div class="details">
                <h3>Packet Details</h3>
                <p><strong>Weight:</strong> 2 kg</p>
                <p><strong>Dimensions:</strong> 30x20x10 cm</p>
                <p><strong>Tracking Number:</strong> ABC123XYZ</p>
            </div>
            <div class="details">
                <h3>Price</h3>
                <p><strong>Total Price:</strong> $29.99</p>
            </div>
            <p>Thank you for trusting us with your delivery needs. We look forward to serving you again in the future.</p>
        </div>
        <div class="footer">
            <p>Contact</p>
            <p>1912 Mcwhorter Road, FL 11223</p>
            <p>+111 222 333 | Info@company.com</p>
            <div class="social-icons">
                <a href="https://www.facebook.com/share/BVJeAPEcHNBwCnN9/?mibextid=qi2Omg" target="_blank"><i class="fa fa-facebook"></i></a>
                <a href="" target="_blank"><i class="fa fa-whatsapp"></i></a>
                <a href="https://www.instagram.com/gotogopost/?utm_source=qr&igsh=MWQ3aDV6NGlkNmZvZg%3D%3D" target="_blank"><i class="fa fa-instagram"></i></a>
                <a href="https://www.youtube.com/channel/UCkLQWa6_bv4CnuQZqdh-l4g" target="_blank"><i class="fa fa-youtube"></i></a>
            </div>
            <p>Company © All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
