<?php
/*
================================================================================
File: reset_password.php
Purpose: Reset User Password (Handles both form display & update)
Author: Dantechdevs
Description:
    - Verifies reset token
    - Allows user to enter new password
    - Updates password in database
================================================================================
*/

session_start();
include "../includes/db.php"; // DB connection

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if (!$token) {
    die('Invalid password reset link.');
}

// Validate token in password_resets table
$stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$reset = $result->fetch_assoc();

if (!$reset) {
    die('This reset link is invalid or has expired.');
}

// FORM SUBMIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($password) || empty($confirm_password)) {
        $error = "Both fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Hash the new password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Update user password
        $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update->bind_param("ss", $hashed, $reset['email']);

        if ($update->execute()) {
            // Delete token after success
            $del = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $del->bind_param("s", $token);
            $del->execute();

            $success = "Password updated successfully. 
                <a href='../login.php' class='btn btn-success mt-3'>Go to Login</a>";
        } else {
            $error = "Failed to update password. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    body {
        background: #f0f2f5;
        font-family: 'Segoe UI', sans-serif;
    }

    .reset-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .reset-card {
        background: #fff;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    .reset-card h3 {
        color: #198754;
        margin-bottom: 25px;
        font-weight: bold;
    }

    .input-group-text {
        background: #198754;
        color: #fff;
        border: none;
    }

    .btn-reset {
        background: #198754;
        border: none;
        width: 100%;
    }

    .btn-reset:hover {
        background: #157347;
    }

    .error-message {
        color: #dc3545;
        margin-bottom: 15px;
    }

    .success-message {
        color: #198754;
        margin-bottom: 15px;
    }
    </style>
</head>

<body>

    <div class="reset-container">
        <div class="reset-card">

            <h3><i class="bi bi-key-fill me-2"></i>Reset Password</h3>

            <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="success-message"><?= $success ?></div>
            <?php else: ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" name="password" required
                            placeholder="Enter new password">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" name="confirm_password" required
                            placeholder="Confirm password">
                    </div>
                </div>

                <button type="submit" class="btn btn-reset btn-lg">Reset Password</button>
            </form>

            <?php endif; ?>

        </div>
    </div>

</body>

</html>