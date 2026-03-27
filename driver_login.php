<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM drivers WHERE phone_number='$phone'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['driver_id'] = $row['driver_id'];
            $_SESSION['driver_name'] = $row['full_name'];
            $_SESSION['driver_plate'] = $row['plate_number'];
            header("Location: driver.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('Driver not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-800 h-screen flex justify-center items-center font-sans">
    <div class="bg-white p-8 rounded-xl shadow-lg w-80">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Driver Login</h2>
        <form method="post" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-gray-700">Phone Number</label>
                <input type="text" name="phone" placeholder="09xxxxxxxxx" class="w-full border p-3 rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700">Password</label>
                <input type="password" name="password" placeholder="Password" class="w-full border p-3 rounded-lg" required>
            </div>
            <button type="submit" class="w-full btn-green py-3 rounded-lg font-bold">Login</button>
        </form>
        <div class="mt-4 text-center">
            <a href="driver_register.php" class="text-sm text-blue-600 underline">Register as new driver</a>
        </div>
    </div>
</body>
</html>