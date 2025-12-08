<?php
$activePage = "clients";
include "../../includes/db.php";
include "sidebar.php";

// Fetch clients (non-deleted)
$clientsQuery = $db->query("SELECT * FROM clients WHERE deleted=0 ORDER BY id DESC");
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Clients | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    <?php include __DIR__ . '/../../assets/css/projects.css'; // reuse your projects CSS ?>
    </style>
</head>

<body>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Clients</h2>
            <div>
                <a href="client_new.php" class="btn btn-success btn-sm">+ New Client</a>
                <a href="deleted_clients.php" class="btn btn-warning btn-sm">Deleted Clients</a>
                <a href="client_import.php" class="btn btn-info btn-sm">Import</a>
                <a href="client_export.php" class="btn btn-primary btn-sm">Export</a>
                <button onclick="window.print();" class="btn btn-secondary btn-sm">Print</button>
            </div>
        </div>

        <div class="projects-container">
            <table class="table table-striped projects-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Company</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($clientsQuery && $clientsQuery->num_rows > 0): ?>
                    <?php while($client = $clientsQuery->fetch_assoc()): ?>
                    <tr>
                        <td><?= $client['id'] ?></td>
                        <td><?= htmlspecialchars($client['client_name']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['phone']) ?></td>
                        <td><?= htmlspecialchars($client['company']) ?></td>
                        <td class="projects-actions">
                            <a href="client_view.php?id=<?= $client['id'] ?>" class="btn btn-info btn-sm">View</a>
                            <a href="client_edit.php?id=<?= $client['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="client_delete.php?id=<?= $client['id'] ?>"
                                class="btn btn-danger btn-sm delete-link">Delete</a>
                            <a href="clients_chat.php?client_id=<?= $client['id'] ?>"
                                class="btn btn-success btn-sm">Chat</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No clients found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this client?')) e
            .preventDefault();
            });
        });
    });
    </script>
</body>

</html>