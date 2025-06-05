<?php
session_start();
include '../../dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $id = $_POST['BookingId'];
    $status = $_POST['StatusModal'];
    $payment_status = $_POST['PaymentStatusModal'];
    $note = $_POST['Note'];
    $staff = $_SESSION['name'];

    try {
        // update into table booking
        $stmt_booking = $conn->prepare("UPDATE booking SET status = ?, note = ? WHERE booking_id = ?");
        $stmt_booking->bind_param("ssi", $status, $note, $id);
        $stmt_booking->execute();
        $stmt_booking->close();

        // update into table payment
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
            // Fallback to keep existing payment status
            $stmt_payment = $conn->prepare("UPDATE payment SET status = ?, payment_date = NULL WHERE booking_id = ?");
            $stmt_payment->bind_param("si", $payment_status, $id);
        }
        $stmt_payment->execute();
        $stmt_payment->close();

        // update into table booking_log
        $stmt_log = $conn->prepare("INSERT INTO booking_log (booking_id, made_by) VALUES (?, ?)");
        $stmt_log->bind_param("is", $id, $staff);
        $stmt_log->execute();
        $stmt_log->close();

        echo "<script>alert('The update is successful.');</script>";
        echo "<script>window.location.href = '../managebooking.php';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Failed to update booking.');</script>";
        echo "<script>window.location.href = '../managebooking.php';</script>";
        exit;
    }
}
$conn->close();
