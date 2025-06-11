<?php
session_start();
include('../dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

// Define database credentials
$dbUser  = 'root';
$dbName = 'hygieiahub';
$backupDir = 'C:\xampp\htdocs\MyPHPSite\HygieiaHub\backup';

// Handle backup request
if (isset($_POST['Backup'])) {
    $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';
    $backupFile = $backupDir . '\\' . $dbName . '_' . date('d-m-Y_H-i-s') . '.sql';
    $command = "\"$mysqldumpPath\" --user={$dbUser} {$dbName} > \"{$backupFile}\" 2>&1";
    $output = shell_exec($command);
    
    if ($output === null) {
        $_SESSION['status'] = "Backup created successfully: " . basename($backupFile);
    } else {
        $_SESSION['EmailMessage'] = "Backup failed. Error: " . $output;
    }
    header("Location: maintenance.php");
    exit();
}

// Handle restore request
if (isset($_POST['Restore'])) {
    $mysqlPath = 'C:\xampp\mysql\bin\mysql.exe';
    $selectedFile = $_POST['RestoreFile'];
    $command = "\"$mysqlPath\" --user={$dbUser} {$dbName} < \"$selectedFile\" 2>&1";
    $output = shell_exec($command);
    
    if ($output === null) {
        $_SESSION['status'] = "Database restored successfully from: " . basename($selectedFile);
    } else {
        $_SESSION['EmailMessage'] = "Restore failed. Error: " . $output;
    }
    header("Location: maintenance.php");
    exit();
}

// Get list of backup files
$backupFiles = array_diff(scandir($backupDir), array('..', '.'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HygieiaHub Maintenance</title>

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
                            <h3 class="font-weight-bold">Maintenance</h3>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Backup and Recovery Section -->
                        <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td>Backup Database</td>
                                                        <td></td>
                                                        <td>
                                                            <button class="btn btn-dark btn-sm" type="submit" name="Backup">Backup</button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Restore Database</td>
                                                        <td>
                                                            <select class="form-control form-control-sm" name="RestoreFile" id="Restore">
                                                                <option value="" disabled selected>Select a backup file</option>
                                                                <?php foreach ($backupFiles as $file): ?>
                                                                    <option value="<?php echo $backupDir . '\\' . $file; ?>"><?php echo $file; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-dark btn-sm" type="submit" name="Restore">Restore</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

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

    <!-- javascript files -->
    <script src="../vendors/js/vendor.bundle.base.js"></script>
    <script src="../js/off-canvas.js"></script>
    <script src="../js/hoverable-collapse.js"></script>
    <script src="../js/template.js"></script>
    <script src="../js/settings.js"></script>
    <script src="../js/dashboard.js"></script>
</body>

</html>