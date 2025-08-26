<?php
if (session_status() === PHP_SESSION_NONE) {

    session_set_cookie_params([
    'lifetime' => 0, // session ends on browser close
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_set_cookie_params(0);

session_start();
}

include '../includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        /* Background */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #74ebd5, #ACB6E5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Login Container */
        .login-box {
            width: 350px;
            background: #1e3c72;
            color: #fff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        /* Input Fields */
        .login-box .input-box {
            position: relative;
            margin-bottom: 20px;
        }

        .login-box .input-box input {
            width: 90%;
            padding: 12px 40px 12px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }

        .login-box .input-box i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #555;
            font-size: 18px;
        }

        /* Button */
        .login-box button {
            width: 105%;
            padding: 12px;
            border: none;
            background: #4CAF50;
            color: #fff;
            font-size: 18px;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-box button:hover {
            background: #45a049;
        }

        .error-message {
            margin-top: 10px;
            color: #ff6b6b;
            font-size: 14px;
        }

        /* Forgot link */
        .login-box a {
            color: #ddd;
            font-size: 14px;
            text-decoration: none;
        }

        .login-box a:hover {
            text-decoration: underline;
        }
    </style>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="login-box">
    <h2>ADMIN LOGIN</h2>
    <form method="POST">
        <div class="input-box">
            <input type="text" name="username" placeholder="Username" required>
            <i class="fas fa-user"></i>
        </div>
        <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
            <i class="fas fa-lock"></i>
        </div>
        <button type="submit">Login</button>
    </form>
    <?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>
    <a href="#">Forgot Password?</a>
</div>

</body>
</html>
