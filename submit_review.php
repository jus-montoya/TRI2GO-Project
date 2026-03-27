<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment']; // This is optional

    // Ensure they haven't already reviewed this exact ride to prevent database errors
    $check = $conn->query("SELECT * FROM reviews WHERE booking_id = '$booking_id'");
    
    if ($check->num_rows == 0) {
        // Safe to insert into the 3NF reviews table!
        $sql = "INSERT INTO reviews (booking_id, rating, comment) VALUES ('$booking_id', '$rating', '$comment')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Thank you for rating your driver!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Database error. Please try again.'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('You already reviewed this ride!'); window.location.href='index.php';</script>";
    }
}
?>