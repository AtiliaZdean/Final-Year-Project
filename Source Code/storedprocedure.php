<?php
include 'dbconnection.php';

// Drop existing procedure if it exists
$checkProcedure = "SHOW PROCEDURE STATUS LIKE 'ManageStaff';";
$result = $conn->query($checkProcedure);

if ($result->num_rows > 0) {
    $dropProcedure = "DROP PROCEDURE ManageStaff;";
    if ($conn->query($dropProcedure) === TRUE) {
        echo "Procedure ManageStaff dropped successfully.<br>";
    } else {
        echo "Error dropping procedure: " . $conn->error . "<br>";
    }
}

// Stored procedure to manage staff activity
$procedure = "
CREATE PROCEDURE ManageStaff(
    IN p_action VARCHAR(12),
    IN p_staff_id INT,
    IN p_name VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255),
    IN p_phone_number VARCHAR(11),
    IN p_branch VARCHAR(100),
    IN p_role VARCHAR(7),
    IN p_status VARCHAR(9),
    IN p_made_by VARCHAR(100),
    OUT p_result INT
)
BEGIN
    DECLARE v_old_email VARCHAR(100);
    DECLARE v_old_phone VARCHAR(11);
    DECLARE v_old_branch VARCHAR(100);
    DECLARE v_old_role VARCHAR(7);
    DECLARE v_old_status VARCHAR(9);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        SET p_result = 0;
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    IF p_action = 'insert' THEN
        -- Insert new staff with status default to 'active'
        INSERT INTO staff (name, email, password, phone_number, branch, role)
        VALUES (p_name, p_email, p_password, p_phone_number, p_branch, p_role);
        
        -- Log the activity
        INSERT INTO staff_log (
            staff_id, action, made_at, made_by, name, new_email, new_phone_number, new_branch, new_role, new_status
        )
        VALUES (
            LAST_INSERT_ID(), 'insert', NOW(), p_made_by, p_name, p_email, p_phone_number, p_branch, p_role, 'active'
        );
        
        SET p_result = 1;
        
    ELSEIF p_action = 'update' THEN
        -- Get current values for logging
        SELECT email, phone_number, branch, role, status 
        INTO v_old_email, v_old_phone, v_old_branch, v_old_role, v_old_status
        FROM staff 
        WHERE staff_id = p_staff_id;
        
        -- Update staff record
        UPDATE staff
        SET 
            name = p_name,
            email = p_email,
            phone_number = p_phone_number,
            branch = p_branch,
            role = p_role,
            status = p_status
        WHERE staff_id = p_staff_id;
        
        -- Log the activity
        INSERT INTO staff_log (
            staff_id, action, made_at, made_by, name, old_email, old_phone_number, old_branch, old_role, old_status, new_email, new_phone_number, new_branch, new_role, new_status
        )
        VALUES (
            p_staff_id, 'update', NOW(), p_made_by, p_name, v_old_email, v_old_phone, v_old_branch, v_old_role, v_old_status, p_email, p_phone_number, p_branch, p_role, p_status
        );
        
        SET p_result = 1;
        
    ELSEIF p_action = 'login' THEN
        -- Log successful login
        INSERT INTO staff_log (
            staff_id, action, made_at, name, made_by
        )
        VALUES (
            p_staff_id, 'login', NOW(), p_name, p_made_by
        );
        
        SET p_result = 1;
        
    ELSEIF p_action = 'failed_login' THEN
        -- Log failed login attempt
        INSERT INTO staff_log (
            staff_id, action, made_at, made_by, old_email, old_status
        )
        VALUES (
            p_staff_id, 'failed login', NOW(), p_made_by, p_email, p_status
        );
        
        SET p_result = 1;
    END IF;
    
    COMMIT;
END
";

// Execute the procedure
if ($conn->query($procedure) === TRUE) {
    echo "Procedure ManageStaff created successfully.";
} else {
    echo "Error creating procedure: " . $conn->error . "<br>";
}

$conn->close();
