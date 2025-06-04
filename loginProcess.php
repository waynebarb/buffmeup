<?php
// Include database connection
include('connection.php');

// Start the session
session_start();

// Get login form values
$username = $_POST['username'];
$password = $_POST['password'];

try {
    // Query full user info
    $stmt = $conn->prepare("SELECT user_id, username, password, role, first_name, last_name, contact_number,car_id FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['contact_number'] = $user['contact_number'];
            $_SESSION['car_id'] = $user['car_id'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                echo "<script>window.location.href='dashboard.php';</script>";
            } elseif ($user['role'] === 'driver') {
                echo "<script>window.location.href='driver.php';</script>";
            } else {
                echo "<script>window.location.href='booking.php';</script>";
            }
        } else {
            echo "<script>alert('Incorrect password. Please try again.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Username not found. Please try again.'); window.history.back();</script>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
