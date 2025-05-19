<?php
session_start();
include('../dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
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
                        <div class="col-md-12 grid-margin">
                            <form class="form-inline" method="POST">
                                <!-- By role -->
                                <div class="form-group col-sm-3 mb-1">
                                    <label for="Role" class="col-sm col-form-label">Role :</label>
                                    <select class="form-control form-control-sm" name="Role" id="Role" onchange="changeInputColor()">
                                        <option value="" disabled selected>Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="cleaner">Cleaner</option>
                                    </select>
                                </div>

                                <!-- By branch -->
                                <div class="form-group col-sm-3 mb-1">
                                    <label for="Branch" class="col-sm col-form-label">Branch :</label>
                                    <select class="form-control form-control-sm" name="Branch" id="Branch" onchange="changeInputColor()">
                                        <option value="" disabled selected>Branch</option>
                                        <?php
                                        $stmt_branch_select = "SELECT DISTINCT branch FROM staff";
                                        $branch_result = $conn->query($stmt_branch_select);
                                        if ($branch_result && $branch_result->num_rows > 0) {
                                            while ($branch_row = $branch_result->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($branch_row['branch']) . "'>" . htmlspecialchars($branch_row['branch']) . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- By status -->
                                <div class="form-group col-sm-3 mb-1">
                                    <label for="Status" class="col-sm col-form-label">Status :</label>
                                    <select class="form-control form-control-sm" name="Status" id="Status" onchange="changeInputColor()">
                                        <option value="" disabled selected>Status</option>
                                        <option value="active">Active</option>
                                        <option value="in-active">In-active</option>
                                    </select>
                                </div>

                                <!-- Buttons -->
                                <div class="form-group col-sm-3 mb-1">
                                    <button type="submit" class="btn btn-dark mr-2 btn-sm">Done</button>
                                    <button type="button" class="btn btn-outline-dark btn-sm" onclick="resetFilters()">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Staff List -->
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#staffModal" onclick="openModal('register')">Register New Staff</button>
                                    <div class="table-responsive pt-3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">#</th>
                                                    <th style="text-align: center;">Name</th>
                                                    <th style="text-align: center;">Email</th>
                                                    <th style="text-align: center;">Phone No.</th>
                                                    <th style="text-align: center;">Branch</th>
                                                    <th style="text-align: center;">Role</th>
                                                    <th style="text-align: center;">Date Registered</th>
                                                    <th style="text-align: center;">Status</th>
                                                    <th style="text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                include '../dbconnection.php';

                                                $role = isset($_POST['Role']) ? $_POST['Role'] : '';
                                                $branch = isset($_POST['Branch']) ? $_POST['Branch'] : '';
                                                $status = isset($_POST['Status']) ? $_POST['Status'] : '';

                                                // Make condition for the SQL query based on filters
                                                $stmt_list = "SELECT * FROM staff WHERE 1=1"; // Start with a base query
                                                if (!empty($role)) {
                                                    $stmt_list .= " AND role = '" . $conn->real_escape_string($role) . "'";
                                                }
                                                if (!empty($branch)) {
                                                    $stmt_list .= " AND branch = '" . $conn->real_escape_string($branch) . "'";
                                                }
                                                if (!empty($status)) {
                                                    $stmt_list .= " AND status = '" . $conn->real_escape_string($status) . "'";
                                                }
                                                $result = $conn->query($stmt_list);

                                                if ($result->num_rows > 0) {
                                                    $i = 1;
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>
                                                            <td style='text-align: center;'>" . $i++ . "</td>
                                                            <td>" . htmlspecialchars($row["name"]) . "</td>
                                                            <td>" . htmlspecialchars($row["email"]) . "</td>
                                                            <td>" . htmlspecialchars($row["phone_number"]) . "</td>
                                                            <td>" . htmlspecialchars($row["branch"]) . "</td>
                                                            <td>" . htmlspecialchars($row["role"]) . "</td>
                                                            <td>" . htmlspecialchars(date('d-m-Y', strtotime($row["created_at"]))) . "</td>
                                                            <td>" . htmlspecialchars($row["status"]) . "</td>
                                                            <td style='text-align: center;'>
                                                                <button class='btn btn-dark btn-sm' onclick=\"openModal('edit', '" . htmlspecialchars($row['staff_id']) . "', '" . htmlspecialchars($row['name']) . "', '" . htmlspecialchars($row['phone_number']) . "', '" . htmlspecialchars($row['branch']) . "', '" . htmlspecialchars($row['role']) . "', '" . htmlspecialchars($row['status']) . "')\">Edit</button>
                                                            </td>
                                                        </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='9'>No staff found</td></tr>";
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
                                        <small class="form-text text-muted"><span class="text-danger">*</span> - required</small>

                                        <input type="hidden" name="StaffId" id="StaffId" value="">

                                        <!-- Name -->
                                        <div class="form-group">
                                            <label for="Name">Name<span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="Name" id="Name" autocomplete="name" placeholder="Full Name" required pattern="[A-Za-z\s]+" title="Only letters are allowed." oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group" id="emailGroup">
                                            <label for="Email">Email</label>
                                            <input type="email" class="form-control" name="Email" id="Email" placeholder="Email">
                                        </div>

                                        <!-- Password -->
                                        <div class="form-group" id="passwordGroup">
                                            <label for="Password">Password</label>
                                            <input type="password" class="form-control" name="Password" id="Password" placeholder="Password">
                                            <small class="form-text text-muted">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="form-group">
                                            <label for="PhoneNumber">Phone Number<span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="PhoneNumber" id="PhoneNumber" maxlength="10" placeholder="01xxxxxxxx" required pattern="[0-9]+" title="Only numbers are allowed." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        </div>

                                        <!-- Branch -->
                                        <div class="form-group">
                                            <label for="Branch">Branch<span class="text-danger"> *</span></label>
                                            <select class="form-control" name="Branch" id="BranchModal" required onchange="changeInputColor()">
                                                <option value="" disabled selected>Branch</option>
                                                <?php
                                                $stmt_branch_select = "SELECT DISTINCT branch FROM staff";
                                                $branch_result = $conn->query($stmt_branch_select);
                                                if ($branch_result && $branch_result->num_rows > 0) {
                                                    while ($branch_row = $branch_result->fetch_assoc()) {
                                                        echo "<option value='" . htmlspecialchars($branch_row['branch']) . "'>" . htmlspecialchars($branch_row['branch']) . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Role -->
                                        <div class="form-group">
                                            <label for="Role">Role<span class="text-danger"> *</span></label>
                                            <select class="form-control" name="Role" id="RoleModal" required onchange="changeInputColor()">
                                                <option value="" disabled selected>Role</option>
                                                <option value="admin">Admin</option>
                                                <option value="cleaner">Cleaner</option>
                                            </select>
                                        </div>

                                        <!-- Status -->
                                        <div class="form-group" id="statusGroup">
                                            <label for="Status">Status<span class="text-danger"> *</span></label>
                                            <select class="form-control" name="Status" id="StatusModal" onchange="changeInputColor()">
                                                <option value="" disabled selected>Status</option>
                                                <option value="active">Active</option>
                                                <option value="in-active">In-Active</option>
                                            </select>
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
        // Change input font color when selecting
        function changeInputColor() {
            // For filter section
            const selectRole = document.getElementById('Role');
            const selectBranch = document.getElementById('Branch');
            const selectStatus = document.getElementById('Status');

            // For modal section
            const selectRoleModal = document.getElementById('RoleModal');
            const selectBranchModal = document.getElementById('BranchModal');
            const selectStatusModal = document.getElementById('StatusModal');

            [selectRole, selectBranch, selectStatus, selectRoleModal, selectBranchModal, selectStatusModal].forEach(select => {
                if (select && select.value !== '') {
                    select.style.color = '#495057';
                } else if (select) {
                    select.style.color = '';
                }
            });
        }

        // Reset all filter dropdowns to their default state
        function resetFilters() {
            document.getElementById('Role').selectedIndex = 0;
            document.getElementById('Branch').selectedIndex = 0;
            document.getElementById('Status').selectedIndex = 0;

            changeInputColor();

            document.forms[0].submit();
        }

        $('#staffModal').on('hidden.bs.modal', function() {
            // Clear any focused elements when modal closes
            if (document.activeElement) {
                document.activeElement.blur();
            }
        });

        function openModal(action, id = '', name = '', phone = '', branch = '', role = '', status = '') {
            const modalTitle = document.getElementById('staffModalLabel');
            const emailGroup = document.getElementById('emailGroup');
            const passwordGroup = document.getElementById('passwordGroup');
            const statusGroup = document.getElementById('statusGroup');
            const submitButton = document.getElementById('submitButton');
            const nameInput = document.getElementById('Name');

            if (action === 'edit') {
                modalTitle.textContent = 'Edit Staff';
                emailGroup.style.display = 'none'; // Hide email field for edit
                passwordGroup.style.display = 'none';
                statusGroup.style.display = 'block';
                submitButton.textContent = 'Update';
                submitButton.setAttribute('name', 'update');

                // Populate fields with existing data
                document.getElementById('StaffId').value = id;
                nameInput.value = name;
                nameInput.readOnly = true;
                document.getElementById('PhoneNumber').value = phone;
                const branchSelect = document.getElementById('BranchModal');
                branchSelect.value = branch;
                const roleSelect = document.getElementById('RoleModal');
                roleSelect.value = role;
                const statusSelect = document.getElementById('StatusModal');
                statusSelect.value = status;
            } else {
                modalTitle.textContent = 'Register Staff';
                emailGroup.style.display = 'block'; // Show email field for register
                passwordGroup.style.display = 'block';
                statusGroup.style.display = 'none';
                submitButton.textContent = 'Register';
                submitButton.setAttribute('name', 'register');

                // Clear fields for new registration
                nameInput.readOnly = false;
                document.getElementById('Name').value = '';
                document.getElementById('PhoneNumber').value = '';
                document.getElementById('Email').value = '';
                document.getElementById('Password').value = '';
                document.getElementById('Branch').value = '';
                document.getElementById('Role').value = '';
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