<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $adn = "DELETE FROM rpos_products WHERE prod_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id); // Changed 's' to 'i' for integer binding
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Product deleted successfully.";
        header("refresh:1; url=products.php");
    } else {
        $err = "Try Again Later";
    }
}

require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div class="header pb-8 pt-5 pt-md-8" style="background-image: url('assets/img/theme/restro00.jpg'); background-size: cover;">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                    <h1 class="text-white">Product Management</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid mt--8">
            <!-- Alerts -->
            <?php if (isset($success)) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success; ?>
                </div>
            <?php } ?>
            <?php if (isset($err)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $err; ?>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                            <h3 class="mb-0">Products</h3>
                            <a href="add_product.php" class="btn btn-light btn-sm">
                                <i class="fas fa-utensils"></i> Add New Product
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Code</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM rpos_products";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($prod = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td>
                                                <img src='assets/img/products/<?php echo $prod->prod_img ? $prod->prod_img : "default.jpg"; ?>' height='60' width='60' class='img-thumbnail'>
                                            </td>
                                            <td><?php echo htmlspecialchars($prod->prod_code); ?></td>
                                            <td><?php echo htmlspecialchars($prod->prod_name); ?></td>
                                            <td>₱<?php echo number_format($prod->prod_price, 2); ?></td>
                                            <td>
                                                <a href="products.php?delete=<?php echo $prod->prod_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                                <a href="update_product.php?update=<?php echo $prod->prod_id; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Update
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
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>