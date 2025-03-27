<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Cancel Order
if (isset($_GET['cancel'])) {
    $id = intval($_GET['cancel']);
    $adn = "DELETE FROM rpos_orders WHERE order_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id); // Changed 's' to 'i' for integer binding
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Order canceled successfully.";
        header("refresh:1; url=payments.php");
    } else {
        $err = "Try Again Later";
    }
}

require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div class="header pb-8 pt-5 pt-md-8" style="background-image: url('assets/img/theme/restro00.jpg'); background-size: cover;">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                    <h1 class="text-white">Order Management</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid mt--8">
            <!-- Alerts -->
            <?php if (isset($success)) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success; ?>
                </div>
            <?php } ?>
            <?php if (isset($err)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $err; ?>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                            <h3 class="mb-0">Pending Orders</h3>
                            <a href="orders.php" class="btn btn-light btn-sm">
                                <i class="fas fa-plus"></i> <i class="fas fa-utensils"></i> Make A New Order
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Code</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT order_code, customer_name, prod_name, prod_price, prod_qty, created_at, order_id, customer_id FROM rpos_orders WHERE order_status ='' ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                        $total = floatval($order->prod_price) * intval($order->prod_qty);
                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row"><?php echo htmlspecialchars($order->order_code); ?></th>
                                            <td><?php echo htmlspecialchars($order->customer_name); ?></td>
                                            <td><?php echo htmlspecialchars($order->prod_name); ?></td>
                                            <td>₱ <?php echo number_format($total, 2); ?></td>
                                            <td><?php echo date('d/M/Y g:i A', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <a href="pay_order.php?order_code=<?php echo htmlspecialchars($order->order_code); ?>&customer_id=<?php echo htmlspecialchars($order->customer_id); ?>&order_status=Paid" class="btn btn-sm btn-success">
                                                    <i class="fas fa-handshake"></i> Pay Order
                                                </a>
                                                <a href="payments.php?cancel=<?php echo $order->order_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?');">
                                                    <i class="fas fa-window-close"></i> Cancel Order
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
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>