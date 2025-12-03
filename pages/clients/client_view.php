<?php
$activePage = "clients";
include "../../includes/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";
include "../../includes/topbar.php";

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid client ID');window.location='client_list.php';</script>";
    exit;
}

$id = $_GET['id'];
$client = $conn->query("SELECT * FROM clients WHERE id=$id")->fetch_assoc();
?>

<div class="content">

    <h3 class="fw-bold mb-4">Client Details</h3>

    <div class="card-box">

        <h4><?= $client['name']; ?></h4>

        <p><strong>Email:</strong> <?= $client['email']; ?></p>
        <p><strong>Phone:</strong> <?= $client['phone']; ?></p>
        <p><strong>Company:</strong> <?= $client['company']; ?></p>
        <p><strong>Address:</strong> <?= nl2br($client['address']); ?></p>

        <p><strong>Added On:</strong> <?= $client['created_at']; ?></p>

        <a href="client_list.php" class="btn btn-secondary mt-3">Back to Clients</a>
    </div>

</div>

<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>