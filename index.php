<?php

include('includes/header.php');
include('includes/navbar.php');
?>

<?= alertMessage2() ?>

<!-- main content -->
<div class="main-content" style="
    background: url(./assets/images/bg3.jpg); background-size: cover;
    background-position: 70%; display: flex; justify-content: center; text-align: center; height: 100vh; align-items: center;">
    <div class="content text-center" style="width: 100%; max-width: 50%;">
        <h1 class="display-4 mb-4 fw-bold">Buy Coffee Now</h1>
        <p class="lead fs-6">Sip, Savor, and Be Blessed – Freshly Brewed Coffee & Homemade Goodness Await!</p>
    </div>    
</div>
<!-- main content -->

<!-- home content -->

<!-- product cards -->
<div class="container">
    <div class="opposing-items mt-5 mb-4 d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-4">Popular Now</h3>
        <a href="products.php" class="btn btn-link">View all</a>
    </div>
    
    <div class="row">
        <?php
        // Filter products based on the selected category
        $productQuery = "SELECT * FROM products WHERE status='0' ORDER BY id LIMIT 12";

        $result = mysqli_query($conn, $productQuery);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
        ?>

        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 col-6 py-3 py-md-0 mb-5">
            <a href="product-details.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
                <div class="card" id="c">
                    <input type="hidden" value="<?= $row['id'] ?>" name="product_id">
                    <input type="hidden" value="<?= $row['name'] ?>" name="name">
                    <input type="hidden" value="<?= $row['price'] ?>" name="price">
                    <input type="hidden" value="1" name="quantity">
                    <input type="hidden" value="<?= $row['image'] ?>" name="image">

                    <?php if ($row['image'] != '') : ?>
                        <img src="<?= $row['image'] ?>" alt="Image" class="card-img-top img-fluid">
                    <?php else : ?>
                        <img src="./assets/images/no-image.png" alt="Image" class="card-img-top img-fluid">
                    <?php endif; ?>

                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $row['name'] ?></h5>
                        <p class="fw-normal"><?= $row['description'] ?></p>
                        <div class="d-flex align-items-center justify-content-center mt-3">
                            <p>₱<?= $row['price'] ?></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php
                }
            } else {
                echo '<h5>No Record Found</h5>';
            }
        } else {
            echo '<h5>Something Went Wrong</h5>';
        }
        ?>
    </div>
</div>
<!-- product cards -->

<!-- about -->
<div class="container">
    <h1 class="text-center" style="margin-top: 50px;">CUP OF FAITH</h1>
    <div class="row" style="margin-top: 50px;">
        <div class="col-md-6 py-3 py-md-0">
            <div class="card">
                <img src="./assets/images/image4.png" alt="" class="img-fluid">
            </div>
        </div>
        <div class="col-md-6 py-3 py-md-0">
            <p>Nestled in the heart of the community, Cup of Faith is more than just a coffee shop—it’s a place of comfort, connection, and inspiration. Whether you’re looking for a peaceful morning with a handcrafted latte, a cozy nook for meaningful conversations, or a quiet space to reflect, our doors are always open. Enjoy rich, ethically sourced coffee, delicious homemade pastries, and a welcoming atmosphere filled with kindness and encouragement. Every cup is brewed with love, and every visit feels like home. Come in, take a moment, and let your spirit be refreshed—one sip at a time.</p>
        </div>
    </div>
</div>
<!-- about -->

<?php
include('includes/footer.php');
?>