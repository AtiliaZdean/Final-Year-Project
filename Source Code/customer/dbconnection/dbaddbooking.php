<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $customer_id = $_SESSION['customer_id'];
    $total_area = $_POST['TotalArea'];
    $no_of_bedrooms = $_POST['NoOfBedrooms'];
    $no_of_bathrooms = $_POST['NoOfBathrooms'];
    $no_of_livingrooms = $_POST['NoOfLivingrooms'];
    $size_of_kitchen = $_POST['SizeOfKitchen'];
    $pet = $_POST['Pet'];
    $custom_request = $_POST['AdditionalReq'];
    $total = $_POST['total'];
    $duration = $_POST['duration'];
    $scheduled_date = $_POST['Date'];
    $scheduled_time = $_POST['Time'];
    $city = $_POST['City'] ?? $_SESSION['city'];
    $additional_services = $_POST['additional_services'] ?? [];
    $no_of_cleaners = intval($_POST['NoOfCleaners']);

    try {
        $conn->begin_transaction();

        // Insert into booking table
        $stmt_booking = $conn->prepare(
            "INSERT INTO booking (customer_id, total_area_sqft, no_of_bedrooms, no_of_bathrooms, no_of_livingroooms, size_of_kitchen_sqft, pet, no_of_cleaners, custom_request, total_RM, estimated_duration_hour, scheduled_date, scheduled_time, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')"
        );

        $stmt_booking->bind_param("idiiidsisddss", $customer_id, $total_area, $no_of_bedrooms, $no_of_bathrooms, $no_of_livingrooms, $size_of_kitchen, $pet, $no_of_cleaners, $custom_request, $total, $duration, $scheduled_date, $scheduled_time);
        $stmt_booking->execute();
        $booking_id = $conn->insert_id;
        $stmt_booking->close();

        // Insert into payment table
        $stmt_payment = $conn->prepare(
            "INSERT INTO payment (booking_id, status)
             VALUES (?, 'Pending')"
        );

        $stmt_payment->bind_param("i", $booking_id);
        $stmt_payment->execute();
        $stmt_payment->close();

        // Assign available cleaners
        $stmt_assign = $conn->prepare("CALL AssignCleaners(?, ?, ?, ?, ?, ?, @success, @message)");
        $stmt_assign->bind_param("isssdi", $booking_id, $city, $scheduled_date, $scheduled_time, $duration, $no_of_cleaners);
        $stmt_assign->execute();

        // Check if assignment was successful
        $result = $conn->query("SELECT @success as success, @message as message");
        $assignment = $result->fetch_assoc();

        if (!$assignment['success']) {
            throw new Exception("Cleaner assignment failed: " . $assignment['message']);
        }

        // Insert each additional service into BOOKING_SERVICE
        if (!empty($additional_services)) {
            $stmt_service = $conn->prepare("INSERT INTO booking_service (booking_id, service_id) VALUES (?, ?)");

            foreach ($additional_services as $service_id) {
                $stmt_service->bind_param("ii", $booking_id, $service_id);
                $stmt_service->execute();
            }
            $stmt_service->close();
        }

        $conn->commit();

        $_SESSION['status'] = "Your booking has been successfully requested.";
        header("Location: ../addbooking.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['EmailMessage'] = "Error: " . $e->getMessage();
        header("Location: ../addbooking.php");
        exit;
    }
}
$conn->close();
