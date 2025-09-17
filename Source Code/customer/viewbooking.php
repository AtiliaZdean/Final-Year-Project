<?php
session_start();
include('../dbconnection.php');

$date = isset($_POST['Date']) ? $_POST['Date'] : '';
$status = isset($_POST['Status']) ? $_POST['Status'] : '';
$paymentStatus = isset($_POST['PaymentStatus']) ? $_POST['PaymentStatus'] : '';

// Get customer's bookings
$customer_id = $_SESSION['customer_id'];
$stmt = "SELECT b.*, 
         GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') AS cleaners,
         GROUP_CONCAT(DISTINCT s.staff_id SEPARATOR ', ') AS cleaner_ids,
         GROUP_CONCAT(DISTINCT s.image_path SEPARATOR ', ') AS cleaner_images,
         GROUP_CONCAT(DISTINCT asv.name SEPARATOR ', ') AS services,
         h.name AS house,
         p.status AS payment_status,
         f.rating AS feedback_rating,
         f.comment AS feedback_comment
         FROM booking b
         LEFT JOIN BOOKING_CLEANER bc ON b.booking_id = bc.booking_id
         LEFT JOIN STAFF s ON bc.staff_id = s.staff_id
         LEFT JOIN BOOKING_SERVICE bs ON b.booking_id = bs.booking_id
         LEFT JOIN ADDITIONAL_SERVICE asv ON bs.service_id = asv.service_id
         LEFT JOIN HOUSE_TYPE h ON h.house_id = b.house_id
         LEFT JOIN PAYMENT p ON p.booking_id = b.booking_id
         LEFT JOIN FEEDBACK f ON f.booking_id = b.booking_id
         WHERE b.customer_id = ?";
if (!empty($date)) {
    $stmt .= " AND b.scheduled_date = '" . $conn->real_escape_string($date) . "'";
}
if (!empty($status)) {
    $stmt .= " AND b.status = '" . $conn->real_escape_string($status) . "'";
}
if (!empty($paymentStatus)) {
    $stmt .= " AND p.status = '" . $conn->real_escape_string($paymentStatus) . "'";
}
$stmt .= " GROUP BY b.booking_id ORDER BY b.scheduled_date DESC, b.scheduled_time DESC";
$stmt = $conn->prepare($stmt);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);

// Analyze service repetition patterns
$serviceFrequency = [];
$lastServiceDates = [];

foreach ($bookings as $booking) {
    // Count service frequency
    if (!empty($booking['services'])) {
        $services = explode(', ', $booking['services']);
        foreach ($services as $service) {
            if (!isset($serviceFrequency[$service])) {
                $serviceFrequency[$service] = 0;
                $lastServiceDates[$service] = [];
            }
            $serviceFrequency[$service]++;
            $lastServiceDates[$service][] = $booking['scheduled_date'];
        }
    }
}

// Calculate average intervals for repeated services
$serviceIntervals = [];
foreach ($lastServiceDates as $service => $dates) {
    if (count($dates) > 1) {
        $intervals = [];
        sort($dates);
        for ($i = 1; $i < count($dates); $i++) {
            $date1 = new DateTime($dates[$i - 1]);
            $date2 = new DateTime($dates[$i]);
            $intervals[] = $date1->diff($date2)->days;
        }
        $serviceIntervals[$service] = array_sum($intervals) / count($intervals);
    }
}

// Handle cancellation request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    $booking_id = $_POST['booking_id'];

    // Verify the booking belongs to the logged-in customer
    $verify_stmt = $conn->prepare("SELECT customer_id, scheduled_date, scheduled_time, status FROM booking WHERE booking_id = ?");
    $verify_stmt->bind_param("i", $booking_id);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();

    if ($verify_result->num_rows > 0) {
        $booking_data = $verify_result->fetch_assoc();

        // Check if booking belongs to this customer
        if ($booking_data['customer_id'] == $customer_id) {
            // Check if booking is pending and at least 24 hours in advance
            $booking_datetime = new DateTime($booking_data['scheduled_date'] . ' ' . $booking_data['scheduled_time']);
            $current_datetime = new DateTime();
            $time_diff = $current_datetime->diff($booking_datetime);

            if ($booking_data['status'] == 'Pending' && $time_diff->h + ($time_diff->days * 24) >= 24) {
                // Update booking status to Cancelled
                $update_booking = $conn->prepare("UPDATE booking SET status = 'Cancelled', note = 'Cancelled by customer' WHERE booking_id = ?");
                $update_booking->bind_param("i", $booking_id);
                $update_booking->execute();

                // Update payment status to Cancelled if exists
                $update_payment = $conn->prepare("UPDATE payment SET status = 'Cancelled' WHERE booking_id = ?");
                $update_payment->bind_param("i", $booking_id);
                $update_payment->execute();

                $_SESSION['status'] = "Booking has been cancelled successfully.";

                // Refresh the page to show updated status
                header("Location: viewbooking.php");
                exit();
            } else {
                $_SESSION['EmailMessage'] = "Cancellation failed. Bookings can only be cancelled at least 24 hours before the scheduled time.";
            }
        } else {
            $_SESSION['EmailMessage'] = "You don't have permission to cancel this booking.";
        }
    } else {
        $_SESSION['EmailMessage'] = "Booking not found.";
    }

    header("Location: viewbooking.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HygieiaHub Booking</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../vendors/feather/feather.css">
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="../images/favicon.png" />

    <style>
        .cleaner-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .cleaner-image-container {
            text-align: center;
            width: 100px;
        }

        .cleaner-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #eee;
        }

        .cleaner-name {
            font-size: 12px;
            margin-top: 5px;
            word-break: break-word;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- Header -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-1" href="../index.php"><img src="..\images\HygieaHub logo.png" class="mr-1" alt="HygieiaHub logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <span class="menu-title">Home</span>
                        </a>
                    </li>

                    <!-- Community -->
                    <li class="nav-item">
                        <a class="nav-link" href="community.php">
                            <span class="menu-title">Community</span>
                        </a>
                    </li>

                    <!-- Booking -->
                    <li class="nav-item">
                        <a class="nav-link" href="addbooking.php">
                            <span class="menu-title">Book Now</span>
                        </a>
                    </li>

                    <?php
                    if (isset($_SESSION['customer_id'])) {
                    ?>
                        <!-- Booking List -->
                        <li class="nav-item">
                            <a class="nav-link" href="viewbooking.php">
                                <span class="menu-title">Bookings</span>
                            </a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <?php
                    if (!isset($_SESSION['customer_id'])) {
                    ?>
                        <a href="login.php" class="btn btn-primary btn-md btn-margin">Sign In</a>
                    <?php
                    } else {
                    ?>
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <img src="..\images\profile picture.jpg" alt="profile" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="profile.php">
                                    <i class="ti-user text-primary"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="ti-power-off text-primary"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper topbar-full-page-wrapper">
            <div class="main-2-panel">
                <!-- content -->
                <div class="content-wrapper">
                    <div class="row row-center">
                        <div class="col-md-12 col-center grid-margin">
                            <h3 class="font-weight-bold">Your Booking History</h3>
                        </div>
                    </div>

                    <!-- Service Repetition Summary Section -->
                    <div class="row row-center">
                        <div class="col-md-12 col-center grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Your Service Patterns</h4>
                                    <?php if (!empty($serviceFrequency)): ?>
                                        <div class="row">
                                            <?php if (!empty($serviceFrequency)): ?>
                                                <div class="col-md-12">
                                                    <ul class="list-star">
                                                        <?php foreach ($serviceFrequency as $service => $count): ?>
                                                            <li>
                                                                <strong><?= htmlspecialchars($service) ?>:</strong>
                                                                <?= $count ?> time<?= $count > 1 ? 's' : '' ?>
                                                                <?php if (isset($serviceIntervals[$service])): ?>
                                                                    <small class="text-muted">(every ~<?= round($serviceIntervals[$service]) ?> days on average)</small>
                                                                <?php endif; ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <p>No service patterns detected yet. Your future patterns will appear here.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row row-center">
                        <div class="col-md-12 col-center grid-margin">
                            <p>Cancellation can be made at least 24 hours before the service appointment. For any inquiry, please contact 019-9545506</p>
                        </div>

                        <!-- Filtering -->
                        <div class="col-md-12 col-center grid-margin">
                            <div class="card card-transparent">
                                <div class="card-body">
                                    <form class="form-inline" method="POST">
                                        <label class="mr-3">Search by :</label>

                                        <!-- Search date -->
                                        <input type="date" class="form-control form-control-sm mr-3" name="Date" id="Date" title="Booking date">

                                        <!-- By status -->
                                        <select class="form-control form-control-sm mr-3" name="Status" id="Status">
                                            <option value="" disabled selected>Status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>

                                        <!-- By payment status -->
                                        <select class="form-control form-control-sm mr-4" name="PaymentStatus" id="PaymentStatus">
                                            <option value="" disabled selected>Payment status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Completed">Completed</option>
                                        </select>

                                        <button type="submit" class="btn btn-primary btn-sm mr-3">Search</button>
                                        <button type="button" class="btn btn-light btn-sm" onclick="resetFilters()">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-center grid-margin">
                            <?php
                            if (count($bookings) > 0):
                                echo "<p>" . count($bookings) . " booking(s).</p>";
                            ?>
                        </div>
                    </div>

                    <div class="row row-center">
                        <?php
                                // Success message
                                if (isset($_SESSION['status'])) {
                        ?>
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <?php echo $_SESSION['status']; ?>
                            </div>
                        <?php
                                    unset($_SESSION['status']);
                                }

                                // Error message
                                if (isset($_SESSION['EmailMessage'])) {
                        ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <?php echo $_SESSION['EmailMessage']; ?>
                            </div>
                        <?php
                                    unset($_SESSION['EmailMessage']);
                                }
                        ?>
                    </div>

                    <?php foreach ($bookings as $index => $booking): ?>
                        <?php
                                    // Determine if this booking has repeated services/house type
                                    $repeatedServices = [];
                                    if (!empty($booking['services'])) {
                                        $services = explode(', ', $booking['services']);
                                        foreach ($services as $service) {
                                            if (isset($serviceFrequency[$service]) && $serviceFrequency[$service] > 1) {
                                                $repeatedServices[] = $service;
                                            }
                                        }
                                    }

                                    // Determine text class for status
                                    $statusClass = '';
                                    if ($booking['status'] == 'Completed') {
                                        $statusClass = 'text-success';
                                    } elseif ($booking['status'] == 'Cancelled') {
                                        $statusClass = 'text-danger';
                                    } else {
                                        $statusClass = 'text-warning';
                                    }

                                    // Determine text class for payment status
                                    $paymentStatusClass = '';
                                    if ($booking['payment_status'] == 'Completed') {
                                        $paymentStatusClass = 'text-success';
                                    } elseif ($booking['payment_status'] == 'Cancelled') {
                                        $paymentStatusClass = 'text-danger';
                                    } else {
                                        $paymentStatusClass = 'text-warning';
                                    }
                        ?>

                        <!-- Booking List -->
                        <div class="row row-center">
                            <div class="col-md-10 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body card-collapsible" onclick="toggleCollapse(this)">
                                        <div class="row" id="collapse" style="display: block;">
                                            <div class="col-md-12 col-center">
                                                <small class="text-secondary">Click to see more</small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Status -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Status</label>
                                                    <div class="col-sm-9">
                                                        <span class="form-control <?= $statusClass ?>" style="border:0;">
                                                            <?= htmlspecialchars($booking['status'] === 'Attention' ? 'Pending' : $booking['status']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Payment Status -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Payment Status</label>
                                                    <div class="col-sm-9">
                                                        <span class="form-control <?= $paymentStatusClass ?>" style="border:0;">
                                                            <?= htmlspecialchars($booking['payment_status'] === 'Attention' ? 'Pending' : $booking['payment_status']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Date -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Scheduled Date</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars(date('d-m-Y', strtotime($booking["scheduled_date"]))); ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Time -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Scheduled Time</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars(date('H:i', strtotime($booking["scheduled_time"]))); ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-content">
                                            <div class="row">
                                                <!-- Address -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Address</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['address']) ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- House Type -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">House Type</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['house']) ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Hours Booked -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Number of Hours</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['hours_booked']) ?> hours" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Custom Request -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Custom Request</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['custom_request'] ?? '') ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Services -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Services</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['services'] ?? '') ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Cleaners -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Cleaners</label>
                                                        <div class="input-group col-sm-9">
                                                            <!-- <input type="text" class="form-control col-sm-2" value="<?= htmlspecialchars($booking['no_of_cleaners']) ?>" readonly> -->
                                                            <?php if (!empty($booking['cleaners']) && !empty($booking['cleaner_images'])):
                                                                $cleaners = explode(', ', $booking['cleaners']);
                                                                $cleaner_ids = explode(',', $booking['cleaner_ids']);
                                                                $cleaner_images = explode(',', $booking['cleaner_images']);
                                                            ?>
                                                                <div class="cleaner-images">
                                                                    <?php foreach ($cleaners as $index => $cleaner): ?>
                                                                        <div class="cleaner-image-container">
                                                                            <?php if (!empty($cleaner_images[$index])): ?>
                                                                                <img src="../media/<?= htmlspecialchars(trim($cleaner_images[$index])) ?>"
                                                                                    alt="<?= htmlspecialchars($cleaner) ?>"
                                                                                    class="cleaner-image"
                                                                                    onerror="this.src='../images/default-profile.jpg'">
                                                                            <?php else: ?>
                                                                                <img src="../images/default-profile.jpg"
                                                                                    alt="<?= htmlspecialchars($cleaner) ?>"
                                                                                    class="cleaner-image">
                                                                            <?php endif; ?>
                                                                            <div class="cleaner-name"><?= htmlspecialchars($cleaner) ?></div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php else: ?>
                                                                <input type="text" class="form-control" value="Not assigned yet" readonly>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Duration -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label pr-1">Estimated Duration</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['estimated_duration_hour']) ?> hours" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Total Amount -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Total Amount</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="RM <?= htmlspecialchars($booking['total_RM']) ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <?php if (htmlspecialchars($booking['status']) == 'Pending') { ?>
                                                    <!-- Cancellation -->
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Cancellation</label>
                                                            <div class="col-sm-9">
                                                                <?php
                                                                $booking_datetime = new DateTime($booking['scheduled_date'] . ' ' . $booking['scheduled_time']);
                                                                $current_datetime = new DateTime();
                                                                $time_diff = $current_datetime->diff($booking_datetime);

                                                                if ($booking['status'] == 'Pending' && $time_diff->h + ($time_diff->days * 24) >= 24): ?>
                                                                    <form method="POST" action="viewbooking.php" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                                        <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                                                                        <button type="submit" name="cancel_booking" class="btn btn-danger">Cancel Booking</button>
                                                                    </form>
                                                                <?php elseif ($booking['status'] == 'Pending'): ?>
                                                                    <button class="btn btn-secondary" disabled title="Cancellation must be made at least 24 hours before appointment">Cancellation Closed</button>
                                                                <?php else: ?>
                                                                    <button class="btn btn-secondary" disabled>Not Applicable</button>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <?php if (htmlspecialchars($booking['status']) == 'Completed') { ?>
                                                <!-- Generate receipt -->
                                                <div class="row row-center mb-5 receipt">
                                                    <button type="button" class="btn btn-primary" onclick="showReceipt(<?= $booking['booking_id'] ?>)">Receipt</button>
                                                </div>

                                                <div class="row row-center">
                                                    <h5>Feedback</h5>
                                                </div>

                                                <form class="pt-3" id="feedbackForm-<?= $booking['booking_id'] ?>" method="POST" action="dbconnection/dbfeedback.php">
                                                    <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                                                    <input type="hidden" name="Rating" id="ratingValue-<?= $booking['booking_id'] ?>" value="<?= htmlspecialchars($booking['feedback_rating']) ?>">

                                                    <div class="row">
                                                        <!-- Rating -->
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 col-form-label pr-1" for="Rating">Rating</label>
                                                                <div class="col-sm-9">
                                                                    <div class="star-rating-container">
                                                                        <div class="form-control star-rating <?= isset($booking['feedback_rating']) ? 'rating-locked' : '' ?>" style="border: none;">
                                                                            <?php
                                                                            $currentRating = isset($booking['feedback_rating']) ? (int)$booking['feedback_rating'] : 0;
                                                                            $isReadonly = isset($booking['feedback_rating']);
                                                                            for ($i = 5; $i >= 1; $i--): ?>
                                                                                <input type="radio" id="star<?= $i ?>-<?= $booking['booking_id'] ?>"
                                                                                    name="rating-<?= $booking['booking_id'] ?>"
                                                                                    value="<?= $i ?>"
                                                                                    <?= $currentRating == $i ? 'checked' : '' ?>
                                                                                    <?= $isReadonly ? 'disabled' : '' ?>>
                                                                                <label for="star<?= $i ?>-<?= $booking['booking_id'] ?>"
                                                                                    title="<?= $i ?> star<?= $i != 1 ? 's' : '' ?>"
                                                                                    <?= $isReadonly ? 'style="cursor: default;"' : '' ?>>
                                                                                    <i class="ti-star"></i>
                                                                                </label>
                                                                            <?php endfor; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Comment -->
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 col-form-label pr-1" for="Comment">Comment</label>
                                                                <div class="col-sm-9">
                                                                    <textarea class="form-control" name="Comment" rows="3" <?= $isReadonly ? 'readonly' : '' ?>><?= htmlspecialchars($booking['feedback_comment']) ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row row-center">
                                                        <button type="submit" class="btn btn-primary" <?= $isReadonly ? 'disabled' : '' ?>
                                                            onclick="<?= !$isReadonly ? 'return confirm(\'Are you sure you want to submit this feedback?\')' : 'return false' ?>">
                                                            <?= $isReadonly ? 'Feedback Submitted' : 'Submit Feedback' ?>
                                                        </button>
                                                    </div>
                                                </form>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No booking. Let's <a href="addbooking.php" class="text-primary">book now !</a></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Receipt Modal -->
        <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="receiptModalLabel">Booking Receipt</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="receiptContent">
                        <!-- Receipt content will be inserted here dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="printReceipt()">Print Receipt</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <footer class="footer">
            <a style="color: white;" href="../staff/login.php">For Staff</a>
        </footer>
    </div>
    </div>
    </div>

    <!-- Function Javascripts -->
    <script>
        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Handle all star rating interactions
            document.querySelectorAll('.star-rating:not(.rating-locked)').forEach(ratingContainer => {
                const form = ratingContainer.closest('form');
                const hiddenInput = form.querySelector('input[type="hidden"][name="Rating"]');
                const stars = ratingContainer.querySelectorAll('input[type="radio"]');

                stars.forEach(star => {
                    star.addEventListener('change', function() {
                        hiddenInput.value = this.value;
                    });

                    star.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                });

                ratingContainer.querySelectorAll('label').forEach(label => {
                    label.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                });

                // Initialize with default value if exists
                if (hiddenInput.value) {
                    const checkedStar = ratingContainer.querySelector(`input[value="${hiddenInput.value}"]`);
                    if (checkedStar) checkedStar.checked = true;
                }
            });
        });

        // Reset all filter dropdowns to their default state
        function resetFilters() {
            document.getElementById('Date').value = '';
            document.getElementById('Status').selectedIndex = 0;
            document.getElementById('PaymentStatus').selectedIndex = 0;
            document.forms[0].submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Set default image for any broken images
            document.querySelectorAll('.cleaner-image').forEach(img => {
                img.onerror = function() {
                    this.src = '../images/default-profile.jpg';
                };
            });
        });

        function toggleCollapse(element) {
            // Check if the click target is inside the form or star rating
            if (event.target.closest('form') || event.target.closest('.star-rating') || event.target.closest('.receipt')) {
                return; // Don't collapse if clicking inside form or star rating
            }

            // Find the card content and arrow icon
            const card = element.closest('.card-collapsible');
            const content = card.querySelector('.card-content');

            // Toggle the show class
            content.classList.toggle('show');
        }

        function showReceipt(bookingId) {
            // Get the booking data for this receipt
            const booking = <?php echo json_encode($bookings); ?>.find(b => b.booking_id == bookingId);

            if (!booking) {
                alert('Booking data not found');
                return;
            }

            // Format the receipt content
            const formattedDate = new Date(booking.scheduled_date).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const receiptContent = `
                <div class="receipt-container">
                    <div class="text-center mb-4">
                        <h3>HygieiaHub Cleaning Service</h3>
                        <p>Phone: 019-9545506 | Email: info@hygieiahub.com</p>
                    </div><hr>
            
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Booking Details</h5>
                            <p><strong>Booking ID:</strong> ${booking.booking_id}</p>
                            <p><strong>Date:</strong> ${formattedDate}</p>
                            <p><strong>Time:</strong> ${booking.scheduled_time.substr(0,5)}</p>
                            <p><strong>Status:</strong> ${booking.status}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
                            <p><strong>Address:</strong> ${booking.address}</p>
                        </div>
                    </div><hr>
            
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-center">Quantity/Hours</th>
                                <th class="text-right">Amount (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Base Cleaning (${booking.house})</td>
                                <td class="text-center">${booking.hours_booked} hours</td>
                                <td class="text-right">${(booking.total_RM / booking.hours_booked).toFixed(2)}</td>
                            </tr>
                            ${booking.services ? `
                            <tr>
                                <td colspan="3" class="font-weight-bold">Additional Services</td>
                            </tr>
                            ${booking.services.split(', ').map(service => `
                            <tr>
                                <td>${service}</td>
                                <td class="text-center">1</td>
                                <td class="text-right">${service.includes('Deep') ? '50.00' : '30.00'}</td>
                            </tr>
                            `).join('')}
                            ` : ''}
                            <tr>
                                <td colspan="2" class="font-weight-bold text-right">Subtotal</td>
                                <td class="text-right font-weight-bold">${(booking.total_RM * 0.94).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="font-weight-bold text-right">Service Tax (6%)</td>
                                <td class="text-right">${(booking.total_RM * 0.06).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="font-weight-bold text-right">Total Amount</td>
                                <td class="text-right font-weight-bold">${parseFloat(booking.total_RM).toFixed(2)}</td>
                            </tr>
                        </tbody>
                    </table>
            
                    <div class="payment-info mt-4">
                        <h5>Payment Information</h5>
                        <p><strong>Payment Status:</strong> ${booking.payment_status}</p>
                        <p><strong>Payment Method:</strong> Cash on Delivery</p>
                    </div><hr>
            
                    <div class="terms mt-4">
                        <p class="small text-muted">Thank you for choosing HygieiaHub!</p>
                        <p class="small text-muted">Cancellation policy: Bookings can be cancelled at least 24 hours before scheduled time.</p>
                    </div>
                </div>
            `;

            // Insert content into modal
            document.getElementById('receiptContent').innerHTML = receiptContent;

            // Show modal
            $('#receiptModal').modal('show');
        }

        // Function to print the receipt
        function printReceipt() {
            const printContent = document.getElementById('receiptContent').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;

            // Re-show the modal after printing
            $('#receiptModal').modal('show');
        }
    </script>

    <!-- javascript files -->
    <script src="../vendors/js/vendor.bundle.base.js"></script>
    <script src="../js/off-canvas.js"></script>
    <script src="../js/hoverable-collapse.js"></script>
    <script src="../js/template.js"></script>
    <script src="../js/settings.js"></script>
    <script src="../js/dashboard.js"></script>
</body>

</html>