<?php
$activePage = "projects";
include "../../includes/db.php";
include "sidebar.php";
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projects | Dantechdevs</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ===========================
           ROOT VARIABLES
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

        /* ===========================
           LAYOUT: SIDEBAR + MAIN
        =========================== */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
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

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            margin-bottom: 1.5rem;
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

        /* Main content */
        .main-content {
            margin-left: 260px;
            /* same as sidebar width */
            padding: 1.5rem;
            min-height: 100vh;
            background-color: var(--page-bg);
            flex-grow: 1;
        }

        /* ===========================
           PROJECTS CONTAINER
        =========================== */
        .projects-container {
            background-color: var(--card-bg);
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 6px 18px rgba(39, 53, 66, 0.06);
            overflow-x: auto;
            transition: all 0.3s ease;
        }

        /* Projects Table */
        .projects-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            min-width: 600px;
        }

        .projects-table th,
        .projects-table td {
            vertical-align: middle;
            padding: 0.75rem 1rem;
            text-align: left;
            border-top: 1px solid var(--border-light);
        }

        .projects-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #111;
            border-bottom: 2px solid var(--border-light);
        }

        .projects-table tbody tr {
            transition: background 0.2s ease, transform 0.2s ease;
            cursor: pointer;
        }

        .projects-table tbody tr:hover {
            background-color: rgba(22, 163, 74, 0.08);
            transform: translateY(-1px);
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
            transition: all 0.2s ease;
        }

        .status-pending {
            background-color: #facc15;
            color: #111;
        }

        .status-in_progress {
            background-color: #3b82f6;
            color: #fff;
        }

        .status-completed {
            background-color: var(--brand-green);
            color: #fff;
        }

        .status-cancelled {
            background-color: #dc2626;
            color: #fff;
        }

        /* Action buttons */
        .projects-actions .btn {
            margin-right: 0.25rem;
            padding: 0.35rem 0.6rem;
            font-size: 0.85rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .projects-actions .btn:hover {
            transform: translateY(-1px);
        }

        /* ===========================
           RESPONSIVE
        =========================== */
        @media (max-width: 991px) {
            .sidebar-modern {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .projects-table th,
            .projects-table td {
                padding: 0.5rem 0.75rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Projects</h2>
            <a href="project_new.php" class="btn btn-success btn-sm">+ New Project</a>
            <a href="project_delete.php" class="btn btn-danger btn-sm">Deleted Projects</a>
            <button onclick="printProjects()" class="btn btn-secondary btn-sm">
                <i class="bi bi-printer"></i> Print
        </div>

        <?php
        $projectsQuery = $db->query("
        SELECT p.id, p.project_name, c.client_name, p.status, p.start_date, p.end_date
        FROM projects p
        LEFT JOIN clients c ON p.client_id = c.id
        ORDER BY p.id DESC
    ");
        ?>

        <div class="projects-container">
            <table class="table table-striped projects-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // fetch projects list as you do earlier
                    $projectsQuery = $db->query("
    SELECT projects.id, projects.project_name, clients.client_name, projects.status, projects.start_date, projects.end_date
    FROM projects
    LEFT JOIN clients ON projects.client_id = clients.id
    ORDER BY projects.id DESC
");

                    if ($projectsQuery && $projectsQuery->num_rows > 0):
                        while ($project = $projectsQuery->fetch_assoc()):
                            // Get invoices for project (sum)
                            $pid = (int)$project['id'];
                            $invQ = $db->query("SELECT id, total_amount FROM invoices WHERE project_id = $pid");

                            $projectTotal = 0;
                            $projectPaid = 0;

                            while ($inv = $invQ->fetch_assoc()) {
                                $projectTotal += (float)$inv['total_amount'];

                                $invPaid = $db->query(
                                    "
                SELECT IFNULL(SUM(amount),0) AS tp 
                FROM payments 
                WHERE invoice_id = " . (int)$inv['id']
                                )->fetch_assoc()['tp'];

                                $projectPaid += (float)$invPaid;
                            }

                            $balance = $projectTotal - $projectPaid;

                            if ($projectPaid >= $projectTotal && $projectTotal > 0)
                                $pStatus = 'Paid';
                            elseif ($projectPaid > 0)
                                $pStatus = 'Partial';
                            else
                                $pStatus = 'Unpaid';
                    ?>

                            <tr>
                                <td><?= $project['id'] ?></td>
                                <td><?= htmlspecialchars($project['project_name']) ?></td>
                                <td><?= htmlspecialchars($project['client_name']) ?></td>
                                <td><?= htmlspecialchars($project['status']) ?></td>
                                <td><?= htmlspecialchars($project['start_date']) ?></td>
                                <td><?= htmlspecialchars($project['end_date']) ?></td>

                                <!-- ✅ NEW FINANCIAL COLUMNS -->
                                <td><?= number_format($projectTotal, 2) ?></td>
                                <td><?= number_format($projectPaid, 2) ?> (<?= $pStatus ?>)</td>

                                <td>
                                    <a href="project_view.php?id=<?= $project['id'] ?>" class="btn btn-info btn-sm">View</a>
                                    <a href="project_edit.php?id=<?= $project['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="project_delete.php?id=<? $project['id'] ?>" class=btn btn-primary btn-sm">Delete</a>
                                </td>
                            </tr>

                    <?php
                        endwhile;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this project?')) e
                        .preventDefault();
                });
            });
        });
        // Print only the table
        function printProjects() {
            var tableContent = document.querySelector('.projects-container').innerHTML;
            var printWindow = window.open('', '', 'height=600,width=900');
            printWindow.document.write('<html><head><title>Projects</title>');
            printWindow.document.write(
                '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">'
            );
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h3>Projects List</h3>');
            printWindow.document.write(tableContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>

</html>