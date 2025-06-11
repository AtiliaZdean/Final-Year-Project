<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HygieiaHub Booking</title>

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
                            <h3 class="font-weight-bold">Book Your Service</h3>
                        </div>
                    </div>

                    <?php
                    if (isset($_SESSION['customer_id'])) {
                    ?>
                        <form id="bookingForm" action="dbconnection/dbaddbooking.php" method="POST">
                            <!-- Booking Form -->
                            <div class="row">
                                <div class="col-md-8 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Date -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="Date">Date<span class="text-danger"> *</span></label>
                                                        <input type="date" class="form-control" name="Date" id="Date" required>
                                                    </div>
                                                </div>

                                                <!-- Time -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="Time">Time<span class="text-danger"> *</span></label>
                                                        <select type="time" class="form-control" name="Time" id="Time" required>
                                                            <option value="" disabled selected>Select a date first</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <?php
                                                $address = $_SESSION['address'] . ', ' . $_SESSION['city'] . ', ' . $_SESSION['state'];
                                                ?>
                                                <!-- Address -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="Address">Address</label>
                                                        <input type="text" class="form-control" name="Address" id="Address" value="<?php echo $address; ?>" readonly>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="City" id="City" value="<?php echo $_SESSION['city']; ?>">

                                                <!-- Total Area -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="TotalArea">Total Area<span class="text-danger"> *</span></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="TotalArea" id="TotalArea" required>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-primary text-white">sq ft</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <!-- Number of Bedrooms -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="NoOfBedrooms">Number Of Bedrooms<span class="text-danger"> *</span></label>
                                                        <select type="text" class="form-control" name="NoOfBedrooms" id="NoOfBedrooms" required>
                                                            <option value="" disabled selected>Select number of bedrooms</option>
                                                            <option value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Number of Bathrooms -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="NoOfBathrooms">Number Of Bathrooms<span class="text-danger"> *</span></label>
                                                        <select type="text" class="form-control" name="NoOfBathrooms" id="NoOfBathrooms" required>
                                                            <option value="" disabled selected>Select number of bathrooms</option>
                                                            <option value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <!-- Number of Livingrooms -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="NoOfLivingrooms">Number Of Livingrooms<span class="text-danger"> *</span></label>
                                                        <select type="text" class="form-control" name="NoOfLivingrooms" id="NoOfLivingrooms" required>
                                                            <option value="" disabled selected>Select number of livingrooms</option>
                                                            <option value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Size of Kitchen -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="SizeOfKitchen">Size Of Kitchen<span class="text-danger"> *</span></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="SizeOfKitchen" id="SizeOfKitchen" required>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-primary text-white">sq ft</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <!-- Pets -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="Pet">Do you have pet ?<span class="text-danger"> *</span></label>
                                                        <div class="form-group row">
                                                            <div class="col-sm-5">
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="Pet" id="PetNo" value="No"> No
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="Pet" id="PetYes" value="Yes">Yes
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Additional Request -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="AdditionalReq">Additional Request</label>
                                                        <textarea class="form-control" name="AdditionalReq" id="AdditionalReq" placeholder="We will consider your additional request" rows="4"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="total" id="total" value="0">
                                            <input type="hidden" name="duration" id="duration" value="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional service selection -->
                                <div class="col-md-4 grid-margin">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Services</h4>
                                            <div id="additional-services">
                                                <?php
                                                include('../dbconnection.php');
                                                $stmt_select = "SELECT * FROM additional_service";
                                                $result = $conn->query($stmt_select);
                                                while ($service = $result->fetch_assoc()) {
                                                ?>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="service_<?php echo $service['service_id']; ?>">
                                                            <input type="checkbox" class="form-check-input service-checkbox"
                                                                id="service_<?php echo $service['service_id']; ?>"
                                                                name="additional_services[]"
                                                                value="<?php echo $service['service_id']; ?>"
                                                                data-duration="<?php echo $service['duration_hour']; ?>"
                                                                data-price="<?php echo $service['price_RM']; ?>">
                                                            <?php echo $service['name']; ?> - RM <?php echo $service['price_RM']; ?>
                                                        </label>
                                                    </div>
                                                    <div class="service-description small p-2" id="desc_<?php echo $service['service_id']; ?>" style="display: none; margin-left: 29px; outline: 1px solid #f8f9fa; border-radius: 2px;">
                                                        <?php echo $service['description']; ?>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <br>
                                            <p class="card-description">
                                                <strong>Total for Additional Services: RM <span id="additional-services-total">0.00</span></strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 grid-margin">
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- Number of Cleaners -->
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="NoOfCleaners">Number Of Cleaners<span class="text-danger"> *</span></label>
                                                    <select type="text" class="form-control" name="NoOfCleaners" id="NoOfCleaners" required>
                                                        <option value="" disabled selected>Select number of cleaners</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-center">
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

                            <div class="row row-center">
                                <button type="button" class="btn btn-primary mr-2" id="calculateTotalBtn">Calculate Total</button>
                                <input type="reset" class="btn btn-light" value="Reset" onclick="resetForms()">
                            </div>
                        </form>

                        <div class="row ">
                            <!-- Modal for Total Calculation -->
                            <div class="modal fade" id="totalCalculationModal" tabindex="-1" role="dialog" aria-labelledby="totalCalculationModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="totalCalculationModalLabel">Booking Details</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p id="bookingDetails"></p>
                                            <p id="costDetails"></p>
                                            <ul>
                                                <li class="text-muted"><small class="form-text text-muted">Pay via Cash on Delivery (COD)</small></li>
                                                <li class="text-muted"><small class="form-text text-muted">Cancellation of booking can be made at least 24 hours before the scheduled date and time.</small></li>
                                                <li class="text-muted"><small class="form-text text-muted">Please contact +6012-3456789 for any inquiry.</small></li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <div>
                                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" id="proceedBookingBtn">Proceed with Booking</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="row row-center">
                            <div class="col-md-12 col-center grid-margin">
                                <p><a href="register.php" class="text-primary">Sign in</a> first to make a booking.</p>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <footer class="footer"></footer>
            </div>
        </div>
    </div>

    <!-- Function Javascripts -->
    <script>
        // Available time slots (9 AM to 2 PM)
        const AVAILABLE_TIME_SLOTS = [
            "09:00", "10:00", "11:00", "12:00", "13:00", "14:00"
        ];

        // Pricing configuration
        const PRICING = {
            areaRate: 0.15, // RM per sq ft
            bedroomRate: 10, // RM per bedroom
            bathroomRate: 8, // RM per bathroom
            livingroomRate: 6, // RM per living room
            kitchenRate: 0.12, // RM per sq ft (kitchen)
            petFee: 15, // RM if pet exists
            // cleanerHourlyRate: 70 // RM per hour per cleaner
        };

        // Duration configuration (in hours)
        const DURATION = {
            areaRate: 0.002, // hours per sq ft
            bedroomRate: 0.5, // hours per bedroom
            bathroomRate: 0.3, // hours per bathroom
            livingroomRate: 0.25, // hours per living room
            kitchenRate: 0.002, // hours per sq ft (kitchen)
            maxDuration: 8
        };

        // Service tax rate (6%)
        const SERVICE_TAX_RATE = 0.06;

        // Resets all form fields and calculations
        function resetForms() {
            // Reset main booking form
            document.getElementById('bookingForm').reset();

            // Reset additional services
            document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                checkbox.checked = false;
                const serviceId = checkbox.name.match(/\[(\d+)\]/)[1];
            });

            // Reset displays
            document.getElementById('additional-services-total').textContent = '0.00';
            document.getElementById('bookingDetails').innerHTML = '';
            document.getElementById('costDetails').innerHTML = '';
        }

        // Validates radio button selections (Pet)
        function validateRadioButtons() {
            const petSelected = document.querySelector('input[name="Pet"]:checked');

            if (!petSelected) {
                alert("Please select options for Pet");
                return false;
            }
            return true;
        }

        // Calculates the base price based on property details
        function calculateBasePrice() {
            const totalArea = parseFloat(document.getElementById('TotalArea').value) || 0;
            const noOfBedrooms = parseInt(document.getElementById('NoOfBedrooms').value) || 0;
            const noOfBathrooms = parseInt(document.getElementById('NoOfBathrooms').value) || 0;
            const noOfLivingrooms = parseInt(document.getElementById('NoOfLivingrooms').value) || 0;
            const sizeOfKitchen = parseFloat(document.getElementById('SizeOfKitchen').value) || 0;
            const hasPet = document.getElementById('PetYes').checked;

            return (PRICING.areaRate * totalArea) +
                (PRICING.bedroomRate * noOfBedrooms) +
                (PRICING.bathroomRate * noOfBathrooms) +
                (PRICING.livingroomRate * noOfLivingrooms) +
                (PRICING.kitchenRate * sizeOfKitchen) +
                (hasPet ? PRICING.petFee : 0);
        }

        // Calculates the base duration based on property details
        function calculateBaseDuration() {
            const totalArea = parseFloat(document.getElementById('TotalArea').value) || 0;
            const noOfBedrooms = parseInt(document.getElementById('NoOfBedrooms').value) || 0;
            const noOfBathrooms = parseInt(document.getElementById('NoOfBathrooms').value) || 0;
            const noOfLivingrooms = parseInt(document.getElementById('NoOfLivingrooms').value) || 0;
            const sizeOfKitchen = parseFloat(document.getElementById('SizeOfKitchen').value) || 0;
            const hasPet = document.getElementById('PetYes').checked;

            return (DURATION.areaRate * totalArea) +
                (DURATION.bedroomRate * noOfBedrooms) +
                (DURATION.bathroomRate * noOfBathrooms) +
                (DURATION.livingroomRate * noOfLivingrooms) +
                (DURATION.kitchenRate * sizeOfKitchen);
        }

        // Calculates the adjusted duration based on number of cleaners
        function calculateAdjustedDuration(baseDuration) {
            const noOfCleaners = parseInt(document.getElementById('NoOfCleaners').value) || 1;
            const adjustedDuration = baseDuration / noOfCleaners;
            return Math.max(1.0, adjustedDuration); // Minimum 1 hour
        }

        // Check if booking duration exceed the limit
        function validateBookingDuration(duration) {
            if (duration > DURATION.maxDuration) {
                alert(`Booking duration cannot exceed ${DURATION.maxDuration} hours. Please reduce the scope of work.`);
                return false;
            }
            return true;
        }

        // Calculates service tax
        function calculateServiceTax(subtotal) {
            return subtotal * SERVICE_TAX_RATE;
        }

        // Calculates total for additional services
        function calculateAdditionalServices() {
            let total = 0;
            let duration = 0;

            // Get all checked service checkboxes
            document.querySelectorAll('.service-checkbox:checked').forEach(checkbox => {
                const price = parseFloat(checkbox.dataset.price);
                const serviceDuration = parseFloat(checkbox.dataset.duration);

                total += price;
                duration += serviceDuration;
            });

            // Update the display
            document.getElementById('additional-services-total').textContent = total.toFixed(2);

            return {
                total,
                duration
            };
        }

        // Update the total when they're checked
        document.querySelectorAll('.service-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateTotalPrice();
            });
        });

        // Updates the total price display when quantities change
        function updateTotalPrice() {
            calculateAdditionalServices();
        }

        // Checks cleaner availability for a date/time
        async function checkAvailability(date, time, city, estimatedDuration = 1.0) {
            const response = await fetch('dbconnection/checkavailability.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    date,
                    time,
                    city,
                    estimatedDuration
                })
            });
            return response.json();
        }

        // Handles date selection changes
        async function handleDateChange() {
            const selectedDate = document.getElementById('Date').value;
            const city = document.getElementById('City').value;
            const timeSelect = document.getElementById('Time');
            const noOfCleanersSelect = document.getElementById('NoOfCleaners');

            timeSelect.innerHTML = '';
            timeSelect.disabled = true;

            if (!selectedDate) {
                timeSelect.innerHTML = '<option value="">Select a date first</option>';
                return;
            }

            // Get today's date
            const today = new Date();
            const selected = new Date(selectedDate);

            // Check if the selected date is today or in the past
            if (selected < today) {
                alert("The selected date cannot be in the past.");
                return;
            }
            
            // Check if the selected date is today
            if (selected.toDateString() === today.toDateString()) {
                alert("The selected date cannot be today. Please choose a future date.");
                return;
            }

            // Check if weekend
            const dayOfWeek = new Date(selectedDate).getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                alert("Booking not available on weekends.");
                return;
            }

            // Check availability
            const availableCleaners = await checkAvailability(selectedDate, '', city);
            if (availableCleaners.length === 0) {
                alert("No available slots on this date.");
                return;
            }

            // Populate time slots
            AVAILABLE_TIME_SLOTS.forEach(time => {
                const option = document.createElement("option");
                option.value = time;
                option.textContent = time;
                timeSelect.appendChild(option);
            });

            timeSelect.disabled = false;
        }

        //Handles time selection changes
        async function handleTimeChange() {
            const date = document.getElementById('Date').value;
            const time = document.getElementById('Time').value;
            const city = document.getElementById('City').value;
            const baseDuration = calculateBaseDuration();
            const adjustedDuration = calculateAdjustedDuration(baseDuration);

            const response = await fetch('dbconnection/checkavailability.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    date,
                    time,
                    city,
                    estimatedDuration: adjustedDuration
                })
            });

            const data = await response.json();
            const cleanersSelect = document.getElementById('NoOfCleaners');

            // Clear previous options
            cleanersSelect.innerHTML = '<option value="" disabled selected>Select number of cleaners</option>';

            // Add available options (1 to available count, max 4)
            const maxCleaners = Math.min(data.available, 4);
            for (let i = 1; i <= maxCleaners; i++) {
                const option = document.createElement("option");
                option.value = i;
                option.textContent = i;
                cleanersSelect.appendChild(option);
            }
        }

        // Calculates and displays the total booking cost
        function calculateAndDisplayTotal() {
            const form = document.getElementById('bookingForm');
            if (!form.checkValidity()) {
                alert("Please fill in all required fields.");
                return;
            }

            // Calculate all components
            const additionalServices = calculateAdditionalServices();
            const basePrice = calculateBasePrice();
            const baseDuration = calculateBaseDuration() + additionalServices.duration;
            const adjustedDuration = calculateAdjustedDuration(baseDuration);

            // Validate duration
            if (!validateBookingDuration(adjustedDuration)) {
                return;
            }

            const serviceTax = calculateServiceTax(basePrice + additionalServices.total);
            const finalTotal = basePrice + additionalServices.total + serviceTax;

            // Update hidden fields
            document.getElementById('total').value = finalTotal.toFixed(2);
            document.getElementById('duration').value = adjustedDuration.toFixed(2);

            const formattedDate = new Date(document.getElementById('Date').value).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            // Display in modal
            document.getElementById('bookingDetails').innerHTML = `
                <strong>Date: ${formattedDate}</strong><br>
                <strong>Time: ${document.getElementById('Time').value}</strong><br><br>
                Cleaners: ${document.getElementById('NoOfCleaners').value}<hr>
            `;

            document.getElementById('costDetails').innerHTML = `
                Base Price: RM ${basePrice.toFixed(2)}<br>
                Additional Services: RM ${additionalServices.total.toFixed(2)}<br>
                Service Tax (6%): RM ${serviceTax.toFixed(2)}<br><hr>
                <strong>Final Total: RM ${finalTotal.toFixed(2)}</strong><br>
                <strong>Estimated Duration: ${adjustedDuration.toFixed(2)} hours</strong>
            `;

            $('#totalCalculationModal').modal('show');
        }

        // Show/hide service description when checkbox is checked/unchecked
        document.querySelectorAll('.service-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const serviceId = this.id.split('_')[1];
                const description = document.getElementById(`desc_${serviceId}`);

                if (this.checked) {
                    description.style.display = 'block';
                } else {
                    description.style.display = 'none';
                }

                updateTotalPrice();
            });
        });

        // Form submission handler
        document.getElementById('bookingForm').addEventListener('submit', function(event) {
            if (!this.checkValidity() || !validateRadioButtons() || !validateAdditionalServices()) {
                event.preventDefault();
                event.stopPropagation();
                alert("Please fill in all required fields.");
            }
            this.classList.add('was-validated');
        });

        // Date and time selection handlers
        document.getElementById('Date').addEventListener('input', handleDateChange);
        document.getElementById('Time').addEventListener('change', handleTimeChange);

        // Button handlers
        document.getElementById('calculateTotalBtn').addEventListener('click', calculateAndDisplayTotal);
        document.getElementById('proceedBookingBtn').addEventListener('click', function() {
            if (confirm("Are you sure you want to proceed with this booking?")) {
                document.getElementById('bookingForm').submit();
            } else {
                // Keep the modal open if user cancels
                $('#totalCalculationModal').modal('show');
            }
        });
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