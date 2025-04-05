<?php
require 'config/function.php';
require 'vendor/autoload.php'; // Add this line to include the QR code library and PHPMailer

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_SESSION['auth'])) {
    if (isset($_POST['placeOrder'])) {
        $name = validate($_POST['name']);
        $address = validate($_POST['address']);
        $phone = validate($_POST['phone']);
        $email = validate($_POST['email']);
        $payment_mode = validate($_POST['payment_mode']);

        // Additional payment details based on the selected payment method
        $payment_id = ''; // Initialize payment_id
        if ($payment_mode == "GCash") {
            $payment_id = validate($_POST['gcash_number']);  // GCash number
        } elseif ($payment_mode == "Visa/Mastercard") {
            $card_number = validate($_POST['card_number']);
            $expiry_date = validate($_POST['expiry_date']);
            $cvv = validate($_POST['cvv']);
            $payment_id = $card_number; // Store card number or last 4 digits as payment_id
        }
        
        // Get user ID from the session
        $user_id = validate($_SESSION['loggedInUser']['userId']);

        if ($name == '' || $address == '' || $phone == '' || $email == '') {
            redirect('checkout.php', 'All fields are mandatory', 'warning');
        }

        // Fetch cart items for the current user
        $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
        $totalPrice = 0;

        foreach ($select_cart as $cartItem) {
            $totalPrice += $cartItem['price'] * $cartItem['quantity'];
        }

        // Generate tracking number
        $tracking_no = rand(1111, 9999) . substr($phone, -4); // Get last 4 digits of the phone number

        // Insert the order into the database
        $insert_query = "INSERT INTO orders (tracking_no, user_id, name, email, phone, address, total_price, payment_mode, payment_id) 
                        VALUES ('$tracking_no', '$user_id', '$name', '$email', '$phone', '$address', '$totalPrice', '$payment_mode', '$payment_id')";

        $insert_query_run = mysqli_query($conn, $insert_query);

        if ($insert_query_run) {
            $order_id = mysqli_insert_id($conn); // Get last inserted order ID

            // Insert each cart item into order_items table
            foreach ($select_cart as $cartItem) {
                $quantity = $cartItem['quantity'];
                $price = $cartItem['price'];
                $product_id = $cartItem['product_id'];
                $size = $cartItem['size'];
                $sugar = $cartItem['sugar'];

                $insert_items_query = "INSERT INTO order_items (order_id, prod_id, quantity, price, size, sugar) 
                                    VALUES ('$order_id', '$product_id', '$quantity', '$price', '$size', '$sugar')";
                $insert_items_query_run = mysqli_query($conn, $insert_items_query);
            }

            // Generate QR code with order details
            $qrCode = new QrCode("Order ID: $order_id\nTracking No: $tracking_no\nTotal Price: ₱$totalPrice");
            $writer = new PngWriter();
            $qrCodePath = 'assets/qrcodes/order_' . $order_id . '.png';
            $writer->write($qrCode)->saveToFile($qrCodePath);

            // Check if the file exists
            if (!file_exists($qrCodePath)) {
                die('Failed to create QR code file.');
            }

            // Send QR code to user's email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'cupoffaith26@gmail.com';
                $mail->Password = 'mycr sjmz krvz yuhg';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('cupoffaith26@gmail.com', 'Cup of Faith');
                $mail->addAddress($email, $name);

                // Attachments
                $mail->addAttachment($qrCodePath);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your Order Confirmation';
                $mail->Body    = 'Thank you for your order. Please find the QR code attached for tracking your order.';
                $mail->AltBody = 'Thank you for your order. Please find the QR code attached for tracking your order.';

                $mail->send();

                // Clear cart after placing order (optional)
                mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");
                logActivity($user_id, "Order placed with order ID: $order_id");
                redirect('my-orders.php', 'Order placed successfully. A QR code has been sent to your email.', 'success');
        
            } catch (Exception $e) {
                redirect('checkout.php', 'Failed to send email. Mailer Error: ' . $mail->ErrorInfo, 'error');
            }
        } else {
            redirect('checkout.php', 'Failed to place order', 'error');
        }
    }
} else {
    header('Location: index.php');
}
?>