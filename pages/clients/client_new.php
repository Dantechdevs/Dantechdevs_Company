<?php
$activePage = "clients";
include "../../includes/db.php";
include "sidebar.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = $db->real_escape_string($_POST['client_name']);
    $email = $db->real_escape_string($_POST['email']);
    $phone = $db->real_escape_string($_POST['phone']);
    $company = $db->real_escape_string($_POST['company']);
    $address = $db->real_escape_string($_POST['address']);

    $insertQuery = "
        INSERT INTO clients (client_name, email, phone, company, address)
        VALUES ('$client_name', '$email', '$phone', '$company', '$address')
    ";

    if ($db->query($insertQuery)) {
        header("Location: client_list.php?msg=Client added successfully");
        exit;
    } else {
        $error = "Error: " . $db->error;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Client | Dantechdevs</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ===========================
           ROOT VARIABLES & MAIN CSS
        =========================== */
        :root {
            --brand-green: #16a34a;
            --brand-green-dark: #128a3b;
            --muted: #9aa0a6;
            --card-bg: #ffffff;
            --page-bg: #f6f8fb;
            --text-dark: #273544;
            --border-light: #e5e7eb;
        }

        .main-content {
            margin-left: 260px;
            padding: 1.5rem;
            min-height: 100vh;
            background-color: var(--page-bg);
        }

        .form-container {
            background-color: var(--card-bg);
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 6px 18px rgba(39, 53, 66, 0.06);
            max-width: 1000px;
            margin: auto;
        }

        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }

        /* Sidebar CSS reused exactly */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar-modern {
            width: 260px;
            flex-shrink: 0;
            background-color: #fff;
            border-right: 1px solid var(--border-light);
            padding: 16px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            color: #111;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .menu-link.active,
        .menu-link:hover {
            background-color: var(--brand-green);
            color: #fff;
        }

        .menu-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #555;
        }
    </style>
</head>

<body>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">New Client</h2>
            <a href="client_list.php" class="btn btn-secondary btn-sm">Back to Clients</a>
        </div>

        <div class="form-container">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="client_name" class="form-label">Client Name</label>
                    <input type="text" class="form-control" id="client_name" name="client_name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>

                <div class="mb-3">
                    <label for="company" class="form-label">Company</label>
                    <input type="text" class="form-control" id="company" name="company">
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Create Client</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>