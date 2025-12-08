<?php
$activePage = "clients";
include "../../includes/db.php";
include "sidebar.php";

if (!isset($_GET['id'])) {
    header("Location: client_list.php");
    exit;
}

$id = intval($_GET['id']);
$clientQuery = $db->query("SELECT * FROM clients WHERE id = $id AND deleted = 0");
if (!$clientQuery || $clientQuery->num_rows == 0) {
    header("Location: client_list.php?msg=Client not found");
    exit;
}
$client = $clientQuery->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = $db->real_escape_string($_POST['client_name']);
    $email = $db->real_escape_string($_POST['email']);
    $phone = $db->real_escape_string($_POST['phone']);
    $company_name = $db->real_escape_string($_POST['company']); // map form input to DB column
    $address = $db->real_escape_string($_POST['address']);

    $updateQuery = "
        UPDATE clients 
        SET client_name='$client_name', email='$email', phone='$phone', company_name='$company_name', address='$address'
        WHERE id=$id
    ";

    if ($db->query($updateQuery)) {
        header("Location: client_list.php?msg=Client updated successfully");
        exit;
    } else {
        $error = "Error: " . $db->error;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit Client | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/index.css" rel="stylesheet">
    <style>
    /* Push content to the right of sidebar */
    .main-content {
        margin-left: 250px;
        /* adjust to your sidebar width */
        padding: 20px;
    }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Client</h2>
            <a href="client_list.php" class="btn btn-secondary btn-sm">Back</a>
        </div>
        <div class="form-container">
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Client Name</label>
                    <input type="text" name="client_name" class="form-control"
                        value="<?= htmlspecialchars($client['client_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($client['email']) ?>">
                </div>
                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control"
                        value="<?= htmlspecialchars($client['phone']) ?>">
                </div>
                <div class="mb-3">
                    <label>Company</label>
                    <input type="text" name="company" class="form-control"
                        value="<?= htmlspecialchars($client['company_name']) ?>">
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control"><?= htmlspecialchars($client['address']) ?></textarea>
                </div>
                <button class="btn btn-primary">Update Client</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>