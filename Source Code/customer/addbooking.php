<?php
session_start();
include('../dbconnection.php');

// Get house types directly in PHP
$houseTypes = [];
$stmt = $conn->prepare("SELECT * FROM HOUSE_TYPE");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $houseTypes[$row['house_id']] = $row;
}
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
                <a class="navbar-brand brand-logo mr-1" href="../index.php"><img src="..\images\HygieaHub logo.png" class="mr-1" alt="HygieiaHub logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
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
                            <h3 class="font-weight-bold">Book Your Service</h3>
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
                                                <!-- Address -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="Address">Address<span class="text-danger"> *</span></label>
                                                        <select class="form-control" name="AddressSelect" id="AddressSelect" required>
                                                            <option value="" disabled selected>Select an address</option>
                                                            <?php
                                                            // Fetch user's addresses
                                                            $address_stmt = $conn->prepare("SELECT * FROM customer_addresses WHERE customer_id = ? ORDER BY is_default DESC");
                                                            $address_stmt->bind_param("i", $_SESSION['customer_id']);
                                                            $address_stmt->execute();
                                                            $addresses = $address_stmt->get_result();

                                                            while ($address = $addresses->fetch_assoc()) {
                                                                $full_address = $address['address'] . ', ' . $address['city'] . ', ' . $address['state'];
                                                                $selected = ($address['is_default']) ? 'selected' : '';
                                                                echo '<option value="' . $address['address_id'] . '" data-house="' . $address['house_id'] . '" data-city="' . $address['city'] . '" ' . $selected . '>'
                                                                    . htmlspecialchars($address['address_label'] . ': ' . $full_address) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="City" id="City">
                                                <input type="hidden" name="HouseType" id="HouseType">

                                                <!-- House Type -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="HouseTypeDisplay">House Type<span class="text-danger"> *</span></label>
                                                        <input type="text" class="form-control" id="HouseTypeDisplay" value="" readonly>
                                                        <input type="hidden" name="HouseType" id="HouseType" value="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Hours Booked -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="HoursBooked">Number of Hours<span class="text-danger"> *</span></label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" name="HoursBooked" id="HoursBooked" min="1" max="8" step="0.5" onchange="validateTotalDuration()" required>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-primary text-white">hours</span>
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

                                            <br>
                                            <div class="row row-center">
                                                <p class="card-description">
                                                    <strong>Base Price: RM <span id="base-price">0.00</span></strong></br>
                                                    <strong>Base duration: <span id="base-duration">0</span> hour</strong>
                                                </p>
                                            </div>

                                            <br>
                                            <p class="card-description">
                                                <small class="text-muted">Our standard cleaning package covers essential cleaning tasks to keep your home fresh and tidy. This includes:<br>
                                                    ✔ Dusting & Wiping – Furniture, shelves, countertops, and surfaces<br>
                                                    ✔ Vacuuming & Mopping – Floors (hardwood, tiles, laminate)<br>
                                                    ✔ Bathroom Cleaning – Sinks, mirrors, countertops, and toilet surfaces<br>
                                                    ✔ Kitchen Cleaning – Countertops, stovetop (exterior), and sink<br>
                                                    ✔ Trash Removal – Emptying bins and replacing liners<br>
                                                    ✔ Light Organization – Straightening up common areas
                                                </small>
                                            </p>

                                            <input type="hidden" name="total" id="total" value="0">
                                            <input type="hidden" name="duration" id="duration" value="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional service selection -->
                                <div class="col-md-4 grid-margin">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Additional Services</h4>
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
                                                            <?php echo $service['name']; ?> - RM <?php echo $service['price_RM']; ?> - <?php echo $service['duration_hour']; ?> hour
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
                                                <strong>Total for Additional Services: RM <span id="additional-services-total">0.00</span></strong></br>
                                                <strong>Total estimated duration for Additional Services: <span id="additional-services-duration">0</span> hour</strong>
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
                                                        <option value="" disabled selected>Select date and time first</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-center">
                                <button type="button" class="btn btn-primary mr-2" id="calculateTotalBtn">Calculate Total</button>
                                <input type="reset" class="btn btn-light" value="Reset" onclick="resetForms()">
                            </div>
                        </form>

                        <div class="row ">
                            <!-- Modal for Total Calculation -->
                            <div class="modal fade" id="totalCalculationModal" tabindex="-1" role="dialog" aria-labelledby="totalCalculationModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="totalCalculationModalLabel">Booking Details</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p id="bookingDetails"></p>
                                            <p id="costDetails"></p>
                                            <hr>
                                            <ul>
                                                <li class="text-muted"><small class="form-text text-muted">Pay via Cash on Delivery (COD)</small></li>
                                                <li class="text-muted"><small class="form-text text-muted">Cancellation of booking can be made at least 24 hours before the scheduled date and time.</small></li>
                                                <li class="text-muted"><small class="form-text text-muted">Please contact +6019-9545506 for any inquiry.</small></li>
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
                                <p><a href="login.php" class="text-primary">Sign in</a> first to make a booking.</p>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <footer class="footer">
                    <a style="color: white;" href="../staff/login.php">For Staff</a>
                </footer>
            </div>
        </div>
    </div>

    <!-- Function Javascripts -->
    <script>
        // Available time slots (9 AM to 2 PM)
        const AVAILABLE_TIME_SLOTS = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00"];

        // Configuration
        const CONFIG = {
            serviceTaxRate: 0.06,
            maxDuration: 8
        };

        // Handle address selection changes
        document.getElementById('AddressSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const houseId = selectedOption.dataset.house;
            const city = selectedOption.dataset.city;

            // Update hidden fields
            document.getElementById('HouseType').value = houseId;
            document.getElementById('City').value = city; // This is critical for cleaner availability

            // Update house type display
            document.getElementById('HouseTypeDisplay').value = houseTypes[houseId].name;

            // Recalculate and update
            updateBasePrice();
            updateCleanerOptions(); // Update cleaners when address changes
        });

        // Function to update house type display
        function updateHouseTypeDisplay(houseId) {
            const houseType = houseTypes[houseId];
            if (houseType) {
                document.getElementById('HouseTypeDisplay').value = houseType.name;
                document.getElementById('HouseType').value = houseId;

                // Update minimum hours if needed
                document.getElementById('HoursBooked').min = houseType.min_hours;
                if (parseFloat(document.getElementById('HoursBooked').value) < houseType.min_hours) {
                    document.getElementById('HoursBooked').value = houseType.min_hours;
                }

                // Recalculate base price
                updateBasePrice();
            }
        }

        // Initialize house type on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial house type from first address (if available)
            const addressSelect = document.getElementById('AddressSelect');
            if (addressSelect && addressSelect.options.length > 1) {
                const selectedOption = addressSelect.options[addressSelect.selectedIndex];
                const initialHouseId = selectedOption.dataset.house;
                const initialCity = selectedOption.dataset.city;

                document.getElementById('HouseType').value = initialHouseId;
                document.getElementById('City').value = initialCity;
                document.getElementById('HouseTypeDisplay').value = houseTypes[initialHouseId].name;

                // Set initial hours
                document.getElementById('HoursBooked').min = houseTypes[initialHouseId].min_hours;
                document.getElementById('HoursBooked').value = houseTypes[initialHouseId].min_hours;
                updateBasePrice();
            }

            // Update when address changes
            addressSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                updateHouseTypeDisplay(selectedOption.dataset.house);
            });
        });

        // House types from PHP
        const houseTypes = <?php echo json_encode($houseTypes); ?>;

        // Resets all form fields and calculations
        function resetForms() {
            document.getElementById('bookingForm').reset();
            document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                checkbox.checked = false;
                const desc = document.getElementById(`desc_${checkbox.id.split('_')[1]}`);
                if (desc) desc.style.display = 'none';
            });
            updateAdditionalServices();
            updateBasePrice();
        }

        // Calculate additional services
        function calculateAdditionalServices() {
            let total = 0;
            let duration = 0;
            let services = [];

            document.querySelectorAll('.service-checkbox:checked').forEach(checkbox => {
                const price = parseFloat(checkbox.dataset.price);
                const serviceDuration = parseFloat(checkbox.dataset.duration);
                const serviceName = checkbox.parentElement.textContent.trim();

                total += price;
                duration += serviceDuration;

                // Push service details into the services array
                services.push({
                    name: serviceName,
                    price: price,
                    duration: serviceDuration
                });
            });

            document.getElementById('additional-services-total').textContent = total.toFixed(2);
            document.getElementById('additional-services-duration').textContent = duration.toFixed(1);

            return {
                total,
                duration,
                services
            };
        }

        // Calculate base price based on house type and hours
        function updateBasePrice() {
            const houseId = document.getElementById('HouseType').value;
            const hours = parseFloat(document.getElementById('HoursBooked').value) || 0;
            const houseType = houseTypes[houseId];

            if (houseType) {
                const basePrice = houseType.base_hourly_rate * hours;
                document.getElementById('base-price').textContent = basePrice.toFixed(2);
                document.getElementById('base-duration').textContent = hours.toFixed(1);
            }
        }

        // Calculate total duration (hours booked + additional services)
        function calculateTotalDuration() {
            const hoursBooked = parseFloat(document.getElementById('HoursBooked').value) || 0;
            const additionalDuration = calculateAdditionalServices().duration;
            return hoursBooked + additionalDuration;
        }

        // Update cleaner options based on availability
        async function updateCleanerOptions() {
            const date = document.getElementById('Date').value;
            const time = document.getElementById('Time').value;
            const city = document.getElementById('City').value;
            const select = document.getElementById('NoOfCleaners');

            // Clear existing options
            select.disabled = true;

            if (!date || !time || !city) {
                select.innerHTML = '<option value="" disabled selected>Select date and time first</option>';
                return;
            } else {
                select.innerHTML = '<option value="" disabled selected>Checking availability...</option>';
            }

            try {
                const totalDuration = calculateTotalDuration();
                const response = await checkAvailability(date, time, city, totalDuration);

                // Handle error response
                if (!response.success) {
                    select.innerHTML = '<option value="" disabled selected>Error: ' + response.error + '</option>';
                    return;
                }

                const availableCleaners = response.available;
                select.innerHTML = '<option value="" disabled selected>Select cleaners</option>';

                // Case 1: No cleaners available
                if (availableCleaners <= 0) {
                    select.innerHTML = '<option value="" disabled selected>No cleaners available</option>';
                    return;
                }

                // Case 2: Cleaners available
                select.disabled = false;
                for (let i = 1; i <= availableCleaners; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = i + (i === 1 ? ' cleaner' : ' cleaners');
                    select.appendChild(option);
                }
            } catch (error) {
                console.error('Error checking availability:', error);
                select.innerHTML = '<option value="" disabled selected>Error checking availability</option>';
            }
        }

        // Check cleaner availability
        async function checkAvailability(date, time, city, estimatedDuration) {
            try {
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

                // First check if response is OK
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                // Try to parse as JSON
                const data = await response.json();

                // Check if the response indicates success
                if (!data.success) {
                    throw new Error(data.error || 'Unknown server error');
                }

                return data;
            } catch (error) {
                console.error('Error checking availability:', error);
                // Return a safe default instead of throwing
                return {
                    success: false,
                    available: 0,
                    error: error.message
                };
            }
        }

        // Handle date selection
        async function handleDateChange() {
            const dateInput = document.getElementById('Date');
            const timeSelect = document.getElementById('Time');
            timeSelect.innerHTML = '<option value="" disabled selected>Select time</option>';
            timeSelect.disabled = true;

            if (!dateInput.value) return;

            // Date validation
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time part to midnight for accurate comparison

            const selectedDate = new Date(dateInput.value);
            selectedDate.setHours(0, 0, 0, 0); // Reset time part to midnight

            // Calculate one month from today
            const oneMonthFromNow = new Date();
            oneMonthFromNow.setMonth(oneMonthFromNow.getMonth() + 1);
            oneMonthFromNow.setHours(0, 0, 0, 0);

            // Check if date is within allowed range (1 day to 1 month in advance)
            if (selectedDate <= today) {
                alert("Bookings must be made at least 1 day in advance and in the future.");
                dateInput.value = '';
                return;
            } else if (selectedDate > oneMonthFromNow) {
                alert("Bookings cannot be made more than one month in advance.");
                dateInput.value = '';
                return;
            }

            // Populate time slots
            AVAILABLE_TIME_SLOTS.forEach(time => {
                timeSelect.appendChild(new Option(time, time));
            });
            timeSelect.disabled = false;
        }

        function validateTotalDuration() {
            const hoursBooked = parseFloat(document.getElementById('HoursBooked').value) || 0;
            const additionalServices = calculateAdditionalServices();
            const cleaners = parseInt(document.getElementById('NoOfCleaners').value) || 1;
            const totalDuration = (hoursBooked + additionalServices.duration) / cleaners;

            if (totalDuration > CONFIG.maxDuration) {
                alert(`Effective duration cannot exceed ${CONFIG.maxDuration} hours (currently ${totalDuration.toFixed(1)} hours).\n\nPlease reduce base hours or additional services`);
                document.getElementById('HoursBooked').value = Math.min(CONFIG.maxDuration - additionalServices.duration, CONFIG.maxDuration);
                updateBasePrice();

                const maxAllowedHours = (CONFIG.maxDuration * cleaners) - additionalServices.duration;
                document.getElementById('HoursBooked').value = Math.max(
                    houseTypes[document.getElementById('HouseType').value].min_hours, // Ensure it's not below minimum
                    Math.min(maxAllowedHours, CONFIG.maxDuration) // Cap at max duration
                ).toFixed(1);

                updateBasePrice();
                return false;
            }
            return true;
        }

        document.getElementById('NoOfCleaners').addEventListener('change', function() {
            validateTotalDuration();
        });

        // Calculate and display total
        function calculateAndDisplayTotal() {
            // Validate form first
            const form = document.getElementById('bookingForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Validate total duration (including cleaner consideration)
            if (!validateTotalDuration()) {
                return;
            }

            // Get booking details
            const houseId = document.getElementById('HouseType').value;
            const houseType = houseTypes[houseId];
            const hoursBooked = parseFloat(document.getElementById('HoursBooked').value) || 0;
            const cleaners = parseInt(document.getElementById('NoOfCleaners').value) || 1;

            // Calculate prices
            const baseHourlyRate = houseType.base_hourly_rate;
            const baseAmount = baseHourlyRate * hoursBooked
            const basePrice = baseAmount * cleaners;
            const additionalServices = calculateAdditionalServices();
            const subtotal = basePrice + additionalServices.total;
            const tax = subtotal * CONFIG.serviceTaxRate;
            const total = subtotal + tax;

            // Calculate duration
            const totalDuration = (hoursBooked + additionalServices.duration) / cleaners;

            if (totalDuration > CONFIG.maxDuration) {
                alert(`Error: Total booking duration cannot exceed ${CONFIG.maxDuration} hours. Current duration: ${totalDuration.toFixed(1)} hours.`);
                return;
            }

            // Update hidden form fields
            document.getElementById('total').value = total.toFixed(2);
            document.getElementById('duration').value = totalDuration.toFixed(2);

            // Format date for display
            const bookingDate = new Date(document.getElementById('Date').value);
            const formattedDate = bookingDate.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const addressSelect = document.getElementById('AddressSelect');
            const selectedAddressText = addressSelect.options[addressSelect.selectedIndex].text.split(': ')[1];

            // Show detailed invoice in modal
            document.getElementById('bookingDetails').innerHTML = `
                <strong>Booking Date:</strong> ${formattedDate}<br>
                <strong>Time:</strong> ${document.getElementById('Time').value}<br>
                <strong>Address:</strong> ${selectedAddressText}<hr>`;

            // Generate the invoice table
            let additionalServicesDetails = '';
            if (additionalServices.total > 0) {
                additionalServicesDetails = additionalServices.services.map(service => `
                <tr>
                    <td>${service.name}</td>
                    <td class="text-center">${service.duration.toFixed(1)} hours</td>
                    <td class="text-center">RM ${service.price.toFixed(2)}</td>
                    <td class="text-right">RM ${service.price.toFixed(2)}</td>
                </tr>
                `).join('');
            }

            document.getElementById('costDetails').innerHTML = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-center">Hours/Quantity</th>
                            <th class="text-center">Unit Price (RM)</th>
                            <th class="text-right">Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center font-weight-bold">Base</td>
                        </tr>
                        <tr>
                            <td>Base Service - ${houseType.name}</td>
                            <td class="text-center">${hoursBooked.toFixed(1)} hours</td>
                            <td class="text-center">RM ${baseHourlyRate}</td>
                            <td class="text-right">RM ${baseAmount.toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td>Number of Cleaners</td>
                            <td class="text-center">${cleaners} cleaner(s)</td>
                            <td class="text-center">-</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="3" class="font-weight-bold text-right">Subtotal (Base Service x Number of Cleaners)</td>
                            <td class="text-right font-weight-bold">RM ${basePrice.toFixed(2)}</td>
                        </tr>
                        ${additionalServicesDetails.length > 0 ? `
                        <tr>
                            <td colspan="4" class="text-center font-weight-bold">Additional Services</td>
                        </tr>
                        ${additionalServicesDetails}
                        <tr>
                            <td colspan="3" class="font-weight-bold text-right">Subtotal + Additional Service(s)</td>
                            <td class="text-right font-weight-bold">RM ${subtotal.toFixed(2)}</td>
                        </tr>
                        ` : ''}
                    </tbody>
                </table><br><br>

                <table class="table table-bordered">
                    </tbody>
                        <tr>
                            <td colspan="4" class="text-center font-weight-bold">Summary</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="font-weight-bold text-right">Service Tax (6%)</td>
                            <td class="text-right">RM ${tax.toFixed(2)}</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="3" class="font-weight-bold text-right">Total Amount</td>
                            <td class="text-right font-weight-bold">RM ${total.toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="font-weight-bold text-right">Estimated Total Duration</td>
                            <td class="text-right font-weight-bold">${totalDuration.toFixed(1)} hours</td>
                        </tr>
                    </tbody>
                </table>`;

            // Show modal
            $('#totalCalculationModal').modal('show');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial hours based on house type
            const houseId = document.getElementById('HouseType').value;
            if (houseId && houseTypes[houseId]) {
                const minHours = houseTypes[houseId].min_hours;
                document.getElementById('HoursBooked').min = minHours;
                document.getElementById('HoursBooked').value = minHours;
                updateBasePrice();
            }

            // Event listeners
            document.getElementById('HoursBooked').addEventListener('input', function() {
                updateBasePrice();
                updateCleanerOptions();
                validateTotalDuration();
            });

            document.getElementById('Date').addEventListener('change', handleDateChange);
            document.getElementById('Time').addEventListener('change', updateCleanerOptions);

            // Service checkbox changes
            document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const serviceId = this.id.split('_')[1];
                    const desc = document.getElementById(`desc_${serviceId}`);
                    if (desc) desc.style.display = this.checked ? 'block' : 'none';

                    calculateAdditionalServices();
                    updateCleanerOptions();
                    validateTotalDuration();
                });
            });

            // Calculate total button
            document.getElementById('calculateTotalBtn').addEventListener('click', calculateAndDisplayTotal);

            // Proceed with booking button
            document.getElementById('proceedBookingBtn').addEventListener('click', function() {
                if (confirm("Are you sure you want to confirm this booking?")) {
                    document.getElementById('bookingForm').submit();
                }
            });
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