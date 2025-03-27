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
                    <h1 class="display-4">Payment Reports</h1>
                    <p class="text-light">Monitor all completed transactions.</p>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--8">
            <div class="row">
                <div class="col">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary text-white">
                            <h3 class="mb-0">Transaction Overview</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-items-center">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th scope="col">Payment Code</th>
                                        <th scope="col">Payment Method</th>
                                        <th scope="col">Order Code</th>
                                        <th scope="col" class="text-right">Amount Paid</th>
                                        <th scope="col">Date Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_payments ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($payment = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td class="font-weight-bold text-primary"><?php echo $payment->pay_code; ?></td>
                                            <td><?php echo $payment->pay_method; ?></td>
                                            <td class="text-success"><?php echo $payment->order_code; ?></td>
                                            <td class="text-right font-weight-bold">₱<?php echo number_format($payment->pay_amt, 2); ?></td>
                                            <td><?php echo date('d/M/Y g:i A', strtotime($payment->created_at)); ?></td>
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
