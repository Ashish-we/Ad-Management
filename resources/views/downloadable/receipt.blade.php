<!-- resources/views/emails/ad-receipt.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Receipt</title>
    <!-- Open Graph Protocol meta tags for social media sharing -->
    <meta property="og:title" content="Receipt">
    <meta property="og:description" content="Receipt of the service you have just purchased">
    <!-- <meta property="og:image" content="{{ asset('path-to-your-image.jpg') }}"> -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <style>
        /* Add your custom styles here */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 600px;
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

        <?php

        use App\Models\Customer;


        $customer = Customer::where('phone', $ad->customer)->first();

        ?>

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
            <!-- <tr>
                <td>Amount (USD)</td>
                <td>${{ $ad->USD }}</td>
            </tr> -->
            <tr>
                <td>Amount (NRP)</td>
                <td>Rs {{ $ad->NRP }}</td>
            </tr>
            <!-- Add more rows for other details -->
        </table>

        <p>Thank you for choosing our services.</p>

        <div class="footer">
            <script src="{{asset('https://kit.fontawesome.com/c8371491b6.js')}}" crossorigin="anonymous"></script>
            <div>
                <!-- Facebook Share Button -->
                <a style="margin-right: 10px; font-size:large" href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank">
                    <i class="fa-brands fa-facebook-f"></i></a>

                <a style="margin-right: 10px; font-size:large" href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text=Your%20Page%20Title" target="_blank">
                    <i class="fa-brands fa-twitter"></i></a>


                <!-- WhatsApp Share Button -->
                <a style="margin-right: 300px; font-size:large" href="whatsapp://send?text={{ rawurlencode('Check out this page for Your Recipt : ' . url()->current()) }}" data-action="share/whatsapp/share">
                    <i class="fa-brands fa-whatsapp"></i></a>
                <!-- WhatsApp Share Button with Clickable Link -->
                <!-- <a href="whatsapp://send?text={{ rawurlencode(url()->current()) }}" data-action="share/whatsapp/share">
                    <i class="fa-brands fa-twitter"></i>
                </a> -->
                <a style="margin-right: 10px; font-size:large" href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}"><i class="fa fa-solid fa-file-arrow-down"></i></a>
            </div>
        </div>
    </div>
</body>

</html>