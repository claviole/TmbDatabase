<?php
session_start();
include '../configurations/connection.php'; 
date_default_timezone_set('America/Chicago');
  // Query to get the total count of tickets
  $count_query = "SELECT COUNT(*) as total FROM `orange_tag`";
  $count_result = mysqli_query($database, $count_query);
  $data = mysqli_fetch_assoc($count_result);
  $count = $data['total']+1;

    $tag_author= $_SESSION['user'];

// Fetch the data from the database
$query = "SELECT * FROM employees WHERE job_title IN (14,18,19,22,23,24,25,26,27,31,33,38) ";
$supervisors = mysqli_query($database, $query);

$query = "SELECT * FROM employees WHERE job_title = 25 ";
$maintenance_managers = mysqli_query($database, $query);

$query = "SELECT * FROM employees WHERE job_title = 38 ";
$safety_coordinators = mysqli_query($database, $query);
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
        .accident-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1B145D;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-weight: 700;
        }

        .accident-button:hover {
            background-color: #111;
        }

        .accident-button-container {
            text-align: right;
            margin-right: 10px;
        }
        .table-auto {
    background-color: white;
    border-collapse: separate;
    border-spacing: 0;
    overflow: auto;
    width: 100%;
    height: 50%;
    border: 2px solid #1B145D; /* Add this line to add a border */
}

.table-auto th, .table-auto td {
    border: 1px solid lightgray;
    padding: 8px;
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

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .table-auto tbody tr .accident-id {
    cursor: pointer;
}

.table-auto tbody tr:hover {
    background-color: #e0e0e0;
}
.modal-dialog.custom-modal {
        max-width: 1000px; /* Adjust this value to set the width of your form */
    }
    </style>

    <title>Maintenance Dashboard</title>
    <!-- Add your CSS styles here -->
</head>
<body style="background-image: url('../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container">
    <a href="index.php" class="return-button">Return to Maintenance Home</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
</div>
     
    </h1>
    
    <div class ="flex justify-center">
    <button class="btn btn-primary" data-toggle="modal" data-target="#newTicketModal">New Maintenance Ticket</button>
    <button class="btn btn-secondary" onclick="window.location.href='view_closed.php'">View Closed</button>
    </div>

    <?php
    $query = "SELECT * FROM orange_tag";
    $result = mysqli_query($database, $query);
    ?>
    <div class="scrollable-table">
    <table class="table employee-table">
        <thead>
            <tr>
                <!-- Add your table headers here -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <!-- Add your table data here -->
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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
                        <a class="nav-link" id="repairs-maintenance-tab" data-toggle="tab" href="#repairs-maintenance" role="tab" aria-controls="repairs-maintenance" aria-selected="false">Repairs/Maintenance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="follow-up-tab" data-toggle="tab" href="#follow-up" role="tab" aria-controls="follow-up" aria-selected="false">Follow Up</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="ticket-details" role="tabpanel" aria-labelledby="ticket-details-tab">
    <form id="new-ticket-form-ticket-details">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="orange_tag_id">Orange Tag ID</label>
                    <input type="text" class="form-control" id="orange_tag_id" name="orange_tag_id" value="CH-<?php echo $count; ?>" required readonly>
                </div>
                <div class="form-group">
                    <label for="ticket_type">Ticket Type</label>
                    <select class="form-control" id="ticket_type" name="ticket_type" required>
                        <option value="" selected disabled hidden></option>
                        <option value="Safety">Safety</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Safety Maintenance">Safety Maintenance</option>
                        <option value="Live Maintenance">Live Maintenance</option>
                        <option value="Strategic Comp">Strategic Comp</option>
                        <option value="Die Maintenance">Die Maintenance</option>
                        <option value="Projects/Improvements">Projects/Improvements</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="originator">Originator</label>
                    <input type="text" class="form-control" id="originator" name="originator" value="<?php echo $tag_author; ?>" required readonly>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
                <div class="form-group">
                    <label for="orange_tag_creation_date">Creation Date</label>
                    <input type="date" class="form-control" id="orange_tag_creation_date" name="orange_tag_creation_date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="orange_tag_creation_time">Creation Time</label>
                    <input type="time" class="form-control" id="orange_tag_creation_time" name="orange_tag_creation_time" value="<?php echo date('H:i'); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
    <label for="priority">Priority</label>
    <select class="form-control" id="priority" name="priority" required>
        <option value="" selected disabled hidden></option>
        <option value="1" title="The most serious type of unsafe condition, work practice, or maintenance issue that could cause loss of life, permanent disability, loss of limb, or extensive loss of structure, equipment, or material repair. Replace or use alternative means  of control to protect  employees, or remove from service immediately">1</option>
        <option value="2" title="An unsafe condition, work practice, or maintenance issue that could cause serious injury or damage to structure, equipment, or material repair. Replace or use alternative means of control to protect employees">2</option>
        <option value="3" title="Minor condition, housekeeping issue, unsafe work practice, or maintenance condition that could require no more than first aid or minor damage to structure, equipment, or material. Schedule to repair, replace, or retrain employee.">3</option>
    </select>
</div>
                
                <div class="form-group">
    <label for="supervisor">Supervisor</label>
    <select class="form-control" id="supervisor" name="supervisor" required>
        <option value="" selected disabled hidden></option>
    <?php while ($row = mysqli_fetch_assoc($supervisors)): ?>
        <option value="<?php echo $row['employee_id']; ?>">
            <?php echo $row['employee_fname'] . ' ' . $row['employee_lname']; ?>
        </option>
    <?php endwhile; ?>
    </select>
</div>
<div class="form-group">
    <label for="maintenance_supervisor">Maintenance Supervisor</label>
    <select class="form-control" id="maintenance_supervisor" name="maintenance_supervisor" required>
    <option value="" selected disabled hidden></option>
    <?php while ($row = mysqli_fetch_assoc($maintenance_managers)): ?>
        <option value="<?php echo $row['employee_id']; ?>">
            <?php echo $row['employee_fname'] . ' ' . $row['employee_lname']; ?>
        </option>
    <?php endwhile; ?>
    </select>
</div>
<div class="form-group">
    <label for="safety_coordinator">Safety Coordinator</label>
    <select class="form-control" id="safety_coordinator" name="safety_coordinator" required>
    <option value="" selected disabled hidden></option>
    <?php while ($row = mysqli_fetch_assoc($safety_coordinators)): ?>
        <option value="<?php echo $row['employee_id']; ?>">
            <?php echo $row['employee_fname'] . ' ' . $row['employee_lname']; ?>
        </option>
    <?php endwhile; ?>
    </select>
</div>
                <div class="form-group">
                    <label for="orange_tag_due_date">Due Date</label>
                    <input type="date" class="form-control" id="orange_tag_due_date" name="orange_tag_due_date" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="orange_tag_description">Description</label>
                    <textarea class="form-control" id="orange_tag_description" name="orange_tag_description" rows="3" required></textarea>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="tab-pane fade" id="repairs-maintenance" role="tabpanel" aria-labelledby="repairs-maintenance-tab">
    <form id="new-ticket-form-repairs-maintenance">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="repair_technician">Repair Technician</label>
                <select class="form-control" id="repair_technician" name="repair_technician[]" multiple required></select>
                <button type="button" id="add_technician" class="btn btn-primary mt-2">Add Technician</button>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="repairs_made">Repairs Made</label>
                <textarea class="form-control" id="repairs_made" name="repairs_made" rows="3"></textarea>
            </div>

            <div class="form-group col-md-6">
                <label for="root_cause">Root Cause</label>
                <textarea class="form-control" id="root_cause" name="root_cause" rows="3"></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
            <div class="form-check">
    <input class="form-check-input" type="checkbox" value="No" id="area_cleaned" name="area_cleaned" onchange="this.value = this.checked ? 'Yes' : 'No'">
    <label class="form-check-label" for="area_cleaned">Area Cleaned</label>
</div>

<div class="form-check mt-2">
    <input class="form-check-input" type="checkbox" value="No" id="follow_up_necessary" name="follow_up_necessary" onchange="this.value = this.checked ? 'Yes' : 'No'">
    <label class="form-check-label" for="follow_up_necessary">Follow Up Necessary</label>
</div>

<div class="form-check mt-2">
    <input class="form-check-input" type="checkbox" value="No" id="parts_needed" name="parts_needed" onchange="this.value = this.checked ? 'Yes' : 'No'">
    <label class="form-check-label" for="parts_needed">Parts Needed</label>
</div>
            </div>

            <div class="form-group col-md-3">
        <label for="total_repair_time">Total Repair Time</label>
        <input type="number" step="0.01" class="form-control" id="total_repair_time" name="total_repair_time">
    </div>

    <div class="form-group col-md-3">
        <label for="equipment_down_time">Equipment Down Time</label>
        <input type="number" step="0.01" class="form-control" id="equipment_down_time" name="equipment_down_time">
    </div>
        </div>
    </form>
</div>
                    <div class="tab-pane fade" id="follow-up" role="tabpanel" aria-labelledby="follow-up-tab">
                        <form id="new-ticket-form-follow-up">
                            <!-- Add your review info form fields here -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-ticket">Save Ticket</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#add_technician').click(function(){
        $.ajax({
            url: 'fetch_technicians.php', // URL of the PHP file that fetches the technicians
            type: 'get',
            success: function(response) {
                var technicians = response; // response is already a JavaScript object

                var html = '';
                $.each(technicians, function(key, technician) {
                    html += `<input type="checkbox" id="technician_${technician.employee_id}" value="${technician.employee_id}">${technician.employee_fname} ${technician.employee_lname}<br>`;
                });

                Swal.fire({
                    title: 'Select Technicians',
                    html: html,
                    showCancelButton: true,
                    confirmButtonText: 'Add Technicians',
                    preConfirm: () => {
                        var selectedTechnicians = [];
                        $.each(technicians, function(key, technician) {
                            if ($('#technician_' + technician.employee_id).is(':checked')) {
                                selectedTechnicians.push(technician);
                            }
                        });
                        return selectedTechnicians;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.each(result.value, function(key, technician) {
                            $('#repair_technician').append(new Option(technician.employee_fname + ' ' + technician.employee_lname, technician.employee_id));
                        });
                    }
                });
            }
        });
    });
});
</script>
</body>
</html>