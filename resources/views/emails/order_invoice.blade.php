<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">

    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; box-shadow: 0px 0px 10px #cccccc;">
        <!-- Header -->
        <tr>
            <td style="background: #2c3e50; padding: 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                <h1 style="color: #ffffff; margin: 0;">Rajput Book Store</h1>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding: 20px;">
                <p style="font-size: 18px; color: #333;">Hello {{ $order->customer->name ?? 'Valued Customer' }},</p>
                <p style="font-size: 16px; color: #555;">
                    Thank you for shopping with us! Your order <strong>#{{ $order->invoice_no }}</strong> has been successfully processed.
                </p>

                <!-- Order Summary -->
                <table width="100%" cellpadding="5" cellspacing="0" style="margin-top: 15px; border-collapse: collapse;">
                    <tr>
                        <td style="font-size: 16px; font-weight: bold;">Order Date:</td>
                        <td style="font-size: 16px;">{{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px; font-weight: bold;">Total Amount:</td>
                        <td style="font-size: 16px; color: #27ae60;">${{ ($order->total) }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px; font-weight: bold;">Payment Status:</td>
                        <td style="font-size: 16px; color: #e74c3c;">{{ ucfirst($order->payment_status) }}</td>
                    </tr>
                </table>

                <!-- View Order Button -->
                <p style="text-align: center; margin-top: 20px;">
                    <a href="{{ url('/orders/' . $order->id) }}" style="background: #27ae60; color: #ffffff; padding: 10px 20px; text-decoration: none; font-size: 16px; border-radius: 5px;">
                        View Your Order
                    </a>
                </p>

                <!-- Need Help? -->
                <p style="background: #f9f9f9; padding: 15px; font-size: 14px; border-left: 5px solid #27ae60; margin-top: 20px;">
                    📌 **Need Assistance?** If you have any questions, feel free to reach out to our support team.
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background: #2c3e50; padding: 15px; text-align: center; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                <p style="color: #ffffff; margin: 0; font-size: 14px;">
                    📞 Support: +91-XXXXXXXXXX | ✉️ Email: support@rajputbookstore.com
                </p>
                <p style="color: #ffffff; margin: 5px 0 0 0; font-size: 14px;">
                    🌐 <a href="https://rajputbookstore.com" style="color: #1abc9c; text-decoration: none;">Visit Our Website</a>
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
