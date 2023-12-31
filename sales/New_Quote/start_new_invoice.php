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
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
         window.user = "<?php echo $_SESSION['user']; ?>";
    </script>
   

    <title>Start New Quote</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
}

.form-container {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    border-radius: 10px;
    
    
   
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-container div {
    display: flex;
    background-color: white;
    
    border: 2px solid black;
    border-radius: 10px;
    padding: 20px;
    margin: 10px;
    box-sizing: border-box;
}
.form-container div div {
    flex: 1 0 50%;
    margin: 10px;
    box-sizing: border-box;
}

form label {
    display: block;
    margin-bottom: 5px;
    padding: 5px;
    font-weight: bold;
}

form input[type="text"], form input[type="number"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
}

form input[type="text"]:focus, form input[type="number"]:focus {
    border-color: #1B145D;
    box-shadow: 0 0 10px rgba(27, 20, 93, 0.1);
    outline: none;
}
form select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
}

.parts-table {
    border: 1px solid #ccc;
    padding: 20px;
    margin-top: 20px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

 body button {
    background-color: gray;
    color: black;
    padding: 10px 20px;
    border: black solid 2px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    width: 200px;
}

button:hover {
    background-color: lightgray;
}
#parts_table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
}

#parts_table th, #parts_table td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}

#parts_table tr:nth-child(even) {
    background-color: #f2f2f2;
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

.button-container {
    text-align: right;
    margin-right: 10px;
}
    </style>

</head>
<body style=" background-color: white;">
<div class= "button-container">
<a href="../index.php" class="return-button">Return to Dashboard</a>
</div>
<div class="form-container">
<div >
   
    <form action="submit_invoice.php" method="post">
        <label for="customer">Customer:</label>
        <select id="customer" name="customer">
        <option value="">Select a customer</option>
            <?php foreach($customers as $Customer): ?>
                <option value="<?= $Customer['Customer Name'] ?>"><?= $Customer['Customer Name'] ?></option>
            <?php endforeach; ?>

            .</select>
            <div>
            <label for="customer_address">Customer Address:</label>
<input type="text" id="customer_address" name="customer_address" readonly>
<br>
<label for="customer_city">Customer City:</label>
<input type="text" id="customer_city" name="customer_city" readonly>
</div>
<br>
<div>
<label for="customer_state">Customer State:</label>
<input type="text" id="customer_state" name="customer_state" readonly>
<br>
<label for="customer_zip">Customer Zip:</label>
<input type="text" id="customer_zip" name="customer_zip" readonly>
</div>
<br>
<div>
<label for="customer_phone">Customer Phone:</label>
<input type="text" id="customer_phone" name="customer_phone" readonly>
<br>
<label for="customer_email">Customer Email:</label>
<input type="text" id="customer_email" name="customer_email" readonly>
</div>
<br>
<label for="customer_contact">Customer Contact:</label>
<input type="text" id="customer_contact" name="customer_contact" readonly>
<br>
<input type="hidden" id="customer_id" name="customer_id">
<br>
<input type="hidden" id="user" value="<?php echo $_SESSION['user']; ?>">

    </form>
    
    </div>
    <div class="form-container">
    <form id="submit_new_part" action="submit_new_part.php" method="post">
    
        <div>
        <label for="partNumber">Part Number:</label>
        <input type="text" id="partNumber" name="partNumber">
        <label for="partName">Part Name:</label>
        <input type="text" id="partName" name="partName">
        </div>
        <br>
        <div>
        <label for="mill">Mill:</label>
        <input type="text" id="mill" name="mill">
        <label for="platform">Platform:</label>
        <input type="text" id="platform" name="platform">
        </div>
        <br>
        <div>
        <label for="type">Type:</label>
        <input type="text" id="type" name="type">
        <label for="surface">Surface:</label>
        <input type="text" id="surface" name="surface">
        <label for="materialType">Material Type:</label>
        <input type="text" id="materialType" name="materialType">
        </div> 
        <br>
        <div>
       
        <label for="palletType">Pallet Type:</label>
        <input type="text" id="palletType" name="palletType">
        <br>
        <label for="palletSize">Pallet Size:</label>
        <input type="text" id="palletSize" name="palletSize">
        <label for="palletWeight">Pallet Weight:</label>
        <input type="number" id="palletWeight" name="palletWeight">
        <label for="palletCost">Pallet Cost:</label>
        <input type="number" id="palletCost" name="palletCost">
        <label for="pallet_uses"># Pallet Uses:</label>
        <input type="number" id="pallet_uses" name="pallet_uses">
       
        </div>
        <br>
        <div>
        <label for="piecesPerLift">Pieces per Lift:</label>
        <input type="number" id="piecesPerLift" name="piecesPerLift">
        <label for="stacksPerSkid">Stacks per Skid:</label>
        <input type="number" id="stacksPerSkid" name="stacksPerSkid">
        <label for="skidsPerTruck">Skids per Truck:</label>
        <input type="number" id="skidsPerTruck" name="skidsPerTruck">
        </div>
        <br>
        <div>
        <label for="scrapConsumption">Scrap Consumption:</label>
        <input type="number" id="scrapConsumption" name="scrapConsumption" step="0.01">
        </div>
        
        </form>
    </div>
    
    
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

<div>


<form action="submit_invoice.php" method="post">
    <div>
    <label for="part"></label>
    <input type="hidden" id="part" name="part">
    <script>
    $(document).ready(function(){
    $('#partNumber').on('input', function() {
        $('#part').val($(this).val());
    });
});
    </script>
       <label for="invoice_number"></label>
    <input type="hidden" id="invoice_number" name="invoice_number" value="<?= $current_invoice ?>" readonly>
 
    <label for="date"></label>
    <input type="hidden" id="date" name="date" value="<?= $current_date ?>" readonly>
    

    <input type="hidden" id="invoice_id" value="<?php echo $current_invoice; ?>">

    <label for="volume">Volume:</label>
    <input type="text" id="volume" name="volume">
    </div>


    <div>
    <label for="Density">Density:</label>
    <input type="number" id="Density" name="Density">
    </div>

    <div>

    <label for="Width(mm)">Width(mm):</label>
    <input type="number" id="width" name="width">
    <br>
    <label for="Pitch">Pitch(mm):</label>
    <input type="number" id="pitch" name="pitch">
    <br>
    </div>
    <div>
    <label for="Gauge">Gauge(mm):</label>
    <input type="number" id="gauge" name="gauge">
    <br>
    <label for="# Out"># Out:</label>
    <input type="number" id="# Out" name="# Out">
    <br>
    </div>
    <div>
    <label for="line_produced">Line Produced On:</label>
<select id="line_produced" name="line_produced">
<option value="">Select a line</option>
<?php foreach($lines as $line): ?>
    <option value="<?= $line['line_id'] ?>"><?= $line['Line_Location'] . ' - ' . $line['Line_Name'] ?></option>
<?php endforeach; ?>
</select>
    </div>
    <div>
    <br>
    <label for="Uptime">Uptime:</label>
    <input type="text" id="uptime" name="uptime">
    <br>
    <label for="pph">PPH:</label>
    <input type="text" id="pph" name="PPH">
    <br>
    </div>
    <div>
    <label for="wash_and_lube">Wash and Lube:</label>
    <input type="checkbox" id="wash_and_lube" name="wash_and_lube">
    <select id ="steel_or_aluminum" name="steel_or_aluminum">
    <option value="">Select a material</option>
    <option value="steel">Steel</option>
    <option value="aluminum">Aluminum</option>
    </select>
</div>
<div>
    <label for= "material_markup_percent">Material Markup % :</label>
    <input type="number" id="material_markup_percent" name="material_markup_percent">
    </div>
    
    <button id="add-part" type="button">Add Part</button>
    </div>
    </div>
    <div class="parts-table">

<table id="parts_table">
<!-- Table headers go here -->
</table>

    <button id="submit-button" type="button">Submit</button>
</form>
</div>



<script>
$("#add-part").click(function(){
    var partNumber = $("#part").val();
    var wash_and_lube = document.getElementById('wash_and_lube').checked;
    if (partNumber != "") {
        // Capture form data before it's cleared
        const formData = {
           
            partNumber: document.getElementById('partNumber').value,
            partName: document.getElementById('partName').value,
            mill: document.getElementById('mill').value,
            platform: document.getElementById('platform').value,
            type: document.getElementById('type').value,
            surface: document.getElementById('surface').value,
            materialType: document.getElementById('materialType').value,
            palletType: document.getElementById('palletType').value,
            palletSize: document.getElementById('palletSize').value,
            pallet_uses: document.getElementById('pallet_uses').value,
            piecesPerLift: document.getElementById('piecesPerLift').value,
            stacksPerSkid: document.getElementById('stacksPerSkid').value,
            skidsPerTruck: document.getElementById('skidsPerTruck').value,
            scrapConsumption: document.getElementById('scrapConsumption').value
        }

        submitNewPartForm(formData).then(function() {
            $.ajax({
                url: 'fetch_part.php',
                method: 'POST',
                data: {partNumber:partNumber},
                success: function(data) {
                    var partData = JSON.parse(data);
                    console.log('Part data:', partData);
                    window.partData= partData;


                    addPart().then(function() {
                        clearPartInputs();
                    });
                    
                }
            });
        });
    } else {
        clearPartInputs();
         // Clear all the input fields
        
    }
});
</script>

</body>
</html>