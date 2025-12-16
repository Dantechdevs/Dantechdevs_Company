<?php
$activePage = "payments";
include "../../includes/db.php";
include "sidebar.php";

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = intval($_POST['project_id']);
    $client_id = intval($_POST['client_id']);
    $invoice_number = $db->real_escape_string(trim($_POST['invoice_number']));
    $total_amount = (float)$_POST['total_amount'];
    $issue_date = $db->real_escape_string($_POST['issue_date']);
    $due_date = $db->real_escape_string($_POST['due_date']);

    if (empty($invoice_number) || $total_amount <= 0) {
        $error = "Invoice number and total amount are required and total must be > 0.";
    } else {
        $ins = $db->query("INSERT INTO invoices (project_id, client_id, invoice_number, total_amount, issue_date, due_date) VALUES ($project_id, $client_id, '{$invoice_number}', '{$total_amount}', '{$issue_date}', '{$due_date}')");
        if ($ins) {
            header("Location: invoices.php?msg=Invoice created");
            exit;
        } else $error = "DB error: " . $db->error;
    }
}

// Fetch projects for select
$projects = $db->query("SELECT id, project_name, client_id FROM projects ORDER BY id DESC");
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Create Invoice | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-content {
            margin-left: 260px;
            padding: 1.5rem;
            background: #f6f8fb;
        }

        .form-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: .75rem;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .05);
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="d-flex justify-content-between mb-4">
            <h2>Create Invoice</h2>
            <a href="invoices.php" class="btn btn-secondary btn-sm">Back</a>
        </div>

        <div class="form-card">
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Project</label>
                    <select name="project_id" id="project_id" class="form-control" required>
                        <option value="">-- Select project --</option>
                        <?php while ($p = $projects->fetch_assoc()): ?>
                            <option value="<?= $p['id'] ?>" data-client="<?= $p['client_id'] ?>">
                                <?= htmlspecialchars($p['project_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Client</label>
                    <select name="client_id" id="client_id" class="form-control" required>
                        <option value="">-- Select client --</option>
                        <?php
                        $clients = $db->query("SELECT id, client_name FROM clients ORDER BY client_name ASC");
                        while ($c = $clients->fetch_assoc()) {
                            echo "<option value='{$c['id']}'>" . htmlspecialchars($c['client_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Invoice Number</label>
                    <input type="text" name="invoice_number" class="form-control" value="INV-<?= time() ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Amount</label>
                    <input type="number" step="0.01" name="total_amount" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Issue Date</label>
                    <input type="date" name="issue_date" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control">
                </div>

                <button class="btn btn-success">Create Invoice</button>
            </form>
        </div>
    </div>

    <script>
        // auto-select client when project chosen
        document.addEventListener('DOMContentLoaded', function() {
            var projectSelect = document.getElementById('project_id');
            projectSelect.addEventListener('change', function() {
                var clientId = projectSelect.options[projectSelect.selectedIndex].dataset.client || '';
                if (clientId) document.getElementById('client_id').value = clientId;
            });
        });
    </script>
</body>

</html>