<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $date = $data['date'] ?? '';
    $time = $data['time'] ?? '';
    $city = $data['city'] ?? '';
    $duration = min(floatval($data['estimatedDuration'] ?? 1.0), 8.0);

    // Count available cleaners
    $stmt = $conn->prepare("
        SELECT COUNT(s.staff_id) as available_count
        FROM STAFF s
        WHERE s.branch = ?
        AND s.role = 'Cleaner'
        AND s.staff_id NOT IN (
            SELECT bc.staff_id
            FROM BOOKING_CLEANER bc
            JOIN BOOKING b ON bc.booking_id = b.booking_id
            WHERE b.scheduled_date = ?
            AND b.status = 'Pending'
            AND (
                TIME(?) < ADDTIME(b.scheduled_time, SEC_TO_TIME(b.estimated_duration_hour*3600))
                AND
                ADDTIME(TIME(?), SEC_TO_TIME(?*3600)) > b.scheduled_time
            )
        )
    ");
    
    $stmt->bind_param("ssssd", $city, $date, $time, $time, $duration);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    echo json_encode(['available' => $row['available_count']]);
    exit;
}
$conn->close();
?>