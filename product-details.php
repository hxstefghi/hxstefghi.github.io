<?php
include('includes/header.php');
include('includes/navbar.php');


if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $productQuery = "SELECT * FROM products WHERE id = '$productId' LIMIT 1";
    $result = mysqli_query($conn, $productQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo '<h5>Product Not Found</h5>';
        exit;
    }
} else {
    echo '<h5>No Product ID Provided</h5>';
    exit;
}
?>

<div class="container">
    <div class="mb-3 py-3">
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-7 col-sm-6 col-lg-6 col-xl-5 py-3 mx-auto py-md-0 mb-5">
                <nav aria-label="breadcrumb" class="d-flex justify-content-center justify-content-md-start">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-dark">Home</a></li>
                        <li class="breadcrumb-item"><a href="products.php" class="text-dark">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $product['name'] ?></li>
                    </ol>
                </nav>
                <img src="<?= $product['image'] ?>" alt="Image" class="card-img-top">
            </div>
            <div class="col-md-5 mx-auto">
                <form action="products-code.php" method="POST">
                    <div class="card text-start p-3 mt-5" id="c" style="min-height: 100%;">
                        <div class="text-center">
                            <h2 class="mb-2"><?= $product['name'] ?></h2>
                            <p class="fw-normal text-secondary mb-5"><?= $product['description'] ?></p>
                            <h4>₱<?= $product['price'] ?></h4>
                        </div>
                        <form action="products-code.php" method="POST">
                            <input type="hidden" value="<?= $product['id'] ?>" name="product_id">
                            <input type="hidden" value="<?= $product['name'] ?>" name="name">
                            <input type="hidden" value="<?= $product['price'] ?>" name="price">
                            <input type="hidden" value="1" name="quantity">
                            <input type="hidden" value="<?= $product['image'] ?>" name="image">
                            
                            <?php if (strtolower($product['category_id']) == 5) : ?>
                                <div class="form-group mb-3">
                                    <label for="size" class="mb-2">Size:</label>
                                    <select name="size" id="size" class="form-select">
                                        <option value="Small">Small</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Large">Large</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sugar" class="mb-2">Sugar Percentage:</label>
                                    <select name="sugar" id="sugar" class="form-select">
                                        <option value="0%">0%</option>
                                        <option value="25%">25%</option>
                                        <option value="50%">50%</option>
                                        <option value="75%">75%</option>
                                        <option value="100%">100%</option>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <button class="btn btn-dark text-white w-100 mt-3" style="background-color: #312922;" name="addToCart" type="submit">Add to Cart</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>