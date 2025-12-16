<?php
session_start();
require_once "../../includes/db.php";

$activePage = "payments";

/* =========================
   Filters (GET)
   month, year -- used for table & export
   ========================= */
$filterYear  = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$filterMonth = isset($_GET['month']) ? intval($_GET['month']) : 0; // 0 => all months

// Export CSV (filtered)
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $where = [];
    if ($filterYear) {
        $where[] = "YEAR(p.payment_date) = " . $filterYear;
    }
    if ($filterMonth && $filterMonth >= 1 && $filterMonth <= 12) {
        $where[] = "MONTH(p.payment_date) = " . $filterMonth;
    }
    $where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

    $sql = "
        SELECT p.id, p.invoice_id, p.amount, p.payment_method, p.transaction_ref, p.payment_date, p.created_at,
               i.invoice_number, i.total_amount AS invoice_total, i.status AS invoice_status,
               c.client_name, pr.project_name
        FROM payments p
        LEFT JOIN invoices i ON i.id = p.invoice_id
        LEFT JOIN clients c ON c.id = i.client_id
        LEFT JOIN projects pr ON pr.id = i.project_id
        {$where_sql}
        ORDER BY p.payment_date DESC, p.id DESC
    ";
    $res = $db->query($sql);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=payments_export_' . date('Ymd_His') . '.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Payment ID','Invoice ID','Invoice #','Client','Project','Amount','Method','Transaction Ref','Payment Date','Created At','Invoice Total','Invoice Status']);
    while ($r = $res->fetch_assoc()) {
        fputcsv($out, [
            $r['id'],
            $r['invoice_id'],
            $r['invoice_number'] ?? '',
            $r['client_name'] ?? '',
            $r['project_name'] ?? '',
            number_format($r['amount'],2),
            $r['payment_method'],
            $r['transaction_ref'],
            $r['payment_date'],
            $r['created_at'],
            isset($r['invoice_total']) ? number_format($r['invoice_total'],2) : '',
            $r['invoice_status'] ?? ''
        ]);
    }
    fclose($out);
    exit;
}

/* =========================
   Core aggregates
   ========================= */
$income = $db->query("SELECT IFNULL(SUM(amount),0) AS total FROM payments")->fetch_assoc()['total'] ?? 0;

$expenses = 0;
$expQ = $db->query("SHOW TABLES LIKE 'expenses'");
if ($expQ && $expQ->num_rows > 0) {
    $expenses = $db->query("SELECT IFNULL(SUM(amount),0) AS total FROM expenses")->fetch_assoc()['total'] ?? 0;
}

$totalInvoices = $db->query("SELECT COUNT(*) AS total FROM invoices")->fetch_assoc()['total'] ?? 0;
$openInvoices = $db->query("SELECT COUNT(*) AS total FROM invoices WHERE status IN ('unpaid','partial')")->fetch_assoc()['total'] ?? 0;

/* =========================
   Recent Payments (last 20 with filter)
   ========================= */
$where = [];
if ($filterYear) {
    $where[] = "YEAR(p.payment_date) = " . $filterYear;
}
if ($filterMonth && $filterMonth >= 1 && $filterMonth <= 12) {
    $where[] = "MONTH(p.payment_date) = " . $filterMonth;
}
$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

$recentSql = "
    SELECT p.*, i.invoice_number, i.total_amount AS invoice_total, i.status AS invoice_status,
           c.client_name, pr.project_name,
           (SELECT IFNULL(SUM(amount),0) FROM payments WHERE invoice_id = p.invoice_id) AS invoice_collected
    FROM payments p
    LEFT JOIN invoices i ON i.id = p.invoice_id
    LEFT JOIN clients c ON c.id = i.client_id
    LEFT JOIN projects pr ON pr.id = i.project_id
    {$where_sql}
    ORDER BY p.payment_date DESC, p.id DESC
    LIMIT 20
";
$recentRes = $db->query($recentSql);

/* =========================
   Monthly Income Breakdown
   ========================= */
$monthlySql = "
    SELECT YEAR(payment_date) AS y, MONTH(payment_date) AS m, SUM(amount) AS total
    FROM payments
    GROUP BY y, m
    ORDER BY y DESC, m DESC
    LIMIT 12
";
$monthlyRes = $db->query($monthlySql);

/* =========================
   Summary footer
   ========================= */
$year = date('Y');
$month = date('m');

$thisMonthTotal = $db->query("SELECT IFNULL(SUM(amount),0) AS total FROM payments WHERE YEAR(payment_date) = $year AND MONTH(payment_date) = $month")->fetch_assoc()['total'] ?? 0;
$thisYearTotal  = $db->query("SELECT IFNULL(SUM(amount),0) AS total FROM payments WHERE YEAR(payment_date) = $year")->fetch_assoc()['total'] ?? 0;
$monthsCount = $db->query("SELECT COUNT(DISTINCT DATE_FORMAT(payment_date,'%Y-%m')) AS c FROM payments")->fetch_assoc()['c'] ?? 1;
$avgPerMonth = $monthsCount > 0 ? ($db->query("SELECT IFNULL(SUM(amount),0) AS total FROM payments")->fetch_assoc()['total'] / $monthsCount) : 0;

/* =========================
   Recent Activity
   ========================= */
$activitySql = "
    SELECT 'payment' AS type, p.id AS id, p.amount, p.payment_method, p.transaction_ref, p.payment_date AS date, i.invoice_number, c.client_name
    FROM payments p
    LEFT JOIN invoices i ON i.id = p.invoice_id
    LEFT JOIN clients c ON c.id = i.client_id

    UNION ALL

    SELECT 'invoice' AS type, i.id AS id, i.total_amount AS amount, NULL AS payment_method, NULL AS transaction_ref, i.created_at AS date, i.invoice_number, c.client_name
    FROM invoices i
    LEFT JOIN clients c ON c.id = i.client_id

    ORDER BY date DESC
    LIMIT 10
";
$activityRes = $db->query($activitySql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../../assets/css/projects.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
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
    margin: 0;
    padding: 0;
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

/* No spacing between sidebar & main */
.main-content {
    margin-left: 0px !important;
    padding: 1.5rem;
    min-height: 100vh;
    background-color: var(--page-bg);
    flex-grow: 1;
}

/* ===========================
   PAYMENT DASHBOARD CARDS
=========================== */
.dashboard-card {
    text-align: center;
    padding: 24px;
    border-radius: 12px;
    background: var(--card-bg);
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}

.dashboard-card i {
    font-size: 30px;
    color: var(--brand-green);
    margin-bottom: 10px;
    display: block;
}

.dashboard-card h5 {
    font-weight: 600;
    color: var(--text-dark);
}

.dashboard-card .amount {
    font-size: 1.4rem;
    font-weight: bold;
    color: var(--brand-green-dark);
}

/* ===========================
   PAYMENT TABLE WRAPPER
=========================== */
.payments-container {
    background-color: var(--card-bg);
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 6px 18px rgba(39, 53, 66, 0.06);
    overflow-x: auto;
}

/* Payment table */
.payments-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 750px;
    font-size: 0.95rem;
}

.payments-table th,
.payments-table td {
    padding: 0.75rem 1rem;
    border-top: 1px solid var(--border-light);
    vertical-align: middle;
}

.payments-table th {
    background: #f9fafb;
    border-bottom: 2px solid var(--border-light);
    font-weight: 600;
    color: var(--text-dark);
}

/* Hover effect */
.payments-table tbody tr:hover {
    background-color: rgba(22, 163, 74, 0.08);
    cursor: pointer;
    transition: 0.2s ease-in-out;
}

/* ===========================
   PAYMENT STATUS BADGES
=========================== */
.badge-paid {
    background: #d1fae5;
    color: #065f46;
    padding: 0.35rem 0.6rem;
    border-radius: 0.5rem;
    font-weight: 600;
}

.badge-partial {
    background: #fff7ed;
    color: #92400e;
    padding: 0.35rem 0.6rem;
    border-radius: 0.5rem;
    font-weight: 600;
}

.badge-unpaid {
    background: #fee2e2;
    color: #8b0000;
    padding: 0.35rem 0.6rem;
    border-radius: 0.5rem;
    font-weight: 600;
}

/* ===========================
   ACTION BUTTONS
=========================== */
.payment-actions .btn {
    padding: 0.35rem 0.6rem;
    font-size: 0.85rem;
    border-radius: 0.375rem;
    margin-right: 0.25rem;
    transition: 0.2s ease;
}

.payment-actions .btn:hover {
    transform: translateY(-2px);
}

/* ===========================
   RESPONSIVE
=========================== */
@media (max-width: 991px) {
    .sidebar-modern {
        display: none;
    }
    .main-content {
        margin-left: 0 !important;
        padding: 1rem;
    }
    .payments-table th,
    .payments-table td {
        padding: 0.6rem;
        font-size: 0.88rem;
    }
}
</style>

</head>
<body>
<div class="layout">
    <?php include "sidebar.php"; ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-0">Payments Dashboard</h3>
                <small class="text-muted">Overview & management</small>
            </div>
            <div class="d-flex align-items-center">
                <form class="d-flex align-items-center me-3" method="get" action="">
                    <select name="month" class="form-select form-select-sm me-2">
                        <option value="0">All months</option>
                        <?php for ($m=1;$m<=12;$m++): ?>
                            <option value="<?= $m ?>" <?= ($filterMonth==$m) ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$m,1)) ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="year" class="form-select form-select-sm me-2">
                        <?php for ($y=date('Y')-4; $y<=date('Y'); $y++): ?>
                            <option value="<?= $y ?>" <?= ($filterYear==$y) ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                    <button class="btn btn-sm btn-primary me-2" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary btn-sm me-2" href="?export=csv&month=<?= $filterMonth ?>&year=<?= $filterYear ?>">Export CSV</a>
                    <a class="btn btn-outline-dark btn-sm" href="" onclick="window.print();return false;">Print</a>
                </form>
            </div>
        </div>

        <!-- Top stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="bi bi-cash-stack"></i>
                    <h6>Total Income</h6>
                    <h3>Ksh <?= number_format($income,2) ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="bi bi-wallet2"></i>
                    <h6>Total Expenses</h6>
                    <h3>Ksh <?= number_format($expenses,2) ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="bi bi-file-earmark-text"></i>
                    <h6>Total Invoices</h6>
                    <h3><?= number_format($totalInvoices) ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="bi bi-hourglass-split"></i>
                    <h6>Open Invoices</h6>
                    <h3><?= number_format($openInvoices) ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left: Recent Payments -->
            <div class="col-lg-8">
                <div class="projects-container mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recent Payments</h5>
                        <small class="text-muted">Showing latest 20</small>
                    </div>
                    <div class="table-responsive">
                        <table class="projects-table table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice</th>
                                    <th>Client</th>
                                    <th>Project</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Ref</th>
                                    <th>Payment Date</th>
                                    <th>Invoice Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recentRes && $recentRes->num_rows > 0): ?>
                                    <?php while($row = $recentRes->fetch_assoc()): ?>
                                        <?php
                                            $status = strtolower($row['invoice_status'] ?? '');
                                            $badge = '<span class="badge-status-unpaid">No Invoice</span>';
                                            if ($status==='paid') $badge='<span class="badge-status-paid">Paid</span>';
                                            elseif($status==='partial') $badge='<span class="badge-status-partial">Partial</span>';
                                            elseif($status==='unpaid') $badge='<span class="badge-status-unpaid">Unpaid</span>';
                                            $invoice_total = isset($row['invoice_total']) ? floatval($row['invoice_total']):0;
                                            $collected = isset($row['invoice_collected']) ? floatval($row['invoice_collected']):0;
                                            $percent = ($invoice_total>0)?min(100, round(($collected/$invoice_total)*100)):0;
                                        ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td>
                                                <?php if(!empty($row['invoice_number'])): ?>
                                                    <a href="view_invoice.php?id=<?= $row['invoice_id'] ?>"><?= htmlspecialchars($row['invoice_number']) ?></a>
                                                    <div class="small text-muted">Total: Ksh <?= number_format($invoice_total,2) ?></div>
                                                    <div class="progress mt-1 progress-small">
                                                        <div class="progress-bar" style="width: <?= $percent ?>%"></div>
                                                    </div>
                                                    <small class="text-muted"><?= $percent ?>% collected</small>
                                                <?php else: ?>—
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['client_name'] ?? '—') ?></td>
                                            <td><?= htmlspecialchars($row['project_name'] ?? '—') ?></td>
                                            <td>Ksh <?= number_format($row['amount'],2) ?></td>
                                            <td><?= htmlspecialchars(ucfirst($row['payment_method'])) ?></td>
                                            <td><?= htmlspecialchars($row['transaction_ref'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($row['payment_date']) ?></td>
                                            <td><?= $badge ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="9" class="text-center text-muted">No payments found</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Monthly Income -->
                <div class="projects-container mb-4">
                    <h5 class="mb-3">Monthly Income Breakdown</h5>
                    <table class="projects-table table table-striped">
                        <thead><tr><th>Year</th><th>Month</th><th>Total Income</th></tr></thead>
                        <tbody>
                        <?php if($monthlyRes && $monthlyRes->num_rows>0): ?>
                            <?php while($m=$monthlyRes->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $m['y'] ?></td>
                                    <td><?= date('F', mktime(0,0,0,$m['m'],1)) ?></td>
                                    <td>Ksh <?= number_format($m['total'],2) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted">No monthly data</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="projects-container mb-4">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h6>This Month</h6>
                            <h4>Ksh <?= number_format($thisMonthTotal,2) ?></h4>
                        </div>
                        <div class="col-md-4">
                            <h6>This Year</h6>
                            <h4>Ksh <?= number_format($thisYearTotal,2) ?></h4>
                        </div>
                        <div class="col-md-4">
                            <h6>Average / Month</h6>
                            <h4>Ksh <?= number_format($avgPerMonth,2) ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Quick Actions & Activity -->
            <div class="col-lg-4">
                <div class="projects-container mb-4">
                    <h5>Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="create_invoice.php" class="btn btn-success">+ Create Invoice</a>
                        <a href="add_payment.php" class="btn btn-primary">+ Record Payment</a>
                        <a href="add_expense.php" class="btn btn-danger">+ Add Expense</a>
                        <a href="invoices.php" class="btn btn-outline-dark">View All Invoices</a>
                        <a href="payments.php" class="btn btn-outline-secondary">View All Payments</a>
                    </div>
                </div>

                <div class="projects-container">
                    <h5>Recent Activity</h5>
                    <?php if($activityRes && $activityRes->num_rows>0): ?>
                        <ul class="timeline">
                            <?php while($a=$activityRes->fetch_assoc()): ?>
                                <li>
                                    <div class="small text-muted"><?= date('M d, Y H:i', strtotime($a['date'])) ?></div>
                                    <?php if($a['type']=='payment'): ?>
                                        <div><strong>Payment received</strong> — Ksh <?= number_format($a['amount'],2) ?> from <?= htmlspecialchars($a['client_name']) ?> (Invoice: <?= htmlspecialchars($a['invoice_number'] ?? '—') ?>)</div>
                                    <?php else: ?>
                                        <div><strong>Invoice created</strong> — <?= htmlspecialchars($a['invoice_number']) ?> for <?= htmlspecialchars($a['client_name']) ?>, Amount: Ksh <?= number_format($a['amount'],2) ?></div>
                                    <?php endif; ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-muted">No recent activity</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
