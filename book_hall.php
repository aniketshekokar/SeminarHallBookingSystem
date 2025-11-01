<?php
session_start();
include 'includes/functions.php';  // Include helper functions and DB connection

// Redirect if not logged in
if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Validate hall_id
if (!isset($_GET['hall_id']) || !is_numeric($_GET['hall_id'])) {
    die("❌ Invalid hall selected. <a href='dashboard.php'>Back</a>");
}

$hall_id = intval($_GET['hall_id']);  // Safely cast hall_id to integer
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];

    // Validate times
    if ($start >= $end) {
        $message = "<div class='error'>⚠️ End time must be later than start time!</div>";
    } elseif (check_booking_conflict($hall_id, $date, $start, $end)) {
        $message = "<div class='error'>⏰ Hall already booked for this time slot! Choose another.</div>";
    } else {
        $sql = "INSERT INTO bookings (user_id, hall_id, date, start_time, end_time, status)
                VALUES ({$_SESSION['user_id']}, $hall_id, '$date', '$start', '$end', 'pending')";
        if ($conn->query($sql)) {
            $message = "<div class='success'>✅ Booking submitted successfully! Await admin approval.<br><a href='dashboard.php'>Go to Dashboard</a></div>";
        } else {
            $message = "<div class='error'>❌ Booking failed: " . $conn->error . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Hall | Seminar Booking</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #36D1DC, #5B86E5);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .book-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            width: 380px;
            text-align: center;
            animation: fadeIn 0.9s ease;
        }

        h1 {
            margin-bottom: 25px;
            font-size: 2rem;
            color: #fff;
        }

        input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 25px;
            margin: 10px 0;
            font-size: 1rem;
            text-align: center;
            outline: none;
        }

        button {
            background: #fff;
            color: #5B86E5;
            padding: 10px 25px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        button:hover {
            background: #5B86E5;
            color: #fff;
            box-shadow: 0 0 15px rgba(255,255,255,0.5);
        }

        a {
            display: block;
            margin-top: 15px;
            color: #fff;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            background: rgba(255, 0, 0, 0.2);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            color: #ffdddd;
        }

        .success {
            background: rgba(0, 255, 0, 0.2);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            color: #d4ffd4;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(30px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>

<div class="book-container">
    <h1>📅 Book Hall</h1>
    <?= $message ?>

    <form method="post">
        <input type="date" name="date" required>
        <input type="time" name="start_time" required>
        <input type="time" name="end_time" required>
        <button type="submit">Submit Booking</button>
    </form>

    <a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
