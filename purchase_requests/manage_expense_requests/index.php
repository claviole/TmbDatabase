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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <title>Purchase Requests</title>
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
   /* DataTables Container */
   .dataTables_wrapper {
        color: black; /* Sets default text color to black for readability */
    }

    /* Header row */
    table.dataTable thead th {
        background-color: #1B145D; /* Dark background for the header */
        color: white; /* White text color for header */
    }

    /* Table body */
    table.dataTable tbody td {
        color: black; /* Ensures text color in the table body is black for readability */
    }

    /* DataTables - control elements */
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        color: white; /* Sets text color to white for DataTables control elements */
    }

    /* Search and length input fields */
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
        color: black; /* Black text color for input fields for readability */
        background-color: white; /* White background for contrast */
        border: 1px solid #ccc; /* Defines border for clarity */
        border-radius: 4px; /* Rounded corners for aesthetics */
        padding: 5px; /* Padding for better interaction area */
    }

    /* Pagination buttons */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: white !important; /* White text color for readability */
        background-color: #007BFF; /* Blue background for visual appeal */
        border: 1px solid #aaa; /* Defines border for clarity */
        border-radius: 4px; /* Rounded corners for aesthetics */
        cursor: pointer; /* Cursor pointer for better UX */
        padding: 5px 10px; /* Padding for better interaction area */
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #0056b3 !important; /* Darker blue on hover for visual feedback */
    }

    /* Active pagination button */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        color: white !important; /* White text color for readability */
        background-color: #28a745 !important; /* Green background for distinction */
    }
    .modal-dialog {
    max-width: 80%; /* Adjust this value to control the width of the modal */
}
.modal-content {
    background-color: #f8f9fa; /* Light grey background */
    border: 1px solid #dee2e6; /* Grey border */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 5px 15px rgba(0,0,0,0.3); /* Shadow for depth */
}

.modal-header {
    background-color: #ffebee; /* Light red background */
    color: #495057; /* Dark grey text */
    border-bottom: 1px solid #dee2e6; /* Grey border */
}

.modal-body {
    background-color: #ffffff; /* White background for the body */
    color: #495057; /* Dark grey text */
}

.modal-footer {
    background-color: #f5f5f5; /* Light grey background */
    border-top: 1px solid #dee2e6; /* Grey border */
}

.modal-title {
    font-weight: bold;
}

#approveButton, #denyButton {
    border-radius: 5px;
}

#approveButton {
    background-color: #c62828; /* Darker red for approve button */
    border-color: #b71c1c;
}

#denyButton {
    background-color: #6c757d; /* Grey for deny button */
    border-color: #5a6268;
}
.form-control {
    background-color: #ffffff; /* White background */
    border: 1px solid #ccc; /* Light grey border */
    color: #495057; /* Dark grey text */
}
    </style>
    
</head>
<body style="background-image: url('../../images/steel_coils.jpg'); background-size: cover;">
<!-- Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expenseModalLabel">Expense Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalBody">
        <!-- Dynamic content will be loaded here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="approveButton">Approve</button>
        <button type="button" class="btn btn-danger" id="denyButton">Deny</button>
      </div>
    </div>
  </div>
</div>
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
     
    </h1>
    <div style="padding: 20px;">
    <table id="purchaseRequestsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Expense ID</th>
                <th>Expense Type</th>
                <th>Customer Name</th>
                <th>Customer Location</th>
                <th>Employee Name</th>
                <th>Approval Status</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here using PHP -->
            <?php
            $query = "SELECT expense_id, expense_type, customer_name, customer_location, employee_name, approval_status FROM purchase_requests";
            $result = mysqli_query($database, $query);
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['expense_id']}</td>
                        <td>{$row['expense_type']}</td>
                        <td>{$row['customer_name']}</td>
                        <td>{$row['customer_location']}</td>
                        <td>{$row['employee_name']}</td>
                        <td>{$row['approval_status']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
    
<!-- Generic Modal Structure -->


    
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
echo "Welcome, " . htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') . "             " . date("m/d/Y") . "<br>";
?>
    <i class="fas fa-cog" id="settings-icon" style="cursor: pointer;"></i>
    
  

</div>



</div>
</body>

<script>
   $(document).ready( function () {
    $('#purchaseRequestsTable').DataTable({
        // DataTables options here
        "scrollX": true // Enables horizontal scrolling
    });
});
// Example using jQuery
$(document).ready(function() {
    $('#purchaseRequestsTable tbody').on('click', 'tr', function() {
        var expenseId = $(this).find('td:first').text(); // Assuming the first column contains the expense ID
        fetchExpenseDetails(expenseId); // Function to fetch expense details and determine modal content
    });
});
function fetchExpenseDetails(expenseId) {
    $.ajax({
        url: 'fetch_expense_details.php', // Server-side script to fetch expense details
        method: 'POST',
        data: { id: expenseId },
        dataType: 'json', // Expect a JSON response
        success: function(response) {
            // Ensure the response contains the details
            if(response.details) {
                var expenseType = response.details.expense_type; // Correctly access expense_type
                populateModal(expenseType, response.details); // Populate modal based on expense type
                $('#expenseModal').modal('show'); // Show the modal
            } else {
                console.error('Expense details not found in the response');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching expense details:', error);
        }
    });
}
function populateModal(expenseType, details) {
    var modalBody = $('#modalBody');
    modalBody.empty(); // Clear previous content

    if(expenseType === 'Expense Report') {
        var formHtml = `
            <form>
                <div class="form-group">
                    <label for="expenseType">Expense Type:</label>
                    <input type="text" class="form-control" id="expenseType" value="${expenseType}" readonly>
                </div>
                <div class="form-group">
                    <label for="employeeName">Employee Name:</label>
                    <input type="text" class="form-control" id="employeeName" value="${details.employee_name}" readonly>
                </div>
                <div class="form-group">
                    <label for="monthOfExpense">Month of Expense:</label>
                    <input type="text" class="form-control" id="monthOfExpense" value="${details.month_of_expense}" readonly>
                </div>
                <div class="form-group">
                    <label for="dateOfVisit">Date of Visit:</label>
                    <input type="text" class="form-control" id="dateOfVisit" value="${details.date_of_visit}" readonly>
                </div>
                <div class="form-group">
                    <label for="customerName">Customer Name:</label>
                    <input type="text" class="form-control" id="customerName" value="${details.customer_name}" readonly>
                </div>
                <div class="form-group">
                    <label for="customerLocation">Customer Location:</label>
                    <input type="text" class="form-control" id="customerLocation" value="${details.customer_location}" readonly>
                </div>
                <div class="form-group">
                    <label for="mileage">Mileage:</label>
                    <input type="text" class="form-control" id="mileage" value="${details.mileage}" readonly>
                </div>
                <div class="form-group">
                    <label for="mileageExpense">Mileage Expense:</label>
                    <input type="text" class="form-control" id="mileageExpense" value="${details.mileage_expense}" readonly>
                </div>
                <div class="form-group">
                    <label for="mealsExpense">Meals Expense:</label>
                    <input type="text" class="form-control" id="mealsExpense" value="${details.meals_expense}" readonly>
                </div>
                <div class="form-group">
                    <label for="entertainmentExpense">Entertainment Expense:</label>
                    <input type="text" class="form-control" id="entertainmentExpense" value="${details.entertainment_expense}" readonly>
                </div>
            </form>
        `;
        modalBody.append(formHtml);

        // Handle files separately
        if(details.files && details.files.length > 0) {
            var fileListHtml = '<div class="form-group"><label>Files:</label><ul class="list-unstyled">';
            details.files.forEach(function(file) {
                fileListHtml += `<li><a href="${file.file_path}" target="_blank">${file.file_name}</a></li>`;
            });
            fileListHtml += '</ul></div>';
            modalBody.append(fileListHtml);
        }
    }

    // Dynamically add Approve and Deny buttons if needed
}

</script>

</html>