<?php
$activePage = "clients";
include "../../includes/db.php";
include "sidebar.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = $db->real_escape_string($_POST['client_name']);
    $email = $db->real_escape_string($_POST['email']);
    $phone = $db->real_escape_string($_POST['phone']);
    $company_name = $db->real_escape_string($_POST['company']); // map form input to DB column
    $address = $db->real_escape_string($_POST['address']);

    $insertQuery = "
        INSERT INTO clients (client_name, email, phone, company_name, address)
        VALUES ('$client_name', '$email', '$phone', '$company_name', '$address')
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

    <link href="../../css/index.css" rel="stylesheet">
    <style>
    .main-content {
        margin-left: 260px;
        /* sidebar width */
        padding: 1.5rem;
        min-height: 100vh;
        background-color: #f6f8fb;
    }

    .form-container {
        background-color: #fff;
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