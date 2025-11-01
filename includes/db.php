<?php
// Database configuration
$host = 'localhost';      // Server (usually 'localhost' in XAMPP)
$user = 'root';           // Default MySQL username in XAMPP
$pass = '';               // Default password (empty in XAMPP)
$db   = 'seminar_booking'; // Your database name

// Establish connection
$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    // Stop script execution and show a friendly error
    die("❌ Database Connection Failed: " . $conn->connect_error);
}

// Set character encoding to UTF-8 (important for special characters)
if (!$conn->set_charset("utf8mb4")) {
    die("❌ Error loading character set utf8mb4: " . $conn->error);
}

// ✅ Optional: Suppress error display in production (uncomment below)
// error_reporting(0);
// ini_set('display_errors', 0);
?>
