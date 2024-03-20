<?php
session_start();
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');
if (!isset($_SESSION['user'])) {
    $currentUrl = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    header("Location: ../../../index.php?redirect=$currentUrl");
    exit();
}

// Fetch data from the accident_report table
$query = "
SELECT accident_report.*, employees.employee_fname, employees.employee_lname 
FROM `accident_report` 
JOIN `employees` ON accident_report.employee_id = employees.employee_id";
$result = mysqli_query($database, $query);

$query = "SELECT `employee_id`, `employee_fname`, `employee_lname` FROM `employees` WHERE `location_code` = '$_SESSION[location_code]' ";
$employees = mysqli_query($database, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

    <title>Accident Log</title>
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
h1, h2, h3, p {
    margin: 0 0 15px 0;
    padding: 0;
}

h1 {
    font-size: 24px;
}

h2 {
    font-size: 20px;
}

p {
    font-size: 16px;
}
/* Change DataTables info text color to white */
.dataTables_info {
    color: white !important; /* Use !important to ensure override */
}

/* Change DataTables length control and search box text color to white */
.dataTables_length label,
.dataTables_filter label {
    color: white !important;
}

/* Change DataTables pagination buttons text color to white */
.dataTables_wrapper .dataTables_paginate .paginate_button.next, 
.dataTables_wrapper .dataTables_paginate .paginate_button.previous {
    color: white !important;
}

/* Change DataTables search input text color to white */
.dataTables_filter input {
    color: black; /* Assuming you want the input text to be black for contrast */
    background-color: white; /* White background to see the black text */
}

/* If you want to change the color of the select dropdown text */
.dataTables_length select {
    color: black; /* Text color inside the select box */
    background-color: white; /* Background of the select box */
}




    </style>
    
</head>
<body style="background-image: url('<?php echo $backgroundImage; ?>'); background-size: cover;">
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Safety Menu</a>
</div>
<h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
    <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="20%">
</h1>
<div class="accident-button-container" style="display: flex; justify-content: space-between; align-items: center;">
<button type="button" class="accident-button" style="background-color: #FFA500; color: black; border: 1px solid black;">New Accident</button>
<button class="btn btn-info" data-toggle="modal" data-target="#howToModal" style="font-size: 24px; line-height: 1; padding: 0 10px;">?</button>
</div>
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
                <h6 style="color: #007bff; margin-top: 20px; margin-bottom: 10px;">Creating a New Accident:</h6>
                <p>Click <strong>New Accident</strong> to begin. Then fill out the form completely and click <strong>Submit</strong> to save the accident. </p>

                <h6 style="color: #007bff; margin-top: 20px; margin-bottom: 10px;">View Accident</h6>
                <p>To view a previously created accident, click on the accident ID in the table. This will open a modal with all the details of the accident. </p>

                <h6 style="color: #007bff; margin-top: 20px; margin-bottom: 10px;">Editing accidents</h6>
                <p>To edit previous accidents, click on the edit button of the row of the accident that you wish to edit.</p>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<table id="accidentTable" class="table-auto w-full">
    
    <thead>
    <tr>
        <th>Accident ID</th>
        <th>Employee Name</th>
        <th>Accident Type</th>
        <th>Date Added</th>
        <th>Accident Date</th>
        <th>Accident Time</th>
        <th>Shift</th>
        <th>Accident Location</th>
        <th>Edit</th>
    </tr>
</thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td class="accident-id" data-id="<?= htmlspecialchars($row['accident_id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($row['accident_id'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['employee_fname'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($row['employee_lname'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['accident_type'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['date_added'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['accident_date'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['accident_time'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['shift'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['accident_location'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><button class="btn btn-info edit-accident-button" data-id="<?= htmlspecialchars($row['accident_id'], ENT_QUOTES, 'UTF-8') ?>">Edit</button></td>
    </tr>
<?php endwhile; ?>
</tbody>
</table>
<!-- Modal -->
<div class="modal fade" id="accidentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="accidentDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <!-- Modal content will be loaded here from the server -->
    </div>
  </div>
</div>
<div class="modal fade" id="newAccidentModal" tabindex="-1" role="dialog" aria-labelledby="newAccidentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <!-- Rest of your modal content... -->
      <div class="modal-body">
        <form id="newAccidentForm" enctype="multipart/form-data">
            <!-- Row 1 -->
            <div class="form-row">
                <div class="form-group col-md-4">
                    <!-- Accident Type, Employee Name, Current Date -->
                    <label for="accidentType">Accident Type</label>
                    <input type="hidden" name="accident_id" id="accidentId">
                    <select id="accidentType" class="form-control">
                        <option>Near Miss</option>
                        <option>Property Damage</option>
                        <option>Equipment Damage</option>
                        <option>Injury</option>
                    </select>
                    <label for="employeeName">Employee Name</label>
                    <select id="employeeName" class="form-control">
                    <?php while ($row = mysqli_fetch_assoc($employees)): ?>
    <option value="<?php echo htmlspecialchars($row['employee_id'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php echo htmlspecialchars($row['employee_fname'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($row['employee_lname'], ENT_QUOTES, 'UTF-8'); ?>
    </option>
<?php endwhile; ?>
</select>
<input type="text" id="nonEmployeeName" class="form-control" style="display: none;">
                    <label for="currentDate">Current Date</label>
                    <input type="date" id="currentDate" class="form-control" readonly>
                </div>
                <div class="form-group col-md-4">
                    <!-- Shift, Accident Date, Accident Time, Time Sent to Clinic -->
                    <label for="shift">Shift</label>
                    <select id="shift" class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                    </select>
                    <label for="accidentDate">Accident Date</label>
                    <input type="date" id="accidentDate" class="form-control">
                    <label for="accidentTime">Accident Time</label>
                    <input type="time" id="accidentTime" class="form-control">
                    <label for="timeSentToClinic">Time Sent to Clinic</label>
                    <input type="time" id="timeSentToClinic" class="form-control">
                    <label for="dateSentToClinic">Date Sent to Clinic</label>
                    <input type="date" id="dateSentToClinic" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <!-- Consecutive Days Worked, Accident Location, Time of Report, Shift Start Time -->
                    <label for="consecutiveDaysWorked">Consecutive Days Worked</label>
                    <input type="number" id="consecutiveDaysWorked" class="form-control">
                    <label for="accidentLocation">Accident Location</label>
                    <input type="text" id="accidentLocation" class="form-control">
                    <label for="timeOfReport">Time of Report</label>
                    <input type="time" id="timeOfReport" class="form-control">
                    <label for="shiftStartTime">Shift Start Time</label>
                    <input type="time" id="shiftStartTime" class="form-control">
                </div>
            </div>

            <!-- Row 2 -->
            <div class="form-row">
                <div class="form-group col-md-12">
                    <!-- Accident Description -->
                    <label for="accidentDescription">Accident Description</label>
                    <textarea id="accidentDescription" class="form-control"></textarea>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="form-row">
            <div class="form-group col-md-4">
   <!-- Proper PPE Worn -->
<label><input type="checkbox" id="ppeWorn" name="ppe_worn"> Proper PPE Worn?</label>
<textarea id="ppeExplanation" name="ppe_explanation" class="form-control" ></textarea>

</div>
<div class="form-group col-md-4">
   <!-- Employee Following Procedure -->
<label><input type="checkbox" id="properProcedure" name="proper_procedure"> Proper Procedure Followed?</label>
<textarea id="procedureExplanation" name="procedure_explanation" class="form-control" ></textarea>
</div>
                <div class="form-group col-md-4">
                    <!-- Potential Severity -->
                    <label for="potentialSeverity">Potential Severity</label>
                    <select id="potentialSeverity" class="form-control">
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                    </select>
                    <textarea id="severityExplanation" class="form-control"></textarea>
                </div>
            </div>

            <!-- File Upload -->
            <div class="form-group">
                <label for="fileUpload">Upload File</label>
                <input type="file" class="form-control-file" id="fileUpload" name="fileUpload[]" multiple>
            </div>

             <!-- Environmental Impact -->
<label><input type="checkbox" id="enverionmentalImpact" name="enverionmental_impact"> Environmental Impact</label>
<textarea id="enverionmentalImpactExplain" name="enverionmental_impact_explain" class="form-control" style="display: none;"></textarea>
<br>
                        <!-- Prevent Reoccurrence -->
                        <label for="preventReoccurance">Prevent Reoccurrence</label>
                        <textarea id="preventReoccurance" name="prevent_reoccurance" class="form-control"></textarea>

                        <!-- Immediate Corrective Action -->
                        <label for="immediateCorrectiveAction">Immediate Corrective Action</label>
                        <textarea id="immediateCorrectiveAction" name="immediate_corrective_action" class="form-control"></textarea>


                        <!-- IRP Names -->
                      <!-- IRP Required -->
<label><input type="checkbox" id="irpRequired" name="irp_required"> IRP Required</label>
<textarea id="irpNames" name="irp_names" class="form-control" style="display: none;"></textarea>
<br>
                       <!-- Equipment Out of Service -->
<label><input type="checkbox" id="equipOutOfService" name="equip_out_of_service"> Equipment Out of Service</label>
<textarea id="equipOutOfServiceExplain" name="equip_out_of_service_explain" class="form-control" style="display: none;"></textarea>
        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #6c757d; border-color: #6c757d; color: white;">Close</button>
<div id="submitBtnContainer" style="display: none;">
    <button name="submitbtn" id="submitbtn" type="button" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff; color: white;">Submit</button>
</div>
<div id="updateBtnContainer" style="display: none;">
    <button name="updatebtn" id="updatebtn" type="button" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff; color: white;" onclick="updateAccident();">Update</button>
</div>
</div>
  </div>
</div>
<script>
window.onload = function() {
    var currentDate = new Date().toISOString().substring(0, 10);
    document.getElementById('currentDate').value = currentDate;
}
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.accident-button').addEventListener('click', function (e) {
    e.preventDefault();
    //clear form
    document.getElementById('newAccidentForm').reset();
    $('#newAccidentModal').modal('show');
    document.getElementById('updateBtnContainer').style.display = 'none';
    document.getElementById('submitBtnContainer').style.display = 'block';
});
});

var isSubmitting = false;

document.querySelector('.btn-primary').addEventListener('click', function (e) {
    e.preventDefault();

    // Check if form is valid
    if (document.querySelector('#newAccidentForm').checkValidity()) {
        isSubmitting = true;
        var formData = new FormData(document.querySelector('#newAccidentForm'));

        // Append form data...
        
formData.append('employee_id', document.querySelector('#employeeName').value);
formData.append('accident_type', document.querySelector('#accidentType').value);
formData.append('date_added', document.querySelector('#currentDate').value);
formData.append('accident_date', document.querySelector('#accidentDate').value);
formData.append('accident_time', document.querySelector('#accidentTime').value);
formData.append('non_employee_name', document.querySelector('#nonEmployeeName').value);
formData.append('shift', document.querySelector('#shift').value);
formData.append('time_sent_to_clinic', document.querySelector('#timeSentToClinic').value);
formData.append('accident_location', document.querySelector('#accidentLocation').value);
formData.append('time_of_report', document.querySelector('#timeOfReport').value);
formData.append('shift_start_time', document.querySelector('#shiftStartTime').value);
formData.append('accident_description', document.querySelector('#accidentDescription').value);
formData.append('consecutive_days_worked', document.querySelector('#consecutiveDaysWorked').value);
formData.append('proper_ppe_used', document.querySelector('#ppeWorn').checked ? 'yes' : 'no');
formData.append('proper_ppe_used_explain', document.querySelector('#ppeExplanation').value);
formData.append('procedure_followed', document.querySelector('#properProcedure').checked ? 'yes' : 'no');
formData.append('procedure_followed_explain', document.querySelector('#procedureExplanation').value);
formData.append('potential_severity', document.querySelector('#potentialSeverity').value);
formData.append('potential_severity_explain', document.querySelector('#severityExplanation').value);
formData.append('enverionmental_impact', document.querySelector('#enverionmentalImpact').checked ? 'yes' : 'no');
formData.append('enverionmental_impact_explain', document.querySelector('#enverionmentalImpactExplain').value);
formData.append('prevent_reoccurance', document.querySelector('#preventReoccurance').value);
formData.append('immediate_corrective_action', document.querySelector('#immediateCorrectiveAction').value);
formData.append('irp_required', document.querySelector('#irpRequired').checked ? 'yes' : 'no');
formData.append('irp_names', document.querySelector('#irpNames').value);
formData.append('equip_out_of_service', document.querySelector('#equipOutOfService').checked ? 'yes' : 'no');
formData.append('equip_out_of_service_explain', document.querySelector('#equipOutOfServiceExplain').value);
formData.append('date_sent_to_clinic', document.querySelector('#dateSentToClinic').value);

        // Send form data to server
        fetch('submit_form.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Handle response from server
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: 'Submission successful!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                console.error(data.message);
            }
        })
        .catch(error => {
            // Handle error
            console.error(error);
        });

        // Close the modal
        $('#newAccidentModal').modal('hide');
    } else {
        alert('Please fill out all required fields.');
    }
});

        $('#newAccidentModal').on('hide.bs.modal', function (e) {
            if (!isSubmitting && !confirm('Are you sure you want to close this form?')) {
                e.preventDefault();
            }
        });

        $('#newAccidentModal').on('hidden.bs.modal', function (e) {
            isSubmitting = false;
        });

        document.querySelector('#enverionmentalImpact').addEventListener('change', function () {
    document.querySelector('#enverionmentalImpactExplain').style.display = this.checked ? 'block' : 'none';
});


document.querySelector('#equipOutOfService').addEventListener('change', function () {
    document.querySelector('#equipOutOfServiceExplain').style.display = this.checked ? 'block' : 'none';
});

document.querySelector('#irpRequired').addEventListener('change', function () {
    document.querySelector('#irpNames').style.display = this.checked ? 'block' : 'none';
});     
window.onload = function() {
    var currentDate = new Date().toISOString().substring(0, 10);
    document.getElementById('currentDate').value = currentDate;
}

document.querySelector('#ppeWorn').addEventListener('change', function () {
    document.querySelector('#ppeExplanation').style.display = this.checked ? 'none' : 'block';
});

document.querySelector('#properProcedure').addEventListener('change', function () {
    document.querySelector('#procedureExplanation').style.display = this.checked ? 'none' : 'block';
});

document.querySelectorAll('.accident-id').forEach(function(element) {
    element.addEventListener('click', function(e) {
        e.preventDefault();
        var accidentId = this.getAttribute('data-id');
        fetch('get_accident_details.php?id=' + accidentId)
            .then(response => response.text())
            .then(data => {
                document.querySelector('#accidentDetailsModal .modal-content').innerHTML = data;
                $('#accidentDetailsModal').modal('show');
            });
    });
});
 // Automatically open the accident details modal if an accident_id is present in the URL
 const urlParams = new URLSearchParams(window.location.search);
    const accidentId = urlParams.get('accident_id');
    if (accidentId) {
        fetchAccidentDetailsAndShowModal(accidentId);
    }

    // Function to fetch accident details and show the modal
    function fetchAccidentDetailsAndShowModal(accidentId) {
        fetch('get_accident_details.php?id=' + accidentId)
            .then(response => response.text())
            .then(data => {
                document.querySelector('#accidentDetailsModal .modal-content').innerHTML = data;
                $('#accidentDetailsModal').modal('show');
            })
            .catch(error => console.error('Error fetching accident details:', error));
    }

    // Attach click event listeners to elements with class 'accident-id'
    document.querySelectorAll('.accident-id').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            var accidentId = this.getAttribute('data-id');
            fetchAccidentDetailsAndShowModal(accidentId);
        });
    });
document.querySelector('#employeeName').addEventListener('change', function () {
    var selectedText = this.options[this.selectedIndex].text;
    document.querySelector('#nonEmployeeName').style.display = selectedText === 'non employee' ? 'block' : 'none';
});
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-accident-button').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var accidentId = this.getAttribute('data-id');
            fetchAccidentDetailsAndPopulateModal(accidentId);

            // Debugging: Ensure we're getting the correct elements
            console.log(document.getElementById('updateBtnContainer'));
            console.log(document.getElementById('submitBtnContainer'));

            // Attempt to show and hide the respective buttons
            var updateBtnContainer = document.getElementById('updateBtnContainer');
            var submitBtnContainer = document.getElementById('submitBtnContainer');
            if (updateBtnContainer && submitBtnContainer) {
                updateBtnContainer.style.display = 'block';
                submitBtnContainer.style.display = 'none';
            } else {
                console.error('One of the containers was not found.');
            }
        });
    });
});

function fetchAccidentDetailsAndPopulateModal(accidentId) {
    fetch('get_accident.php?id=' + accidentId)
        .then(response => response.json()) // Assuming the response is JSON
        .then(data => {
            // Assuming 'data' is the object containing the accident details
            const accidentDetails = data.data; // Adjust according to your actual response structure

            // Populate the form fields
            document.querySelector('#accidentType').value = accidentDetails.accident_type || '';
            document.querySelector('#accidentId').value = accidentDetails.accident_id || '';
            document.querySelector('#employeeName').value = accidentDetails.employee_id || '';
            document.querySelector('#nonEmployeeName').value = accidentDetails.non_employee_name || '';
            document.querySelector('#currentDate').value = accidentDetails.date_added || '';
            document.querySelector('#accidentDate').value = accidentDetails.accident_date || '';
            document.querySelector('#accidentTime').value = accidentDetails.accident_time || '';
            document.querySelector('#shift').value = accidentDetails.shift || '';
            document.querySelector('#timeSentToClinic').value = accidentDetails.time_sent_to_clinic || '';
            document.querySelector('#accidentLocation').value = accidentDetails.accident_location || '';
            document.querySelector('#timeOfReport').value = accidentDetails.time_of_report || '';
            document.querySelector('#shiftStartTime').value = accidentDetails.shift_start_time || '';
            document.querySelector('#accidentDescription').value = accidentDetails.accident_description || '';
            document.querySelector('#consecutiveDaysWorked').value = accidentDetails.consecutive_days_worked || '';
            document.querySelector('#ppeWorn').checked = accidentDetails.proper_ppe_used === 'yes';
            document.querySelector('#ppeExplanation').value = accidentDetails.proper_ppe_used_explain || '';
            document.querySelector('#properProcedure').checked = accidentDetails.procedure_followed === 'yes';
            document.querySelector('#procedureExplanation').value = accidentDetails.procedure_followed_explain || '';
            document.querySelector('#potentialSeverity').value = accidentDetails.potential_severity || '';
            document.querySelector('#severityExplanation').value = accidentDetails.potential_severity_explain || '';
            document.querySelector('#enverionmentalImpact').checked = accidentDetails.environmental_impact === 'yes'; // Corrected typo
            document.querySelector('#enverionmentalImpactExplain').value = accidentDetails.environmental_impact_explain || '';
            document.querySelector('#preventReoccurance').value = accidentDetails.prevent_reoccurance || '';
            document.querySelector('#immediateCorrectiveAction').value = accidentDetails.immediate_corrective_action || '';
            document.querySelector('#irpRequired').checked = accidentDetails.irp_required === 'yes';
            document.querySelector('#irpNames').value = accidentDetails.irp_names || '';
            document.querySelector('#equipOutOfService').checked = accidentDetails.equip_out_of_service === 'yes';
            document.querySelector('#equipOutOfServiceExplain').value = accidentDetails.equip_out_of_service_explain || '';
            document.querySelector('#dateSentToClinic').value = accidentDetails.date_sent_to_clinic || '';

            // Show the modal
            $('#newAccidentModal').modal('show');
        })
        .catch(error => console.error('Error fetching accident details:', error));
}
function updateAccident() {
    var accidentId = document.querySelector('#accidentId').value;
var formData = new FormData(document.querySelector('#newAccidentForm'));
formData.append('employee_id', document.querySelector('#employeeName').value);
formData.append('accident_type', document.querySelector('#accidentType').value);
formData.append('date_added', document.querySelector('#currentDate').value);
formData.append('accident_date', document.querySelector('#accidentDate').value);
formData.append('accident_time', document.querySelector('#accidentTime').value);
formData.append('non_employee_name', document.querySelector('#nonEmployeeName').value);
formData.append('shift', document.querySelector('#shift').value);
formData.append('time_sent_to_clinic', document.querySelector('#timeSentToClinic').value);
formData.append('accident_location', document.querySelector('#accidentLocation').value);
formData.append('time_of_report', document.querySelector('#timeOfReport').value);
formData.append('shift_start_time', document.querySelector('#shiftStartTime').value);
formData.append('accident_description', document.querySelector('#accidentDescription').value);
formData.append('consecutive_days_worked', document.querySelector('#consecutiveDaysWorked').value);
formData.append('proper_ppe_used', document.querySelector('#ppeWorn').checked ? 'yes' : 'no');
formData.append('proper_ppe_used_explain', document.querySelector('#ppeExplanation').value);
formData.append('procedure_followed', document.querySelector('#properProcedure').checked ? 'yes' : 'no');
formData.append('procedure_followed_explain', document.querySelector('#procedureExplanation').value);
formData.append('potential_severity', document.querySelector('#potentialSeverity').value);
formData.append('potential_severity_explain', document.querySelector('#severityExplanation').value);
formData.append('enverionmental_impact', document.querySelector('#enverionmentalImpact').checked ? 'yes' : 'no');
formData.append('enverionmental_impact_explain', document.querySelector('#enverionmentalImpactExplain').value);
formData.append('prevent_reoccurance', document.querySelector('#preventReoccurance').value);
formData.append('immediate_corrective_action', document.querySelector('#immediateCorrectiveAction').value);
formData.append('irp_required', document.querySelector('#irpRequired').checked ? 'yes' : 'no');
formData.append('irp_names', document.querySelector('#irpNames').value);
formData.append('equip_out_of_service', document.querySelector('#equipOutOfService').checked ? 'yes' : 'no');
formData.append('equip_out_of_service_explain', document.querySelector('#equipOutOfServiceExplain').value);
formData.append('date_sent_to_clinic', document.querySelector('#dateSentToClinic').value);
    // Send form data to server for update
    fetch('update_accident.php?id=' + encodeURIComponent(accidentId), {
    method: 'POST',
    body: formData
})
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('Update Successful', '', 'success').then(() => {
                location.reload(); // Reload the page to reflect the changes
            });
        } else {
            Swal.fire('Error updating accident', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error updating accident:', error);
    });
}
$(document).ready(function() {
    // Initialize the DataTable
    var table = $('#accidentTable').DataTable({
        // ... other DataTable options ...
        "order": [[0, 'desc']],
        "initComplete": function(settings, json) {
            // HTML for the year filter dropdown
            var yearFilterHtml = '<label for="yearFilter">Year: <select id="yearFilter" class="form-control"><option value="">Select Year</option></select></label>';

            // Insert the year filter dropdown next to the DataTable's "Show # entries" dropdown
            $(yearFilterHtml).appendTo(".dataTables_length");

            // Populate year filter dropdown based on the data in the accident date column
            this.api().columns(4).every(function() {
                var column = this;
                var select = $('#yearFilter');
                column.data().unique().sort().each(function(d, j) {
                    var year = d.split('-')[0]; // Extract year from date
                    if (select.find('option[value="' + year + '"]').length === 0) {
                        select.append('<option value="' + year + '">' + year + '</option>');
                    }
                });
            });
        }
    });

    // Custom search function to filter by year
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var selectedYear = $('#yearFilter').val();
        var accidentDate = data[4]; // Use the correct column index for the accident date
        if (selectedYear) {
            var year = accidentDate.split('-')[0];
            return year === selectedYear;
        }
        return true; // Show all rows if no year is selected
    });

    // Event handler for when the year filter changes
    $('#yearFilter').on('change', function() {
        table.draw(); // Redraw the table to apply the search filter
    });
});
    </script>
</body>
</html>