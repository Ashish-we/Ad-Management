<!-- resources/views/emails/ad-receipt.blade.php -->
<?php

use App\Models\Customer;


$customer = Customer::where('phone', $ad->customer)->first();

?>
<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Receipt</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 5px;
        }

        h2 {
            color: #3498db;
        }

        p {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Ad Receipt</h2>


        <p>Dear {{ $customer->name }},</p>

        <p>Your ad has been created successfully. Below are the details:</p>

        <table>
            <tr>
                <th>Attribute</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Customer</td>
                <td>{{ $customer->name }}</td>
            </tr>
            <tr>
                <td>Amount (NRP)</td>
                <td>Rs {{ $ad->NRP }}</td>
            </tr>
        </table>

        <p>Thank you for choosing our services.</p>

        <div class="footer">
            <p>This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>

</html> -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Request</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eeeeee;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: px;
            border: 1px solid #dddddd;
        }

        .header {
            background-color: #dce1e9;
            padding: 6px;
            text-align: center;
        }

        .header img {
            max-width: 180px;
        }

        .content {
            padding: 6px;
            text-align: center;
        }

        .content h2 {
            color: #0047ab;
        }

        .content p {
            line-height: 1.5;
            color: #333333;
        }

        .footer {
            background-color: #f2f2f2;
            padding: 6px;
            text-align: center;
            font-size: 12px;
            color: #666666;
        }

        .button {
            background-color: #28a745;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }

        .button:hover {
            background-color: #218838;
        }

        .highlight {
            color: #0047ab;
            font-weight: 700;
        }

        .cost {
            font-size: 20px;
            color: #ff5722;
            font-weight: 700;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <!-- Replace 'logo.png' with the actual logo image file -->
            <img src="https://mpg.com.np/wp-content/uploads/2023/03/mpg-solution-logo-3.png" alt="MPG Solution Private Limited Logo">
        </div>
        <div class="content">
            <h2>Ad Campaign Applied</h2>
            <p>Dear {{ $customer->name }},</p>
            <p>Great! Your social media campaign is all set to start.</p>
            <p>To proceed with the activation, kindly pay the <span class="highlight">COST AMOUNT</span> at your earliest convenience.</p>
            <p class="cost">Rs {{ $ad->NRP }}</p>
            <p>Payment can be made through eSewa, <b>Khalti, ImePay, Fonepay or Direct Deposit</b> details of which have been shared via WhatsApp.</p>
            <a href="https://wa.me/9779856000601" class="button" style="color: white;">Contact Us on WhatsApp</a>
            <p>If you require any assistance, our team is available from 9 AM to 5 PM, Sunday to Friday. Please note that our office remains closed on designated red days in the calendar.</p>
            <!-- Add this inside the <body> tag where you want the download button to appear -->
            <div class="content">
                <!-- Other content above -->
                <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" style="color: white;" class="button" download="PaymentReceipt.pdf">Download Receipt as PDF</a>
                <!-- Other content below -->
            </div>

        </div>
        <div class="footer">
            <p>MPG Solution Private Limited</p>
            <p>Pokhara 15, Kaski, Nepal</p>
            <p>Email: <a href="mailto:support@mpg.com.np">support@mpg.com.np</a></p>
            <p>© 2017- 2024 MPG Solution Private Limited. All Rights Reserved.</p>
        </div>
    </div>
</body>

</html>