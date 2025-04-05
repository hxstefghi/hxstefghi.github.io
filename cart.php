<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<?= alertMessage2() ?>

<div class="container">
    <div class="py-5">
        <div class="row py-3" style="min-height: 50vh;">
            <div class="col-md-8 mb-3">
                <div class="card text-start">
                    <div class="card-body">
                        <h4 class="card-title">Your cart</h4>
                        <div class="table-responsive-md table-responsive-lg table-responsive-xl">
                        <table class="table text-center table-striped overflow-scroll">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="cart-items">
                            <?php
                            $user_id = validate($_SESSION['loggedInUser']['userId']);
                            $cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
                            $cartIsEmpty = mysqli_num_rows($cart) === 0; // Check if the cart is empty
                            $grandTotal = 0;

                            if (!$cartIsEmpty) {
                                foreach ($cart as $cartItem) {
                                    $itemTotal = $cartItem['price'] * $cartItem['quantity'];
                                    $grandTotal += $itemTotal;
                            ?>
                                <tr data-id="<?= $cartItem['id'] ?>" data-price="<?= $cartItem['price'] ?>">
                                    <input type="hidden" value="<?= $cartItem['id']; ?>">
                                    <td><img src="<?= $cartItem['image'];?>" style="height: 50px; width:50px" alt=""></td>
                                    <td><?= $cartItem['name'];?></td>
                                    <td>₱<?= number_format($cartItem['price'], 2); ?></td>
                                    <td>
                                        <div class="input-group d-flex justify-content-center align-items-center qtyBox">
                                            <!-- Decrement button form -->
                                            <form method="POST" action="cart-code.php" style="display: inline;">
                                                <input type="hidden" name="cartItemId" value="<?= $cartItem['id']; ?>">
                                                <input type="hidden" name="action" value="decrement">
                                                <button type="submit" name="updateQuantity" class="input-group-text">-</button>
                                            </form>
                                            <!-- Quantity Display -->
                                            <span class="mx-2"><?= $cartItem['quantity']; ?></span>
                                            <!-- Increment button form -->
                                            <form method="POST" action="cart-code.php" style="display: inline;">
                                                <input type="hidden" name="cartItemId" value="<?= $cartItem['id']; ?>">
                                                <input type="hidden" name="action" value="increment">
                                                <button type="submit" name="updateQuantity" class="input-group-text">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="cart-delete.php?id=<?= $cartItem['id'];?>" 
                                        class="btn btn-danger mx-2 delete-btn"
                                        data-id="<?= $cartItem['id']; ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="9">Cart is empty</td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-start">
                    <form method="POST" action="cart-code.php">
                        <div class="card-body">
                            <h4 class="card-title">Grand Total</h4>
                            <hr>
                            <div class="row d-flex justify-content-center align-items-center text-center mb-2">
                                <div class="col-md-4 text-start">
                                    <strong>Name</strong>
                                </div>
                                <div class="col-md-4">
                                    <strong>Quantity</strong>
                                </div>
                                <div class="col-md-4 text-end">
                                    <strong>Amount</strong>
                                </div>
                            </div>
                            <?php
                                if (!$cartIsEmpty) { // Display cart items if not empty
                                    foreach($cart as $cartItem) {
                                        $itemTotal = $cartItem['price'] * $cartItem['quantity'];
                            ?>
                                    <div class="row text-center">
                                        <div class="col-md-4 text-start">
                                            <p><?= $cartItem['name'] ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p>x <?= $cartItem['quantity'] ?></p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <p>₱<?= number_format($itemTotal, 2); ?></p>
                                        </div>
                                    </div>
                            <?php
                                    }
                                }
                            ?>
                            <hr>
                            <div class="group-list d-flex justify-content-between align-items-center py-0">
                                <strong>Total Amount:</strong>
                                <p id="grandTotal">₱<?= number_format($grandTotal, 2); ?></p>
                            </div>

                            <?php if (!$cartIsEmpty): ?>
                                <a href="checkout.php?id=<?= $_SESSION['loggedInUser']['userId'] ?>" name="checkOut" style="background: #312922; border:none;" class="btn btn-primary float-end w-100 mt-4 mb-3">Proceed To Checkout</a>
                            <?php else: ?>
                                <button class="btn btn-secondary float-end w-100 mt-4 mb-3" disabled>Cart is Empty</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const deleteUrl = this.href;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action will delete the item from your cart.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>

<?php
include('includes/footer.php');
?>
