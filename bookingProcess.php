<?php
// Include database connection
include('connection.php');

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
    exit;
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Get form data
$carId = $_POST['car_id'];
$pickUp = $_POST['pickupAddressHidden'];  // Pickup address
$dropOff = $_POST['dropOffAddressHidden'];  // Drop-off address
$distance = $_POST['calculatedDistance'];  // Distance
$fare = $_POST['calculatedFare'];  // Fare

// Debug: check if the form data is received properly
var_dump($carId, $pickUp, $dropOff, $distance, $fare);

// Prepare SQL query for insertion
try {
    $stmt = $conn->prepare("INSERT INTO transactions (car_id, user_id, pick_up, drop_off, status, distance, fare) 
                            VALUES (:car_id, :user_id, :pick_up, :drop_off, :status, :distance, :fare)");

    // Bind parameters
    $status = 'Pending';  // Status can be 'Pending' for now
    $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':pick_up', $pickUp, PDO::PARAM_STR);
    $stmt->bindParam(':drop_off', $dropOff, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':distance', $distance, PDO::PARAM_STR);
    $stmt->bindParam(':fare', $fare, PDO::PARAM_STR);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Booking successful!'); window.location.href='booking.php';</script>";
    } else {
        echo "<script>alert('Failed to book car.'); window.history.back();</script>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close connection
$conn = null;
?>
