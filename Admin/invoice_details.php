<?php
include '../connection.php';

$result = $database->query("SELECT MAX(invoice_id) as max_invoice_id FROM invoice");
$row = $result->fetch_assoc();
$invoice_id = $row['max_invoice_id'];

// Fetch the invoice details from the database
$result = $database->query("SELECT * FROM invoice WHERE invoice_id = $invoice_id");
$invoice = $result->fetch_assoc();

// Fetch the line items for the invoice
$result = $database->query("SELECT * FROM Line_Item WHERE invoice_id = $invoice_id");
$line_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quote Details</title>
</head>
<body>
    <h1>Quote Details</h1>

    <h2>Quote Information</h2>
    <p>Invoice ID: <?= $invoice['invoice_id'] ?></p>
    <p>Customer Name: <?= $invoice['Customer Name'] ?></p>
    <p>Invoice Date: <?= $invoice['invoice_date'] ?></p>

    <h2>Line Items</h2>
    <table>
        <tr>
            <th>Part#</th>
            <th>Part Name</th>
            <th>Volume</th>
            <th>Material Type</th>
            <th>Width(mm)</th>
            <th>width(in)</th>
            <th>Pitch(mm)</th>
            <th>Pitch(in)</th>
            <th>Gauge(mm)</th>
            <th>Gauge(in)</th>
            <th>Pcs per Skid</th>
            <th>Blanking Per Piece Cost</th>
            <th>Packaging Per Piece Cost</th>
            <th>freight per piece cost</th>
            <th>Total Cost per Piece</th>


            <!-- Add more headers as needed -->
        </tr>
        <?php foreach($line_items as $item): ?>
            <tr>
                <td><?= $item['Part#'] ?></td>
                <td><?= $item['Part Name'] ?></td>
                <td><?= $item['Volume'] ?></td>
                <td><?= $item['Material Type'] ?></td>
                <td><?= $item['Width(mm)'] ?></td>
                <td><?= $item['width(in)'] ?></td>
                <td><?= $item['Pitch(mm)'] ?></td>
                <td><?= $item['Pitch(in)'] ?></td>
                <td><?= $item['Gauge(mm)'] ?></td>
                <td><?= $item['Gauge(in)'] ?></td>
                <td><?= $item['Pcs per Skid'] ?></td>
                <td><?= $item['Blanking per piece cost'] ?></td>
                <td><?= $item['Packaging Per Piece Cost'] ?></td>
                <td><?= $item['freight per piece cost'] ?></td>
                <td><?= $item['Total Cost per Piece'] ?></td>
                

                
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>