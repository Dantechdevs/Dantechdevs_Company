<?php
/*
================================================================================
File: profile/settings.php
Purpose: User Profile Settings
Author: Dantechdevs
Description:
    - Allows user to edit profile (username, email)
    - Allows password change
    - Responsive and professional UI
================================================================================
*/
session_start();

// User data (replace with DB query in real app)
$username = $_SESSION['username'] ?? 'User';
$email = $_SESSION['email'] ?? 'user@example.com';
$role = $_SESSION['role'] ?? 'Administrator';
date_default_timezone_set('Africa/Nairobi');

// Greeting
$hour = (int) date('H');
$greeting = ($hour >= 5 && $hour < 12) ? "Good morning" : (($hour >= 12 && $hour < 17) ? "Good afternoon" : (($hour >= 17 && $hour < 21) ? "Good evening" : "Good night"));

// Handle form submission
$success_msg = '';
$error_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username'] ?? $username);
    $new_email = trim($_POST['email'] ?? $email);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Password validation
    if (!empty($new_password) && $new_password !== $confirm_password) {
        $error_msg = "New password and confirmation do not match.";
    } else {
        // Update session (replace with DB update in production)
        $_SESSION['username'] = $new_username;
        $_SESSION['email'] = $new_email;
        $username = $new_username;
        $email = $new_email;
        $success_msg = "Profile updated successfully!";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings | Dantechdevs</title>
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
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .settings-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .settings-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #6610f2;
        }

        .btn-rounded {
            border-radius: 50px;
            padding: 10px 25px;
        }

        .alert {
            border-radius: 15px;
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
                <a href="profile.php" class="btn btn-secondary btn-rounded"><i
                        class="bi bi-arrow-left me-1"></i>Back</a>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="settings-card">
            <h4 class="mb-3">Edit Profile</h4>

            <?php if ($success_msg): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_msg) ?></div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>"
                        required>
                </div>

                <hr>
                <h5>Change Password</h5>
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control"
                        placeholder="Leave blank if not changing">
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control"
                        placeholder="Leave blank if not changing">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control"
                        placeholder="Leave blank if not changing">
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-rounded"><i class="bi bi-save me-1"></i> Save
                        Changes</button>
                </div>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>