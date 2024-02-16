<?php
session_start();
include '../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');
// Prepare a parameterized statement


// Check if the user is logged in 
if(!isset($_SESSION['user']) ){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../../index.php");
    exit();
}
$months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

$glCode = $_GET['gl_code']; // Or use a more secure method to get the gl_code

$query = "SELECT expense_name FROM expense_types WHERE gl_code = ?";
$stmt = $database->prepare($query);
$stmt->bind_param("i", $glCode);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $expenseName = $row['expense_name'];
    // You can now use $expenseName as needed, for example, displaying it or storing it in an input field
} else {
    // Handle the case where no matching gl_code is found
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLEYS7vn5FTgmGoHl7-5kdWcCE62CMhc8&libraries=places"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Expense Report</title>
    <style>
  
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

        button {
    margin-bottom: 20px;
    background-color: #007BFF; /* Change to a more professional color */
    color: white;
    border: none;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    transition: all 0.3s ease; /* Smooth transition for all changes */
    cursor: pointer;
    font-family: 'Roboto', sans-serif;
    border-radius: 25px; /* More rounded corners */
    box-shadow: 0 9px 20px rgba(0, 0, 0, 0.25); /* More pronounced shadow */
    outline: none; /* Remove outline */
}

button:hover {
    background-color: #0056b3; /* Darken the color on hover */
    box-shadow: 0 9px 20px rgba(0, 0, 0, 0.5); /* Darken the shadow on hover */
    transform: translateY(-2px); /* Slightly lift the button on hover */
}

button:active {
    transform: translateY(1px); /* Slightly press the button on click */
    box-shadow: 0 9px 20px rgba(0, 0, 0, 0.15); /* Lessen the shadow on click */
}
#distanceModal {
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    display: none;
    z-index: 1000;
}
.modal-content {
    margin-bottom: 20px;
}
    </style>
    
</head>
<body style="background-image: url('<?php echo $backgroundImage; ?>'); background-size: cover;">
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</div>

    <h2 style="display: flex; justify-content: center; align-items: flex-start;"> 
    <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="15%">
   
    </h2>
    
    <div class="container mx-auto mt-10">
        <!-- Overlay -->
<div id="distanceModalOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40" style="display:none;"></div>

<!-- Distance Calculation Modal -->
<div id="distanceModal" class="fixed left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-8 rounded-lg shadow-lg z-50" style="display:none; width: 90%; max-width: 500px;">
    <div class="modal-content">
        <h2 class="text-xl font-bold mb-4">Calculate Distance</h2>
        <div id="addressInputs">
    <div class="address-input-container">
        <input type="text" placeholder="Start Address" class="address-input block w-full px-4 py-2 border rounded">
    </div>
    <!-- New stop inputs will be inserted here -->
    <div class="address-input-container">
        <input type="text" placeholder="End Address" class="address-input block w-full px-4 py-2 border rounded">
    </div>
</div>
        <div class="mt-4 flex justify-end space-x-2">
            <button id="addStopBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add Stop</button>
            <button id="calculateBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Calculate</button>
        </div>
    </div>
</div>
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-6xl mx-auto">
            <h2 class="text-2xl font-bold mb-5 text-center">Expense Report</h2>
            
            <form id="expenseReportForm" enctype="multipart/form-data" class="space-y-4">
                <!-- Distance Calculation Modal -->

            <input type="hidden" name="expense_type" value="<?php echo htmlspecialchars($glCode); ?>">
                <input type="hidden" name="employee_name" value="<?php echo htmlspecialchars($_SESSION['user']); ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="month_of_expense" class="block text-gray-700 text-sm font-bold mb-2">Month of Expense:</label>
                        <select id="month_of_expense" name="month_of_expense" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <?php foreach ($months as $month): ?>
                            <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="date_of_visit" class="block text-gray-700 text-sm font-bold mb-2">Date of Visit:</label>
                        <input type="date" id="date_of_visit" name="date_of_visit" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <!-- Dynamic table for multiple expenses -->
        <table id="expensesTable" class="mt-4">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Customer Location</th>
                    <th>Mileage</th>
                    <th>Mileage Expense</th>
                    <th>Meals Expense</th>
                    <th>Entertainment Expense</th>
                </tr>
            </thead>
            <tbody>
            <tr class="expenseRow">
    <td><input type="text" name="customer_name[]" class="shadow border rounded"></td>
    <td><input type="text" name="customer_location[]" class="shadow border rounded customer_location"></td> <!-- Added class customer_location -->
    <td><input type="number" step="0.01" name="mileage[]" class="shadow border rounded mileage"></td> <!-- Added class mileage -->
    <td><input type="number" step="0.01" name="mileage_expense[]" class="shadow border rounded mileage_expense"></td> <!-- Ensure this class exists for the JS function -->
    <td><input type="number" step="0.01" name="meals_expense[]" class="shadow border rounded"></td>
    <td><input type="number" step="0.01" name="entertainment_expense[]" class="shadow border rounded"></td>
</tr>
            </tbody>
        </table>
                    <div class="col-span-2">
                        <label for="fileUpload" class="block text-gray-700 text-sm font-bold mb-2">Upload Expense Related Files:</label>
                        <input type="file" id="fileUpload" name="fileUpload[]" multiple class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                <button type="button" id="addAnotherExpense" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Add Another</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
   

    
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
echo "Welcome, " . htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') . "             " . date("m/d/Y");
?>
</div>
<script>


function initializeAutocomplete(input) {
    if (input) {
        return new google.maps.places.Autocomplete(input, {types: ['geocode', 'establishment']});
    }
}

function calculateMileageExpense(input, index) {
    if (input) {
        input.addEventListener('input', function() {
            var mileage = parseFloat(input.value) || 0;
            var rate = 0.67; // Define your rate per mile
            var expense = mileage * rate;
            var expenseInputs = document.querySelectorAll('.mileage_expense');
            if (expenseInputs[index]) {
                expenseInputs[index].value = expense.toFixed(2);
            }
        });
    }
}

   
$(document).ready(function() {
   // Initialize for existing inputs on document ready
   $('.customer_location').each(function(index, input) {
        initializeAutocomplete(input);
    });
    $('.mileage').each(function(index, input) {
        calculateMileageExpense(input, index);
    });

    $('#addAnotherExpense').click(function() {
    var newRow = $('.expenseRow:first').clone();
    newRow.find('input').val('');
    $('#expensesTable tbody').append(newRow);

    // Re-initialize for the new row inputs
    var newCustomerLocationInput = newRow.find('.customer_location')[0];
    var newMileageInput = newRow.find('.mileage')[0];
    initializeAutocomplete(newCustomerLocationInput);
    calculateMileageExpense(newMileageInput, $('.mileage').length - 1);
});

    $('#expenseReportForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        document.querySelectorAll('.expenseItem').forEach(function(item, index) {
        formData.append('customer_name[]', item.querySelector('.customer_name').value);
        formData.append('customer_location[]', item.querySelector('.customer_location').value);
        formData.append('mileage[]', item.querySelector('.mileage').value);
        formData.append('mileage_expense[]', item.querySelector('.mileage_expense').value);
        formData.append('meals_expense[]', item.querySelector('.meals_expense').value);
        formData.append('entertainment_expense[]', item.querySelector('.entertainment_expense').value);
    });

    $.ajax({
            url: 'submit_expense_report.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Use SweetAlert for success message and refresh page on close
                Swal.fire({
                    title: 'Success!',
                    text: 'Expense report submitted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        window.location.reload(); // Reload the page
                    }
                });
            },
            error: function(xhr, status, error) {
                // Use SweetAlert for error message and refresh page on close
                Swal.fire({
                    title: 'Error!',
                    text: 'Submission error: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        window.location.reload(); // Reload the page
                    }
                });
            }
        });
    });
});
    </script>
</body>

</html>
