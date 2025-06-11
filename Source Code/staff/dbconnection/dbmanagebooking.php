<?php
session_start();
include '../../dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $id = $_POST['BookingId'];
    $status = $_POST['StatusModal'];
    $payment_status = $_POST['PaymentStatusModal'];
    $note = $_POST['Note'];
    $conn->query("SET @current_user = '" . $_SESSION['staffname'] . "'");

    try {
        // Get current status before updating
        $stmt_get_current = $conn->prepare("SELECT status FROM booking WHERE booking_id = ?");
        $stmt_get_current->bind_param("i", $id);
        $stmt_get_current->execute();
        $result = $stmt_get_current->get_result();
        $current_data = $result->fetch_assoc();
        $old_status = $current_data['status'];
        $stmt_get_current->close();

        // Start transaction
        $conn->begin_transaction();

        // Update booking table
        $stmt_booking = $conn->prepare("UPDATE booking SET status = ?, note = ? WHERE booking_id = ?");
        $stmt_booking->bind_param("ssi", $status, $note, $id);
        $stmt_booking->execute();
        $stmt_booking->close();

        // Update payment table
        if ($payment_status == 'Completed') {
            $stmt_payment = $conn->prepare("UPDATE payment SET status = ?, payment_date = NOW() WHERE booking_id = ?");
            $stmt_payment->bind_param("si", $payment_status, $id);
        } else if ($status == 'Pending') {
            $stmt_payment = $conn->prepare("UPDATE payment SET status = 'Pending', payment_date = NULL WHERE booking_id = ?");
            $stmt_payment->bind_param("i", $id);
        } else if ($status == 'Cancelled') {
            $stmt_payment = $conn->prepare("UPDATE payment SET status = 'Cancelled', payment_date = NULL WHERE booking_id = ?");
            $stmt_payment->bind_param("i", $id);
        } else {
            $stmt_payment = $conn->prepare("UPDATE payment SET status = ?, payment_date = NULL WHERE booking_id = ?");
            $stmt_payment->bind_param("si", $payment_status, $id);
        }
        $stmt_payment->execute();
        $stmt_payment->close();

        // Commit transaction
        $conn->commit();

        echo "<script>alert('The update is successful.');</script>";
        echo "<script>window.location.href = '../managebooking.php';</script>";
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Booking update failed: " . $e->getMessage());
        echo "<script>alert('Failed to update booking. Please try again.');</script>";
        echo "<script>window.location.href = '../managebooking.php';</script>";
        exit;
    }
}
$conn->close();
