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
        <div class="header pb-8 pt-5 pt-md-8" style="background-image: url('assets/img/theme/restro00.jpg'); background-size: cover;">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                    <h1 class="text-white">Paid Orders</h1>
                </div>
            </div>
        </div>
        
        <!-- Page content -->
        <div class="container-fluid mt--8">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">List of Paid Orders</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Code</th>
                                        <th scope="col">Customer</th>
                                        <th class="text-success" scope="col">Product</th>
                                        <th scope="col">Unit Price</th>
                                        <th class="text-success" scope="col">Qty</th>
                                        <th scope="col">Total Price</th>
                                        <th class="text-success" scope="col">Date</th>
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
                                            <th class="text-success" scope="row"><?php echo htmlspecialchars($order->order_code); ?></th>
                                            <td><?php echo htmlspecialchars($order->customer_name); ?></td>
                                            <td class="text-success"><?php echo htmlspecialchars($order->prod_name); ?></td>
                                            <td>₱ <?php echo number_format($order->prod_price, 2); ?></td>
                                            <td class="text-success"><?php echo htmlspecialchars($order->prod_qty); ?></td>
                                            <td>₱ <?php echo number_format($total, 2); ?></td>
                                            <td><?php echo date('d/M/Y g:i A', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <a target="_blank" href="print_receipt.php?order_code=<?php echo htmlspecialchars($order->order_code); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Print Receipt">
                                                    <i class="fas fa-print"></i> Print Receipt
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
    
    <!-- Argon Scripts -->
    <?php require_once('partials/_scripts.php'); ?>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>