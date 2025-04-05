<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container d-flex justify-content-center align-items-center mt-5" style="min-height: 65vh;">
    <div class="row rounded-4 shadow-lg overflow-hidden" style="max-width: 600px; width: 100%; background-color: #ffffff;">
        <div class="col-md-12 p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color: #333;">Forgot Password</h3>
            </div>
            <?= alertMessage2() ?>
            <form action="send-code.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label" style="font-weight: 500; color: #555;">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <button type="submit" name="send_code_btn" class="btn btn-md w-100 text-white" style="background-color: #312922;">Send Verification Code</button>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>