<?php
// Include TCPDF
require_once '../../tcpdf/tcpdf.php';

// Retrieve POST data
$company_name = $_POST['company_name'] ?? '';
$company_address1 = $_POST['company_address1'] ?? '';
$company_address2 = $_POST['company_address2'] ?? '';
$company_city = $_POST['company_city'] ?? '';
$company_state = $_POST['company_state'] ?? '';
$company_country = $_POST['company_country'] ?? '';
$company_phone = $_POST['company_phone'] ?? '';

$client_name = $_POST['client_name'] ?? '';
$client_address1 = $_POST['client_address1'] ?? '';
$client_address2 = $_POST['client_address2'] ?? '';
$client_city = $_POST['client_city'] ?? '';
$client_state = $_POST['client_state'] ?? '';
$client_country = $_POST['client_country'] ?? '';
$client_phone = $_POST['client_phone'] ?? '';

$date_issued = $_POST['date_issued'] ?? '';
$date_due = $_POST['date_due'] ?? '';
$invoice_number = $_POST['invoice_number'] ?? '';
$reference = $_POST['reference'] ?? '';
$currency = $_POST['currency'] ?? '';

$items_name = $_POST['item_name'] ?? [];
$items_desc = $_POST['item_description'] ?? [];
$items_rate = $_POST['rate'] ?? [];
$items_qty = $_POST['qty'] ?? [];
$items_total = $_POST['line_total'] ?? [];

$subtotal = $_POST['subtotal'] ?? 0;
$discount = $_POST['discount'] ?? 0;
$tax = $_POST['tax'] ?? 0;
$total = $_POST['total'] ?? 0;

$notes = $_POST['notes'] ?? '';
$terms = $_POST['terms'] ?? '';

// Create new PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Document info
$pdf->SetCreator('Dantechdevs IT Company');
$pdf->SetAuthor($company_name);
$pdf->SetTitle('Invoice '.$invoice_number);
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);

// Add page
$pdf->AddPage();

// Logo top-right
$logoFile = '../../assets/img/logo.jpg';
$pdf->Image($logoFile, 150, 10, 40, '', '', '', 'T', false, 300);

// Invoice Header
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 15, 'INVOICE', 0, 1, 'L');

// Company & Client Info
$pdf->SetFont('helvetica', '', 10);
$companyInfo = <<<EOD
<b>From:</b> $company_name
$company_address1
$company_address2
$company_city, $company_state
$company_country
Phone: $company_phone
EOD;

$clientInfo = <<<EOD
<b>Bill To:</b> $client_name
$client_address1
$client_address2
$client_city, $client_state
$client_country
Phone: $client_phone
EOD;

$pdf->SetY(35);
$pdf->MultiCell(90, 40, $companyInfo, 0, 'L', 0, 0);
$pdf->MultiCell(90, 40, $clientInfo, 0, 'L', 0, 1);

// Invoice Details
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 10);
$details = <<<EOD
<b>Invoice Number:</b> $invoice_number
<b>Date Issued:</b> $date_issued
<b>Date Due:</b> $date_due
<b>Reference:</b> $reference
<b>Currency:</b> $currency
EOD;

$pdf->MultiCell(0, 0, $details, 0, 'L', 0, 1);

// Items Table
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(13,110,253);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(60, 8, 'Item', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Description', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'Rate', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'Qty', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Line Total', 1, 1, 'C', 1);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0,0,0);

for($i=0; $i<count($items_name); $i++){
    $pdf->Cell(60, 8, $items_name[$i], 1);
    $pdf->Cell(60, 8, $items_desc[$i], 1);
    $pdf->Cell(20, 8, number_format((float)$items_rate[$i],2), 1, 0, 'R');
    $pdf->Cell(20, 8, $items_qty[$i], 1, 0, 'R');
    $pdf->Cell(30, 8, number_format((float)$items_total[$i],2), 1, 1, 'R');
}

// Totals
$pdf->Ln(5);
$pdf->Cell(160, 8, 'Subtotal', 1);
$pdf->Cell(30, 8, number_format((float)$subtotal,2), 1, 1, 'R');

$pdf->Cell(160, 8, 'Discount', 1);
$pdf->Cell(30, 8, number_format((float)$discount,2), 1, 1, 'R');

$pdf->Cell(160, 8, 'Tax', 1);
$pdf->Cell(30, 8, number_format((float)$tax,2), 1, 1, 'R');

$pdf->SetFont('helvetica','B',12);
$pdf->Cell(160, 10, 'Total', 1);
$pdf->Cell(30, 10, number_format((float)$total,2), 1, 1, 'R');

// Notes & Terms
$pdf->Ln(5);
$pdf->SetFont('helvetica','',10);
if(!empty($notes)){
    $pdf->MultiCell(0,5,"<b>Notes:</b>\n$notes",0,'L',0,1);
}
if(!empty($terms)){
    $pdf->MultiCell(0,5,"<b>Terms & Conditions:</b>\n$terms",0,'L',0,1);
}

// Output PDF
$pdf->Output('Invoice_'.$invoice_number.'.pdf', 'I');
?>
