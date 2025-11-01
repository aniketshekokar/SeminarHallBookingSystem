<?php
session_start();
include 'includes/functions.php'; // Include helper functions
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seminar Hall Booking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* 🌈 Modern gradient background with animation */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            background-size: 400% 400%;
            animation: gradientMove 8s ease infinite;
            color: #fff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h1 {
            font-size: 2.8rem;
            margin-bottom: 30px;
            text-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        .btn-container {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            justify-content: center;
        }

        a {
            text-decoration: none;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            padding: 14px 35px;
            border-radius: 30px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        a:hover {
            background: #fff;
            color: #764ba2;
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.6);
            transform: translateY(-3px);
        }

        footer {
            position: absolute;
            bottom: 20px;
            font-size: 0.9rem;
            opacity: 0.85;
            letter-spacing: 0.5px;
        }

        footer i {
            color: #ff9ff3;
        }

        /* 📱 Responsive adjustments */
        @media (max-width: 600px) {
            h1 {
                font-size: 2rem;
            }
            .btn-container a {
                padding: 12px 25px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

    <h1><i class="fas fa-school"></i> Seminar Hall Booking System</h1>

    <div class="btn-container">
        <?php if (is_logged_in()) { ?>
            <?php if ($_SESSION['role'] === 'admin') { ?>
                <a href="admin_dashboard.php"><i class="fas fa-user-shield"></i> Admin Dashboard</a>
            <?php } else { ?>
                <a href="dashboard.php"><i class="fas fa-user"></i> User Dashboard</a>
            <?php } ?>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <?php } else { ?>
            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
        <?php } ?>
    </div>

    <footer>
        &copy; <?= date("Y"); ?> Seminar Booking System | Made with <i class="fas fa-heart"></i> by Team Aniket
    </footer>

</body>
</html>
