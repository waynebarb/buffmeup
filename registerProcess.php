<?php
// registerProcess.php

include('connection.php'); // $conn is your PDO instance
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// START the transaction
$conn->beginTransaction();

try {
    // Collect POST data
    $userType      = $_POST['userType'] ?? 'user';
    $firstName     = $_POST['firstName'];
    $lastName      = $_POST['lastName'];
    $contactNumber = $_POST['contact_number'];
    $userName      = $_POST['userName'];
    $passwordHash  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $plainPassword = $_POST['password'];

    $carId = null;

    // 1. If Driver, insert into cars first
    if ($userType === 'driver') {
        $stmt = $conn->prepare("
            INSERT INTO cars 
            (plate_number, model, driver, contact_number, user_id, date)
            VALUES 
            (:plate, :model, :driver, :contact, NULL, CURDATE())
        ");

        $stmt->execute([
            ':plate'   => $_POST['plate_number'],
            ':model'   => $_POST['model'],
            ':driver'  => $firstName . ' ' . $lastName,
            ':contact' => $_POST['contact_number'],
        ]);

        // 2. Get the newly created car_id
        $carId = $conn->lastInsertId(); // fetch the auto-generated ID
    }

    // 3. Insert into users
    $stmt = $conn->prepare("
        INSERT INTO users 
        (first_name, last_name, contact_number, username, password, role, car_id, plain_password)
        VALUES 
        (:first, :last, :contact, :username, :password, :role, :car_id, :plain_password)
    ");

    $stmt->execute([
        ':first'           => $firstName,
        ':last'            => $lastName,
        ':contact'         => $contactNumber,
        ':username'        => $userName,
        ':password'        => $passwordHash,
        ':role'            => $userType,
        ':car_id'          => $carId,
        ':plain_password'  => $plainPassword
    ]);

    // 4. Commit the transaction
    $conn->commit();

    header('Location: login.php');
    echo "Registration successful!";

} catch (PDOException $e) {
    // If anything fails, roll everything back
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo "Error: " . htmlspecialchars($e->getMessage());
}
