<?php
session_start();
include('../../dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle new registrations
    if (isset($_POST['submit'])) {
        $name = $_POST['Name'];
        $phone_number = $_POST['PhoneNumber'];
        $house_id = $_POST['HouseType'];
        $address = $_POST['Address'];
        $city = $_POST['City'];
        $state = $_POST['State'];
        $email = $_POST['Email'];
        $password = trim($_POST['Password']);
        $password2 = trim($_POST['Password2']);

        // Validate password strength
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
            $_SESSION['EmailMessage'] = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
            header("Location: ../register.php");
            exit;
        }

        // Validate password re-type
        if ($password !== $password2) {
            $_SESSION['EmailMessage'] = 'Passwords do not match.';
            header("Location: ../register.php");
            exit;
        }

        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Start transaction
            $conn->begin_transaction();

            // Insert data into CUSTOMER
            $stmt_insert = $conn->prepare(
                "INSERT INTO customer (name, phone_number, email, password)
                VALUES (?, ?, ?, ?)"
            );
            $stmt_insert->bind_param("ssss", $name, $phone_number, $email, $hashed_password);
            
            if (!$stmt_insert->execute()) {
                throw new Exception("Failed to create customer account.");
            }
            
            $customer_id = $stmt_insert->insert_id;
            
            // Insert primary address into customer_addresses
            $address_stmt = $conn->prepare(
                "INSERT INTO customer_addresses (customer_id, address_label, house_id, address, city, state, is_default)
                VALUES (?, 'Primary', ?, ?, ?, ?, 1)"
            );
            $address_stmt->bind_param("iisss", $customer_id, $house_id, $address, $city, $state);
            
            if (!$address_stmt->execute()) {
                throw new Exception("Failed to save address.");
            }

            // Commit transaction
            $conn->commit();

            $_SESSION['status'] = 'Your registration is successful. You may login.';
            header("Location: ../login.php");
            exit;
            
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['EmailMessage'] = 'Error: ' . $e->getMessage();
            header("Location: ../register.php");
            exit;
        }
    }
    // Handle profile updates
    elseif (isset($_POST['update'])) {
        $name = $_POST['Name'];
        $phone_number = $_POST['PhoneNumber'];
        $email = $_POST['Email'];
        $password = trim($_POST['Password']);

        try {
            $id = $_POST['CustomerId'];

            if ($password != '') {
                if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
                    $_SESSION['EmailMessage'] = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
                    header("Location: ../profile.php");
                    exit;
                }
                $password = password_hash($password, PASSWORD_DEFAULT);

                $stmt_update = $conn->prepare(
                    "UPDATE customer SET name = ?, phone_number = ?, email = ?, password = ? WHERE customer_id = ?"
                );
                $stmt_update->bind_param("ssssi", $name, $phone_number, $email, $password, $id);
            } else {
                $stmt_update = $conn->prepare(
                    "UPDATE customer SET name = ?, phone_number = ?, email = ? WHERE customer_id = ?"
                );
                $stmt_update->bind_param("sssi", $name, $phone_number, $email, $id);
            }

            $_SESSION['name'] = $name;

            if ($stmt_update->execute()) {
                $_SESSION['status'] = 'Your profile update is successful.';
                header("Location: ../profile.php");
                exit;
            } else {
                $_SESSION['EmailMessage'] = 'Failed to update profile.';
                header("Location: ../profile.php");
                exit;
            }

            $stmt_update->close();
        } catch (Exception $e) {
            $_SESSION['EmailMessage'] = 'Error: ' . $e->getMessage();
            header("Location: ../profile.php");
            exit;
        }
    }
    // Handle new address additions
    elseif (isset($_POST['address_label'])) {
        // [Keep your existing address addition code from previous implementation]
        $customer_id = $_POST['customer_id'];
        $address_label = $_POST['address_label'];
        $house_id = $_POST['HouseType'];
        $address = $_POST['Address'];
        $city = $_POST['City'];
        $state = $_POST['State'];
        $is_default = isset($_POST['set_as_default']) ? 1 : 0;

        try {
            // If this is being set as default, first unset any existing default
            if ($is_default) {
                $reset_stmt = $conn->prepare("UPDATE customer_addresses SET is_default = FALSE WHERE customer_id = ?");
                $reset_stmt->bind_param("i", $customer_id);
                $reset_stmt->execute();
            }
            
            // If this is the first address, it must be default
            $count_stmt = $conn->prepare("SELECT COUNT(*) as count FROM customer_addresses WHERE customer_id = ?");
            $count_stmt->bind_param("i", $customer_id);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result()->fetch_assoc();
            
            if ($count_result['count'] == 0) {
                $is_default = 1;
            }

            // Insert the new address
            $insert_stmt = $conn->prepare(
                "INSERT INTO customer_addresses (customer_id, address_label, house_id, address, city, state, is_default)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $insert_stmt->bind_param("isisssi", $customer_id, $address_label, $house_id, $address, $city, $state, $is_default);

            if ($insert_stmt->execute()) {
                $_SESSION['status'] = 'Address added successfully.';
            } else {
                $_SESSION['EmailMessage'] = 'Failed to add address.';
            }
            
            $insert_stmt->close();
        } catch (Exception $e) {
            $_SESSION['EmailMessage'] = 'Error: ' . $e->getMessage();
        }
        
        header("Location: ../profile.php");
        exit;
    }
}

$conn->close();
?>