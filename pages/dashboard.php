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

<div class="container mt-4">

    <h2>Welcome, User</h2>
    <p>Welcome to Dantechdevs IT Consultancy & Company System</p>

    <!-- Dashboard Cards -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Projects</h5>
                    <h3 class="card-text"><?= $projectsCount ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Clients</h5>
                    <h3 class="card-text"><?= $clientsCount ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Tasks</h5>
                    <h3 class="card-text"><?= $tasksCount ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Support Tickets</h5>
                    <h3 class="card-text"><?= $ticketsCount ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Overview Chart -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Projects Overview</h5>
            <canvas id="projectsChart" height="80"></canvas>
            <?php if (empty($clients)) echo "<p class='text-muted'>No project data available yet.</p>"; ?>
        </div>
    </div>

    <!-- Recent Projects Table -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Recent Projects</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
                        if ($recentProjects->num_rows > 0) {
                            $i = 1;
                            while ($row = $recentProjects->fetch_assoc()) {
                                $status = strtolower($row['status']);
                                $badge = "secondary";
                                if ($status == "completed") $badge = "success";
                                elseif ($status == "in progress") $badge = "primary";
                                elseif ($status == "pending") $badge = "warning";
                                elseif ($status == "delayed") $badge = "danger";

                                echo "<tr>
                                <td>{$i}</td>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['client']) . "</td>
                                <td><span class='badge bg-{$badge}'>" . ucfirst($status) . "</span></td>
                                <td>" . date('d M, Y', strtotime($row['deadline'])) . "</td>
                            </tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No recent projects found.</td></tr>";
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