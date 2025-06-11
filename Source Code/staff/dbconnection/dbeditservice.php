<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $service_id = $_POST['service_id'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    $duration = $_POST['Duration'];
    $staff = $_SESSION['staffname'];

    $conn->query("SET @made_by = '$staff'");

    try {
        // First check if service is used in any PENDING bookings
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM BOOKING_SERVICE bs JOIN BOOKING b ON bs.booking_id = b.booking_id WHERE bs.service_id = ? AND b.status = 'Pending'");
        $stmt_check->bind_param("i", $service_id);
        $stmt_check->execute();
        $stmt_check->bind_result($pending_count);
        $stmt_check->fetch();
        $stmt_check->close();

        // Check if update button was pressed
        if (isset($_POST['update'])) {
            if ($pending_count > 0) {
                $_SESSION['EmailMessage'] = 'Cannot update service - it is being used in pending bookings.';
            } else {
                $stmt_update = $conn->prepare("UPDATE additional_service SET description = ?, price_RM = ?, duration_hour = ? WHERE service_id = ?");
                $stmt_update->bind_param("sssi", $description, $price, $duration, $service_id);
                if ($stmt_update->execute()) {
                    $_SESSION['status'] = 'Service successfully updated.';
                } else {
                    $_SESSION['EmailMessage'] = 'Error updating service.';
                }
                $stmt_update->close();
            }
        }

        // Check if delete button was pressed
        if (isset($_POST['delete'])) {
            if ($pending_count > 0) {
                $_SESSION['EmailMessage'] = 'Cannot delete service - it is being used in pending bookings.';
            } else {
                // Delete the service only if not used in any bookings
                $stmt_delete = $conn->prepare("DELETE FROM additional_service WHERE service_id = ?");
                $stmt_delete->bind_param("i", $service_id);
                if ($stmt_delete->execute()) {
                    $_SESSION['status'] = 'Service successfully deleted.';
                } else {
                    $_SESSION['EmailMessage'] = 'Error deleting service.';
                }
                $stmt_delete->close();
            }
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
?>