<?php
$activePage = "projects";
include "../../includes/db.php";
include "sidebar.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = $db->real_escape_string($_POST['project_name']);
    $client_id = intval($_POST['client_id']);
    $status = $db->real_escape_string($_POST['status']);
    $start_date = $db->real_escape_string($_POST['start_date']);
    $end_date = $db->real_escape_string($_POST['end_date']);

    $insertQuery = "
        INSERT INTO projects (project_name, client_id, status, start_date, end_date)
        VALUES ('$project_name', $client_id, '$status', '$start_date', '$end_date')
    ";

    if ($db->query($insertQuery)) {
        header("Location: project_list.php?msg=Project created successfully");
        exit;
    } else {
        $error = "Error: " . $db->error;
    }
}

// Fetch clients for dropdown
$clientsQuery = $db->query("SELECT id, client_name FROM clients ORDER BY client_name ASC");
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Project | Dantechdevs</title>

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
    </style>
</head>

<body>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">New Project</h2>
            <a href="project_list.php" class="btn btn-secondary btn-sm">Back to Projects</a>
            <button onclick="window.print();" class="btn btn-dark btn-sm">
                <i class="bi bi-printer"></i> Print
            </button>

            <a href="project_import.php" class="btn btn-primary btn-sm">
                <i class="bi bi-cloud-upload"></i> Import
            </a>
        </div>

        <div class="form-container">
            <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="project_name" class="form-label">Project Name</label>
                    <input type="text" class="form-control" id="project_name" name="project_name" required>
                </div>

                <div class="mb-3">
                    <label for="client_id" class="form-label">Client</label>
                    <select class="form-select" id="client_id" name="client_id" required>
                        <option value="">-- Select Client --</option>
                        <?php while ($client = $clientsQuery->fetch_assoc()): ?>
                        <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['client_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>

                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>

                <button type="submit" class="btn btn-success">Create Project</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>