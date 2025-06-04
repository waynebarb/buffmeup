<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('connection.php');

if (!isset($_GET['transaction_id'])) {
    die("Transaction ID is missing.");
}

$transactionId = $_GET['transaction_id'];

// Fetch transaction details
$query = "SELECT * FROM transactions WHERE transaction_id = :transaction_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);
$stmt->execute();
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    die("Transaction not found.");
}

// Set variables
$pickup = $transaction['pick_up'];
$dropoff = $transaction['drop_off'];
$status = $transaction['status'];  // Get the status of the transaction
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Driver Transaction</title>
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
                            <h4 class="mb-4 text-center">Transaction ID: <?= htmlspecialchars($transactionId) ?></h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Pick-Up Location:</strong></label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($pickup) ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Drop-Off Location:</strong></label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($dropoff) ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Status:</strong></label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($status) ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Fare:</strong></label>
                                        <input type="text" class="form-control" value="₱<?= number_format($transaction['fare'], 2) ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="map" style="height: 300px;"></div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <a href="driver.php" class="btn btn-secondary">Back to Transactions</a>

                                <?php if ($status !== 'Completed'): ?>
                                    <?php if (empty($transaction['pickup_date']) || empty($transaction['pickup_time'])): ?>
                                        <form action="driverTransactionProcess.php" method="POST" class="d-inline">
                                            <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transactionId) ?>">
                                            <button type="submit" class="btn btn-success">Pick Up</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="driverTransactionDropOffProcess.php" method="POST" class="d-inline">
                                            <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transactionId) ?>">
                                            <button type="submit" class="btn btn-success">Drop Passenger</button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Leaflet Map Script -->
    <script>
        const pickup = "<?= addslashes($pickup) ?>";
        const dropoff = "<?= addslashes($dropoff) ?>";

        const map = L.map('map').setView([10.3157, 123.8854], 13); // Cebu default

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18
        }).addTo(map);

        async function geocode(address) {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`);
            const data = await res.json();
            return data.length ? [parseFloat(data[0].lat), parseFloat(data[0].lon)] : null;
        }

        (async () => {
            const pickupCoords = await geocode(pickup);
            const dropoffCoords = await geocode(dropoff);

            if (pickupCoords) {
                L.marker(pickupCoords).addTo(map).bindPopup("Pick-Up").openPopup();
            }
            if (dropoffCoords) {
                L.marker(dropoffCoords).addTo(map).bindPopup("Drop-Off");
            }

            if (pickupCoords && dropoffCoords) {
                const bounds = L.latLngBounds([pickupCoords, dropoffCoords]);
                map.fitBounds(bounds);
            }
        })();
    </script>
</body>

</html>
