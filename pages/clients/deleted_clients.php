<?php
$activePage = "clients";
include "../../includes/db.php";
include "sidebar.php";

$deletedClientsQuery = $db->query("SELECT * FROM clients WHERE deleted=1 ORDER BY id DESC");
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Deleted Clients | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="main-content">
        <h2>Deleted Clients</h2>
        <a href="client_list.php" class="btn btn-secondary btn-sm mb-3">Back to Clients</a>

        <div class="projects-container p-3">
            <table class="table table-striped">
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
                    <?php if($deletedClientsQuery->num_rows>0): ?>
                    <?php while($client=$deletedClientsQuery->fetch_assoc()): ?>
                    <tr>
                        <td><?= $client['id'] ?></td>
                        <td><?= htmlspecialchars($client['client_name']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['phone']) ?></td>
                        <td><?= htmlspecialchars($client['company']) ?></td>
                        <td>
                            <a href="client_restore.php?id=<?= $client['id'] ?>"
                                class="btn btn-success btn-sm">Restore</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No deleted clients</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>