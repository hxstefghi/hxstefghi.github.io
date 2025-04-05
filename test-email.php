<?php
$to = 'your-email@example.com';
$subject = 'Test Email';
$message = 'This is a test email.';
$headers = 'From: no-reply@yourdomain.com' . "\r\n" .
           'Reply-To: no-reply@yourdomain.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo 'Email sent successfully.';
} else {
    echo 'Failed to send email.';
}
?>