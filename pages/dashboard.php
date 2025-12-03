<?php
$activePage = "dashboard";
include "../includes/db.php";
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";
?>

<div class="content">

    <h2 class="mb-4 fw-bold">Dashboard</h2>

    <!-- ============================
         DASHBOARD STAT CARDS
    ============================ -->
    <div class="row g-4">

        <div class="col-md-3">
            <div class="card-box">
                <span class="card-title">Projects</span>
                <div class="card-value" id="projectsCount">0</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box">
                <span class="card-title">Clients</span>
                <div class="card-value" id="clientsCount">0</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box">
                <span class="card-title">Tasks</span>
                <div class="card-value" id="tasksCount">0</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box">
                <span class="card-title">Support Tickets</span>
                <div class="card-value" id="ticketsCount">0</div>
            </div>
        </div>

    </div>

    <!-- ============================
         PROJECTS CHART
    ============================ -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card-box">
                <h5 class="fw-bold">Projects Overview</h5>
                <canvas id="projectsChart" height="80"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- JS -->
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/chart.min.js"></script>
<script src="../assets/js/main.js"></script>

</body>

</html>