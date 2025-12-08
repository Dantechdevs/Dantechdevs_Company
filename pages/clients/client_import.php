<?php
$activePage = "clients";
include "../../includes/db.php";
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Import Clients | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-content {
            margin-left: 260px;
            padding: 20px;
            min-height: 100vh;
            background-color: #f6f8fb;
        }

        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }
        }

        .form-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 6px 18px rgba(39, 53, 66, 0.06);
            max-width: 900px;
            margin: auto;
        }
    </style>
</head>

<body>

    <?php include "sidebar.php"; ?>

    <div class="main-content">
        <h2>Import Clients</h2>
        <a href="client_list.php" class="btn btn-secondary btn-sm mb-3">Back to Clients</a>

        <div class="form-container">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
                $file = $_FILES['csv_file']['tmp_name'];

                if (($handle = fopen($file, "r")) !== FALSE) {
                    fgetcsv($handle); // skip header
                    $imported = 0;
                    $skipped = 0;

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        // Skip completely empty rows
                        if (empty(array_filter($data))) continue;

                        // Map CSV columns with proper-case
                        $client_name = isset($data[0]) ? ucwords(strtolower(trim($data[0]))) : '';
                        $email = isset($data[1]) ? trim($data[1]) : '';
                        $phone = isset($data[2]) ? trim($data[2]) : '';
                        $company_name = isset($data[3]) ? ucwords(strtolower(trim($data[3]))) : '';
                        $address = isset($data[4]) ? trim($data[4]) : '';

                        // Skip if client_name AND email are empty
                        if (empty($client_name) && empty($email)) continue;

                        // Check for duplicates (email OR phone)
                        $check = $db->query("SELECT id FROM clients WHERE (email='$email' OR phone='$phone') AND deleted=0");
                        if ($check && $check->num_rows > 0) {
                            $skipped++;
                            continue;
                        }

                        // Insert into database
                        $db->query("INSERT INTO clients (client_name,email,phone,company_name,address) 
                                    VALUES (
                                        '" . $db->real_escape_string($client_name) . "',
                                        '" . $db->real_escape_string($email) . "',
                                        '" . $db->real_escape_string($phone) . "',
                                        '" . $db->real_escape_string($company_name) . "',
                                        '" . $db->real_escape_string($address) . "'
                                    )");
                        $imported++;
                    }

                    fclose($handle);

                    echo "<div class='alert alert-success'>$imported client(s) imported successfully!</div>";
                    if ($skipped > 0) {
                        echo "<div class='alert alert-warning'>$skipped duplicate client(s) were skipped.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Failed to open CSV file.</div>";
                }
            }
            ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="csv_file" class="form-label">CSV File</label>
                    <input type="file" name="csv_file" id="csv_file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Import</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>