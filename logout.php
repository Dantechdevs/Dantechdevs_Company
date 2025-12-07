<?php
/*
===============================================================================
File: logout.php
Purpose: Logout Page
Author: Dantechdevs
Version: v1.0
===============================================================================
*/
session_start();
session_unset();
session_destroy();
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
        padding: 20px;
    }

    .logout-card {
        background: #fff;
        padding: 40px 35px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        max-width: 450px;
        width: 100%;
        animation: fadeIn 0.4s ease-in-out;
    }

    .logout-card h3 {
        color: #198754;
        font-weight: bold;
    }

    .btn-login {
        background-color: #198754;
        border: none;
        padding: 12px 20px;
        font-size: 17px;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-login:hover {
        background-color: #157347;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    </style>
</head>

<body>

    <div class="logout-container">
        <div class="logout-card">
            <i class="bi bi-check-circle-fill" style="font-size: 55px; color: #198754;"></i>
            <h3 class="mt-3">You Have Logged Out</h3>
            <p class="text-muted mt-2">
                Your session has been securely closed.
            </p>

            <a href="login.php" class="btn btn-login mt-3">
                <i class="bi bi-box-arrow-in-right me-1"></i> Go to Login
            </a>
        </div>
    </div>

</body>

</html>