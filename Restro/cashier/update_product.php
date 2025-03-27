<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

if (isset($_POST['UpdateProduct'])) {
    if (empty($_POST["prod_code"]) || empty($_POST["prod_name"]) || empty($_POST['prod_desc']) || empty($_POST['prod_price'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $update = $_GET['update'];
        $prod_code = $_POST['prod_code'];
        $prod_name = $_POST['prod_name'];
        $prod_desc = $_POST['prod_desc'];
        $prod_price = $_POST['prod_price'];

       
        if (!empty($_FILES['prod_img']['name'])) {
            $prod_img = $_FILES['prod_img']['name'];
            move_uploaded_file($_FILES["prod_img"]["tmp_name"], "../admin/assets/img/products/" . $prod_img);
        } else {
            $prod_img = $_POST['current_img'];
        }

        // Update Query
        $query = "UPDATE rpos_products SET prod_code = ?, prod_name = ?, prod_img = ?, prod_desc = ?, prod_price = ? WHERE prod_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssssi', $prod_code, $prod_name, $prod_img, $prod_desc, $prod_price, $update);

        if ($stmt->execute()) {
            header("Location: products.php?msg=updated");
            exit();
        } else {
            $err = "Error updating product. Please try again.";
        }
    }
}

// Fetch existing product data
require_once('partials/_head.php');
$update = $_GET['update'];
$stmt = $mysqli->prepare("SELECT * FROM rpos_products WHERE prod_id = ?");
$stmt->bind_param('i', $update);
$stmt->execute();
$result = $stmt->get_result();
$prod = $result->fetch_object();
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>

        <div class="header pb-6 d-flex align-items-center" style="min-height: 350px; background: url('../admin/assets/img/theme/restro00.jpg') center/cover no-repeat;">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid d-flex align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="display-3 text-white font-weight-bold">Update Product</h1>
                    <p class="text-white lead">Modify product details below.</p>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--6">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary text-white text-center">
                            <h3 class="mb-0">Edit Product Details</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($err)) { ?>
                                <div class="alert alert-danger"><?php echo $err; ?></div>
                            <?php } ?>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" name="prod_name" class="form-control" value="<?= htmlspecialchars($prod->prod_name); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Product Code</label>
                                    <input type="text" name="prod_code" class="form-control" value="<?= htmlspecialchars($prod->prod_code); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Product Price (₱)</label>
                                    <input type="number" step="0.01" name="prod_price" class="form-control" value="<?= htmlspecialchars($prod->prod_price); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Product Description</label>
                                    <textarea name="prod_desc" rows="4" class="form-control" required><?= htmlspecialchars($prod->prod_desc); ?></textarea>
                                </div>

                                <div class="form-group text-center">
                                    <label>Current Product Image</label>
                                    <br>
                                    <img id="imgPreview" src="../admin/assets/img/products/<?= htmlspecialchars($prod->prod_img ?: 'default.jpg'); ?>" width="120" height="120" class="img-thumbnail rounded">
                                </div>

                                <div class="form-group">
                                    <label>Upload New Image</label>
                                    <input type="file" name="prod_img" id="prodImg" class="form-control" onchange="previewImage(event)">
                                    <input type="hidden" name="current_img" value="<?= htmlspecialchars($prod->prod_img); ?>">
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" name="UpdateProduct" class="btn btn-success btn-lg shadow">
                                        <i class="fas fa-save"></i> Update Product
                                    </button>
                                    <a href="products.php" class="btn btn-danger btn-lg shadow">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>

    <!-- Preview Image Script -->
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('imgPreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
