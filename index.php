<?php
session_start();
$activePage = "dashboard";

include "includes/header.php";
include "includes/topbar.php";
include "includes/sidebar.php";
include "includes/db.php";

// --- Dashboard Counts ---
$projectsCount = $db->query("SELECT COUNT(*) AS total FROM projects")->fetch_assoc()['total'] ?? 0;
$clientsCount  = $db->query("SELECT COUNT(*) AS total FROM clients")->fetch_assoc()['total'] ?? 0;
$tasksCount    = $db->query("SELECT COUNT(*) AS total FROM tasks")->fetch_assoc()['total'] ?? 0;
$ticketsCount  = $db->query("SELECT COUNT(*) AS total FROM invoices")->fetch_assoc()['total'] ?? 0;

// --- Project Status ---
$pendingProjects   = $db->query("SELECT COUNT(*) total FROM projects WHERE status='Pending'")->fetch_assoc()['total'] ?? 0;
$activeProjects    = $db->query("SELECT COUNT(*) total FROM projects WHERE status='In Progress'")->fetch_assoc()['total'] ?? 0;
$completedProjects = $db->query("SELECT COUNT(*) total FROM projects WHERE status='Completed'")->fetch_assoc()['total'] ?? 0;
$delayedProjects   = $db->query("SELECT COUNT(*) total FROM projects WHERE status='Delayed'")->fetch_assoc()['total'] ?? 0;

// --- Projects per Client Chart ---
$projectData = $db->query("
    SELECT clients.client_name, COUNT(projects.id) AS project_count
    FROM projects
    JOIN clients ON projects.client_id = clients.id
    GROUP BY clients.id
");
$clients = [];
$projectCounts = [];
while ($row = $projectData->fetch_assoc()) {
    $clients[] = $row['client_name'];
    $projectCounts[] = $row['project_count'];
}

// --- Recent Projects ---
$recentProjects = $db->query("
    SELECT projects.project_name, clients.client_name, projects.status
    FROM projects
    JOIN clients ON projects.client_id = clients.id
    ORDER BY projects.id DESC
    LIMIT 5
");
?>

<div class="main-content p-4">

    <!-- ✅ HERO WELCOME -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Welcome Back, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?> 👋</h3>
            <p class="text-muted">Here’s a snapshot of your IT operations today.</p>
        </div>
        <div>
            <select class="form-select">
                <option>All Departments</option>
                <option>Development</option>
                <option>Support</option>
                <option>Finance</option>
            </select>
        </div>
    </div>

    <!-- ✅ QUICK ACTIONS -->
    <div class="bg-success p-4 rounded text-white mb-4">
        <h5 class="mb-3">Quick Actions</h5>
        <div class="row">
            <div class="col-md-2"><a href="projects/add.php" class="btn btn-light w-100">New Project</a></div>
            <div class="col-md-2"><a href="clients/add.php" class="btn btn-light w-100">New Client</a></div>
            <div class="col-md-2"><a href="tasks/add.php" class="btn btn-light w-100">New Task</a></div>
            <div class="col-md-2"><a href="billing/invoices.php" class="btn btn-light w-100">New Invoice</a></div>
            <div class="col-md-2"><a href="reports/" class="btn btn-light w-100">Analytics</a></div>
        </div>
    </div>

    <!-- ✅ KPI CARDS -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">Total Projects <h4><?= $projectsCount ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">Active Clients <h4><?= $clientsCount ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">Tasks Running <h4><?= $tasksCount ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">Support Tickets <h4><?= $ticketsCount ?></h4>
            </div>
        </div>
    </div>

    <!-- ✅ PROJECT PIPELINE -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="app-box">Pending Projects <h2><?= $pendingProjects ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="app-box text-primary">Active Projects <h2><?= $activeProjects ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="app-box text-success">Completed Projects <h2><?= $completedProjects ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="app-box text-danger">Delayed Projects <h2><?= $delayedProjects ?></h2>
            </div>
        </div>
    </div>

    <!-- ✅ PROJECT CHART -->
    <div class="app-box mb-4">
        <h5>Projects Overview</h5>
        <canvas id="projectsChart"></canvas>
    </div>

    <!-- ✅ RECENT PROJECTS -->
    <div class="app-box">
        <h5>Recent Projects</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Project</th>
                    <th>Client</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recentProjects->num_rows > 0) {
                    $i = 1;
                    while ($row = $recentProjects->fetch_assoc()) {
                        echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['project_name']}</td>
                            <td>{$row['client_name']}</td>
                            <td>{$row['status']}</td>
                        </tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No recent projects found</td></tr>";
                } ?>
            </tbody>
        </table>
    </div>

</div>

<?php include "includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('projectsChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($clients) ?>,
            datasets: [{
                label: 'Projects per Client',
                data: <?= json_encode($projectCounts) ?>,
                backgroundColor: 'rgba(22,163,74,0.8)'
            }]
        }
    });
</script>