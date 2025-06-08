<?php
include('connection.php'); // $conn is your PDO instance
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = (int)$_POST['user_id']; // sanitize input

    try {
        $stmt = $conn->prepare("
            UPDATE users
            SET driver_status = :status
            WHERE user_id = :uid
        ");
        $stmt->execute([
            ':status' => 'accepted',
            ':uid'    => $userId
        ]);

        if ($stmt->rowCount()) {
            echo "Driver status updated to 'accepted'.";
              header('Location: ' . "drivers.php");
        } else {
            echo "No changes made. Are you sure this user exists or wasn't already accepted?";
        }

      
    } catch (PDOException $e) {
        echo "Error updating status: " . htmlspecialchars($e->getMessage());
    }
}
