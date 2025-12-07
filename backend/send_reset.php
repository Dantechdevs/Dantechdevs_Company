<?php
/*
================================================================================
File: send_reset.php
Purpose: Handle Forgot Password Requests
Author: Dantechdevs
Description:
    - Accepts user email
    - Generates a reset token
    - Sends reset link via email
================================================================================
*/

session_start();
require_once(__DIR__ . "/../includes/db.php");
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = "Please enter your email.";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $error = "No account found with this email.";
        } else {
            // Generate token
            $token = bin2hex(random_bytes(50));
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Store in password_resets table
            $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $token, $expires);
            if ($stmt->execute()) {
                // Send email
                $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/backend/reset_password.php?token=" . $token;

                $subject = "Dantechdevs Password Reset";
                $message = "Hi " . $user['full_name'] . ",\n\n";
                $message .= "You requested a password reset. Click the link below to reset your password:\n\n";
                $message .= $reset_link . "\n\n";
                $message .= "This link expires in 1 hour.\n\n";
                $message .= "If you did not request this, please ignore this email.\n\n";
                $headers = "From: noreply@dantechdevs.com";

                // Use mail() or PHPMailer
                if (mail($email, $subject, $message, $headers)) {
                    $success = "A password reset link has been sent to your email.";
                } else {
                    $error = "Failed to send email. Try again later.";
                }
            } else {
                $error = "Something went wrong. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .forgot-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .forgot-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .forgot-card h3 {
            color: #198754;
            margin-bottom: 25px;
            font-weight: bold;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: none;
        }

        .btn-send {
            background-color: #198754;
            border: none;
            width: 100%;
        }

        .btn-send:hover {
            background-color: #157347;
        }

        .input-group-text {
            background: #198754;
            color: #fff;
            border: none;
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }

        .success-message {
            color: #198754;
            margin-bottom: 15px;
        }

        @media(max-width:576px) {
            .forgot-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>

    <div class="forgot-container">
        <div class="forgot-card">
            <h3><i class="bi bi-envelope-fill me-2"></i>Forgot Password</h3>

            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message"><?= $success ?></div>
                <a href="../login.php" class="btn btn-success mt-3">Back to Login</a>
            <?php else: ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                                required>
                        </div>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-send btn-lg">Send Reset Link</button>
                    </div>
                    <div class="text-center">
                        <a href="../login.php" class="text-muted">Back to Login</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>