<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<?= alertMessage2() ?>

<style>
    .card-container {
        display: flex;
        flex-wrap: wrap;
    }
    .card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .card-body {
        flex-grow: 1;
    }
</style>

<div class="container">
    <div class="py-5">
        <div class="row card-container" style="margin-top: 50px;">

            <!-- Category Filter Dropdown -->
            <div class="d-flex flex-column flex-md-row justify-content-between mb-5">
                <form action="" method="GET" class="mb-3 mb-md-0">
                    <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Products</option>
                        <?php
                        // Fetch categories from the database
                        $categoryQuery = "SELECT * FROM categories";
                        $categoryResult = mysqli_query($conn, $categoryQuery);

                        if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                            while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                                // Pre-select the selected category
                                $selected = isset($_GET['category']) && $_GET['category'] == $categoryRow['id'] ? 'selected' : '';
                                echo "<option value='{$categoryRow['id']}' $selected>{$categoryRow['name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </form>

                <!-- Search Form -->
                <form action="" method="GET" class="d-flex mb-3 mb-md-0">
                    <input type="text" name="search" class="form-control" placeholder="Search products" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit" class="btn btn-dark ms-2" style="background-color: #312922;">Search</button>
                </form>

                <?php
                // Determine the current category name based on selected category ID
                $categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
                $currentCategoryName = 'All Products';

                if ($categoryFilter) {
                    // Fetch the selected category name from the database
                    $currentCategoryQuery = "SELECT name FROM categories WHERE id = '$categoryFilter' LIMIT 1";
                    $currentCategoryResult = mysqli_query($conn, $currentCategoryQuery);

                    if ($currentCategoryResult && mysqli_num_rows($currentCategoryResult) > 0) {
                        $currentCategoryRow = mysqli_fetch_assoc($currentCategoryResult);
                        $currentCategoryName = $currentCategoryRow['name'];
                    }
                }
                ?>
                
                
            </div>

            <?php
            // Filter products based on the selected category and search term
            $productQuery = "SELECT * FROM products WHERE status='0'";

            // Add category filter to query if a category is selected
            if ($categoryFilter) {
                $productQuery .= " AND category_id = '$categoryFilter'";
            }

            // Add search filter to query if a search term is provided
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
                $productQuery .= " AND (name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%')";
            }

            $result = mysqli_query($conn, $productQuery);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
            ?>
                        <div class="col-6 col-md-6 col-sm-6 col-lg-6 col-xl-3 py-3 py-md-0 mb-5">
                            <a href="product-details.php?id=<?= $row['id'] ?>" class="text-decoration-none text-dark">
                                <div class="card" id="c">
                                    <?php if ($row['image'] != '') : ?>
                                        <img src="<?= $row['image'] ?>" alt="Image" class="card-img-top">
                                    <?php else : ?>
                                        <img src="./assets/images/no-image.png" alt="Image" class="card-img-top">
                                    <?php endif; ?>
            
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><?= $row['name'] ?></h5>
                                        <p class="fw-normal text-secondary"><?= $row['description'] ?></p>
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
                    echo '<h5>No product found</h5>';
                }
            } else {
                echo '<h5>Something Went Wrong</h5>';
            }
            ?>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>