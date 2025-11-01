<?php
session_start();
include 'includes/functions.php';  // Include helper functions (and DB connection)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = hash_password($_POST['password']);  // Securely hash password

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "⚠️ Email already registered! Try logging in.";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$pass', 'user')";
        if ($conn->query($sql)) {
            header('Location: login.php');
            exit();
        } else {
            $error = "❌ Registration failed: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Seminar Booking</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
            animation: fadeIn 1s ease;
        }

        h1 {
            margin-bottom: 25px;
            font-size: 2rem;
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
            color: #11998e;
            padding: 10px 25px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        button:hover {
            background: #11998e;
            color: #fff;
            box-shadow: 0 0 15px rgba(255,255,255,0.5);
        }

        a {
            display: block;
            margin-top: 20px;
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

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(30px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>

<div class="register-container">
    <h1>📝 Register</h1>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Create Password" required>
        <button type="submit">Register</button>
    </form>

    <a href="login.php">Already have an account? Login →</a>
</div>

</body>
</html>
