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
    <title>HygieiaHub Manage Staff Account</title>

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
                            <h3 class="font-weight-bold">Staff</h3>
                        </div>
                    </div>

                    <!-- Filtering -->
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card card-transparent">
                                <div class="card-body">
                                    <form class="form-inline" method="POST">
                                        <label class="mr-3">Search by :</label>

                                        <!-- By role -->
                                        <!-- <select class="form-control form-control-sm mr-3" name="Role" id="Role">
                                            <option value="" disabled selected>Role</option>
                                            <option value="Admin">Admin</option>
                                            <option value="Cleaner">Cleaner</option>
                                        </select> -->

                                        <!-- By status -->
                                        <select class="form-control form-control-sm mr-4" name="Status" id="Status">
                                            <option value="" disabled selected>Status</option>
                                            <option value="Active">Active</option>
                                            <option value="In-Active">In-active</option>
                                        </select>

                                        <!-- Buttons -->
                                        <button type="submit" class="btn btn-primary btn-sm mr-3">Done</button>
                                        <button type="button" class="btn btn-light btn-sm" onclick="resetFilters()">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff List -->
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#staffModal" onclick="openModal('register')">Register New Staff</button>
                                    <div class="table-responsive pt-3">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">#</th>
                                                    <th style="text-align: center;">Name</th>
                                                    <th style="text-align: center;">Phone No.</th>
                                                    <th style="text-align: center;">Branch</th>
                                                    <th style="text-align: center;">Role</th>
                                                    <th style="text-align: center;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                include '../dbconnection.php';

                                                $role = isset($_POST['Role']) ? $_POST['Role'] : '';
                                                $status = isset($_POST['Status']) ? $_POST['Status'] : '';
                                                $conn->query("SET @current_user_branch = '" . $conn->real_escape_string($_SESSION['branch']) . "'");

                                                // Make condition for the SQL query based on filters
                                                $stmt_list = "SELECT s.*, sl.made_at, sl.made_by FROM branch_staff s 
                                                                LEFT JOIN (SELECT sl1.*
                                                                FROM staff_log sl1
                                                                INNER JOIN (
                                                                    SELECT staff_id, MAX(made_at) AS latest_log
                                                                    FROM staff_log
                                                                    WHERE action='Update'
                                                                    GROUP BY staff_id
                                                                    ) sl2 ON sl1.staff_id = sl2.staff_id AND sl1.made_at = sl2.latest_log
                                                                ) sl ON s.staff_id = sl.staff_id
                                                                WHERE 1=1 AND role = 'Cleaner'"; // Start with a base query
                                                /* if (!empty($role)) {
                                                    $stmt_list .= " AND role = '" . $conn->real_escape_string($role) . "'";
                                                } */
                                                if (!empty($status)) {
                                                    $stmt_list .= " AND status = '" . $conn->real_escape_string($status) . "'";
                                                }
                                                $result = $conn->query($stmt_list);

                                                echo "<tr><td colspan='6'>" . $result->num_rows . " rows returned</td></tr>";
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        // Determine badge class for status
                                                        $statusClass = '';
                                                        if ($row['status'] == 'Active') {
                                                            $statusClass = 'badge-success';
                                                        } else {
                                                            $statusClass = 'badge-danger';
                                                        }
                                                        
                                                        echo "<tr>
                                                            <td style='text-align: center;'>
                                                                <a class='ti-pencil-alt text-primary' style='text-decoration: none;' onclick=\"openModal('edit', '" . htmlspecialchars($row['staff_id']) . "', '" . htmlspecialchars($row['name']) . "', '" . htmlspecialchars($row['phone_number']) . "', '" . htmlspecialchars($row['branch']) . "', '" . htmlspecialchars($row['role']) . "', '" . htmlspecialchars($row['status']) . "', '" . htmlspecialchars($row['made_at']) . "', '" . htmlspecialchars($row['made_by']) . "')\"></a>
                                                            </td>
                                                            <td>" . htmlspecialchars($row["name"]) . "</td>
                                                            <td>" . htmlspecialchars($row["phone_number"]) . "</td>
                                                            <td>" . htmlspecialchars($row["branch"]) . "</td>
                                                            <td>" . htmlspecialchars($row["role"]) . "</td>
                                                            <td style='text-align: center;'><span class='badge $statusClass'>" . htmlspecialchars($row["status"]) . "</span></td>
                                                        </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='6'>No staff found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff Registration/Edit Modal -->
                    <div class="modal fade" id="staffModal" tabindex="-1" role="dialog" aria-labelledby="staffModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h4 class="modal-title" id="staffModalLabel">Register Staff</h4>

                                    <form class="pt-3" id="staffForm" method="POST" action="dbconnection/dbmanagestaff.php" onsubmit="return confirmAction(event)">
                                        <small class="form-text text-muted"><span class="text-danger">*</span> - required</small></br>

                                        <input type="hidden" name="StaffId" id="StaffId" value="">

                                        <!-- Role -->
                                        <div class="form-group" id="roleGroup1">
                                            <label for="Role1">Role<span class="text-danger"> *</span></label>
                                            <select class="form-control" name="Role1" id="Role1" required>
                                                <option value="" disabled selected>Role</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Cleaner">Cleaner</option>
                                            </select>
                                        </div>

                                        <!-- Name -->
                                        <div class="form-group">
                                            <label for="Name">Name<span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="Name" id="Name" autocomplete="name" placeholder="Full Name" required pattern="[A-Za-z\s]+" title="Only letters are allowed." oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group" id="emailGroup">
                                            <small class="form-text text-muted">Email and password are not required for cleaner.</small></br>
                                            <label for="Email">Email</label>
                                            <input type="email" class="form-control" name="Email" id="Email" placeholder="Email">
                                        </div>

                                        <!-- Password -->
                                        <div class="form-group" id="passwordGroup">
                                            <label for="Password">Password</label><!-- <i class="ti-info-alt text-muted"> -->
                                            <input type="password" class="form-control" name="Password" id="Password" placeholder="Password">
                                            <small class="form-text text-muted">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="form-group">
                                            <label for="PhoneNumber">Phone Number<span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="PhoneNumber" id="PhoneNumber" maxlength="10" placeholder="01xxxxxxxx" required pattern="[0-9]+" title="Only numbers are allowed." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        </div>

                                        <!-- Branch -->
                                        <div class="form-group" id="branchGroup1">
                                            <label for="Branch1">Branch</label>
                                            <input type="text" class="form-control" name="Branch1" id="Branch1" readonly>
                                        </div>

                                        <!-- Branch for edit -->
                                        <div class="form-group" id="branchGroup2" style="display: none;">
                                            <label for="Branch2">Branch</label>
                                            <select class="form-control" name="Branch2" id="Branch2">
                                                <option value="" disabled selected>Branch</option>
                                                <!-- Melaka -->
                                                <option value="Ayer Keroh">Ayer Keroh</option>
                                                <option value="Batu Berendam">Batu Berendam</option>
                                                <option value="Bukit Baru">Bukit Baru</option>
                                                <option value="Melaka City">Melaka City</option>
                                                <!-- Negeri Sembilan -->
                                                <option value="Seremban">Seremban</option>
                                                <option value="Port Dickson">Port Dickson</option>
                                                <option value="Nilai">Nilai</option>
                                                <option value="Tampin">Tampin</option>
                                            </select>
                                        </div>

                                        <!-- Role for edit -->
                                        <div class="form-group" id="roleGroup2" style="display: none;">
                                            <label for="Role2">Role</label>
                                            <input type="text" class="form-control" name="Role2" id="Role2" readonly>
                                        </div>

                                        <!-- Status -->
                                        <div class="form-group" id="statusGroup">
                                            <label for="Status">Status<span class="text-danger"> *</span></label>
                                            <select class="form-control" name="StatusModal" id="StatusModal">
                                                <option value="" disabled selected>Status</option>
                                                <option value="Active">Active</option>
                                                <option value="In-Active">In-Active</option>
                                            </select>
                                        </div>

                                        <!-- Latest Update Information -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <small id="latestUpdate" class="text-muted">No updates made.</small>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary" id="submitButton" name="register">Register</button>
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
        // Reset all filter dropdowns to their default state
        function resetFilters() {
            document.getElementById('Role').selectedIndex = 0;
            document.getElementById('Status').selectedIndex = 0;

            document.forms[0].submit();
        }

        $('#staffModal').on('hidden.bs.modal', function() {
            // Clear any focused elements when modal closes
            if (document.activeElement) {
                document.activeElement.blur();
            }
        });

        function openModal(action, id = '', name = '', phone = '', branch = '', role = '', status = '', lastUpdateTime = '', lastUpdatedBy = '') {
            const modalTitle = document.getElementById('staffModalLabel');
            const emailGroup = document.getElementById('emailGroup');
            const branchGroup1 = document.getElementById('branchGroup1');
            const branchGroup2 = document.getElementById('branchGroup2');
            const rolelGroup1 = document.getElementById('roleGroup1');
            const rolelGroup2 = document.getElementById('roleGroup2');
            const passwordGroup = document.getElementById('passwordGroup');
            const statusGroup = document.getElementById('statusGroup');
            const submitButton = document.getElementById('submitButton');
            const nameInput = document.getElementById('Name');
            const roleSelect = document.getElementById('Role1');
            const roleText = document.getElementById('Role2');
            const latestUpdate = document.getElementById('latestUpdate');

            if (action === 'edit') {
                modalTitle.textContent = 'Edit Staff';
                emailGroup.style.display = 'none'; // Hide email field for edit
                branchGroup1.style.display = 'none';
                branchGroup2.style.display = 'block';
                roleGroup1.style.display = 'none';
                roleGroup2.style.display = 'block';
                passwordGroup.style.display = 'none';
                statusGroup.style.display = 'block';
                latestUpdate.style.display = 'block';
                submitButton.textContent = 'Update';
                submitButton.setAttribute('name', 'update');

                // Populate fields with existing data
                document.getElementById('StaffId').value = id;
                nameInput.value = name;
                nameInput.readOnly = true;
                document.getElementById('PhoneNumber').value = phone;
                const branchSelect = document.getElementById('Branch2');
                branchSelect.value = branch;
                roleText.value = role;
                roleSelect.value = role;
                const statusSelect = document.getElementById('StatusModal');
                statusSelect.value = status;

                if (lastUpdatedBy && lastUpdateTime) {
                    const formattedTime = new Date(lastUpdateTime).toLocaleString();
                    $('#latestUpdate').text(`Latest update by ${lastUpdatedBy} at ${formattedTime}`);
                } else {
                    $('#latestUpdate').text('No updates made.');
                }
            } else {
                modalTitle.textContent = 'Register Staff';
                emailGroup.style.display = 'block'; // Show email field for register
                branchGroup1.style.display = 'block';
                branchGroup2.style.display = 'none';
                roleGroup1.style.display = 'block';
                roleGroup2.style.display = 'none';
                passwordGroup.style.display = 'block';
                statusGroup.style.display = 'none';
                submitButton.textContent = 'Register';
                submitButton.setAttribute('name', 'register');
                latestUpdate.style.display = 'none';

                // Clear fields for new registration
                nameInput.readOnly = false;
                document.getElementById('Name').value = '';
                document.getElementById('PhoneNumber').value = '';
                document.getElementById('Email').value = '';
                document.getElementById('Password').value = '';
                roleSelect.value = '';
                const branchText = document.getElementById('Branch1');
                branchText.value = "<?php echo $_SESSION['branch']; ?>";
            }

            // Show the modal
            $('#staffModal').modal('show');
        }

        // Action confirmation popup
        function confirmAction(event) {
            // Check which button was clicked
            const registerButton = event.submitter.name === 'register';
            const updateButton = event.submitter.name === 'update';

            if (registerButton) {
                return confirm("Are you sure you want to register this staff?");
            } else if (updateButton) {
                return confirm("Are you sure you want to update this staff's information?");
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
    <script src="../js/todolist.js"></script>
    <script src="../js/dashboard.js"></script>
</body>

</html>