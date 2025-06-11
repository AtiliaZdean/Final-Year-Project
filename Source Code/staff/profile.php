<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HygieiaHub Sign Up</title>

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
                            <form class="forms-sample" action="dbconnection/dbprofile.php" method="POST" onsubmit="return confirmAction(event)">

                                <!-- Service name -->
                                <div class="form-group">
                                    <label for="Name">Name<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" name="Name" id="Name" placeholder="Service Name" required>
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
                                            <span class="input-group-text bg-primary text-white">hour</span>
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
            citySelect.innerHTML = '<option value="" disabled selected hidden>City</option>';
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

        // Submit form
        function validateForm() {
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