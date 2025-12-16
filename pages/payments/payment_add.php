<?php
$activePage = "payments";
include "../../includes/db.php";
include "sidebar.php";

$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_id = intval($_POST['invoice_id']);
    $amount = (float)$_POST['amount'];
    $method = $db->real_escape_string($_POST['payment_method']);
    $ref = $db->real_escape_string($_POST['transaction_ref']);
    $date = $db->real_escape_string($_POST['payment_date']);

    if ($amount <= 0) $error = "Amount must be greater than 0.";
    else {
        $ins = $db->query("INSERT INTO payments (invoice_id, amount, payment_method, transaction_ref, payment_date) VALUES ($invoice_id, '{$amount}', '{$method}', '{$ref}', '{$date}')");
        if ($ins) {
            // optionally update invoice status
            // compute totals
            $paidQ = $db->query("SELECT IFNULL(SUM(amount),0) AS total_paid FROM payments WHERE invoice_id = $invoice_id");
            $paidRow = $paidQ->fetch_assoc();
            $paid = (float)$paidRow['total_paid'];
            $invQ = $db->query("SELECT total_amount FROM invoices WHERE id = $invoice_id");
            $inv = $invQ->fetch_assoc();
            $total = (float)$inv['total_amount'];
            $newStatus = ($paid >= $total) ? 'paid' : (($paid > 0) ? 'partial' : 'unpaid');
            $db->query("UPDATE invoices SET status = '{$newStatus}' WHERE id = $invoice_id");

            header("Location: payment_history.php?invoice_id=$invoice_id&msg=Payment recorded");
            exit;
        } else $error = "DB error: " . $db->error;
    }
}

// fetch invoice details
$inv = null;
if ($invoice_id) {
    $iq = $db->query("SELECT invoices.*, projects.project_name, clients.client_name FROM invoices LEFT JOIN projects ON invoices.project_id = projects.id LEFT JOIN clients ON invoices.client_id = clients.id WHERE invoices.id = $invoice_id");
    $inv = $iq ? $iq->fetch_assoc() : null;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Record Payment | Dantechdevs</title>
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
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="d-flex justify-content-between mb-4">
            <h2>Record Payment</h2>
            <a href="invoices.php" class="btn btn-secondary btn-sm">Back</a>
        </div>

        <div class="form-card">
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <?php if (!$inv): ?>
                <div class="alert alert-warning">Invoice not found. <a href="invoices.php">Go back</a></div>
            <?php else:
                $paidQ = $db->query("SELECT IFNULL(SUM(amount),0) AS total_paid FROM payments WHERE invoice_id = {$inv['id']}");
                $paid = $paidQ ? (float)$paidQ->fetch_assoc()['total_paid'] : 0;
                $balance = (float)$inv['total_amount'] - $paid;
            ?>
                <p><strong>Invoice:</strong> <?= htmlspecialchars($inv['invoice_number']) ?></p>
                <p><strong>Project:</strong> <?= htmlspecialchars($inv['project_name']) ?></p>
                <p><strong>Client:</strong> <?= htmlspecialchars($inv['client_name']) ?></p>
                <p><strong>Total:</strong> <?= number_format($inv['total_amount'], 2) ?> | <strong>Paid:</strong>
                    <?= number_format($paid, 2) ?> | <strong>Balance:</strong> <?= number_format($balance, 2) ?></p>

                <form method="post">
                    <input type="hidden" name="invoice_id" value="<?= $inv['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="mpesa">M-PESA</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction Reference</label>
                        <input type="text" name="transaction_ref" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>
                    <button class="btn btn-success">Record Payment</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>