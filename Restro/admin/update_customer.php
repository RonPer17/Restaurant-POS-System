<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

// Check if update ID is provided
if (isset($_GET['update'])) {
    $update = $_GET['update'];

    // Fetch customer data
    $stmt = $mysqli->prepare("SELECT * FROM rpos_customers WHERE customer_id = ?");
    $stmt->bind_param('s', $update);
    $stmt->execute();
    $res = $stmt->get_result();
    $cust = $res->fetch_object();
}

// Update Customer
if (isset($_POST['updateCustomer'])) {
    if (empty($_POST["customer_phoneno"]) || empty($_POST["customer_name"]) || empty($_POST['customer_email'])) {
        $_SESSION['msg'] = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                <strong>Warning!</strong> All fields are required.
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>";
    } else {
        $customer_name = $_POST['customer_name'];
        $customer_phoneno = $_POST['customer_phoneno'];
        $customer_email = $_POST['customer_email'];

        // Check if password is provided
        if (!empty($_POST['customer_password'])) {
            $customer_password = sha1(md5($_POST['customer_password']));
            $postQuery = "UPDATE rpos_customers SET customer_name=?, customer_phoneno=?, customer_email=?, customer_password=? WHERE customer_id=?";
            $postStmt = $mysqli->prepare($postQuery);
            $postStmt->bind_param('sssss', $customer_name, $customer_phoneno, $customer_email, $customer_password, $update);
        } else {
            $postQuery = "UPDATE rpos_customers SET customer_name=?, customer_phoneno=?, customer_email=? WHERE customer_id=?";
            $postStmt = $mysqli->prepare($postQuery);
            $postStmt->bind_param('ssss', $customer_name, $customer_phoneno, $customer_email, $update);
        }

        if ($postStmt->execute()) {
            $_SESSION['msg'] = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>Success!</strong> Customer updated successfully.
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>";
            header("refresh:1; url=customers.php");
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

    <!-- Main content -->
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>

        <!-- Header -->
        <div class="header pb-6 pt-5 pt-md-8" style="background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);">
            <div class="container-fluid">
                <div class="header-body text-white text-center">
                    <h1 class="display-4">Update Customer</h1>
                    <p class="text-light">Modify customer details and save changes.</p>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-transparent text-dark">
                            <h3 class="mb-0"><i class="fas fa-user-edit"></i> Customer Information</h3>
                        </div>

                        <div class="card-body">
                            <!-- Alerts -->
                            <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>

                            <form method="POST">
                                <div class="form-group">
                                    <label for="customer_name">Full Name</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Enter full name" value="<?php echo $cust->customer_name; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_phoneno">Phone Number</label>
                                    <input type="text" id="customer_phoneno" name="customer_phoneno" class="form-control" placeholder="Enter phone number" value="<?php echo $cust->customer_phoneno; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_email">Email Address</label>
                                    <input type="email" id="customer_email" name="customer_email" class="form-control" placeholder="Enter email" value="<?php echo $cust->customer_email; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_password">New Password (Leave blank to keep existing password)</label>
                                    <input type="password" id="customer_password" name="customer_password" class="form-control" placeholder="Enter new password">
                                </div>

                                <div class="text-center">
                                    <a href="customes.php" button type="submit" name="updateCustomer" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                    <a href="customes.php" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="card-footer text-muted text-center">
                            <small>Ensure all details are accurate before saving.</small>
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
