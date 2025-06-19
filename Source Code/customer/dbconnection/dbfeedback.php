<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
    $rating = isset($_POST['Rating']) ? (float)$_POST['Rating'] : 0;
    $comment = isset($_POST['Comment']) ? trim($_POST['Comment']) : '';

    // Validate inputs
    if ($booking_id <= 0) {
        $_SESSION['EmailMessage'] = 'Invalid booking ID';
        header("Location: ../viewbooking.php");
        exit;
    }

    // We don't need to validate rating range since our star input only allows 1-5
    if ($rating == 0) {
        $_SESSION['EmailMessage'] = 'Please select a star rating';
        header("Location: ../viewbooking.php");
        exit;
    }

    try {
        // Check if feedback already exists for this booking
        $stmt_check = $conn->prepare("SELECT feedback_id FROM feedback WHERE booking_id = ?");
        $stmt_check->bind_param("i", $booking_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        
        if ($result->num_rows > 0) {
            $_SESSION['EmailMessage'] = 'Feedback has already been submitted for this booking';
            header("Location: ../viewbooking.php");
            exit;
        }

        // Insert new feedback
        $stmt_insert = $conn->prepare(
            "INSERT INTO feedback (booking_id, rating, comment) 
             VALUES (?, ?, ?)"
        );
        $stmt_insert->bind_param("ids", $booking_id, $rating, $comment);

        if ($stmt_insert->execute()) {
            $_SESSION['status'] = 'Thank you! Your feedback has been submitted successfully.';
        } else {
            $_SESSION['EmailMessage'] = 'Failed to submit feedback. Please try again.';
        }
        
        $stmt_insert->close();
        $stmt_check->close();
        
        header("Location: ../viewbooking.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['EmailMessage'] = 'Error submitting feedback. Please try again later.';
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        header("Location: ../viewbooking.php");
        exit;
    }
} else {
    $_SESSION['EmailMessage'] = 'Invalid request method';
    header("Location: ../viewbooking.php");
    exit;
}

$conn->close();