<?php
session_start();
include 'includes/functions.php'; // Include helper + DB connection

// If user is already logged in, redirect directly to dashboard/admin
if (is_logged_in()) {
    if (is_admin()) {
        header('Location: admin_dashboard.php');
    } else {
        header('Location: dashboard.php');
    }
    exit();
}

$error = ''; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    // ✅ Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if (!$stmt) {
        die("Database Error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // ✅ Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // ✅ Verify password using password_verify()
        if (verify_password($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // ✅ Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: dashboard.php');
            }
            exit();
        } else {
            $error = "❌ Invalid password. Please try again.";
        }
    } else {
        $error = "⚠️ No account found with that email.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Seminar Booking System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #43cea2, #185a9d);
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }

        .login-container {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px 60px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
            width: 350px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2rem;
            letter-spacing: 1px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: none;
            border-radius: 8px;
            outline: none;
            background: rgba(255,255,255,0.9);
            color: #333;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #fff;
            color: #185a9d;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background: #185a9d;
            color: #fff;
            box-shadow: 0 0 15px rgba(255,255,255,0.4);
        }

        a {
            color: #fff;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
            opacity: 0.8;
        }

        a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .error {
            background: rgba(255, 50, 50, 0.3);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            color: #ffdddd;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🔐 Login</h1>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="post" autocomplete="off">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>
        <a href="register.php">Don’t have an account? Register</a>
    </div>
</body>
</html>
