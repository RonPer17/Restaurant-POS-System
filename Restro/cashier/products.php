<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $mysqli->prepare("DELETE FROM rpos_products WHERE prod_id = ?");
    $stmt->bind_param('s', $id);
    if ($stmt->execute()) {
        header("Location: products.php?msg=deleted");
        exit();
    } else {
        $err = "Error deleting product. Try again later.";
    }
    $stmt->close();
}

require_once('partials/_head.php');
?>
<body>
    <?php require_once('partials/_sidebar.php'); ?>
    
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>

        <!-- Page Header -->
        <div class="header pb-6 d-flex align-items-center" style="min-height: 400px; background: url('../admin/assets/img/theme/restro00.jpg') center/cover no-repeat;">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid d-flex align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="display-3 text-white font-weight-bold">Manage Products</h1>
                    <p class="text-white lead">Easily view, update, and delete your products.</p>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid mt--6">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Product List</h3>
                            <a href="add_product.php" class="btn btn-success btn-sm shadow-sm">
                                <i class="fas fa-plus"></i> Add New Product
                            </a>
                        </div>

                        <div class="table-responsive p-3">
                            <table class="table align-items-center table-hover table-striped">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Code</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $mysqli->prepare("SELECT * FROM rpos_products ORDER BY created_at DESC");
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($prod = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td class="text-center">
                                                <img src="../admin/assets/img/products/<?= htmlspecialchars($prod->prod_img ?: 'default.jpg'); ?>" 
                                                     class="img-thumbnail rounded-circle shadow-sm" width="70" height="70">
                                            </td>
                                            <td class="font-weight-bold text-primary"><?= htmlspecialchars($prod->prod_code); ?></td>
                                            <td class="text-dark"><?= htmlspecialchars($prod->prod_name); ?></td>
                                            <td class="text-success font-weight-bold">₱<?= number_format($prod->prod_price, 2); ?></td>
                                            <td class="text-center">
                                                <a href="update_product.php?update=<?= $prod->prod_id; ?>" class="btn btn-sm btn-primary shadow-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="?delete=<?= $prod->prod_id; ?>" onclick="return confirm('Are you sure you want to delete this product?');" class="btn btn-sm btn-danger shadow-sm">
                                                    <i class="fas fa-trash"></i> Delete
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
