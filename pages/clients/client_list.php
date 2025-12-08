<?php
$activePage = "clients";
include "../../includes/db.php";

// Count total clients
$totalClientsQuery = $db->query("SELECT COUNT(*) AS total FROM clients WHERE deleted=0");
$totalClients = 0;
if ($totalClientsQuery && $row = $totalClientsQuery->fetch_assoc()) {
    $totalClients = $row['total'];
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Clients | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    <?php include __DIR__ . '/../../assets/css/projects.css';
    ?>

    /* Flex layout wrapper */
    .layout {
        display: flex;
        min-height: 100vh;
    }

    .sidebar-modern {
        width: 220px;
        flex-shrink: 0;
        position: fixed;
        height: 100%;
        overflow-y: auto;
    }

    .main-content {
        margin-left: 220px;
        flex: 1;
        padding: 20px;
        background-color: #f6f8fb;
    }

    .projects-container {
        margin-top: 20px;
        overflow-x: auto;
    }

    @media (max-width: 991px) {
        .layout {
            flex-direction: column;
        }

        .sidebar-modern {
            width: 100%;
            position: relative;
        }

        .main-content {
            margin-left: 0;
        }
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }

        .projects-container,
        .projects-container * {
            visibility: visible;
        }

        .print-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
        }

        .print-subheader {
            font-size: 12pt;
            font-weight: normal;
            margin-top: 4px;
        }

        .projects-container {
            position: absolute;
            top: 80px;
            left: 0;
            width: 100%;
            margin: 0;
        }

        .projects-table {
            width: 100%;
            border-collapse: collapse;
        }

        .projects-table th,
        .projects-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            word-wrap: break-word;
        }

        .projects-table th {
            background-color: #f2f2f2;
        }

        /* Hide Actions column */
        .projects-table th:last-child,
        .projects-table td:last-child {
            display: none;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        .projects-table tr {
            page-break-inside: avoid;
        }

        /* Page number */
        @page {
            margin: 50px 20px;
        }

        body::after {
            content: "Page "counter(page) " of "counter(pages);
            position: fixed;
            bottom: 0;
            right: 0;
            font-size: 12pt;
        }
    }
    </style>
</head>

<body>
    <div class="layout">
        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>

        <!-- Main content -->
        <div class="main-content">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="client_new.php" class="btn btn-success btn-sm">+ New Client</a>
                <a href="deleted_clients.php" class="btn btn-warning btn-sm">Deleted Clients</a>
                <a href="client_import.php" class="btn btn-info btn-sm">Import</a>
                <a href="client_export.php" class="btn btn-primary btn-sm">Export</a>
                <button onclick="window.print();" class="btn btn-secondary btn-sm">Print</button>
                <a href="clients_chat.php" class="btn btn-dark btn-sm">Chat</a>
            </div>

            <!-- Print Header -->
            <div class="print-header">
                Clients List
                <div class="print-subheader">Total Clients: <?= $totalClients ?> | Date: <?= date('d M Y') ?></div>
            </div>

            <!-- Table -->
            <div class="projects-container">
                <table class="table table-striped projects-table" id="clientsTable">
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
                        <?php
                        $clientsQuery = $db->query("SELECT * FROM clients WHERE deleted=0 ORDER BY id DESC");
                        if ($clientsQuery && $clientsQuery->num_rows > 0):
                            $counter = 1;
                            while ($client = $clientsQuery->fetch_assoc()):
                                $clientName = ucwords(strtolower($client['client_name']));
                        ?>
                        <tr>
                            <td><?= $counter ?></td>
                            <td><?= htmlspecialchars($clientName) ?></td>
                            <td><?= htmlspecialchars($client['email']) ?></td>
                            <td><?= htmlspecialchars($client['phone']) ?></td>
                            <td><?= htmlspecialchars($client['company_name']) ?></td>
                            <td class="projects-actions">
                                <a href="client_view.php?id=<?= $client['id'] ?>" class="btn btn-info btn-sm">View</a>
                                <a href="client_edit.php?id=<?= $client['id'] ?>"
                                    class="btn btn-primary btn-sm">Edit</a>
                                <a href="client_delete.php?id=<?= $client['id'] ?>"
                                    class="btn btn-danger btn-sm delete-link">Delete</a>
                                <a href="clients_chat.php?client_id=<?= $client['id'] ?>"
                                    class="btn btn-success btn-sm">Chat</a>
                            </td>
                        </tr>
                        <?php
                                $counter++;
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="6" class="text-center">No clients found.</td>
                        </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this client?')) {
                    e.preventDefault();
                }
            });
        });
    });
    </script>
</body>

</html>