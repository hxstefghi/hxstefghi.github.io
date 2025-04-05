<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<!-- Signup Section -->
<div class="container d-flex justify-content-center align-items-center mt-5" style="min-height: 65vh;">
    <div class="row rounded-4 shadow-lg overflow-hidden" style="max-width: 1000px; width: 100%; background-color: #ffffff;">
        <!-- Left Section -->
        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center text-white p-4" style="background: #312922;">
            <h2 class="text-center mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Join Us</h2>
            <p class="text-center" style="font-family: 'Roboto', sans-serif; font-size: 1rem; line-height: 1.5;">
                Join our community today!
            </p>
        </div>

        <!-- Right Section -->
        <div class="col-md-6 p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color: #333;">Sign Up</h3>
            </div>
            <?= alertMessage2(); ?>
            <form action="signup-code.php" method="POST">
                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label" style="font-weight: 500; color: #555;">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                </div>

                <!-- First and Last Name -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstname" class="form-label" style="font-weight: 500; color: #555;">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" placeholder="First name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastname" class="form-label" style="font-weight: 500; color: #555;">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Last name" required>
                    </div>
                </div>

                <!-- Email and Phone -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label" style="font-weight: 500; color: #555;">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label" style="font-weight: 500; color: #555;">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone number" required pattern="\d{11}" minlength="11" maxlength="11">
                    </div>
                </div>

                <!-- Password and Confirm Password -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label" style="font-weight: 500; color: #555;">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cpassword" class="form-label" style="font-weight: 500; color: #555;">Confirm Password</label>
                        <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm password" required>
                    </div>
                </div>

                <!-- Address -->
                <div class="mb-3">
                    <label for="address" class="form-label" style="font-weight: 500; color: #555;">Address</label>
                    <textarea name="address" id="address" class="form-control" placeholder="Address" rows="4" required></textarea>
                </div>

                <!-- Sign Up Button -->
                <button type="submit" name="signup_btn" class="btn btn-md w-100 text-white" style="background: #312922;">Sign Up</button>

                <!-- Login Link -->
                <div class="text-center mt-4">
                    <p style="color: #555;">Already have an account? <a href="./login.php" class="text-decoration-none" style="color: #007bff;">Log In</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
