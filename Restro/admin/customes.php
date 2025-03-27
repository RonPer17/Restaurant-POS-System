<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Delete Customer
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $mysqli->prepare("DELETE FROM rpos_customers WHERE customer_id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $_SESSION['msg'] = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Success!</strong> Customer deleted successfully.
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>";
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                <strong>Error!</strong> Something went wrong. Try again later.
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>";
    }
    $stmt->close();
    header("Location: customes.php");
    exit();
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
        <div class="header pb-6 pt-5 pt-md-8" style="background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);">
            <div class="container-fluid">
                <div class="header-body text-white">
                    <h1>Customer Management</h1>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--7">
            <!-- Alerts -->
            <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>

            <!-- Table -->
            <div class="row">
                <div class="col">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h3 class="mb-0 text-dark"><i class="fas fa-users"></i> Customers List</h3>
                            <a href="add_customer.php" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Add Customer
                            </a>
                        </div>

                        <div class="table-responsive p-3">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-dark text-white">
                                    <tr>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Contact Number</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $mysqli->prepare("SELECT * FROM rpos_customers ORDER BY created_at DESC");
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($cust = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($cust->customer_name); ?></strong></td>
                                            <td><?php echo htmlspecialchars($cust->customer_phoneno); ?></td>
                                            <td><?php echo htmlspecialchars($cust->customer_email); ?></td>
                                            <td class="text-center">
                                                <a href="update_customer.php?update=<?php echo $cust->customer_id; ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?php echo $cust->customer_id; ?>" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal<?php echo $cust->customer_id; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($cust->customer_name); ?></strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <a href="customes.php?delete=<?php echo $cust->customer_id; ?>" class="btn btn-danger">Yes, Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-muted text-center">
                            <small>Manage your customers efficiently.</small>
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
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>
