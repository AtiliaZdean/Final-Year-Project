<?php
include '../../dbconnection.php';

header('Content-Type: application/json');

if (isset($_GET['staff_id'])) {
    $staff_id = (int)$_GET['staff_id'];
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT image_path FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'image_path' => $row['image_path']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No image found for this staff member'
        ]);
    }
    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No staff ID provided'
    ]);
}

$conn->close();
?>