<?php
$servername = "localhost";
$username = "B032210369";
$password = "hygieiahub";
$dbname = "hygieiahub";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} /*else {
    echo "Database connection successful!";
} */
?>