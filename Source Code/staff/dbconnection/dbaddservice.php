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
