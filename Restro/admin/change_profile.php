<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Update Profile
if (isset($_POST['ChangeProfile'])) {
    $admin_id = $_SESSION['admin_id'];
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    
    $Qry = "UPDATE rpos_admin SET admin_name =?, admin_email =? WHERE admin_id =?";
    $postStmt = $mysqli->prepare($Qry);
    $postStmt->bind_param('sss', $admin_name, $admin_email, $admin_id);
    $postStmt->execute();

    if ($postStmt->affected_rows > 0) {
        $success = "Account Updated";
        header("refresh:1; url=dashboard.php");
        exit();
    } else {
        $err = "No Changes Made";
    }
}

// Change Password
if (isset($_POST['changePassword'])) {
    $error = 0;

    if (!empty($_POST['old_password'])) {
        $old_password = $_POST['old_password'];
    } else {
        $error = 1;
        $err = "Old Password Cannot Be Empty";
    }
    if (!empty($_POST['new_password'])) {
        $new_password = $_POST['new_password'];
    } else {
        $error = 1;
        $err = "New Password Cannot Be Empty";
    }
    if (!empty($_POST['confirm_password'])) {
        $confirm_password = $_POST['confirm_password'];
    } else {
        $error = 1;
        $err = "Confirmation Password Cannot Be Empty";
    }

    if (!$error) {
        $admin_id = $_SESSION['admin_id'];
        $sql = "SELECT admin_password FROM rpos_admin WHERE admin_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $admin_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($old_password, $hashed_password)) {
            $err = "Incorrect Old Password";
        } elseif ($new_password !== $confirm_password) {
            $err = "Passwords Do Not Match";
        } else {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE rpos_admin SET admin_password = ? WHERE admin_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss', $new_hashed_password, $admin_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $success = "Password Changed";
                header("refresh:1; url=dashboard.php");
                exit();
            } else {
                $err = "Something Went Wrong";
            }
        }
    }
}

require_once('partials/_head.php');
?>

<body>
  <?php require_once('partials/_sidebar.php'); ?>

  <div class="main-content">
    <?php require_once('partials/_topnav.php'); ?>
    
    <?php
    $admin_id = $_SESSION['admin_id'];
    $ret = "SELECT * FROM rpos_admin WHERE admin_id = '$admin_id'";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($admin = $res->fetch_object()) {
    ?>
    
    <div class="container-fluid mt--8">
      <div class="row">
        <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
          <div class="card card-profile shadow-sm rounded-lg">
            <div class="card-body text-center">
              <h3 class="h3 text-dark font-weight-bold"><?php echo $admin->admin_name; ?></h3>
              <p class="text-muted"><?php echo $admin->admin_email; ?></p>
            </div>
          </div>
        </div>

        <div class="col-xl-8 order-xl-1">
          <div class="card bg-light shadow-sm rounded-lg">
            <div class="card-header bg-primary text-white">
              <h3>My Account</h3>
            </div>
            <div class="card-body">
              <form method="POST">
                <h6 class="heading-small text-muted mb-4">User Information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label">Username</label>
                        <input type="text" name="admin_name" value="<?php echo $admin->admin_name; ?>" class="form-control form-control-alternative" required>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label">Email Address</label>
                        <input type="email" value="<?php echo $admin->admin_email; ?>" name="admin_email" class="form-control form-control-alternative" required>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <button type="submit" name="ChangeProfile" class="btn btn-success">Save Changes</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>

              <hr class="my-4">

              <form method="POST">
                <h6 class="heading-small text-muted mb-4">Change Password</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-control-label">Old Password</label>
                        <input type="password" name="old_password" id="old-password" class="form-control form-control-alternative" required>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-control-label">New Password</label>
                        <input type="password" name="new_password" id="new-password" class="form-control form-control-alternative" required>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-control-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" id="confirm-password" class="form-control form-control-alternative" required>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <input type="checkbox" id="showPassword">
                        <label for="showPassword"> Show Password</label>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <button type="submit" name="changePassword" class="btn btn-danger">Change Password</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>

              <script>
                document.getElementById("showPassword").addEventListener("change", function () {
                  var pwdFields = document.querySelectorAll('input[type="password"]');
                  pwdFields.forEach(field => field.type = this.checked ? "text" : "password");
                });
              </script>

            </div>
          </div>
        </div>
      </div>
    </div>

    <?php require_once('partials/_footer.php'); ?>
    <?php } ?>
  </div>

</body>
</html>
