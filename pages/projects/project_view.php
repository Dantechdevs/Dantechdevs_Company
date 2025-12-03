<?php
$activePage = "projects";
include "../../includes/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";
include "../../includes/topbar.php";

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid project');window.location='project_list.php';</script>";
    exit;
}

$id = $_GET['id'];
$project = $conn->query("SELECT * FROM projects WHERE id=$id")->fetch_assoc();
?>

<div class="content">

    <h3 class="fw-bold mb-4">Project Details</h3>

    <div class="card-box">

        <h4><?= $project['title']; ?></h4>
        <p><strong>Client:</strong> <?= $project['client']; ?></p>
        <p><strong>Status:</strong> <span class="badge bg-primary"><?= $project['status']; ?></span></p>
        <p><strong>Description:</strong></p>
        <p><?= nl2br($project['description']); ?></p>

        <p><strong>Created At:</strong> <?= $project['created_at']; ?></p>

        <a href="project_list.php" class="btn btn-secondary mt-3">Back to Projects</a>

    </div>

</div>

<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>