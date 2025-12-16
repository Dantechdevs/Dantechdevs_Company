<?php
$activePage = "payments";
include "../../includes/db.php";
include "sidebar.php";
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoices | Dantechdevs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* reuse your project styles briefly */
:root{--brand-green:#16a34a;--border-light:#e5e7eb;}
.main-content{margin-left:260px;padding:1.5rem;min-height:100vh;background:#f6f8fb;}
.card-table{padding:1rem;background:#fff;border-radius:.75rem;box-shadow:0 6px 18px rgba(39,53,66,.06);}
.badge-paid{background:var(--brand-green);color:#fff}
.badge-unpaid{background:#facc15;color:#111}
.badge-partial{background:#3b82f6;color:#fff}
</style>
</head>
<body>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Invoices</h2>
        <div>
            <a href="invoice_create.php" class="btn btn-success btn-sm">+ New Invoice</a>
            <a href="payment_dashboard.php" class="btn btn-secondary btn-sm">Dashboard</a>
        </div>
    </div>

    <div class="card-table">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice #</th>
                    <th>Project</th>
                    <th>Client</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
<?php
$q = $db->query("
    SELECT invoices.*, projects.project_name, clients.client_name
    FROM invoices
    LEFT JOIN projects ON invoices.project_id = projects.id
    LEFT JOIN clients ON invoices.client_id = clients.id
    ORDER BY invoices.id DESC
");
if($q && $q->num_rows>0){
    while($inv = $q->fetch_assoc()){
        $invoice_id = (int)$inv['id'];
        $paidQ = $db->query("SELECT IFNULL(SUM(amount),0) AS total_paid FROM payments WHERE invoice_id = $invoice_id");
        $paidRow = $paidQ ? $paidQ->fetch_assoc() : ['total_paid'=>0];
        $paid = (float)$paidRow['total_paid'];
        $balance = (float)$inv['total_amount'] - $paid;
        if($paid >= (float)$inv['total_amount']) $payStatus = 'Paid';
        elseif($paid > 0) $payStatus = 'Partial';
        else $payStatus = 'Unpaid';
        $badge = $payStatus=='Paid' ? 'badge-paid' : ($payStatus=='Partial' ? 'badge-partial' : 'badge-unpaid');
        echo "<tr>
            <td>{$inv['id']}</td>
            <td>".htmlspecialchars($inv['invoice_number'])."</td>
            <td>".htmlspecialchars($inv['project_name'])."</td>
            <td>".htmlspecialchars($inv['client_name'])."</td>
            <td>".number_format($inv['total_amount'],2)."</td>
            <td>".number_format($paid,2)."</td>
            <td>".number_format($balance,2)."</td>
            <td><span class='status-badge {$badge}'>$payStatus</span></td>
            <td>".htmlspecialchars($inv['issue_date'])."</td>
            <td>".htmlspecialchars($inv['due_date'])."</td>
            <td>
                <a href='invoice_view.php?id={$inv['id']}' class='btn btn-info btn-sm'>View</a>
                <a href='payment_add.php?invoice_id={$inv['id']}' class='btn btn-success btn-sm'>Record Payment</a>
                <a href='payment_history.php?invoice_id={$inv['id']}' class='btn btn-secondary btn-sm'>Payments</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='11' class='text-center'>No invoices found.</td></tr>";
}
?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
