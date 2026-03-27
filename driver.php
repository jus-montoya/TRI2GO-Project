<?php
session_start();
include 'db_connect.php';

// STEP 2: The Approval (3NF Updated)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_ride'])) {
    $bid = $_POST['booking_id'];
    $driver_id = $_SESSION['driver_id']; // We ONLY need the driver's ID now
    
    // Update the booking to lock in this specific driver
    $sql = "UPDATE bookings SET status='accepted', driver_id='$driver_id' WHERE booking_id='$bid'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: driver.php?tab=map");
        exit();
    } else {
        echo "<script>alert('Error accepting ride.');</script>";
    }
}

// STEP 3: The Completion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complete_ride'])) {
    $bid = $_POST['booking_id'];
    
    $sql = "UPDATE bookings SET status='completed' WHERE booking_id='$bid'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: driver.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="style.css">
    <style>
        .queue-card { border: 2px solid #558B6E; border-radius: 12px; overflow: hidden; background: white; margin-bottom: 1rem; }
        .queue-header { background-color: #558B6E; color: white; padding: 8px 12px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
        .active-tab { color: #FCD34D !important; border-top: 4px solid #FCD34D; background: rgba(0,0,0,0.1); }
        .screen { display: none; height: 100%; flex-direction: column; }
        .screen.active { display: flex; }
    </style>
</head>
<body class="bg-gray-100 h-screen flex flex-col overflow-hidden">

    <div id="screen-map" class="screen <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'map') ? 'active' : ''; ?>">
        <div class="bg-white p-4 shadow-md z-10 text-center font-bold">Active Ride</div>
        <div id="map" class="flex-1 z-0"></div>
        
        <?php
        // 3NF FIX: We search by driver_id now, not driver_name!
        $d_id = $_SESSION['driver_id'];
        $active_sql = "SELECT * FROM bookings WHERE driver_id='$d_id' AND status='accepted' LIMIT 1";
        $active_res = $conn->query($active_sql);
        
        if ($active_res->num_rows > 0) {
            $ride = $active_res->fetch_assoc();
            echo '<div class="absolute bottom-24 left-4 right-4 bg-white p-4 rounded-xl shadow-lg z-20 border-l-4 border-green-500">';
            echo '<div class="flex justify-between items-center mb-2">';
            echo '<h3 class="font-bold text-lg text-green-700">Current Passenger</h3>';
            echo '<span class="font-bold text-xl">₱'.$ride['total_price'].'</span>';
            echo '</div>';
            echo '<p class="text-sm text-gray-600 mb-4"><i class="fas fa-users"></i> '.$ride['pax_count'].' Pax</p>';
            echo '<form method="post">';
            echo '<input type="hidden" name="booking_id" value="'.$ride['booking_id'].'">';
            // Also fixed the button name here to match $_POST['complete_ride'] at the top of the file
            echo '<button type="submit" name="complete_ride" class="w-full bg-red-500 text-white font-bold py-3 rounded-lg shadow hover:bg-red-600 transition">COMPLETE RIDE</button>';
            echo '</form>';
            echo '</div>';
            
            echo "<script>window.onload = function() { initMap(".$ride['pickup_lat'].", ".$ride['pickup_lng']."); }</script>";
        } else {
            echo "<script>window.onload = function() { initMap(15.0700, 120.5400); }</script>";
        }
        ?>
    </div>

    <div id="screen-booking" class="screen <?php echo (!isset($_GET['tab']) || $_GET['tab'] != 'map') ? 'active' : ''; ?>">
        <div class="p-4 bg-white shadow-sm z-10">
            <h1 class="text-xl font-bold text-gray-700">Passenger Queue</h1>
        </div>
        <div id="queue-container" class="flex-1 p-4 overflow-y-auto pb-24">
            <div class="text-center mt-10"><i class="fas fa-circle-notch fa-spin text-green-600"></i> Loading...</div>
        </div>
    </div>

    <div id="screen-profile" class="screen justify-center items-center">
        <div class="w-32 h-32 bg-gray-300 rounded-full mb-4 flex items-center justify-center shadow-lg">
            <i class="fas fa-user text-5xl text-gray-600"></i>
        </div>
        <h2 class="text-2xl font-bold"><?php echo $_SESSION['driver_name']; ?></h2>
        <p class="text-gray-500 mb-8"><?php echo $_SESSION['driver_plate']; ?></p>
        <a href="driver_login.php" class="bg-red-500 text-white px-10 py-3 rounded-full font-bold shadow hover:bg-red-600 transition">LOGOUT</a>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-[#4CAF83] h-20 flex justify-around items-center text-white font-bold text-[10px] uppercase shadow-2xl z-50">
        <button onclick="changeTab('map')" id="tab-map" class="flex flex-col items-center h-full justify-center w-full transition <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'map') ? 'active-tab' : ''; ?>">
            <i class="fas fa-map text-2xl mb-1"></i>Map
        </button>
        <button onclick="changeTab('booking')" id="tab-booking" class="flex flex-col items-center h-full justify-center w-full border-l border-r border-green-600 transition <?php echo (!isset($_GET['tab']) || $_GET['tab'] != 'map') ? 'active-tab' : ''; ?>">
            <i class="fas fa-clipboard-list text-2xl mb-1"></i>Booking
        </button>
        <button onclick="changeTab('profile')" id="tab-profile" class="flex flex-col items-center h-full justify-center w-full transition">
            <i class="fas fa-user-circle text-2xl mb-1"></i>Profile
        </button>
    </div>

    <script>
        function changeTab(tabName) {
            document.querySelectorAll('.screen').forEach(screen => screen.classList.remove('active'));
            document.querySelectorAll('button[id^="tab-"]').forEach(btn => btn.classList.remove('active-tab'));

            if(tabName === 'map') { 
                document.getElementById('screen-map').classList.add('active'); 
                document.getElementById('tab-map').classList.add('active-tab');
                if(typeof map !== 'undefined') {
                    setTimeout(() => { map.invalidateSize(); }, 200);
                }
            } else if(tabName === 'booking') {
                document.getElementById('screen-booking').classList.add('active'); 
                document.getElementById('tab-booking').classList.add('active-tab');
                
                updateQueue();
            } else {
                document.getElementById('screen-profile').classList.add('active'); 
                document.getElementById('tab-profile').classList.add('active-tab');
            }
        }

        let map;
        function initMap(lat, lng) {
            if (map) return;
            const defaultLat = 15.0700;
            const defaultLng = 120.5400;
            const finalLat = lat || defaultLat;
            const finalLng = lng || defaultLng;

            map = L.map('map', {zoomControl: false}).setView([finalLat, finalLng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            
            if (lat && lng) {
                const paxIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    iconSize: [25, 41], iconAnchor: [12, 41]
                });
                L.marker([lat, lng], {icon: paxIcon}).addTo(map)
                 .bindPopup("Passenger is Here")
                 .openPopup();
            }
        }

        
        let queueTimer;
        
        function updateQueue() {
            const bookingTab = document.getElementById('screen-booking');
            if (bookingTab.classList.contains('active')) {
                fetch('fetch_queue.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('queue-container').innerHTML = html;
                    
                    queueTimer = setTimeout(updateQueue, 3000); 
                })
                .catch(err => console.error(err));
            } else {
                
                queueTimer = setTimeout(updateQueue, 10000);
            }
        }

        if (!map) { initMap(15.0700, 120.5400); }
        
        
        updateQueue();
    </script>
</body>
</html>