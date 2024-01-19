<?php
session_start();
include '../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection

// Fetch customers for dropdown
$result = $database->query("SELECT `Customer Name` FROM Customer");
$customers = $result->fetch_all(MYSQLI_ASSOC);


$invoice_author= $_SESSION['user'];
$author_parts = explode(' ', $invoice_author);
$author_initials = $author_parts[0][0] . $author_parts[1][0];


// Get current date
$current_date = date("m-d-Y");
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
// Count the number of invoices in the database
$result = $database->query("SELECT COUNT(*) as invoiceCount FROM `invoice`");
$invoiceCount = $result->fetch_assoc()['invoiceCount'];

// Increment the invoice count
$newInvoiceId = $invoiceCount + 1;

// Set the invoice_id
$invoice_id = "TWB_" . $author_initials . "_" . $newInvoiceId;





?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
           body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}



.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
    margin-bottom: 20px;
}

.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

.tab button:hover {
    background-color: #ddd;
}

.tab button.active {
    background-color: #ccc;
}

.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 15px;
}



form label {
    display: block;
    margin-bottom: 2px;
    padding: 5px;
    font-weight: bold;
}

form input[type="text"], form input[type="number"] {
    width: 25%;
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
    width: 25%;
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

button {
    background-color: #4da6ff; /* light blue */
 
    padding: 10px 20px;
    border: black;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    width: 200px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #007bff; /* darker blue on hover */
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

#parts_table td {
    white-space: normal;
    word-wrap: break-word;
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
    margin-top: 5px;
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

#add-part {
    background-color: green;
    color: white;
    padding: 10px 20px;
}

#add-part:hover {
    background-color: darkgreen;
}
#submit-button {
    background-color: green;
    color: white;
    padding: 10px 20px;
}

#submit-button:hover {
    background-color: darkgreen;
}
#add-contingencies {
    background-color: yellow;
    color: black;
    padding: 10px 20px;
}

#add-contingencies:hover {
    background-color: darkyellow;
}

.nav-tabs {
    border-bottom: 3px solid #343a40; /* Dark grey */
}

.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
    color: #fff; /* White text */
    background-color: #343a40; /* Dark grey background */
}

.nav-tabs .nav-link:hover {
    border-color: #343a40;
    background-color: #495057; /* Darker grey on hover */
}

.nav-tabs .nav-link.active {
    color: #fff; /* White text */
    background-color: #495057; /* Darker grey background */
    border-color: #343a40 #343a40 #fff; /* Dark grey border */
}
.form-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.container {
    background-color: #fff;
  width: 40%; /* Adjust this to change the form width */
  padding: 5px;
  border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #ccc;
 
}
button.next-tab {
    background-color: #007BFF !important; /* Change to your preferred color */
    color: white !important;
    border: none !important;
    padding: 10px 20px !important;
    text-align: center !important;
    text-decoration: none !important;
    display: inline-block !important;
    font-size: 16px !important;
    margin: 4px 2px !important;
    transition-duration: 0.4s !important;
    cursor: pointer !important;
    border-radius: 5px !important;
}

button.next-tab:hover {
    background-color: #0069D9 !important; /* Change to your preferred color */
    color: white !important;
}

/* Style for the address form */
#stops {
    background-color: #f9f9f9; /* Light grey background */
    border: 1px solid #ccc; /* Grey border */
    border-radius: 5px; /* Rounded corners */
    padding: 10px; /* Padding */
    margin-bottom: 10px; /* Space below the form */
}

/* Style for the address input fields */
.address {
    width: 50%; /* Almost full width */
    padding: 5px; /* Padding */
    border: 1px solid #ccc; /* Grey border */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Larger font size */
    margin-bottom: 10px; /* Space below each input field */
}

/* Style for the "Add Another Stop" button */
#add_stop {
    background-color: #007BFF; /* Blue background */
    color: white; /* White text */
    border: none; /* No border */
    padding: 10px 20px; /* Padding */
    text-align: center; /* Centered text */
    text-decoration: none; /* No underline */
    display: inline-block; /* Necessary for padding to take effect */
    font-size: 16px; /* Larger font size */
    margin: 4px 2px; /* Some margin */
    transition-duration: 0.4s; /* Transition effect */
    cursor: pointer; /* Cursor changes when hovering over the button */
    border-radius: 5px; /* Rounded corners */
}

/* Style for the "Add Another Stop" button when hovered over */
#add_stop:hover {
    background-color: #0069D9; /* Darker blue background */
    color: white; /* White text */
}
/* Style for the "Calculate Distance" button */
#calculate_distance {
    background-color: #28a745; /* Green background */
    color: white; /* White text */
    border: none; /* No border */
    padding: 10px 20px; /* Padding */
    text-align: center; /* Centered text */
    text-decoration: none; /* No underline */
    display: inline-block; /* Necessary for padding to take effect */
    font-size: 16px; /* Larger font size */
    margin: 4px 2px; /* Some margin */
    transition-duration: 0.4s; /* Transition effect */
    cursor: pointer; /* Cursor changes when hovering over the button */
    border-radius: 5px; /* Rounded corners */
}

/* Style for the "Calculate Distance" button when hovered over */
#calculate_distance:hover {
    background-color: #218838; /* Darker green background */
    color: white; /* White text */
}

/* Style for the "Calculate Shipping" button */
#calculate_shipping {
    background-color: #ffc107; /* Yellow background */
    color: black; /* Black text */
    border: none; /* No border */
    padding: 10px 20px; /* Padding */
    text-align: center; /* Centered text */
    text-decoration: none; /* No underline */
    display: inline-block; /* Necessary for padding to take effect */
    font-size: 16px; /* Larger font size */
    margin: 4px 2px; /* Some margin */
    transition-duration: 0.4s; /* Transition effect */
    cursor: pointer; /* Cursor changes when hovering over the button */
    border-radius: 5px; /* Rounded corners */
}

/* Style for the "Calculate Shipping" button when hovered over */
#calculate_shipping:hover {
    background-color: #e0a800; /* Darker yellow background */
    color: black; /* Black text */
}
/* Style for the "Delete Stop" button */
.delete-stop {
    background-color: transparent; /* Transparent background */
    color: #333; /* Dark grey text */
    border: none; /* No border */
    padding: 5px; /* Padding */
    text-align: center; /* Centered text */
    text-decoration: none; /* No underline */
    display: inline-block; /* Necessary for padding to take effect */
    font-size: 16px; /* Larger font size */
    margin: 4px 2px; /* Some margin */
    cursor: pointer; /* Cursor changes when hovering over the button */
}

/* Style for the "Delete Stop" button when hovered over */
.delete-stop:hover {
    color: #ff0000 !important ; /* Red text on hover */
}
    </style>
    <title>Quick Quote</title>
</head>
<body style="background-image: url('../../images/steel_coils.jpg'); background-size: cover;">
    <div class="button-container">
        <a href="../index.php" class="return-button">Return to Dashboard</a>
    </div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
    </h1>
 
    <div class="form-wrapper">
        <div class="container">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#Tab1">Measurements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#Tab2">Production</a>
                </li>
            </ul>
            <div class="tab-content">
         
                
            <div class="tab-pane fade show active" id="Tab1">
            <form id="new_quick_quote">
                <br>
        
                <div style="display: flex; align-items: center;">
                <input type="hidden" id="invoice_id" name="invoice_id" value="<?= $invoice_id ?>" readonly>
                    <label for="customer_name" style="width: 30%;">Customer Name:</label>
                    <select id="customer_name" name="customer_name" style="width: 20%;">
                    <option value="">Select a customer</option>
                    <?php foreach($customers as $customer): ?>
                    <option value="<?= $customer['Customer Name'] ?>"><?= $customer['Customer Name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="part_number" style="width: 30%;">Part Number:</label>
                    <input type="text" id="part_number" name="part_number" style="width: 15%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="material_type" style="width: 30%;">Material Type?:</label>
                    <select id="material_type" name="material_type" style="width: 13%;">
                    <option value="">Select a Material</option>
                    <option value="Steel">Steel</option>
                    <option value="Aluminum">Aluminum</option>
                    </select>
                </div>
                <div id="density" style="display: none; align-items: center;">
                    <input type="float" id="density_input" name="density"  style="width: 15%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="gauge" style="width: 30%;">Gauge(mm):</label>
                    <input type="number" id="gauge" name="gauge" style="width: 10%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="width" style="width: 30%;">Width(mm):</label>
                    <input type="number" id="width" name="width" style="width: 10%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="pitch" style="width: 30%;">Pitch(mm):</label>
                    <input type="number" id="pitch" name="pitch" style="width: 10%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="ctl" style="width: 30%;">CTL or NON CTL:</label>
                    <select id="ctl" name="ctl" style="width: 10%;">
                    <option value="">Select a format</option>
                    <option value="CTL">CTL</option>
                    <option value="NON CTL">Configured</option>
                    </select>
                </div>
                <br>
                <div id="scrap_weight_div" style="display: none; align-items: center;">
                    <label for="scrap_weight" style="width: 30%;">Approximate Scrap Weight(lb):</label>
                    <input type="number" id="scrap_weight" name="scrap_weight" value="0" style="width: 10%;">
                </div>
                <div style="display: none; ">
                    
                    <input type="hidden" id="customer_id" name="customer_id">
                    <input type="hidden" id="quote_name" name="quote_name">
                    <input type="hidden" id="user" value="<?php echo $_SESSION['user']; ?>">
                    <input type="hidden" id="date" name="date" value="<?= $current_date ?>" readonly>
                    
                </div>
                <div style="text-align: right; margin-top: 20px;">
            <button type="button" class="btn btn-primary next-tab">Next</button>
            </div>
            </div>
            
            </form>

            
            <div class="tab-pane fade" id="Tab2">
            <form id="new_quick_quote2"action="generate_excel.php" method="post">
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="skid_weight" style="width: 30%;">Finished Goods Skid Weight (lbs):</label>
                    <input type="text" id="skid_weight" name="skid_weight" style="width: 10%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="line_produced" style="width: 30%;">Line Produced On:</label>
                    <select id="line_produced" name="line_produced" style="width: 25%;">
                        <option value="">Select a line</option>
                        <?php foreach($lines as $line): ?>
                            <option value="<?= $line['line_id'] ?>"><?= $line['Line_Location'] . ' - ' . $line['Line_Name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="pph" style="width: 30%;">Estimated Parts/hr:</label>
                    <input type="number" id="parts_per_hour" name="parts_per_hour" style="width: 10%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                <label for="wood_packaging_selection" style="width: 30%;">Wood Packaging Cost</label>
                    <select id="wood_packaging_selection" name="wood_packaging_selection" style="width: 10%;">
                        <option value="">Select a Size</option>
                        
                            <option value="50">Small: $50</option>
                            <option value="100">Medium: $100</option>
                            <option value="200">Large: $200</option>
                       
                    </select>
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                <label for="ownage" style="width: 30%;">Owned or Toll?</label>
                    <select id="own_or_toll" name="own_or_toll" style="width: 10%;">
                        <option value="">Select</option>
                        
                            <option value="own">Owned</option>
                            <option value="toll">Toll</option>
                        
                       
                    </select>
                </div>
                <div id="steel_cost" style="display: none; align-items: center;">
                    <label for="cost_per_pound" style="width: 30%;">Cost Per Pound:</label>
                    <input type="number" id="cost_per_pound" name="cost_per_pound" value="0" style="width: 10%;">
                    <label for="markup" style="width: 30%;">Markup %</label>
                    <input type="number" id="markup" name="markup" value="0" style="width: 10%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="shipping_location" style="width: 30%;">Ship to Location:</label>
                    <input type="text" id="shipping_location" name="location" style="width: 20%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="freight_cost" style="width: 30%;">Freight Cost:</label>
                    <input type="number" id="freight_cost" name="freight_cost" style="width: 10%;">
                </div>
                
                <div style="text-align: right; margin-top: 20px;">
                <button id="save-button" class="btn btn-primary">Save</button>
                <button id="add-contingencies" type="button">Add Contingencies</button>
                <input type="hidden" id="contingencies" name="contingencies">
                <input type="hidden" id="results" name="results">
               
                </div>
            </form>
            </div>
         
        </div>
    </div>
</div>
</body>
<script>
$(document).ready(function() {
    $('#material_type').change(function() {
        var material = $(this).val();
        if (material == 'Steel') {
            $('#density_input').val(0.2833);
        } else if (material == 'Aluminum') {
            $('#density_input').val(0.0987);
        } else {
            $('#density_input').val('');
        }
    });

    $('#ctl').change(function() {
        if ($(this).val() == 'CTL') {
            $('#scrap_weight_div').hide();
        } else {
            $('#scrap_weight').val(0);
            $('#scrap_weight_div').show();
        }
    });
});

$(document).ready(function() {
    $(".next-tab").click(function() {
        var currentTab = $('.nav-tabs .active').attr('id'); // Get current active tab's id
        var nextTab = $("#" + currentTab).parent().next().find('a').attr('id'); // Get next tab's id
        $('#' + nextTab).tab('show'); // Set next tab as active
    });
});


$(document).ready(function() {
    $('#part_number').change(function() {
        var partName = $(this).val();
        var authorInitials = "<?php echo $author_initials; ?>";
        $('#quote_name').val(authorInitials + "_" + partName + "_quickQuote");
    });
});

$(document).ready(function() {
    $('#own_or_toll').change(function() {
        if ($(this).val() == 'own') {
            $('#steel_cost').show();
        } else {
            $('#cost_per_pound').val(0);
            $('#steel_cost').hide();
        }
    });
});

var selectedContingencies = [];

document.getElementById('add-contingencies').addEventListener('click', function() {
    var contingencies = [
        'Quote is based on information provided and could be re-negotiated should material price, blank nesting, blank processing, packaging, and or freight change.',
        'Exposed parts will be ran top side prime, Light defects could occur during loading and unloading of blanks on the laser.',
        'If Exposed defects can not be accomidated due to sheet feed proceedure, TMB respecfully No Quotes Exposed laser processing.',
        'Quoted pricing with blanks packaged on returnable skids.',
        'All scrap will be retained by TMB.',
        'Material pricing will change with Rivian negotiated contracts and will increase/decrease percentage based adders such as scrap rate, financing, gross margin, and SG&A.',
        'Stampers required to take full truckload quantities.',
        'Pricing does not include slitting and coils are expected to come in with slit edge and at widths designated.',
        'Any surcharges, customs, duties, or other charges are to be a pass through.',
        'Blank processing at  Target Metal Blanking New Boston and Sauk Village based on freight.'

        // Add more contingencies here
    ];

    var html = '<form id="contingencies-form" style="display: grid; gap: 10px; max-width: 500px;">';
    contingencies.forEach(function(contingency, index) {
        var backgroundColor = index % 2 === 0 ? '#ffffff' : '#f0f0f0';
        var isChecked = selectedContingencies.includes(contingency) ? ' checked' : '';
        html += '<div style="display: flex; align-items: center; background-color: ' + backgroundColor + ';">';
        html += '<input type="checkbox" id="' + contingency + '" name="contingency" value="' + contingency + '"' + isChecked + '>';
        html += '<label for="' + contingency + '" style="margin-left: 5px;">' + contingency + '</label>';
        html += '</div>';
    });
    html += '</form>';

    Swal.fire({
        title: 'Select Contingencies',
        html: html,
        preConfirm: function() {
            selectedContingencies = [];
            document.querySelectorAll('#contingencies-form input:checked').forEach(function(checkbox) {
                selectedContingencies.push(checkbox.value);
            });
            return selectedContingencies;
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            document.getElementById('contingencies').value = result.value.join('\n');
        }
    });
});

$(document).ready(function() {
    var results = [];
    $('#save-button').click(function(e) {
        e.preventDefault();

        // Grab the values from the input fields
        var customerName = $('#customer_name').val();
        var partNumber = $('#part_number').val();
        var materialType = $('#material_type').val();
        var density = $('#density_input').val();
        var gauge = $('#gauge').val();
        var width = $('#width').val();
        var pitch = $('#pitch').val();
        var ctl = $('#ctl').val();
        var scrapWeight = $('#scrap_weight').val();
        var skidWeight = $('#skid_weight').val();
        var lineProduced = $('#line_produced').val();
        var partsPerHour = $('#parts_per_hour').val();
        var woodPackagingSelection = $('#wood_packaging_selection').val();
        var ownOrToll = $('#own_or_toll').val();
        var costPerPound = $('#cost_per_pound').val();
        var shippingLocation = $('#shipping_location').val();
        var freightCost = $('#freight_cost').val();
        var contingencies = $('#contingencies').val();
        var user = $('#user').val();
        var date = $('#date').val();
        var quote_name = $('#quote_name').val();
        var invoice_id = $('#invoice_id').val() + '_QuickQuote';
        var hourlyRate;
        var markup = $('#markup').val();
        markup = markup/100;

        if(lineProduced==1|| lineProduced==2 || lineProduced==3 || lineProduced==4 || lineProduced==5 || lineProduced==6 || lineProduced==7 || lineProduced==8 || lineProduced==9 || lineProduced==10)
        {
            hourlyRate=850;
        }


        else if (lineProduced==11 || lineProduced==12 || lineProduced==13 || lineProduced==14 )
        {
            hourlyRate=900;

        }  


        else if (lineProduced==15|| lineProduced==16 || lineProduced==17 || lineProduced==18 )
        {
            hourlyRate=850;

        }
        else if(lineProduced==19 || lineProduced==20)
        {
            hourlyRate=950;
        }
        else if(lineProduced==21)
        {
            hourlyRate=350;
        }
       
    


        // Add more variables as needed

        // Perform your calculations
        var grossWeight = (((width * pitch * gauge * density) / 100000) * 2.20462).toFixed(3); // in pounds
        console.log(grossWeight);
        var pcsPerLift= Math.floor(330.2/gauge);
        console.log(pcsPerLift);
        var netWeight = (grossWeight - scrapWeight).toFixed(3);
        console.log(netWeight);
        var liftWeight= (pcsPerLift * netWeight).toFixed(3);
        console.log(liftWeight);
        var liftsPerTruck = Math.floor(40000/liftWeight);
        console.log(liftsPerTruck);
        var blankingCostPerPiece = (hourlyRate / partsPerHour).toFixed(3);
        console.log(blankingCostPerPiece);
        var packagingCostPerPiece = (woodPackagingSelection /pcsPerLift).toFixed(3);
        console.log(packagingCostPerPiece);
        var pcsPerTruck = pcsPerLift * liftsPerTruck;
        console.log(pcsPerTruck);
        var freightPerPiece = (freightCost / pcsPerTruck).toFixed(3);
        console.log(freightPerPiece);
        var material_cost = (((costPerPound*markup)+costPerPound) * netWeight).toFixed(3);
        var total_cost_per_piece = (parseFloat(blankingCostPerPiece) + parseFloat(packagingCostPerPiece) + parseFloat(freightPerPiece) + parseFloat(material_cost)).toFixed(3);


        var contingencies = $('#contingencies').val();
        var date = $('#date').val();
        var invoice_author = $('#user').val();
        var ship_to_location = $('#shipping_location').val();


        var result = [customerName,partNumber,grossWeight,netWeight,shippingLocation,blankingCostPerPiece,packagingCostPerPiece,freightPerPiece,invoice_id,contingencies,date,invoice_author,lineProduced,partsPerHour,ship_to_location,material_cost,total_cost_per_piece];

   results.push(result);
    console.log(results);
   Swal.fire({
        title: 'Continue adding parts?',
        showDenyButton: true,
        confirmButtonText:'No, submit this quote',
        denyButtonText: 'Add more parts',
    }).then((result) => {
        if (result.isConfirmed) {
            // set Results to hidden input
            $('#results').val(JSON.stringify(results));
            $('#new_quick_quote2').submit();
    // Convert the results array to a JSON string
    var submit_quote_results = JSON.stringify(results);
// Send an AJAX request to submit_quote.php
    $.ajax({
    url: 'submit_quote.php',
    type: 'POST',
    data: {
        submit_quote_results: submit_quote_results
    },
    success: function(response) {
        // Log the response to the console
        console.log(response);
    },
    error: function(jqXHR, textStatus, errorThrown) {
        // Log the error to the console
        console.error(textStatus, errorThrown);
    }
});

// Submit the form
           
        } else if (result.isDenied) {
             // If the user clicked "Add more parts", clear the input boxes
            // Replace 'inputField1', 'inputField2', etc. with the actual IDs of your input fields
            $('#part_number').val('');
            $('#gauge').val('');
            $('#width').val('');
            $('#pitch').val('');
            $('#ctl').val('');
            $('#scrap_weight').val('');
            $('#skid_weight').val('');
            $('#line_produced').val('');
            $('#parts_per_hour').val('');
            $('#wood_packaging_selection').val('');
            $('#own_or_toll').val('');
            $('#cost_per_pound').val('');
            $('#shipping_location').val('');
            $('#freight_cost').val('');
            $('#customer_name').val('');
            $('#material_type').val('');
        }
    });
});



});
</script>
</html>