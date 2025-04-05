<?php
require_once('./config/function.php'); // Include your database connection file

if (isset($_GET['t'])) {
    $tracking_no = $_GET['t'];
    $order = getOrderDetails($tracking_no); // Function to fetch order details

    if ($order) {
        echo json_encode(['success' => true, 'order' => $order]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>