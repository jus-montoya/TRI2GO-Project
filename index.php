<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tri2Go - Passenger</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        #map-container {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
        }
    </style>
</head>
<body>

    <div id="screen-landing" class="screen active justify-center items-center p-6 text-center" style="background-color: #FFFFFF;">
        <div class="mb-12 flex flex-col items-center">
            <img src="logo.jpg" alt="Tri2Go Logo" class="w-56 h-auto mix-blend-multiply">
        </div>
        
        <div class="w-full max-w-xs space-y-4">
            <button onclick="window.location.href='driver_login.php'" class="w-full btn-green py-4 rounded-full font-bold text-lg shadow-md">Sign up as driver</button>
            <button onclick="switchScreen('screen-map')" class="w-full btn-green py-4 rounded-full font-bold text-lg shadow-md">Order a ride</button>
            <a href="history.php" class="block text-green-700 font-semibold mt-6">View History</a>
            <a href="login.php" class="block text-gray-400 text-sm mt-8">Logout</a>
        </div>
    </div>

    <div id="screen-map" class="screen relative hidden">
        <div class="absolute top-0 w-full z-20 bg-white shadow-sm p-4 rounded-b-3xl">
            <div class="flex items-center mb-4 relative">
                <button onclick="switchScreen('screen-landing')" class="absolute left-0 p-2 text-gray-700"><i class="fas fa-arrow-left text-xl"></i></button>
                <h2 class="text-xl font-bold flex-1 text-center">Book Trike</h2>
            </div>
            
            <div class="bg-white border-2 border-gray-100 rounded-2xl p-4 shadow-sm flex items-center relative">
                <div class="flex-1 space-y-3 text-left">
                    <div class="flex items-center">
                        <i class="fas fa-circle text-green-600 text-[10px] mr-4"></i>
                        <input type="text" id="from-input" placeholder="Loading map..." class="w-full outline-none text-sm font-medium text-gray-600 bg-transparent" readonly>
                    </div>
                    <div class="border-t border-gray-200 my-1 ml-8"></div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 text-lg mr-4"></i>
                        <input type="text" id="to-input" placeholder="Loading map..." class="w-full outline-none text-sm font-medium text-gray-600 bg-transparent" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div id="map-container">
            <div id="map" class="h-full w-full"></div>
        </div>

        <div class="absolute bottom-12 left-0 right-0 z-20 px-6 flex justify-center">
            <button onclick="showPaxModal()" class="btn-green px-12 py-4 rounded-full font-bold shadow-lg text-lg w-full max-w-sm">
                Buy this ticket
            </button>
        </div>
    </div>

    <div id="modal-pax" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex-col justify-end">
        <div class="bg-[#FFFCF2] w-full rounded-t-3xl p-6 flex flex-col">
            <div class="flex items-center mb-6 relative">
                <button onclick="hidePaxModal()" class="absolute left-0 p-2 text-gray-700"><i class="fas fa-arrow-left text-xl"></i></button>
                <h2 class="text-xl font-bold flex-1 text-center">Order Trike</h2>
            </div>

            <div class="ticket-card mb-8">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-motorcycle text-3xl mb-2 text-gray-800"></i>
                        <p class="text-xs font-bold text-gray-700" id="ticket-dist">0.0 km</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="flex items-center text-xs font-bold text-gray-700 mb-2">
                            <i class="far fa-clock mr-1"></i> <span id="ticket-time">0 min</span>
                        </div>
                        <i class="fas fa-map text-3xl text-gray-800"></i>
                    </div>
                </div>
                <div class="barcode-lines mb-1"></div>
            </div>

            <div class="text-center">
                <h3 class="text-lg font-bold mb-6">How many pax?</h3>
                <div class="flex justify-center items-center space-x-6 mb-8">
                    <button onclick="updatePax(-1)" class="w-12 h-12 rounded-full bg-[#E0D8B0] text-white text-2xl font-bold flex items-center justify-center shadow-sm">-</button>
                    <span id="pax-count" class="text-3xl font-bold text-green-700 w-12">1</span>
                    <button onclick="updatePax(1)" class="w-12 h-12 rounded-full bg-[#E0D8B0] text-white text-2xl font-bold flex items-center justify-center shadow-sm">+</button>
                </div>
                <h2 class="text-4xl font-bold text-gray-800 mb-10">₱<span id="price-display">20</span></h2>
            </div>

            <div class="flex space-x-4">
                <button onclick="startBooking()" class="flex-1 btn-green py-4 rounded-xl font-bold text-lg">Buy</button>
                <button onclick="hidePaxModal()" class="flex-1 border-2 border-gray-300 py-4 rounded-xl font-bold text-gray-500 text-lg">Cancel</button>
            </div>
        </div>
    </div>

    <div id="screen-searching" class="screen hidden relative">
        <div class="absolute top-0 w-full z-20 bg-white shadow-sm p-4 rounded-b-3xl">
             <div class="flex items-center mb-2 relative text-center">
                <h2 class="text-xl font-bold flex-1">Searching Trike</h2>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0 p-6 bg-[#FFFCF2] rounded-t-3xl shadow-lg z-20">
             <div class="ticket-card mb-6">
                <div class="flex justify-between items-start mb-8">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-motorcycle text-3xl mb-2 text-gray-800"></i>
                        <p class="text-xs font-bold text-gray-700">Tokwing</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="flex items-center text-xs font-bold text-gray-700 mb-2">
                            <i class="far fa-clock mr-1"></i> 10min
                        </div>
                        <i class="fas fa-map text-3xl text-gray-800"></i>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center py-4">
                    <div class="searching-pulse mb-4">
                        <i class="fas fa-circle text-green-500 text-xl"></i>
                    </div>
                    <p class="text-lg font-bold text-gray-700">Waiting for rider...</p>
                </div>
            </div>
        </div>
    </div>

    <div id="screen-waiting" class="screen hidden relative">
         <div class="absolute top-0 w-full z-20 bg-white shadow-sm p-4 rounded-b-3xl">
             <h2 class="text-xl font-bold text-center">Rider Found</h2>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0 p-6 bg-[#FFFCF2] rounded-t-3xl shadow-lg z-20">
             <p class="text-center font-bold text-lg mb-4">Your ride will arrive in 2 mins.</p>
             <div class="ticket-card mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-motorcycle text-3xl mb-2 text-gray-800"></i>
                        <p class="text-xs font-bold text-gray-700">Tokwing</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="flex items-center text-xs font-bold text-gray-700 mb-2">
                            <i class="far fa-clock mr-1"></i> 10min
                        </div>
                        <i class="fas fa-map text-3xl text-gray-800"></i>
                    </div>
                </div>
                <div class="border-t border-dashed border-gray-400 my-4"></div>
                <div class="flex justify-between items-end text-left">
                    <div>
                        <p class="font-bold text-sm">Rider: <span id="driver-name" class="font-normal">Loading...</span></p>
                        <p class="font-bold text-sm">Trike: <span id="trike-plate" class="font-normal">...</span></p>
                        <p class="font-bold text-sm">Rating: <span id="driver-rating" class="font-normal">...</span> stars</p>
                    </div>
                    <h2 class="text-3xl font-bold">₱<span id="final-price">20</span></h2>
                </div>
            </div>
            <button onclick="showSuccess()" class="w-full mt-6 py-3 text-white bg-blue-500 rounded-xl font-bold text-sm">Finish Ride</button>
        </div>
    </div>

    <div id="screen-success" class="screen hidden justify-center items-center p-6 text-center overflow-y-auto" style="padding-bottom: 100px;">
        <h2 class="text-2xl font-bold mb-4 mt-8">Success</h2>
        <div class="w-20 h-20 bg-[#6E8E6E] rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-check text-4xl text-white"></i></div>
        
        <div class="bg-[#F8F5E9] w-full rounded-3xl p-6 shadow-sm mb-6">
            <h3 class="text-lg font-bold text-[#4CAF83] mb-2">Ticket Successful</h3>
            <div class="border-t border-gray-200 pt-4 text-left text-sm space-y-2">
                <div class="flex justify-between"><span class="font-bold">Transaction:</span><span id="trans-id">123456</span></div>
                <div class="flex justify-between"><span class="text-gray-500">PAID</span><span class="font-bold">₱<span id="success-price">20</span></span></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-sm mx-auto border-t-4 border-green-500 text-left">
            <h3 class="text-xl font-bold text-gray-800 text-center mb-2">How was your driver?</h3>
            
            <form action="submit_review.php" method="POST" class="space-y-4">
                <input type="hidden" name="booking_id" id="review-booking-id" value="">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700">Rating</label>
                    <select name="rating" class="w-full border p-3 rounded-lg bg-gray-50 mt-1" required>
                        <option value="5">⭐⭐⭐⭐⭐ (5) - Excellent</option>
                        <option value="4">⭐⭐⭐⭐ (4) - Great</option>
                        <option value="3">⭐⭐⭐ (3) - Good</option>
                        <option value="2">⭐⭐ (2) - Needs Improvement</option>
                        <option value="1">⭐ (1) - Poor</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700">Comment (Optional)</label>
                    <textarea name="comment" placeholder="Very fast and safe ride!" class="w-full border p-3 rounded-lg bg-gray-50 mt-1 text-sm"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-bold transition">Submit Review</button>
            </form>
            
            <button onclick="switchScreen('screen-map')" class="w-full text-green-700 font-bold mt-4 text-center block text-sm">Skip & Order Again</button>
        </div>
    </div>
    
    <script>
        let currentScreen = 'screen-landing';
        let pax = 1;
        let totalPrice = 20;
        let map, fromMarker, toMarker;
        let bookingId = null;
        let checkInterval = null;
        let pollIntervalMs = 3000;

        function switchScreen(id) {
            document.querySelectorAll('.screen').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('active');
            });
            
            const target = document.getElementById(id);
            target.classList.remove('hidden');
            target.classList.add('active');
            currentScreen = id;

            if (id === 'screen-map' || id === 'screen-searching' || id === 'screen-waiting') {
                target.appendChild(document.getElementById('map-container'));
                target.style.backgroundColor = 'transparent'; 
                if (map) { setTimeout(() => map.invalidateSize(), 50); }
            } else {
                target.style.backgroundColor = ''; 
            }

            if (id === 'screen-map') {
                setTimeout(() => {
                    initMap();
                }, 300);
            } else {
                if (checkInterval) clearTimeout(checkInterval);
            }
        }

        function showPaxModal() {
            calculateRoute();
            document.getElementById('modal-pax').classList.remove('hidden');
            document.getElementById('modal-pax').classList.add('flex');
        }

        function hidePaxModal() {
            document.getElementById('modal-pax').classList.add('hidden');
            document.getElementById('modal-pax').classList.remove('flex');
        }

        function initMap() {
            if (map) { map.invalidateSize(); return; }

            map = L.map('map', {zoomControl: false}).setView([15.0691, 120.5413], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const greenIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                iconSize: [25, 41], iconAnchor: [12, 41]
            });
            const redIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                iconSize: [25, 41], iconAnchor: [12, 41]
            });

            fromMarker = L.marker([15.0691, 120.5390], {draggable: true, icon: greenIcon}).addTo(map);
            toMarker = L.marker([15.0691, 120.5430], {draggable: true, icon: redIcon}).addTo(map);

            fromMarker.on('drag', () => { updateInputs(); });
            toMarker.on('drag', () => { updateInputs(); });
            fromMarker.on('dragend', () => { calculateRoute(); });
            toMarker.on('dragend', () => { calculateRoute(); });

            updateInputs();
            setTimeout(() => { map.invalidateSize(); }, 100);
        }

        function updateInputs() {
            document.getElementById('from-input').value = fromMarker.getLatLng().lat.toFixed(4) + ", " + fromMarker.getLatLng().lng.toFixed(4);
            document.getElementById('to-input').value = toMarker.getLatLng().lat.toFixed(4) + ", " + toMarker.getLatLng().lng.toFixed(4);
        }

         
        async function calculateRoute() {
            if(!fromMarker || !toMarker) return;
            
            const lat1 = fromMarker.getLatLng().lat;
            const lng1 = fromMarker.getLatLng().lng;
            const lat2 = toMarker.getLatLng().lat;
            const lng2 = toMarker.getLatLng().lng;

            try {
                const res = await fetch(`https://router.project-osrm.org/route/v1/driving/${lng1},${lat1};${lng2},${lat2}?overview=false`);
                const data = await res.json();
                let distKm = data.routes[0].distance / 1000;
                
                let price = distKm <= 1 ? 20 : 20 + ((distKm - 1) * 15);
                totalPrice = Math.ceil(price) * pax;
                
                document.getElementById('ticket-dist').innerText = distKm.toFixed(1) + " km";
                document.getElementById('ticket-time').innerText = Math.ceil(distKm * 5) + " min";
                document.getElementById('price-display').innerText = totalPrice;
            } catch (err) {
                console.error("Routing failed, falling back to straight line", err);
                let distKm = (map.distance(fromMarker.getLatLng(), toMarker.getLatLng()) / 1000);
                let price = distKm <= 1 ? 20 : 20 + ((distKm - 1) * 15);
                totalPrice = Math.ceil(price) * pax;
                document.getElementById('ticket-dist').innerText = distKm.toFixed(1) + " km";
                document.getElementById('price-display').innerText = totalPrice;
            }
        }

        function updatePax(val) {
            pax = Math.max(1, Math.min(4, pax + val));
            document.getElementById('pax-count').innerText = pax;
            calculateRoute();
        }

        function startBooking() {
            hidePaxModal();
            switchScreen('screen-searching');
            const data = new FormData();
            data.append('lat', fromMarker.getLatLng().lat);
            data.append('lng', fromMarker.getLatLng().lng);
            data.append('dest_lat', toMarker.getLatLng().lat); 
            data.append('dest_lng', toMarker.getLatLng().lng);
            data.append('pax', pax);
            data.append('price', totalPrice);

            fetch('book_ride.php', { method: 'POST', body: data })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    bookingId = res.booking_id;
                    pollIntervalMs = 3000; 
                    checkInterval = setTimeout(monitorStatus, pollIntervalMs);
                } else {
                    alert(res.message);
                    switchScreen('screen-map');
                }
            });
        }

        
        function monitorStatus() {
            fetch('check_status.php?booking_id=' + bookingId)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'accepted') {
                    clearTimeout(checkInterval);
                    document.getElementById('driver-name').innerText = data.driver_name;
                    document.getElementById('trike-plate').innerText = data.trike_plate;
                    document.getElementById('driver-rating').innerText = data.driver_rating;
                    document.getElementById('final-price').innerText = totalPrice;
                    switchScreen('screen-waiting');
                } else {
                    
                    if (pollIntervalMs < 10000) {
                        pollIntervalMs += 1000; 
                    }
                    checkInterval = setTimeout(monitorStatus, pollIntervalMs);
                }
            });
        }

        function showSuccess() {
            document.getElementById('success-price').innerText = totalPrice;
            document.getElementById('trans-id').innerText = bookingId;
            
            document.getElementById('review-booking-id').value = bookingId; 
            switchScreen('screen-success');
        }
    </script>
</body>
</html>