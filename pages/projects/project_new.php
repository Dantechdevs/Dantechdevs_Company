<?php
$activePage = "projects";
include "../../includes/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";
include "../../includes/topbar.php";
?>

<div class="content">

    <h3 class="fw-bold mb-4">Create New Project</h3>

    <div class="card-box">

        <form action="../../api/create_project.php" method="POST">

            <div class="mb-3">
                <label class="form-label">Project Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Client Name</label>
                <input type="text" name="client" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Project Description</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option>Pending</option>
                    <option>In Progress</option>
                    <option>Completed</option>
                </select>
            </div>

            <button class="btn btn-dante">Create Project</button>

        </form>

    </div>

</div>

<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>