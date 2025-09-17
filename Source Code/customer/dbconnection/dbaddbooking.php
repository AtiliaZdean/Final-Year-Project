<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $customer_id = $_SESSION['customer_id'];
    $address_id = $_POST['AddressSelect'];
    $hours_booked = $_POST['HoursBooked'];
    $custom_request = $_POST['AdditionalReq'];
    $total = $_POST['total'];
    $duration = $_POST['duration'];
    $scheduled_date = $_POST['Date'];
    $scheduled_time = $_POST['Time'];
    $city = $_POST['City'];
    $additional_services = $_POST['additional_services'] ?? [];
    $no_of_cleaners = intval($_POST['NoOfCleaners']);

    try {
        $conn->begin_transaction();

        // Get full address details from address_id
        $stmt_address = $conn->prepare(
            "SELECT a.address, a.city, a.state, a.house_id, h.name as house_type 
             FROM customer_addresses a
             JOIN HOUSE_TYPE h ON a.house_id = h.house_id
             WHERE a.address_id = ? AND a.customer_id = ?"
        );
        $stmt_address->bind_param("ii", $address_id, $customer_id);
        $stmt_address->execute();
        $address_data = $stmt_address->get_result()->fetch_assoc();
        $stmt_address->close();

        if (!$address_data) {
            throw new Exception("Invalid address selected");
        }

        $full_address = $address_data['address'] . ', ' . $address_data['city'] . ', ' . $address_data['state'];

        // Insert into booking table
        $stmt_booking = $conn->prepare(
            "INSERT INTO booking (customer_id, address_id, house_id, address, hours_booked, no_of_cleaners, custom_request, total_RM, estimated_duration_hour, scheduled_date, scheduled_time, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')"
        );

        $stmt_booking->bind_param("iiisdisddss", $customer_id, $address_id, $address_data['house_id'], $full_address, $hours_booked, $no_of_cleaners, $custom_request, $total, $duration, $scheduled_date, $scheduled_time);
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
        $stmt_assign->close();

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

        $_SESSION['status'] = "Your booking has been successfully requested. Thank you for choosing us!";
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
