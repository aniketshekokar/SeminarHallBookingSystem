<?php
session_start(); // Start the session

// Remove all session variables
$_SESSION = array();

// Destroy the session completely
session_unset();
session_destroy();

// Prevent caching (so back button won’t reopen dashboard)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Redirect to login page
header("Location: login.php");
exit();
?>
