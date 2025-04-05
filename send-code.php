<?php
session_start();
require 'config/function.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if(isset($_POST['send_code_btn'])) {
    $email = $_POST['email'];
    $_SESSION["email"] = $email;

    // Check if the email exists in the database
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $code = rand(100000, 999999);

        // Save the code in the database
        $query = "UPDATE users SET verification_code='$code' WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        if($result) {
            // Send the code to the user's email
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = 2; // Enable verbose debug output
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth   = true;
                $mail->Username   = 'cupoffaith26@gmail.com'; // SMTP username
                $mail->Password   = 'mycr sjmz krvz yuhg'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('cupoffaith26@gmail.com', 'Cup of Faith');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Verification Code';
                $mail->Body    = "Your verification code is: <b>$code</b>";

                $mail->send();
                redirect('reset-verify-code.php','Verification code successfully sent to your email.','success');
            } catch (Exception $e) {
                redirect('forgot-password.php','Message could not be sent.','error');
            }
        } else {
            redirect('forgot-password.php','Failed to send verification code.','error');
        }
    } else {
        redirect('forgot-password.php','Email not found or is not existing.','error');
    }
}


if (isset($_POST['resend_code_btn'])) {
    $email = $_SESSION['email'];

    // Generate a new 6-digit verification code
    $new_code = rand(100000, 999999);

    // Update the verification code in the database
    $query = "UPDATE users SET verification_code='$new_code' WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Send the new code via email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth   = true;
            $mail->Username   = 'cupoffaith26@gmail.com'; // SMTP username
            $mail->Password   = 'mycr sjmz krvz yuhg'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('cupoffaith26@gmail.com', 'Cup of Faith');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Resend Verification Code';
            $mail->Body    = "Your new verification code is: <b>$new_code</b>";

            $mail->send();
            redirect('reset-verify-code.php', 'Verification code resent successfully.', 'success');
        } catch (Exception $e) {
            redirect('reset-verify-code.php', 'Message could not be sent. Error: ' . $mail->ErrorInfo, 'error');
        }
    } else {
        redirect('reset-verify-code.php', 'Failed to update verification code. Please try again.', 'error');
    }
}
?>