<?php
session_start();
include('../dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HygieiaHub Manage Booking</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../vendors/feather/feather.css">
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="../images/favicon.png" />

    <!-- Spesified external -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ADD THIS LINE -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    <div class="container-scroller">
        <!-- Header -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-1" href="dashboard.php"><img src="..\images\HygieaHub logo.png" class="mr-1" alt="HygieiaHub logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav navbar-nav-right">
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
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <!-- sidebar -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>

                    <!-- Manage House Type -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#manage-housetype" aria-expanded="false" aria-controls="manage-housetype">
                            <span class="menu-title">Manage House Type</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="manage-housetype">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="addhousetype.php">Add House Type</a></li>
                                <li class="nav-item"> <a class="nav-link" href="edithousetype.php">Edit House Type</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Manage Service -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#manage-service" aria-expanded="false" aria-controls="manage-service">
                            <span class="menu-title">Manage Service</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="manage-service">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="addservice.php">Add Service</a></li>
                                <li class="nav-item"> <a class="nav-link" href="editservice.php">Edit Service</a></li>
                                <li class="nav-item"> <a class="nav-link" href="viewservice.php">View Service</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Manage Staff Account -->
                    <li class="nav-item">
                        <a class="nav-link" href="managestaff.php">
                            <span class="menu-title">Manage Staff</span>
                        </a>
                    </li>

                    <!-- Manage Booking -->
                    <li class="nav-item">
                        <a class="nav-link" href="managebooking.php">
                            <span class="menu-title">Manage Booking</span>
                        </a>
                    </li>

                    <!-- Report -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#report" aria-expanded="false" aria-controls="report">
                            <span class="menu-title">Report</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="report">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="salesreport.php">Sales</a></li>
                                <li class="nav-item"> <a class="nav-link" href="feedbackreport.php">Feedback</a></li>
                                <li class="nav-item"> <a class="nav-link" href="staffreport.php">Staff Performance</a></li>
                                <li class="nav-item"> <a class="nav-link" href="servicereport.php">Service Utilization</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Maintenance -->
                    <li class="nav-item">
                        <a class="nav-link" href="maintenance.php">
                            <span class="menu-title">Maintenance</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- content -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <h3 class="font-weight-bold">Booking</h3>
                        </div>
                    </div>

                    <!-- Filtering -->
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card card-transparent">
                                <div class="card-body">
                                    <form class="form-inline" method="POST">
                                        <label class="mr-3">Search by :</label>

                                        <!-- Search date -->
                                        <input type="date" class="form-control form-control-sm mr-3" name="Date" id="Date" title="Booking date">

                                        <!-- Search cleaner -->
                                        <input type="text" class="form-control form-control-sm mr-3" name="Cleaner" id="Cleaner" placeholder="Cleaner's name">

                                        <!-- By status -->
                                        <select class="form-control form-control-sm mr-3" name="Status" id="Status">
                                            <option value="" disabled selected>Status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Cancelled">Cancelled</option>
                                            <option value="Attention">Attention</option>
                                        </select>

                                        <!-- By payment status -->
                                        <select class="form-control form-control-sm mr-4" name="PaymentStatus" id="PaymentStatus">
                                            <option value="" disabled selected>Payment status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Cancelled">Cancelled</option>
                                            <option value="Attention">Attention</option>
                                        </select>

                                        <button type="submit" class="btn btn-primary btn-sm mr-3">Search</button>
                                        <button type="button" class="btn btn-light btn-sm" onclick="resetFilters()">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking List -->
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive pt-3">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Status</th>
                                                    <th>Payment Status</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Address</th>
                                                    <th>Cleaners</th>
                                                    <th>Estimated Duration</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                include '../dbconnection.php';

                                                $date = isset($_POST['Date']) ? $_POST['Date'] : '';
                                                $cleaner = isset($_POST['Cleaner']) ? $_POST['Cleaner'] : '';
                                                $status = isset($_POST['Status']) ? $_POST['Status'] : '';
                                                $paymentStatus = isset($_POST['PaymentStatus']) ? $_POST['PaymentStatus'] : '';
                                                $conn->query("SET @current_user_branch = '" . $conn->real_escape_string($_SESSION['branch']) . "'");

                                                // Make condition for the SQL query based on filters
                                                $stmt_list = "SELECT b.*, c.name AS customer_name, c.phone_number, h.name AS house,
                                                              GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') AS cleaners,
                                                              GROUP_CONCAT(DISTINCT asv.name SEPARATOR ', ') AS services,
                                                              p.status AS payment_status,
                                                              f.rating AS rating,
                                                              f.comment AS comment,
                                                              bl.made_at, bl.made_by
                                                              FROM branch_booking b
                                                              JOIN CUSTOMER c ON b.customer_id = c.customer_id
                                                              JOIN HOUSE_TYPE h ON b.house_id = h.house_id
                                                              LEFT JOIN BOOKING_CLEANER bc ON b.booking_id = bc.booking_id
                                                              LEFT JOIN STAFF s ON bc.staff_id = s.staff_id
                                                              LEFT JOIN BOOKING_SERVICE bs ON b.booking_id = bs.booking_id
                                                              LEFT JOIN ADDITIONAL_SERVICE asv ON bs.service_id = asv.service_id
                                                              LEFT JOIN PAYMENT p ON p.booking_id = b.booking_id
                                                              LEFT JOIN FEEDBACK f ON f.booking_id = b.booking_id
                                                              LEFT JOIN (SELECT bl1.*
                                                                            FROM booking_log bl1
                                                                            INNER JOIN (
                                                                                SELECT booking_id, MAX(made_at) AS latest_log
                                                                                FROM booking_log
                                                                                GROUP BY booking_id
                                                                            ) bl2 ON bl1.booking_id = bl2.booking_id AND bl1.made_at = bl2.latest_log
                                                                        ) bl ON b.booking_id = bl.booking_id
                                                              WHERE 1=1";
                                                if (!empty($date)) {
                                                    $stmt_list .= " AND b.scheduled_date = '" . $conn->real_escape_string($date) . "'";
                                                }
                                                if (!empty($cleaner)) {
                                                    $stmt_list .= " AND s.name LIKE '%" . $conn->real_escape_string($cleaner) . "%'";
                                                }
                                                if (!empty($status)) {
                                                    $stmt_list .= " AND b.status = '" . $conn->real_escape_string($status) . "'";
                                                }
                                                if (!empty($paymentStatus)) {
                                                    $stmt_list .= " AND p.status = '" . $conn->real_escape_string($paymentStatus) . "'";
                                                }
                                                $stmt_list .= " GROUP BY b.booking_id ORDER BY CASE WHEN b.status = 'Attention' THEN 0 ELSE 1 END, b.scheduled_date DESC, b.scheduled_time DESC";
                                                $result = $conn->query($stmt_list);

                                                echo "<tr><td colspan='10'>" . $result->num_rows . " rows returned</td></tr>";
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        error_log("Booking ID: " . $row['booking_id'] . " Cleaners: " . $row['cleaners']);

                                                        // Determine row class based on status
                                                        $rowClass = ($row['status'] == 'Attention') ? 'table-danger' : '';

                                                        // Determine badge class for status
                                                        $statusClass = '';
                                                        if ($row['status'] == 'Completed') {
                                                            $statusClass = 'badge-success';
                                                        } elseif ($row['status'] == 'Cancelled') {
                                                            $statusClass = 'badge-danger';
                                                        } elseif ($row['status'] == 'Attention') {
                                                            $statusClass = 'text-danger';
                                                        } else {
                                                            $statusClass = 'badge-warning';
                                                        }

                                                        // Determine badge class for payment status
                                                        $paymentStatusClass = '';
                                                        if ($row['payment_status'] == 'Completed') {
                                                            $paymentStatusClass = 'badge-success';
                                                        } elseif ($row['payment_status'] == 'Cancelled') {
                                                            $paymentStatusClass = 'badge-danger';
                                                        } elseif ($row['payment_status'] == 'Attention') {
                                                            $paymentStatusClass = 'text-danger';
                                                        } else {
                                                            $paymentStatusClass = 'badge-warning';
                                                        }

                                                        $jsArgs = [
                                                            $row['booking_id'],
                                                            $row['customer_name'],
                                                            $row['phone_number'],
                                                            $row['house'],
                                                            $row['address'],
                                                            $row['hours_booked'],
                                                            $row['custom_request'] ?? '',
                                                            $row['scheduled_date'],
                                                            $row['scheduled_time'],
                                                            $row['estimated_duration_hour'],
                                                            $row['total_RM'],
                                                            $row['no_of_cleaners'],
                                                            $row['cleaners'],
                                                            $row['services'] ?? '',
                                                            $row['status'],
                                                            $row['payment_status'],
                                                            $row['rating'],
                                                            $row['comment'],
                                                            $row['note'] ?? '',
                                                            $row['made_by'] ?? '',
                                                            $row['made_at'] ?? ''
                                                        ];

                                                        // JSON encode each argument (to properly escape special chars)
                                                        $jsArgsEncoded = array_map(function ($arg) {
                                                            return json_encode($arg, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
                                                        }, $jsArgs);
                                                        $jsArgsString = implode(',', $jsArgsEncoded);

                                                        echo "<tr class='$rowClass'>
                                                            <td style='text-align: center;'>
                                                                <a class='ti-pencil-alt text-primary' style='text-decoration: none; cursor:pointer;' 
                                                                    onclick='openModal($jsArgsString)'></a>
                                                            </td>
                                                            <td style='text-align: center;'><span class='badge $statusClass'>" . htmlspecialchars($row["status"]) . "</span></td>
                                                            <td style='text-align: center;'><span class='badge $paymentStatusClass'>" . ($row["payment_status"] ?? 'Pending') . "</span></td>
                                                            <td>" . htmlspecialchars(date('d-m-Y', strtotime($row["scheduled_date"]))) . "</td>
                                                            <td>" . htmlspecialchars(date('H:i', strtotime($row["scheduled_time"]))) . "</td>
                                                            <td>" . htmlspecialchars($row["address"]) . "</td>
                                                            <td>" . htmlspecialchars($row["cleaners"]) . "</td>
                                                            <td>" . htmlspecialchars($row["estimated_duration_hour"]) . " hour</td>
                                                            <td>RM " . htmlspecialchars($row["total_RM"]) . "</td>
                                                        </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='9'>No booking found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details & Edit Modal -->
                    <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h4 class="modal-title" id="bookingModalLabel">Booking Details</h4>
                                    <form class="pt-3" id="bookingForm" method="POST" action="dbconnection/dbmanagebooking.php" onsubmit="return confirmAction(event)">

                                        <input type="hidden" name="BookingId" id="BookingId" value="">

                                        <!-- Customer Section -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <h5>Customer Information</h5>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Customer Name -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Customer Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="CustomerName" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Phone Number -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Phone Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="PhoneNumber" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- House Type -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">House Type</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="HouseType" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Address</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="Address" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Booking Section -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <h5>Booking Information</h5>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Hours Booked -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Number of Hours</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" id="HoursBooked" readonly>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-primary text-white">hours</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Custom Request -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Custom Request</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="CustomRequest" readonly>
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
                                                        <input type="text" class="form-control" id="Services" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cleaners -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Cleaners</label>
                                                    <div class="input-group col-sm-9">
                                                        <input type="text" class="form-control col-sm-2" name="NoOfCleaners" id="NoOfCleaners" readonly>
                                                        <input type="text" class="form-control" id="Cleaners" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                            </div>

                                            <!-- Cleaners -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Reassign Cleaners</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control select2" name="NewCleaners[]" id="NewCleaners" multiple disabled>
                                                            <!-- Options will be loaded dynamically -->
                                                        </select>
                                                        <small class="text-muted">Hold Ctrl to select multiple cleaners</small><br>
                                                        <small class="text-muted" id="availabilityStatus"></small>
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
                                                        <input type="text" class="form-control" id="ScheduledDate" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Time -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Scheduled Time</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="ScheduledTime" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Duration -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Estimated Duration</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="EstimatedDuration" readonly>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-primary text-white">hours</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Status -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Status</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="StatusModal" id="StatusModal">
                                                            <option value="Pending">Pending</option>
                                                            <option value="Completed">Completed</option>
                                                            <option value="Cancelled">Cancelled</option>
                                                            <option value="Attention" disabled>Attention</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payment Section -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <h5>Payment Information</h5>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Total Amount -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Total Amount</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-primary text-white">RM</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="TotalAmount" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Payment Status -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Payment Status</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="PaymentStatusModal" id="PaymentStatusModal">
                                                            <option value="Pending">Pending</option>
                                                            <option value="Completed">Completed</option>
                                                            <option value="Cancelled">Cancelled</option>
                                                            <option value="Attention" disabled>Attention</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <h5>More</h5>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Note -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Note</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="Note" id="Note">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Latest Update Information -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <small id="latestUpdate" class="text-muted">No updates made.</small>
                                            </div>
                                        </div><br>

                                        <div id="feedback">
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <h5>Feedback</h5>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Rating -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Rating</label>
                                                        <div class="col-sm-9">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="Rating" readonly>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text bg-primary text-white ti-star"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Comment -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Comment</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="Comment" id="Comment" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="row row-center">
                                            <button type="button" class="btn btn-dark mr-3" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" id="submitButton">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer"></footer>
            </div>
        </div>
    </div>

    <!-- Function Javascripts -->
    <script>
        // Helper function to format date as dd-mm-yyyy
        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        // Helper function to format time as H:i
        function formatTime(timeString) {
            const time = new Date('1970-01-01T' + timeString);
            const hours = String(time.getHours()).padStart(2, '0');
            const minutes = String(time.getMinutes()).padStart(2, '0');
            return `${hours}:${minutes}`;
        }

        // Helper function to format datetime as dd-mm-yyyy H:i
        function formatDateTime(dateTimeString) {
            const date = new Date(dateTimeString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${day}-${month}-${year} ${hours}:${minutes}`;
        }

        // Reset all filter dropdowns to their default state
        function resetFilters() {
            document.getElementById('Date').value = '';
            document.getElementById('Cleaner').value = '';
            document.getElementById('Status').selectedIndex = 0;
            document.getElementById('PaymentStatus').selectedIndex = 0;
            document.forms[0].submit();
        }

        $('#bookingModal').on('hidden.bs.modal', function() {
            // Clear any focused elements when modal closes
            if (document.activeElement) {
                document.activeElement.blur();
            }
        });

        // Open modal when button pressed
        function openModal(bookingId, customerName, phoneNumber, house, address, hoursBooked, customRequest, scheduledDate, scheduledTime, estimatedDuration, totalAmount, noOfCleaners, cleaners, services, status, paymentStatus, rating, comment, note, lastUpdatedBy, lastUpdateTime) {
            // Set all the values
            $('#CustomerName').val(customerName);
            $('#PhoneNumber').val(phoneNumber);
            $('#HouseType').val(house);
            $('#Address').val(address);
            $('#HoursBooked').val(hoursBooked);
            $('#NoOfCleaners').val(noOfCleaners);
            $('#CustomRequest').val(customRequest);
            $('#ScheduledDate').val(formatDate(scheduledDate));
            $('#ScheduledTime').val(formatTime(scheduledTime));
            $('#EstimatedDuration').val(estimatedDuration);
            $('#TotalAmount').val(totalAmount);
            $('#Cleaners').val(cleaners);
            $('#Services').val(services);
            $('#Note').val(note);
            $('#Rating').val(rating);
            $('#Comment').val(comment);
            $('#BookingId').val(bookingId);

            // Calculate estimated end time
            const scheduledDateTime = new Date(scheduledDate + 'T' + scheduledTime);
            const endTime = new Date(scheduledDateTime.getTime() + estimatedDuration * 60 * 60 * 1000);
            const currentTime = new Date();

            const statusSelect = document.getElementById('StatusModal');

            // Enable/disable reassignment based on status
            if (status === 'Pending') {
                checkCleanerAvailability(bookingId, scheduledDate, scheduledTime, estimatedDuration);
            } else {
                $('#NewCleaners').prop('disabled', true);
                $('#availabilityStatus').text('Cleaner reassignment only available for Pending bookings');
            }
            // If current time is before end time, disable "Completed" option
            if (currentTime < endTime) {
                Array.from(statusSelect.options).forEach(option => {
                    if (option.value === 'Completed') {
                        option.disabled = true;
                        option.title = "Cannot complete booking before estimated end time";
                    }
                });
            } else {
                Array.from(statusSelect.options).forEach(option => {
                    if (option.value === 'Completed') {
                        option.disabled = false;
                        option.title = "";
                    }
                });
            }

            statusSelect.value = status;
            const paymentStatusSelect = document.getElementById('PaymentStatusModal');
            paymentStatusSelect.value = paymentStatus;

            if (lastUpdatedBy && lastUpdateTime) {
                const formattedTime = formatDateTime(lastUpdateTime);
                $('#latestUpdate').text(`Latest update by ${lastUpdatedBy} at ${formattedTime}`);
            } else {
                $('#latestUpdate').text('No updates made.');
            }

            if (rating) {
                $('#feedback').show();
            } else {
                $('#feedback').hide();
            }

            $('#bookingModal').modal('show');
        }

        function checkCleanerAvailability(bookingId, date, time, duration) {
            $.ajax({
                url: '../customer/dbconnection/checkavailability.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    date: date,
                    time: time,
                    city: '<?php echo $_SESSION["branch"]; ?>',
                    estimatedDuration: duration
                }),
                success: function(response) {
                    console.log("Response from server:", response);
                    // jQuery automatically parses JSON, so use response directly
                    if (response.available > 0) {
                        loadAvailableCleaners(bookingId, date, time, duration);
                        $('#availabilityStatus').html('<span class="text-success">' + response.available + ' cleaners available</span>');
                    } else {
                        $('#NewCleaners').prop('disabled', true);
                        $('#availabilityStatus').html('<span class="text-danger">No cleaners available for this timeslot</span>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    $('#availabilityStatus').html('<span class="text-danger">Error checking availability</span>');
                }
            });
        }

        function loadAvailableCleaners(bookingId, date, time, duration) {
            $.ajax({
                url: 'dbconnection/getavailablecleaners.php',
                type: 'POST',
                dataType: 'json', // ← Add this to expect JSON
                data: {
                    bookingId: bookingId,
                    date: date,
                    time: time,
                    duration: duration,
                    city: '<?php echo $_SESSION["branch"]; ?>'
                },
                success: function(response) {
                    console.log("Available Cleaners Response:", response);
                    // jQuery automatically parses JSON, so use response directly
                    if (response.cleaners && response.cleaners.length > 0) {
                        response.cleaners.forEach(function(cleaner) {
                            var option = new Option(
                                cleaner.name + (cleaner.is_current ? ' (currently assigned)' : ''),
                                cleaner.staff_id,
                                false,
                                cleaner.is_current
                            );
                            $('#NewCleaners').append(option);
                        });


                        // Get the IDs of currently assigned cleaners
                        var currentCleanerIds = response.cleaners.filter(c => c.is_current).map(c => c.staff_id);


                        // Set the selected values
                        $('#NewCleaners').val(currentCleanerIds).trigger('change');
                        $('#NewCleaners').prop('disabled', false);


                        // Update availability status
                        var availableCount = response.cleaners.length;
                        $('#availabilityStatus').html('<span class="text-success">' + availableCount + ' cleaner' + (availableCount !== 1 ? 's' : '') + ' available</span>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    $('#availabilityStatus').html('<span class="text-danger">Error loading cleaners</span>');
                }
            });
        }

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select cleaners",
                width: '100%',
                dropdownParent: $('#bookingModal')
            });
        });

        // Action confirmation popup
        function confirmAction(event) {
            return confirm("Are you sure you want to update this booking?");
        }
    </script>

    <!-- javascript files -->
    <script src="../vendors/js/vendor.bundle.base.js"></script>
    <script src="../js/off-canvas.js"></script>
    <script src="../js/hoverable-collapse.js"></script>
    <script src="../js/template.js"></script>
    <script src="../js/settings.js"></script>
    <script src="../js/todolist.js"></script>
    <script src="../js/dashboard.js"></script>
</body>

</html>