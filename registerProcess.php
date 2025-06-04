<?php
// Include database connection
include('connection.php');

// Get form values
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$userName = $_POST['userName']; // Assuming this is the username (not email)
$password = $_POST['password'];
$contactNumber = $_POST['contactNumber'];  // New field
$role = 'user';  // Constant role value

// Check if username already exists
try {
    $checkUserName = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
    $checkUserName->bindParam(':username', $userName, PDO::PARAM_STR);
    $checkUserName->execute();

    if ($checkUserName->rowCount() > 0) {
        echo "<script>alert('Username already registered!'); window.history.back();</script>";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Hash password using bcrypt (password_hash)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
try {
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password, contact_number, role) 
                            VALUES (:firstName, :lastName, :userName, :password, :contactNumber, :role)");
    $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
    $stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':contactNumber', $contactNumber, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);  // Assign the constant role

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Try again later.'); window.history.back();</script>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null; // Close connection
?>
