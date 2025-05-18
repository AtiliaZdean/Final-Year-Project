<?php
session_start();
include '../../dbconnection.php';

// Retrieve form data
$email = $_POST['Email'];
$password = $_POST['Password'];

// Find the data of user
$sql = "SELECT customer_id, name, password FROM customer WHERE email = ?";
$stmt_select = $conn->prepare($sql);
$stmt_select->bind_param("s", $email);
$stmt_select->execute();
$stmt_select->store_result();

// Check if a matching row was found
if ($stmt_select->num_rows == 0) {
    $_SESSION['login_error'] = "Invalid Email or Password.";
    header("Location: ../login.php");
    exit();
} else {
    $stmt_select->bind_result($customer_id, $name, $stored_password);
    $stmt_select->fetch();

    if (password_verify($password, $stored_password)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['customer_id'] = $customer_id;
        $_SESSION['name'] = $name;

        header("Location: ../home.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid Email or Password.";
        header("Location: ../login.php");
        exit();
    }
}
$stmt_select->close();
$conn->close();