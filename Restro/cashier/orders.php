<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

require_once('partials/_head.php');
?>

<body>
  <?php require_once('partials/_sidebar.php'); ?>
  
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php require_once('partials/_topnav.php'); ?>

    <!-- Header -->
    <div class="header pb-7 pt-5 pt-md-7 text-white text-center" style="background: url('../admin/assets/img/theme/restro00.jpg') center/cover no-repeat; position: relative;">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
          <h1 class="display-4 font-weight-bold">Order Your Favorite Products</h1>
          <p class="lead">Select a product and place your order instantly!</p>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-lg-12">
          <div class="card shadow-lg border-0">
            <div class="card-header bg-gradient-primary text-white text-center">
              <h3 class="mb-0">Available Products</h3>
            </div>

            <div class="table-responsive p-4">
              <table class="table table-hover align-items-center">
                <thead class="bg-dark text-white">
                  <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Product Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = "SELECT * FROM rpos_products ORDER BY created_at DESC";
                  $stmt = $mysqli->prepare($query);
                  $stmt->execute();
                  $result = $stmt->get_result();
                  while ($prod = $result->fetch_object()) {
                  ?>
                    <tr>
                      <td class="text-center">
                        <img src="../admin/assets/img/products/<?= htmlspecialchars($prod->prod_img ?: 'default.jpg'); ?>" class="img-thumbnail rounded shadow-sm" width="70" height="70">
                      </td>
                      <td class="font-weight-bold text-primary"><?= htmlspecialchars($prod->prod_code); ?></td>
                      <td class="text-dark"><?= htmlspecialchars($prod->prod_name); ?></td>
                      <td class="text-success font-weight-bold">₱<?= number_format($prod->prod_price, 2); ?></td>
                      <td class="text-center">
                        <a href="make_oder.php?prod_id=<?= htmlspecialchars($prod->prod_id); ?>&prod_name=<?= urlencode($prod->prod_name); ?>&prod_price=<?= htmlspecialchars($prod->prod_price); ?>" class="btn btn-warning btn-sm shadow-sm">
                          <i class="fas fa-cart-plus"></i> Place Order
                        </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
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
