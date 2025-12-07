<?php
// backend/forgot_password.php
session_start();
require_once __DIR__ . '/../includes/db.php'; // Make sure this connects to your MySQL database

// Initialize message
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id, full_name FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $token = bin2hex(random_bytes(50));

            // Save token to DB
            $stmt2 = $conn->prepare("UPDATE users SET reset_token=?, token_expiry=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id=?");
            $stmt2->bind_param("si", $token, $user['id']);
            $stmt2->execute();

            // Send email (basic PHP mail example)
            $reset_link = "http://yourdomain.com/backend/reset_password.php?token=" . $token;
            $subject = "Password Reset Request";
            $body = "Hi " . htmlspecialchars($user['full_name']) . ",<br><br>";
            $body .= "Click the link below to reset your password:<br>";
            $body .= "<a href='$reset_link'>$reset_link</a><br><br>";
            $body .= "This link will expire in 1 hour.<br><br>Thanks,<br>Dantechdevs Team";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: noreply@yourdomain.com" . "\r\n";

            if (mail($email, $subject, $body, $headers)) {
                $message = "Password reset link has been sent to your email.";
            } else {
                $message = "Failed to send email. Please try again later.";
            }
        } else {
            $message = "No account found with that email address.";
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
            margin-bottom: 20px;
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

        .message {
            margin-bottom: 15px;
            color: #dc3545;
        }
    </style>
</head>

<body>

    <div class="forgot-container">
        <div class="forgot-card">
            <h3><i class="bi bi-envelope-fill me-2"></i>Forgot Password</h3>

            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST" action="forgot_password.php">
                <div class="mb-3 text-start">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                            required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-send btn-lg">Send Reset Link</button>
                </div>

                <div class="mt-3">
                    <small>Remembered your password? <a href="../login.php">Login here</a></small>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>