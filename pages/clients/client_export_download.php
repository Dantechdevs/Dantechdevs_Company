<?php
include "../../includes/db.php";

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="clients.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, ['Client Name', 'Email', 'Phone', 'Company', 'Address']);

// Fetch clients (non-deleted)
$clients = $db->query("SELECT client_name, email, phone, company_name, address FROM clients WHERE deleted=0 ORDER BY client_name ASC");

// Output each client row
if ($clients) {
    while ($c = $clients->fetch_assoc()) {
        fputcsv($output, [
            $c['client_name'],
            $c['email'],
            $c['phone'],
            $c['company_name'],
            $c['address']
        ]);
    }
}

fclose($output);
exit;
