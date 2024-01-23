<?php
session_start();
include '../../configurations/connection.php';
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('super-admin')){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}

$invoice_id = $_GET['invoice_id'];

// Fetch the quote and line items from the database
$invoice = $database->query("SELECT * FROM invoice WHERE invoice_id = '$invoice_id'")->fetch_assoc();
$line_items = $database->query("SELECT * FROM Line_Item WHERE invoice_id = '$invoice_id'")->fetch_all(MYSQLI_ASSOC);
// Then assign the fetched values to your variables

$customer_id = $invoice['customer_id'];

// Fetch customers for dropdown
$result = $database->query("SELECT `Customer Name` FROM Customer");
$customers = $result->fetch_all(MYSQLI_ASSOC);

// Fetch parts for dropdown
$result_part = $database->query("SELECT `Part#` FROM Part");
$parts = $result_part->fetch_all(MYSQLI_ASSOC);

// Fetch lines for dropdown
$line_result = $database->query("SELECT `line_id`, `Line_Location`, `Line_Name` FROM `Lines`");
$lines = $line_result->fetch_all(MYSQLI_ASSOC);
$resultmax = $database->query("SELECT MAX(`version`) as `max_version` FROM `invoice` WHERE invoice_id = '$invoice_id'");
$row = $resultmax->fetch_assoc();

$max_version = $row['max_version'];


// Get current date
$current_date = date("m-d-Y");
$invoice_author= $_SESSION['user'];
$author_parts = explode(' ', $invoice_author);
$author_initials = $author_parts[0][0] . $author_parts[1][0];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="codes.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
         window.user = "<?php echo $_SESSION['user']; ?>";
    </script>

    <title>Edit Quote</title>

    <style>
       body {
    font-family: 'Roboto', sans-serif; /* Use a modern, readable font */
    background-color: #f0f0f0;
    color: #333;
}

.form-container {
    display: flex;
    justify-content: center;
    
    margin-bottom: 20px;
    border-radius: 10px;
    width: auto;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.form-container div {
    display: flex;
    background-color: white;
    width: 90%;
    border: 2px solid #ccc;
    border-radius: 10px;
    padding: 5px;
    margin: 10px;
    box-sizing: border-box;
}

.form-container div div {
    flex: 1 0 50%;
    margin: auto;
    box-sizing: border-box;
}

#generate-pdf-button {
    background-color: #4CAF50; /* Green background */
    border: none; /* No border */
    color: white; /* White text */
    padding: 15px 32px; /* Some padding */
    text-align: center; /* Centered text */
    text-decoration: none; /* No underline */
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer; /* Mouse pointer on hover */
    border-radius: 12px; /* Rounded corners */
}

#generate-pdf-button:hover {
    background-color: #45a049; /* Darker green on hover */
}


form label {
    display: block;
    margin-bottom: 2px;
    padding: 5px;
    font-weight: bold;
}

form input[type="text"], form input[type="number"] {
    width: 100%;
    padding: 5px;
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
    width: 100%;
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
    table-layout: auto;
    border-collapse: collapse;
    
}
#parts_table input {
    width: 90%; /* Adjust this value as needed */
}

#parts_table th, #parts_table td {
    border: 1px solid #ccc;
    padding: 4px;
    text-align: left;
    font-size: 14px;
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
label[for="pdf_format"] {
    display: block;
    margin-top: 20px;
    margin-bottom: 10px;
    font-size: 16px;
    color: #333;
}

#pdf_format {
    width: 10%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    font-size: 16px;
    color: #333;
}
.green-highlight {
    background-color: yellow;
    color: black;
}

    .save-button {
        background-color: red;
        color: white;
    }
    .save-button {
    background-color: #FF0000; /* Red background */
    border: none; /* No border */
    color: white; /* White text */
    padding: 15px 32px; /* Some padding */
    text-align: center; /* Centered text */
    text-decoration: none; /* No underline */
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer; /* Mouse pointer on hover */
    border-radius: 12px; /* Rounded corners */
}

.save-button:hover {
    background-color: #CC0000; /* Darker red on hover */
}

.selected {
    background-color: #f0f0f0; /* Change this to your preferred color */
}
.return-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1B145D;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-weight: 700;
        }

        .return-button:hover {
            background-color: #111;
        }

        .return-button-container {
            text-align: right;
            margin-right: 10px;
        }

        #parts_table .part-number {
        white-space: normal;
        word-wrap: break-word;
    }
</style>
</head>
<body>
<div class="return-button-container">
    <a href="lookup_quote.php" class="return-button">Return to Quotes</a>
</div>

   
    <form action="submit_invoice.php" method="post">
       
        <select id="customer" name="customer">
        <option value="">Select a customer</option>
            <?php foreach($customers as $Customer): ?>
                <option value="<?= $Customer['Customer Name'] ?>"><?= $Customer['Customer Name'] ?></option>
            <?php endforeach; ?>
            .</select>
<input type="hidden" id="customer_address" name="customer_address" readonly>
<input type="hidden" id="customer_city" name="customer_city" readonly>
<input type="hidden" id="customer_state" name="customer_state" readonly>
<input type="hidden"id="customer_zip" name="customer_zip" readonly>
<input type="hidden"id="customer_phone" name="customer_phone" readonly>
<input type="hidden" id="customer_email" name="customer_email" readonly>
<input type="hidden"id="customer_contact" name="customer_contact" readonly>
<input type="hidden" id="customer_id" name="customer_id">
<input type="hidden" id="user" value="<?php echo $_SESSION['user']; ?>">

    </form>

<div class="form-container">
    
    <div class="form-container">
    <form id="submit_new_part" action="submit_new_part.php" method="post">
                
    <div>
        <label for="supplier_name">Supplier Name:</label>
        <input type="text" id="supplier_name" name="supplier_name">
        </div>
        <br>
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
        <label for="model_year">Model Year(if applicable):</label>
        <input type="number" id="model_year" name="model_year">
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
        <label for="palletWeight">Pallet Weight(Lbs):</label>
        <input type="number" id="palletWeight" name="palletWeight">
        <label for="palletCost">Pallet Cost:</label>
        <input type="number" id="palletCost" name="palletCost">
       
       
        </div>
        <br>
        <div>
        <label for="stacksPerSkid">Stacks per Skid:</label>
        <input type="number" id="stacksPerSkid" name="stacksPerSkid">
        <label for="pallet_uses"># Pallet Uses:</label>
        <input type="number" id="pallet_uses" name="pallet_uses">
        <label for="scrapConsumption">Scrap Consumption %:</label>
        <input type="number" id="scrapConsumption" name="scrapConsumption" step="0.01">
        </div>
        
        
        
        </form>
    </div>
    <div class= "form-container">


<form action="submit_invoice.php" method="post">

    <div>
  
 
    <label for="date"></label>
    <input type="hidden" id="date" name="date" value="<?= $current_date ?>" readonly>
    
    
    <input type="hidden" id="invoice_number" name="invoice_number" value="<?php echo $invoice_id; ?>" readonly>
    

    <label for="volume">Volume:</label>

    <input type="number" id="volume" name="volume">
    </div>
    <br>


    <div>
    <label for="Density">Density:</label>
    <input type="number" id="Density" name="Density">
    <label for="Width(mm)">Width(mm):</label>
    <input type="number" id="width" name="width">
    <label for="Pitch">Pitch(mm):</label>
    <input type="number" id="pitch" name="pitch">
    <label for="Gauge">Gauge(mm):</label>
    <input type="number" id="gauge" name="gauge">
    
    </div>
    <br>

    <div>
    <label for="nom?">Select Measurement Type:</label>
<select id="nom?" name="nom?">
    <option value="">Select a format</option>
    <option value="NOM">NOM</option>
    <option value="MIN">MIN</option>
    <option value="MAX">MAX</option>
</select>
<label for="blank_die?">Blank Die?</label>
<select id="blank_die?" name="blank_die?">
    <option value="">Select YES/NO</option>
    <option value="YES">YES</option>
    <option value="NO">NO</option>

</select>
<label for="trap">Trap:</label>
    <input type="text" id="trap" name="trap">
    </div>

    
    <br>

 


    <div>
    <label for="# Out"># Out:</label>
    <input type="number" id="# Out" name="# Out">
 
    <label for="line_produced">Line Produced On:</label>
<select id="line_produced" name="line_produced">
<option value="">Select a line</option>
<?php foreach($lines as $line): ?>
    <option value="<?= $line['line_id'] ?>"><?= $line['Line_Location'] . ' - ' . $line['Line_Name'] ?></option>
<?php endforeach; ?>
</select>
    </div>
    <br>

    <div>

    <label for="Uptime">Uptime %:</label>
    <input type="text" id="uptime" name="uptime">
    <label for="pph">PPH:</label>
    <input type="text" id="pph" name="PPH">
    <label for="wash_and_lube">Wash and Lube:</label>
    <input type="checkbox" id="wash_and_lube" name="wash_and_lube">

   
</div>
<br>


<div>
    <label for= "material_markup_percent">Material Markup % :</label>
    <input type="number" id="material_markup_percent" name="material_markup_percent">
 

    <label for="freight">Freight Cost</label>
    <input type="number" id="freight" name="freight">
    <label for="cost_per_lb">Material Cost / lb:</label>
    <input type="number" id="cost_per_lb" name="cost_per_lb">
    </div>
    
    <button id="add-part" type="button">Add Part</button>
    </div>
    </div>
    <div class="parts-table">

    
    
    <table class="parts-table" id="parts_table">

 
    <thead>
        <tr>
            <th>Part Number</th>
            <th>Volume</th>
            <th>Width(mm)</th>
            <th>Pitch(mm)</th>
            <th>Gauge(mm)</th>
            <th>Density</th>
            <th># Outputs</th>
            <th>Line Produced on</th>
            <th>Uptime</th>
            <th>PPH</th>
            <th>Pcs per Skid</th>
            <th>Skids per Truck</th>
            <th>Truck Weight(lb)</th>
            <th>Freight/pc</th>
            <th>Blanking/pc</th>
            <th>Packaging/pc</th>
            <th>Total/pc</th>
            

            <!-- Add more columns as needed -->
        </tr>
    </thead>
    <tbody>
    <?php foreach ($line_items as $item): ?>
        <tr class="line-item" data-item='<?php echo json_encode($item); ?>'>
        <td><?php echo $item['Part#']; ?></td>
        <td><?php echo $item['Volume']; ?></td>
        <td><?php echo $item['Width(mm)']; ?></td>
        <td><?php echo $item['Pitch(mm)']; ?></td>
        <td><?php echo $item['Gauge(mm)']; ?></td>
        <td><?php echo $item['Density']; ?></td>
        <td><?php echo $item['# Outputs'];?></td>
        <td><?php echo $item['Line Produced on']; ?></td>
        <td><?php echo $item['Uptime']." % "; ?></td>
        <td><?php echo $item['PPH']; ?></td>
        <td><?php echo $item['Pcs per Skid']; ?></td>
        <td><?php echo $item['Skids per Truck']; ?></td>
        <td><?php echo $item['Truck Weight(lb)']; ?></td>
        <td><input type="text" id="freight-cost-" class="freight-cost" value="<?php echo $item['freight per piece cost']; ?>"></td>
        <td><input type="text" id="blanking-cost-" class="blanking-cost" value="<?php echo $item['Blanking per piece cost']; ?>"></td>
    <td><input type="text" id="packaging-cost-" class="packaging-cost" value="<?php echo $item['Packaging Per Piece Cost']; ?>"></td>
        <td><?php echo $item['Total Cost per Piece']; ?></td>
</tr>
       
    </tr>
<?php endforeach; ?>
</tbody>

</table>

</div>
</div>
<style>
    .form-container {
        width: 100%; /* Ensures that the form container takes up the full width of its parent */
        text-align: center; /* Aligns the text to the left */
        border: 2px solid black;
        margin-bottom: 30px;
    border-radius: 10px;
    }
    .form-container textarea {
        width: 100%; /* Ensures that the textarea takes up the full width of its parent */
        padding: 10px;
    border: 3px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
    background-color: #f9f9f9;
    transition: all 0.3s ease;

    }
</style>
<div class="form-container">
    <label for="contingencies">Contingencies:</label>
    <textarea id="contingencies" name="contingencies" rows="4" cols="50" style="resize: both;"></textarea>
    <label for="invoice_files">Upload Files:</label>
    <input type="file" id="invoice_files" name="invoice_files[]" multiple>
</div>
<div>
<label for="pdf_format">Select PDF format:</label>
<select id="pdf_format" name="pdf_format">
    <option value="">Select a format</option>
    <option value="ford">Ford</option>
    <option value="thai_summit">Thai Summit</option>
</select>

</form>
<td><button class="save-button">Save Manual Overrides</button></td>
<button id="generate-pdf-button" class="generate-pdf-button">Generate PDF</button>
<button id="submit-button" type="button">Submit Recalulated Part</button>
</div>
</div>
</body>

<script>
    $('#customer').hide();

$(".line-item").click(function(){
        // Remove the 'selected' class from all line items
        $(".line-item").removeClass("selected");

        // Add the 'selected' class to the clicked line item
        $(this).addClass("selected");

    var clickedLineItem = null;
    console.log("Line item clicked");
    var item = $(this).data('item');
    customer_id =<?php echo $customer_id; ?>;
    console.log("Item data: ", item);
    $('#palletWeight').val(item['Pallet Weight(lb)']);
    $('#palletCost').val((item['palletCost']));
    $('#model_year').val(item['model_year']);
    $('#gauge').val(item['Gauge(mm)']);
    $('#width').val(item['Width(mm)']);
    $('#pitch').val(item['Pitch(mm)']);
    $('#trap').val(item['trap']);
    $('#volume').val(item['Volume']);
    $('#Density').val(item['Density']);
    $('#nom\\?').val(item['nom?']);
    $('#blank_die\\?').val(item['blank_die?']);
    $('input[id="# Out"]').val(item['# Outputs']);
    $('#uptime').val(item['Uptime']);
    $('#line_produced').val(item['Line Produced on']);
    $('#pph').val(item['PPH']);
    $('#freight').val(item['Pieces per Truck']*item['freight per piece cost']);
    $('#cost_per_lb').val(item['material_cost']/item['Blank Weight(lb)']);
    $('#material_markup_percent').val(item['material_markup_percent']*100);
    $('#invoice_number').val(item['invoice_id']);
    $('#customer').val(customer_id);
    
   



    // Make an AJAX request to fetch the part_id and additional data
    $.ajax({
        url: 'fetch_part_id.php', // The URL of the PHP script that will fetch the part_id and additional data
        type: 'post',
        data: {
            partNumber: item['Part#'],
            partName: item['Part Name']

        },
        success: function(response) {
            // The response from the server is a JSON string containing the part_id and additional data
            var data = JSON.parse(response);
            console.log("Part data: ", data);
            // You can now use the part_id and additional data to fill form fields
            $('#customer_id').val(data['customer_id']);
            $('#supplier_name').val(data['supplier_name']);
            $('#partNumber').val((data['Part#']));
            $('#partName').val(data['Part Name']);
            $('#mill').val(data['Mill']);
            $('#platform').val(data['Platform']);
            $('#type').val(data['Type']);
            $('#surface').val(data['Surface']);
            $('#materialType').val(data['Material Type']);
            $('#palletType').val(data['pallet_type']);
            $('#palletSize').val(data['pallet_size']);
            $('#pallet_uses').val(data['pallet_uses']);
            $('#stacksPerSkid').val(data['Stacks per Skid']);
            $('#scrapConsumption').val(data['Scrap Consumption']);
        }
    });
});

$("#add-part").click(function(){

    var partNumber = $("#partNumber").val();
    var max_version = <?php echo $max_version; ?>;
   

    var wash_and_lube = document.getElementById('wash_and_lube').checked;
    if (partNumber != "") {
        // Capture form data before it's cleared
        const formData = {
           
            invoice_number: (document.getElementById('invoice_number').value),
            pdf_format:document.getElementById('pdf_format').value,
            partNumber: (document.getElementById('partNumber').value),
            lineProduced: document.getElementById('line_produced').value,
            uptime: document.getElementById('uptime').value,
            pph: document.getElementById('pph').value,
            supplier_name: document.getElementById('supplier_name').value,
            partName: document.getElementById('partName').value,
            line_produced: document.getElementById('line_produced').value,
            mill: document.getElementById('mill').value,
            platform: document.getElementById('platform').value,
            type: document.getElementById('type').value,
            surface: document.getElementById('surface').value,
            materialType: document.getElementById('materialType').value,
            palletType: document.getElementById('palletType').value,
            palletSize: document.getElementById('palletSize').value,
            pallet_uses: document.getElementById('pallet_uses').value,
            stacksPerSkid: document.getElementById('stacksPerSkid').value,
            scrapConsumption: document.getElementById('scrapConsumption').value,
            contingencies: document.getElementById('contingencies').value,

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
                        $('#parts_table tr:last').addClass('green-highlight');
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

$(".save-button").click(function() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to override a calculated value",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, save it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var row = $(this).closest('tr');
            var index = row.index(); // Get the index of the row
            var partNumber = $('#part-number').val();
            var freightCost = $('#freight-cost-').val();
            var blankingCost = $('#blanking-cost-').val();
            var packagingCost = $('#packaging-cost-').val();
            var totalCost = $('#total-cost-').val();

            // Send the new values to the server to be saved
            $.ajax({
                url: 'save_line_item.php',
                type: 'post',
                data: {
                    freightCost: freightCost,
                    blankingCost: blankingCost,
                    packagingCost: packagingCost,
                    totalCost: totalCost,
                    partNumber: partNumber
                },
                success: function(response) {
                    // Handle the server response here
                    Swal.fire(
                        'Saved!',
                        'Your changes have been saved.',
                        'success'
                    )
                    // Refresh the page
                    location.reload();
                }
            });
        }
    })
});
$(".generate-pdf-button").click(function() {
    var invoiceNumber = $('#invoice_number').val(); // Get the invoice number from the input field
    var pdfFormat = $('#pdf_format').val(); // Get the selected PDF format

    // Check the selected PDF format and call the appropriate PDF generator
    if (pdfFormat === 'ford') {
        window.location.href = 'generate_ford_quote_pdf.php?invoice_id=' + invoiceNumber;
    } else if (pdfFormat === 'thai_summit') {
        window.location.href = 'generate_thai_summit_quote.php?invoice_id=' + invoiceNumber;
    } else {
        alert('Please select a PDF format.');
    }
});
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
$(document).ready(function() {
    // Hide the "Submit Changes" button initially
    $('#submit-button').hide();

    // Show the "Submit Changes" button when the "Add Part" button is clicked
    $('#add-part').click(function() {
        $('#submit-button').show();
    });

    // Handle click event for "Submit Recalculated Part" button
    $("#submit-button").click(function() {
        var pdfFormat = $('#pdf_format').val(); // Get the selected PDF format

        if (!pdfFormat) {
            Swal.fire({
                title: 'Success',
                text: 'Changes submitted successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        } 
    });
});
</script>
</html>
