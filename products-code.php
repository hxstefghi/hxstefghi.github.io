<?php

require 'config/function.php';

if(isset($_POST['addToCart']))
{
    if(!isset($_SESSION['loggedInUserRole']))
    {
        redirect('login.php','Please login first to add cart','warning');
    }

    $user_id = validate($_SESSION['loggedInUser']['userId']);
    $product_id = validate($_POST['product_id']);
    $name = validate($_POST['name']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $image = validate($_POST['image']);
    $size = isset($_POST['size']) ? validate($_POST['size']) : null;
    $sugar = isset($_POST['sugar']) ? validate($_POST['sugar']) : null;

    // Fetch the current quantity of the product from the database
    $result = mysqli_query($conn, "SELECT quantity FROM products WHERE id = '$product_id'");
    $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        $currentQuantity = $product['quantity'];

        // Check if the product is out of stock
        if ($currentQuantity <= 0) {
            redirect('products.php', 'Product is out of stock', 'warning');
        }

        // Calculate the new quantity
        $newQuantity = $currentQuantity - $quantity;

        // Update the quantity of the product in the database
        mysqli_query($conn, "UPDATE products SET quantity = '$newQuantity' WHERE id = '$product_id'");

        // Add the product to the cart
        if(mysqli_num_rows($select_cart) > 0){
            redirect('products.php','Product already added to cart','warning');
        }else{
            $insert_product = mysqli_query($conn, "INSERT INTO cart(user_id, product_id, name, price, quantity, image, size, sugar) 
                            VALUES('$user_id','$product_id','$name','$price','$quantity','$image','$size','$sugar')");
            logActivity($user_id, "Added product '$name' to cart");
            redirect('products.php','Product added to cart','success');
        }
    }
}

?>