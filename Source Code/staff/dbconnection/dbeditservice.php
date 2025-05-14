<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $service_id = $_POST['service_id'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    $price = 'RM ' . $price;
    $duration = $_POST['Duration'];
    $duration = $duration . ' hour';
    $staff = $_SESSION['name'];

    $conn->query("SET @made_by = '$staff'");

    try {
        // Check if update button was pressed
        if (isset($_POST['update'])) {
            $stmt_update = $conn->prepare("UPDATE additional_service SET description = ?, price = ?, duration = ? WHERE service_id = ?");
            $stmt_update->bind_param("sssi", $description, $price, $duration, $service_id);
            if ($stmt_update->execute()) {
                $_SESSION['status'] = 'Service successfully updated.';
            } else {
                $_SESSION['EmailMessage'] = 'Error updating service.';
            }
            $stmt_update->close();
        }

        // Check if delete button was pressed
        if (isset($_POST['delete'])) {
            // Delete the service
            $stmt_delete = $conn->prepare("DELETE FROM additional_service WHERE service_id = ?");
            $stmt_delete->bind_param("i", $service_id);
            if ($stmt_delete->execute()) {
                $_SESSION['status'] = 'Service successfully deleted.';
            } else {
                $_SESSION['EmailMessage'] = 'Error deleting service.';
            }
            $stmt_delete->close();
        }

        header("Location: ../editservice.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['EmailMessage'] = ' Error: ' . $e->getMessage();
        header("Location: ../editservice.php");
        exit;
    }
}
$conn->close();