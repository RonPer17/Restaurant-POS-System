<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Cancel Order with Confirmation
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $adn = "DELETE FROM rpos_orders WHERE order_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    
    if ($stmt) {
        header("refresh:1; url=payments.php");
        $success = "Order successfully canceled.";
    } else {
        $err = "Something went wrong. Try again later.";
    }
}

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
                    <h1 class="display-4">Pending Payments</h1>
                    <p class="text-light">Manage all unpaid orders efficiently.</p>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--8">
            <div class="row">
                <div class="col">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary text-white">
                            <h3 class="mb-0">Orders Awaiting Payment</h3>
                        </div>
                        <div class="card-body">
                            <a href="orders.php" class="btn btn-success mb-3">
                                <i class="fas fa-plus"></i> New Order
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-items-center">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th scope="col">Order Code</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Product</th>
                                        <th scope="col" class="text-right">Total Price</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_orders WHERE order_status ='' ORDER BY created_at DESC";
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
                                            <td class="text-right">₱<?php echo number_format($total, 2); ?></td>
                                            <td><?php echo date('d/M/Y g:i A', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <a href="pay_order.php?order_code=<?php echo $order->order_code;?>&customer_id=<?php echo $order->customer_id;?>&order_status=Paid" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-handshake"></i> Pay
                                                </a>

                                                <button class="btn btn-sm btn-outline-danger" onclick="confirmCancel('<?php echo $order->order_id; ?>')">
                                                    <i class="fas fa-window-close"></i> Cancel
                                                </button>
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

    <script>
        function confirmCancel(orderId) {
            if (confirm("Are you sure you want to cancel this order? This action cannot be undone.")) {
                window.location.href = "payments.php?cancel=" + orderId;
            }
        }
    </script>

    <!-- Scripts -->
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
