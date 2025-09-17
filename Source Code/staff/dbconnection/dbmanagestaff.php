<?php
session_start();
include '../../dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['Name'];
    $phone_number = $_POST['PhoneNumber'];
    $made_by = $_SESSION['staffname'];
    $image_path = null;

    // Handle image upload if it's a cleaner
    if (isset($_FILES['StaffImage']) && $_FILES['StaffImage']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../media/";
        $file_extension = pathinfo($_FILES["StaffImage"]["name"], PATHINFO_EXTENSION);
        $new_filename = 'cleaner_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["StaffImage"]["tmp_name"]);
        if ($check !== false) {
            // Check file size (2MB max)
            if ($_FILES["StaffImage"]["size"] <= 2000000) {
                // Allow certain file formats
                $allowed_types = ['jpg', 'jpeg', 'png'];
                if (in_array(strtolower($file_extension), $allowed_types)) {
                    if (move_uploaded_file($_FILES["StaffImage"]["tmp_name"], $target_file)) {
                        $image_path = $new_filename;
                    }
                }
            }
        }
    }

    if (isset($_POST['register'])) {
        $email = $_POST['Email'];
        $raw_password = $_POST['Password'] ?? '';
        $role = $_POST['Role1'];
        $branch = $_POST['Branch1'];

        // Check if staff other than cleaner put email & password
        if ($role != 'Cleaner') {
            if ($email === '' || $raw_password === '') {
                echo "<script>alert('Staffs other than cleaner must enter their email and password.');</script>";
                echo "<script>window.location.href = '../managestaff.php';</script>";
                exit();
            }

            // Validate password strength on raw password
            if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $raw_password)) {
                echo "<script>alert('Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');</script>";
                echo "<script>window.location.href = '../managestaff.php';</script>";
                exit();
            }

            $password = password_hash($raw_password, PASSWORD_DEFAULT);

            // Call stored procedure for registration
            $conn->query("CALL ManageStaff('insert', 0, '$name', '$email', '$password', '$phone_number', '$branch', '$role', '', '$made_by', '$image_path', @result)");
            $result = $conn->query("SELECT @result AS result")->fetch_assoc();
        } else {
            $conn->query("CALL ManageStaff('insert', 0, '$name', '$email', '', '$phone_number', '$branch', '$role', '', '$made_by', '$image_path', @result)");
            $result = $conn->query("SELECT @result AS result")->fetch_assoc();
        }

        // Success/fail message
        if ($result['result'] == 1) {
            echo "<script>alert('The registration is successful.');</script>";
        } else {
            echo "<script>alert('Failed to register staff.');</script>";
        }
    } elseif (isset($_POST['update'])) {
        $id = $_POST['StaffId'];
        $status = $_POST['StatusModal'];
        $branch = $_POST['Branch2'];
        $role = $_POST['Role2'] ?? '';

        // For admin's update
        if ($role != 'Cleaner') {
            $email = $_POST['Email'];
            $raw_password = $_POST['Password'] ?? '';

            if ($raw_password != '') {
                if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $raw_password)) {
                    $_SESSION['EmailMessage'] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
                    header("Location: ../profile.php");
                    exit();
                }
                $password = password_hash($raw_password, PASSWORD_DEFAULT);

                $stmt_update = $conn->prepare(
                    "UPDATE staff SET password = ? WHERE staff_id = ?"
                );
                $stmt_update->bind_param("si", $password, $id);
                $stmt_update->execute();
                $stmt_update->close();
            }

            $conn->query("CALL ManageStaff('update', '$id', '$name', '$email', '', '$phone_number', '', '', '', '', '$image_path', @result)");
            $result = $conn->query("SELECT @result AS result")->fetch_assoc();

            $_SESSION['staffname'] = $name;

            // Success/fail message
            if ($result['result'] == 1) {
                $_SESSION['status'] = 'Your profile update is successful.';
            } else {
                $_SESSION['EmailMessage'] = 'Failed to update staff.';
            }
            header("Location: ../profile.php");
            exit;
        }

        // First check if cleaner is being assigned in any PENDING bookings
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM BOOKING_CLEANER bc JOIN BOOKING b ON bc.booking_id = b.booking_id WHERE bc.staff_id = ? AND b.status = 'Pending'");
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $stmt_check->bind_result($pending_count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($pending_count > 0) {
            echo "<script>alert('Cannot update staff - she/he is being assigned in pending bookings.');</script>";
        } else {
            // Get current image path if not uploading new image
            if ($image_path === null) {
                $stmt = $conn->prepare("SELECT image_path FROM staff WHERE staff_id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->bind_result($current_image);
                $stmt->fetch();
                $stmt->close();
                $image_path = $current_image;
            }
            
            // Call stored procedure for update
            $conn->query("CALL ManageStaff('update', '$id', '$name', '', '', '$phone_number', '$branch', '', '$status', '$made_by', '$image_path', @result)");
            $result = $conn->query("SELECT @result AS result")->fetch_assoc();

            // Success/fail message
            if ($result['result'] == 1) {
                echo "<script>alert('The update is successful.');</script>";
            } else {
                echo "<script>alert('Failed to update staff.');</script>";
            }
        }
    }

    echo "<script>window.location.href = '../managestaff.php';</script>";
    exit();
}
$conn->close();
