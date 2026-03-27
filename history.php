<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Rides</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-[#FFFCF2] p-6">
    <div class="flex items-center mb-6">
        <a href="index.php" class="mr-4 text-gray-600"><i class="fas fa-arrow-left"></i></a>
        <h2 class="text-xl font-bold">My Ride History</h2>
    </div>

    <div class="space-y-4">
        <?php
        $uid = $_SESSION['user_id'];
        $sql = "SELECT * FROM bookings WHERE user_id = '$uid' ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $statusColor = 'text-yellow-600';
                if($row['status'] == 'completed') $statusColor = 'text-green-600';
                if($row['status'] == 'cancelled') $statusColor = 'text-red-600';

                echo "<div class='bg-white p-4 rounded-lg shadow border-l-4 border-green-500'>";
                echo "<div class='flex justify-between'>";
                echo "<span class='font-bold'>Ride #" . $row['booking_id'] . "</span>";
                echo "<span class='font-bold'>₱" . $row['total_price'] . "</span>";
                echo "</div>";
                echo "<p class='text-sm capitalize " . $statusColor . "'>" . $row['status'] . "</p>";
                echo "<p class='text-xs text-gray-500 mt-2'>" . date('M d, Y h:i A', strtotime($row['created_at'])) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p class='text-center text-gray-500 mt-10'>No ride history found.</p>";
        }
        ?>
    </div>
</body>
</html>