<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
    include('config/config.php');
    include('config/checklogin.php');
    check_login();
    require_once('partials/_head.php');
    require_once('partials/_analytics.php');
    ?>
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: all 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .icon {
            padding: 15px;
            font-size: 24px;
        }
        .header {
            background-size: cover;
            background-position: center;
            position: relative;
            border-radius: 10px;
            color: white;
            padding: 50px 0;
        }
        .header .mask {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
        }
        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .badge {
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .status-paid {
            background-color: #28a745;
            color: white;
        }
        .status-unpaid {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div class="header" style="background-image: url('assets/img/theme/restro00.jpg');">
            <span class="mask">
                <div class="container-fluid text-center">
                    <h2 class="font-weight-bold">Dashboard</h2>
                </div>
            </span>
        </div>
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="card p-4 text-center">
                        <h5 class="text-muted">Customers</h5>
                        <span class="h2 font-weight-bold"><?php echo $customers; ?></span>
                        <div class="icon bg-danger text-white rounded-circle mx-auto mt-2">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card p-4 text-center">
                        <h5 class="text-muted">Products</h5>
                        <span class="h2 font-weight-bold"><?php echo $products; ?></span>
                        <div class="icon bg-primary text-white rounded-circle mx-auto mt-2">
                            <i class="fas fa-utensils"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card p-4 text-center">
                        <h5 class="text-muted">Orders</h5>
                        <span class="h2 font-weight-bold"><?php echo $orders; ?></span>
                        <div class="icon bg-warning text-white rounded-circle mx-auto mt-2">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card p-4 text-center">
                        <h5 class="text-muted">Sales</h5>
                        <span class="h2 font-weight-bold">₱<?php echo number_format($sales, 2); ?></span>
                        <div class="icon bg-success text-white rounded-circle mx-auto mt-2">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white d-flex justify-content-between">
                            <h3 class="mb-0">Recent Orders</h3>
                            <a href="orders_reports.php" class="btn btn-light btn-sm">See all</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_orders ORDER BY created_at DESC LIMIT 7";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        $total = ($order->prod_price * $order->prod_qty);
                                    ?>
                                        <tr>
                                            <td><?php echo $order->order_code; ?></td>
                                            <td><?php echo $order->customer_name; ?></td>
                                            <td><?php echo $order->prod_name; ?></td>
                                            <td>₱<?php echo number_format($order->prod_price, 2); ?></td>
                                            <td><?php echo $order->prod_qty; ?></td>
                                            <td>₱<?php echo number_format($total, 2); ?></td>
                                            <td>
                                                <span class='badge <?php echo ($order->order_status == '') ? "status-unpaid" : "status-paid"; ?>'>
                                                    <?php echo ($order->order_status == '') ? "Not Paid" : $order->order_status; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/M/Y g:i A', strtotime($order->created_at)); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
