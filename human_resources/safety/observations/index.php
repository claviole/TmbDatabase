<?php
session_start();
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('Human Resources' || 'super-admin')){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}

// Fetch data from the observations table
$query = "
SELECT observations.*, employees.employee_fname, employees.employee_lname 
FROM observations 
JOIN employees ON observations.employee_id = employees.employee_id
ORDER BY observations.observation_date DESC";
$result = mysqli_query($database, $query);

$query = "SELECT employee_id, employee_fname, employee_lname FROM employees";
$employees = mysqli_query($database, $query);
?>

<!-- HTML and CSS code here -->
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
    <title>S.A.F.E.</title>
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
    .table-auto td {
    word-wrap: break-word;
    max-width: 50px;
    
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

    .low-score {
    background-color: #FFCCCC; /* Light red color */
}
    </style>
    
</head>
<body style="background-image: url('../../../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container" style="display: flex; justify-content: space-between; align-items: center;">
<button type="button" class="accident-button" style="background-color: #FFA500; color: black; border: 1px solid black;" id="newObservationButton">New Observation</button>
    

    <a href="../index.php" class="return-button">Return to Safety Menu</a>
</div>
<!-- Table -->
<table class="table-auto w-full">
    <thead>
    <tr>
        <th>Observation ID</th>
        <th>Employee Name</th>
        <th>Observation Score</th>
        <th>Observation Date</th>
        <th>Observation Time</th>
        <th>Observation Description</th>
    </tr>
</thead>
<tbody>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr <?php if ($row['observation_score'] <= 5) echo 'class="low-score"'; ?>>
            <td class="observation-id" data-id="<?= $row['observation_id'] ?>"><?= $row['observation_id'] ?></td>
            <td><?= $row['employee_fname'] . ' ' . $row['employee_lname'] ?></td>
            <td><?= $row['observation_score'] ?></td>
            <td><?= $row['observation_date'] ?></td>
            <td><?= $row['observation_time'] ?></td>
            <td><?= $row['observation_description'] ?></td>
        </tr>
    <?php endwhile; ?>
</tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="newObservationModal" tabindex="-1" role="dialog" aria-labelledby="newObservationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <!-- Rest of your modal content... -->
      <div class="modal-body">
        <form id="newObservationForm" enctype="multipart/form-data">
            <!-- Row 1 -->
            <div class="form-row">
                <div class="form-group col-md-4">
                   <!-- Observation Score -->
<label for="observationScore">Observation Score</label>
<input type="range" id="observationScore" class="form-control" min="1" max="10" step="1" oninput="updateScoreDescription(this.value)">
<p id="scoreDescription"></p>

<script>
function updateScoreDescription(value) {
    var description = '';
    if (value >= 1 && value <= 2) {
        description = 'Negative Major';
    } else if (value >= 3 && value <= 6) {
        description = 'Negative Minor';
    } else if (value >= 7 && value <= 10) {
        description = 'Positive';
    }
    document.getElementById('scoreDescription').innerText = 'Score: ' + value + ' (' + description + ')';
}
</script>
                    <label for="employeeName">Employee Name</label>
<select id="employeeName" class="form-control">
    <?php while ($row = mysqli_fetch_assoc($employees)): ?>
        <option value="<?php echo $row['employee_id']; ?>">
            <?php echo $row['employee_fname'] . ' ' . $row['employee_lname']; ?>
        </option>
    <?php endwhile; ?>
</select>
                    <label for="observationDate">Observation Date</label>
                    <input type="date" id="observationDate" class="form-control">
                    <label for="observationTime">Observation Time</label>
                    <input type="time" id="observationTime" class="form-control">
                </div>
                <div class="form-group col-md-8">
                    <!-- Observation Description -->
                    <label for="observationDescription">Observation Description</label>
                    <textarea id="observationDescription" class="form-control"></textarea>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #6c757d; border-color: #6c757d; color: white;">Close</button>
    <button type="button" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff; color: white;">Submit</button>
</div>
    </div>
  </div>
</div>

<!-- JavaScript code here -->



<script>
document.querySelector('#newObservationButton').addEventListener('click', function (e) {
    e.preventDefault();
    $('#newObservationModal').modal('show');
});

document.querySelector('.btn-primary').addEventListener('click', function (e) {
    e.preventDefault();

    // Check if form is valid
    if (document.querySelector('#newObservationForm').checkValidity()) {
        var formData = new FormData(document.querySelector('#newObservationForm'));

        formData.append('observation_score', document.querySelector('#observationScore').value);
        formData.append('employee_id', document.querySelector('#employeeName').value);
        formData.append('observation_date', document.querySelector('#observationDate').value);
        formData.append('observation_time', document.querySelector('#observationTime').value);
        formData.append('observation_description', document.querySelector('#observationDescription').value);

        // Send form data to server
        fetch('submit_observation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Handle response from server
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: 'Observation submitted successfully!',
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
        $('#newObservationModal').modal('hide');
    } else {
        alert('Please fill out all required fields.');
    }
});
</script>
</body>