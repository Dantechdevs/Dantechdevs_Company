<?php
$activePage = "clients";
include "../../includes/db.php";
include "sidebar.php";

if (!isset($_GET['id'])) {
    header("Location: client_list.php");
    exit;
}

$id = intval($_GET['id']);
$clientQuery = $db->query("SELECT * FROM clients WHERE id = $id AND deleted = 0");
if (!$clientQuery || $clientQuery->num_rows == 0) {
    header("Location: client_list.php?msg=Client not found");
    exit;
}
$client = $clientQuery->fetch_assoc();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>View Client | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="main-content">
        <h2>Client Details</h2>
        <a href="client_list.php" class="btn btn-secondary btn-sm mb-3">Back</a>
        <div class="projects-container p-3">
            <p><strong>Name:</strong> <?= htmlspecialchars($client['client_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($client['phone']) ?></p>
            <p><strong>Company:</strong> <?= htmlspecialchars($client['company']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($client['address']) ?></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>