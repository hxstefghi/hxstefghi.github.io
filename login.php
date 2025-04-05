<?php
include('includes/header.php');
include('includes/navbar.php');

if(isset($_SESSION['auth'])){
    redirect('index.php', 'You are already logged in', 'error');
}
?>

<?php
// Check if cookies exist and pre-fill the username and password fields
$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$password = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
?>

<!-- Login Section -->
<div class="container d-flex justify-content-center align-items-center mt-5" style="min-height: 65vh;">
    <div class="row rounded-4 shadow-lg overflow-hidden" style="max-width: 900px; width: 100%; background-color: #ffffff;">
        <!-- Left Section -->
        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center text-white p-4" style="background: #312922;">
            <h2 class="text-center mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Welcome Back</h2>
            <p class="text-center" style="font-family: 'Roboto', sans-serif; font-size: 1rem; line-height: 1.5;">
                Join our community today!
            </p>
        </div>

        <!-- Right Section -->
        <div class="col-md-6 p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color: #333;">Login</h3>
            </div>
            <?= alertMessage2() ?>
            <form action="login-code.php" method="POST">
                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label" style="font-weight: 500; color: #555;">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" value="<?= htmlspecialchars($username) ?>" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label" style="font-weight: 500; color: #555;">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" value="<?= htmlspecialchars($password) ?>" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="border-left: none;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me and Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me" <?= isset($_COOKIE['username']) ? 'checked' : '' ?>>
                        <label for="rememberMe" class="form-check-label" style="color: #666;">Remember Me</label>
                    </div>
                    <a href="forgot-password.php" class="text-decoration-none" style="color: #007bff; font-size: 0.9rem;">Forgot Password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" name="login_btn" class="btn btn-md w-100 text-white" style="background-color: #312922;">Login</button>
                
                <!-- Sign Up Link -->
                <div class="text-center mt-4">
                    <p style="color: #555;">Don't have an account? <a href="./signup.php" class="text-decoration-none" style="color: #007bff;">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the icon
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
</script>

<?php
include('includes/footer.php');
?>
