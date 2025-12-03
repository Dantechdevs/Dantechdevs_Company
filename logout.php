<?php
/*
================================================================================
File: logout.php
Purpose: Logout User and Show Confirmation
Author: Dantechdevs
Description:
    - Ends the current session securely
    - Shows logout confirmation message
    - Provides button to return to login page
================================================================================
*/

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get username for personalized message before destroying session
$username = $_SESSION['username'] ?? 'User';

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Optional: Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    body {
        background: #f0f2f5;
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    .logout-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .logout-card {
        background: #fff;
        padding: 50px 35px;
        border-radius: 15px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
        transition: transform 0.3s;
    }

    .logout-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .logout-card i {
        font-size: 60px;
        color: #198754;
        margin-bottom: 20px;
    }

    .logout-card h3 {
        color: #198754;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .logout-card p {
        color: #6c757d;
        margin-bottom: 30px;
    }

    .btn-login {
        background-color: #198754;
        color: #fff;
        font-weight: 600;
        font-size: 1rem;
        padding: 12px 0;
        width: 100%;
        border-radius: 50px;
        transition: background 0.3s, transform 0.2s;
    }

    .btn-login:hover {
        background-color: #157347;
        transform: translateY(-2px);
    }

    @media(max-width: 576px) {
        .logout-card {
            padding: 35px 20px;
        }
    }
    </style>
</head>

<body>

    <div class="logout-container">
        <div class="logout-card">
            <i class="bi bi-box-arrow-right"></i>
            <h3>Goodbye, <?= htmlspecialchars($username) ?>!</h3>
            <p>You have successfully logged out from Dantechdevs system.</p>
            <a href="login.php" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i> Go to Login
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>