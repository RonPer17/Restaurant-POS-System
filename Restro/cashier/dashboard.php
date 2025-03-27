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
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .icon {
            padding: 15px;
            font-size: 26px;
        }
        .badge {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 8px;
        }
        .table-hover tbody tr:hover {
            background: #f8f9fa;
        }
        .btn-primary {
            background-color: #007bff;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div class="header bg-gradient-dark pb-8 pt-5">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row">
                        <?php 
                        $cards = [
                            ['title' => 'Customers', 'value' => $customers, 'icon' => 'fas fa-users', 'bg' => 'bg-danger'],
                            ['title' => 'Products', 'value' => $products, 'icon' => 'fas fa-utensils', 'bg' => 'bg-primary'],
                            ['title' => 'Orders', 'value' => $orders, 'icon' => 'fas fa-shopping-cart', 'bg' => 'bg-warning'],
                            ['title' => 'Sales', 'value' => '₱' . number_format($sales, 2), 'icon' => 'fas fa-money-bill-wave', 'bg' => 'bg-success']
                        ];
                        foreach ($cards as $card) { ?>
                            <div class="col-xl-3 col-lg-6 mb-4">
                                <div class="card shadow text-center p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h5 class="text-muted"><?php echo $card['title']; ?></h5>
                                            <span class="h2 font-weight-bold"><?php echo $card['value']; ?></span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape <?php echo $card['bg']; ?> text-white rounded-circle">
                                                <i class="<?php echo $card['icon']; ?>"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h3 class="mb-0">Recent Orders</h3>
                    <a href="orders_reports.php" class="btn btn-light btn-sm">See all</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
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
                                        <span class="badge <?php echo ($order->order_status == '') ? 'badge-danger' : 'badge-success'; ?>">
                                            <?php echo ($order->order_status == '') ? 'Not Paid' : $order->order_status; ?>
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

        <?php require_once('partials/_footer.php'); ?>
    </div>

    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
