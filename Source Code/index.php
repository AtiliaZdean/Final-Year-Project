<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HygieiaHub Home</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <!-- Header -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-1" href="index.php"><img src="images\HygieaHub logo.png" class="mr-1" alt="HygieiaHub logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <span class="menu-title">Home</span>
                        </a>
                    </li>

                    <!-- Community -->
                    <li class="nav-item">
                        <a class="nav-link" href="customer/community.php">
                            <span class="menu-title">Community</span>
                        </a>
                    </li>

                    <!-- Booking -->
                    <li class="nav-item">
                        <a class="nav-link" href="customer/addbooking.php">
                            <span class="menu-title">Book Now</span>
                        </a>
                    </li>

                    <?php
                    if (isset($_SESSION['customer_id'])) {
                    ?>
                        <!-- Booking List -->
                        <li class="nav-item">
                            <a class="nav-link" href="customer/viewbooking.php">
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
                        <a href="customer/login.php" class="btn btn-primary btn-md btn-margin">Sign In</a>
                    <?php
                    } else {
                    ?>
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <img src="images\profile picture.jpg" alt="profile" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="customer/profile.php">
                                    <i class="ti-user text-primary"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="customer/logout.php">
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
                <img src="images/banner.png" alt="banner">
                <!-- content -->
                <div class="content-wrapper">
                    <div class="row row-center">
                        <div class="col-md-12 col-center grid-margin">
                            <h2 class="font-weight-bold">Why us?</h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Trusted Professionals</h4>
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="card-text">All our cleaners are thoroughly vetted, background-checked, and trained in the latest cleaning techniques for your peace of mind.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Deep Clean Guarantee</h4>
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="card-text">We don’t just tidy—we sanitize. Using hospital-grade techniques to eliminate germs in high-touch areas.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Your Schedule, Your Rules</h4>
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="card-text">Book same-day or plan ahead. Easy cancellations and lock in your preferred time slot every visit.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer">
                    <a style="color: white;" href="staff/login.php">For Staff</a>
                </footer>
            </div>
        </div>
    </div>

    <!-- javascript files -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/dashboard.js"></script>
</body>

</html>