<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
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
    <title>HygieiaHub Add Service</title>

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
                            </ul>
                        </div>
                    </li>

                    <!-- Manage Staff Account -->
                    <li class="nav-item">
                        <a class="nav-link" href="managestaff.php">
                            <span class="menu-title">Manage Staff Account</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- content -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="row">
                                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                    <h3 class="font-weight-bold">Service</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-8 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add Service</h4>
                                    <form class="forms-sample" action="dbconnection/dbaddservice.php" method="POST" onsubmit="return confirmAction(event)">

                                        <!-- Service name -->
                                        <div class="form-group">
                                            <label for="Name">Name<span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="Name" id="Name" placeholder="Service Name" required pattern="[A-Za-z\s]+" title="Only letters are allowed." oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                                        </div>

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

                                        <button type="submit" class="btn btn-primary mr-2">Add</button>
                                        <input type="reset" class="btn btn-light" value="Reset">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Function Javascripts -->
    <script>
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
            return confirm("Are you sure you want to add this service?");

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