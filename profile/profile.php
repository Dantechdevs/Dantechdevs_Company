<?php
/*
================================================================================
File: profile/profile.php
Purpose: Professional Profile Dashboard Overview
Author: Dantechdevs
Description:
    - Shows user info, greeting, and dynamic system stats
    - Uses sidebar.php for navigation
    - Fully responsive and modern design
    - Replaces generic Posts/Messages/Notifications with Projects/Clients/Tasks
================================================================================
*/
session_start();

// Example user data (replace with DB queries)
$username = $_SESSION['username'] ?? 'User';
$email = $_SESSION['email'] ?? 'user@example.com';
$role = $_SESSION['role'] ?? 'Administrator';
date_default_timezone_set('Africa/Nairobi');

// Greeting based on current time
$hour = (int) date('H');
$greeting = ($hour >= 5 && $hour < 12) ? "Good morning" : (($hour >= 12 && $hour < 17) ? "Good afternoon" : (($hour >= 17 && $hour < 21) ? "Good evening" : "Good night"));

// Example dashboard stats (replace with real DB queries)
$total_projects = 34;
$total_clients = 12;
$pending_tasks = 5;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile | Dantechdevs</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
        }

        .content {
            margin-left: 220px;
            padding: 30px;
            transition: 0.3s;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            font-size: 32px;
            background: linear-gradient(135deg, #198754, #0d6efd);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .card-stats {
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-details {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        @media(max-width:768px) {
            .content {
                margin-left: 60px;
                padding: 15px;
            }

            .avatar-circle {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>

    <div class="content">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3 flex-wrap mb-4">
            <div class="avatar-circle"><?= strtoupper(substr($username, 0, 1)) ?></div>
            <div>
                <h3 class="mb-0"><?= htmlspecialchars($username) ?></h3>
                <small class="text-muted"><?= htmlspecialchars($role) ?></small>
                <p class="mt-1"><?= $greeting ?>!</p>
            </div>
            <div class="ms-auto">
                <a href="settings.php" class="btn btn-success btn-rounded">
                    <i class="bi bi-pencil-square me-1"></i>Edit Profile
                </a>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card-stats">
                    <h5><?= $total_projects ?></h5>
                    <small>Projects</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-stats">
                    <h5><?= $total_clients ?></h5>
                    <small>Clients</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-stats">
                    <h5><?= $pending_tasks ?></h5>
                    <small>Pending Tasks</small>
                </div>
            </div>
        </div>

        <!-- Detailed Profile Info -->
        <div class="card-details">
            <h5>Profile Details</h5>
            <hr>
            <div class="row">
                <div class="col-md-4"><strong>Email:</strong> <?= htmlspecialchars($email) ?></div>
                <div class="col-md-4"><strong>Username:</strong> <?= htmlspecialchars($username) ?></div>
                <div class="col-md-4"><strong>Role:</strong> <?= htmlspecialchars($role) ?></div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>