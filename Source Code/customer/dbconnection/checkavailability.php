<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $date = $data['date'] ?? '';
    $time = $data['time'] ?? '';
    $city = $data['city'] ?? '';
    $duration = min(floatval($data['estimatedDuration'] ?? 1.0), 8.0);

    // Call stored procedure
    $conn->query("SET @available_count = 0");
    $stmt = $conn->prepare("CALL CheckCleanerAvailability(?, ?, ?, ?, @available_count)");
    $stmt->bind_param("sssd", $city, $date, $time, $duration);
    $stmt->execute();
    $stmt->close();
    
    // Get the output parameter
    $result = $conn->query("SELECT @available_count as available_count");
    $row = $result->fetch_assoc();

    // Debugging: Log available count
    error_log("Available Cleaners: " . $row['available_count']);
    
    echo json_encode(['available' => (int)$row['available_count']]);
    exit;
}
$conn->close();
?>