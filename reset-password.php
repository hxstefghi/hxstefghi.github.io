<?php
include('includes/header.php');
include('includes/navbar.php');

if (isset($_POST['reset_password_btn'])) {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    // Update the password
    $query = "UPDATE users SET password='$new_password', verification_code=NULL WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        redirect('login.php', 'Password reset successfully.', 'success');
    } else {
        redirect('set-new-password.php', 'Failed to reset password.', 'error');
    }
}
?>