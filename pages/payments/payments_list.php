<?php
$activePage = "payments";
include "../../includes/db.php";
include "sidebar.php";
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Payments List | Dantechdevs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ✅ SAME STRUCTURE AS PROJECT LIST */

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

        .projects-container {
            background-color: var(--card-bg);
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 6px 18px rgba(39, 53, 66, 0.06);
            overflow-x: auto;
        }

        .projects-table th,
        .projects-table td {
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--border-light);
        }

        .projects-table th {
            background-color: #f9fafb;
            font-weight: 600;
        }

        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

<div class="main-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Payments</h2>
        <a href="add_payment.php" class="btn btn-success btn-sm">
            <i class="bi bi-plus-circle"></i> Record Payment
        </a>
    </div>

    <?php
    $paymentsQuery = $db->query("
        SELECT 
            p.id,
            p.amount,
            p.payment_method,
            p.payment_date,
            i.invoice_number,
            c.client_name
        FROM payments p
        LEFT JOIN invoices i ON p.invoice_id = i.id
        LEFT JOIN projects pr ON i.project_id = pr.id
        LEFT JOIN clients c ON pr.client_id = c.id
        ORDER BY p.id DESC
    ");
    ?>

    <div class="projects-container">
        <table class="table table-striped projects-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice</th>
                    <th>Client</th>
                    <th>Amount (Ksh)</th>
                    <th>Method</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php if ($paymentsQuery && $paymentsQuery->num_rows > 0): ?>
                <?php while ($pay = $paymentsQuery->fetch_assoc()): ?>
                    <tr>
                        <td><?= $pay['id'] ?></td>
                        <td><?= htmlspecialchars($pay['invoice_number']) ?></td>
                        <td><?= htmlspecialchars($pay['client_name']) ?></td>
                        <td><?= number_format($pay['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($pay['payment_method']) ?></td>
                        <td><?= htmlspecialchars($pay['payment_date']) ?></td>
                        <td>
                            <a href="payment_view.php?id=<?= $pay['id'] ?>" class="btn btn-info btn-sm">View</a>
                            <a href="payment_delete.php?id=<?= $pay['id'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Delete this payment?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">No payments recorded yet.</td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
