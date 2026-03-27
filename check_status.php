<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

$booking_id = $_GET['booking_id'];

// 3NF Fix: JOIN the bookings and drivers tables to get the text details
$sql = "SELECT b.status, d.full_name AS driver_name, d.plate_number AS trike_plate 
        FROM bookings b 
        LEFT JOIN drivers d ON b.driver_id = d.driver_id 
        WHERE b.booking_id = '$booking_id'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['status' => 'error']);
}
?>