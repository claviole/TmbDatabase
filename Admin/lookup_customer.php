<?php
session_start();
include '../connection.php'; // Assuming you have a db_connection.php file for database connection

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
                            html += '<li>Invoice ID: ' + invoice.invoice_id + ', Date: ' + invoice.invoice_date + '</li>';
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
    </script>
</body>
</html>