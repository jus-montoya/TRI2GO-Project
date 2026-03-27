<?php
include 'db_connect.php';

$sql = "SELECT * FROM bookings WHERE status='searching' ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="queue-card">
            <div class="queue-header">
                <span>ORDER NO. '.$row['booking_id'].'</span>
                <span class="bg-white text-green-700 px-2 rounded text-xs">₱'.$row['total_price'].'</span>
            </div>
            <div class="p-4">
                <div class="mb-2">
                    <p class="text-xs font-bold text-gray-500">FROM:</p>
                    <p class="font-bold text-gray-800">Pin Location</p>
                </div>
                <div class="mb-4">
                    <p class="text-xs font-bold text-gray-500">TO:</p>
                    <p class="font-bold text-gray-800">Destination Area</p>
                </div>
                <form action="driver.php" method="post">
                    <input type="hidden" name="booking_id" value="'.$row['booking_id'].'">
                    <button type="submit" name="accept_ride" class="w-full bg-[#FCD34D] text-black font-bold py-3 rounded-full shadow-md hover:bg-yellow-400 transition">
                        TAKE ORDER
                    </button>
                </form>
            </div>
        </div>';
    }
} else {
    echo '<div class="text-center text-gray-400 mt-10">
        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
        <p>Waiting for passengers...</p>
    </div>';
}
?>