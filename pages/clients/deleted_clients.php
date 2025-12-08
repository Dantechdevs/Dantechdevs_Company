<?php
$activePage = "clients";
include "../../includes/db.php";

/* ✅ SEARCH */
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

/* ✅ PAGINATION */
$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;
$offset = ($page - 1) * $limit;

/* ✅ COUNT TOTAL */
$countQuery = $db->query("
    SELECT COUNT(*) AS total 
    FROM clients 
    WHERE deleted = 1 
    AND (
        client_name LIKE '%$search%' 
        OR email LIKE '%$search%' 
        OR phone LIKE '%$search%'
    )
");
$totalRows = $countQuery->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

/* ✅ FETCH DATA */
$deletedClientsQuery = $db->query("
    SELECT * FROM clients 
    WHERE deleted = 1 
    AND (
        client_name LIKE '%$search%' 
        OR email LIKE '%$search%' 
        OR phone LIKE '%$search%'
    )
    ORDER BY id DESC 
    LIMIT $limit OFFSET $offset
");
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Deleted Clients | Dantechdevs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            margin: 0;
        }

        /* ✅ NO SPACE BETWEEN SIDEBAR & CONTENT */
        .main-content {
            margin-left: 260px;
            padding: 20px 20px 20px 0;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }

        .back-btn {
            position: relative;
            z-index: 9999;
            background: white;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <!-- ✅ Sidebar -->
    <?php include "sidebar.php"; ?>

    <!-- ✅ Main Content -->
    <div class="main-content">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Deleted Clients</h2>
            <a href="client_list.php" class=class="btn btn-secondary btn-smn>
                ← Back to Clients
            </a>
        </div>

        <!-- ✅ SEARCH -->
        <form method=" GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                        class="form-control form-control-sm" placeholder="Search name, email or phone...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100">Search</button>
                </div>
                </form>

                <!-- ✅ TABLE -->
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">

                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Client Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if ($deletedClientsQuery->num_rows > 0): ?>
                                    <?php while ($client = $deletedClientsQuery->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $client['id'] ?></td>
                                            <td><?= htmlspecialchars($client['client_name']) ?></td>
                                            <td><?= htmlspecialchars($client['email']) ?></td>
                                            <td><?= htmlspecialchars($client['phone']) ?></td>
                                            <td>
                                                <a href="client_restore.php?id=<?= $client['id'] ?>"
                                                    class="btn btn-success btn-sm"
                                                    onclick="return confirm('Restore this client?')">
                                                    Restore
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            No deleted clients found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- ✅ PAGINATION -->
                        <?php if ($totalPages > 1): ?>
                            <nav>
                                <ul class="pagination justify-content-center mt-3">
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                            <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>

                    </div>
                </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>