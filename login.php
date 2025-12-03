<?php
/*
================================================================================
File: login.php
Purpose: User Login Page
Author: Dantechdevs
Description:
    - Handles user login
    - Shows error messages
    - Uses sessions
    - Professional green & white theme
================================================================================
*/
session_start();

// Redirect if already logged in
if(isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}

// Initialize variables
$error = '';
$username = '';

// Handle login form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Example: Replace this with your DB authentication
    $dummyUser = [
        'username' => 'admin',
        'password' => 'admin123', // In production, use hashed passwords!
        'role' => 'Administrator',
        'email' => 'admin@example.com'
    ];

    if($username === $dummyUser['username'] && $password === $dummyUser['password']){
        // Set session variables
        $_SESSION['username'] = $dummyUser['username'];
        $_SESSION['role'] = $dummyUser['role'];
        $_SESSION['email'] = $dummyUser['email'];

        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    body {
        background: #f0f2f5;
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-card {
        background: #fff;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        transition: transform 0.3s;
    }

    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .login-card h3 {
        color: #198754;
        /* Green theme */
        margin-bottom: 25px;
        font-weight: bold;
        text-align: center;
    }

    .form-control:focus {
        border-color: #198754;
        box-shadow: none;
    }

    .btn-login {
        background-color: #198754;
        border: none;
        width: 100%;
    }

    .btn-login:hover {
        background-color: #157347;
    }

    .input-group-text {
        background: #198754;
        color: #fff;
        border: none;
    }

    .error-message {
        color: #dc3545;
        text-align: center;
        margin-bottom: 15px;
    }

    @media(max-width: 576px) {
        .login-card {
            padding: 30px 20px;
        }
    }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-card">
            <h3><i class="bi bi-lock-fill me-2"></i>Login</h3>

            <?php if($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Enter username" value="<?= htmlspecialchars($username) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter password" required>
                    </div>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-login btn-lg">Login</button>
                </div>

                <div class="text-center">
                    <small class="text-muted">Forgot password? <a href="settings.php">Reset here</a></small>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>