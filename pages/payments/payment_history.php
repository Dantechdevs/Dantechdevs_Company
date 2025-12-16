<?php
$activePage = "payments";
include "../../includes/db.php";
include "sidebar.php";

$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Payments History | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-content {
            margin-left: 260px;
            padding: 1.5rem;
            background: #f6f8fb;
        }

        .card-table {
            background: #fff;
            padding: 1rem;
            border-radius: .75rem;
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Payments History</h2>
            <div>
                <a href="invoices.php" class="btn btn-secondary btn-sm">Invoices</a>
                <a href="payment_add.php" class="btn btn-success btn-sm">Record Payment</a>
            </div>
        </div>

        <div class="card-table">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "
    SELECT payments.*, invoices.invoice_number, invoices.project_id, projects.project_name, clients.client_name
    FROM payments
    LEFT JOIN invoices ON payments.invoice_id = invoices.id
    LEFT JOIN projects ON invoices.project_id = projects.id
    LEFT JOIN clients ON invoices.client_id = clients.id
";
                    if ($invoice_id) $sql .= " WHERE payments.invoice_id = $invoice_id";
                    $sql .= " ORDER BY payments.id DESC";

                    $res = $db->query($sql);
                    if ($res && $res->num_rows > 0) {
                        while ($r = $res->fetch_assoc()) {
                            echo "<tr>
            <td>{$r['id']}</td>
            <td>" . htmlspecialchars($r['invoice_number']) . "</td>
            <td>" . htmlspecialchars($r['project_name']) . "</td>
            <td>" . htmlspecialchars($r['client_name']) . "</td>
            <td>" . number_format($r['amount'], 2) . "</td>
            <td>" . htmlspecialchars($r['payment_method']) . "</td>
            <td>" . htmlspecialchars($r['transaction_ref']) . "</td>
            <td>" . htmlspecialchars($r['payment_date']) . "</td>
        </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No payments found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>