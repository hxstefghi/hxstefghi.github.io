<?php
require 'config/function.php';

if (isset($_POST['checkOut'])) {

    $user_id = validate($_SESSION['loggedInUser']['userId']);
    $cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");

    // Check if the cart is empty
    if (mysqli_num_rows($cart) <= 0) {
        redirect('cart.php', 'Your cart is empty', 'warning');
    }

}

if (isset($_POST['updateQuantity'])) {
    $cartItemId = $_POST['cartItemId'];
    $action = $_POST['action'];
    $user_id = validate($_SESSION['loggedInUser']['userId']);

    // Fetch the current quantity from the database
    $result = mysqli_query($conn, "SELECT quantity, product_id FROM cart WHERE id = '$cartItemId' AND user_id = '$user_id'");
    if ($result && mysqli_num_rows($result) > 0) {
        $cartItem = mysqli_fetch_assoc($result);
        $quantity = $cartItem['quantity'];
        $product_id = $cartItem['product_id'];

        // Fetch the current quantity of the product from the database
        $productResult = mysqli_query($conn, "SELECT quantity FROM products WHERE id = '$product_id'");
        if ($productResult && mysqli_num_rows($productResult) > 0) {
            $product = mysqli_fetch_assoc($productResult);
            $currentProductQuantity = $product['quantity'];

            // Update the quantity of the product in the database based on the action
            if ($action === 'increment') {
                $newQuantity = $quantity + 1;
                $newProductQuantity = $currentProductQuantity - 1;
            } elseif ($action === 'decrement' && $quantity > 1) {
                $newQuantity = $quantity - 1;
                $newProductQuantity = $currentProductQuantity + 1;
            }

            // Check if the product is out of stock
            if ($currentProductQuantity <= 0 && $newProductQuantity <= 0) {
                redirect('cart.php', 'Product is out of stock', 'warning');
            }

            // Update the quantity of the product in the database
            mysqli_query($conn, "UPDATE products SET quantity = '$newProductQuantity' WHERE id = '$product_id'");

            // Update the quantity in the cart
            mysqli_query($conn, "UPDATE cart SET quantity = '$newQuantity' WHERE id = '$cartItemId' AND user_id = '$user_id'");
        }
    }

    // Redirect back to cart with a message (optional)
    redirect('cart.php', 'Quantity updated successfully', 'success');
}

?>