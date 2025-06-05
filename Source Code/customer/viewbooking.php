<?php
session_start();
include('../dbconnection.php');

// Get customer's bookings
$customer_id = $_SESSION['customer_id'];
$stmt = "SELECT b.*, 
         GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') AS cleaners,
         GROUP_CONCAT(DISTINCT asv.name SEPARATOR ', ') AS services,
         p.status AS payment_status
         FROM booking b
         LEFT JOIN BOOKING_CLEANER bc ON b.booking_id = bc.booking_id
         LEFT JOIN STAFF s ON bc.staff_id = s.staff_id
         LEFT JOIN BOOKING_SERVICE bs ON b.booking_id = bs.booking_id
         LEFT JOIN ADDITIONAL_SERVICE asv ON bs.service_id = asv.service_id
         LEFT JOIN PAYMENT p ON p.booking_id = b.booking_id
         WHERE b.customer_id = ?
         GROUP BY b.booking_id 
         ORDER BY b.scheduled_date DESC, b.scheduled_time DESC";
$stmt = $conn->prepare($stmt);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
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
</head>

<body>
    <div class="container-scroller">
        <!-- Header -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-1" href="home.php"><img src="..\images\HygieaHub logo.png" class="mr-1" alt="HygieiaHub logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">
                            <span class="menu-title">Home</span>
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

                    <div class="row row-center">
                        <div class="col-md-12 col-center grid-margin">
                            <?php
                            echo "<p>" . count($bookings) . " booking(s) made.</p>";
                            if (count($bookings) > 0):
                            ?>
                        </div>
                    </div>

                    <?php foreach ($bookings as $index => $booking): ?>
                        <?php
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
                                                        <span class="form-control <?= $statusClass ?>" style="border:0;"><?= htmlspecialchars($booking['status']) ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Payment Status -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Payment Status</label>
                                                    <div class="col-sm-9">
                                                        <span class="form-control <?= $paymentStatusClass ?>" style="border:0;"><?= htmlspecialchars($booking['payment_status']) ?></span>
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
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($booking['scheduled_date']); ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Time -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Scheduled Time</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" value="<?= htmlspecialchars($booking['scheduled_time']); ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-content">
                                            <div class="row">
                                                <!-- Total Area -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Total Area</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['total_area_sqft']) ?> sqft" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- No Of Bedrooms -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">No Of Bedrooms</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" class="form-control" value="<?= htmlspecialchars($booking['no_of_bedrooms']) ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- No Of Bathrooms -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">No Of Bathrooms</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['no_of_bathrooms']) ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- No Of Livingrooms -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">No Of Livingrooms</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['no_of_livingroooms']) ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Size Of Kitchen -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Size Of Kitchen</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['size_of_kitchen_sqft']) ?> sqft" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Pet -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Pets</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['pet']) ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Custom Request -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Custom Request</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['custom_request'] ?? '') ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Services -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Services</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['services'] ?? '') ?>" readonly>
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
                                                <!-- Cleaners -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Cleaners</label>
                                                        <div class="input-group col-sm-9">
                                                            <input type="text" class="form-control col-sm-2" value="<?= htmlspecialchars($booking['no_of_cleaners']) ?>" readonly>
                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($booking['cleaners'] ?? '') ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="row row-center">
                        <div class="col-md-12 col-center grid-margin">
                            <p>No booking made.Let's </p>
                            <a href="addbooking.php" class="text-primary">book now !</a>
                        </div>
                    </div>
                <?php endif; ?>
                </div>
                <footer class="footer"></footer>
            </div>
        </div>
    </div>

    <!-- Function Javascripts -->
    <script>
        function toggleCollapse(element) {
            // Find the card content and arrow icon
            const card = element.closest('.card-collapsible');
            const content = card.querySelector('.card-content');

            // Toggle the show class
            content.classList.toggle('show');
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