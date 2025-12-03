<?php
$activePage = "clients";
include "../../includes/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";
include "../../includes/topbar.php";
?>

<div class="content">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Clients</h3>
        <a href="client_new.php" class="btn btn-dante">
            <i class="bi bi-plus-circle"></i> New Client
        </a>
    </div>

    <div class="card-box">
        <h5 class="fw-semibold mb-3">All Clients</h5>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Client Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Company</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $sql = $conn->query("SELECT * FROM clients ORDER BY id DESC");

                if ($sql->num_rows > 0) {
                    while ($row = $sql->fetch_assoc()) {
                        echo "
                            <tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['company']}</td>
                                <td>
                                    <a href='client_view.php?id={$row['id']}' class='btn btn-sm btn-info'>View</a>
                                </td>
                            </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No clients found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

</div>

<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>