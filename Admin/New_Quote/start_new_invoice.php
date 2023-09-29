<?php
session_start();
include '../../connection.php'; // Assuming you have a db_connection.php file for database connection

// Fetch customers for dropdown
$result = $database->query("SELECT `Customer Name` FROM Customer");
$customers = $result->fetch_all(MYSQLI_ASSOC);

// Fetch current invoice number
$invoice_result = $database->query("SELECT MAX(invoice_id) as max_invoice FROM invoice");
$current_invoice = intval($invoice_result->fetch_assoc()['max_invoice']) + 1;

// Get current date
$current_date = date("Y-m-d");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming the name of the select field for customer is 'customer'
    $_SESSION['selected_customer'] = $_POST['customer'];
    // Redirect to invoice.php
    header('Location: invoice.php');
    exit;
}
$result_part = $database->query("SELECT `Part#` FROM Part");
$parts = $result_part->fetch_all(MYSQLI_ASSOC);
// Fetch lines for dropdown
$line_result = $database->query("SELECT `line_id`, `Line_Location`, `Line_Name` FROM `Lines`");
$lines = $line_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="codes.js"></script>
    <link rel="stylesheet" href="css/../styles.css">

    <title>Start New Quote</title>
</head>
<body>
    <h1>Start New Quote</h1>
    <form action="submit_invoice.php" method="post">
        <label for="customer">Customer:</label>
        <select id="customer" name="customer">
        <option value="">Select a customer</option>
            <?php foreach($customers as $Customer): ?>
                <option value="<?= $Customer['Customer Name'] ?>"><?= $Customer['Customer Name'] ?></option>
            <?php endforeach; ?>

            .</select>
            <label for="customer_address">Customer Address:</label>
<input type="text" id="customer_address" name="customer_address" readonly>
<br>
<label for="customer_city">Customer City:</label>
<input type="text" id="customer_city" name="customer_city" readonly>
<br>
<label for="customer_state">Customer State:</label>
<input type="text" id="customer_state" name="customer_state" readonly>
<br>
<label for="customer_zip">Customer Zip:</label>
<input type="text" id="customer_zip" name="customer_zip" readonly>
<br>
<label for="customer_phone">Customer Phone:</label>
<input type="text" id="customer_phone" name="customer_phone" readonly>
<br>
<label for="customer_email">Customer Email:</label>
<input type="text" id="customer_email" name="customer_email" readonly>
<br>
<label for="customer_contact">Customer Contact:</label>
<input type="text" id="customer_contact" name="customer_contact" readonly>
<br>
<input type="hidden" id="customer_id" name="customer_id">

    </form>
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
                    $('#customer_id').val(customerData['customer_id']);
                    $('#customer_address').val(customerData['Customer Address']);
                    $('#customer_city').val(customerData['Customer City']);
                    $('#customer_state').val(customerData['Customer State']);
                    $('#customer_zip').val(customerData['Customer Zip']);
                    $('#customer_phone').val(customerData['Customer Phone']);
                    $('#customer_email').val(customerData['Customer Email']);
                    $('#customer_contact').val(customerData['Customer Contact']);
                }
            });
        } else {
            // Clear all the input fields
            $('#customer_id').val('');
            $('#customer_address').val('');
            $('#customer_city').val('');
            $('#customer_state').val('');
            $('#customer_zip').val('');
            $('#customer_phone').val('');
            $('#customer_email').val('');
            $('#customer_contact').val('');
        }
    });
});
</script>
<h1>Parts</h1>

<form action="submit_invoice.php" method="post">
    <label for="part">Part Number:</label>
    <select id="part" name="part">
        <option value="">Select a part</option>
        <?php foreach($parts as $part): ?>
            <option value="<?= $part['Part#'] ?>"><?= $part['Part#'] ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <input type="hidden" id="invoice_id" value="<?php echo $current_invoice; ?>">
    <br>
    <label for="volume">Volume:</label>
    <input type="text" id="volume" name="volume">
    <br>
    <label for="invoice_number">Invoice Number:</label>
    <input type="text" id="invoice_number" name="invoice_number" value="<?= $current_invoice ?>" readonly>
    <br>
    <label for="date">Date:</label>
    <input type="text" id="date" name="date" value="<?= $current_date ?>" readonly>
    <br>
    <label for="Steel_Or_Aluminum">Steel or Aluminum?:</label>
<select id="Steel_Or_Aluminum" name="Steel_Or_Aluminum">
<option value="">Select a material</option>
<option value="Steel">Steel</option>
<option value="Aluminum">Aluminum</option>
</select>
<br>

    <label for="Width(mm)">Width(mm):</label>
    <input type="double" id="width" name="width">
    <br>
    <label for="Pitch">Pitch(mm):</label>
    <input type="double" id="pitch" name="pitch">
    <br>
    <label for="Gauge">Gauge(mm):</label>
    <input type="double" id="gauge" name="gauge">
    <br>
    <label for="# Out"># Out:</label>
    <input type="int" id="# Out" name="# Out">
    <br>
    <label for="line_produced">Line Produced On:</label>
<select id="line_produced" name="line_produced">
<option value="">Select a line</option>
<?php foreach($lines as $line): ?>
    <option value="<?= $line['line_id'] ?>"><?= $line['Line_Location'] . ' - ' . $line['Line_Name'] ?></option>
<?php endforeach; ?>
</select>
    <br>
    <label for="Uptime">Uptime:</label>
    <input type="text" id="uptime" name="uptime">
    <br>
    <label for="pph">PPH:</label>
    <input type="text" id="pph" name="PPH">
    <br>
    <button id="add-part" type="button">Add Part</button>
<table id="parts_table">
<!-- Table headers go here -->
</table>
    <button id="submit-button" type="button">Submit</button>
</form>

<script>
$(document).ready(function(){
$("#part").change(function(){
    // Initially disable the "Add Part" button
$("#add-part").prop("disabled", true);
    var partNumber = $(this).val();
    if (partNumber != "") {
        // Disable the "Add Part" button while the AJAX call is in progress
        $("#add-part").prop("disabled", true);
        $.ajax({
            url: 'fetch_part.php',
            method: 'POST',
            data: {partNumber:partNumber},
            success: function(data) {
                var partData = JSON.parse(data);
                console.log('Part data:', partData);
                window.partData= partData;
                // Enable the "Add Part" button after the AJAX call has completed
                $("#add-part").prop("disabled", false);
                $('part')
                $('#volume').val(partData['Volume']);
                $('#width').val(partData['Width']);
                $('#pitch').val(partData['Pitch']);
                $('#gauge').val(partData['Gauge']);
                $('#num_outputs').val(partData['# Out']);
                $('#line_produced').val(partData['Line Produced On']);
                $('#uptime').val(partData['Uptime']);
                $('#pph').val(partData['PPH']);
            }
        });
    } else {
        // Clear all the input fields
        $('#volume').val('');
        $('#width').val('');
        $('#pitch').val('');
        $('#gauge').val('');
        $('#num_outputs').val('');
        $('#line_produced').val('');
        $('#uptime').val('');
        $('#pph').val('');
    }
});
});
</script>

</body>
</html>