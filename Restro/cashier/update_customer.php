<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

// Update Customer
if (isset($_POST['updateCustomer'])) {
  if (empty($_POST["customer_phoneno"]) || empty($_POST["customer_name"]) || empty($_POST['customer_email'])) {
    $err = "All fields except password are required.";
  } else {
    $customer_name = $_POST['customer_name'];
    $customer_phoneno = $_POST['customer_phoneno'];
    $customer_email = $_POST['customer_email'];
    $update = $_GET['update'];

    // Update Query
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
      $success = "Customer Updated Successfully";
      header("refresh:1; url=customers.php");
    } else {
      $err = "Update Failed. Please try again.";
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
    $update = $_GET['update'];
    $ret = "SELECT * FROM rpos_customers WHERE customer_id = ?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('s', $update);
    $stmt->execute();
    $res = $stmt->get_result();
    $cust = $res->fetch_object();
    ?>

    <div class="container-fluid mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center">
              <h3 class="my-3">Update Customer Information</h3>
            </div>
            <div class="card-body">
              <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
              <?php if (isset($err)) echo "<div class='alert alert-danger'>$err</div>"; ?>
              
              <form method="POST">
                <div class="mb-3">
                  <label class="form-label">Customer Name</label>
                  <input type="text" name="customer_name" value="<?php echo $cust->customer_name; ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Customer Phone Number</label>
                  <input type="text" name="customer_phoneno" value="<?php echo $cust->customer_phoneno; ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Customer Email</label>
                  <input type="email" name="customer_email" value="<?php echo $cust->customer_email; ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">New Password (Optional)</label>
                  <input type="password" name="customer_password" class="form-control">
                </div>
                <div class="text-center">
                  <button type="submit" name="updateCustomer" class="btn btn-success w-100">Update Customer</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require_once('partials/_footer.php'); ?>
  <?php require_once('partials/_scripts.php'); ?>
</body>
</html>