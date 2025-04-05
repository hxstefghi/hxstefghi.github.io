<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container d-flex justify-content-center align-items-center mt-5" style="min-height: 65vh;">
    <div class="row rounded-4 shadow-lg overflow-hidden" style="max-width: 600px; width: 100%; background-color: #ffffff;">
        <div class="col-md-12 p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color: #333;">Verify Code</h3>
            </div>
            <?= alertMessage2() ?>
            <form action="verify-code-handler.php" method="POST">
                <input type="hidden" name="email" value="<?= $_SESSION['email'] ?>">
                <div class="mb-3">
                    <label for="code" class="form-label" style="font-weight: 500; color: #555;">Verification Code</label>
                    <input type="text" name="code" id="code" class="form-control" placeholder="Enter the verification code" required>
                </div>
                <button type="submit" name="verify_code_btn" class="btn btn-md w-100 text-white mb-3" style="background-color: #312922;">Verify Code</button>
            </form>
            <form action="send-code.php" method="POST">
                <input type="hidden" name="email" value="<?= $email ?>">
                <button type="submit" name="resend_code_btn" class="btn btn-md w-100 text-white" style="background-color: #555;">Resend Verification Code</button>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>