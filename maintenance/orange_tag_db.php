<?php
session_start();
include '../configurations/connection.php'; 
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('super-admin' || 'maintenance-tech')){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}
date_default_timezone_set('America/Chicago');
  // Query to get the total count of tickets
  $count_query = "SELECT COUNT(*) as total FROM `orange_tag`";
  $count_result = mysqli_query($database, $count_query);
  $data = mysqli_fetch_assoc($count_result);
  $count = $data['total']+1;

    $tag_author= $_SESSION['user'];

// Fetch the data from the database
$query = "SELECT * FROM employees WHERE job_title IN (14,18,19,22,23,24,25,26,27,31,33,38) AND `status` = 'active'"; 
$supervisors = mysqli_query($database, $query);

$query = "SELECT * FROM employees WHERE job_title = 25 AND `status` = 'active'";
$maintenance_managers = mysqli_query($database, $query);

$query = "SELECT * FROM employees WHERE job_title = 38 AND `status` = 'active'";
$safety_coordinators = mysqli_query($database, $query);
$current_user_location_code = $_SESSION['location_code']; // Assuming the location code of the current user is stored in the session

// Query for open tickets
$query = "SELECT COUNT(*) as total FROM `orange_tag` WHERE `location_code` = '$current_user_location_code' AND `ticket_status` = 'Open'";
$result = mysqli_query($database, $query);
$data = mysqli_fetch_assoc($result);
$openTicketCount = $data['total'];

// Query for priority 1 tickets
$query = "SELECT COUNT(*) as total FROM `orange_tag` WHERE `location_code` = '$current_user_location_code' AND `priority` = 1 AND `ticket_status` = 'Open'";
$result = mysqli_query($database, $query);
$data = mysqli_fetch_assoc($result);
$priority1TicketCount = $data['total'];

// Query for priority 2 tickets
$query = "SELECT COUNT(*) as total FROM `orange_tag` WHERE `location_code` = '$current_user_location_code' AND `priority` = 2 AND `ticket_status` = 'Open'";
$result = mysqli_query($database, $query);
$data = mysqli_fetch_assoc($result);
$priority2TicketCount = $data['total'];

// Query for priority 3 tickets
$query = "SELECT COUNT(*) as total FROM `orange_tag` WHERE `location_code` = '$current_user_location_code' AND `priority` = 3 AND `ticket_status` = 'Open'";
$result = mysqli_query($database, $query);
$data = mysqli_fetch_assoc($result);
$priority3TicketCount = $data['total'];

// Query for priority 4 tickets
$query = "SELECT COUNT(*) as total FROM `orange_tag` WHERE `location_code` = '$current_user_location_code' AND `priority` = 4 AND `ticket_status` = 'Open'";
$result = mysqli_query($database, $query);
$data = mysqli_fetch_assoc($result);
$priority4TicketCount = $data['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="printWorkOrder.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

<script>
$(document).ready( function () {
    $('.table-auto').DataTable();
});
</script>

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
        

    .modal-content {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6;
        background-color: #e9ecef;
    }

    .modal-title {
        color: #495057;
    }

    .modal-body {
        padding: 2em;
    }

    .form-control {
        border-radius: 0;
    }

    .form-group label {
        font-weight: bold;
        color: #495057;
    }

    
  
.modal-dialog.custom-modal {
        max-width: 1000px; /* Adjust this value to set the width of your form */
    }
    .checkbox-container {
        height: 150px; /* Adjust as needed */
        overflow-y: auto;
    }
    
    .modal .btn-primary {
        background-color: #FFA500; /* Background color for the primary button */
        border-color: #FFA500; /* Border color for the primary button */
        color: #fff; /* Text color for the primary button */
    }

    .modal .btn-secondary {
        background-color: #6c757d; /* Background color for the secondary button */
        border-color: #6c757d; /* Border color for the secondary button */
        color: #fff; /* Text color for the secondary button */
    }

    .modal .btn-primary:hover, .modal .btn-secondary:hover {
        opacity: 0.8; /* Reduce opacity when hovering over the buttons */
    }
    .btn-primary {
        background-color: #FFA500; /* Background color for the primary button */
        border-color: #FFA500; /* Border color for the primary button */
        color: #fff; /* Text color for the primary button */
    }

    .btn-primary:hover {
        opacity: 0.8; /* Reduce opacity when hovering over the button */
        background-color: #FFA500; /* Background color for the primary button */
        border-color: #FFA500; /* Border color for the primary button */
        color: #fff; /* Text color for the primary button */
        
    }
    
    @media print {
    /* All your print styles go here */
    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; }
    h1 { font-size: 28px; text-align: center; margin-bottom: 0.5em; }
    h2 { font-size: 22px; color: #444; margin-top: 1em; margin-bottom: 0.25em; }
    table { width: 100%; border-collapse: collapse; margin-top: 1em; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background-color: #eee; font-weight: bold; }
    td { background-color: #fff; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .footer { margin-top: 30px; text-align: center; font-size: 0.85em; }
    .print-container { margin: 20px; }
    .parts-needed { background-color: #dff0d8; }
    /* Hide elements not needed for printing */
    .print-hide { display: none; }
}
/* Styling for DataTables' table */
.dataTables_wrapper .table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    table-layout: auto; /* Allow cells to adjust their widths as needed */
    border: 1px solid #dee2e6;
    border-radius: 10px;
}

/* Styling for rows */
.dataTables_wrapper .table-striped tbody tr:nth-of-type(odd) {
    background-color: #fff;
}

/* Styling for table headers */
/* Styling for table headers */
#orange_tag_table thead th {
    background-color: #FFA500; /* Orange background */
    color: #000; /* Black text */
    /* Other styles */
}
.dataTables_wrapper .table thead th {
    background-color: #FFA500 !important; /* Orange background */
    color: #000 !important; /* Black text */
    padding: 10px;
    text-align: left;
    position: sticky;
    top: 0; /* This will make the header stick to the top */
    z-index: 10; /* This will make sure the header is above the table rows */
}
/* Styling for table cells */
.dataTables_wrapper .table td {
    border: 1px solid #ddd;
    padding: 8px;
}

/* Hover effect for rows */
.dataTables_wrapper .table-striped tbody tr:hover {
    background-color: #e0e0e0;
    cursor: pointer;
}

/* Adjusting the DataTables pagination buttons to match your theme */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #212529; /* Button text color */
    padding: 0.5em 1em;
    margin: 0 2px;
    border: 1px solid transparent; /* Remove border */
    border-radius: 5px; /* Rounded corners for buttons */
    background-color: #FFA500; /* Button background color */
    box-shadow: none; /* Remove default DataTables button shadow */
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #e69500; /* Darker shade for hover */
    border-color: transparent;
}

/* Active pagination button */
.dataTables_wrapper .dataTables_paginate .paginate_button.current, 
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    color: #fff !important;
    background-color: #007bff !important; /* Bootstrap primary color for active button */
    border-color: transparent;
}
/* Change search input text color to white */
.dataTables_wrapper .dataTables_filter input {
    color: black; /* Text color */
    padding: 0.25em 0.5em;
}

/* Change pagination buttons text color to white */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #fff !important; /* Text color */
}

/* Change the text color of DataTables length menu (show entries) to white */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_length label {
    color: black; /* Text color */
}

/* Change the text color of DataTables "Showing 1 to 10 of 50 entries" to white */
.dataTables_wrapper .dataTables_info {
    color: #fff; /* Text color */
}
.dataTables_length label,
.dataTables_filter label {
    color: white !important;
}

/* Change DataTables pagination buttons text color to white */
.dataTables_wrapper .dataTables_paginate .paginate_button.next, 
.dataTables_wrapper .dataTables_paginate .paginate_button.previous {
    color: white !important;
}

    </style>

    <title>S.M.A.R.T.</title>
    <!-- Add your CSS styles here -->
</head>
<body style="background-image: url('../images/steel_coils.jpg'); background-size: cover;">
<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] != 'floor-user'): ?>
<div class="return-button-container">
    <a href="../super-admin/index.php" class="return-button">Return to Dashboard</a>
</div>
<?php endif; ?>
    <h2 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../images/home_page_SMART_header.png" alt="company header" width="30%" height="20%" > 
    </h2>
  
</div>
<!-- Add this in your HTML where you want the loading symbol to appear -->

     
  
    
    <div class="container mt-5">
    
    <div class="row">
        <div class="col-12">
        <button id="newTicketButton" class="btn btn-primary" data-toggle="modal" data-target="#newTicketModal">New Maintenance Ticket</button>
        <button class="btn btn-secondary" onclick="viewClosedTickets()">View Closed</button>
        <button class="btn btn-secondary" onclick="viewOpenTickets()">View Open</button>
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'super-admin'): ?>
        <button id="generateReportButton" class="btn btn-info" data-toggle="modal" data-target="#reportModal">Generate Report</button>
        <?php endif; ?>
        <button class="btn btn-info" data-toggle="modal" data-target="#howToModal" style="font-size: 24px; line-height: 1; padding: 0 10px;">?</button>
<!-- How to Use System Modal -->
<!-- How to Use System Modal -->
<!-- How to Use System Modal -->
<div class="modal fade" id="howToModal" tabindex="-1" role="dialog" aria-labelledby="howToModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="howToModalLabel">System Usage Guidelines</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 style="color: #007bff; margin-top: 20px; margin-bottom: 10px;">Creating a New Ticket:</h6>
                <p>Click <strong>New Maintenance Ticket</strong> to begin. Select the priority level (1-4), choose the ticket type, and provide line or die number if needed. Enter supervisor's name, your full name (Originator), precise location, and a comprehensive description of the issue.</p>

                <h6 style="color: #007bff; margin-top: 20px; margin-bottom: 10px;">Maintenance & Repair Technicians:</h6>
                <p>Log repair details under 'Ticket Details' and 'Repairs/Maintenance' tabs. Include actions taken, causes, repair times, and note any parts used. Ensure all tasks are checked off and documented.</p>

                <h6 style="color: #007bff; margin-top: 20px; margin-bottom: 10px;">Follow-Up:</h6>
                <p>Management is responsible for documenting, generating work order numbers, and ensuring records are up to date. Remember, closed tickets are locked from editing and require an administrator to reopen.</p>

                <h6 style="color: #007bff; margin-top: 20px; margin-bottom: 10px;">Assigning Technicians:</h6>
                <p>Assign technicians to work orders by selecting from the list provided. Only management has the ability to assign and manage technician tasks.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportModalLabel">Report Options</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <div class="row">
        <div class="col-md-6 mb-2">
            <button class="btn btn-primary btn-block" onclick="generateReport('openTags')">All Open Tags</button>
        </div>
        <div class="col-md-6 mb-2">
            <button id="TagsByTech" class="btn btn-primary btn-block">Tags by Tech</button>
        </div>
        <div class="col-md-6 mb-2">
            <button id="TagsByPriority" class="btn btn-primary btn-block" data-toggle="modal" data-target="#prioritySelectionModal">Tags by Priority</button>
        </div>
        <div class="col-md-6 mb-2">
            <button id="TagsByType" class="btn btn-primary btn-block" data-toggle="modal" data-target="#ticketTypeSelectionModal">Tags by Type</button>
        </div>
    </div>
</div>
    </div>
  </div>
</div>
<!-- Ticket Type Selection Modal -->
<div class="modal fade" id="ticketTypeSelectionModal" tabindex="-1" role="dialog" aria-labelledby="ticketTypeSelectionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ticketTypeSelectionModalLabel">Select Ticket Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select id="ticketTypeSelect" class="form-control">
            <!-- Populate with ticket types -->
            <option value="" selected disabled hidden></option>
        <option value="Safety">Safety</option>
        <option value="Maintenance">Maintenance</option>
        <option value="Line Maintenance">Line Maintenance</option>
        <option value="Die Maintenance">Die Maintenance</option>
        <option value="Forklift">Forklift</option>
        <option value="Cranes">Cranes</option>
        <option value="Semi Truck">Semi Truck</option>
        <option value="Building/Property">Building/Property</option>
        <option value="Packaging">Packaging</option>
        <option value="Projects/Improvements">Projects/Improvements</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="generateTicketTypeReport()">Generate Report</button>
      </div>
    </div>
  </div>
</div>
<!-- Priority Selection Modal -->
<div class="modal fade" id="prioritySelectionModal" tabindex="-1" role="dialog" aria-labelledby="prioritySelectionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="prioritySelectionModalLabel">Select Priority</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select id="prioritySelect" class="form-control">
            <option value="1">Priority 1</option>
            <option value="2">Priority 2</option>
            <option value="3">Priority 3</option>
            <option value="4">Priority 4</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="generatePriorityReport()">Generate Report</button>
      </div>
    </div>
  </div>
</div>
<!-- Tech Selection Modal -->
<div class="modal fade" id="techSelectionModal" tabindex="-1" role="dialog" aria-labelledby="techSelectionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="techSelectionModalLabel">Select a Technician</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select id="techSelect" class="form-control">
          <!-- Technician options will be populated here -->
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="generateTechReport()">Generate Report</button>
      </div>
    </div>
  </div>
</div>
        <button id="myOpenTicketsButton" class="btn btn-secondary" style="display:none;" onclick="viewMyOpenTickets()">My Open Tickets</button>
        <?php if ($_SESSION['user_type'] == 'maintenance-tech'): ?>
        <script>
        document.getElementById('myOpenTicketsButton').style.display = 'block';
        </script>
        <?php endif; ?>
        </div>
    </div>
    <div class="card-deck mt-3">
    <div class="card text-white bg-primary mb-3">
        <div class="card-body">
            <h5 class="card-title">Open Tickets</h5>
            <p class="card-text" style="font-size: 2em;"><?php echo $openTicketCount; ?></p>
        </div>
    </div>
    <div class="card text-white" style="background-color: #b71c1c;">
        <div class="card-body">
            <h5 class="card-title">Priority 1</h5>
            <p class="card-text" style="font-size: 2em;"><?php echo $priority1TicketCount; ?></p>
        </div>
    </div>
    <div class="card text-white" style="background-color: #f57f17;">
        <div class="card-body">
            <h5 class="card-title">Priority 2</h5>
            <p class="card-text" style="font-size: 2em;"><?php echo $priority2TicketCount; ?></p>
        </div>
    </div>
    <div class="card text-white" style="background-color: #fdd835;">
        <div class="card-body">
            <h5 class="card-title">Priority 3</h5>
            <p class="card-text" style="font-size: 2em;"><?php echo $priority3TicketCount; ?></p>
        </div>
    </div>
    <div class="card text-white" style="background-color: #4a148c;"> <!-- Choose a color that represents Priority 4 -->
        <div class="card-body">
            <h5 class="card-title">Priority 4</h5>
            <p class="card-text" style="font-size: 2em;"><?php echo $priority4TicketCount; ?></p> <!-- Use the variable that holds the count for Priority 4 tickets -->
        </div>
    </div>
</div>
    <div class="row mt-3">
        <div class="col-12">
           
                <table id = "orange_tag_table" class="table-auto">
                    <thead>
                        <tr>
                            <th>Orange Tag ID</th>
                            <th>Creation Date</th>
                            <th>Due Date</th>
                            <th>Originator</th>
                            <th>Type</th>
                            <th>Priority</th>
                            <th>Work Order Number</th>
                            <th>Repair Technicians</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 


$current_user_location_code = $_SESSION['location_code']; // Assuming the location code of the current user is stored in the session

$query = "SELECT * FROM orange_tag WHERE location_code = '$current_user_location_code'";

$result = mysqli_query($database, $query);
while ($row = mysqli_fetch_assoc($result)): ?>
    <tr class="<?php echo $row['ticket_status']; ?>">
        <td><?php echo $row['orange_tag_id']; ?></td>
        <td><?php echo date('m-d-Y', strtotime($row['orange_tag_creation_date'])); ?></td>
        <td><?php echo date('m-d-Y', strtotime($row['orange_tag_due_date'])); ?></td>
        <td><?php echo $row['originator']; ?></td>
        <td><?php echo $row['ticket_type']; ?></td>
        <td><?php echo $row['priority']; ?></td>
        <td><?php echo $row['work_order_number']; ?></td>
        <td class="repair-technician">
        <?php 
        $technicians = explode(',', $row['repair_technician']);
        foreach ($technicians as $technician) {
            if (!empty($technician)) {
                $tech_query = "SELECT `username` FROM `Users` WHERE `id` = $technician"; // Replace 'id' with the correct column name
                $tech_result = mysqli_query($database, $tech_query);
                if ($tech_result) {
                    $tech_data = mysqli_fetch_assoc($tech_result);
                    echo htmlspecialchars($tech_data['username']) . '<br>';
                } else {
                    // Handle error, e.g., log it or echo a message
                    echo "Error fetching technician data: " . mysqli_error($database);
                }
            }
        }
        ?>
    </td>
        <td><?php echo $row['location']; ?></td>
        <td class="ticket-status"><?php echo $row['ticket_status']; ?></td>
        <td><?php echo $row['orange_tag_description']; ?></td>
    </tr>
<?php endwhile; ?>
                    </tbody>
                </table>
                </div>
        </div>
</div>

<!-- Full-screen loading overlay -->
<div id="loading-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
        <img src="../images/Spin-0.7s-703px.gif" alt="Loading..." />
    </div>
</div>
   
    <!-- New Ticket Modal -->
<div class="modal fade" id="newTicketModal" tabindex="-1" role="dialog" aria-labelledby="newTicketModalLabel" aria-hidden="true">

    <div class="modal-dialog custom-modal" role="document"> <!-- Add the custom-modal class here -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newTicketModalLabel">New Maintenance Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="ticket-details-tab" data-toggle="tab" href="#ticket-details" role="tab" aria-controls="ticket-details" aria-selected="true">Ticket Details</a>
                    </li>
                    <li class="nav-item">
                        <?php if($_SESSION['user_type'] == ('super-admin' || 'maintenance-tech')): ?>
                        <a class="nav-link" id="repairs-maintenance-tab" data-toggle="tab" href="#repairs-maintenance" role="tab" aria-controls="repairs-maintenance" aria-selected="false">Repairs/Maintenance</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <?php if($_SESSION['user_type'] == 'super-admin'): ?>
                        <a class="nav-link" id="follow-up-tab" data-toggle="tab" href="#follow-up" role="tab" aria-controls="follow-up" aria-selected="false">Follow Up</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <?php if($_SESSION['user_type'] == 'super-admin'): ?>
                        <a class="nav-link" id="assign_technicians-tab" data-toggle="tab" href="#assign_technicians" role="tab" aria-controls="assign_technicians" aria-selected="false">Assign Technicians</a>
                        <?php endif; ?>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
               
                <div class="tab-pane fade show active" id="ticket-details" role="tabpanel" aria-labelledby="ticket-details-tab">
                    <form class= "trackable-form" id="new-ticket-form-ticket-details">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="orange_tag_id">SMART Tag I.D.</label>
                                    <input type="text" class="form-control" id="orange_tag_id" name="SMART Tag ID"readonly>
                                </div>
                                <div class="form-group">
                                <label for="ticket_type">Ticket Type</label>
    <select class="form-control" id="ticket_type" name="Ticket Type" required>
        <option value="" selected disabled hidden></option>
        <option value="Safety">Safety</option>
        <option value="Maintenance">Maintenance</option>
        <option value="Line Maintenance">Line Maintenance</option>
        <option value="Die Maintenance">Die Maintenance</option>
        <option value="Forklift">Forklift</option>
        <option value="Cranes">Cranes</option>
        <option value="Semi Truck">Semi Truck</option>
        <option value="Building/Property">Building/Property</option>
        <option value="Packaging">Packaging</option>
        <option value="Projects/Improvements">Projects/Improvements</option>
    </select>
                                </div>
                                <div class="form-group">
                                    <label for="originator">Originator</label>
                                    <input type="text" class="form-control" id="originator" name="Account of Origin" value="<?php echo $tag_author; ?>" required style="display: none">
                                    <input type="text" class="form-control" id="originator_name" name="Originator Name"  required>
                                </div>
                                <div class="form-group">
                                    <label for="location">Location in building/ On Line </label>
                                    <input type="text" class="form-control" id="location" name="Location" required>
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Priority</label>
                                    <select class="form-control" id="priority" name="Priority" required>
                                        <option value="" selected disabled hidden></option>
                                        <option value="1" title="Safety:Any issue that you see fit to prevent injury, accidents, or hazards">1</option>
                                        <option value="2" title="Quality: Any issue that delays/obstructs products, processes, or deliverables of our customers expectations">2</option>
                                        <option value="3" title="Production:Any issue that causes a delay in meeting production goals or the delivery of product and services">3</option>
                                        <option value="4" title="Cost: Continuous improvement ideas that could decrease expenses,optimize utilization and maintain profitibility ">4</option>
                                    </select>
                                </div>
                                <?php
// Fetch the data from the Lines table
$lines_query = "SELECT * FROM `Lines`";
$lines_result = mysqli_query($database, $lines_query);
?>

<div class="form-group" id="line_name_group" style="display: none;">
    <label for="line_name">Line Name</label>
    <select class="form-control" id="line_name" name="Line Name">
    <option value="" selected disabled hidden></option>
        <?php while ($line = mysqli_fetch_assoc($lines_result)): ?>
            <option value="<?php echo $line['line_id']; ?>">
                <?php echo $line['Line_Name'] . ' - ' . $line['Line_Location']; ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>
<div class="form-group" id="die_number_group" style="display: none;">
    <label for="die_number">Die Number</label>
    <input type="text" class="form-control" id="die_number" name="Die Number">
</div>


                                <div class="form-group">
                                    <label for="supervisor">Supervisor</label>
                                    <select class="form-control" id="supervisor" name="Supervisor" required>
                                        <option value="" selected disabled hidden></option>
                                        <?php while ($row = mysqli_fetch_assoc($supervisors)): ?>
                                        <option value="<?php echo $row['employee_id']; ?>">
                                        <?php echo $row['employee_fname'] . ' ' . $row['employee_lname']; ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="orange_tag_creation_date">Creation Date</label>
                                    <input type="date" class="form-control" id="orange_tag_creation_date" name="Creation Date" value="<?php echo date('Y-m-d'); ?>" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="orange_tag_creation_time">Creation Time</label>
                                    <input type="time" class="form-control" id="orange_tag_creation_time" name="Creation Time" value="<?php echo date('H:i'); ?>" required readonly>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="orange_tag_description">Description</label>
                                    <textarea class="form-control" id="orange_tag_description" name="Description" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="repairs-maintenance" role="tabpanel" aria-labelledby="repairs-maintenance-tab">
                    <form class= "trackable-form" id="new-ticket-form-repairs-maintenance">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="repairs_made">Repairs Made</label>
                                <textarea class="form-control" id="repairs_made" name="Repairs Made" rows="3"></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="root_cause">Root Cause</label>
                                <textarea class="form-control" id="root_cause" name="Root Cause" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <div class="form-group">
                                    <label for="orange_tag_due_date">Due Date</label>
                                    <input type="date" class="form-control" id="orange_tag_due_date" name="Due Date" required>
                                </div>
                            <div class="form-check">
    <input class="form-check-input" type="checkbox" value="No" id="area_cleaned" name="area_cleaned" onchange="this.value = this.checked ? 'on' : 'off'">
    <label class="form-check-label" for="area_cleaned">Area Cleaned</label>
</div>

<div class="form-check mt-2">
    <input class="form-check-input" type="checkbox" id="follow_up_necessary" name="follow_up_necessary" onchange="this.value = this.checked ? 'on' : 'off'">
    <label class="form-check-label" for="follow_up_necessary">Follow Up Necessary</label>
</div>
                                

<div class="form-check mt-2">
    <input class="form-check-input" type="checkbox" value="No" id="parts_needed" name="parts_needed" onchange="togglePartsForm(this.checked); this.value = this.checked ? 'on' : 'off'">
    <label class="form-check-label" for="parts_needed">Parts Used</label>
</div>
                            </div>
                                    <div class="form-group col-md-3">
                                        <label for="total_repair_time">Total Repair Time Hours</label>
                                        <input type="number" step="0.01" class="form-control" id="total_repair_time" name="Total Repair Time">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="equipment_down_time">Equipment Down Time Hours</label>
                                        <input type="number" step="0.01" class="form-control" id="equipment_down_time" name="Equipment Downtime">
                                    </div>
                                    
                            <div id="parts_needed_form" style="display: none;">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPartModal">
                                    Add Part
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="addPartModal" tabindex="-1" role="dialog" aria-labelledby="addPartModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addPartModalLabel">Add Part</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Your form goes here -->
                                                <form id="new_part_form">
                                                    <div class="form-group">
                                                        <label for="date_used">Date Used</label>
                                                        <input type="date" class="form-control" id="date_used" name="date_used" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="part_description">Part Description</label>
                                                        <textarea class="form-control" id="part_description" name="part_description" rows="3" required></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="quantity">Quantity</label>
                                                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="brand_name">Brand Name</label>
                                                        <input type="text" class="form-control" id="brand_name" name="brand_name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="model_number">Model Number</label>
                                                        <input type="text" class="form-control" id="model_number" name="model_number">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="serial_number">Serial Number</label>
                                                        <input type="text" class="form-control" id="serial_number" name="serial_number">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dimensions">Dimensions</label>
                                                        <input type="text" class="form-control" id="dimensions" name="dimensions">
                                                    </div>
                                                    <input type="hidden" id="orange_tag_id" name="orange_tag_id">
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" id="save-part">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                    

                                <div class="col-12">
                                    <table id="parts_list" class="table">
                                        <thead>
                                            <tr>
                                            <th>Date Used</th>
                                            <th>Part Description</th>
                                            <th>Quantity</th>
                                            <th>Brand Name</th>
                                            <th>Model Number</th>
                                            <th>Serial Number</th>
                                            <th>Dimensions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
                <div class="tab-pane fade" id="assign_technicians" role="tabpanel" aria-labelledby="assign_technicians-tab">
                    <form class= "trackable-form" id ="new-ticket-form-assign_technicians">
                    <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="repair_technician">Repair Technician</label>
                                <div id="repair_technician" class="checkbox-container"></div>
                              
                            </div>
                        </div>
                    </form>

                </div>

                <div class="tab-pane fade" id="follow-up" role="tabpanel" aria-labelledby="follow-up-tab">
                    <form class= "trackable-form" id="new-ticket-form-follow-up">
                    <div class="form-group">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="reviewed_by_supervisor" onchange="toggleDateField('supervisor_review_date', this.checked)">
        <label class="form-check-label" for="reviewed_by_supervisor">Reviewed By Supervisor</label>
    </div>
    <input type="date" class="form-control" id="supervisor_review_date" name="supervisor_review_date" style="display: none;">
</div>

<div class="form-group">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="reviewed_by_safety_coordinator" onchange="toggleDateField('safety_coordinator_review_date', this.checked)">
        <label class="form-check-label" for="reviewed_by_safety_coordinator">Reviewed By Safety Coordinator</label>
        <input type="text" class="form-control" id="location_code" name="location_code" value="<?php echo $_SESSION['location_code']; ?>" style="display: none;">
    </div>
    <input type="date" class="form-control" id="safety_coordinator_review_date" name="safety_coordinator_review_date" style="display: none;">
</div>

<div class="form-group">
    <!-- Confirmation Modal -->
<div class="modal fade" id="confirmCloseModal" tabindex="-1" role="dialog" aria-labelledby="confirmCloseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmCloseModalLabel">Confirm Ticket Closure</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Closing the ticket cannot be undone. Once closed, the ticket will no longer be modifiable. Are you sure you would like to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmCloseButton">Yes, Close Ticket</button>
            </div>
        </div>
    </div>
</div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="verified" onchange="toggleDateField('date_verified', this.checked)">
        <label class="form-check-label" for="verified">Verified</label>
    </div>
    <input type="date" class="form-control" id="date_verified" name="date_verified" style="display: none;">
</div>
                        <div class="form-group">
                            <label for="ticket_status">Ticket Status</label>
                            <select class="form-control" id="ticket_status" name="ticket_status" required>
                                <option value="Open" selected>Open</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                        <div class="form-group">
    <label for="date_closed">Date Closed</label>
    <input type="date" class="form-control" id="date_closed" name="date_closed" readonly>
</div>
                        
                        <div class="form-group">
                            <button type="button" id="generate_wo_number" class="btn btn-primary">Generate WO #</button>
                            <input type="text" class="form-control" id="work_order_number" name="work_order_number" readonly>
                        </div>
                        <div class="form-group col-md-3">
                                        <label for="total_cost">Total Cost</label>
                                        <input type="number" step="0.01" class="form-control" id="total_cost" name="total_cost">
                        </div>
                    </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary" id="print-workorder">Print Workorder</button>
                <button type="button" class="btn btn-primary" id="save-ticket">Save Ticket</button>
                <button type="button" class="btn btn-primary" id="update-ticket">Update Ticket</button>
            </div>
        </div>
    </div>
</div>

<script>
      
$(document).ready(function() {
    $('#update-ticket').click(function() {
        // Check if the ticket status is set to 'Closed'
        if ($('#ticket_status').val() === 'Closed') {
            // Show the confirmation modal
            $('#confirmCloseModal').modal('show');
        } else {
            // If the ticket status is not 'Closed', proceed with the update
            updateTicket();
        }
    });

    $('#confirmCloseButton').click(function() {
        // Proceed with the update since the user confirmed the action
        updateTicket();
        // Hide the confirmation modal
        $('#confirmCloseModal').modal('hide');
    });
    // Initially disable the button
    $('#generate_wo_number').prop('disabled', true);

    function checkTechniciansSelected() {
        var anyChecked = $('#repair_technician input[type=checkbox]:checked').length > 0;
        $('#generate_wo_number').prop('disabled', !anyChecked);
    }

    // Run the check function whenever any checkbox within the repair_technician container is changed
    $('#repair_technician').on('change', 'input[type=checkbox]', checkTechniciansSelected);

    // Call the check function when the modal is opened or when the form is loaded
    $('#newTicketModal').on('shown.bs.modal', function() {
        checkTechniciansSelected();
    });


    $('#generate_wo_number').click(function() {
        function togglePrintButton() {
        var workOrderNumber = $('#work_order_number').val();
        $('#print-workorder').prop('disabled', !workOrderNumber);
    }
        var repair_technicians = [];
        $('#repair_technician input:checked').each(function() {
            repair_technicians.push($(this).val());
        });
        var orangeTagId = $('#orange_tag_id').val();
        var workOrderNumber = 'MA' + orangeTagId.substring(2);
        $('#work_order_number').val(workOrderNumber);

        // AJAX call to send email
        
        
});
});
function togglePartsForm(isChecked, parts) {
    var form = document.getElementById('parts_needed_form');
    if (parts && parts.length > 0) {
        // If there are parts, always show the form
        form.style.display = 'block';
    } else {
        // Otherwise, show or hide the form based on isChecked
        form.style.display = isChecked ? 'block' : 'none';
    }
}
// Initialize an empty array to store the parts
var parts = [];

$(document).ready(function() {
      // Clear the form when the "Add Part" button is clicked
   
    $('#save-part').click(function() {
        // Create a part object from the form data
        var part = {
            date_used: $('#date_used').val(),
            orange_tag_id: $('#orange_tag_id').val(),
            part_description: $('#part_description').val(),
            quantity: $('#quantity').val(),
            brand_name: $('#brand_name').val(),
            model_number: $('#model_number').val(),
            serial_number: $('#serial_number').val(),
            dimensions: $('#dimensions').val()
        };

        // Add the part to the parts array
        parts.push(part);

        // Clear the table
        $('#parts_list tbody').empty();

        // Add each part to the table
        $.each(parts, function(i, part) {
            var row = '<tr>' +
                '<td>' + part.date_used + '</td>' +
                '<td>' + part.part_description + '</td>' +
                '<td>' + part.quantity + '</td>' +
                '<td>' + part.brand_name + '</td>' +
                '<td>' + part.model_number + '</td>' +
                '<td>' + part.serial_number + '</td>' +
                '<td>' + part.dimensions + '</td>' +
                '</tr>';
            $('#parts_list tbody').append(row);
        });

        $('#addPartModal').modal('hide');
    });

    $('#save-ticket').click(function() {
        var repair_technicians = [];
        $('#repair_technician input:checked').each(function() {
    repair_technicians.push($(this).val());
        });
        
    // Collect ticket data
    var ticketData = {
        orange_tag_id: $('#orange_tag_id').val(),
        ticket_type: $('#ticket_type').val(),
        originator: $('#originator').val(),
        originator_name: $('#originator_name').val(),
        location: $('#location').val(),
        priority: $('#priority').val(),
        line_name: $('#line_name').val(),
        die_number: $('#die_number').val(),
        supervisor: $('#supervisor').val(),
        orange_tag_creation_date: $('#orange_tag_creation_date').val(),
        orange_tag_creation_time: $('#orange_tag_creation_time').val(),
        orange_tag_due_date: $('#orange_tag_due_date').val(),
        repairs_made: $('#repairs_made').val(),
        root_cause: $('#root_cause').val(),
        equipment_down_time: $('#equipment_down_time').val(),
        total_repair_time: $('#total_repair_time').val(),
        area_cleaned: $('#area_cleaned').prop('checked') ? 'on' : 'off',
        follow_up_necessary: $('#follow_up_necessary').prop('checked') ? 'on' : 'off',
        parts_needed: $('#parts_needed').prop('checked') ? 'on' : 'off',
        reviewed_by_supervisor: $('#reviewed_by_supervisor').prop('checked') ? 'on' : 'off',
    reviewed_by_safety_coordinator: $('#reviewed_by_safety_coordinator').prop('checked') ? 'on' : 'off',
        supervisor_review_date: $('#supervisor_review_date').val(),
        safety_coordinator_review_date: $('#safety_coordinator_review_date').val(),
        verified: $('#verified').prop('checked') ? 'on' : 'off',
        date_verified: $('#date_verified').val(),
        location_code: $('#location_code').val(),
        orange_tag_description: $('#orange_tag_description').val(),
        repair_technician: repair_technicians,
        total_cost: $('#total_cost').val(),
        ticket_status: $('#ticket_status').val(),
        date_closed: $('#date_closed').val(),
        work_order_number: $('#work_order_number').val()
       
    };

   // Determine the URL and method for the AJAX request based on whether an orange tag ID is already present
   var orange_tag_id = $('#orange_tag_id').val();
   
    // Make an AJAX request to check if the orange tag already exists
    
    // Send ticket data to the server
    $.ajax({
        url: 'add_ticket.php',
        method: 'POST',
        data: ticketData,
        success: function(response) {
            // Handle the response from the server
            console.log(response);
            $.ajax({
    url: '../configurations/send_email.php', // Replace with the URL of your PHP script for sending emails
    method: 'POST',
    
    data: {
        // Include only the ticket details information from the 'ticket-details' tab
        'technicians[]': repair_technicians,
        orange_tag_id: $('#orange_tag_id').val(),
        ticket_type: $('#ticket_type').val(),
        originator_name: $('#originator_name').val(),
        location: $('#location').val(),
        priority: $('#priority').val(),
        supervisor: $('#supervisor').val(),
        orange_tag_creation_date: $('#orange_tag_creation_date').val(),
        orange_tag_creation_time: $('#orange_tag_creation_time').val(),
        orange_tag_description: $('#orange_tag_description').val()
    },
    success: function(response) {
        // Handle the response from the server
        console.log(response);
       
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX Error:', textStatus, errorThrown);
        console.error('Response Text:', jqXHR.responseText);
    }
});
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Handle any errors
            console.error(textStatus, errorThrown);
        }
    });

    // Send parts data to the server
    $.each(parts, function(i, part) {
        $.ajax({
            url: 'add_part.php', // Replace with the URL of your PHP script for adding a part
            method: 'POST',
            data: part,
            success: function(response) {
                // Handle the response from the server
                console.log(response);
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle any errors
                console.error(textStatus, errorThrown);
            }
        });
    });
    location.reload();
    // Clear the parts array
    parts = [];
});

function setReviewDate(dateFieldId, isChecked) {
    var dateField = document.getElementById(dateFieldId);
    if (isChecked) {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        dateField.value = today;
    } else {
        dateField.value = '';
    }
}
});

$(document).ready(function(){
    $.ajax({
        url: 'fetch_technicians.php', // URL of the PHP file that fetches the technicians
        type: 'get',
        success: function(response) {
            var technicians = response; // response is already a JavaScript object

            // Populate the div with checkboxes for each technician
            // Populate the div with checkboxes for each technician
        $.each(technicians, function(key, technician) {
            $('#repair_technician').append(
                '<input type="checkbox" id="technician_' + technician.id + '" name="repair_technician[]" value="' + technician.id + '">' +
                '<label for="technician_' + technician.id + '">' + technician.username + '</label><br>'
            );
            });
        }
    });
});
$('#newTicketButton').click(function() {
    // Make an AJAX request to get the count of orange tags
    $('#newTicketModal').find('form').each(function() {
    this.reset();
    // Enable all input fields and selection elements in the form
    $('#newTicketModal').find('input, select, textarea').prop('disabled', false);
});

// Uncheck all checkboxes in the modal
$('#newTicketModal').find('input[type=checkbox]').prop('checked', false);
    $.ajax({
        url: 'get_tag_count.php', // Replace with the URL of your PHP script
        method: 'GET',
        success: function(response) {
            var currentUserLocationCode = '<?php echo $_SESSION['location_code']; ?>'; // Assuming the location code of the current user is stored in the session
            currentUserLocationCode = currentUserLocationCode.toUpperCase();

            var newOrangeTagId = currentUserLocationCode + '-' + (parseInt(response));


            // Set the new ID as the value of the orange_tag_id field
            $('#orange_tag_id').val(newOrangeTagId);

            // Check if the tag exists
            $.ajax({
                url: 'check_tag_exists.php', // Replace with the URL of your PHP script
                method: 'GET',
                data: { orange_tag_id: newOrangeTagId },
                success: function(response) {
                    if (response === 'true') {
                        // If the tag exists, hide the "Save" button and show the "Update" button
                        $('#save-ticket').hide();
                        $('#update-ticket').show();
                    } else {
                        // If the tag doesn't exist, show the "Save" button and hide the "Update" button
                        $('#save-ticket').show();
                        $('#update-ticket').hide();
                    }

                    // Open the modal
                    $('#newTicketModal').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle any errors
                    console.error(textStatus, errorThrown);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Handle any errors
            console.error(textStatus, errorThrown);
        }
    });
});
$('#orange_tag_table tbody').on('click', 'tr', function() {
    var orange_tag_id = $(this).children('td').first().text();

// Clear all form fields in the modal
$('#newTicketModal').find('form').each(function() {
    this.reset();
});

// Uncheck all checkboxes in the modal
$('#newTicketModal').find('input[type=checkbox]').prop('checked', false);

// Store the orange_tag_id in a data attribute on the modal
$('#newTicketModal').data('orange_tag_id', orange_tag_id);

// Hide the "Save" button and show the "Update" button
$('#save-ticket').hide();
$('#update-ticket').show();


    // Make an AJAX request to fetch the data
    $.ajax({
        
        url: 'fetch_ticket.php', // Replace with the URL of your PHP script for fetching a ticket
        method: 'GET',
        data: { orange_tag_id: orange_tag_id },
        success: function(response) {
    // The response should be a JavaScript object containing the ticket data
    var ticketData = JSON.parse(response);

    // Fill the form with the ticket data
    $('#orange_tag_id').val(ticketData.orange_tag_id);
    $('#ticket_type').val(ticketData.ticket_type);
    $('#originator').val(ticketData.originator);
    $('#originator_name').val(ticketData.originator_name);
    $('#location').val(ticketData.location);
    $('#priority').val(ticketData.priority);
    $('#line_name').val(ticketData.line_name);
    $('#die_number').val(ticketData.die_number);
    var ticketType = $('#ticket_type').val();
    if (ticketType == 'Die Maintenance') {
        // If the ticket type is "Die Maintenance", show the relevant field
        $('#die_number_group').show();
    } else {
        // Otherwise, hide it
        $('#die_number_group').hide();
    }

    if (ticketType == 'Line Maintenance') {
        // If the ticket type is "Line Maintenance", show the relevant field
        $('#line_name_group').show();
    } else {
        // Otherwise, hide it
        $('#line_name_group').hide();
    }
    $('#supervisor').val(ticketData.supervisor);
    $('#orange_tag_creation_date').val(ticketData.orange_tag_creation_date);
    $('#orange_tag_creation_time').val(ticketData.orange_tag_creation_time);
    $('#orange_tag_due_date').val(ticketData.orange_tag_due_date);
    $('#repairs_made').val(ticketData.repairs_made);
    $('#root_cause').val(ticketData.root_cause);
    $('#equipment_down_time').val(ticketData.equipment_down_time);
    $('#total_repair_time').val(ticketData.total_repair_time);
    $('#area_cleaned').prop('checked', ticketData.area_cleaned === 'on');
    $('#follow_up_necessary').prop('checked', ticketData.follow_up_necessary === 'on');
    $('#parts_needed').prop('checked', ticketData.parts_needed === 'on');
    $('#reviewed_by_supervisor').prop('checked', ticketData.reviewed_by_supervisor === 'on');
if ($('#reviewed_by_supervisor').is(':checked')) {
    $('#supervisor_review_date').show();
} else {
    $('#supervisor_review_date').hide();
}
    $('#reviewed_by_safety_coordinator').prop('checked', ticketData.reviewed_by_safety_coordinator === 'on');
if ($('#reviewed_by_safety_coordinator').is(':checked')) {
    $('#safety_coordinator_review_date').show();
} else {
    $('#safety_coordinator_review_date').hide();
}
    $('#supervisor_review_date').val(ticketData.supervisor_review_date);
    $('#safety_coordinator_review_date').val(ticketData.safety_coordinator_review_date);
    $('#location_code').val(ticketData.location_code);
    $('#verified').prop('checked', ticketData.verified === 'on');
if ($('#verified').is(':checked')) {
    $('#date_verified').show();
} else {
    $('#date_verified').hide();
}

    $('#date_verified').val(ticketData.date_verified);
    $('#orange_tag_description').val(ticketData.orange_tag_description);
    var repair_technicians = ticketData.repair_technician.split(',');
    $.each(repair_technicians, function(index, technician_id) {
     $('#technician_' + technician_id).prop('checked', true);
    });
    $('#total_cost').val(ticketData.total_cost);
    $('#ticket_status').val(ticketData.ticket_status);
    $('#date_closed').val(ticketData.date_closed);
    $('#work_order_number').val(ticketData.work_order_number);
    if (ticketData.ticket_status === 'Closed') {
                // Disable all input fields and selection elements in the form
                $('#newTicketModal').find('input, select, textarea').prop('disabled', true);
                // Hide the "Update Ticket" button
                $('#update-ticket').hide();
            } else {
                // Enable all input fields and selection elements in the form
                $('#newTicketModal').find('input, select, textarea').prop('disabled', false);
                // Show the "Update Ticket" button
                $('#update-ticket').show();
            }
  // Open the modal
  $('#newTicketModal').modal('show');

// Make an AJAX request to fetch the parts associated with this tag
$.ajax({
    url: 'fetch_parts.php',
    method: 'GET',
    data: { orange_tag_id: orange_tag_id },
    success: function(response) {
        // 'response' should be an array of parts associated with the tag

        // Clear the parts table
        $('#parts_list tbody').empty();

        // Parse the JSON string into a JavaScript object
        var parts = JSON.parse(response);

        // Add each part to the table
        $.each(parts, function(i, part) {
            var row = '<tr>' +
                '<td>' + part.date_used + '</td>' +
                '<td>' + part.part_description + '</td>' +
                '<td>' + part.quantity + '</td>' +
                '<td>' + part.brand_name + '</td>' +
                '<td>' + part.model_number + '</td>' +
                '<td>' + part.serial_number + '</td>' +
                '<td>' + part.dimensions + '</td>' +
                '</tr>';
            $('#parts_list tbody').append(row);
        });

        // Toggle the parts form based on whether there are parts
        togglePartsForm($('#parts_needed').is(':checked'), parts);
    },
    error: function(jqXHR, textStatus, errorThrown) {
        // Handle any errors
        console.error(textStatus, errorThrown);
    }
});
},
error: function(jqXHR, textStatus, errorThrown) {
// Handle any errors
console.error(textStatus, errorThrown);
}
});
});


function updateTicket() {
      // Show the loading overlay
      var repair_technicians = [];
$('#repair_technician input:checked').each(function() {
    repair_technicians.push($(this).val());
});
    // Collect ticket data
    var ticketData = {
        orange_tag_id: $('#orange_tag_id').val(),
        ticket_type: $('#ticket_type').val(),
        originator: $('#originator').val(),
        originator_name: $('#originator_name').val(),
        location: $('#location').val(),
        priority: $('#priority').val(),
        line_name: $('#line_name').val(),
        die_number: $('#die_number').val(),
        supervisor: $('#supervisor').val(),
        orange_tag_creation_date: $('#orange_tag_creation_date').val(),
        orange_tag_creation_time: $('#orange_tag_creation_time').val(),
        orange_tag_due_date: $('#orange_tag_due_date').val(),
        repairs_made: $('#repairs_made').val(),
        root_cause: $('#root_cause').val(),
        equipment_down_time: $('#equipment_down_time').val(),
        total_repair_time: $('#total_repair_time').val(),
        area_cleaned: $('#area_cleaned').prop('checked') ? 'on' : 'off',
        follow_up_necessary: $('#follow_up_necessary').prop('checked') ? 'on' : 'off',
        parts_needed: $('#parts_needed').prop('checked') ? 'on' : 'off',
        reviewed_by_supervisor: $('#reviewed_by_supervisor').prop('checked') ? 'on' : 'off',
    reviewed_by_safety_coordinator: $('#reviewed_by_safety_coordinator').prop('checked') ? 'on' : 'off',
        supervisor_review_date: $('#supervisor_review_date').val(),
        safety_coordinator_review_date: $('#safety_coordinator_review_date').val(),
        location_code: $('#location_code').val(),
        verified: $('#verified').prop('checked') ? 'on' : 'off',
        date_verified: $('#date_verified').val(),
        orange_tag_description: $('#orange_tag_description').val(),
        repair_technician: repair_technicians,
        total_cost: $('#total_cost').val(),
        ticket_status: $('#ticket_status').val(),
        date_closed: $('#date_closed').val(),
        work_order_number: $('#work_order_number').val()
       
    };

    // Send ticket data to the server
    $.ajax({
        url: 'update_ticket.php',
        method: 'POST',
        data: ticketData,
        success: function(response) {
            // Handle the response from the server
            console.log(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Handle any errors
            console.error(textStatus, errorThrown);
        }
    });

    // Send parts data to the server
    $.each(parts, function(i, part) {
        $.ajax({
            url: 'add_part.php', // Replace with the URL of your PHP script for updating a part
            method: 'POST',
            data: part,
            success: function(response) {
                // Handle the response from the server
                console.log(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle any errors
                console.error(textStatus, errorThrown);
            }
        });
    });
    location.reload();
    // Clear the parts array
    parts = [];
};

function toggleDateField(dateFieldId, isChecked) {
    var dateField = document.getElementById(dateFieldId);
    dateField.style.display = isChecked ? 'block' : 'none';
}
function viewClosedTickets() {
    // Hide rows where the status is "Open"
    $('#orange_tag_table tr.Open').hide();

    // Show rows where the status is "Closed"
    $('#orange_tag_table tr.Closed').show();
}

function viewOpenTickets() {
    // Hide rows where the status is "Closed"
    $('#orange_tag_table tr.Closed').hide();

    // Show rows where the status is "Open"
    $('#orange_tag_table tr.Open').show();
}

$(document).ready(function() {
    viewOpenTickets();
});


$(document).ready(function() {
    $('#ticket_type').change(function() {
        if ($(this).val() == 'Line Maintenance') {
            $('#line_name_group').show();
        } else {
            $('#line_name_group').hide();
        }
    });
});

$(document).ready(function() {
    $('#priority').change(function() {
        var today = new Date();
        var dueDate;

        switch ($(this).val()) {
            case '1':
                dueDate = today;
                break;
            case '2':
                dueDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 7);
                break;
            case '3':
                dueDate = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
                break;
            default:
                dueDate = today;
        }

        var dd = String(dueDate.getDate()).padStart(2, '0');
        var mm = String(dueDate.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = dueDate.getFullYear();
        dueDate = yyyy + '-' + mm + '-' + dd;

        $('#orange_tag_due_date').val(dueDate);
    });
});

$(document).ready(function() {
    $('#ticket_type').change(function() {
        if ($(this).val() == 'Die Maintenance') {
            $('#die_number_group').show();
        } else {
            $('#die_number_group').hide();
        }
    });
    
});
function viewMyOpenTickets() {
    var currentUsername = <?php echo json_encode($_SESSION['user']); ?>.trim(); // Trim the username
    $('#orange_tag_table tbody tr').each(function() {
        var repairTechniciansHTML = $(this).find('.repair-technician').html(); // Use html() instead of text()
        var technicianUsernames = repairTechniciansHTML.split('<br>'); // Split by '<br>'
        // Trim each username in the array
        technicianUsernames = technicianUsernames.map(function(username) {
            return username.trim();
        });
        var ticketStatus = $(this).find('.ticket-status').text().trim(); // Get the ticket status and trim it

        // Check if the username is included and the ticket status is 'Open'
        if (technicianUsernames.includes(currentUsername) && ticketStatus === 'Open') {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}
$(document).ready(function() {
    $('#ticket_status').change(function() {
        if ($(this).val() == 'Closed') {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;
            $('#date_closed').val(today);
        } else {
            $('#date_closed').val(''); // Clear the date if the ticket is not closed
        }
    });
});

$(document).ready(function() {
    $('.trackable-form').find('input, textarea, select').on('change', function() {
        var formId = $(this).closest('form').attr('id');
        var fieldId = this.id;
        var value = $(this).val();
        var orangeTagId = $('#orange_tag_id').val(); // Get the orange tag ID

        // Pass the orangeTagId to the logChange function
        logChange(formId, fieldId, value, orangeTagId);
    });
});

function logChange(formId, fieldId, value, orangeTagId) {
    var now = new Date();
    var formattedDate = (now.getMonth() + 1).toString().padStart(2, '0') + '/' +
                        now.getDate().toString().padStart(2, '0') + '/' +
                        now.getFullYear().toString() + ' ' +
                        now.getHours().toString().padStart(2, '0') + ':' +
                        now.getMinutes().toString().padStart(2, '0') + ':' +
                        now.getSeconds().toString().padStart(2, '0');

    var data = {
        form_id: formId,
        field_id: fieldId,
        new_value: value,
        orange_tag_id: orangeTagId,
        user: '<?php echo $_SESSION['user']; ?>',
        timestamp: formattedDate // mm/dd/yyyy hh:mm:ss format
    };

    // Send the data to the server using AJAX
    $.ajax({
        url: 'log_change.php',
        type: 'POST',
        data: data,
        success: function(response) {
            console.log('Change logged successfully');
        },
        error: function(xhr, status, error) {
            console.error('Error logging change:', error);
        }
    });
}
function generateReport(reportType) {
    if (reportType === 'openTags') {
        window.open('generate_open_tags_report.php', '_blank');
    }
}
function openTechSelectionModal() {
    // Fetch and populate techs matching the user's location_code
    var locationCode = '<?php echo $_SESSION['location_code']; ?>';
    $.ajax({
        url: 'fetch_technicians.php',
        type: 'GET',
        data: {location_code: locationCode},
        success: function(techs) {
            $('#techSelect').empty();
            techs.forEach(function(tech) {
                $('#techSelect').append(new Option(tech.username, tech.id));
            });
            $('#techSelectionModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching technicians:', textStatus, errorThrown);
        }
    });
}

function generateTechReport() {
    var selectedTechId = $('#techSelect').val();
    window.open('generate_tech_tags_report.php?techId=' + selectedTechId, '_blank');
}

// Modify the existing button's onclick event
document.getElementById('TagsByTech').onclick = openTechSelectionModal;

function generatePriorityReport() {
    var selectedPriority = $('#prioritySelect').val();
    $('#prioritySelectionModal').modal('hide'); // Hide the modal
    window.open('generate_priority_tags_report.php?priority=' + selectedPriority, '_blank');
}
function generateTicketTypeReport() {
    var selectedTicketType = $('#ticketTypeSelect').val();
    $('#ticketTypeSelectionModal').modal('hide'); // Hide the modal
    window.open('generate_by_ticket_type.php?ticketType=' + encodeURIComponent(selectedTicketType), '_blank');
}
$(document).ready(function() {
    function togglePrintButton() {
        var workOrderNumber = $('#work_order_number').val();
        $('#print-workorder').prop('disabled', !workOrderNumber);
    }
    
    function populateTicketData() {
        // Assuming you have a way to get ticket details and repairs/maintenance details
        // For example, you might be fetching this data from the server or from form inputs
        // Here's a placeholder for where that code would go
        ticketDetails = $('#new-ticket-form-ticket-details').serializeArray();
        repairsMaintenanceDetails = $('#new-ticket-form-repairs-maintenance').serializeArray().filter(function(field) {
            return field.name !== 'parts_needed_form' &&
                field.name !== 'date_used' &&
                field.name !== 'part_description' &&
                field.name !== 'quantity' &&
                field.name !== 'brand_name' &&
                field.name !== 'model_number' &&
                field.name !== 'serial_number' &&
                field.name !== 'dimensions';
        });
    }
    
    function printWorkOrder() {
    var workOrderNumber = $('#work_order_number').val();
    if (workOrderNumber) {
        populateTicketData(); // Make sure data is populated

        // Find the line_id field in the ticketDetails array
        var lineIdField = ticketDetails.find(function(field) {
            return field.name === 'Line Name';
        });

        // If the line_id field exists, fetch the line details
        if (lineIdField && lineIdField.value) {
            $.ajax({
                url: 'get_line_details.php', // Replace with the correct path to your PHP script
                type: 'GET',
                data: { line_id: lineIdField.value },
                dataType: 'json',
                success: function(lineDetails) {
                    // Now that we have line details, we can proceed to open the print window
                    openPrintWindow(workOrderNumber, ticketDetails, repairsMaintenanceDetails, lineDetails);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching line details:', textStatus, errorThrown);
                    // Proceed without line details
                    openPrintWindow(workOrderNumber, ticketDetails, repairsMaintenanceDetails, null);
                }
            });
        } else {
            // If there is no line_id, proceed without line details
            openPrintWindow(workOrderNumber, ticketDetails, repairsMaintenanceDetails, null);
        }
    }
}



    
    // Event handler for the "Print Workorder" button
// Event handler for the "Print Workorder" button
$('#print-workorder').on('click', function() {
    var orangeTagId = $('#orange_tag_id').val(); // Get the orange tag ID from the form
    if (orangeTagId) {
        // Create a form element
        var form = $('<form>', {
            'method': 'POST',
            'action': 'print_work_order.php'
        }).append($('<input>', {
            'type': 'hidden',
            'name': 'orange_tag_id',
            'value': orangeTagId
        }));

        // Append the form to the body and submit it
        $(document.body).append(form);
        form.submit();
    } else {
        console.error('No Orange Tag ID provided for printing the workorder.');
    }
});


});
</script>
</body>
</html>