<?php
session_start();
require 'config/function.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if(isset($_POST['verify_btn']))
{
    $entered_code = validate($_POST['verification_code']);
    $email = $_SESSION['email'];

    $query = "SELECT * FROM users WHERE email='$email' AND verification_code='$entered_code'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        $query = "UPDATE users SET verification_code=NULL, verification_expiry=NULL, is_verified=1 WHERE email='$email'";
        mysqli_query($conn, $query);
        redirect('login.php','Email verified successfully. You can now log in.','success');
    }
    else
    {
        redirect('verify.php','Invalid or expired verification code.','error');
    }
}
else if(isset($_POST['resend_btn']))
{
    $email = $_SESSION['email'];
    $query = "SELECT * FROM users WHERE email='$email' AND is_verified=0";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        $user = mysqli_fetch_assoc($result);
        $verificationCode = rand(100000, 999999); // Generate a 6-digit code
        $verificationExpiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $query = "UPDATE users SET verification_code='$verificationCode', verification_expiry='$verificationExpiry' WHERE email='$email'";
        mysqli_query($conn, $query);

        // Send verification email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth   = true;
            $mail->Username   = 'cupoffaith26@gmail.com'; // SMTP username
            $mail->Password   = 'mycr sjmz krvz yuhg'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('cupoffaith26@gmail.com', 'Cup of Faith');
            $mail->addAddress($email, $user['fname'] . ' ' . $user['lname']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verify your email address';
            $mail->Body    = "Please enter the following verification code to verify your email address:<br><b>$verificationCode</b>";

            $mail->send();
            redirect('verify.php','Verification code resent. Please check your email.','success');
        } catch (Exception $e) {
            redirect('verify.php','Message could not be sent. Mailer Error: '.$mail->ErrorInfo,'error');
        }
    }
    else
    {
        redirect('verify.php','Email not found or already verified.','error');
    }
}
else
{
    redirect('signup.php','No verification code provided.','error');
}
?>