<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

$order_code = $_GET['order_code'];
$ret = "SELECT * FROM rpos_orders WHERE order_code = ?";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param("s", $order_code);
$stmt->execute();
$res = $stmt->get_result();
$order = $res->fetch_object();

if (!$order) {
    die("Order not found");
}

$total = $order->prod_price * $order->prod_qty;
$tax = $total * 0.14;
$grand_total = $total + $tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin-top: 20px;
        }
        .receipt-container {
            max-width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .receipt-header {
            text-align: center;
        }
        .receipt-header h2 {
            font-weight: 600;
        }
        .receipt-footer {
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .receipt-container, .receipt-container * {
                visibility: visible;
            }
            .receipt-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="receipt-container">
            <div class="receipt-header">
                <h2>Receipt</h2>
                <p><strong>Hotzarap Bulalohan & Kambingan</strong></p>
                <p>Q24H+PM5, Congressional Rd Ext, QC, Metro Manila</p>
                <p>(+63) 992-610-8351</p>
                <hr>
                <p>Date: <?php echo date('d/M/Y g:i A', strtotime($order->created_at)); ?></p>
                <p><strong>Receipt #: <?php echo $order->order_code; ?></strong></p>
            </div>
            <table class="table table-borderless">
                <tr>
                    <td><strong>Item</strong></td>
                    <td class="text-end"><?php echo $order->prod_name; ?></td>
                </tr>
                <tr>
                    <td><strong>Quantity</strong></td>
                    <td class="text-end"><?php echo $order->prod_qty; ?></td>
                </tr>
                <tr>
                    <td><strong>Unit Price</strong></td>
                    <td class="text-end">₱<?php echo number_format($order->prod_price, 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Subtotal</strong></td>
                    <td class="text-end">₱<?php echo number_format($total, 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Tax (14%)</strong></td>
                    <td class="text-end">₱<?php echo number_format($tax, 2); ?></td>
                </tr>
                <tr class="fw-bold text-danger">
                    <td><strong>Total</strong></td>
                    <td class="text-end">₱<?php echo number_format($grand_total, 2); ?></td>
                </tr>
            </table>
            <hr>
            <div class="text-center">
                <button class="btn btn-primary w-100" onclick="window.print();">Print Receipt</button>
            </div>
            <div class="receipt-footer mt-3">
                <p>Thank you for dining with us!</p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
