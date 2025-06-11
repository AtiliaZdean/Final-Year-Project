<?php
session_start();

unset($_SESSION['staff_id']);
unset($_SESSION['staffname']);
unset($_SESSION['branch']);

header("Location: login.php"); 
exit();
?>