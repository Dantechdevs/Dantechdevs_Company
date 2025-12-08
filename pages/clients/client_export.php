<?php
$activePage = "clients";
include "../../includes/db.php";
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Export Clients | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-content {
            margin-left: 260px;
            padding: 20px;
            min-height: 100vh;
            background-color: #f6f8fb;
        }

        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }
        }

        .card {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 6px 18px rgba(39, 53, 66, 0.06);
            max-width: 900px;
            margin: auto;
        }
    </style>
</head>

<body>

    <?php include "sidebar.php"; ?>

    <div class="main-content">
        <h2>Export Clients</h2>
        <a href="client_list.php" class="btn btn-secondary btn-sm mb-3">Back to Clients</a>

        <div class="card">
            <p>Click the button below to download all clients as a CSV file.</p>
            <form method="POST" action="client_export_download.php">
                <button type="submit" class="btn btn-success">Download CSV</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>