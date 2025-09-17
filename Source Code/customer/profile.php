<?php
session_start();
include('../dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user's data
$stmt = $conn->prepare("SELECT * FROM customer WHERE customer_id = ?");
$stmt->bind_param("i", $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch user's addresses
$address_stmt = $conn->prepare("SELECT * FROM customer_addresses WHERE customer_id = ? ORDER BY is_default DESC");
$address_stmt->bind_param("i", $_SESSION['customer_id']);
$address_stmt->execute();
$addresses = $address_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle setting default address
if (isset($_GET['set_default']) && is_numeric($_GET['set_default'])) {
    $address_id = $_GET['set_default'];

    // First, set all addresses to not default
    $reset_stmt = $conn->prepare("UPDATE customer_addresses SET is_default = FALSE WHERE customer_id = ?");
    $reset_stmt->bind_param("i", $_SESSION['customer_id']);
    $reset_stmt->execute();

    // Then set the selected address as default
    $set_stmt = $conn->prepare("UPDATE customer_addresses SET is_default = TRUE WHERE address_id = ? AND customer_id = ?");
    $set_stmt->bind_param("ii", $address_id, $_SESSION['customer_id']);
    $set_stmt->execute();

    header("Location: profile.php");
    exit();
}

// Handle address deletion
if (isset($_GET['delete_address']) && is_numeric($_GET['delete_address'])) {
    $address_id = $_GET['delete_address'];

    // Check if this is the last address (must keep at least one)
    $count_stmt = $conn->prepare("SELECT COUNT(*) as count FROM customer_addresses WHERE customer_id = ?");
    $count_stmt->bind_param("i", $_SESSION['customer_id']);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result()->fetch_assoc();

    if ($count_result['count'] > 1) {
        $delete_stmt = $conn->prepare("DELETE FROM customer_addresses WHERE address_id = ? AND customer_id = ?");
        $delete_stmt->bind_param("ii", $address_id, $_SESSION['customer_id']);
        $delete_stmt->execute();

        // If we deleted the default address, set another one as default
        $check_default_stmt = $conn->prepare("SELECT COUNT(*) as count FROM customer_addresses WHERE customer_id = ? AND is_default = TRUE");
        $check_default_stmt->bind_param("i", $_SESSION['customer_id']);
        $check_default_stmt->execute();
        $default_count = $check_default_stmt->get_result()->fetch_assoc()['count'];

        if ($default_count == 0) {
            $new_default_stmt = $conn->prepare("UPDATE customer_addresses SET is_default = TRUE WHERE customer_id = ? LIMIT 1");
            $new_default_stmt->bind_param("i", $_SESSION['customer_id']);
            $new_default_stmt->execute();
        }
    }

    header("Location: profile.php");
    exit();
}
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

<body onload="updateCities()">
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-5 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="..\images\HygieaHub logo.png" alt="HygieiaHub logo">
                            </div>
                            <h4 class="mb-4">Your Profile</h4>

                            <!-- Profile Update Form -->
                            <div>
                                <h5>Update Profile Information</h5>
                                <form class="pt-3" method="POST" action="dbconnection/dbregister.php" onsubmit="return confirmAction(event)">
                                    <input type="hidden" name="CustomerId" id="CustomerId" value="<?= htmlspecialchars($user['customer_id']); ?>">

                                    <!-- Name -->
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="Name" id="Name" placeholder="Full Name" value="<?= htmlspecialchars($user['name']); ?>" required pattern="[A-Za-z\s]+" title="Only letters are allowed." oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="PhoneNumber" id="PhoneNumber" maxlength="10" placeholder="Phone Number (01xxxxxxxx)" value="<?= htmlspecialchars($user['phone_number']); ?>" required pattern="[0-9]+" title="Only numbers are allowed." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="Email" id="Email" placeholder="Email" value="<?= htmlspecialchars($user['email']); ?>" required>
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="Password" id="Password" placeholder="Leave blank to keep current password">
                                        <small class="form-text text-muted">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
                                    </div>

                                    <?php
                                    // Success message
                                    if (isset($_SESSION['status'])) {
                                        echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">' . $_SESSION['status'] . '</div>';
                                        unset($_SESSION['status']);
                                    }

                                    // Error message
                                    if (isset($_SESSION['EmailMessage'])) {
                                        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">' . $_SESSION['EmailMessage'] . '</div>';
                                        unset($_SESSION['EmailMessage']);
                                    }
                                    ?>

                                    <button type="submit" class="btn btn-primary mr-2" name="update">Update</button>
                                    <a href="../index.php" class="btn btn-light">Back</a>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <!-- Address Management Section -->
                            <div class="mb-4">
                                <h4>Your Addresses</h4>
                                <?php if (empty($addresses)): ?>
                                    <p>No addresses found. Please add one below.</p>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($addresses as $address): ?>
                                            <div class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?= htmlspecialchars($address['address_label']) ?></strong>
                                                        <?php if ($address['is_default']): ?>
                                                            <span class="badge badge-primary ml-2">Default</span>
                                                        <?php endif; ?>
                                                        <div>
                                                            <?= htmlspecialchars($address['address']) ?>,
                                                            <?= htmlspecialchars($address['city']) ?>,
                                                            <?= htmlspecialchars($address['state']) ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <?php if (!$address['is_default']): ?>
                                                            <a href="profile.php?set_default=<?= $address['address_id'] ?>" class="btn btn-sm btn-outline-primary mb-2">Set Default</a>
                                                        <?php endif; ?>
                                                        <?php if (count($addresses) > 1): ?>
                                                            <a href="profile.php?delete_address=<?= $address['address_id'] ?>" class="btn btn-sm btn-outline-danger ml-1" onclick="return confirm('Are you sure you want to delete this address?')">Delete</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Add New Address Form -->
                            <div>
                                <h5>Add New Address</h5>
                                <form method="POST" action="dbconnection/dbregister.php">
                                    <input type="hidden" name="customer_id" value="<?= $_SESSION['customer_id'] ?>">

                                    <div class="form-group">
                                        <label for="address_label">Address Label (e.g., Home, Work)</label>
                                        <input type="text" class="form-control" name="address_label" id="address_label" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="HouseType">House Type</label>
                                        <select class="form-control" name="HouseType" id="HouseType" required>
                                            <option value="" disabled selected>House Type</option>
                                            <?php
                                            $sql = "SELECT house_id, name FROM HOUSE_TYPE";
                                            $result = $conn->query($sql);
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row["house_id"] . '">' . $row["name"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="Address">Address</label>
                                        <input type="text" class="form-control" name="Address" id="Address" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="State">State</label>
                                        <select class="form-control" name="State" id="State" required onchange="updateCities()">
                                            <option value="" disabled selected>State</option>
                                            <option value="Melaka">Melaka</option>
                                            <option value="Negeri Sembilan">Negeri Sembilan</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="City">City</label>
                                        <select class="form-control" name="City" id="City" required>
                                            <option value="" disabled selected>City</option>
                                        </select>
                                    </div>

                                    <div class="form-check form-check-flat form-check-primary">
                                        <label class="form-check-label" for="set_as_default">
                                            <input type="checkbox" class="form-check-input" name="set_as_default" id="set_as_default">Set as default address
                                        </label>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Add Address</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>

        <!-- Function Javascripts -->
        <script>
            // Generate city options based on state selected
            function updateCities() {
                const stateSelect = document.getElementById('State');
                const citySelect = document.getElementById('City');

                // Clear existing cities
                citySelect.innerHTML = '<option value="" disabled hidden>City</option>';
                const selectedState = stateSelect.value;
                let cities = [];

                if (selectedState === 'Melaka') {
                    cities = ['Ayer Keroh', 'Batu Berendam', 'Bukit Baru', 'Melaka City'];
                } else if (selectedState === 'Negeri Sembilan') {
                    cities = ['Seremban', 'Port Dickson', 'Nilai', 'Tampin'];
                }

                // Populate city dropdown
                cities.forEach(function(city) {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                });
            }

            // Action confirmation popup
            function confirmAction(event) {
                return confirm("Are you sure you want to update with these information?");
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