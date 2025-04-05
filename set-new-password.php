<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container d-flex justify-content-center align-items-center mt-5" style="min-height: 65vh;">
    <div class="row rounded-4 shadow-lg overflow-hidden" style="max-width: 600px; width: 100%; background-color: #ffffff;">
        <div class="col-md-12 p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color: #333;">Set New Password</h3>
            </div>
            <?= alertMessage2() ?>
            <form action="reset-password.php" method="POST">
                <input type="hidden" name="email" value="<?= $_SESSION['email'] ?>">
                <div class="mb-3">
                    <label for="new_password" class="form-label" style="font-weight: 500; color: #555;">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter your new password" required>
                </div>
                <button type="submit" name="reset_password_btn" class="btn btn-md w-100 text-white" style="background-color: #312922;">Reset Password</button>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>