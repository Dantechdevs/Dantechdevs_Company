<?php
include "../../includes/db.php";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="clients.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Client Name', 'Email', 'Phone', 'Company', 'Address']);

$clients = $db->query("SELECT * FROM clients WHERE deleted=0 ORDER BY client_name ASC");
while ($c = $clients->fetch_assoc()) {
    fputcsv($output, [$c['client_name'], $c['email'], $c['phone'], $c['company'], $c['address']]);
}
fclose($output);
exit;
