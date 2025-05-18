<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>HygieiaHub Log In</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="..\vendors\feather\feather.css">
  <link rel="stylesheet" href="..\vendors\ti-icons\css\themify-icons.css">
  <link rel="stylesheet" href="..\vendors\css\vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="..\css\vertical-layout-light\style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="..\images\favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="..\images\HygieaHub logo.png" alt="Hygieia logo">
              </div>
              <h4>Sign In</h4>
              <form class="pt-3" method="POST" action="dbconnection\dblogin.php">
                
                <!-- Email -->
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" name="Email" id="Email" placeholder="Email">
                </div>

                <!-- Password -->
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="Password" id="Password" placeholder="Password">
                </div>

                <!-- Login error message -->
                <?php
                  session_start();
                    if (isset($_SESSION['login_error'])) {
                      echo '<div class="alert alert-danger" role="alert">' . $_SESSION['login_error'] . '</div>';
                      unset($_SESSION['login_error']); // Unset to prevent repeated messages
                    }
                ?>

                <!-- Log in button -->
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">Sign In</button>
                </div>

                <!-- Sign up navigation -->
                <div class="text-center mt-4 font-weight-light">
                  Doesn't have an account? <a href="register.php" class="text-primary">Sign Up</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- javascript files -->
  <script src="../vendors/js/vendor.bundle.base.js"></script>
  <script src="../js/off-canvas.js"></script>
  <script src="../js/hoverable-collapse.js"></script>
  <script src="../js/template.js"></script>
  <script src="../js/settings.js"></script>
  <script src="../js/todolist.js"></script>
</body>

</html>