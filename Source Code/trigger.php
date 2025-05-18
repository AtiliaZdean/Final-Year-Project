<?php
include 'dbconnection.php';

// Drop existing triggers if they exist
$conn->query("DROP TRIGGER IF EXISTS before_insert_service");
$conn->query("DROP TRIGGER IF EXISTS after_insert_service");
$conn->query("DROP TRIGGER IF EXISTS after_update_service");
$conn->query("DROP TRIGGER IF EXISTS after_delete_service");

// Ensure unique service name trigger
$before_insert_service = "
CREATE TRIGGER before_insert_service
BEFORE INSERT ON additional_service
FOR EACH ROW
BEGIN
    -- Check if the service name already exists
    IF EXISTS (SELECT 1 FROM additional_service WHERE LOWER(name) = LOWER(NEW.name)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Service name already exists.';
    END IF;
END;
";
if ($conn->query($before_insert_service) === FALSE) {
    $_SESSION['EmailMessage'] = 'Error creating before_insert_service trigger: ' . $conn->error;
    header("Location: staff/addservice.php");
    exit;
} else {
    echo "before_insert_service trigger created successfully.\n";
}

// After insert service trigger
$after_insert_service = "
CREATE TRIGGER after_insert_service
AFTER INSERT ON additional_service
FOR EACH ROW
BEGIN
    INSERT INTO additional_service_log(
        service_id, action, name, new_description, new_price, new_duration, made_by
    ) VALUES (
        NEW.service_id, 'INSERT', NEW.name, NEW.description, NEW.price, NEW.duration, @made_by
    );
END;
";
if ($conn->query($after_insert_service) === FALSE) {
    $_SESSION['EmailMessage'] = 'Error creating after_insert_service trigger: ' . $conn->error;
    header("Location: staff/addservice.php");
    exit;
} else {
    echo "after_insert_service trigger created successfully.\n";
}

// After update service trigger
$after_update_service = "
CREATE TRIGGER after_update_service
AFTER UPDATE ON additional_service
FOR EACH ROW
BEGIN
    INSERT INTO additional_service_log(
        service_id, action,
        name,
        old_description, new_description,
        old_price, new_price,
        old_duration, new_duration,
        made_by
    ) VALUES (
        OLD.service_id, 'UPDATE',
        OLD.name,
        OLD.description, NEW.description,
        OLD.price, NEW.price,
        OLD.duration, NEW.duration,
        @made_by
    );
END;
";
if ($conn->query($after_update_service) === FALSE) {
    $_SESSION['EmailMessage'] = 'Error creating after_update_service trigger: ' . $conn->error;
    header("Location: staff/editservice.php");
    exit;
} else {
    echo "after_update_service trigger created successfully.\n";
}

// After delete service trigger
$after_delete_service = "
CREATE TRIGGER after_delete_service
AFTER DELETE ON additional_service
FOR EACH ROW
BEGIN
    INSERT INTO additional_service_log(
        service_id, action, name, old_description, old_price, old_duration, made_by
    ) VALUES (
        OLD.service_id, 'DELETE', OLD.name, OLD.description, OLD.price, OLD.duration, @made_by
    );
END;
";
if ($conn->query($after_delete_service) === FALSE) {
    $_SESSION['EmailMessage'] = 'Error creating after_delete_service trigger: ' . $conn->error;
    header("Location: staff/editservice.php");
    exit;
} else {
    echo "after_delete_service trigger created successfully.\n";
}
?>