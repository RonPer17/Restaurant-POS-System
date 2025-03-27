<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
require_once('partials/_head.php');
?>
<body>
    <!-- Sidenav -->
    <?php require_once('partials/_sidebar.php'); ?>

    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php require_once('partials/_topnav.php'); ?>

        <!-- Header -->
        <div class="header pb-8 pt-5 pt-md-8" style="background: url(../admin/assets/img/theme/restro00.jpg) center/cover no-repeat;">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body text-white">
                    <h1 class="display-4">Paid Orders</h1>
                    <p class="text-light">Manage all completed transactions in one place.</p>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--8">
            <div class="row">
                <div class="col">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary text-white">
                            <h3 class="mb-0">Paid Orders</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-items-center">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th scope="col">Code</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Product</th>
                                        <th scope="col" class="text-right">Unit Price</th>
                                        <th scope="col" class="text-center">Qty</th>
                                        <th scope="col" class="text-right">Total Price</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_orders WHERE order_status = 'Paid' ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        $total = ($order->prod_price * $order->prod_qty);
                                    ?>
                                        <tr>
                                            <td class="font-weight-bold text-primary"><?php echo $order->order_code; ?></td>
                                            <td><?php echo $order->customer_name; ?></td>
                                            <td><?php echo $order->prod_name; ?></td>
                                            <td class="text-right">₱<?php echo number_format($order->prod_price, 2); ?></td>
                                            <td class="text-center"><?php echo $order->prod_qty; ?></td>
                                            <td class="text-right">₱<?php echo number_format($total, 2); ?></td>
                                            <td><?php echo date('d/M/Y g:i A', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <a href="print_receipt.php?order_code=<?php echo $order->order_code; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-print"></i> Print
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>

    <!-- Scripts -->
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
