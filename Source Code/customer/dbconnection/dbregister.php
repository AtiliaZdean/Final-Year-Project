<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['Name'];
    $phone_number = $_POST['PhoneNumber'];
    $address = $_POST['Address'];
    $city = $_POST['City'];
    $state = $_POST['State'];
    $email = $_POST['Email'];
    $password = trim($_POST['Password']);
    $password2 = trim($_POST['Password2']);

    // Validate password strength
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
        $_SESSION['EmailMessage'] = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
        header("Location: ../register.php");
        exit;
    }

    // Validate password re-type
    if ($password !== $password2) {
        $_SESSION['EmailMessage'] = 'Passwords do not match.';
        header("Location: ../register.php");
        exit;
    }

    try {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into CUSTOMER
        $stmt_insert = $conn->prepare(
            "INSERT INTO customer (name, phone_number, address, city, state, email, password)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt_insert->bind_param("sssssss", $name, $phone_number, $address, $city, $state, $email, $hashed_password);

        // Success/fail message
        if ($stmt_insert->execute()) {
            $_SESSION['status'] = 'Your registration is successful.';
            header("Location: ../register.php");
            exit;
        } else {
            $_SESSION['EmailMessage'] = 'Failed to create account.';
            header("Location: ../register.php");
            exit;
        }
       
        $stmt_insert->close();
    } catch (Exception $e) {
        $_SESSION['EmailMessage'] = ' Error: ' . $e->getMessage();
        header("Location: ../register.php");
        exit;
    }
}
$conn->close();
?>