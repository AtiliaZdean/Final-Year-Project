<?php
session_start();
include '../../dbconnection.php';

// Retrieve form data
$email = $_POST['Email'];
$password = $_POST['Password'];

// Find the data of user
$sql = "SELECT staff_id, name, password FROM staff WHERE email = ?";
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
    // Bind result password
    $stmt_select->bind_result($staff_id, $name, $stored_password); // No password verification needed
    $stmt_select->fetch();

    // Check if the provided password matches the stored password
    if (password_verify($password, $stored_password)) {

        $_SESSION['loggedin'] = true;
        $_SESSION['staff_id'] = $staff_id;
        $_SESSION['name'] = $name;

        header("Location: ../dashboard.php"); // Redirect to volunteer page
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid Email or Password.";
        header("Location: ../login.php"); // Redirect to login page
        exit();
    }
}
$stmt_select->close();
$conn->close();
?>