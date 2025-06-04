<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include('connection.php');

// Get the user ID
$userId = $_SESSION['user_id'];

// Query to check the user's most recent transaction status
$query = "SELECT status FROM transactions WHERE user_id = :user_id ORDER BY transaction_id DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the user has a pending booking
$isPending = ($transaction && $transaction['status'] === 'Pending');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Booking - Select Your Drop-off Location</title>
    <!-- Leaflet CSS for map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Bootstrap and Custom styles -->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Booking System</a>
            <div class="ml-auto">
                <form action="logout.php" method="post">
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-5">
                            <h1 class="h4 text-gray-900 mb-4 text-center">
                               
                                <h1 class="h4 text-gray-900 mb-4 text-center">
                                    <?php if ($isPending): ?>
                                        <?php
                                        // Get pickup date and time
                                        $pickupDateQuery = "SELECT pickup_date, pickup_time FROM transactions WHERE user_id = :user_id ORDER BY transaction_id DESC LIMIT 1";
                                        $pickupStmt = $conn->prepare($pickupDateQuery);
                                        $pickupStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                                        $pickupStmt->execute();
                                        $pickupInfo = $pickupStmt->fetch(PDO::FETCH_ASSOC);

                                        $pickupStarted = !empty($pickupInfo['pickup_date']) && !empty($pickupInfo['pickup_time']);
                                        ?>

                                        <?= $pickupStarted ? 'Your trip is currently in progress. Please stay safe and be patient.' : 'Your driver is on the way!' ?>

                                        <img src="assets/img/taxi.gif" alt="Description of the GIF" style="width: 50%;">

                                    <?php else: ?>
                                        Select Your Drop-off Location
                                    <?php endif; ?>
                                </h1>




                            </h1>

                            <?php if (!$isPending): ?>
                                <!-- Map Container -->
                                <div id="map" style="height: 500px; width: 100%;"></div>

                                <br>

                                <div class="form-group">
                                    <label for="pickupAddress">Pickup Address:</label>
                                    <input type="text" class="form-control" id="pickupAddress" name="pickupAddress" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="dropOffAddress">Drop-off Address:</label>
                                    <input type="text" class="form-control" id="dropOffAddress" name="dropOffAddress" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="distance">Distance (in kilometers):</label>
                                    <input type="text" class="form-control" id="distance" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="fare">Total Fare (PHP):</label>
                                    <input type="text" class="form-control" id="fare" disabled>
                                </div>

                                <form method="post" action="bookingProcess.php">
                                    <div class="form-group">
                                        <label for="carSelection">Select Available Car:</label>
                                        <select class="form-control" id="carSelection" name="car_id" required>
                                            <option value="">-- Select Car --</option>
                                            <?php
                                            // Fetch available cars from the database
                                            $query = "SELECT car_id, model, plate_number FROM cars WHERE status IS NULL";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            // Loop through the available cars and create an option for each
                                            foreach ($cars as $car) {
                                                echo "<option value='" . $car['car_id'] . "'>" . $car['model'] . " (" . $car['plate_number'] . ")</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Hidden inputs for coordinates and calculated values -->
                                    <input type="hidden" id="dropOffLatitude" name="dropOffLatitude">
                                    <input type="hidden" id="dropOffLongitude" name="dropOffLongitude">
                                    <input type="hidden" id="pickupLatitude" name="pickupLatitude">
                                    <input type="hidden" id="pickupLongitude" name="pickupLongitude">
                                    <input type="hidden" id="pickupAddressHidden" name="pickupAddressHidden">
                                    <input type="hidden" id="dropOffAddressHidden" name="dropOffAddressHidden">
                                    <input type="hidden" id="calculatedDistance" name="calculatedDistance">
                                    <input type="hidden" id="calculatedFare" name="calculatedFare">

                                    <button type="submit" class="btn btn-primary btn-user btn-block">Book Driver</button>
                                </form>
                            <?php else: ?>
                                <!-- <p class="text-center">Your booking is pending. Please wait for your driver to arrive.</p> -->
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Map initialization and handling for pickup and drop-off locations (only if the booking is not pending)
        <?php if (!$isPending): ?>
            var map = L.map('map').setView([14.5995, 120.9842], 13);
            var userMarker, destinationMarker, userLatLng;

            // Tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Reverse geocode function
            function reverseGeocode(lat, lng, callback) {
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            callback(data.display_name);
                        } else {
                            callback("Address not found");
                        }
                    })
                    .catch(() => {
                        callback("Failed to retrieve address");
                    });
            }

            // Get user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    userLatLng = [position.coords.latitude, position.coords.longitude];

                    map.setView(userLatLng, 15);

                    userMarker = L.marker(userLatLng).addTo(map)
                        .bindPopup("You are here").openPopup();

                    // Set pickup lat/lng
                    document.getElementById('pickupLatitude').value = userLatLng[0];
                    document.getElementById('pickupLongitude').value = userLatLng[1];

                    reverseGeocode(userLatLng[0], userLatLng[1], function(address) {
                        document.getElementById('pickupAddress').value = address;
                        document.getElementById('pickupAddressHidden').value = address;
                    });
                }, function() {
                    alert("Failed to get your location.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }

            // Click to select drop-off
            map.on('click', function(e) {
                var latlng = e.latlng;

                if (destinationMarker) {
                    destinationMarker.setLatLng(latlng);
                } else {
                    destinationMarker = L.marker(latlng).addTo(map)
                        .bindPopup("Drop-off point").openPopup();
                }

                document.getElementById('dropOffLatitude').value = latlng.lat;
                document.getElementById('dropOffLongitude').value = latlng.lng;

                reverseGeocode(latlng.lat, latlng.lng, function(address) {
                    document.getElementById('dropOffAddress').value = address;
                    document.getElementById('dropOffAddressHidden').value = address;
                });

                // Calculate distance and fare
                var distance = map.distance(userLatLng, latlng) / 1000;
                var fare = (distance * 60).toFixed(2); // ₱60/km

                document.getElementById('distance').value = distance.toFixed(2) + " km";
                document.getElementById('fare').value = "PHP " + fare;
                document.getElementById('calculatedDistance').value = distance.toFixed(2);
                document.getElementById('calculatedFare').value = fare;
            });
        <?php endif; ?>
    </script>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>