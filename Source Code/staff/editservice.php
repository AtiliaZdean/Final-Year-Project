<?php
session_start();
include('../dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch services from the database
$stmt_select = "SELECT s.service_id, s.name, s.description, s.price_RM, s.duration_hour, sl.made_at, sl.made_by 
                FROM additional_service s 
                LEFT JOIN (SELECT sl1.*
                                FROM additional_service_log sl1
                                INNER JOIN (SELECT service_id, MAX(made_at) AS latest_log
                                            FROM additional_service_log
                                            WHERE action = 'Update'
                                            GROUP BY service_id
                                           ) sl2 ON sl1.service_id = sl2.service_id AND sl1.made_at = sl2.latest_log
                          ) sl ON s.service_id = sl.service_id";
$result = $conn->query($stmt_select);
$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HygieiaHub Edit Service</title>

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
                            <h3 class="font-weight-bold">Service</h3>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Edit Form -->
                        <div class="col-8 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit Service</h4>
                                    <form class="forms-sample" action="dbconnection/dbeditservice.php" method="POST" onsubmit="return confirmAction(event)">

                                        <!-- Service name -->
                                        <div class="form-group">
                                            <label for="Name">Name<span class="text-danger"> *</span></label>
                                            <select class="form-control" name="Name" id="Name" required>
                                                <option value="" disabled selected hidden>Select a service</option>
                                                <?php foreach ($services as $service): ?>
                                                    <option value="<?php echo $service['service_id']; ?>" data-description="<?php echo htmlspecialchars($service['description']); ?>" data-price="<?php echo htmlspecialchars($service['price_RM']); ?>" data-duration="<?php echo htmlspecialchars($service['duration_hour']); ?>" data-updated-by="<?php echo htmlspecialchars($service['made_by'] ?? ''); ?>" data-updated-at="<?php echo htmlspecialchars($service['made_at'] ?? ''); ?>">
                                                        <?php echo htmlspecialchars($service['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Service ID -->
                                        <input type="hidden" name="service_id" id="service_id" value="">

                                        <!-- Description -->
                                        <div class="form-group">
                                            <label for="Description">Description</label>
                                            <textarea class="form-control" name="Description" id="Description" placeholder="Service Description" rows="4"></textarea>
                                        </div>

                                        <!-- Price -->
                                        <div class="form-group">
                                            <label for="Price">Price<span class="text-danger"> *</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-primary text-white">RM</span>
                                                </div>
                                                <input type="text" class="form-control" name="Price" id="Price" maxlength="6" placeholder="Service Price" required pattern="^\d+(\.\d{1,2})?$" onblur="formatPrice(this)">
                                            </div>
                                        </div>

                                        <!-- Duration -->
                                        <div class="form-group">
                                            <label for="Duration">Duration<span class="text-danger"> *</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="Duration" id="Duration" maxlength="5" placeholder="Service Duration" required pattern="^\d+(\.\d{1,2})?$" onblur="formatPrice(this)">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">hour</span>
                                                </div>
                                            </div>
                                        </div>

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

                                        <!-- Latest Update Information -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <small id="latestUpdate" class="text-muted" style="display: none;">No updates made.</small>
                                            </div>
                                        </div>

                                        <button type="submit" name="update" class="btn btn-primary mr-2">Update</button>
                                        <button type="submit" name="delete" class="btn btn-danger mr-2">Delete</button>
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

        // Fill in the data after service selected
        document.getElementById('Name').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const description = selectedOption.getAttribute('data-description');
            const price = selectedOption.getAttribute('data-price');
            const duration = selectedOption.getAttribute('data-duration');
            const serviceId = selectedOption.value;
            const updatedBy = selectedOption.getAttribute('data-updated-by');
            const updatedAt = selectedOption.getAttribute('data-updated-at');

            // Fill in the other fields
            document.getElementById('Description').value = description;
            document.getElementById('Price').value = price;
            document.getElementById('Duration').value = duration;
            document.getElementById('service_id').value = serviceId;

            // Show the latest update information
            const latestUpdateElement = document.getElementById('latestUpdate');
            latestUpdateElement.style.display = 'block';

            if (updatedBy && updatedAt) {
                // Format the date properly
                const formattedTime = formatDateTime(updatedAt);
                latestUpdateElement.textContent = `Latest update by ${updatedBy} at ${formattedTime}`;
            } else {
                latestUpdateElement.textContent = 'No updates made.';
            }
        });

        function formatPrice(input) {
            let value = input.value.replace(/[^0-9.]/g, '');

            // Check if the value has more than one decimal point
            const parts = value.split('.');

            if (parts.length > 2) {
                value = parts[0] + '.' + parts[1]; // Keep only the first decimal point
            }

            if (parts.length === 2) {
                if (parts[1].length === 0) {
                    value = parts[0] + '.00';
                } else if (parts[1].length > 2) {
                    value = parts[0] + '.' + parts[1].substring(0, 2);
                } else if (parts[1].length === 1) {
                    value = parts[0] + '.' + parts[1] + '0';
                }
            } else if (parts.length === 1) {
                value = parts[0] + '.00';
            }

            input.value = value; // Update the input value
        }

        // Prevent form submission if the input is invalid
        document.querySelector('form').addEventListener('submit', function(event) {
            const priceInput = document.getElementById('Price');
            const durationInput = document.getElementById('Duration');

            if (priceInput.value === '.00') {
                event.preventDefault();
                alert('Please enter a valid price.');
            }
            if (durationInput.value === '.00') {
                event.preventDefault();
                alert('Please enter a valid duration.');
            }
        });

        // Action confirmation popup
        function confirmAction(event) {
            // Check which button was clicked
            const updateButton = event.submitter.name === 'update';
            const deleteButton = event.submitter.name === 'delete';
            if (updateButton) {
                return confirm("Are you sure you want to update this service?");
            } else if (deleteButton) {
                return confirm("Are you sure you want to delete this service?");
            }
            return true;
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