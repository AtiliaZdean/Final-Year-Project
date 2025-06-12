<?php
session_start();
include('../dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

$conn->query("SET @current_user_branch = '" . $conn->real_escape_string($_SESSION['branch']) . "'");

// Fetch user's data
$stmt = $conn->prepare("SELECT * FROM branch_staff WHERE staff_id = ?");
$stmt->bind_param("s", $_SESSION['staff_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HygieiaHub Profile</title>

    <!-- css files -->
    <link rel="stylesheet" href="..\vendors\feather\feather.css">
    <link rel="stylesheet" href="..\vendors\ti-icons\css\themify-icons.css">
    <link rel="stylesheet" href="..\vendors\css\vendor.bundle.base.css">
    <link rel="stylesheet" href="..\css\vertical-layout-light\style.css">
    <link rel="shortcut icon" href="..\images\favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-5 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="..\images\HygieaHub logo.png" alt="HygieiaHub logo">
                            </div>
                            <h4>Your Profile</h4>
                            <!-- Form section -->
                            <form class="forms-sample" action="dbconnection/dbmanagestaff.php" method="POST" onsubmit="return confirmAction(event)">

                                <input type="hidden" name="StaffId" id="StaffId" value="<?= htmlspecialchars($user['staff_id']); ?>">
                                <input type="hidden" name="Role" id="Role" value="<?= htmlspecialchars($user['role']); ?>">

                                <!-- Name -->
                                <div class="form-group">
                                    <label for="Name">Name</label>
                                    <input type="text" class="form-control" name="Name" id="Name" value="<?= htmlspecialchars($user['name']); ?>" required>
                                </div>

                                <!-- Phone Number -->
                                <div class="form-group">
                                    <label for="PhoneNumber">Phone Number</label>
                                    <input type="text" class="form-control" name="PhoneNumber" id="PhoneNumber" maxlength="10" value="<?= htmlspecialchars($user['phone_number']); ?>" required pattern="[0-9]+" title="Only numbers are allowed." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>

                                <!-- Email -->
                                <div class="form-group" id="emailGroup">
                                    <label for="Email">Email</label>
                                    <input type="email" class="form-control" name="Email" id="Email" value="<?= htmlspecialchars($user['email']); ?>" required>
                                </div>

                                <!-- Password -->
                                <div class="form-group" id="passwordGroup">
                                    <label for="Password">Password</label><!-- <i class="ti-info-alt text-muted"> -->
                                    <input type="password" class="form-control" name="Password" id="Password" placeholder="Leave blank to keep current password">
                                    <small class="form-text text-muted">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
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

                                <button type="submit" class="btn btn-primary mr-2" name="update">Update</button>
                                <a href="dashboard.php" class="btn btn-light">Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Function Javascripts -->
    <script>
        // Action confirmation popup
        function confirmAction(event) {
            return confirm("Are you sure you want to update your profile?");

            return true;
        }
    </script>

    <!-- javascript files -->
    <script src="..\vendors\js\vendor.bundle.base.js"></script>
    <script src="..\js\off-canvas.js"></script>
    <script src="..\js\hoverable-collapse.js"></script>
    <script src="..\js\template.js"></script>
    <script src="..\js\settings.js"></script>
    <script src=" ..\js\todolist.js"></script>
</body>

</html>