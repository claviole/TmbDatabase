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
    <script src="codes.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLEYS7vn5FTgmGoHl7-5kdWcCE62CMhc8&libraries=places"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
         window.user = "<?php echo $_SESSION['user']; ?>";
    </script>
        <script>
function openTab(evt, tabId) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].style.backgroundColor = ""; // Reset the background color
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabId).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
   

    <title>Start New Quote</title>
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
      <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#Tab1">Customer Information</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#Tab2">Part Information</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="tab3-tab" data-toggle="tab" href="#Tab3">Measurements</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="tab4-tab" data-toggle="tab" href="#Tab4">Operations</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="tab5-tab" data-toggle="tab" href="#Tab5">Freight Information</a>
    </li>
    <li class="nav-item">
    <a class="nav-link disabled" id="tab6-tab" data-toggle="tab" href="#Tab6" role="tab" aria-controls="Tab6" aria-selected="false">Review Quote</a>
</li>
  </ul>
    <div class="tab-content">
    <div class="tab-pane fade show active" id="Tab1">
   
    <form id=customer_informations>
    <div style="text-align: left; margin-top: 20px;">
        <button id="add-customer-btn" class="btn btn-primary">Add New Customer</button>
        </div>
        <br>
        <div style="display: flex; justify-content: space-between;">
            <div style="flex: 1; margin-right: 10px;">
                <div style="display: flex; align-items: center;">
                    <label for="customer" style="width: 30%;">Select Customer:</label>
                    <select id="customer" name="customer" style="width: 50%;">
                        <option value="">Select a customer</option>
                        <?php foreach($customers as $Customer): ?>
                            <option value="<?= $Customer['Customer Name'] ?>"><?= $Customer['Customer Name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="customer_city" style="width: 30%;">City:</label>
                    <input type="text" id="customer_city" name="customer_city" readonly style="width: 50%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="customer_state" style="width: 30%;">State:</label>
                    <input type="text" id="customer_state" name="customer_state" readonly style="width: 50%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="customer_contact" style="width: 30%;">Contact:</label>
                    <input type="text" id="customer_contact" name="customer_contact" readonly style="width: 50%;">
                </div>
                <br>
            </div>
            <div style="flex: 1; margin-left: 10px;">
                <div style="display: flex; align-items: center;">
                    <label for="customer_address" style="width: 30%;">Address:</label>
                    <input type="text" id="customer_address" name="customer_address" readonly style="width: 50%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="customer_zip" style="width: 30%;">Zipcode:</label>
                    <input type="text" id="customer_zip" name="customer_zip" readonly style="width: 50%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="customer_phone" style="width: 30%;">Phone:</label>
                    <input type="text" id="customer_phone" name="customer_phone" readonly style="width: 50%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="customer_email" style="width: 30%;">Email:</label>
                    <input type="text" id="customer_email" name="customer_email" readonly style="width: 50%;">
                </div>
                <br>
                <input type="hidden" id="customer_id" name="customer_id">
                <input type="hidden" id="user" value="<?php echo $_SESSION['user']; ?>">
            </div>
        </div>
       
        <div style="text-align: right; margin-top: 20px;">
            <button type="button" class="btn btn-primary next-tab">Next</button>
        </div>
    </form>
</div>




    


    <div class="tab-pane fade" id="Tab2">
    <style>
#customer_supplied_material {
    transform: scale(1.5);
}
#customer_provided_die {
    align-items: right;
    transform: scale(1.5);
}
</style>
    <form id="submit_new_part" action="submit_new_part.php" method="post">
    <br>    
    <div style="display: flex; align-items: center; justify-content: space-evenly">
    <div style="display: flex; align-items: center;">
    <label for="customer_supplied_material">Customer Supplied Material? :</label>
    <input type="checkbox" id="customer_supplied_material" name="customer_supplied_material">
    </div>
    <div style="display: flex; align-items: center;">
     

        <label for="customer_provided_die">Did the Customer Provide a Die?:</label>
        <input type="checkbox" id="customer_provided_die" name="customer_provided_die" onchange="toggleDieReviewer()">
   </div>
    </div>
        
        
        <br>
        <div style="display: flex; justify-content: space-between;">
    <div style="flex: 1; margin-right: 10px; padding: 10px;">
        <div id="supplier_name_div"style="display: flex; align-items: center;">
            <label for="supplier_name" style="width: 30%;">Supplier Name:</label>
            <input type="text" id="supplier_name" name="supplier_name"style="width: 30%;">
        </div>
        <br>
        <div style="display: flex; align-items: center;">
            <label for="partNumber" style="width: 30%;">Part Number:</label>
            <input type="text" id="partNumber" name="partNumber" style="width: 30%;">
        </div>
        <br>
        <div style="display: flex; align-items: center;">
            <label for="mill" style="width: 30%;">Mill:</label>
            <input type="text" id="mill" name="mill" style="width: 30%;">
        </div>
        <br>
        <div style="display: flex; align-items: center;">
            <label for="model_year" style="width: 30%;">Model Year(if applicable):</label>
            <input type="number" id="model_year" name="model_year" style="width: 30%;">
        </div>
        <br>
        <div style="display: flex; align-items: center;">
            <label for="surface" style="width: 30%;">Surface:</label>
            <select id="surface" name="surface" style="width: 30%;">
                <option value="">Select a surface</option>
                <option value="Exposed">Exposed</option>
                <option value="Unexposed">Unexposed</option>
                <option value="Semi-exposed">Semi-exposed</option>
            </select>
        </div>
        <br>
        <div style="display: flex; align-items: center;">
        <label for="# Out" style="width: 30%;"># Out:</label>
        <input type="number" id="# Out" name="# Out"style="width: 30%;">
        </div>
        <br>
    </div>
    <div style="flex: 1; margin-left: 10px; padding: 10px;">
         <div id="die_reviewer" style="display: none; align-items: center;">
            <label for="die_reviewer_input" style="width: 30%;">Die Reviewer:</label>
            <input type="text" id="die_reviewer_input" name="die_reviewer_input" style="width: 30%;">
        </div>
        <br>
        <div style="display: flex; align-items: center;">
            <label for="partName" style="width: 30%;">Part Name:</label>
            <input type="text" id="partName" name="partName" style="width: 30%;">
        </div>
        <br>
        <div style="display: flex; align-items: center;">
            <label for="platform" style="width: 30%;">Platform:</label>
            <input type="text" id="platform" name="platform" style="width: 30%;">
        </div>
        <br>
        <div style="display: flex; align-items: center;">
            <label for="type" style="width: 30%;">Type:</label>
            <select id="type" name="type" style="width: 30%;">
                <option value="">Select a type</option>
                <option value="Configured">Configured</option>
                <option value="Cut To Length">Cut To Length</option>
            </select>
        </div>
        <br>
        <div style="display: flex; align-items: center;">
            <label for="materialType" style="width: 30%;">Material Type:</label>
            <input type="text" id="materialType" name="materialType" style="width: 30%;">
        </div>
        <br>
        <div style="display: flex; align-items: center;">
            <label for="scrapConsumption" style="width: 30%;">Head/Tail Scrap Allowance(0-6%):</label>
            <input type="number" id="scrapConsumption" name="scrapConsumption" step="1.00" max="6" style="width: 30%;">
        </div>
        </div>
        </div>
        <div style="text-align: right; margin-top: 20px;">
            <button type="button" class="btn btn-primary next-tab">Next</button>
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

<div class="tab-pane fade" id="Tab3">
    <form id="measurements_tab">
    <input type="hidden" id="part" name="part">
    <script>
    $(document).ready(function(){
    $('#partNumber').on('input', function() {
        $('#part').val($(this).val());
    });
});
    </script>
 

    <input type="hidden" id="date" name="date" value="<?= $current_date ?>" readonly>
    <input type="hidden" id="invoice_number" name="invoice_number" readonly>
    <script>
$(document).ready(function(){
    $('#invoice_number').val('<?php echo $invoice_id; ?>');
});
</script>
        <div style="display: flex; justify-content: space-between;">
            <div style="flex: 1; margin-right: 10px; padding: 10px;">
                <div style="display: flex; align-items: center;">
                    <label for="volume" style="width: 30%;">Volume:</label>
                    <input type="number" id="volume" name="volume" style="width: 30%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="Density" style="width: 30%;">Density (kg/m³):</label>
                    <input type="number" id="Density" name="Density" style="width: 30%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="Width(mm)" style="width: 30%;">Width(mm):</label>
                    <input type="number" id="width" name="width" style="width: 30%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="Pitch" style="width: 30%;">Pitch(mm):</label>
                    <input type="number" id="pitch" name="pitch" style="width: 30%;">
                </div>
            </div>
            <div style="flex: 1; margin-left: 10px; padding: 10px;">
                <div style="display: flex; align-items: center;">
                    <label for="Gauge" style="width: 30%;">Gauge(mm):</label>
                    <input type="number" id="gauge" name="gauge" style="width: 30%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="nom?" style="width: 30%;">Select Measurement Type:</label>
                    <select id="nom?" name="nom?" style="width: 30%;">
                        <option value="">Select a format</option>
                        <option value="NOM">NOM</option>
                        <option value="MIN">MIN</option>
                        <option value="MAX">MAX</option>
                    </select>
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="blank_die?" style="width: 30%;">Blank Die?</label>
                    <select id="blank_die?" name="blank_die?" style="width: 30%;">
                        <option value="">Select YES/NO</option>
                        <option value="YES">YES</option>
                        <option value="NO">NO</option>
                    </select>
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="trap" style="width: 30%;">Trap:</label>
                    <input type="text" id="trap" name="trap" style="width: 30%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="parts_per_blank" style="width: 30%;">Parts per Blank:</label>
                    <input type="text" id="parts_per_blank" name="parts_per_blank" style="width: 30%;">
                </div>
            </div>
        </div>
        <div style="text-align: right; margin-top: 20px;">
            <button type="button" class="btn btn-primary next-tab">Next</button>
        </div>
    </form>
</div>
<div class="tab-pane fade" id="Tab4">
    <br>
    <form id="operations_tab">
    <input type="hidden" id="invoice_number" name="invoice_number" readonly>
        <div style="display: flex; justify-content: space-between;">
            <div style="flex: 1; margin-right: 10px;">
                <div style="display: flex; align-items: center;">
                    <label for="line_produced" style="width: 30%;">Line Produced On:</label>
                    <select id="line_produced" name="line_produced" style="width: 50%;">
                        <option value="">Select a line</option>
                        <?php foreach($lines as $line): ?>
                            <option value="<?= $line['line_id'] ?>"><?= $line['Line_Location'] . ' - ' . $line['Line_Name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="Uptime" style="width: 30%;">Uptime %:</label>
                    <input type="text" id="uptime" name="uptime" style="width: 30%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="pph" style="width: 30%;">PPH:</label>
                    <input type="text" id="pph" name="PPH" style="width: 30%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="wash_and_lube" style="width: 30%;">Wash and Lube:</label>
                    <input type="checkbox" id="wash_and_lube" name="wash_and_lube" style="width: 30%; transform: scale(1.5);">
                </div>
            </div>
            <div style="flex: 1; margin-left: 10px;">
                <div style="display: flex; align-items: center;">
                    <label for="material_markup_percent" style="width: 30%;">Material Markup % :</label>
                    <input type="number" id="material_markup_percent" name="material_markup_percent" style="width: 30%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="cost_per_lb" style="width: 30%;">Material Cost / lb:</label>
                    <input type="number" id="cost_per_lb" name="cost_per_lb" style="width: 30%;">
                </div>
                <!-- Add more fields here -->
            </div>
        </div>
        <div style="text-align: right; margin-top: 20px;">
            <button type="button" class="btn btn-primary next-tab">Next</button>
        </div>
    </form>
</div>




<div class="tab-pane fade" id="Tab5">
<div style="display: flex; align-items: center;">
    <button type="button" id="calculate_shipping">Calculate Freight</button>
</div>
<br>
<div id="shippingModal" style="display: none;">
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
        <label for="rate_per_mile" style="width: 7%; font-weight: bold; margin-right: 10px;">Rate Per Mile:</label>
        <input type="number" id="rate_per_mile" name="rate_per_mile" style="width: 7%; padding: 5px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; color: #333; background-color: #f9f9f9; text-align: center;">
    </div>
    <div id="stops">
        <div class="stop">
            <input type="text" class="address" placeholder="Starting Address">
        </div>
    </div>
    <button type="button" id="add_stop">Add Another Stop</button>
    <button type="button" id="calculate_distance">Calculate Freight Cost</button>
</div>
    <form id="shipping_tab">
        <div style="display: flex; justify-content: space-between;">
            <div style="flex: 1; margin-right: 10px; padding: 10px;">
                
                <div style="display: flex; align-items: center;">
                    <label for="freight" style="width: 30%;">Freight Cost:</label>
                    <input type="number" id="freight" name="freight" style="width: 30%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="palletType" style="width: 30%;">Pallet Type:</label>
                    <select id="palletType" name="palletType" style="width: 30%;" onchange="updatePalletCost()">
                    <option value="">Select a type</option>
                    <option value="Wood">Wood</option>
                    <option value="Metal">Metal</option>
                    </select>
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                 <label for="palletSize" style="width: 30%;">Pallet Size:</label>
                    <select id="palletSize" name="palletSize" style="width: 30%;" onchange="updatePalletCost()">
                        <option value="">Select a size</option>
                        <option value="Small">Small</option>
                        <option value="Medium">Medium</option>
                        <option value="Large">Large</option>
                    </select>
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="palletWeight" style="width: 30%;">Pallet Weight(Lbs):</label>
                    <input type="number" id="palletWeight" name="palletWeight" style="width: 30%;">
                </div>
            </div>
            <div style="flex: 1; margin-left: 10px; padding: 10px;">
               
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="palletCost" style="width: 30%;">Pallet Cost:</label>
                    <!-- Assuming you have an input field for palletCost -->
                    <input type="number" id="palletCost" name="palletCost" style="width: 30%;" readonly>
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="stacksPerSkid" style="width: 30%;">Stacks per Skid:</label>
                    <input type="number" id="stacksPerSkid" name="stacksPerSkid" step="1.00" max="5" style="width: 30%;">
                </div>
                <br>
                <div style="display: flex; align-items: center;">
                    <label for="pallet_uses" style="width: 30%;"># Pallet Returns:</label>
                    <input type="number" id="pallet_uses" name="pallet_uses" step="1.00" min="0"style="width: 30%;">
                </div>
            </div>
        </div>
        <button id="add-part" type="button">Add Part to Quote</button>
    </form>
</div>
 
    
        
<div class="tab-pane fade" id="Tab6">
<button id="add-part-btn" class="btn btn-primary">Add Another Part</button>
<div class="parts-table">

<table id="parts_table">
<!-- Table headers go here -->
</table>
</div>

   <style>
    .form-container {
        width: 100%; /* Ensures that the form container takes up the full width of its parent */
        text-align: center; /* Aligns the text to the left */
        border: 2px solid black;
        margin-bottom: 30px;
    border-radius: 10px;
    }
    
    </style>
<div class="form-container">
    <button id="add-contingencies" type="button">Add Contingencies</button>
    <input type="hidden" id="contingencies" name="contingencies">
    <label for="invoice_files">Upload Files:</label>
    <input type="file" id="invoice_files" name="invoice_files[]" multiple>
</div>
<div>

<select id="pdf_format" name="pdf_format" style="display:none;">
    <option value="">Select a format</option>
    <option value="ford">Ford</option>
    <option value="thai_summit">Thai Summit</option>
</select>
    <button id="submit-button" type="button">Submit</button>
</form>
</div>
</div>



<script>

$(document).ready(function(){
    // Show an alert if the submit button is clicked without a customer selected
    $('#submit-button').click(function(e){
        if($('#customer').val() == '') {
            e.preventDefault();
            alert('Please select a customer before submitting.');
        }
    });
});
$("#add-part").click(function(e){
    var partNumber = $("#part").val();
    var wash_and_lube = document.getElementById('wash_and_lube').checked;

    // List of required field IDs
    var requiredFields = ['customer','supplier_name','partNumber','mill','model_year','surface','partName','platform','type','materialType','scrapConsumption','volume','Density','width','pitch','gauge','parts_per_blank','line_produced','uptime','pph','material_markup_percent','cost_per_lb','freight','palletWeight','stacksPerSkid','pallet_uses'];
    var emptyFields = [];

    // Check each required field
    requiredFields.forEach(function(fieldId) {
        var field = document.getElementById(fieldId);
        if (!field || !field.value) {
            // If the field is empty or doesn't exist, add it to the list of empty fields
            emptyFields.push(fieldId);
        }
    });

    if (emptyFields.length > 0) {
        // If there are any empty fields, prevent the form from being submitted
        e.preventDefault();

        // Create a message with the names of the empty fields
        var message = 'Cannot submit an incomplete form. Please fill out the following fields: ' + emptyFields.join(', ');

        // Display the message using SweetAlert2
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message
        });
    } else {
        // If all fields are filled, proceed with the form submission
        if (partNumber != "") {
            // Capture form data before it's cleared
            const formData = {
                pdf_format:document.getElementById('pdf_format').value,
                partNumber: document.getElementById('partNumber').value,
                supplier_name: document.getElementById('supplier_name').value,
                partName: document.getElementById('partName').value,
                mill: document.getElementById('mill').value,
                customer_id: document.getElementById('customer_id').value,
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
                die_reviewer_input: document.getElementById('die_reviewer_input').value,
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
                            $('#tab6-tab').tab('show');
                        });
                    }
                });
            });
            // Enable the tab
            $('#tab6-tab').removeClass('disabled');
        } else {
            clearPartInputs();
            // Clear all the input fields
        }
    }
});




$(document).ready(function(){
    $('#scrapConsumption').on('input', function() {
        if ($(this).val() > 6) {
            $(this).val(6);
        }
    });
});
$(document).ready(function(){
    $('#stacksPerSkid').on('input', function() {
        if ($(this).val() > 5) {
            $(this).val(5);
        }
    });
});
$(document).ready(function() {
    $('#customer_supplied_material').change(function() {
        if ($(this).is(':checked')) {
            // Checkbox is checked
            $('#supplier_name').val($('#customer').val());
            $('#supplier_name_div').hide();
        } else {
            // Checkbox is unchecked
            $('#supplier_name').val('');
            $('#supplier_name_div').show();
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
document.querySelector('#add-customer-btn').onclick = function() {
    event.preventDefault();
    Swal.fire({
    title: 'Add New Customer',
    width:'700px',
    html: `
        <style>
            .swal2-input {
                width: 85% !important;
            }
        </style>
        <input id="swal-input1" class="swal2-input" placeholder="Customer Name">
        <input id="swal-input2" class="swal2-input" placeholder="Customer Address">
        <input id="swal-input3" class="swal2-input" placeholder="Customer City">
        <input id="swal-input4" class="swal2-input" placeholder="Customer State">
        <input id="swal-input5" class="swal2-input" placeholder="Customer Zip">
        <input id="swal-input6" class="swal2-input" placeholder="Customer Phone">
        <input id="swal-input7" class="swal2-input" placeholder="Customer Email">
        <input id="swal-input8" class="swal2-input" placeholder="Customer Contact">
    `,
        preConfirm: () => {
    const values = [
        document.getElementById('swal-input1').value,
        document.getElementById('swal-input2').value,
        document.getElementById('swal-input3').value,
        document.getElementById('swal-input4').value,
        document.getElementById('swal-input5').value,
        document.getElementById('swal-input6').value,
        document.getElementById('swal-input7').value,
        document.getElementById('swal-input8').value
    ];
    // Check if any of the fields are empty
    if (values.some(value => value === '')) {
        Swal.showValidationMessage('Please fill out all fields');
        return;
    }
    return values;
}
    }).then((result) => {
        if (result.isConfirmed) {
            var formData = new FormData();
            formData.append('customerName', result.value[0]);
            formData.append('customerAddress', result.value[1]);
            formData.append('customerCity', result.value[2]);
            formData.append('customerState', result.value[3]);
            formData.append('customerZip', result.value[4]);
            formData.append('customerPhone', result.value[5]);
            formData.append('customerEmail', result.value[6]);
            formData.append('customerContact', result.value[7]);

            fetch('../add_new_customer/submit_new_customer.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(response => {
                if (response.status === 'error') {
                    throw new Error(response.message);
                }
                Swal.fire('Success', 'New customer added successfully', 'success')
                    .then(() => {
                        location.reload(); // Refresh the page
                    });
                })
                .catch(error => {
                    Swal.fire('Error', `Request failed: ${error}`, 'error');
               
            });
        }
    });
}

$(document).ready(function() {
    $("#add-part-btn").click(function() {
        $('.nav-tabs a[href="#Tab2"]').tab('show');
    });
});
function updatePalletCost() {
    var palletSize = document.getElementById('palletSize').value;
    var palletType = document.getElementById('palletType').value;
    var palletCost;

    if (palletType === 'Metal') {
        palletCost = 0;
    } else {
        switch(palletSize) {
            case 'Small':
                palletCost = 80;
                break;
            case 'Medium':
                palletCost = 125;
                break;
            case 'Large':
                palletCost = 250;
                break;
            default:
                palletCost = 0;
        }
    }

    document.getElementById('palletCost').value = palletCost;
}
function toggleDieReviewer() {
    var checkbox = document.getElementById('customer_provided_die');
    var dieReviewer = document.getElementById('die_reviewer');

    if (checkbox.checked) {
        dieReviewer.style.display = 'flex';
    } else {
        dieReviewer.style.display = 'none';
    }
}



function openExcelItemsPopup() {
    Swal.fire({
        title: 'Select Excel Items',
        html: generateExcelItemsFormHTML(), // Generate the HTML for the form
        focusConfirm: false,
        preConfirm: () => {
            // Get selected item names
            var selectedItemNames = Array.from(document.querySelectorAll('input[name="excel-item"]:checked')).map(function(checkbox) {
                return checkbox.value;
            });
            return selectedItemNames;
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var invoiceId = document.getElementById('invoice_number').value; 
            // Send selected item names to the server
            $.ajax({
                url: 'generate_excel.php',
                method: 'POST',
                data: { itemNames: result.value,
                    invoice_id: invoiceId
                 },
                 success: function(response) {
                    // Parse the JSON response
                     var data = JSON.parse(response);

                     // Check if there was an error
                    if (data.error) {
                    // If there was an error, show an error message
                        Swal.fire('Error', data.message, 'error');
                    }   
                    else 
                    {
                    // If there was no error, show the download prompt
                    Swal.fire({
                    title: 'Download File',
                    text: "Would you like to download the file?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, download it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../quote_approvals/download.php?quoteId=" + encodeURIComponent(data.invoice_id) + "&file_name=" + encodeURIComponent(data.filename);
                setTimeout(function(){ // Delay for file download initiation
            location.reload(); // Refresh the page
        }, 2000); // Delay for 5 seconds
               
            }
        });
    }
}
            });
        }
    });
}
var presets = {
    'Ford': ['supplier_name','Part#','Part Name','Material Type','Mill','Platform','Surface','Volume','Gauge(mm)','Width(mm)','Pitch(mm)','Blank Weight(kg)','Pallet Type','cost_per_kg','total_steel_cost(kg)','Blanking per piece cost','freight per piece cost','Packaging Per Piece Cost','material_cost_markup','Total Cost per Piece'],
    'Rivian': ['Material Type', 'Volume', 'Width(mm)'],
    'Thai Summit':['Part#', 'Part Name','blank_die?','Type','Gauge(mm)','nom?','Width(mm)','Pitch(mm)','trap','Gauge(in)','Pitch(in)','Blank Weight(lb)','parts_per_blank','blanks_per_mt','Surface','Scrap Consumption','Blanking per piece cost','freight per piece cost','Total Cost per Piece']
    // ... add more presets here ...
};
function applyPreset() {
    var preset = document.getElementById("preset").value;
    if (preset) {
        // Uncheck all checkboxes inside the form
        document.querySelectorAll('#excel-items-form input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.checked = false;
        });

        // Check the checkboxes for the selected preset
        presets[preset].forEach(function(item) {
            document.getElementById(item).checked = true;
        });
    }
}

function generateExcelItemsFormHTML() {
    var items = [
    'supplier_name',    
    'Part#',
    'Part Name',
    'model_year',
    'Material Type',
    'Mill',
    'Platform',
    'Volume',
    'Width(mm)',
    'width(in)',
    'Pitch(mm)',
    'Pitch(in)',
    'Gauge(mm)',
    'Gauge(in)',
    'Density',
    'nom?',
    'trap',
    'Type',
    'blank_die?',
    'Blank Weight(kg)',
    'Blank Weight(lb)',
    'Scrap Consumption',
    'Pcs Weight(kg)',
    'Pcs Weight(lb)',
    'Scrap Weight(kg)',
    'Scrap Weight(lb)',
    'parts_per_blank',
    'blanks_per_mt',
    'blanks_per_ton',
    'Surface',
    'Pallet Type',
    'Pallet Size',
    'Pcs per Lift',
    'Stacks per Skid',
    'Pcs per Skid',
    'Lift Weight+Skid Weight(lb)',
    'Skids per Truck',
    'Pieces per Truck',
    'Truck Weight(lb)',
    'Annual Truckloads',
    'UseSkidPcs',
    'Skid cost per piece',
    'Line Produced on',
    'PPH',
    'Uptime',
    'cost_per_lb',
    'cost_per_kg',
    'total_steel_cost(kg)',
    'total_steel_cost(lb)',
    'Blanking per piece cost',
    'Packaging Per Piece Cost',
    'freight per piece cost',
    'material_cost',
    'material_markup_percent',
    'material_cost_markup',
    'palletCost',
    'Total Cost per Piece'
];

    var html = '<form id="excel-items-form" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; max-height: 400px; overflow-y: auto; padding: 10px;">';

    // Add dropdown for presets
    html += '<div style="grid-column: span 2; text-align: center;">';
    html += '<select id="preset" onchange="applyPreset()" style="width: 70%;">';
    html += '<option value="">Select a preset</option>';
    for (var preset in presets) {
        html += '<option value="' + preset + '">' + preset + '</option>';
    }
    html += '</select>';
    html += '</div>';

    items.forEach(function(item) {
        html += '<div style="padding: 5px; border: 1px solid #ccc; border-radius: 5px; margin: 5px;">';
        html += '<input type="checkbox" id="' + item + '" name="excel-item" value="' + item + '">';
        html += '<label for="' + item + '" style="margin-left: 5px;">' + item + '</label>';
        html += '</div>';
    });

    html += '</form>';

    return html;
}

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
    var backgroundColor = index % 2 === 0 ? '#ffffff' : '#f0f0f0'; // Use a lighter grey for every other contingency
    html += '<div style="display: flex; align-items: center; background-color: ' + backgroundColor + ';">';
    html += '<input type="checkbox" id="' + contingency + '" name="contingency" value="' + contingency + '">';
    html += '<label for="' + contingency + '" style="margin-left: 5px;">' + contingency + '</label>';
    html += '</div>';
});
html += '</form>';

    Swal.fire({
        title: 'Select Contingencies',
        html: html,
        preConfirm: function() {
            var selectedContingencies = [];
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
document.getElementById('calculate_shipping').addEventListener('click', function() {
    document.getElementById('shippingModal').style.display = 'block';
    initAutocomplete();
});

function addStop() {
    var stop = document.createElement('div');
    stop.className = 'stop';
    stop.innerHTML = '<input type="text" class="address" placeholder="Enter Stop Address">';
    
    var deleteButton = document.createElement('button');
    deleteButton.innerHTML = '🗑️';
    deleteButton.className = 'delete-stop';
    deleteButton.addEventListener('click', function() {
        stop.remove();
    });

    stop.appendChild(deleteButton);
    document.getElementById('stops').appendChild(stop);
    initAutocomplete();
}

document.getElementById('calculate_distance').addEventListener('click', function() {
    var stops = Array.from(document.getElementsByClassName('stop'));
    var addresses = stops.map(function(stop) {
        return stop.getElementsByClassName('address')[0].value;
    });

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'calculate_distance.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('addresses=' + encodeURIComponent(JSON.stringify(addresses)));

    xhr.onload = function() {
        if (this.status == 200) {
            var distance = parseFloat(this.responseText);
            var ratePerMile = parseFloat(document.getElementById('rate_per_mile').value); // Get the rate per mile
          var freight_cost = (distance * ratePerMile).toFixed(3);

            // Update the freight cost input field
            document.getElementById('freight').value = freight_cost;

            // Close the modal
            document.getElementById('shippingModal').style.display = 'none';
        }
    };
});

function initAutocomplete() {
    // Initialize the autocomplete feature for each address input field
    Array.from(document.getElementsByClassName('address')).forEach(function(input) {
        new google.maps.places.Autocomplete(input);
    });
}
document.getElementById('add_stop').addEventListener('click', addStop);
// Save data to localStorage
$('form').on('change', function() {
    var form_id = $(this).attr('id');
    var form_data = $(this).serialize();
    localStorage.setItem('form_data_' + form_id, form_data);
});

</script>

</body>
</html>