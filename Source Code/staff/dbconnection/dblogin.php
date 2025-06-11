<?php
session_start();
include '../../dbconnection.php';

// Retrieve form data
$email = $_POST['Email'];
$password = $_POST['Password'];

// Find the data of user
$sql = "SELECT staff_id, name, password, branch, status FROM staff WHERE email = ?";
$stmt_select = $conn->prepare($sql);
$stmt_select->bind_param("s", $email);
$stmt_select->execute();
$stmt_select->store_result();

// Check if a matching row was found
if ($stmt_select->num_rows == 0) {
    // Email not found - call stored procedure to log failed attempt
    $conn->query("CALL ManageStaff('failed_login', 0, '', '$email', '', '', '', '', '', 'Unknown', @result)");
    $_SESSION['login_error'] = "Invalid Email or Password.";
    header("Location: ../login.php");
    exit();
} else {
    $stmt_select->bind_result($staff_id, $name, $stored_password, $branch, $status);
    $stmt_select->fetch();

    if (password_verify($password, $stored_password)) {
        if ($status === 'in-active') {
            // Unsuccessful login - call stored procedure to log the activity
            $conn->query("CALL ManageStaff('failed_login', '$staff_id', '', '$email', '', '', '', '', '$status', '$name', @result)");
            $_SESSION['login_error'] = "Your account is inactive. Please contact administrator.";
            header("Location: ../login.php");
            exit();
        }

        // Successful login - call stored procedure to log the activity
        $conn->query("CALL ManageStaff('login', $staff_id, '$name', '', '', '', '', '', '', '$name', @result)");

        $_SESSION['staff_id'] = $staff_id;
        $_SESSION['staffname'] = $name;
        $_SESSION['branch'] = $branch;

        header("Location: ../dashboard.php");
        exit();
    } else {
        // Unsuccessful login - call stored procedure to log the activity
        $conn->query("CALL ManageStaff('failed_login', '$staff_id', '', '$email', '', '', '', '', '', '$name', @result)");
        $_SESSION['login_error'] = "Invalid Email or Password.";
        header("Location: ../login.php");
        exit();
    }
}
$stmt_select->close();
$conn->close();
