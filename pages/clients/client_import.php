<?php
$activePage = "clients";
include "../../includes/db.php";
include "sidebar.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); // skip header
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $name = $db->real_escape_string($data[0]);
            $email = $db->real_escape_string($data[1]);
            $phone = $db->real_escape_string($data[2]);
            $company = $db->real_escape_string($data[3]);
            $address = $db->real_escape_string($data[4]);
            $db->query("INSERT INTO clients (client_name,email,phone,company,address) VALUES ('$name','$email','$phone','$company','$address')");
        }
        fclose($handle);
        $msg = "Clients imported successfully";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Import Clients | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="main-content">
        <h2>Import Clients</h2>
        <a href="client_list.php" class="btn btn-secondary btn-sm mb-3">Back to Clients</a>
        <?php if (isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>CSV File</label>
                <input type="file" name="csv_file" class="form-control" required>
            </div>
            <button class="btn btn-success">Import</button>
        </form>
    </div>
</body>

</html>