<?php
session_start();
include '../connection.php'; // Assuming you have a db_connection.php file for database connection

// Fetch invoices for dropdown
$result = $database->query("SELECT invoice_id FROM invoice");
$invoices = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Lookup Invoice</title>
</head>
<body>
    <h1>Lookup Invoice</h1>
    <form method="post">
        <label for="invoice">Invoice:</label>
        <select id="invoice" name="invoice">
        <option value="">Select an invoice</option>
            <?php foreach($invoices as $invoice): ?>
                <option value="<?= $invoice['invoice_id'] ?>"><?= $invoice['invoice_id'] ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <div id="invoice_info"></div>
    <div id="line_items"></div>

    <script>
    $(document).ready(function(){
    $("#invoice").change(function(){
        var invoiceId = $(this).val();
        if (invoiceId != "") {
            $.ajax({
                url: 'fetch_invoice.php',
                method: 'POST',
                data: {invoiceId:invoiceId},
                success: function(data) {
                    var invoiceData = JSON.parse(data);
                    var html = '<h2>Invoice Info</h2><ul>';
                    for (var key in invoiceData.invoice) {
                        if (invoiceData.invoice.hasOwnProperty(key) && key != 'invoice_id') {
                            html += '<li>' + key + ': ' + invoiceData.invoice[key] + '</li>';
                        }
                    }
                    html += '</ul>';
                    $('#invoice_info').html(html);

                    // Handle line items data
                    html = '<h2>Line Items</h2>';
                    html += '<table>';
                    html += '<tr>';
                    html += '<th>Part Number</th>';
                    html += '<th>Volume</th>';
                    html += '<th>Width</th>';
                    html += '<th>Pitch</th>';
                    html += '<th>Gauge</th>';
                    html += '<th># Outputs</th>';
                    html += '<th>Line Produced</th>';
                    html += '<th>Uptime</th>';
                    html += '<th>Parts Per Hour</th>';
                    html += '<th>Pcs per Skid</th>';
                    html += '<th>Skids per Truck</th>';
                    html += '<th>Blanking Per Piece Cost</th>';
                    html += '<th>Packaging Per Piece Cost</th>';
                    html += '<th>Total Cost per Piece</th>';
                    html += '</tr>';

                    for (var i = 0; i < invoiceData.line_items.length; i++) {
                        html += '<tr>';
                        html += '<td>' + invoiceData.line_items[i]['Part#'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Volume'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Width(mm)'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Pitch(mm)'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Gauge(mm)'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['# Outputs'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Line Produced on'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Uptime'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['PPH'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Pcs per Skid'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Skids per Truck'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Blanking per piece cost'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Packaging Per Piece Cost'] + '</td>';
                        html += '<td>' + invoiceData.line_items[i]['Total Cost per Piece'] + '</td>';
                        html += '</tr>';
                    }
                    html += '</table>';
                    $('#line_items').html(html);
                }
            });
        } else {
            // Clear the invoice info
            $('#invoice_info').html('');
            $('#line_items').html('');
        }
    });
});
    </script>
</body>
</html>
