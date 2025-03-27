<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

if (isset($_POST['make'])) {
    if (empty($_POST["order_code"]) || empty($_POST["customer_name"]) || empty($_GET['prod_price'])) {
        $err = "All fields are required!";
    } else {
        $order_id = $_POST['order_id'];
        $order_code = $_POST['order_code'];
        $customer_id = $_POST['customer_id'];
        $customer_name = $_POST['customer_name'];
        $prod_id = $_GET['prod_id'];
        $prod_name = $_GET['prod_name'];
        $prod_price = $_GET['prod_price'];
        $prod_qty = $_POST['prod_qty'];
        $total_price = $prod_price * $prod_qty;

        $query = "INSERT INTO rpos_orders (prod_qty, order_id, order_code, customer_id, customer_name, prod_id, prod_name, prod_price, total_price) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssssssss', $prod_qty, $order_id, $order_code, $customer_id, $customer_name, $prod_id, $prod_name, $prod_price, $total_price);

        if ($stmt->execute()) {
            header("Location: payments.php?msg=order_placed");
            exit();
        } else {
            $err = "Error processing order. Try again.";
        }
    }
}

require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>

        <div class="header pb-6 d-flex align-items-center" style="min-height: 300px; background: url('../admin/assets/img/theme/restro00.jpg') center/cover;">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid d-flex align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="display-3 text-white font-weight-bold">Place an Order</h1>
                    <p class="text-white lead">Fill in the details to complete the order.</p>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--6">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary text-white text-center">
                            <h3 class="mb-0">Order Details</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($err)) { ?>
                                <div class="alert alert-danger"><?= $err; ?></div>
                            <?php } ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <select class="form-control" name="customer_name" id="custName" onChange="getCustomer(this.value)" required>
                                        <option value="">Select Customer</option>
                                        <?php
                                        $ret = "SELECT * FROM rpos_customers";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($cust = $res->fetch_object()) {
                                            echo "<option value='$cust->customer_name' data-id='$cust->customer_id'>$cust->customer_name</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" name="order_id" value="<?= $orderid; ?>" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Customer ID</label>
                                    <input type="text" name="customer_id" id="customerID" class="form-control" readonly required>
                                </div>

                                <div class="form-group">
                                    <label>Order Code</label>
                                    <input type="text" name="order_code" class="form-control" value="<?= $alpha; ?>-<?= $beta; ?>" readonly required>
                                </div>

                                <?php
                                $prod_id = $_GET['prod_id'];
                                $ret = "SELECT * FROM rpos_products WHERE prod_id = ?";
                                $stmt = $mysqli->prepare($ret);
                                $stmt->bind_param('i', $prod_id);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                $prod = $res->fetch_object();
                                ?>
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($prod->prod_name); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Product Price (₱)</label>
                                    <input type="number" name="prod_price" class="form-control" value="<?= htmlspecialchars($prod->prod_price); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="prod_qty" id="prodQty" class="form-control" min="1" required oninput="calculateTotal()">
                                </div>

                                <div class="form-group">
                                    <label>Total Price (₱)</label>
                                    <input type="text" id="totalPrice" class="form-control" readonly>
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" name="make" class="btn btn-success btn-lg shadow">
                                        <i class="fas fa-check"></i> Confirm Order
                                    </button>
                                    <a href="products.php" class="btn btn-danger btn-lg shadow">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>

    <!-- Auto-fill Customer ID & Calculate Total Price -->
    <script>
        function getCustomer(name) {
            let customers = document.querySelectorAll('#custName option');
            customers.forEach(opt => {
                if (opt.value === name) {
                    document.getElementById('customerID').value = opt.getAttribute('data-id');
                }
            });
        }

        function calculateTotal() {
            let price = <?= $prod->prod_price; ?>;
            let qty = document.getElementById("prodQty").value;
            let total = price * qty;
            document.getElementById("totalPrice").value = isNaN(total) ? "" : "₱ " + total.toFixed(2);
        }
    </script>

    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
