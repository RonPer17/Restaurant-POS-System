<?php
session_start();
include('config/config.php');

// Login logic
if (isset($_POST['login'])) {
  $staff_email = $_POST['staff_email'];
  $staff_password = sha1(md5($_POST['staff_password'])); // Double encryption for security
  
  $stmt = $mysqli->prepare("SELECT staff_email, staff_password, staff_id FROM rpos_staff WHERE (staff_email =? AND staff_password =?)");
  $stmt->bind_param('ss', $staff_email, $staff_password);
  $stmt->execute();
  $stmt->bind_result($staff_email, $staff_password, $staff_id);
  
  $rs = $stmt->fetch();
  $_SESSION['staff_id'] = $staff_id;

  if ($rs) {
    header("location:dashboard.php");
  } else {
    $err = "Incorrect Authentication Credentials";
  }
}

require_once('partials/_head.php');
?>

<body class="bg-dark">
  <div class="main-content">
    <div class="header bg-gradient-primary py-7">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <h1 class="text-white">Restaurant Point Of Sale</h1>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">
            <div class="card-body px-lg-5 py-lg-5">
              <form method="post" role="form">
                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" required name="staff_email" placeholder="Email" type="email">
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input id="password" class="form-control" required name="staff_password" placeholder="Password" type="password">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <input type="checkbox" id="showPassword" onclick="togglePassword()"> Show
                      </span>
                    </div>
                  </div>
                </div>

                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id="customCheckLogin" type="checkbox">
                  <label class="custom-control-label" for="customCheckLogin">
                    <span class="text-muted">Remember me</span>
                  </label>
                </div>

                <div class="text-center">
                  <button type="submit" name="login" class="btn btn-primary my-4">Log In</button>
                </div>
              </form>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-6"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <?php require_once('partials/_footer.php'); ?>

  <!-- Scripts -->
  <?php require_once('partials/_scripts.php'); ?>

  <script>
    function togglePassword() {
      var passwordField = document.getElementById("password");
      if (passwordField.type === "password") {
        passwordField.type = "text";
      } else {
        passwordField.type = "password";
      }
    }
  </script>
</body>
</html>
