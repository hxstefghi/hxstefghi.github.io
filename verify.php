<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<!-- Verification Section -->
<div class="container d-flex justify-content-center align-items-center mt-5" style="min-height: 65vh;">
    <div class="row rounded-4 shadow-lg overflow-hidden" style="max-width: 1000px; width: 100%; background-color: #ffffff;">
        <!-- Left Section -->
        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center text-white p-4" style="background: #312922;">
            <h2 class="text-center mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Verify Your Email</h2>
            <p class="text-center" style="font-family: 'Roboto', sans-serif; font-size: 1rem; line-height: 1.5;">
                Please enter the verification code sent to your email.
            </p>
        </div>

        <!-- Right Section -->
        <div class="col-md-6 p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color: #333;">Enter Verification Code</h3>
            </div>
            <?= alertMessage2(); ?>
            <form action="verify-code.php" method="POST">
                <!-- Verification Code -->
                <div class="mb-3">
                    <label for="verification_code" class="form-label" style="font-weight: 500; color: #555;">Verification Code</label>
                    <input type="text" name="verification_code" id="verification_code" class="form-control form-control-lg" placeholder="Enter your verification code" maxlength="6" required>
                </div>

                <!-- Verify Button -->
                <button type="submit" name="verify_btn" class="btn btn-md w-100 text-white" style="background: #312922;">Verify</button>
            </form>

            <form action="verify-code.php" method="POST" class="mt-3">
                <!-- Resend Button -->
                <button type="submit" name="resend_btn" class="btn btn-md w-100 text-white" style="background: #312922;">Resend Code</button>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>