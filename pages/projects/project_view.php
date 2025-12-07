<?php
$activePage = "projects";
include "../../includes/db.php";
include "sidebar.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: project_list.php");
    exit;
}

$project_id = intval($_GET['id']);

$projectQuery = $db->query("
    SELECT p.*, c.client_name
    FROM projects p
    LEFT JOIN clients c ON p.client_id = c.id
    WHERE p.id = $project_id
");

if (!$projectQuery || $projectQuery->num_rows === 0) {
    header("Location: project_list.php?msg=Project not found");
    exit;
}

$project = $projectQuery->fetch_assoc();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Project | Dantechdevs</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
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

    .details-container {
        background-color: var(--card-bg);
        padding: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 6px 18px rgba(39, 53, 66, 0.06);
        max-width: 700px;
        margin: auto;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
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
            <h2 class="mb-0">Project Details</h2>
            <a href="project_list.php" class="btn btn-secondary btn-sm">Back to Projects</a>
        </div>

        <div class="details-container">
            <h4><?= htmlspecialchars($project['project_name']) ?></h4>
            <p><strong>Client:</strong> <?= htmlspecialchars($project['client_name']) ?></p>
            <p>
                <strong>Status:</strong>
                <?php $statusClass = 'status-' . strtolower(str_replace(' ', '_', $project['status'])); ?>
                <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($project['status']) ?></span>
            </p>
            <p><strong>Start Date:</strong> <?= htmlspecialchars($project['start_date']) ?></p>
            <p><strong>End Date:</strong> <?= htmlspecialchars($project['end_date']) ?></p>
            <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($project['description'] ?? 'N/A')) ?></p>

            <div class="mt-3">
                <a href="project_edit.php?id=<?= $project['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                <a href="project_delete.php?id=<?= $project['id'] ?>"
                    class="btn btn-danger btn-sm delete-link">Delete</a>
            </div>
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
    </script>
</body>

</html>