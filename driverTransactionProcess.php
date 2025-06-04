<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id'])) {
    $transactionId = $_POST['transaction_id'];

    try {
        $stmt = $conn->prepare("
            UPDATE transactions 
            SET pickup_date = CURDATE(), 
                pickup_time = CURTIME() 
            WHERE transaction_id = :transaction_id
        ");
        $stmt->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);
        $stmt->execute();

        echo "<script>alert('Pickup time has been recorded.'); window.location.href='driver.php';</script>";
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }

    $conn = null;
} else {
    echo "<script>alert('Invalid request.'); window.location.href='driver.php';</script>";
}
?>
