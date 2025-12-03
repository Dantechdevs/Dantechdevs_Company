<?php
$activePage = "projects";
include "../../includes/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";
include "../../includes/topbar.php";
?>

<div class="content">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Projects</h3>
        <a href="project_new.php" class="btn btn-dante">
            <i class="bi bi-plus-circle"></i> New Project
        </a>
    </div>

    <div class="card-box">
        <h5 class="fw-semibold mb-3">All Projects</h5>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Project Name</th>
                    <th>Client</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $sql = $conn->query("SELECT * FROM projects ORDER BY id DESC");

                if ($sql->num_rows > 0) {
                    while ($row = $sql->fetch_assoc()) {
                        echo "
                            <tr>
                                <td>{$row['id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['client']}</td>
                                <td><span class='badge bg-primary'>{$row['status']}</span></td>
                                <td>{$row['created_at']}</td>
                                <td>
                                    <a href='project_view.php?id={$row['id']}' class='btn btn-sm btn-info'>View</a>
                                </td>
                            </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No projects found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

</div>

<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/main.js"></script>
</body>

</html>