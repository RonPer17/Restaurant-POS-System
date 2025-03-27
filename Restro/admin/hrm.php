<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Delete Staff
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $mysqli->prepare("DELETE FROM rpos_staff WHERE staff_id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $_SESSION['msg'] = "<div class='alert alert-success'>Staff Deleted Successfully!</div>";
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Error! Please Try Again.</div>";
    }
    $stmt->close();
    header("Location: hrm.php");
    exit();
}

require_once('partials/_head.php');
?>

<body>
    <!-- Sidenav -->
    <?php require_once('partials/_sidebar.php'); ?>

    <!-- Main content -->
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>

        <div class="header bg-gradient-primary pb-6 pt-5">
            <div class="container-fluid">
                <div class="header-body">
                    <h2 class="text-white">Human Resource Management</h2>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--6">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h3 class="mb-0 text-dark"><i class="fas fa-users"></i> Staff List</h3>
                            <a href="add_staff.php" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Add New Staff
                            </a>
                        </div>

                        <!-- Display Messages -->
                        <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>

                        <div class="table-responsive p-3">
                            <table class="table table-hover align-items-center table-bordered">
                                <thead class="thead-dark text-white">
                                    <tr>
                                        <th scope="col">Staff Number</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $mysqli->prepare("SELECT * FROM rpos_staff ORDER BY created_at DESC");
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($staff = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($staff->staff_number); ?></strong></td>
                                            <td><?php echo htmlspecialchars($staff->staff_name); ?></td>
                                            <td><?php echo htmlspecialchars($staff->staff_email); ?></td>
                                            <td class="text-center">
                                                <a href="update_staff.php?update=<?php echo $staff->staff_id; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $staff->staff_id; ?>">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal<?php echo $staff->staff_id; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($staff->staff_name); ?></strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <a href="hrm.php?delete=<?php echo $staff->staff_id; ?>" class="btn btn-danger">Yes, Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-muted text-center">
                            <small>Manage your HR database efficiently.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>

    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
