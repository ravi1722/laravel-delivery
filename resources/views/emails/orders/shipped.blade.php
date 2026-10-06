<!DOCTYPE html>
<html>
<head>
    <title>Order Shipped</title>
</head>
<body>
    <h1>Good news, {{ $order->customer_name }}!</h1>
    <p>Your order #{{ $order->id }} has been shipped and is on its way.</p>
</body>
</html>