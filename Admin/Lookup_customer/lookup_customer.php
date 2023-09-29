<?php
session_start();
include '../../connection.php'; // Assuming you have a db_connection.php file for database connection

// Fetch customers for dropdown
$result = $database->query("SELECT `Customer Name` FROM Customer");
$customers = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Lookup Customer</title>
</head>
<body>
    <h1>Lookup Customer</h1>
    <form method="post">
        <label for="customer">Customer:</label>
        <select id="customer" name="customer">
        <option value="">Select a customer</option>
            <?php foreach($customers as $Customer): ?>
                <option value="<?= $Customer['Customer Name'] ?>"><?= $Customer['Customer Name'] ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <div id="customer_info"></div>
    <div id="invoices"></div>
    <div id="invoice_details"></div>
    <div id="line_items"></div>

    <script>
    $(document).ready(function(){
        $("#customer").change(function(){
            var customerName = $(this).val();
            if (customerName != "") {
                $.ajax({
                    url: 'fetch_customer.php',
                    method: 'POST',
                    data: {customerName:customerName},
                    success: function(data) {
                        var customerData = JSON.parse(data);
                        var html = '<h2>Customer Info</h2><ul>';
                        for (var key in customerData) {
                            if (customerData.hasOwnProperty(key) && key != 'customer_id') {
                                html += '<li>' + key + ': ' + customerData[key] + '</li>';
                            }
                        }
                        html += '</ul>';
                        $('#customer_info').html(html);
                    }
                });
                $.ajax({
                    url: 'fetch_invoices.php',
                    method: 'POST',
                    data: {customerName:customerName},
                    success: function(data) {
                        var invoices = JSON.parse(data);
var html = '<h2>Invoices</h2><ul>';
invoices.forEach(function(invoice) {
    html += '<li><a href="#" onclick="fetchInvoiceDetails(' + invoice.invoice_id + ')">Invoice ID: ' + invoice.invoice_id + ', Date: ' + invoice.invoice_date + '</a></li>';
});
html += '</ul>';
$('#invoices').html(html);
                    }
                });
            } else {
                $('#customer_info').html('');
                $('#invoices').html('');
            }
        });
    });
    function fetchInvoiceDetails(invoiceId) {
    $.ajax({
        url: 'fetch_invoice.php',
        method: 'POST',
        data: {invoiceId:invoiceId},
        success: function(data) {
            var invoiceData = JSON.parse(data);
            var html = '<h2>Invoice Details</h2><ul>';
            for (var key in invoiceData.invoice) {
                if (invoiceData.invoice.hasOwnProperty(key) && key != 'invoice_id') {
                    html += '<li>' + key + ': ' + invoiceData.invoice[key] + '</li>';
                }
            }
            html += '</ul>';
            $('#invoice_details').html(html);

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

            // Add more columns as needed...

            invoiceData.line_items.forEach(function(item) {
    html += '<tr>';
    html += '<td>' + item['Part#'] + '</td>';
    html += '<td>' + item['Volume'] + '</td>';
    html += '<td>' + item['Width(mm)'] + '</td>';
    html += '<td>' + item['Pitch(mm)'] + '</td>';
    html += '<td>' + item['Gauge(mm)'] + '</td>';
    html += '<td>' + item['# Outputs'] + '</td>';
    html += '<td>' + item['Line_Location'] + ' (' + item['Line_Name'] + ')' + '</td>';
    html += '<td>' + item['Uptime'] + '</td>';
    html += '<td>' + item['PPH'] + '</td>';
    html += '<td>' + item['Pcs per Skid'] + '</td>';
    html += '<td>' + item['Skids per Truck'] + '</td>';
    html += '<td>' + item['Blanking per piece cost'] + '</td>';
    html += '<td>' + item['Packaging Per Piece Cost'] + '</td>';
    html += '<td>' + item['Total Cost per Piece'] + '</td>';
    html += '</tr>';
});

            html += '</table>';
            $('#line_items').html(html);
        }
    });
}

</script>
    </script>
</body>
</html>