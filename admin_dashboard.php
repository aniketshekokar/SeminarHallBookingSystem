<?php
session_start();
include 'includes/functions.php'; // DB connection + helper functions

// ✅ Allow access only to admins
if (!is_logged_in() || !is_admin()) {
    header("Location: login.php");
    exit();
}

// ✅ Add new hall
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_hall'])) {
    $name = trim($_POST['name']);
    $capacity = (int)$_POST['capacity'];
    $location = trim($_POST['location']);
    $desc = trim($_POST['description']);

    $stmt = $conn->prepare("INSERT INTO halls (name, capacity, location, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $name, $capacity, $location, $desc);

    if ($stmt->execute()) {
        $message = "✅ Hall added successfully!";
    } else {
        $message = "❌ Error adding hall: " . $conn->error;
    }
}

// ✅ Approve or reject booking
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = ($_GET['action'] === 'approve') ? 'approved' : 'rejected';

    $conn->query("UPDATE bookings SET status='$status' WHERE id=$id");
    $message = "✅ Booking marked as $status.";
}

// ✅ Fetch all bookings with details
$bookings = $conn->query("
    SELECT b.*, u.name AS user_name, h.name AS hall_name
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN halls h ON b.hall_id = h.id
    ORDER BY b.date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Seminar Booking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #141E30, #243B55);
            color: #fff;
            margin: 0;
            padding: 0;
        }

        header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            text-align: center;
            font-size: 1.8rem;
            letter-spacing: 1px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            text-decoration: none;
            background: #ff4d4d;
            color: #fff;
            padding: 10px 18px;
            border-radius: 25px;
            font-weight: 600;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #e60000;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            background: rgba(255,255,255,0.08);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 25px rgba(0,0,0,0.3);
        }

        h2 {
            border-bottom: 2px solid #fff;
            padding-bottom: 10px;
        }

        .message {
            background: rgba(255,255,255,0.2);
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
            font-weight: 600;
        }

        form {
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input, textarea, button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            margin-bottom: 15px;
            outline: none;
        }

        input, textarea {
            background: rgba(255,255,255,0.85);
            color: #333;
        }

        button {
            background: #00b894;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #009874;
        }

        .card {
            background: rgba(255,255,255,0.15);
            padding: 15px 20px;
            margin: 12px 0;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .card h3 {
            margin: 0;
            color: #ffeaa7;
        }

        .actions a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            margin-right: 10px;
        }

        .approve {
            background: #2ecc71;
            color: #fff;
        }

        .reject {
            background: #e74c3c;
            color: #fff;
        }

        .approve:hover { background: #27ae60; }
        .reject:hover { background: #c0392b; }

        footer {
            text-align: center;
            padding: 15px;
            font-size: 0.9rem;
            opacity: 0.7;
            margin-top: 20px;
        }

        @media (max-width: 700px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    👑 Admin Dashboard
    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</header>

<div class="container">
    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

    <h2><i class="fas fa-plus-circle"></i> Add New Hall</h2>
    <form method="POST">
        <input type="hidden" name="add_hall" value="1">
        <label>Hall Name:</label>
        <input type="text" name="name" required>

        <label>Capacity:</label>
        <input type="number" name="capacity" required>

        <label>Location:</label>
        <input type="text" name="location" required>

        <label>Description:</label>
        <textarea name="description" rows="3"></textarea>

        <button type="submit"><i class="fas fa-save"></i> Add Hall</button>
    </form>

    <h2><i class="fas fa-tasks"></i> Manage Bookings</h2>
    <?php if ($bookings->num_rows > 0) { ?>
        <?php while ($b = $bookings->fetch_assoc()) { ?>
            <div class="card">
                <h3><?= htmlspecialchars($b['user_name']); ?> → <?= htmlspecialchars($b['hall_name']); ?></h3>
                <p><b>Date:</b> <?= htmlspecialchars($b['date']); ?> |
                   <b>Time:</b> <?= htmlspecialchars($b['start_time']); ?> - <?= htmlspecialchars($b['end_time']); ?></p>
                <p><b>Status:</b> <?= ucfirst(htmlspecialchars($b['status'])); ?></p>
                <div class="actions">
                    <a href="?action=approve&id=<?= $b['id']; ?>" class="approve"><i class="fas fa-check"></i> Approve</a>
                    <a href="?action=reject&id=<?= $b['id']; ?>" class="reject"><i class="fas fa-times"></i> Reject</a>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No bookings found.</p>
    <?php } ?>
</div>

<footer>
    &copy; <?= date("Y"); ?> Seminar Booking System | Admin Panel
</footer>

</body>
</html>
