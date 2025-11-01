<?php
// Include database connection
include 'db.php';

// ✅ Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// ✅ Check if the current user is an admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// ✅ Securely hash a password before storing it
function hash_password($pass) {
    return password_hash($pass, PASSWORD_DEFAULT);
}

// ✅ Verify a plain password against its hashed version
function verify_password($pass, $hash) {
    return password_verify($pass, $hash);
}

// ✅ Check if a booking conflicts with an existing approved one
function check_booking_conflict($hall_id, $date, $start, $end) {
    global $conn;

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM bookings 
            WHERE hall_id = ? 
              AND date = ? 
              AND status = 'approved' 
              AND (
                    (start_time <= ? AND end_time > ?) 
                    OR 
                    (start_time < ? AND end_time >= ?)
                  )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $hall_id, $date, $start, $start, $end, $end);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0; // True if conflict exists
}
?>
