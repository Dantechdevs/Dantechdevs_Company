<?php
$activePage = "payments";
include "../../includes/db.php";
include "sidebar.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $invoice_id = (int)$_POST['invoice_id'];
    $amount = (float)$_POST['amount'];
    $method = $_POST['payment_method'];
    $ref = $_POST['transaction_ref'];
    $payment_date = $_POST['payment_date'];

    // ✅ Insert payment
    $db->query("
        INSERT INTO payments (invoice_id, amount, payment_method, transaction_ref, payment_date)
        VALUES ($invoice_id, $amount, '$method', '$ref', '$payment_date')
    ");

    // ✅ Recalculate invoice total paid
    $paid = $db->query("
        SELECT IFNULL(SUM(amount),0) AS total_paid 
        FROM payments 
        WHERE invoice_id = $invoice_id
    ")->fetch_assoc()['total_paid'];

    // ✅ Get invoice total
    $invoiceTotal = $db->query("
        SELECT total_amount 
        FROM invoices 
        WHERE id = $invoice_id
    ")->fetch_assoc()['total_amount'];

    // ✅ Update invoice status
    if ($paid >= $invoiceTotal) {
        $status = "paid";
    } elseif ($paid > 0) {
        $status = "partial";
    } else {
        $status = "unpaid";
    }

    $db->query("
        UPDATE invoices SET status = '$status' WHERE id = $invoice_id
    ");

    header("Location: payments_list.php?success=1");
    exit;
}

// Fetch invoices
$invoiceQuery = $db->query("
    SELECT 
        i.id,
        i.invoice_number,
        i.total_amount,
        i.status,
        c.client_name,
        IFNULL(SUM(p.amount),0) AS total_paid
    FROM invoices i
    LEFT JOIN projects pr ON i.project_id = pr.id
    LEFT JOIN clients c ON pr.client_id = c.id
    LEFT JOIN payments p ON p.invoice_id = i.id
    GROUP BY i.id
    ORDER BY i.id DESC
");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Record Payment | Dantechdevs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --brand-green: #16a34a;
            --page-bg: #f6f8fb;
            --card-bg: #fff;
            --border-light: #e5e7eb;
        }

        .main-content {
            margin-left: 260px;
            padding: 1.5rem;
            background: var(--page-bg);
            min-height: 100vh;
        }

        .form-card {
            background: var(--card-bg);
            padding: 2rem;
            max-width: 720px;
            border-radius: 0.75rem;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
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

    <div class="d-flex justify-content-between mb-4">
        <h2>Record Payment</h2>
        <a href="payments_list.php" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="form-card">

        <form method="POST" onsubmit="return confirm('Confirm payment submission?');">

            <!-- Invoice -->
            <div class="mb-3">
                <label class="form-label">Select Invoice</label>
                <select class="form-select" name="invoice_id" required>
                    <option value="">-- Select Invoice --</option>
                    <?php while ($inv = $invoiceQuery->fetch_assoc()): 
                        $balance = $inv['total_amount'] - $inv['total_paid'];
                    ?>
                        <option value="<?= $inv['id'] ?>">
                            <?= $inv['invoice_number'] ?> | 
                            <?= $inv['client_name'] ?> | 
                            Balance: Ksh <?= number_format($balance, 2) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Amount -->
            <div class="mb-3">
                <label class="form-label">Payment Amount (Ksh)</label>
                <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>

            <!-- Method -->
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select class="form-select" name="payment_method" required>
                    <option value="">-- Select Method --</option>
                    <option value="Cash">Cash</option>
                    <option value="Mpesa">Mpesa</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Card">Card</option>
                </select>
            </div>

            <!-- Transaction Ref -->
            <div class="mb-3">
                <label class="form-label">Transaction Reference</label>
                <input type="text" name="transaction_ref" class="form-control">
            </div>

            <!-- Date -->
            <div class="mb-3">
                <label class="form-label">Payment Date</label>
                <input type="date" name="payment_date" class="form-control" required value="<?= date('Y-m-d') ?>">
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Save Payment
                </button>
            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
