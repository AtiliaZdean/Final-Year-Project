<?php
session_start();
include('../dbconnection.php');

// Calculate average rating
$avg_rating_query = "SELECT AVG(rating) as avg_rating FROM FEEDBACK";
$avg_result = $conn->query($avg_rating_query);
$avg_rating = $avg_result->fetch_assoc()['avg_rating'];

// Get all feedback with customer and booking details
$feedback_query = "SELECT 
                    f.feedback_id, 
                    f.rating, 
                    f.comment, 
                    f.submitted_at,
                    c.name as customer_name,
                    c.city as customer_city,
                    h.name as house_type,
                    b.scheduled_date,
                    b.no_of_cleaners,
                    GROUP_CONCAT(DISTINCT asv.name SEPARATOR ', ') AS services
                   FROM FEEDBACK f
                   JOIN BOOKING b ON f.booking_id = b.booking_id
                   JOIN CUSTOMER c ON b.customer_id = c.customer_id
                   JOIN HOUSE_TYPE h ON b.house_id = h.house_id
                   LEFT JOIN BOOKING_SERVICE bs ON b.booking_id = bs.booking_id
                   LEFT JOIN ADDITIONAL_SERVICE asv ON bs.service_id = asv.service_id
                   GROUP BY f.feedback_id
                   ORDER BY f.submitted_at DESC";
$feedback_result = $conn->query($feedback_query);
$feedbacks = $feedback_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HygieiaHub Community</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../vendors/feather/feather.css">
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="../images/favicon.png" />

    <style>
        .feedback-card {
            border-left: 4px solid #e0e0e0;
            margin-bottom: 20px;
            padding-left: 15px;
        }

        .customer-info {
            font-weight: 500;
        }

        .service-badge {
            color: #fff;
            background-color: #4B49AC;
            border-radius: 12px;
            padding: 3px 10px;
            margin-right: 5px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 5px;
        }

        .feedback-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .feedback-comment {
            margin: 10px 0;
            line-height: 1.5;
        }

        .avg-rating-display {
            font-size: 1.5rem;
        }
    </style>
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
                            <h3 class="font-weight-bold">Customer Feedback Community</h3>
                        </div>
                    </div>

                    <div class="row row-center">
                        <div class="col-md-12 col-center grid-margin">
                            <p class="text-muted">See what our customers say about our services</p>
                        </div>
                    </div>

                    <!-- Average Rating Card -->
                    <div class="row row-center">
                        <div class="col-md-8 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                        <div class="mb-3 mb-md-0">
                                            <h4 class="card-title">Overall Customer Satisfaction</h4>
                                            <p class="text-muted">Based on <?php echo count($feedbacks); ?> reviews</p>
                                        </div>
                                        <div class="text-center">
                                            <h1 class="display-4 mb-0 font-weight-bolder"><?php echo number_format($avg_rating, 1); ?></h1>
                                            <div class="star-rating rating-locked avg-rating-display" style="justify-content: center;">
                                                <?php
                                                $rounded_rating = round($avg_rating * 2) / 2; // Round to nearest 0.5
                                                for ($i = 5; $i >= 1; $i--) {
                                                    $checked = ($i <= $rounded_rating) ? 'checked' : '';
                                                    echo '<input type="radio" id="avg-star-' . $i . '" value="' . $i . '" ' . $checked . ' disabled />
                                                          <label for="avg-star-' . $i . '"><i class="ti-star"></i></label>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback List -->
                    <div class="row row-center">
                        <div class="col-md-8 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Recent Feedback</h4>

                                    <?php if (count($feedbacks) > 0): ?>
                                        <?php foreach ($feedbacks as $feedback): ?>
                                            <div class="feedback-card">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <span class="customer-info"><?php echo htmlspecialchars($feedback['customer_name']); ?></span>
                                                        <span class="text-muted">from <?php echo htmlspecialchars($feedback['customer_city']); ?></span>
                                                    </div>
                                                    <span class="feedback-date">
                                                        <?php echo date('d M Y', strtotime($feedback['submitted_at'])); ?>
                                                    </span>
                                                </div>

                                                <div class="star-rating rating-locked mt-2">
                                                    <?php
                                                    for ($i = 5; $i >= 1; $i--) {
                                                        $checked = ($i <= $feedback['rating']) ? 'checked' : '';
                                                        echo '<input type="radio" id="feedback-' . $feedback['feedback_id'] . '-star-' . $i . '" value="' . $i . '" ' . $checked . ' disabled />
                                                              <label for="feedback-' . $feedback['feedback_id'] . '-star-' . $i . '"><i class="ti-star"></i></label>';
                                                    }
                                                    ?>
                                                </div>

                                                <p class="feedback-comment"><?php echo htmlspecialchars($feedback['comment']); ?></p>

                                                <div class="d-flex flex-wrap">
                                                    <span class="service-badge">
                                                        <?php echo htmlspecialchars($feedback['house_type']); ?>
                                                    </span>
                                                    <span class="service-badge">
                                                        <?php echo $feedback['no_of_cleaners'] . ' cleaner' . ($feedback['no_of_cleaners'] > 1 ? 's' : ''); ?>
                                                    </span>
                                                    <?php if (!empty($feedback['services'])): ?>
                                                        <span class="service-badge">
                                                            <?php echo htmlspecialchars($feedback['services']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <span class="service-badge">
                                                        <?php echo date('d M Y', strtotime($feedback['scheduled_date'])); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <p>No feedback available yet. Be the first to share your experience!</p>
                                            <?php if (isset($_SESSION['customer_id'])): ?>
                                                <a href="viewbooking.php" class="btn btn-primary">Leave Feedback</a>
                                            <?php else: ?>
                                                <a href="login.php" class="btn btn-primary">Sign In to Leave Feedback</a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer"></footer>
    </div>

    <!-- javascript files -->
    <script src="../vendors/js/vendor.bundle.base.js"></script>
    <script src="../js/off-canvas.js"></script>
    <script src="../js/hoverable-collapse.js"></script>
    <script src="../js/template.js"></script>
    <script src="../js/settings.js"></script>
    <script src="../js/dashboard.js"></script>
</body>

</html>