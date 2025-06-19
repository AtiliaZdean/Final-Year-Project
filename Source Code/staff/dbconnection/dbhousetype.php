<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $rate = $_POST['BaseHourlyRate'];
    $minhours = $_POST['MinimumHours'];

    try {
        // Check if update button was pressed
        if (isset($_POST['update'])) {
            $house_id = $_POST['house_id'];

            $stmt_update = $conn->prepare("UPDATE house_type SET base_hourly_rate = ?, min_hours = ? WHERE house_id = ?");
            $stmt_update->bind_param("ssi", $rate, $minhours, $house_id);
            if ($stmt_update->execute()) {
                $_SESSION['status'] = 'House type successfully updated.';
            } else {
                $_SESSION['EmailMessage'] = 'Error updating service.';
            }
            $stmt_update->close();

            header("Location: ../edithousetype.php");
        }

        // Check if insert button was pressed
        if (isset($_POST['insert'])) {
            $name = $_POST['Name'];

            // insert the service only if not used in any bookings
            $stmt_insert = $conn->prepare("INSERT INTO house_type (name, base_hourly_rate, min_hours) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $name, $rate, $minhours);
            if ($stmt_insert->execute()) {
                $_SESSION['status'] = 'House type successfully added.';
            } else {
                $_SESSION['EmailMessage'] = 'Error adding house type.';
            }
            $stmt_insert->close();

            header("Location: ../addhousetype.php");
        }

        exit;
    } catch (Exception $e) {
        $_SESSION['EmailMessage'] = ' Error: ' . $e->getMessage();
        header("Location: ../edithousetype.php");
        exit;
    }
}
$conn->close();
