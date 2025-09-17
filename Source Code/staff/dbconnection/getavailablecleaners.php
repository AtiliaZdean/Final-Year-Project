<?php
session_start();
include('../../dbconnection.php');

$bookingId = $_POST['bookingId'] ?? 0;
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$duration = min(floatval($_POST['duration'] ?? 1.0), 8.0);
$branch = $_POST['city'] ?? $_SESSION['branch'];

try {
    // Get currently assigned cleaners for this booking
    $currentCleaners = [];
    if ($bookingId > 0) {
        $stmtCurrent = $conn->prepare("
            SELECT s.staff_id, s.name 
            FROM booking_cleaner bc
            JOIN staff s ON bc.staff_id = s.staff_id
            WHERE bc.booking_id = ?
        ");
        $stmtCurrent->bind_param("i", $bookingId);
        $stmtCurrent->execute();
        $resultCurrent = $stmtCurrent->get_result();
        while ($row = $resultCurrent->fetch_assoc()) {
            $currentCleaners[$row['staff_id']] = $row['name'];
        }
        $stmtCurrent->close();
    }

    // Get all active cleaners in the branch
    $stmtAll = $conn->prepare("
        SELECT staff_id, name 
        FROM staff 
        WHERE role = 'Cleaner' 
        AND branch = ?
        AND status = 'Active'
    ");
    $stmtAll->bind_param("s", $branch);
    $stmtAll->execute();
    $resultAll = $stmtAll->get_result();
    
    $cleaners = [];
    while ($row = $resultAll->fetch_assoc()) {
        $cleaners[] = [
            'staff_id' => $row['staff_id'],
            'name' => $row['name'],
            'is_current' => array_key_exists($row['staff_id'], $currentCleaners)
        ];
    }
    
    // Get unavailable cleaners (excluding current ones for this booking)
    $stmtUnavailable = $conn->prepare("
        SELECT DISTINCT s.staff_id, s.name
        FROM staff s
        JOIN booking_cleaner bc ON s.staff_id = bc.staff_id
        JOIN booking b ON bc.booking_id = b.booking_id
        WHERE b.scheduled_date = ?
        AND b.status = 'Pending'
        AND b.booking_id != ?
        AND (
            TIME(?) < ADDTIME(b.scheduled_time, SEC_TO_TIME(b.estimated_duration_hour*3600))
            AND
            ADDTIME(TIME(?), SEC_TO_TIME((? + 0.5)*3600)) > b.scheduled_time
        )
        AND s.branch = ?
    ");
    $stmtUnavailable->bind_param("sissds", $date, $bookingId, $time, $time, $duration, $branch);
    $stmtUnavailable->execute();
    $resultUnavailable = $stmtUnavailable->get_result();
    
    $unavailableCleaners = [];
    while ($row = $resultUnavailable->fetch_assoc()) {
        $unavailableCleaners[$row['staff_id']] = true;
    }
    
    // Filter out unavailable cleaners (except current ones for this booking)
    $availableCleaners = array_filter($cleaners, function($cleaner) use ($unavailableCleaners) {
        return !isset($unavailableCleaners[$cleaner['staff_id']]) || $cleaner['is_current'];
    });
    
    header('Content-Type: application/json');
    echo json_encode(['cleaners' => array_values($availableCleaners)]);
} catch (Exception $e) {
    error_log("Error getting available cleaners: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
$conn->close();
?>