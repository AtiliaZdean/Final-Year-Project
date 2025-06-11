<?php
session_start();

// Unset specific session variables
unset($_SESSION['customer_id']);
unset($_SESSION['name']);
unset($_SESSION['address']);
unset($_SESSION['city']);
unset($_SESSION['state']);

header("Location: home.php");
exit();
?>