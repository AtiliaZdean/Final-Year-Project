<?php
session_start();

// Unset specific session variables
unset($_SESSION['customer_id']);
unset($_SESSION['name']);

header("Location: ../index.php");
exit();
?>