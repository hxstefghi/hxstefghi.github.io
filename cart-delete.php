<?php

require 'config/function.php';

$paraResult = checkParamId('id');

if(is_numeric($paraResult))
{
  $cartId = validate($paraResult);
  $user_id = validate($_SESSION['loggedInUser']['userId']);

  $cart = getById('cart', $cartId);
  if($cart['status'] == 200)
  {
    $product_id = $cart['data']['product_id'];
    $productResult = mysqli_query($conn, "SELECT name FROM products WHERE id = '$product_id'");
    if ($productResult && mysqli_num_rows($productResult) > 0) {
        $product = mysqli_fetch_assoc($productResult);
        $productName = $product['name'];

        $cartDelete = deleteQuery('cart', $cartId);

        if($cartDelete){
          logActivity($user_id, "Deleted product '$productName' from cart");
          redirect('cart.php','Product deleted successfully','success');
        } else {
          redirect('cart.php','Something Went Wrong','error');
        }
    } else {
        redirect('cart.php','Product not found','error');
    }
  }
  else 
  {
    redirect('cart.php',$cart['message'],'error');
  }

}
else 
{
  redirect('cart.php',$paraResult,'error');
}

?>