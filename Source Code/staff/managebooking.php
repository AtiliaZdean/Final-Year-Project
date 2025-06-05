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
                            <span class="menu-title">Manage Staff Account</span>
                        </a>
                    </li>

                    <!-- Manage Booking -->
                    <li class="nav-item">
                        <a class="nav-link" href="managebooking.php">
                            <span class="menu-title">Manage Booking</span>
                        </a>
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
                                        <select class="form-control form-control-sm mr-3" name="Status" id="Status" onchange="changeInputColor()">
                                            <option value="" disabled selected>Status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>

                                        <!-- By payment status -->
                                        <select class="form-control form-control-sm mr-4" name="PaymentStatus" id="PaymentStatus" onchange="changeInputColor()">
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
                    </div>

                    <!-- Booking List -->
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive pt-3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Address</th>
                                                    <th>Cleaners</th>
                                                    <th>Estimated Duration</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>Payment Status</th>
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
                                                $stmt_list = "SELECT b.*, c.name AS customer_name, c.phone_number, c.address AS customer_address, c.city, c.state,
                                                              GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') AS cleaners,
                                                              GROUP_CONCAT(DISTINCT asv.name SEPARATOR ', ') AS services,
                                                              p.status AS payment_status,
                                                              bl.made_at, bl.made_by
                                                              FROM branch_booking b
                                                              JOIN CUSTOMER c ON b.customer_id = c.customer_id
                                                              LEFT JOIN BOOKING_CLEANER bc ON b.booking_id = bc.booking_id
                                                              LEFT JOIN STAFF s ON bc.staff_id = s.staff_id
                                                              LEFT JOIN BOOKING_SERVICE bs ON b.booking_id = bs.booking_id
                                                              LEFT JOIN ADDITIONAL_SERVICE asv ON bs.service_id = asv.service_id
                                                              LEFT JOIN PAYMENT p ON p.booking_id = b.booking_id
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
                                                $stmt_list .= " GROUP BY b.booking_id ORDER BY b.scheduled_date DESC, b.scheduled_time DESC";
                                                $result = $conn->query($stmt_list);

                                                echo "<tr><td colspan='10'>" . $result->num_rows . " rows returned</td></tr>";
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        // Determine badge class for status
                                                        $statusClass = '';
                                                        if ($row['status'] == 'Completed') {
                                                            $statusClass = 'badge-success';
                                                        } elseif ($row['status'] == 'Cancelled') {
                                                            $statusClass = 'badge-danger';
                                                        } else {
                                                            $statusClass = 'badge-warning';
                                                        }

                                                        // Determine badge class for payment status
                                                        $paymentStatusClass = '';
                                                        if ($row['payment_status'] == 'Completed') {
                                                            $paymentStatusClass = 'badge-success';
                                                        } elseif ($row['payment_status'] == 'Cancelled') {
                                                            $paymentStatusClass = 'badge-danger';
                                                        } else {
                                                            $paymentStatusClass = 'badge-warning';
                                                        }

                                                        echo "<tr>
                                                            <td style='text-align: center;'>
                                                                <a class='ti-pencil-alt text-primary' style='text-decoration: none;' 
                                                                    onclick=\"openModal(
                                                                    '{$row['booking_id']}',
                                                                    '" . htmlspecialchars($row['customer_name'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['phone_number'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['customer_address'] . ', ' . $row['city'] . ', ' . $row['state'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['total_area_sqft'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['no_of_bedrooms'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['no_of_bathrooms'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['no_of_livingroooms'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['size_of_kitchen_sqft'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['pet'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['custom_request'] ?? '', ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['scheduled_date'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['scheduled_time'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['estimated_duration_hour'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['total_RM'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['no_of_cleaners'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['cleaners'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['services'] ?? '', ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['status'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['payment_status'], ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['note'] ?? '', ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['made_by'] ?? '', ENT_QUOTES) . "',
                                                                    '" . htmlspecialchars($row['made_at'] ?? '', ENT_QUOTES) . "'
                                                                )\"></a>
                                                            </td>
                                                            <td>" . htmlspecialchars($row["scheduled_date"]) . "</td>
                                                            <td>" . htmlspecialchars($row["scheduled_time"]) . "</td>
                                                            <td>" . htmlspecialchars($row["customer_address"] . ', ' . $row["city"] . ', ' . $row["state"]) . "</td>
                                                            <td>" . htmlspecialchars($row["cleaners"]) . "</td>
                                                            <td>" . htmlspecialchars($row["estimated_duration_hour"]) . " hour</td>
                                                            <td>RM " . htmlspecialchars($row["total_RM"]) . "</td>
                                                            <td style='text-align: center;'><span class='badge $statusClass'>" . htmlspecialchars($row["status"]) . "</span></td>
                                                            <td style='text-align: center;'><span class='badge $paymentStatusClass'>" . ($row["payment_status"] ?? 'Pending') . "</span></td>
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
                                            <!-- Total Area -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Total Area</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="TotalArea" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- No Of Bedrooms -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">No Of Bedrooms</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" class="form-control" id="NoOfBedrooms" readonly>
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
                                                        <input type="text" class="form-control" id="NoOfBathrooms" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- No Of Livingrooms -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">No Of Livingrooms</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="NoOfLivingrooms" readonly>
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
                                                        <input type="text" class="form-control" id="SizeOfKitchen" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Pet -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Pets</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="Pet" readonly>
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
                                                        <input type="text" class="form-control" id="CustomRequest" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Services -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Services</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="Services" readonly>
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
                                                        <input type="text" class="form-control col-sm-2" name="NoOfCleaners" id="NoOfCleaners" readonly>
                                                        <input type="text" class="form-control" id="Cleaners" readonly>
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
                                                        <input type="text" class="form-control" id="EstimatedDuration" readonly>
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
                                                        <input type="text" class="form-control" id="TotalAmount" readonly>
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
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Note -->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Note</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control col-sm-2" name="Note" id="Note">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Latest Update Information -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <small id="latestUpdate" class="text-muted">No updates made.</small>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary" id="submitButton">Update</button>
                                    </form>
                                </div>
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
        // Change input font color when selecting
        function changeInputColor() {
            const selects = document.querySelectorAll('select');
            selects.forEach(select => {
                if (select.value !== '') {
                    select.style.color = '#495057';
                } else {
                    select.style.color = '';
                }
            });
        }

        // Reset all filter dropdowns to their default state
        function resetFilters() {
            document.getElementById('Date').value = '';
            document.getElementById('Cleaner').value = '';
            document.getElementById('Status').selectedIndex = 0;
            document.getElementById('PaymentStatus').selectedIndex = 0;
            changeInputColor();
            document.forms[0].submit();
        }

        $('#staffModal').on('hidden.bs.modal', function() {
            // Clear any focused elements when modal closes
            if (document.activeElement) {
                document.activeElement.blur();
            }
        });

        // Open modal when button pressed
        function openModal(bookingId, customerName, phoneNumber, address, totalArea, noOfBedrooms, noOfBathrooms, noOfLivingrooms, sizeOfKitchen, pet, customRequest, scheduledDate, scheduledTime, estimatedDuration, totalAmount, noOfCleaners, cleaners, services, status, paymentStatus, note, lastUpdatedBy, lastUpdateTime) {
            // Set all the values
            $('#CustomerName').val(customerName);
            $('#PhoneNumber').val(phoneNumber);
            $('#Address').val(address);
            $('#TotalArea').val(totalArea + ' sqft');
            $('#NoOfBedrooms').val(noOfBedrooms);
            $('#NoOfBathrooms').val(noOfBathrooms);
            $('#NoOfLivingrooms').val(noOfLivingrooms);
            $('#SizeOfKitchen').val(sizeOfKitchen + ' sqft');
            $('#Pet').val(pet);
            $('#NoOfCleaners').val(noOfCleaners);
            $('#CustomRequest').val(customRequest);
            $('#ScheduledDate').val(scheduledDate);
            $('#ScheduledTime').val(scheduledTime);
            $('#EstimatedDuration').val(estimatedDuration + ' hours');
            $('#TotalAmount').val('RM ' + totalAmount);
            $('#Cleaners').val(cleaners);
            $('#Services').val(services);
            const statusSelect = document.getElementById('StatusModal');
            document.getElementById('StatusModal').value = status;
            const paymentStatusSelect = document.getElementById('PaymentStatusModal');
            paymentStatusSelect.value = paymentStatus;
            $('#Note').val(note);
            $('#BookingId').val(bookingId);

            if (lastUpdatedBy && lastUpdateTime) {
                const formattedTime = new Date(lastUpdateTime).toLocaleString();
                $('#latestUpdate').text(`Latest update by ${lastUpdatedBy} at ${formattedTime}`);
            } else {
                $('#latestUpdate').text('No updates made.');
            }

            $('#bookingModal').modal('show');
        }

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