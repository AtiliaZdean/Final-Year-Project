<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['Name'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    $duration = $_POST['Duration'];
    $staff = $_SESSION['staffname'];

    $conn->query("SET @made_by = '$staff'");

    try {
        // First check if service name already exists
        $stmt_check = $conn->prepare(
            "SELECT COUNT(*) FROM additional_service WHERE name = ?"
        );
        $stmt_check->bind_param("s", $name);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            $_SESSION['EmailMessage'] = 'A service with this name already exists.';
            header("Location: ../addservice.php");
            exit;
        }

        // Insert data into additional_service table
        $stmt_insert = $conn->prepare(
            "INSERT INTO additional_service (name, description, price_RM, duration_hour)
             VALUES (?, ?, ?, ?)"
        );
        $stmt_insert->bind_param("ssss", $name, $description, $price, $duration);

        if ($stmt_insert->execute()) {
            $_SESSION['status'] = 'Service is successfully added.';
            header("Location: ../addservice.php");
            exit;
        } else {
            // Handle the case where the trigger raises an error
            if ($conn->errno == 45000) {
            }
            header("Location: ../addservice.php");
            exit;
        }
        $stmt_insert->close();
    } catch (Exception $e) {
        $_SESSION['EmailMessage'] = ' Error: ' . $e->getMessage();
        file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);
        header("Location: ../addservice.php");
        exit;
    }
}
$conn->close();
