<?php
// Include database connection
include('connection.php');

// Start the session to get user_id from session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form values and convert to uppercase
    $model = strtoupper($_POST['model']);
    $plateNumber = strtoupper($_POST['plate_number']);
    $driver = strtoupper($_POST['driver']);
    $contactNumber = strtoupper($_POST['contact_number']);
    $userId = $_SESSION['user_id']; // Get user_id from session

    // Check if car_id is provided (indicates editing an existing car)
    $carId = isset($_POST['car_id']) ? $_POST['car_id'] : null;

    try {
        if ($carId) {
            // Editing existing car
            // Update the car details
            $stmt = $conn->prepare("UPDATE cars SET model = :model, plate_number = :plate_number, driver = :driver, contact_number = :contact_number 
                                    WHERE car_id = :car_id AND user_id = :user_id");

            $stmt->bindParam(':model', $model);
            $stmt->bindParam(':plate_number', $plateNumber);
            $stmt->bindParam(':driver', $driver);
            $stmt->bindParam(':contact_number', $contactNumber);
            $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "<script>alert('Car details updated successfully!'); window.location.href='register-car.php';</script>";
            } else {
                echo "<script>alert('Failed to update car details.'); window.history.back();</script>";
            }
        } else {
            // New car registration
            // Insert into the cars table, including user_id and the current server date/time
            $stmt = $conn->prepare("INSERT INTO cars (model, plate_number, driver, contact_number, user_id, date) 
                                    VALUES (:model, :plate_number, :driver, :contact_number, :user_id, NOW())");

            $stmt->bindParam(':model', $model, PDO::PARAM_STR);
            $stmt->bindParam(':plate_number', $plateNumber, PDO::PARAM_STR);
            $stmt->bindParam(':driver', $driver, PDO::PARAM_STR);
            $stmt->bindParam(':contact_number', $contactNumber, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $carId = $conn->lastInsertId();
                // Now, insert driver into users table
                // Generate unique username based on driver's name (e.g., first name)
                $username = strtolower(str_replace(' ', '', $driver)) . rand(100, 999);

                // Generate an 8-character random password
                $password = bin2hex(random_bytes(4));  // 4 bytes = 8 hex characters

                // Encrypt the password
                $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert into the users table
                $userStmt = $conn->prepare("INSERT INTO users (username, password, first_name, role, car_id,plain_password) 
                                    VALUES (:username, :password, :first_name, 'driver', :car_id, :plain_password)");
                $userStmt->bindParam(':username', $username, PDO::PARAM_STR);
                $userStmt->bindParam(':password', $encryptedPassword, PDO::PARAM_STR);
                $userStmt->bindParam(':first_name', $driver, PDO::PARAM_STR);
                $userStmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
                 $userStmt->bindParam(':plain_password', $password, PDO::PARAM_STR);

                if ($userStmt->execute()) {
                    // Display password to the user (for admin or authorized view)
                    echo "<script>alert('Car registered and driver created successfully! Username: $username, Password: $password'); window.location.href='register-car.php';</script>";
                } else {
                    echo "<script>alert('Failed to create driver.'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Failed to register car.'); window.history.back();</script>";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $conn = null;
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
