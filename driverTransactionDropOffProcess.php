<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('connection.php');

// Check if transaction_id is set
if (!isset($_POST['transaction_id'])) {
    die("Transaction ID is missing.");
}

$transactionId = $_POST['transaction_id'];

// Prepare the SQL query to update the transaction
$query = "UPDATE transactions 
          SET dropoff_date = CURDATE(), dropoff_time = CURTIME(), status = 'Completed' 
          WHERE transaction_id = :transaction_id";
$stmt = $conn->prepare($query);

// Bind the transaction_id parameter
$stmt->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);

// Execute the update
if ($stmt->execute()) {
    // Redirect back to the driver page with a success message
    header("Location: driver.php?message=Transaction Completed Successfully");
} else {
    // Handle the error if the update fails
    echo "Error updating the transaction.";
}
