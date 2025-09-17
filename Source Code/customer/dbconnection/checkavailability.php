<?php
session_start();
include('../../dbconnection.php');

// Set content type first
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't output errors to browser
ini_set('log_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        
        if (!$data || json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON input: ' . json_last_error_msg());
        }

        $date = $data['date'] ?? '';
        $time = $data['time'] ?? '';
        $city = $data['city'] ?? $_SESSION['branch'] ?? '';
        $duration = min(floatval($data['estimatedDuration'] ?? 1.0), 8.0);

        // Validate required fields
        if (empty($date) || empty($time) || empty($city)) {
            throw new Exception('Missing required parameters');
        }

        // Call stored procedure
        $conn->query("SET @available_count = 0");
        $stmt = $conn->prepare("CALL CheckCleanerAvailability(?, ?, ?, ?, @available_count)");
        
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        
        $stmt->bind_param("sssd", $city, $date, $time, $duration);
        
        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
        
        $stmt->close();

        // Get the output parameter
        $result = $conn->query("SELECT @available_count as available_count");
        
        if (!$result) {
            throw new Exception('Query failed: ' . $conn->error);
        }
        
        $row = $result->fetch_assoc();
        $available_count = (int)($row['available_count'] ?? 0);

        echo json_encode([
            'success' => true,
            'available' => $available_count
        ]);
        
    } catch (Exception $e) {
        error_log("checkavailability.php error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => 'Server error: ' . $e->getMessage(),
            'available' => 0
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method',
        'available' => 0
    ]);
}

$conn->close();
exit;
?>