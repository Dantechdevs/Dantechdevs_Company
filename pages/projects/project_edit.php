<?php
$activePage = "projects";
include "../../includes/db.php";
include "sidebar.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: project_list.php");
    exit;
}

$project_id = intval($_GET['id']);

// Fetch project details
$projectQuery = $db->query("
    SELECT * FROM projects WHERE id = $project_id
");

if (!$projectQuery || $projectQuery->num_rows === 0) {
    header("Location: project_list.php?msg=Project not found");
    exit;
}

$project = $projectQuery->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = $db->real_escape_string($_POST['project_name']);
    $client_id = intval($_POST['client_id']);
    $status = $db->real_escape_string($_POST['status']);
    $start_date = $db->real_escape_string($_POST['start_date']);
    $end_date = $db->real_escape_string($_POST['end_date']);
    $description = $db->real_escape_string($_POST['description']);

    $updateQuery = "
        UPDATE projects
        SET project_name='$project_name', client_id=$client_id, status='$status', start_date='$start_date', end_date='$end_date', description='$description'
        WHERE id=$project_id
    ";

    if ($db->query($updateQuery)) {
        header("Location: project_view.php?id=$project_id&msg=Project updated successfully");
        exit;
    } else {
        $error = "Error: " . $db->error;
    }
}

// Fetch clients for dropdown
$clientsQuery = $db->query("SELECT id, client_name, contact_email FROM clients ORDER BY client_name ASC");

// Prepare clients array for JS live preview
$clientsArray = [];
while ($client = $clientsQuery->fetch_assoc()) {
    $clientsArray[$client['id']] = $client;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Project | Dantechdevs</title>

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

        .form-container {
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
            margin-bottom: 0.5rem;
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

        .client-preview {
            margin-top: 0.25rem;
            font-size: 0.9rem;
            color: var(--muted);
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
            <h2 class="mb-0">Edit Project</h2>
            <a href="project_view.php?id=<?= $project['id'] ?>" class="btn btn-secondary btn-sm">Back to Project</a>
        </div>

        <div class="form-container">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="project_name" class="form-label">Project Name</label>
                    <input type="text" class="form-control" id="project_name" name="project_name"
                        value="<?= htmlspecialchars($project['project_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="client_id" class="form-label">Client</label>
                    <select class="form-select" id="client_id" name="client_id" required>
                        <option value="">-- Select Client --</option>
                        <?php foreach ($clientsArray as $id => $client): ?>
                            <option value="<?= $id ?>" <?= $id == $project['client_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($client['client_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="clientPreview" class="client-preview">
                        <?= htmlspecialchars($clientsArray[$project['client_id']]['client_name'] ?? '') ?>
                        (<?= htmlspecialchars($clientsArray[$project['client_id']]['contact_email'] ?? '') ?>)
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Preview</label><br>
                    <?php $statusClass = 'status-' . strtolower(str_replace(' ', '_', $project['status'])); ?>
                    <span id="statusPreview" class="status-badge <?= $statusClass ?>">
                        <?= htmlspecialchars($project['status']) ?>
                    </span>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Pending" <?= $project['status'] == 'Pending' ? 'selected' : '' ?>>Pending
                        </option>
                        <option value="In Progress" <?= $project['status'] == 'In Progress' ? 'selected' : '' ?>>In
                            Progress</option>
                        <option value="Completed" <?= $project['status'] == 'Completed' ? 'selected' : '' ?>>Completed
                        </option>
                        <option value="Cancelled" <?= $project['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="<?= htmlspecialchars($project['start_date']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="<?= htmlspecialchars($project['end_date']) ?>">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"
                        rows="4"><?= htmlspecialchars($project['description']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Project</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const statusSelect = document.getElementById('status');
        const statusPreview = document.getElementById('statusPreview');
        const clientSelect = document.getElementById('client_id');
        const clientPreview = document.getElementById('clientPreview');

        const statusClasses = {
            'Pending': 'status-pending',
            'In Progress': 'status-in_progress',
            'Completed': 'status-completed',
            'Cancelled': 'status-cancelled'
        };

        // Live status badge update
        statusSelect.addEventListener('change', function() {
            const selected = statusSelect.value;
            statusPreview.className = 'status-badge ' + statusClasses[selected];
            statusPreview.textContent = selected;
        });

        // Live client preview
        const clients = <?= json_encode($clientsArray) ?>;
        clientSelect.addEventListener('change', function() {
            const selectedId = clientSelect.value;
            if (clients[selectedId]) {
                clientPreview.textContent = clients[selectedId]['client_name'] + ' (' + clients[selectedId][
                    'contact_email'
                ] + ')';
            } else {
                clientPreview.textContent = '';
            }
        });
    </script>
</body>

</html>