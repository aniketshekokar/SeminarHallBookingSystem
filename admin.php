<?php
session_start();
include 'includes/functions.php'; // Include DB + helper functions

// ✅ Check admin access
if (!is_logged_in() || !is_admin()) {
    header('Location: login.php');
    exit();
}

$message = "";

// ✅ Handle adding a new hall
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_hall'])) {
    $name = trim($_POST['name']);
    $capacity = (int)$_POST['capacity'];
    $location = trim($_POST['location']);
    $desc = trim($_POST['description']);

    $stmt = $conn->prepare("INSERT INTO halls (name, capacity, location, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $name, $capacity, $location, $desc);

    $message = $stmt->execute()
        ? "✅ Hall added successfully!"
        : "❌ Error adding hall: " . $stmt->error;

    $stmt->close();
}

// ✅ Handle approving/rejecting bookings
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = ($_GET['action'] === 'approve') ? 'approved' : 'rejected';

    $stmt = $conn->prepare("UPDATE bookings SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $message = $stmt->execute()
        ? "Booking $status successfully!"
        : "Error updating booking: " . $stmt->error;

    $stmt->close();
}

// ✅ Fetch all bookings with user and hall details
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
    <title>Admin Dashboard | Hall Booking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1f4037, #99f2c8);
            margin: 0;
            padding: 0;
            color: #fff;
        }

        header {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
            letter-spacing: 1px;
        }

        .logout-btn {
            background: #ff4b2b;
            padding: 10px 20px;
            color: white;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #ff6b5a;
        }

        .message {
            text-align: center;
            background: rgba(255,255,255,0.2);
            padding: 10px;
            margin: 20px auto;
            border-radius: 10px;
            width: 70%;
            font-weight: 500;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: #fff;
        }

        .form-box {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            background: rgba(255,255,255,0.1);
            padding: 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .form-box label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-box input, .form-box textarea, .form-box button {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: none;
            border-radius: 8px;
            outline: none;
        }

        .form-box button {
            background: #43cea2;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .form-box button:hover {
            background: #185a9d;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 30px;
        }

        .card {
            background: rgba(255,255,255,0.15);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-top: 0;
            color: #ffe;
        }

        .approve, .reject {
            display: inline-block;
            padding: 8px 15px;
            margin-top: 10px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
        }

        .approve {
            background: #00c851;
            color: #fff;
        }

        .reject {
            background: #ff4444;
            color: #fff;
        }

        .approve:hover { background: #007e33; }
        .reject:hover { background: #cc0000; }
    </style>
</head>
<body>
    <header>
        <h1><i class="fas fa-user-shield"></i> Admin Dashboard</h1>
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </header>

    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <h2><i class="fas fa-plus-circle"></i> Add New Hall</h2>
    <form method="post" class="form-box">
        <input type="hidden" name="add_hall">
        <label><i class="fas fa-building"></i> Hall Name</label>
        <input type="text" name="name" required>

        <label><i class="fas fa-users"></i> Capacity</label>
        <input type="number" name="capacity" required>

        <label><i class="fas fa-map-marker-alt"></i> Location</label>
        <input type="text" name="location" required>

        <label><i class="fas fa-info-circle"></i> Description</label>
        <textarea name="description" rows="3"></textarea>

        <button type="submit" name="add_hall"><i class="fas fa-plus"></i> Add Hall</button>
    </form>

    <h2><i class="fas fa-list"></i> Manage Bookings</h2>
    <div class="container">
        <?php if ($bookings->num_rows > 0): ?>
            <?php while ($b = $bookings->fetch_assoc()): ?>
                <div class="card">
                    <h3><i class="fas fa-user"></i> <?= htmlspecialchars($b['user_name']) ?> → 
                        <i class="fas fa-building"></i> <?= htmlspecialchars($b['hall_name']) ?></h3>
                    <p><b>Status:</b> <?= htmlspecialchars($b['status']) ?></p>
                    <p><b>Date:</b> <?= htmlspecialchars($b['date']) ?> |
                       <b>Time:</b> <?= htmlspecialchars($b['start_time']) ?> - <?= htmlspecialchars($b['end_time']) ?></p>
                    <a href="?action=approve&id=<?= $b['id'] ?>" class="approve"><i class="fas fa-check"></i> Approve</a>
                    <a href="?action=reject&id=<?= $b['id'] ?>" class="reject"><i class="fas fa-times"></i> Reject</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; color:#fff;">📭 No bookings found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
