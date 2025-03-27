<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

// Update Staff
if (isset($_POST['UpdateStaff'])) {
  // Prevent Posting Blank Values
  if (empty($_POST["staff_number"]) || empty($_POST["staff_name"]) || empty($_POST['staff_email']) || empty($_POST['staff_password'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $staff_number = $_POST['staff_number'];
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $staff_password = $_POST['staff_password'];
    $update = $_GET['update'];

    // Update database table
    $postQuery = "UPDATE rpos_staff SET staff_number =?, staff_name =?, staff_email =?, staff_password =? WHERE staff_id =?";
    $postStmt = $mysqli->prepare($postQuery);
    $postStmt->bind_param('ssssi', $staff_number, $staff_name, $staff_email, $staff_password, $update);
    $postStmt->execute();

    if ($postStmt) {
      $success = "Staff Updated" && header("refresh:1; url=hrm.php");
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}

require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
  <?php require_once('partials/_sidebar.php'); ?>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Top Navbar -->
    <?php require_once('partials/_topnav.php'); ?>
    
    <?php
    $update = $_GET['update'];
    $ret = "SELECT * FROM rpos_staff WHERE staff_id = '$update' ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($staff = $res->fetch_object()) {
    ?>
    
    <!-- Header Section -->
    <div class="header pb-8 pt-5 pt-md-8" style="background-image: url('../assets/img/theme/restro00.jpg'); background-size: cover;">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body text-white text-center">
          <h1 class="display-4">Update Staff Information</h1>
          <p class="lead">Please update the necessary fields for the staff</p>
        </div>
      </div>
    </div>

    <!-- Page Content -->
    <div class="container-fluid mt--8">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center">
              <h3><i class="fas fa-user-edit"></i> Edit Staff</h3>
            </div>
            <div class="card-body">
              <!-- Display Error or Success Messages -->
              <?php if (isset($err)) { echo "<div class='alert alert-danger'>$err</div>"; } ?>
              <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

              <!-- Form to Update Staff -->
              <form method="POST">
                <div class="form-row">
                  <div class="col-md-6">
                    <label for="staff_number">Staff Number</label>
                    <input type="text" name="staff_number" class="form-control" value="<?php echo $staff->staff_number; ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label for="staff_name">Staff Name</label>
                    <input type="text" name="staff_name" class="form-control" value="<?php echo $staff->staff_name; ?>" required>
                  </div>
                </div>

                <div class="form-row">
                  <div class="col-md-6">
                    <label for="staff_email">Staff Email</label>
                    <input type="email" name="staff_email" class="form-control" value="<?php echo $staff->staff_email; ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label for="staff_password">Staff Password</label>
                    <input type="password" name="staff_password" class="form-control" placeholder="Enter new password">
                  </div>
                </div>

                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <button type="submit" name="UpdateStaff" class="btn btn-success btn-lg btn-block">Update Staff</button>
                  </div>
                  <div class="col-md-6">
                    <a href="hrm.php" class="btn btn-secondary btn-lg btn-block">Cancel</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <?php require_once('partials/_footer.php'); ?>
    
    <?php } // End while loop ?>
    
  </div>

  <!-- Argon Scripts -->
  <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
