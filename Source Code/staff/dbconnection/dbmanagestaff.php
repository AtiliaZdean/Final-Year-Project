<?php
session_start();
include '../../dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['Name'];
    $phone_number = $_POST['PhoneNumber'];
    $branch = $_POST['Branch'];
    $role = $_POST['Role'];
    $made_by = $_SESSION['name'];

    if (isset($_POST['register'])) {
        $email = $_POST['Email'];
        $raw_password = $_POST['Password'];

        // Check if staff other than cleaner put email & password
        if ($role != 'cleaner') {
            if ($email === '' || $raw_password === '') {
                echo "<script>alert('Staffs other than cleaner must enter their email and password.');</script>";
                echo "<script>window.location.href = '../managestaff.php';</script>";
                exit();
            }
        }

        // Validate password strength on raw password
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $raw_password)) {
            echo "<script>alert('Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');</script>";
            echo "<script>window.location.href = '../managestaff.php';</script>";
            exit();
        }

        $password = password_hash($raw_password, PASSWORD_DEFAULT);

        // Call stored procedure for registration
        $conn->query("CALL ManageStaff('insert', 0, '$name', '$email', '$password', '$phone_number', '$branch', '$role', '', '$made_by', @result)");
        $result = $conn->query("SELECT @result AS result")->fetch_assoc();

        // Success/fail message
        if ($result['result'] == 1) {
            echo "<script>alert('The registration is successful.');</script>";
        } else {
            echo "<script>alert('Failed to register staff.');</script>";
        }
    } elseif (isset($_POST['update'])) {
        $id = $_POST['StaffId'];
        $status = $_POST['Status'];

        // Call stored procedure for update
        $conn->query("CALL ManageStaff('update', '$id', '$name', NULL, '', '$phone_number', '$branch', '$role', '$status', '$made_by', @result)");
        $result = $conn->query("SELECT @result AS result")->fetch_assoc();

        // Success/fail message
        if ($result['result'] == 1) {
            echo "<script>alert('The update is successful.');</script>";
        } else {
            echo "<script>alert('Failed to update staff.');</script>";
        }
    }

    echo "<script>window.location.href = '../managestaff.php';</script>";
    exit();
}
$conn->close();
