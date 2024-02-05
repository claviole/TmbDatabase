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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
    </style>
    
</head>
<body style="background-image: url('../../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</div>
    <h2 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../../images/home_page_company_header.png" alt="company header" width="30%" height="15%" > 
     
    </h2>
    <div class="container mx-auto mt-10">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold mb-5 text-center">Expense Report</h2>
            <form id="expenseReportForm" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="expense_type" value="Expense Report">
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

                    <div>
                        <label for="customer_name" class="block text-gray-700 text-sm font-bold mb-2">Customer Name:</label>
                        <input type="text" id="customer_name" name="customer_name" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div>
                        <label for="customer_location" class="block text-gray-700 text-sm font-bold mb-2">Customer Location:</label>
                        <input type="text" id="customer_location" name="customer_location" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div>
                        <label for="mileage" class="block text-gray-700 text-sm font-bold mb-2">Mileage:</label>
                        <input type="number" id="mileage" name="mileage" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div>
                        <label for="mileage_expense" class="block text-gray-700 text-sm font-bold mb-2">Mileage Expense:</label>
                        <input type="text" id="mileage_expense" name="mileage_expense" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
                    </div>

                    <div>
                        <label for="meals_expense" class="block text-gray-700 text-sm font-bold mb-2">Meals Expense:</label>
                        <input type="number" id="meals_expense" name="meals_expense" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div>
                        <label for="entertainment_expense" class="block text-gray-700 text-sm font-bold mb-2">Entertainment Expense:</label>
                        <input type="number" id="entertainment_expense" name="entertainment_expense" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="col-span-2">
                        <label for="fileUpload" class="block text-gray-700 text-sm font-bold mb-2">Upload Expense Related Files:</label>
                        <input type="file" id="fileUpload" name="fileUpload[]" multiple class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
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
$(document).ready(function() {
    $('#mileage').on('input', function() {
        var mileage = $(this).val();
        var mileageExpense = mileage * 0.67; // Assuming $0.67 per mile
        $('#mileage_expense').val(mileageExpense.toFixed(2));
    });

    $('#expenseReportForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'submit_expense_report.php',
            type: 'POST',
            data: formData,
            processData: false, // Important: Don't process the files
            contentType: false, // Important: Set content type to false
            success: function(response) {
                // Assuming the response is already a JSON object
                // If the response is a JSON string, parse it first
                // var response = JSON.parse(data); // Use this line if response is not automatically parsed

                if(response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../index.php'; // Redirect
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "An error occurred: " + xhr.status + " " + error,
                });
            }
        });
    });
});
    </script>
</body>

</html>
```