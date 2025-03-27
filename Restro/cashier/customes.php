<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Delete Customer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM rpos_customers WHERE customer_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    
    if ($stmt) {
        header("refresh:1; url=customers.php");
        $success = "Customer Deleted Successfully!";
    } else {
        $err = "Error! Try Again Later";
    }
}

require_once('partials/_head.php');
?>

<body>
    <!-- Sidebar -->
    <?php require_once('partials/_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>

        <!-- Header -->
        <div class="header bg-gradient-dark pb-7 pt-5">
            <div class="container-fluid">
                <div class="header-body text-white">
                    <h1 class="display-4">Customer Management</h1>
                    <p class="lead">Manage your customers effectively.</p>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col">
                    <div class="card shadow-lg border-0">
                        <div class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white">
                            <h3 class="mb-0">Customers List</h3>
                            <a href="add_customer.php" class="btn btn-light text-dark">
                                <i class="fas fa-user-plus"></i> Add Customer
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-items-center">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th scope="col"><i class="fas fa-user"></i> Full Name</th>
                                        <th scope="col"><i class="fas fa-phone"></i> Contact</th>
                                        <th scope="col"><i class="fas fa-envelope"></i> Email</th>
                                        <th scope="col" class="text-center"><i class="fas fa-cog"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM rpos_customers ORDER BY created_at DESC";
                                    $stmt = $mysqli->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($customer = $result->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td class="font-weight-bold"><?php echo htmlspecialchars($customer->customer_name); ?></td>
                                            <td><?php echo htmlspecialchars($customer->customer_phoneno); ?></td>
                                            <td><?php echo htmlspecialchars($customer->customer_email); ?></td>
                                            <td class="text-center">
                                                <a href="update_customer.php?update=<?php echo $customer->customer_id; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="customes.php?delete=<?php echo $customer->customer_id; ?>" onclick="return confirm('Are you sure you want to delete this customer?');" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-muted text-center">
                            <small>Manage your customer database efficiently.</small>
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
