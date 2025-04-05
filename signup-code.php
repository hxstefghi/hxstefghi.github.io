<?php

session_start();
require 'config/function.php';
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['signup_btn']))
{
    $username = validate($_POST['username']);
    $firstname = validate($_POST['firstname']);
    $lastname = validate($_POST['lastname']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $password = validate($_POST['password']);
    $cpassword = validate($_POST['cpassword']);
    $address = validate($_POST['address']);
    
    $_SESSION['email'] = $email;

    if($username != '' && $firstname != '' && $lastname != '' && $email != '' && $phone != '' && $password != '')
    {
        if($password != $cpassword)
        {
            redirect('signup.php','Password not matched','warning');
        }

        // Check for duplicate username
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0)
        {
            redirect('signup.php','Username already exists','warning');
        }

        // Check for duplicate email
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0)
        {
            redirect('signup.php','Email already exists','warning');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $verificationCode = rand(100000, 999999);
        $verificationExpiry = date("Y-m-d H:i:s", strtotime('+30 minutes')); // Set expiry to 30 minutes

        $query = "INSERT INTO users (username, fname, lname, email, phone, password, address, verification_code, verification_expiry) 
        VALUES ('$username','$firstname','$lastname','$email','$phone','$hashedPassword','$address','$verificationCode','$verificationExpiry')";

        $result = mysqli_query($conn, $query);

        if($result){
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
                $mail->addAddress($email, $firstname . ' ' . $lastname);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Verify your email address';
                $mail->Body    = "Please enter the following verification code to verify your email address:<br><b>$verificationCode</b>";

                $mail->send();
                redirect('verify.php','Signup Successful. Please check your email for the verification code.','success');
            } catch (Exception $e) {
                redirect('signup.php','Message could not be sent. Mailer Error: '.$mail->ErrorInfo,'error');
            }
        }else{
            redirect('signup.php','Something Went Wrong','error');
        }
    }
    else 
    {
        redirect('signup.php', 'Please fill all the input fields','warning');
    }
}

?>