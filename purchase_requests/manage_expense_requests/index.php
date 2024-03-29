<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection

include '../../configurations/handle_request.php'; // Assuming you have a handle_request.php file for handling approval/denial requests

date_default_timezone_set('America/Chicago');
// Prepare a parameterized statement


// Check if the user is logged in 
if(!isset($_SESSION['user']) ){
    // Not logged in or not an admin, redirect to login page
    header("Location: /../index.php?redirect=" . urlencode('https://targetmetalsync.com' . $_SERVER['REQUEST_URI']));
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
        cursor: pointer;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.next, 
.dataTables_wrapper .dataTables_paginate .paginate_button.previous{
    background-color: #1B145D !important; /* Dark background for the header */
        color: white !important; /* White text color for header */
}

.paginate_button.next:hover, .paginate_button.previous:hover {
    background-color: #111 !important; /* Darker blue on hover for visual feedback */
    cursor: pointer !important;
}

/* Labels */
.form-group > label {
    display: inline-block;
    margin-bottom: 0.5rem;
}

/* File List Styling */
.list-unstyled {
    list-style-type: none;
    padding-left: 0;
}

.list-unstyled li a {
    color: #007bff;
    text-decoration: none;
}

.list-unstyled li a:hover {
    text-decoration: underline;
}

.modal-md {
    max-width: 60%; /* Adjust this value based on your preference */
}

.modal-content {
    background-color: #fff; /* Keeps modal background light */
    color: #333; /* Dark text for better readability */
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Adjusting form and button colors */
.form-control, .form-control[readonly] {
    background-color: #f2f2f2; /* Light grey background */
    color: #333; /* Dark text for better readability */
}

.btn {
        display: inline-block !important;
        font-weight: 400 !important;
        color: #212529 !important;
        text-align: center !important;
        vertical-align: middle !important;
        user-select: none !important;
        background-color: transparent !important;
        border: 1px solid transparent !important;
        padding: 0.375rem 0.75rem !important;
        font-size: 1rem !important;
        line-height: 1.5 !important;
        border-radius: 0.25rem !important;
        transition: color 0.15s ease-in-out !important, background-color 0.15s ease-in-out !important, border-color 0.15s ease-in-out !important, box-shadow 0.15s ease-in-out !important;
    }

    .btn-primary {
        color: #fff !important;
        background-color: #007bff !important;
        border-color: #007bff !important;
    }

    .btn-danger {
        color: #fff !important;
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .btn-secondary {
        color: #fff !important;
        background-color: #6c757d !important;
        border-color: #6c757d !important;
    }

    .btn-primary:hover, .btn-danger:hover, .btn-secondary:hover {
        opacity: 0.85 !important;
    }

    .btn:focus, .btn:active {
        outline: none !important;
        box-shadow: none !important;
    }
    #purchaseRequestsTable tbody tr:hover {
    cursor: pointer;
}

.filter-buttons {
    margin-bottom: 20px !important; /* Adds space between the buttons and the table */
    background-color: rgba(255, 255, 255, 0.9) !important; /* Semi-transparent white background */
    padding: 10px !important; /* Padding around the buttons */
    border-radius: 5px !important; /* Rounded corners for the button container */
}

.filter-button {
    margin-right: 5px !important; /* Adds space between the buttons */
    background-color: #007bff !important; /* Bootstrap primary color for the buttons */
    color: white !important; /* White text for better readability */
    border: 1px solid #0056b3 !important; /* Darker blue border for contrast */
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.2) !important; /* Subtle shadow for depth */
}

.filter-button:hover {
    background-color: #0056b3 !important; /* Darker blue on hover for visual feedback */
}

.filter-button-reset {
    background-color: #dc3545 !important; /* Bootstrap danger color for contrast */
    color: white !important; /* White text for better readability */
    border: 1px solid #bd2130 !important; /* Darker red border for contrast */
}

.filter-button-reset:hover {
    background-color: #bd2130 !important; /* Darker red on hover */
}
    </style>
    
</head>
<body style="background-image: url('<?php echo $backgroundImage; ?>'); background-size: cover;">

<!-- Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"> <!-- Adjusted for a wider modal -->
    <div class="modal-content">
      <div class="modal-header border-b-2 border-gray-200">
        <h5 class="modal-title text-xl font-semibold" id="expenseModalLabel">Expense Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4" id="modalBody">
        <!-- Dynamic content will be loaded here -->
      </div>
      <div class="modal-footer border-t-2 border-gray-200">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="printButton" onclick=printExpenseDetails()>Print</button>
        <?php if ($_SESSION['user_type'] == 'super-admin') { ?>
        <button type="button" class="btn btn-primary" id="approveButton">Approve</button>
        <button type="button" class="btn btn-danger" id="denyButton">Deny</button>
        <?php } ?>

      </div>
    </div>
  </div>
</div>
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
    <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="15%">
   
    </h1>
    <div style="padding: 20px;">
    <div class="filter-buttons">
    <button class="btn btn-info filter-button" data-location="sv">Sauk Village</button>
    <button class="btn btn-info filter-button" data-location="nv">North Vernon</button>
    <button class="btn btn-info filter-button" data-location="nb">New Boston</button>
    <button class="btn btn-info filter-button" data-location="fr">Flatrock</button>
    <button class="btn btn-info filter-button" data-location="tc">Torch</button>
    <button class="btn btn-info filter-button" data-location="gb">Gibraltar</button>
    <button class="btn btn-info filter-button" data-location="rv">Riverview</button>
    <button class="btn btn-secondary filter-button-reset">Reset Filter</button>
</div>
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
    $query = "SELECT expense_id, expense_type, customer_name, customer_location, employee_name, approval_status, location_code FROM purchase_requests";
    $result = mysqli_query($database, $query);
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['expense_id'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['expense_type'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['customer_name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['customer_location'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['employee_name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['approval_status'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['location_code'] ?? '', ENT_QUOTES, 'UTF-8') . "</td> 
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
   
  

</div>



</div>
<!-- Denial Reason Modal -->
<div class="modal fade" id="denialReasonModal" tabindex="-1" aria-labelledby="denialReasonModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="denialReasonModalLabel">Denial Reason</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="denialReasonForm">
          <div class="form-group">
            <label for="denialReasonText" class="col-form-label">Reason:</label>
            <textarea class="form-control" id="denialReasonText"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitDenialReason">Submit</button>
      </div>
    </div>
  </div>
</div>
</body>

<script>
$(document).ready(function() {
    var table = $('#purchaseRequestsTable').DataTable({
        "scrollX": true, // Your existing options
        "columnDefs": [
            {
                "targets": [6], // Index of the location_code column
                "visible": false, // This makes the column hidden
            }
        ]
    });
    var currentUserLocationCode = "<?php echo $_SESSION['location_code']; ?>";

    // Apply the default filter
    table.column(6).search(currentUserLocationCode).draw();
      // Filter action
      $('.filter-button').on('click', function() {
        var locationCode = $(this).data('location');
        table.columns(6).search(locationCode).draw(); // Assuming the 6th column (index 5) contains the location codes
    });

    // Reset filter
    $('.filter-button-reset').on('click', function() {
        table.columns(6).search('').draw();
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
                populateModal(expenseType, response); // Populate modal based on expense type
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
function populateModal(expenseType, response) { // Adjusted to receive the entire response
    var modalBody = $('#modalBody');
    modalBody.empty(); // Clear previous content
    var details = response.details; // Access details
     // Adjust modal dialog class for better width control
     $('#expenseModal .modal-dialog').addClass('modal-md').removeClass('modal-lg');

if(expenseType === 'Expense Report') {
    var formHtml = `
    <form>
    <input type="hidden" id="selectedPurchaseRequest" data-employee-name="${details.employee_name}" data-expense-id="${details.expense_id}">
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="expenseType">Expense Type:</label>
                <input type="text" class="form-control" id="expenseType" value="${expenseType}" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="employeeName">Employee Name:</label>
                <input type="text" class="form-control" id="employeeName" value="${details.employee_name}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="monthOfExpense">Month of Expense:</label>
                <input type="text" class="form-control" id="monthOfExpense" value="${details.month_of_expense}" readonly>
            </div>
        </div>
        <h5>Items:</h5>
    <ul class="list-group">`;
    response.items.forEach(item => {
        formHtml += `
        <li class="list-group-item">
            <div><strong>Customer Name:</strong> ${item.customer_name}</div>
            <div><strong>Customer Location:</strong> ${item.customer_location}</div>
            <div><strong>Date of Visit:</strong> ${item.date_of_visit}</div>
            <div><strong>Mileage:</strong> ${item.mileage}</div>
            <div><strong>Mileage Expense:</strong> ${item.mileage_expense}</div>
            <div><strong>Meals Expense:</strong> ${item.meals_expense}</div>
            <div><strong>Entertainment Expense:</strong> ${item.entertainment_expense}</div>
        </li>
        `;
    });
    formHtml += `
    </ul>
    </form>
`;
modalBody.append(formHtml);

    // Handle files separately
    if(response.files && response.files.length > 0) {
        var fileListHtml = '<div class="form-group"><label>Files:</label><ul class="list-unstyled">';
        response.files.forEach(function(file) {
            var downloadUrl = `download.php?file=${encodeURIComponent(file.file_name)}`;
            fileListHtml += `<li><a href="${downloadUrl}" target="_blank">${file.file_name}</a></li>`;
        });
        fileListHtml += '</ul></div>';
        modalBody.append(fileListHtml);
    }
}
else if (expenseType === 'Travel Approval') {
        var formHtml = `
        <form>
        <input type="hidden" id="selectedPurchaseRequest" data-employee-name="${details.employee_name}" data-expense-id="${details.expense_id}">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="employeeName">Employee Name:</label>
                    <input type="text" class="form-control" id="employeeName" value="${details.employee_name}" readonly>
                </div>
                <div class="col-md-6 form-group">
                    <label for="expenseType">Expense Type:</label>
                    <input type="text" class="form-control" id="expenseType" value="${expenseType}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="travelStartDate">Travel Start Date:</label>
                    <input type="text" class="form-control" id="travelStartDate" value="${details.travel_start_date}" readonly>
                </div>
                <div class="col-md-6 form-group">
                    <label for="travelEndDate">Travel End Date:</label>
                    <input type="text" class="form-control" id="travelEndDate" value="${details.travel_end_date}" readonly>
                </div>
            </div>
           
            <div class="row">
                <div class="col-12 form-group">
                    <label for="additionalComments">Additional Comments:</label>
                    <textarea class="form-control" id="additionalComments" readonly>${details.additional_comments}</textarea>
                </div>
            </div>
        </form>
        `;
         // Dynamically add customer details if they exist in the response, using a list
    if (response.customers && response.customers.length > 0) {
        formHtml += `<h5>Customer Details:</h5><ul class="list-group">`;
        response.customers.forEach(customer => {
            formHtml += `
            <li class="list-group-item">
                <div><strong>Customer Name:</strong> ${customer.customer_name}</div>
                <div><strong>Customer Location:</strong> ${customer.customer_location}</div>
            </li>`;
        });
        formHtml += `</ul>`;
    }
        modalBody.append(formHtml);
    

    // Handle files separately, if applicable
    if(response.files && response.files.length > 0) {
        var fileListHtml = '<div class="form-group"><label>Files:</label><ul class="list-unstyled">';
        response.files.forEach(function(file) {
            var downloadUrl = `download.php?file=${encodeURIComponent(file.file_name)}`;
            fileListHtml += `<li><a href="${downloadUrl}" target="_blank">${file.file_name}</a></li>`;
        });
        fileListHtml += '</ul></div>';
        modalBody.append(fileListHtml);
    }
}
else {
    // Code for all other expense types, including "Office Supplies"
    var formHtml = `
    <form>
        <input type="hidden" id="selectedPurchaseRequest" data-employee-name="${details.employee_name}" data-expense-id="${details.expense_id}">
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="employeeName">Employee Name:</label>
                <input type="text" class="form-control" id="employeeName" value="${details.employee_name}" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="expenseType">Expense Type:</label>
                <input type="text" class="form-control" id="expenseType" value="${expenseType}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="customerLocation">Customer Location:</label>
                <input type="text" class="form-control" id="customerLocation" value="${details.customer_location}" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="vendorName">Vendor Name:</label>
                <input type="text" class="form-control" id="vendorName" value="${details.vendor_name}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="monthOfExpense">Month of Expense:</label>
                <input type="text" class="form-control" id="monthOfExpense" value="${details.month_of_expense}" readonly>
            </div>
        </div>
    </form>
    <h5>Items:</h5>
    <ul class="list-group">`;
    console.log(response);
    response.items.forEach(item => {
        formHtml += `
        <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="flex: 1; margin-right: 15px;"><strong>Item:</strong> ${item.item_name}</div>
            <div style="flex: 1; margin-right: 15px;"><strong>Quantity:</strong> ${item.item_quantity}</div>
            <div style="flex: 1; margin-right: 15px;"><strong>Price per Item:</strong> ${item.price_per_item}</div>
            <div style="flex: 1; margin-right: 15px;"><strong>Total Cost:</strong> ${item.total_cost}</div>
            <div style="flex: 1;"><strong>Department:</strong> ${item.department}</div>
        </li>`;
    });

    formHtml += `</ul>`;
    modalBody.append(formHtml);

    // Handle files separately, if applicable
    if(response.files && response.files.length > 0) {
        var fileListHtml = '<div class="form-group"><label>Files:</label><ul class="list-unstyled">';
        response.files.forEach(function(file) {
            var downloadUrl = `download.php?file=${encodeURIComponent(file.file_name)}`;
            fileListHtml += `<li><a href="${downloadUrl}" target="_blank">${file.file_name}</a></li>`;
        });
        fileListHtml += '</ul></div>';
        modalBody.append(fileListHtml);
    }
}

}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('approveButton').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to approve this request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var selectedElement = document.getElementById('selectedPurchaseRequest');
                var employeeName = selectedElement.getAttribute('data-employee-name');
                var expenseId = selectedElement.getAttribute('data-expense-id');
                sendRequest('approve', employeeName, expenseId);
            }
        });
    });

    document.getElementById('denyButton').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to deny this request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deny it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show the Bootstrap modal for entering denial reason
                $('#denialReasonModal').modal('show');
            }
        });
    });

    document.getElementById('submitDenialReason').addEventListener('click', function() {
        var reason = document.getElementById('denialReasonText').value;
        if (reason) {
            var selectedElement = document.getElementById('selectedPurchaseRequest');
            var employeeName = selectedElement.getAttribute('data-employee-name');
            var expenseId = selectedElement.getAttribute('data-expense-id');
            sendRequest('deny', employeeName, expenseId, reason);
            // Hide the modal after submission
            $('#denialReasonModal').modal('hide');
        } else {
            // Optionally, alert the user that a reason is required
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter a reason for denial.',
                confirmButtonText: 'OK'
            });
        }
    });

    function sendRequest(action, username, expenseId, reason = '') {
      var formHtml = document.getElementById('modalBody').innerHTML; // Get the form HTML
    fetch('../../configurations/handle_request.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=${action}&username=${username}&expenseId=${expenseId}&reason=${reason}&formHtml=${encodeURIComponent(formHtml)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Operation successful: ' + data.message);
            // Optionally, refresh the page or update the UI accordingly
            location.reload();
        } else {
            alert('Operation failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
});

function printExpenseDetails() { 
    var selectedElement = document.getElementById('selectedPurchaseRequest');
    var expenseId = selectedElement.getAttribute('data-expense-id');
    window.open(`print.php?expenseId=${expenseId}`, '_blank');
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const expenseId = urlParams.get('expenseId');
    if (expenseId) {
        fetchExpenseDetails(expenseId); // Assuming this function fetches and populates the modal with expense details
        $('#expenseModal').modal('show'); // Assuming 'expenseModal' is the ID of your modal
    }
});

</script>

</html>