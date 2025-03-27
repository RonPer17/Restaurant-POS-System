<?php
session_start();
include('config/config.php');

// Register new admin
if (isset($_POST['register'])) {
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = sha1(md5($_POST['admin_password'])); // Double encryption for security

    // Check if email already exists
    $stmt = $mysqli->prepare("SELECT admin_email FROM rpos_admin WHERE admin_email = ?");
    $stmt->bind_param('s', $admin_email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $err = "Email already exists!";
    } else {
        // Insert new admin
        $query = "INSERT INTO rpos_admin (admin_name, admin_email, admin_password) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sss', $admin_name, $admin_email, $admin_password);

        if ($stmt->execute()) {
            $success = "Admin Account Created!" && header("refresh:2; url=index.php");
        } else {
            $err = "Something went wrong, try again.";
        }
    }
}

require_once('partials/_head.php');
?>

<body class="bg-dark">
  <div class="main-content">
    <div class="container mt-5 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">
            <div class="card-body px-lg-5 py-lg-5">
              <h4 class="text-center text-dark mb-4">Admin Registration</h4>
              <form method="post">
                <div class="form-group mb-3">
                  <input class="form-control" required name="admin_name" placeholder="Full Name" type="text">
                </div>
                <div class="form-group mb-3">
                  <input class="form-control" required name="admin_email" placeholder="Email" type="email">
                </div>
                <div class="form-group">
                  <input class="form-control" required name="admin_password" id="admin-password" placeholder="Password" type="password">
                </div>
                <div class="form-group">
                  <input class="form-control" required name="confirm_password" id="confirm-password" placeholder="Confirm Password" type="password">
                </div>
                <div class="form-group">
                  <input type="checkbox" id="showPassword"> Show Password
                </div>
                <div class="text-center">
                  <button type="submit" name="register" class="btn btn-success">Register</button>
                </div>
              </form>
              <div class="text-center mt-3">
                <a href="indexx.php" class="text-dark">Already have an account? Login</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById("showPassword").addEventListener("change", function () {
        var passField = document.getElementById("admin-password");
        var confirmField = document.getElementById("confirm-password");
        passField.type = this.checked ? "text" : "password";
        confirmField.type = this.checked ? "text" : "password";
    });
  </script>

  <?php require_once('partials/_footer.php'); ?>
</body>

</html>
