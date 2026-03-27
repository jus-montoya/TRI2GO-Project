<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $phone = $_POST['phone'];
        $name = $_POST['name'];
       
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
        
        $sql = "INSERT INTO users (phone_number, full_name, password) VALUES ('$phone', '$name', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registered! Please login.');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
    
    if (isset($_POST['login'])) {
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM users WHERE phone_number='$phone'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['name'] = $row['full_name'];
                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('Incorrect password');</script>";
            }
        } else {
            echo "<script>alert('User not found');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tri2Go Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FFFCF2] h-screen flex justify-center items-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-80">
        <h2 class="text-2xl font-bold text-center mb-6">Login to Tri2Go</h2>
        
        <form method="post" class="space-y-4">
            <input type="text" name="phone" placeholder="Phone Number" class="w-full border p-2 rounded" required>
            <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded" required>
            <button type="submit" name="login" class="w-full bg-green-600 text-white p-2 rounded hover:bg-green-700">Login</button>
        </form>
        
        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="flex-shrink-0 mx-4 text-gray-500 text-sm">OR</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>
        
        <h3 class="text-lg font-bold text-center mb-2">Register</h3>
        <form method="post" class="space-y-4">
            <input type="text" name="name" placeholder="Full Name" class="w-full border p-2 rounded" required>
            <input type="text" name="phone" placeholder="Phone Number" class="w-full border p-2 rounded" required>
            <input type="password" name="password" placeholder="Create Password" class="w-full border p-2 rounded" required>
            <button type="submit" name="register" class="w-full bg-yellow-400 text-black p-2 rounded hover:bg-yellow-500">Sign Up</button>
        </form>
    </div>
</body>
</html>