<?php
session_start();

// Check if user is logged in
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

          <!-- Report -->
          <li class="nav-item">
            <a class="nav-link" href="report.php">
              <span class="menu-title">Report</span>
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
              <h3 class="font-weight-bold">Welcome, <?= htmlspecialchars($_SESSION['staffname']); ?> !</h3>
            </div>
          </div>

          <?php
          include('../dbconnection.php');

          // Set branch filter
          $conn->query("SET @current_user_branch = '" . $conn->real_escape_string($_SESSION['branch']) . "'");
          $today = date('Y-m-d');
          $yesterday = date('Y-m-d', strtotime('-1 day'));

          // 1. Today's Bookings Count
          $query = "SELECT COUNT(*) as count FROM BRANCH_BOOKING 
          WHERE scheduled_date = '$today'";
          $result = $conn->query($query);
          $today_bookings_count = $result->fetch_assoc()['count'];

          // Yesterday's Bookings Count for comparison
          $query = "SELECT COUNT(*) as count FROM BRANCH_BOOKING 
          WHERE scheduled_date = '$yesterday'";
          $result = $conn->query($query);
          $yesterday_bookings_count = $result->fetch_assoc()['count'];

          if ($yesterday_bookings_count == 0) {
            $booking_change = $today_bookings_count > 0 ? "New bookings today!" : "Same as yesterday";
          } else {
            $change = (($today_bookings_count - $yesterday_bookings_count) / $yesterday_bookings_count) * 100;
            $booking_change = round($change, 2) . "% vs yesterday";
          }

          // 2. Today's Revenue
          $query = "SELECT SUM(b.total_RM) as total FROM BRANCH_BOOKING b
          JOIN PAYMENT p ON b.booking_id = p.booking_id
          WHERE b.scheduled_date = '$today'
          AND p.status = 'Completed'";
          $result = $conn->query($query);
          $today_revenue = $result->fetch_assoc()['total'] ?? 0;

          // Yesterday's Revenue for comparison
          $query = "SELECT SUM(b.total_RM) as total FROM BRANCH_BOOKING b
          JOIN PAYMENT p ON b.booking_id = p.booking_id
          WHERE b.scheduled_date = '$yesterday'
          AND p.status = 'Completed'";
          $result = $conn->query($query);
          $yesterday_revenue = $result->fetch_assoc()['total'] ?? 0;

          if ($yesterday_revenue == 0) {
            $revenue_change = $today_revenue > 0 ? "New revenue today!" : "Same as yesterday";
          } else {
            $change = (($today_revenue - $yesterday_revenue) / $yesterday_revenue) * 100;
            $revenue_change = round($change, 2) . "% vs yesterday";
          }

          // 3. Pending Bookings
          $query = "SELECT COUNT(*) as count FROM BRANCH_BOOKING 
          WHERE status = 'Pending'";
          $result = $conn->query($query);
          $pending_bookings_count = $result->fetch_assoc()['count'];

          // 4. Available Cleaners
          $query = "SELECT COUNT(*) as count FROM BRANCH_STAFF 
          WHERE role = 'Cleaner' 
          AND status = 'Active'";
          $result = $conn->query($query);
          $available_cleaners_count = $result->fetch_assoc()['count'];

          // Total cleaners in branch
          $query = "SELECT COUNT(*) as count FROM BRANCH_STAFF
          WHERE role = 'Cleaner'";
          $result = $conn->query($query);
          $total_cleaners_count = $result->fetch_assoc()['count'];

          // 5. Weekly Revenue Data (last 7 days)
          $weekly_revenue = [];
          for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $day_name = date('D', strtotime($date));

            $query = "SELECT SUM(b.total_RM) as total FROM BRANCH_BOOKING b
              JOIN PAYMENT p ON b.booking_id = p.booking_id
              WHERE b.scheduled_date = '$date'
              AND p.status = 'Completed'";
            $result = $conn->query($query);
            $weekly_revenue[$day_name] = $result->fetch_assoc()['total'] ?? 0;
          }

          // 6. Service Popularity (branch-specific)
          $query = "SELECT s.name, COUNT(bs.service_id) as count 
          FROM BRANCH_BOOKING b
          JOIN BOOKING_SERVICE bs ON b.booking_id = bs.booking_id
          JOIN ADDITIONAL_SERVICE s ON bs.service_id = s.service_id
          GROUP BY s.name
          ORDER BY count DESC
          LIMIT 4";
          $result = $conn->query($query);
          $popular_services = $result->fetch_all(MYSQLI_ASSOC);
          ?>

          <!-- Metric Card -->
          <div class="row">
            <div class="col-md-12 grid-margin transparent">
              <div class="row">
                <!-- Today's Bookings -->
                <div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Today’s Bookings</p>
                      <p class="fs-30 mb-2"><?= $today_bookings_count ?></p> <!-- show how many -->
                      <p><?= $booking_change ?></p> <!-- show percentage (not sure per what) -->
                    </div>
                  </div>
                </div>

                <!-- Today's Revenue -->
                <div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Today's Revenue</p>
                      <p class="fs-30 mb-2">RM <?= number_format($today_revenue, 2) ?></p>
                      <p><?= $revenue_change ?></p>
                    </div>
                  </div>
                </div>

                <!-- Pending Bookings -->
                <div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Pending Bookings</p>
                      <p class="fs-30 mb-2"><?= $pending_bookings_count ?></p>
                      <p>Need attention</p>
                    </div>
                  </div>
                </div>

                <!-- Available Cleaners -->
                <div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Active Cleaners</p>
                      <p class="fs-30 mb-2"><?= $available_cleaners_count ?></p>
                      <p>Out of <?= $total_cleaners_count ?> total</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Chart -->
          <div class="row">
            <!-- Weekly Revenue Chart -->
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Weekly Revenue Trend</h4>
                  <canvas id="weeklyRevenueChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Service Popularity Chart -->
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Service Popularity</h4>
                  <canvas id="servicePopularityChart"></canvas>
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
    document.addEventListener('DOMContentLoaded', function() {
      // Weekly Revenue Chart
      if ($("#weeklyRevenueChart").length) {
        var ctx = $("#weeklyRevenueChart").get(0).getContext("2d");
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: <?php echo json_encode(array_keys($weekly_revenue)); ?>,
            datasets: [{
              label: 'Revenue (RM)',
              data: <?php echo json_encode(array_values($weekly_revenue)); ?>,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 2,
              fill: true,
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            plugins: {
              tooltip: {
                callbacks: {
                  label: function(context) {
                    return 'RM ' + context.parsed.y.toFixed(2);
                  }
                }
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  callback: function(value) {
                    return 'RM ' + value;
                  }
                }
              }
            }
          }
        });
      }

      // Service Popularity Chart
      if ($("#servicePopularityChart").length) {
        var ctx = $("#servicePopularityChart").get(0).getContext("2d");
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: <?php echo json_encode(array_column($popular_services, 'name')); ?>,
            datasets: [{
              data: <?php echo json_encode(array_column($popular_services, 'count')); ?>,
              backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)'
              ],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'right',
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    return context.label + ': ' + context.raw + ' bookings';
                  }
                }
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
  <script src="../js/settings.js"></script>
  <script src="../js/dashboard.js"></script>
  <script src="../vendors/chart.js/Chart.min.js"></script>
</body>

</html>