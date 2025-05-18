<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = mysqli_real_escape_string($conn, $_POST['Name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['PhoneNumber']);
    $address1 = mysqli_real_escape_string($conn, $_POST['Address1']);
    $address2 = mysqli_real_escape_string($conn, $_POST['Address2']);
    $address3 = mysqli_real_escape_string($conn, $_POST['Address3']);
    $address = $address1 . ', ' . $address3 . ', ' . $address2;
    $email = mysqli_real_escape_string($conn, $_POST['Email']);
    $password = trim($_POST['Password']);
    $password2 = trim($_POST['Password2']);

    // Validate password strength
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
        $_SESSION['EmailMessage'] = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
        header("Location: ../pages/register.php");
        exit;
    }

    // Validate password re-type
    if ($password !== $password2) {
        $_SESSION['EmailMessage'] = 'Passwords do not match.';
        header("Location: ../pages/register.php");
        exit;
    }

    try {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into customer table
        $stmt_insert = $conn->prepare(
            "INSERT INTO customer (name, phone_number, address, email, password)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt_insert->bind_param("sssss", $name, $phone_number, $address, $email, $hashed_password);

        // Success/fail message
        if ($stmt_insert->execute()) {
            $_SESSION['status'] = 'Your registration is successful.';
            header("Location: ../pages/register.php");
            exit;
        } else {
            $_SESSION['EmailMessage'] = 'Failed to create account.';
            header("Location: ../pages/register.php");
            exit;
        }
       
        $stmt_insert->close();
    } catch (Exception $e) {
        $_SESSION['EmailMessage'] = ' Error: ' . $e->getMessage();
        header("Location: ../pages/register.php");
        exit;
    }
}
$conn->close();
?>