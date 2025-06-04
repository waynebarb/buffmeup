<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include('connection.php');

$carId = $_SESSION['car_id'];

// Get the user ID
$query = "SELECT transaction_id, user_id, car_id, pick_up, drop_off, status, pickup_date, pickup_time, dropoff_date, dropoff_time, distance, fare
          FROM transactions
          WHERE car_id = :car_id
          ORDER BY transaction_id DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                            <h1 class="h4 text-gray-900 mb-4 text-center"></h1>
                            <?php if (!empty($transactions)): ?>
                                <h4 class="mb-4 text-center">Transactions for <span style="color:blue;"><?php echo strtoupper($_SESSION['username']); ?></span></h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Pick-Up</th>
                                                <th>Drop-Off</th>
                                                <th>Status</th>
                                                <th>Distance</th>
                                                <th>Fare</th>
                                                <th>Option</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($transactions as $tx): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($tx['transaction_id']) ?></td>
                                                    <td><?= htmlspecialchars($tx['user_id']) ?></td>
                                                    <td><?= htmlspecialchars($tx['pick_up']) ?></td>
                                                    <td><?= htmlspecialchars($tx['drop_off']) ?></td>
                                                    <td><?= htmlspecialchars($tx['status']) ?></td>
                                                    <td><?= htmlspecialchars($tx['distance']) ?>km</td>
                                                    <td>₱<?= number_format($tx['fare'], 2) ?></td>
                                                    <td>
                                                        <form action="driverTransaction.php" method="get">
                                                            <input type="hidden" name="transaction_id" value="<?= $tx['transaction_id'] ?>">
                                                            <button type="submit" class="btn btn-success btn-sm">View</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-center">No transactions found for this car.</p>
                            <?php endif; ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>