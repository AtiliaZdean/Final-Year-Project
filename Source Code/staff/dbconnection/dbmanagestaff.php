<?php
session_start();
include '../../dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['Name'];
    $phone_number = $_POST['PhoneNumber'];
    $made_by = $_SESSION['staffname'];

    if (isset($_POST['register'])) {
        $email = $_POST['Email'];
        $raw_password = $_POST['Password'];
        $role = $_POST['Role1'];
        $branch = $_POST['Branch1'];

        // Check if staff other than cleaner put email & password
        if ($role != 'Cleaner') {
            if ($email === '' || $raw_password === '') {
                echo "<script>alert('Staffs other than cleaner must enter their email and password.');</script>";
                echo "<script>window.location.href = '../managestaff.php';</script>";
                exit();
            }

            // Validate password strength on raw password
            if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $raw_password)) {
                echo "<script>alert('Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');</script>";
                echo "<script>window.location.href = '../managestaff.php';</script>";
                exit();
            }
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
        $status = $_POST['StatusModal'];
        $branch = $_POST['Branch2'];

        // First check if cleaner is being assigned in any PENDING bookings
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM BOOKING_CLEANER bc JOIN BOOKING b ON bc.booking_id = b.booking_id WHERE bc.staff_id = ? AND b.status = 'Pending'");
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $stmt_check->bind_result($pending_count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($pending_count > 0) {
            echo "<script>alert('Cannot update staff - she/he is being assigned in pending bookings.');</script>";
        } else {
            // Call stored procedure for update
            $conn->query("CALL ManageStaff('update', '$id', '$name', '', '', '$phone_number', '$branch', '', '$status', '$made_by', @result)");
            $result = $conn->query("SELECT @result AS result")->fetch_assoc();

            // Success/fail message
            if ($result['result'] == 1) {
                echo "<script>alert('The update is successful.');</script>";
            } else {
                echo "<script>alert('Failed to update staff.');</script>";
            }
        }
    }

    echo "<script>window.location.href = '../managestaff.php';</script>";
    exit();
}
$conn->close();
