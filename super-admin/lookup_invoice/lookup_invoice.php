<?php
session_start();
include '../../connection.php'; // Assuming you have a db_connection.php file for database connection

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
    <title>Lookup Quote</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

h1 {
    color: #333;
    text-align: center;
    margin-top: 50px;
}

form {
    width: 300px;
    margin: 30px auto;
}

label {
    display: block;
    margin-bottom: 10px;
}

select {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

#invoice_info, #line_items {
    width: 80%;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

table th {
    background-color: #f2f2f2;
}

button {
    display: block;
    width: 200px;
    height: 40px;
    margin: 20px auto;
    background-color: #333;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #444;
}
.button-container {
    text-align: right;
}

.button-container button {
    display: inline-block;
    margin-left: 0px;
    margin-right: 150px;
}
.return-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #1B145D;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.return-button:hover {
    background-color: #111;
}

.return-button-container {
    text-align: right;
    margin-right: 10px;
}
</style>
</head>
<body>
<div class= "return-button-container">
<a href="../index.php" class="return-button">Return to Dashboard</a>
</div>
    <h1>Lookup Quote</h1>
    <form method="post">
        <label for="invoice">Quote:</label>
        <select id="invoice" name="invoice">
        <option value="">Select an Quote</option>
            <?php foreach($invoices as $invoice): ?>
                <option value="<?= $invoice['invoice_id'] ?>"><?= $invoice['invoice_id'] ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <div class="button-container">
    <button id="approve-invoice">Approve Quote</button>
    <button id="deny-invoice">Deny Quote</button>
    </div>
    <div id="invoice_info"></div>
    <div id="line_items"></div>
    <button id="delete-invoice">Delete Quote</button>
    

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
                    var html = '<h2>Quote</h2><ul>';
                    // Define your label mapping here
                    var labelMapping = {
                        'invoice_number': 'Quote Number',
                        'invoice_date': 'Quote Date',
                        'customer_id': 'Customer ID',
                        'invoice_author': 'Quote Author',
                        'approval_status': 'Approval Status',
                        'customer_name': 'Customer Name'

                        // Add more mappings as needed
                    };
                    for (var key in invoiceData.invoice) {
                        if (invoiceData.invoice.hasOwnProperty(key) && key != 'invoice_id') {
                            // Use the label from the mapping if it exists, otherwise use the key
                            var label = labelMapping[key] ? labelMapping[key] : key;
                            html += '<li>' + label + ': ' + invoiceData.invoice[key] + '</li>';
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
                        html += '<td>' + invoiceData.line_items[i]['Line_Location'] + ' (' + invoiceData.line_items[i]['Line_Name'] + ')' + '</td>';
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
    <script>
        $("#deny-invoice").click(function(){
    var invoiceId = $("#invoice").val();
    if (invoiceId != "") {
        $.ajax({
            url: 'deny_invoice.php',
            method: 'POST',
            data: {invoiceId:invoiceId},
            success: function(data) {
                alert("Quote denied successfully");
                location.reload();
            }
        });
    } else {
        alert("Please select a Quote to deny");
    }
});
</script>
    <script>
    $("#approve-invoice").click(function(){
    var invoiceId = $("#invoice").val();
    if (invoiceId != "") {
        $.ajax({
            url: 'approve_invoice.php',
            method: 'POST',
            data: {invoiceId:invoiceId},
            success: function(data) {
                alert("Quote approved successfully");
                location.reload();
            }
        });
    } else {
        alert("Please select an quote to approve");
    }
});
</script>
    <script>
        $("#delete-invoice").click(function(){
        var invoiceId = $("#invoice").val();
        if (invoiceId != "") {
            var confirmDelete = confirm("Are you sure you want to delete this quote?");
            if (confirmDelete) {
                $.ajax({
                    url: 'delete_invoice.php',
                    method: 'POST',
                    data: {invoiceId:invoiceId},
                    success: function(data) {
                        alert("Invoice deleted successfully");
                        location.reload();
                    }
                });
            }
        } else {
            alert("Please select an invoice to delete");
        }
    });;
    </script>

</body>
</html>
