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
              <h4>Sign Up</h4>
              <!-- Form section -->
              <form class="pt-3" method="POST" action="dbconnection\dbregister.php" onsubmit="return confirmAction(event)">

                <!-- Name -->
                <div class="form-group">
                  <input type="text" class="form-control" name="Name" id="Name" placeholder="Full Name" required pattern="[A-Za-z\s]+" title="Only letters are allowed." oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                  <input type="text" class="form-control" name="PhoneNumber" id="PhoneNumber" maxlength="10" placeholder="Phone Number (01xxxxxxxx)" required pattern="[0-9]+" title="Only numbers are allowed." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <!-- House Type -->
                <div class="form-group">
                  <select class="form-control" name="HouseType" id="HouseType" required>
                    <option value="" disabled selected>House Type</option>
                    <?php
                    include('../dbconnection.php');

                    // Fetch house types from HOUSE_TYPE table
                    $sql = "SELECT house_id, name FROM HOUSE_TYPE";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                      echo '<option value="' . $row["house_id"] . '">' . $row["name"] . '</option>';
                    }

                    $conn->close();
                    ?>
                  </select>
                </div>

                <!-- Address -->
                <div class="form-group">
                  <input type="text" class="form-control" name="Address" id="Address" placeholder="Address" required>
                </div>
                <div class="form-group">
                  <select class="form-control" name="State" id="State" required onchange="updateCities()">
                    <option value="" disabled selected>State</option>
                    <option value="Melaka">Melaka</option>
                    <option value="Negeri Sembilan">Negeri Sembilan</option>
                  </select>
                </div>
                <div class="form-group">
                  <select class="form-control" name="City" id="City" required>
                    <option value="" disabled selected hidden>City</option>
                  </select>
                </div>

                <!-- Email -->
                <div class="form-group">
                  <input type="email" class="form-control" name="Email" id="Email" placeholder="Email" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                  <input type="password" class="form-control" name="Password" id="Password" placeholder="Password" required>
                  <small class="form-text text-muted">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
                </div>

                <div class="form-group">
                  <input type="password" class="form-control" name="Password2" id="Password2" placeholder="Re-type Password" required>
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

                <!-- Sign up button -->
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="submit" type="submit">Sign Up</button>
                </div>

                <!-- Log in navigation -->
                <div class="text-center mt-4 font-weight-light">
                  Already have an account? <a href="login.php" class="text-primary">Sign In</a>
                </div>
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

    // Action confirmation popup
    function confirmAction(event) {
      return confirm("Are you sure you want to register with these information?");

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