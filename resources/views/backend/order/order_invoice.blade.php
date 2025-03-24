<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Rajput Book Store</title>
    <style>
        /* Global Styles */
        * {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            background-color: #fff;
            padding: 30px;
            margin: 0 auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 26px;
            color: #222;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .contact-info {
            font-size: 14px;
            color: #666;
        }

        /* Details Section */
        .details {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .details div {
            font-size: 14px;
            line-height: 1.6;
        }

        .invoice-details {
            text-align: right;
        }

        .invoice-details h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            color: #222;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            background-color: #007bff;
            color: #fff;
        }

        thead th {
            padding: 10px;
            font-size: 14px;
            text-align: left;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody td {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            text-align: left;
        }

        tfoot td {
            font-weight: bold;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: right;
            background-color: #e9ecef;
        }

        /* Thanks & Signature */
        .thanks {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            color: #555;
        }

        .signature {
            text-align: right;
            margin-top: 40px;
            font-size: 14px;
        }

        .signature p {
            margin-bottom: 5px;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>Rajput Book Store</h1>
        <div class="contact-info">
            <p>Email: rajputbookssupport@gmail.com | Mob: 9584193075 | Gwalior, MP</p>
        </div>
    </div>

    <!-- Details -->
    <div class="details">
        <div class="customer-details">
            <strong>Customer Name:</strong> {{ $order->customer->name }}<br>
            <strong>Email:</strong> {{ $order->customer->email }}<br>
            <strong>Phone:</strong> {{ $order->customer->phone }}<br>
            <strong>Address:</strong> {{ $order->customer->address }}<br>
            <strong>Shop Name:</strong> {{ $order->customer->shopname }}
        </div>
        <div class="invoice-details">
            <h3>Invoice #{{ $order->invoice_no }}</h3>
            <p>
                Order Date: {{ $order->order_date }}<br>
                Order Status: {{ $order->order_status }}<br>
                Payment Status: {{ $order->payment_status }}<br>
                Total Pay: ${{ $order->pay }}<br>
                Total Due: ${{ $order->due }}
            </p>
        </div>
    </div>

    <!-- Products Table -->
    <h3 style="margin-top: 20px;">Products</h3>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Group</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total (+VAT)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderItem as $item)
            <tr>
                <td>
                    <img src="{{ public_path($item->product->product_image) }}" height="40" width="40" style="border-radius: 4px;" alt="Product">
                </td>
                <td>{{ $item->product->product_name }}</td>
                <td>{{ $item->product->product_code }}</td>
                <td>{{ $item->product->group ? $item->product->group->group_name : 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ $item->product->selling_price }}</td>
                <td>${{ $item->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <table>
        <tfoot>
            <tr>
                <td colspan="6">Subtotal:</td>
                <td>${{ $order->total }}</td>
            </tr>
            <tr>
                <td colspan="6">Total:</td>
                <td>${{ $order->total }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Thanks & Signature -->
    <div class="thanks">
        <p>Thank you for shopping with us!</p>
    </div>

    <div class="signature">
        <p>-----------------------------------</p>
        <h5>Authorized Signature</h5>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>For support, contact rajputbookssupport@gmail.com | Powered by Rajput Book Store</p>
    </div>
</div>

</body>
</html>
