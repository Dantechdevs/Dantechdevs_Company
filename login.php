<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Include database connection
require __DIR__ . '/includes/db.php';

// Initialize variables
$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        // Prepare SQL query securely
        $stmt = $conn->prepare("SELECT username, password, role, email FROM users WHERE username = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($dbUsername, $dbPassword, $dbRole, $dbEmail);
                $stmt->fetch();

                // Verify password (works if hashed in DB)
                if (password_verify($password, $dbPassword)) {
                    // Set session variables
                    $_SESSION['username'] = $dbUsername;
                    $_SESSION['role'] = $dbRole;
                    $_SESSION['email'] = $dbEmail;

                    // Redirect to dashboard/index
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Invalid username or password!";
                }
            } else {
                $error = "Invalid username or password!";
            }

            $stmt->close();
        } else {
            $error = "Database error: Failed to prepare statement!";
        }
    } else {
        $error = "Please enter both username and password!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | DANTECHDEVS IT COMPANY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f7941d, #0d1b2a);
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            position: relative;
        }

        body::before {
            content: "DANTECHDEVS IT COMPANY";
            position: absolute;
            font-size: 5rem;
            font-weight: bold;
            color: rgba(255, 255, 255, 0.05);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            pointer-events: none;
            z-index: 0;
        }

        .login-card {
            background: #ffffff;
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 1;
        }

        .login-card img.logo {
            margin-bottom: 20px;
            max-width: 120px;
        }

        .login-card h3 {
            color: #f7941d;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .form-control:focus {
            border-color: #f7941d;
            box-shadow: 0 0 5px rgba(247, 148, 29, 0.5);
        }

        .input-group-text {
            background-color: #f7941d;
            color: #fff;
            border: none;
        }

        .btn-login {
            background-color: #f7941d;
            color: #fff;
            font-weight: bold;
            border-radius: 50px;
            padding: 10px 0;
        }

        .btn-login:hover {
            background-color: #d87d00;
        }

        .error-message {
            color: #dc3545;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 500;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <img src="assets/img/logo.jpg" alt="DANTECHDEVS Logo" class="logo">
        <h3><i class="bi bi-lock-fill me-2"></i>Login</h3>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="w-100">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username"
                        value="<?= htmlspecialchars($username) ?>" required>
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
                <small class="text-muted">Forgot password? <a href="backend/reset_password.php">Reset here</a></small>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>