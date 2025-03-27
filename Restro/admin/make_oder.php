<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
if (isset($_POST['make'])) {
    if (empty($_POST["order_code"]) || empty($_POST["customer_name"]) || empty($_GET['prod_price'])) {
        $_SESSION['msg'] = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                <strong>Warning!</strong> All fields are required.
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>";
    } else {
        $order_id = $_POST['order_id'];
        $order_code  = $_POST['order_code'];
        $customer_id = $_POST['customer_id'];
        $customer_name = $_POST['customer_name'];
        $prod_id  = $_GET['prod_id'];
        $prod_name = $_GET['prod_name'];
        $prod_price = $_GET['prod_price'];
        $prod_qty = $_POST['prod_qty'];

        $postQuery = "INSERT INTO rpos_orders (prod_qty, order_id, order_code, customer_id, customer_name, prod_id, prod_name, prod_price) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $postStmt = $mysqli->prepare($postQuery);
        $postStmt->bind_param('ssssssss', $prod_qty, $order_id, $order_code, $customer_id, $customer_name, $prod_id, $prod_name, $prod_price);

        if ($postStmt->execute()) {
            $_SESSION['msg'] = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>Success!</strong> Order submitted successfully.
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>";
            header("refresh:1; url=payments.php");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                    <strong>Error!</strong> Please try again later.
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>";
        }
    }
}

require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>

    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>

        <!-- Header -->
        <div class="header pb-6 pt-5 pt-md-8" style="background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);">
            <div class="container-fluid">
                <div class="header-body text-white text-center">
                    <h1 class="display-4">Make an Order</h1>
                    <p class="text-light">Fill in the details to place an order.</p>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-transparent text-dark">
                            <h3 class="mb-0"><i class="fas fa-shopping-cart"></i> Order Details</h3>
                        </div>

                        <div class="card-body">
                            <!-- Alerts -->
                            <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>

                            <form method="POST">
                                <div class="form-group">
                                    <label for="customer_name">Customer Name</label>
                                    <select class="form-control" name="customer_name" id="custName" onChange="getCustomer(this.value)" required>
                                        <option value="">Select Customer</option>
                                        <?php
                                        $ret = "SELECT * FROM rpos_customers";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($cust = $res->fetch_object()) {
                                            echo "<option value='$cust->customer_name'>$cust->customer_name</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" name="order_id" value="<?php echo $orderid; ?>" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="customer_id">Customer ID</label>
                                    <input type="text" id="customerID" name="customer_id" class="form-control" placeholder="Auto-filled" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="order_code">Order Code</label>
                                    <input type="text" name="order_code" class="form-control" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" readonly>
                                </div>

                                <?php
                                $prod_id = $_GET['prod_id'];
                                $ret = "SELECT * FROM rpos_products WHERE prod_id = ?";
                                $stmt = $mysqli->prepare($ret);
                                $stmt->bind_param('s', $prod_id);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($prod = $res->fetch_object()) {
                                ?>
                                    <div class="form-group">
                                        <label for="prod_price">Product Price (₱)</label>
                                        <input type="text" name="prod_price" class="form-control" value="₱ <?php echo $prod->prod_price; ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="prod_qty">Quantity</label>
                                        <input type="number" name="prod_qty" class="form-control" placeholder="Enter quantity" required>
                                    </div>
                                <?php } ?>

                                <div class="text-center">
                                    <button type="submit" name="make" class="btn btn-primary btn-lg">
                                        <i class="fas fa-check"></i> Confirm Order
                                    </button>
                                    <a href="products.php" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="card-footer text-muted text-center">
                            <small>Ensure all details are accurate before submitting.</small>
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
