<?php
session_start();

// Example user data (replace with your database queries)
$username = $_SESSION['username'] ?? 'User';
$email = $_SESSION['email'] ?? 'user@example.com';
$role = $_SESSION['role'] ?? 'Administrator';

// Set correct timezone
date_default_timezone_set('Africa/Nairobi');

// Determine greeting
$hour = (int) date('H');
if ($hour >= 5 && $hour < 12) {
    $greeting = "Good morning";
} elseif ($hour >= 12 && $hour < 17) {
    $greeting = "Good afternoon";
} elseif ($hour >= 17 && $hour < 21) {
    $greeting = "Good evening";
} else {
    $greeting = "Good night";
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    body {
        background-color: #f8f9fa;
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    .profile-card {
        max-width: 500px;
        margin: auto;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .avatar-circle {
        width: 70px;
        height: 70px;
        font-size: 28px;
        background-color: #0d6efd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
    }

    .greeting {
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.2rem;
        font-weight: 500;
    }

    .profile-details div {
        margin-bottom: 10px;
    }

    .btn-rounded {
        border-radius: 25px;
    }
    </style>
</head>

<body>

    <div class="profile-card">

        <div class="greeting"><?= $greeting ?>, <?= htmlspecialchars($username) ?>!</div>

        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="avatar-circle"><?= strtoupper(substr($username, 0, 1)) ?></div>
            <div>
                <h4 class="mb-0"><?= htmlspecialchars($username) ?></h4>
                <small class="text-muted"><?= htmlspecialchars($role) ?></small>
            </div>
        </div>

        <hr>

        <div class="profile-details">
            <div><strong>Email:</strong> <?= htmlspecialchars($email) ?></div>
            <div><strong>Username:</strong> <?= htmlspecialchars($username) ?></div>
            <div><strong>Role:</strong> <?= htmlspecialchars($role) ?></div>
        </div>

        <div class="mt-4 text-center">
            <a href="settings.php" class="btn btn-primary btn-rounded">
                <i class="bi bi-pencil-square me-1"></i> Edit Profile
            </a>
        </div>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>