<?php
session_start();
include 'includes/functions.php'; // Includes helper functions and DB connection

// ✅ Redirect user to login if not logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

global $conn; // Make sure $conn is accessible

// ✅ Fetch all halls
$halls = $conn->query("SELECT * FROM halls ORDER BY name ASC");

// ✅ Fetch bookings for the logged-in user
$user_id = intval($_SESSION['user_id']);
$bookings = $conn->query("
    SELECT b.*, h.name AS hall_name 
    FROM bookings b 
    JOIN halls h ON b.hall_id = h.id 
    WHERE b.user_id = $user_id
    ORDER BY b.date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Seminar Booking System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            margin: 0;
            padding: 0;
        }

        header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(8px);
            padding: 20px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 1px;
            position: relative;
        }

        .logout-link, .admin-link {
            position: absolute;
            top: 20px;
            background: #fff;
            color: #2a5298;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .logout-link { right: 20px; }
        .admin-link { right: 120px; background: #f9c74f; color: #2a5298; }

        .logout-link:hover, .admin-link:hover {
            background: #2a5298;
            color: #fff;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 25px rgba(0,0,0,0.2);
        }

        h2 {
            border-bottom: 2px solid #fff;
            padding-bottom: 10px;
            margin-top: 30px;
        }

        .hall, .booking {
            background: rgba(255,255,255,0.15);
            border-radius: 15px;
            padding: 15px 20px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s;
        }

        .hall:hover, .booking:hover {
            background: rgba(255,255,255,0.25);
        }

        a.button {
            background: #fff;
            color: #2a5298;
            padding: 6px 14px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        a.button:hover {
            background: #2a5298;
            color: #fff;
        }

        footer {
            text-align: center;
            padding: 15px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        @media (max-width: 600px) {
            .hall, .booking {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<header>
    Seminar Booking Dashboard
    <a href="logout.php" class="logout-link">Logout</a>
    <?php if (is_admin()) { ?>
        <a href="admin_dashboard.php" class="admin-link">Admin Panel</a>
    <?php } ?>
</header>

<div class="container">
    <h2>🏢 Available Halls</h2>
    <?php if ($halls && $halls->num_rows > 0) { ?>
        <?php while ($hall = $halls->fetch_assoc()) { ?>
            <div class="hall">
                <div>
                    <strong><?php echo htmlspecialchars($hall['name']); ?></strong><br>
                    Capacity: <?php echo htmlspecialchars($hall['capacity']); ?><br>
                    Location: <?php echo htmlspecialchars($hall['location']); ?>
                </div>
                <a href="book_hall.php?hall_id=<?php echo $hall['id']; ?>" class="button">Book Now</a>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No halls available at the moment.</p>
    <?php } ?>

    <h2>📅 Your Bookings</h2>
    <?php if ($bookings && $bookings->num_rows > 0) { ?>
        <?php while ($booking = $bookings->fetch_assoc()) { ?>
            <div class="booking">
                <div>
                    <strong><?php echo htmlspecialchars($booking['hall_name']); ?></strong><br>
                    <?php echo htmlspecialchars($booking['date']); ?> |
                    <?php echo htmlspecialchars($booking['start_time']); ?> → 
                    <?php echo htmlspecialchars($booking['end_time']); ?>
                </div>
                <div>Status: <b><?php echo ucfirst(htmlspecialchars($booking['status'])); ?></b></div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>You haven’t booked any halls yet.</p>
    <?php } ?>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> Seminar Booking System | All Rights Reserved
</footer>

</body>
</html>
