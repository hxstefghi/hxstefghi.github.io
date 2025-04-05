<?php
include('includes/header.php');
include('includes/navbar.php');

if (isset($_POST['verify_code_btn'])) {
    $email = $_POST['email'];
    $code = $_POST['code'];

    // Verify the code
    $query = "SELECT * FROM users WHERE email='$email' AND verification_code='$code'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Code is valid, redirect to new password page
        $_SESSION['email'] = $email;
        redirect('set-new-password.php', 'Valid', 'success');
    } else {
        redirect('reset-verify-code.php', 'Invalid verification code.', 'error');
    }
}
?>