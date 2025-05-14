<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['Name'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    $price = 'RM ' . $price;
    $duration = $_POST['Duration'];
    $duration = $duration . ' hour';
    $staff = $_SESSION['name'];

    $conn->query("SET @made_by = '$staff'");

    try {
        // Insert data into additional_service table
        $stmt_insert = $conn->prepare(
            "INSERT INTO additional_service (name, description, price, duration)
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
        header("Location: ../addservice.php");
        exit;
    }
}
$conn->close();
