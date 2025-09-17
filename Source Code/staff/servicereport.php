<?php
session_start();
include('../dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

// Set the user's branch for the view
$conn->query("SET @current_user_branch = '" . $conn->real_escape_string($_SESSION['branch']) . "'");

// Initialize variables
$periodicity = isset($_POST['Periodicity']) ? $_POST['Periodicity'] : 'Monthly';
$selected_date = isset($_POST['selected_date']) ? $_POST['selected_date'] : date('Y-m');
$chart_data = [];
$table_data = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $periodicity = $_POST['Periodicity'];
    $selected_date = $_POST['selected_date'];
}

// Get data based on periodicity
switch ($periodicity) {
    case 'Daily':
        $date_format = '%Y-%m-%d';
        $group_format = 'DATE(b.scheduled_date)';
        $default_date = date('Y-m-d');
        break;
    case 'Monthly':
        $date_format = '%Y-%m';
        $group_format = 'DATE_FORMAT(b.scheduled_date, "%Y-%m")';
        $default_date = date('Y-m');
        break;
    case 'Yearly':
        $date_format = '%Y';
        $group_format = 'YEAR(b.scheduled_date)';
        $default_date = date('Y');
        break;
    default:
        $date_format = '%Y-%m';
        $group_format = 'DATE_FORMAT(b.scheduled_date, "%Y-%m")';
        $default_date = date('Y-m');
}

// Get data for the chart (top 5 services)
$query = "SELECT 
            asv.service_id,
            asv.name AS service_name,
            COUNT(bs.service_id) AS service_count,
            SUM(asv.price_RM) AS total_revenue
          FROM additional_service asv
          LEFT JOIN booking_service bs ON asv.service_id = bs.service_id
          LEFT JOIN branch_booking b ON bs.booking_id = b.booking_id AND b.status = 'Completed'";

if ($periodicity === 'Daily') {
    $query .= " AND DATE(b.scheduled_date) = '" . $conn->real_escape_string($selected_date) . "'";
} elseif ($periodicity === 'Monthly') {
    $query .= " AND DATE_FORMAT(b.scheduled_date, '%Y-%m') = '" . $conn->real_escape_string($selected_date) . "'";
} elseif ($periodicity === 'Yearly') {
    $query .= " AND YEAR(b.scheduled_date) = '" . $conn->real_escape_string($selected_date) . "'";
}

$query .= " GROUP BY asv.service_id, asv.name
            ORDER BY service_count DESC
            LIMIT 5";

$result = $conn->query($query);

$chart_labels = [];
$chart_values = [];
while ($row = $result->fetch_assoc()) {
    $chart_labels[] = $row['service_name'];
    $chart_values[] = $row['service_count'];
}

// Get data for the table (all services)
$query = "SELECT 
            asv.service_id,
            asv.name AS service_name,
            COUNT(bs.service_id) AS service_count,
            SUM(asv.price_RM) AS total_revenue,
            AVG(f.rating) AS avg_rating
          FROM additional_service asv
          LEFT JOIN booking_service bs ON asv.service_id = bs.service_id
          LEFT JOIN branch_booking b ON bs.booking_id = b.booking_id AND b.status = 'Completed'
          LEFT JOIN feedback f ON b.booking_id = f.booking_id";

if ($periodicity === 'Daily') {
    $query .= " AND DATE(b.scheduled_date) = '" . $conn->real_escape_string($selected_date) . "'";
} elseif ($periodicity === 'Monthly') {
    $query .= " AND DATE_FORMAT(b.scheduled_date, '%Y-%m') = '" . $conn->real_escape_string($selected_date) . "'";
} elseif ($periodicity === 'Yearly') {
    $query .= " AND YEAR(b.scheduled_date) = '" . $conn->real_escape_string($selected_date) . "'";
}

$query .= " GROUP BY asv.service_id, asv.name
            ORDER BY service_count DESC";

$result = $conn->query($query);
$table_data = [];
while ($row = $result->fetch_assoc()) {
    $table_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HygieiaHub Administration</title>

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
                            <h3 class="font-weight-bold">Service Utilization Report</h3>
                            <h6 class="font-weight-normal mb-0">Branch: <?php echo htmlspecialchars($_SESSION['branch']); ?></h6>
                        </div>
                    </div>

                    <!-- Filtering -->
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card card-transparent">
                                <div class="card-body">
                                    <form class="form-inline" method="POST">
                                        <label class="mr-3">Search by :</label>

                                        <!-- Periodicity -->
                                        <select class="form-control form-control-sm mr-3" name="Periodicity" id="Periodicity" onchange="updateDateInput()">
                                            <option value="Daily" <?php echo $periodicity === 'Daily' ? 'selected' : ''; ?>>Daily</option>
                                            <option value="Monthly" <?php echo $periodicity === 'Monthly' ? 'selected' : ''; ?>>Monthly</option>
                                            <option value="Yearly" <?php echo $periodicity === 'Yearly' ? 'selected' : ''; ?>>Yearly</option>
                                        </select>

                                        <!-- Date input (changes based on periodicity) -->
                                        <?php if ($periodicity === 'Daily'): ?>
                                            <input type="date" class="form-control form-control-sm mr-3" name="selected_date" id="selected_date" value="<?php echo htmlspecialchars($selected_date); ?>">
                                        <?php elseif ($periodicity === 'Monthly'): ?>
                                            <input type="month" class="form-control form-control-sm mr-3" name="selected_date" id="selected_date" value="<?php echo htmlspecialchars($selected_date); ?>">
                                        <?php else: ?>
                                            <input type="number" class="form-control form-control-sm mr-3" name="selected_date" id="selected_date" min="2024" max="2030" value="<?php echo htmlspecialchars($selected_date); ?>">
                                        <?php endif; ?>

                                        <button type="submit" class="btn btn-primary btn-sm mr-3">Search</button>
                                        <button type="button" class="btn btn-light btn-sm" onclick="resetFilters()">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Chart -->
                        <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Top Services</h4>
                                    <canvas id="pieChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Stats -->
                        <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Service Summary</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center pb-2">
                                                <div class="dot-indicator bg-success mr-2"></div>
                                                <p class="mb-0">Total Services</p>
                                            </div>
                                            <h4 class="font-weight-semibold"><?php echo count($table_data); ?></h4>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center pb-2">
                                                <div class="dot-indicator bg-primary mr-2"></div>
                                                <p class="mb-0">Total Revenue</p>
                                            </div>
                                            <h4 class="font-weight-semibold">
                                                RM <?php
                                                    $total_revenue = 0;
                                                    foreach ($table_data as $row) {
                                                        $total_revenue += $row['total_revenue'];
                                                    }
                                                    echo number_format($total_revenue, 2);
                                                    ?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="row">
                            <!-- Table -->
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Service Utilization Details</h4>
                                        <div class="table-responsive pt-3">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Service Name</th>
                                                        <th>Times Booked</th>
                                                        <th>Total Revenue (RM)</th>
                                                        <!-- <th>Avg Rating</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (count($table_data) > 0): ?>
                                                        <?php foreach ($table_data as $row): ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                                                <td><?php echo htmlspecialchars($row['service_count']); ?></td>
                                                                <td><?php echo number_format($row['total_revenue'], 2); ?></td>
                                                                <!-- <td><?php echo $row['avg_rating'] ? number_format($row['avg_rating'], 1) : 'N/A'; ?></td> -->
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center">No data available for the selected period</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Print button -->
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card card-transparent">
                                <div class="card-body">
                                    <button type="button" class="btn btn-dark" onclick="window.print()">Print</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer"></footer>
            </div>
        </div>
    </div>

    <script>
        // Update date input based on periodicity selection
        function updateDateInput() {
            const periodicity = document.getElementById('Periodicity').value;
            const dateInputContainer = document.getElementById('selected_date').parentNode;

            let newInput;
            const currentValue = document.getElementById('selected_date').value;

            if (periodicity === 'Daily') {
                newInput = document.createElement('input');
                newInput.type = 'date';
                newInput.className = 'form-control form-control-sm mr-3';
                newInput.name = 'selected_date';
                newInput.id = 'selected_date';
                newInput.value = currentValue || new Date().toISOString().split('T')[0];
            } else if (periodicity === 'Monthly') {
                newInput = document.createElement('input');
                newInput.type = 'month';
                newInput.className = 'form-control form-control-sm mr-3';
                newInput.name = 'selected_date';
                newInput.id = 'selected_date';
                newInput.value = currentValue || new Date().toISOString().substring(0, 7);
            } else {
                newInput = document.createElement('input');
                newInput.type = 'number';
                newInput.className = 'form-control form-control-sm mr-3';
                newInput.name = 'selected_date';
                newInput.id = 'selected_date';
                newInput.min = '2024';
                newInput.max = '2030';
                newInput.value = currentValue || new Date().getFullYear();
            }

            dateInputContainer.replaceChild(newInput, document.getElementById('selected_date'));
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('Periodicity').value = 'Monthly';
            updateDateInput();
        }

        // Generating the pie chart
        document.addEventListener('DOMContentLoaded', function() {
            var pieData = {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($chart_values); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            if ($("#pieChart").length) {
                var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
                var pieChart = new Chart(pieChartCanvas, {
                    type: 'pie',
                    data: pieData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            }
        });
    </script>

    <!-- javascript files -->
    <script src="../vendors/js/vendor.bundle.base.js"></script>
    <script src="../js/off-canvas.js"></script>
    <script src="../js/hoverable-collapse.js"></script>
    <script src="../js/template.js"></script>
    <script src="../js/dashboard.js"></script>
    <script src="../vendors/chart.js/Chart.min.js"></script>
</body>

</html>