<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 3NF Fix: Grabbing the integer IDs from the new dropdowns
    $toda_id = $_POST['toda_id']; 
    $brand_id = $_POST['brand_id'];
    
    // Matched these to your original HTML name attributes!
    $name = $_POST['full_name']; 
    $phone = $_POST['phone'];
    $plate = $_POST['plate'];
    
    // Steven's Security Feature
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Insert into the 3NF drivers table
    $sql = "INSERT INTO drivers (toda_id, brand_id, full_name, phone_number, plate_number, password) 
            VALUES ('$toda_id', '$brand_id', '$name', '$phone', '$plate', '$password')";
            
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Driver Registration Successful!'); window.location.href='driver_login.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register. " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100">

    <div class="bg-white max-w-md mx-auto min-h-screen flex flex-col relative shadow-xl">
        <div class="p-4 flex items-center border-b border-gray-200">
            <a href="driver_login.php" class="text-gray-700 mr-4"><i class="fas fa-arrow-left text-xl"></i></a>
            <h1 class="text-xl font-bold text-gray-800 flex-1 text-center">Rider Registration</h1>
        </div>

        <form method="post" class="p-6 space-y-4 pb-24">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="full_name" placeholder="Surname, First name Middle Name" class="w-full border border-gray-400 p-2 rounded" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Address</label>
                <input type="text" name="address" placeholder="Block, Bldg, Street, Municipality" class="w-full border border-gray-400 p-2 rounded" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Date of Birth</label>
                    <input type="date" class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Civil Status</label>
                    <select class="w-full border border-gray-400 p-2 rounded">
                        <option>Single</option>
                        <option>Married</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Contact Number</label>
                    <input type="text" name="phone" placeholder="09xxxxxxxxx" class="w-full border border-gray-400 p-2 rounded" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" placeholder="Create Password" class="w-full border border-gray-400 p-2 rounded" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Driver License Number</label>
                <input type="text" name="license" placeholder="License Number" class="w-full border border-gray-400 p-2 rounded" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Motorcycle Brand</label>
                    <select name="brand_id" class="w-full border border-gray-400 p-2 rounded" required>
                        <option value="" disabled selected>Select Brand</option>
                        <option value="1">Honda</option>
                        <option value="2">Yamaha</option>
                        <option value="3">Kawasaki</option>
                        <option value="4">Suzuki</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Plate Number</label>
                    <input type="text" name="plate" placeholder="ABC 123" class="w-full border border-gray-400 p-2 rounded" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">TODA Name</label>
                <select name="toda_id" class="w-full border border-gray-400 p-2 rounded" required>
                    <option value="" disabled selected>Select TODA</option>
                    <option value="1">Tokwing</option>
                    <option value="2">Cutcut</option>
                    <option value="3">Lourdes Sur East</option>
                </select>
            </div>

            <div class="border-t border-gray-300 pt-4">
                <p class="text-sm font-bold mb-2">Upload Requirements</p>
                <div class="flex space-x-4">
                    <button type="button" class="border-2 border-gray-400 rounded-lg p-4 flex flex-col items-center justify-center w-24 h-24">
                        <i class="fas fa-upload text-2xl mb-1"></i>
                        <span class="text-[10px]">LTO OR/CR</span>
                    </button>
                     <button type="button" class="border-2 border-gray-400 rounded-lg p-4 flex flex-col items-center justify-center w-24 h-24">
                        <i class="fas fa-upload text-2xl mb-1"></i>
                        <span class="text-[10px]">License</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#FCD34D] text-black font-bold py-3 rounded-full border-2 border-black shadow-md mt-6">
                SUBMIT REGISTRATION
            </button>
        </form>
    </div>
</body>
</html>