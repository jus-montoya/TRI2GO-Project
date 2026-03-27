<?php
session_start();
include 'db_connect.php';

// Force PHP to actually show us what is crashing
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You are not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$lat = $_POST['lat'] ?? 0;
$lng = $_POST['lng'] ?? 0;
$dest_lat = $_POST['dest_lat'] ?? 0;
$dest_lng = $_POST['dest_lng'] ?? 0;
$pax = $_POST['pax'] ?? 1;
$price = $_POST['price'] ?? 20;

// The Try/Catch Safety Net
try {
    $sql = "INSERT INTO bookings (user_id, pickup_lat, pickup_lng, dest_lat, dest_lng, pax_count, total_price, status) 
            VALUES ('$user_id', '$lat', '$lng', '$dest_lat', '$dest_lng', '$pax', '$price', 'searching')";

    if ($conn->query($sql) === TRUE) {
        $booking_id = $conn->insert_id;
        echo json_encode(['status' => 'success', 'booking_id' => $booking_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Normal DB Error: ' . $conn->error]);
    }
} catch (Exception $e) {
    // If PHP 8.1 tries to crash, this catches it and sends the EXACT reason back to your screen!
    echo json_encode(['status' => 'error', 'message' => 'Fatal Crash Reason: ' . $e->getMessage()]);
}
?>