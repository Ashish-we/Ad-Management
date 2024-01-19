<?php

use App\Models\Customer;
use App\Models\Invoice_Items;

$customer = Customer::where('phone', $invoice->customer)->first();
$invoiceItems = Invoice_Items::where('invoice_id', $invoice->id)->get();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <!-- Add your own styles or link a CSS file for custom styling -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        .invoice {
            border: 1px solid #ddd;
            padding: 20px;
            max-width: 800px;
            margin: auto;
            background-color: #f9f9f9;
        }

        .invoice h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .invoice-address {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .invoice-total {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-weight: bold;
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

    <div class="invoice">
        <h2>Invoice</h2>

        <div class="invoice-address">
            <div>
                <strong>Your Company Name</strong><br><br>
                Your Company Address<br><br>
                Your Company Contact
            </div>

            <div>
                <strong>To:</strong><br><br>
                {{ $customer->name}}<br><br>
                {{ $customer->address }}<br><br>
                {{ $customer->phone }}
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Tax</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoiceItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->rate }}</td>
                    <td>{{ $item->tax }}</td>
                    <td>{{ $item->amount }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <?php
        $t_amount = 0;
        foreach ($invoiceItems as $item) {
            $t_amount = $t_amount + $item->amount;
        }
        ?>
        <div class="invoice-total">
            <div>
                <strong>Description:</strong><br>
                {{ $invoice->description }}
            </div>

            <div>
                <strong>Total Amount:</strong> {{ $t_amount }}
            </div>
        </div>
        <div class="footer">
            <script src="{{asset('https://kit.fontawesome.com/c8371491b6.js')}}" crossorigin="anonymous"></script>
            <div>
                <a style="font-size:xx-large;" href="{{ URL('/invoice/pdf_gen_invoice/'. $invoice->id) }}"><i class="fa fa-solid fa-file-arrow-down"></i></a>
            </div>
        </div>
    </div>

</body>


</html>