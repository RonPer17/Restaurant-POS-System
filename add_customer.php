<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');
check_login();

// Add Customer
if (isset($_POST['addCustomer'])) {
    if (empty($_POST["customer_phoneno"]) || empty($_POST["customer_name"]) || empty($_POST['customer_email']) || empty($_POST['customer_password'])) {
        $_SESSION['msg'] = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                <strong>Warning!</strong> All fields are required.
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>";
    } else {
        $customer_id = $_POST['customer_id'];
        $customer_name = $_POST['customer_name'];
        $customer_phoneno = $_POST['customer_phoneno'];
        $customer_email = $_POST['customer_email'];
        $customer_password = sha1(md5($_POST['customer_password']));

        // Insert Data
        $stmt = $mysqli->prepare("INSERT INTO rpos_customers (customer_id, customer_name, customer_phoneno, customer_email, customer_password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $customer_id, $customer_name, $customer_phoneno, $customer_email, $customer_password);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>Success!</strong> Customer added successfully.
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>";
            header("refresh:1; url=customes.php");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                    <strong>Error!</strong> Please try again later.
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>";
        }
        $stmt->close();
    }
}

require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>

    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php require_once('partials/_topnav.php'); ?>

        <!-- Header -->
        <div class="header pb-6 pt-5 pt-md-8" style="background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);">
            <div class="container-fluid">
                <div class="header-body text-white text-center">
                    <h1 class="display-4">Add New Customer</h1>
                    <p class="text-light">Fill in the details below to register a new customer.</p>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-transparent text-dark">
                            <h3 class="mb-0"><i class="fas fa-user-plus"></i> Customer Registration Form</h3>
                        </div>

                        <div class="card-body">
                            <!-- Alerts -->
                            <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>

                            <form method="POST">
                                <div class="form-group">
                                    <label for="customer_name">Full Name</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Enter full name" required>
                                    <input type="hidden" name="customer_id" value="<?php echo $cus_id; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="customer_phoneno">Phone Number</label>
                                    <input type="text" id="customer_phoneno" name="customer_phoneno" class="form-control" placeholder="Enter phone number" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_email">Email Address</label>
                                    <input type="email" id="customer_email" name="customer_email" class="form-control" placeholder="Enter email" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_password">Password</label>
                                    <input type="password" id="customer_password" name="customer_password" class="form-control" placeholder="Enter password" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" name="addCustomer" class="btn btn-success btn-lg">
                                        <i class="fas fa-check-circle"></i> Add Customer
                                    </button>
                                    <a href="customes.php" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left"></i> Back to Customers
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

    <!-- Scripts -->
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
