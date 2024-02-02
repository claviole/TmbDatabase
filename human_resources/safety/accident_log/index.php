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
FROM accident_report 
JOIN employees ON accident_report.employee_id = employees.employee_id";
$result = mysqli_query($database, $query);

$query = "SELECT employee_id, employee_fname, employee_lname FROM employees WHERE location_code = '$_SESSION[location_code]' ";
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
    </style>
    
</head>
<body style="background-image: url('../../../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container" style="display: flex; justify-content: space-between; align-items: center;">
<button type="button" class="accident-button" style="background-color: #FFA500; color: black; border: 1px solid black;">New Accident</button>
    

    <a href="../index.php" class="return-button">Return to Safety Menu</a>
</div>
<table class="table-auto w-full">
    
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
    </tr>
</thead>
<tbody>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td class="accident-id" data-id="<?= $row['accident_id'] ?>"><?= $row['accident_id'] ?></td>
            <td><?= $row['employee_fname'] . ' ' . $row['employee_lname'] ?></td>
            <td><?= $row['accident_type'] ?></td>
            <td><?= $row['date_added'] ?></td>
            <td><?= $row['accident_date'] ?></td>
            <td><?= $row['accident_time'] ?></td>
            <td><?= $row['shift'] ?></td>
            <td><?= $row['accident_location'] ?></td>
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
                    <select id="accidentType" class="form-control">
                        <option>Near Miss</option>
                        <option>Property Damage</option>
                        <option>Equipment Damage</option>
                        <option>Injury</option>
                    </select>
                    <label for="employeeName">Employee Name</label>
                    <select id="employeeName" class="form-control">
    <?php while ($row = mysqli_fetch_assoc($employees)): ?>
        <option value="<?php echo $row['employee_id']; ?>">
            <?php echo $row['employee_fname'] . ' ' . $row['employee_lname']; ?>
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
    <button type="button" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff; color: white;">Submit</button>
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
    document.querySelector('.accident-button').addEventListener('click', function (e) {
    e.preventDefault();
    $('#newAccidentModal').modal('show');
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
    </script>
</body>
</html>