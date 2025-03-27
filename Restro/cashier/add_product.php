<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
if (isset($_POST['addProduct'])) {
  if (empty($_POST["prod_code"]) || empty($_POST["prod_name"]) || empty($_POST['prod_desc']) || empty($_POST['prod_price'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $prod_id = $_POST['prod_id'];
    $prod_code  = $_POST['prod_code'];
    $prod_name = $_POST['prod_name'];
    $prod_img = $_FILES['prod_img']['name'];
    move_uploaded_file($_FILES["prod_img"]["tmp_name"], "../admin/assets/img/products/" . $_FILES["prod_img"]["name"]);
    $prod_desc = $_POST['prod_desc'];
    $prod_price = $_POST['prod_price'];

    $postQuery = "INSERT INTO rpos_products (prod_id, prod_code, prod_name, prod_img, prod_desc, prod_price ) VALUES(?,?,?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    $rc = $postStmt->bind_param('ssssss', $prod_id, $prod_code, $prod_name, $prod_img, $prod_desc, $prod_price);
    $postStmt->execute();
    if ($postStmt) {
      $success = "Product Added" && header("refresh:1; url=add_product.php");
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}
require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
  <?php
  require_once('partials/_sidebar.php');
  ?>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php
    require_once('partials/_topnav.php');
    ?>

    <!-- Header Section -->
    <div class="header pb-8 pt-5 pt-md-8" style="background-image: url('../admin/assets/img/theme/restro00.jpg'); background-size: cover;">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body text-white text-center">
          <h1 class="display-4">Add New Product</h1>
          <p class="lead">Fill in the product details below to add a new item to your inventory</p>
        </div>
      </div>
    </div>

    <!-- Page Content -->
    <div class="container-fluid mt--8">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center">
              <h3><i class="fas fa-plus-circle"></i> Add Product</h3>
            </div>
            <div class="card-body">
              <!-- Display error or success messages -->
              <?php if (isset($err)) { echo "<div class='alert alert-danger'>$err</div>"; } ?>
              <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

              <!-- Form to Add Product -->
              <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="col-md-6">
                    <label for="prod_name">Product Name</label>
                    <input type="text" name="prod_name" class="form-control" required>
                    <input type="hidden" name="prod_id" value="<?php echo $prod_id; ?>" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label for="prod_code">Product Code</label>
                    <input type="text" name="prod_code" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" class="form-control" readonly>
                  </div>
                </div>

                <hr>

                <div class="form-row">
                  <div class="col-md-6">
                    <label for="prod_img">Product Image</label>
                    <input type="file" name="prod_img" class="form-control-file" accept="image/*" required>
                    <!-- Add Image Preview -->
                    <div id="imagePreview" class="mt-2">
                      <img id="imgPreview" src="#" alt="Image Preview" class="img-fluid d-none" style="max-width: 150px;">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="prod_price">Product Price</label>
                    <input type="number" name="prod_price" class="form-control" step="0.01" required>
                  </div>
                </div>

                <hr>

                <div class="form-row">
                  <div class="col-md-12">
                    <label for="prod_desc">Product Description</label>
                    <textarea rows="5" name="prod_desc" class="form-control" required></textarea>
                  </div>
                </div>

                <br>

                <div class="form-row">
                  <div class="col-md-6">
                    <button type="submit" name="addProduct" class="btn btn-success btn-lg btn-block">Add Product</button>
                  </div>
                  <div class="col-md-6">
                    <a href="products.php" class="btn btn-secondary btn-lg btn-block">Cancel</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <?php
    require_once('partials/_footer.php');
    ?>
  </div>

  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>

  <!-- Image Preview Script -->
  <script>
    // Image preview before upload
    document.querySelector("input[name='prod_img']").addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
          const imgPreview = document.getElementById("imgPreview");
          imgPreview.src = event.target.result;
          imgPreview.classList.remove("d-none");
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>
