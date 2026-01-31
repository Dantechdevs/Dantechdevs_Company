<?php
$activePage = "dashboard";
include "includes/header.php"; // Your header with CSS links, Bootstrap, etc.

// --- FETCH DYNAMIC COUNTS ---
$projectsCount = $db->query("SELECT COUNT(*) as total FROM projects")->fetch_assoc()['total'] ?? 0;
$clientsCount  = $db->query("SELECT COUNT(*) as total FROM clients")->fetch_assoc()['total'] ?? 0;
$tasksCount    = $db->query("SELECT COUNT(*) as total FROM tasks")->fetch_assoc()['total'] ?? 0;
$ticketsCount  = $db->query("SELECT COUNT(*) as total FROM support_tickets")->fetch_assoc()['total'] ?? 0;

// --- FETCH PROJECTS PER CLIENT FOR CHART ---
$projectData = $db->query("
    SELECT c.name AS client, COUNT(p.id) AS project_count
    FROM projects p
    JOIN clients c ON p.client_id = c.id
    GROUP BY c.id
");

$clients = [];
$projectCounts = [];
while ($row = $projectData->fetch_assoc()) {
    $clients[] = $row['client'];
    $projectCounts[] = $row['project_count'];
}

// --- FETCH RECENT PROJECTS ---
$recentProjects = $db->query("SELECT p.id, p.name, c.name as client, p.status, p.deadline 
                              FROM projects p 
                              JOIN clients c ON p.client_id = c.id
                              ORDER BY p.id DESC 
                              LIMIT 5");
?>

<div class="main-content">
    <div class="container-fluid">

        <!-- Welcome -->
        <h2 class="mb-1">
            Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?> 👋
        </h2>
        <p class="text-muted mb-4">
            Here’s what’s happening in your system today.
        </p>

        <!-- Dashboard Stats -->
        <div class="row g-3 card-grid">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <small>Projects</small>
                    <h3><?= $projectsCount ?></h3>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <small>Clients</small>
                    <h3><?= $clientsCount ?></h3>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <small>Tasks</small>
                    <h3><?= $tasksCount ?></h3>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <small>Support Tickets</small>
                    <h3><?= $ticketsCount ?></h3>
                </div>
            </div>
        </div>

        <!-- Projects Overview Chart -->
        <div class="app-box mt-4">
            <h5 class="mb-3">Projects Overview</h5>
            <canvas id="projectsChart" height="80"></canvas>

            <?php if (empty($clients)) : ?>
            <p class="text-muted mt-3">No project data available yet.</p>
            <?php endif; ?>
        </div>

        <!-- Recent Projects Table -->
        <div class="app-box mt-4">
            <h5 class="mb-3">Recent Projects</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Project Name</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($recentProjects && $recentProjects->num_rows > 0) {
                            $i = 1;
                            while ($row = $recentProjects->fetch_assoc()) {
                                $status = strtolower($row['status']);
                                $badge = "secondary";

                                if ($status === "completed") $badge = "success";
                                elseif ($status === "in progress") $badge = "primary";
                                elseif ($status === "pending") $badge = "warning";
                                elseif ($status === "delayed") $badge = "danger";

                                echo "<tr>
                                    <td>{$i}</td>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['client']) . "</td>
                                    <td>
                                        <span class='badge bg-{$badge}'>" . ucfirst($status) . "</span>
                                    </td>
                                    <td>" . date('d M, Y', strtotime($row['deadline'])) . "</td>
                                </tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr>
                                <td colspan='5' class='text-center text-muted'>
                                    No recent projects found.
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>


<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    &copy; <?= date("Y") ?> Dantechdevs IT Consultancy & Company
</footer>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('projectsChart').getContext('2d');
var projectsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($clients) ?>,
        datasets: [{
            label: 'Projects per Client',
            data: <?= json_encode($projectCounts) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                stepSize: 1
            }
        }
    }
});
</script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>