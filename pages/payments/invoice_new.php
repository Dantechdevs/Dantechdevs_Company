<?php
$activePage = "invoice_new.php";
include "../../includes/db.php";
include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Invoice | Dantechdevs IT Company</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ================= INTERNAL CSS ================= -->
    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .invoice-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        h2, h4 {
            font-weight: 700;
            color: #0d6efd;
        }

        label {
            font-weight: 600;
            margin-top: 5px;
        }

        input.form-control, textarea.form-control {
            border-radius: 6px;
            border: 1px solid #d4dbe6;
            padding: 8px 12px;
        }

        input.form-control:focus, textarea.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 5px rgba(13,110,253,.3);
        }

        table th {
            background: #0d6efd;
            color: #fff;
        }

        table td input {
            border-radius: 0;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 10px 24px;
            font-size: 16px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #084dcb;
        }

        hr {
            border-top: 2px solid #0d6efd;
            opacity: 0.5;
        }
    </style>
    <!-- ================================================= -->
</head>

<body>

<div class="container mt-4 invoice-container">

    <h2>Create Invoice</h2>
    <hr>

    <form action="generate_invoice.php" method="POST">

        <!-- COMPANY INFO -->
        <h4 class="mt-3">From (Your Company)</h4>
        <div class="row">
            <div class="col-md-6">
                <label>Company Name</label>
                <input type="text" name="company_name" class="form-control" value="Dantechdevs IT Company & Consultancy" required>

                <label class="mt-2">Address 1</label>
                <input type="text" name="company_address1" class="form-control" required>

                <label class="mt-2">Address 2</label>
                <input type="text" name="company_address2" class="form-control">

                <label class="mt-2">City</label>
                <input type="text" name="company_city" class="form-control">

                <label class="mt-2">State/Province</label>
                <input type="text" name="company_state" class="form-control">

                <label class="mt-2">Country</label>
                <input type="text" name="company_country" class="form-control">

                <label class="mt-2">Phone</label>
                <input type="text" name="company_phone" class="form-control">
            </div>
        </div>

        <!-- CLIENT INFO -->
        <h4 class="mt-4">Bill To (Client)</h4>
        <div class="row">
            <div class="col-md-6">
                <label>Client Name</label>
                <input type="text" name="client_name" class="form-control" required>

                <label class="mt-2">Address 1</label>
                <input type="text" name="client_address1" class="form-control">

                <label class="mt-2">Address 2</label>
                <input type="text" name="client_address2" class="form-control">

                <label class="mt-2">City</label>
                <input type="text" name="client_city" class="form-control">

                <label class="mt-2">State</label>
                <input type="text" name="client_state" class="form-control">

                <label class="mt-2">Country</label>
                <input type="text" name="client_country" class="form-control">

                <label class="mt-2">Phone</label>
                <input type="text" name="client_phone" class="form-control">
            </div>
        </div>

        <!-- INVOICE INFO -->
        <h4 class="mt-4">Invoice Details</h4>

        <div class="row">
            <div class="col-md-4">
                <label>Date Issued</label>
                <input type="date" name="date_issued" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label>Date Due</label>
                <input type="date" name="date_due" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label>Invoice Number</label>
                <input type="text" name="invoice_number" class="form-control" value="INV-<?php echo rand(10000,99999); ?>" required>
            </div>

            <div class="col-md-4 mt-2">
                <label>Reference</label>
                <input type="text" name="reference" class="form-control">
            </div>

            <div class="col-md-4 mt-2">
                <label>Currency</label>
                <input type="text" name="currency" class="form-control" value="KES">
            </div>
        </div>

        <!-- ITEMS -->
        <h4 class="mt-4">Invoice Items</h4>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Rate</th>
                    <th>Qty</th>
                    <th>Line Total</th>
                </tr>
            </thead>

            <tbody>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                <tr>
                    <td><input type="text" name="item_name[]" class="form-control"></td>
                    <td><input type="text" name="item_description[]" class="form-control"></td>
                    <td><input type="number" name="rate[]" step="0.01" class="form-control rate"></td>
                    <td><input type="number" name="qty[]" class="form-control qty"></td>
                    <td><input type="number" name="line_total[]" step="0.01" class="form-control line_total" readonly></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-4">
                <label>Subtotal</label>
                <input type="number" name="subtotal" class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label>Discount</label>
                <input type="number" name="discount" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Tax</label>
                <input type="number" name="tax" class="form-control">
            </div>

            <div class="col-md-4 mt-2">
                <label><b>Total</b></label>
                <input type="number" name="total" class="form-control" readonly>
            </div>
        </div>

        <!-- NOTES & TERMS -->
        <h4 class="mt-4">Notes</h4>
        <textarea name="notes" class="form-control" rows="3"></textarea>

        <h4 class="mt-4">Terms & Conditions</h4>
        <textarea name="terms" class="form-control" rows="3"></textarea>

        <button type="submit" class="btn btn-primary mt-4">Generate Invoice PDF</button>
    </form>
</div>

</body>
</html>
