<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<?= alertMessage2() ?>

<div class="container">
    <div class="py-5">
        <form action="placeorder-code.php" method="POST">
            <div class="row py-3" style="min-height: 50vh;">
                <div class="col-md-7 mb-3">
                    <div class="card text-start">
                        <div class="card-body">
                            <h4 class="card-title">Basic Details</h4>
                            <hr>
                            <?php


                                $paramResult = checkParamId('id');
                                if(!is_numeric($paramResult)){
                                echo '<h5>'.$paramResult.'</h5>';
                                return false;
                                }

                                $user = getById('users',checkParamId('id'));
                                if($user['status'] == 200)
                                {
                                ?>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="name" placeholder="Full Name" value="<?= $user['data']['fname']?> <?= $user['data']['lname']?>" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="address" placeholder="Address" value="<?= $user['data']['address']?>" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <input type="text" name="phone" placeholder="Phone Number" value="<?= $user['data']['phone']?>" readonly class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <input type="email" name="email" placeholder="Email Address" value="<?= $user['data']['email']?>" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                            else 
                            {
                                ?>
                                <td colspan="9">No Record Found</td>
                                <?php
                            }
            
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card text-start">
                        <div class="card-body">
                            <h4 class="card-title">Review Order</h4>
                            <hr>
                            <div class="row d-flex justify-content-center align-items-center text-center mb-2">
                                <div class="col-md-3">
                                    <strong>Image</strong>
                                </div>
                                <div class="col-md-3">
                                    <strong>Name</strong>
                                </div>
                                <div class="col-md-3">
                                    <strong>Quantity</strong>
                                </div>
                                <div class="col-md-3 text-end">
                                    <strong>Price</strong>
                                </div>
                            </div>
                            <?php
                                    $user_id = validate($_SESSION['loggedInUser']['userId']);
                                    $cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
                                    $totalPrice = 0;
                                    if(mysqli_num_rows($cart) > 0) {
                                        foreach($cart as $cartItem) {
                                ?>

                                    <input type="hidden" value="<?= $cartItem['id'] ?>" name="product_id">

                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <img src="<?= $cartItem['image'];?>" style="height: 50px; width:50px" alt="">
                                        </div>
                                        <div class="col-md-3">
                                            <p><?= $cartItem['name'] ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p>x <?= $cartItem['quantity'] ?></p>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <p>₱<?= number_format($cartItem['price'], 2) ?></p>
                                        </div>
                                    </div>
                                    
                                    <!--
                                    <div class="group-list d-flex justify-content-between align-items-center">
                                    <p><?= $cartItem['name'] ?> (<?= $cartItem['quantity'] ?>)</p>
                                    <p>₱<?= number_format($cartItem['price'], 2) ?></p>
                                    </div>
                                    -->
                            <?php
                                    $totalPrice += $cartItem['price'] * $cartItem['quantity'];
                                }
                            }


                            ?>

                            <h4 class="card-title mt-3">Payment Method</h4>
                            <hr>

                            <!-- Cash on Delivery Option -->
                            <div class="form-check mb-2">
                                <input type="radio" id="cod" name="payment_mode" value="COD" class="form-check-input" checked onclick="togglePaymentFields()">
                                <label for="cod" class="form-check-label">
                                    <img src="assets/images/cash-on-delivery.png" alt="COD" style="height: 20px; width: 20px; margin-right: 8px;">Cash on Delivery
                                </label>
                            </div>

                            <!-- GCash Option -->
                            <div class="form-check mb-2">
                                <input type="radio" id="gcash" name="payment_mode" value="GCash" class="form-check-input" onclick="togglePaymentFields()">
                                <label for="gcash" class="form-check-label">
                                    <img src="assets/images/gcash-seeklogo.png" alt="GCash" style="height: 20px; width: 80px; margin-right: 8px;">
                                </label>
                            </div>
                            <!-- GCash Phone Number Input (hidden by default) -->
                            <div id="gcashFields" class="mb-2" style="display: none; margin-top: 10px;">
                                <input type="text" name="gcash_number" placeholder="Enter GCash Number" class="form-control">
                            </div>

                            <!-- Visa/Mastercard Option -->
                            <div class="form-check mb-2">
                                <input type="radio" id="visa_mastercard" name="payment_mode" value="Visa/Mastercard" class="form-check-input" onclick="togglePaymentFields()">
                                <label for="visa_mastercard" class="form-check-label">
                                    <i class="fa-brands fa-cc-visa"></i>
                                    <i class="fa-brands fa-cc-mastercard"></i>
                                </label>
                            </div>
                            <!-- Visa/Mastercard Details Inputs (hidden by default) -->
                            <div id="cardFields" class="mb-2" style="display: none; margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="card_number" placeholder="Card Number" class="form-control mb-2">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="expiry_date" placeholder="Expiry Date (MM/YY)" class="form-control mb-2">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="cvv" placeholder="CVV" class="form-control">
                                    </div>
                                </div>
                            </div>


                            <hr>
                            <div class="group-list d-flex justify-content-between align-items-center py-0">
                                <strong>Order Total:</strong>
                                <p id="grandTotal">₱<?= number_format($totalPrice, 2) ?></p>
                            </div>
                            <button type="submit" name="placeOrder" class="btn btn-primary float-end w-100 mt-4 mb-3" style="background: #312922; border:none;">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function togglePaymentFields() {
    // Hide all payment-specific fields initially
    document.getElementById('gcashFields').style.display = 'none';
    document.getElementById('cardFields').style.display = 'none';

    // Show the fields based on the selected payment mode
    if (document.getElementById('gcash').checked) {
        document.getElementById('gcashFields').style.display = 'block';
    } else if (document.getElementById('visa_mastercard').checked) {
        document.getElementById('cardFields').style.display = 'block';
    }
}
</script>

<?php
include('includes/footer.php');
?>
