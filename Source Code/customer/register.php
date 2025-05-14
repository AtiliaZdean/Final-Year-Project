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
          <div class="col-lg-6 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="..\images\HygieaHub logo.png" alt="HygieiaHub logo">
              </div>
              <h4>Sign Up</h4>
              <!-- Form section -->
              <form class="pt-3" method="POST" action="dbconnection\dbregister.php" onsubmit="return validateForm()">

                <!-- Name -->
                <div class="form-group row">
                  <label for="Name" class="col-sm-3 col-form-label">Name<span class="text-danger"> *</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="Name" id="Name" placeholder="Full Name" required pattern="[A-Za-z\s]+" title="Only letters are allowed." oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                  </div>
                </div>

                <!-- Phone Number -->
                <div class="form-group row">
                  <label for="PhoneNumber" class="col-sm-3 col-form-label" class="form-label">Phone Number<span class="text-danger"> *</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="PhoneNumber" id="PhoneNumber" maxlength="10" placeholder="01xxxxxxxx" required pattern="[0-9]+" title="Only numbers are allowed." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                  </div>
                </div>

                <!-- Address -->
                <div class="form-group row">
                  <label for="Address1" class="col-sm-3 col-form-label">Address<span class="text-danger"> *</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="Address1" id="Address1" placeholder="Address" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="Address2" class="col-sm-3 col-form-label">State<span class="text-danger"> *</span></label>
                  <div class="col-sm-9">
                    <select class="form-control" name="Address2" id="Address2" required onchange="changeInputColor(); updateCities()">
                      <option value="" disabled selected>State</option>
                      <option value="melaka">Melaka</option>
                      <option value="negeri sembilan">Negeri Sembilan</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="Address3" class="col-sm-3 col-form-label">City<span class="text-danger"> *</span></label>
                  <div class="col-sm-9">
                    <select class="form-control" name="Address3" id="Address3" required onchange="changeInputColor()">
                      <option value="" disabled selected hidden>City</option>
                    </select>
                  </div>
                </div>

                <!-- Email -->
                <div class="form-group row">
                  <label for="Email" class="col-sm-3 col-form-label">Email<span class="text-danger"> *</span></label>
                  <div class="col-sm-9">
                    <input type="email" class="form-control" name="Email" id="Email" placeholder="Email" required>
                  </div>
                </div>

                <!-- Password -->
                <div class="form-group row">
                  <label for="Password" class="col-sm-3 col-form-label">Password<span class="text-danger"> *</span></label>
                  <div class="col-sm-9">
                    <input type="password" class="form-control" name="Password" id="Password" placeholder="Password" required>
                  </div>
                  <label class="col-sm-3 col-form-label"></label>
                  <div class="col-sm-9">
                    <small class="form-text text-muted">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="Password2" class="col-sm-3 col-form-label">Re-type Password<span class="text-danger"> *</span></label>
                  <div class="col-sm-9">
                    <input type="password" class="form-control" name="Password2" id="Password2" placeholder="Re-type Password" required>
                  </div>
                </div>

                <small class="form-text text-muted"><span class="text-danger">*</span> - required</small>

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
                  Already have an account? <a href="login.html" class="text-primary">Login</a>
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
    // Change input font color when selecting
    function changeInputColor() {
      const selectAddress2 = document.getElementById('Address2');
      const selectAddress3 = document.getElementById('Address3');

      if (selectAddress2.value !== '') {
        selectAddress2.style.color = '#495057';
      } else {
        selectAddress2.style.color = '';
      }

      if (selectAddress3.value !== '') {
        selectAddress3.style.color = '#495057';
      } else {
        selectAddress3.style.color = '';
      }
    }

    // Generate city options based on state selected
    function updateCities() {
      const stateSelect = document.getElementById('Address2');
      const citySelect = document.getElementById('Address3');

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
        option.value = city.toLowerCase();
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
  <script src="..\js\settings.js""></script>
  <script src=" ..\js\todolist.js"></script>
</body>

</html>