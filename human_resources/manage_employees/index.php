<?php
session_start();
include '../../configurations/connection.php'; 
date_default_timezone_set('America/Chicago');

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

    <title>HR Dashboard</title>
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
        .employee-table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    border: 2px solid #000; /* Add this line */
}

    .employee-table th, .employee-table td {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: left;
    }

    .employee-table th {
        background-color: #34495e;
        color: white;
    }

    .employee-table tr {
        transition: background-color 0.3s ease;
    }

    .employee-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .employee-table tr:nth-child(odd) {
    background-color: #e6e6e6; /* Slightly darker grey for odd rows */
}

    .employee-table tr:hover {
        background-color: #bdc3c7;
    }

    .btn {
        margin-right: 5px;
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        transition: background-color 0.3s ease;
    }

    .btn.edit-btn {
        background-color: #2980b9;
        color: white;
    }

    .btn.edit-btn:hover {
        background-color: #3498db;
    }

    .btn.delete-btn {
        background-color: #c0392b;
        color: white;
    }

    .btn.delete-btn:hover {
        background-color: #e74c3c;
    }
    /* Style for the Bootstrap modal */
    .modal-content {
        font-size: 1.2rem;
        border-radius: 0.5em;
        box-shadow: 0 0.5em 1em rgba(0, 0, 0, 0.15);
        background-color: #f8f9fa;
    }

    /* Style for the Bootstrap modal title */
    .modal-title {
        font-size: 1.8rem;
        color: #343a40;
    }

    /* Style for the Bootstrap modal body */
    .modal-body {
        color: #495057;
    }

    /* Style for the Bootstrap form labels */
    .form-group label {
        font-weight: bold;
    }

    /* Style for the Bootstrap form inputs */
    .form-group input {
        border: 1px solid #ced4da;
        border-radius: 0.25em;
    }

    /* Style for the Bootstrap form select */
    .form-group select {
        border: 1px solid #ced4da;
        border-radius: 0.25em;
    }

    /* Style for the Bootstrap modal buttons */
    .modal-footer .btn {
        font-size: 1.2rem;
        border-radius: 0.25em;
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .modal-footer .btn-primary {
        background-color: #007bff;
        color: #fff;
    }
    .scrollable-table {
    width: 100%;
}

.scrollable-table thead,
.scrollable-table tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}

.scrollable-table tbody {
    display: block;
    overflow-y: auto;
    max-height: 500px; /* Adjust this value according to your needs */
}

.scrollable-table thead {
    width: calc( 100% - 1em ) /* scrollbar is average 1em/16px width, remove it from thead width */
}
    </style>
    
</head>
<body style="background-image: url('../../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Main Menu</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../../images/home_page_company_header_hr.png" alt="company header" width="30%" height="20%" > 
 
 
</div>
     
    </h1>
    
    <div class ="flex justify-center">
    <?php
$query = "SELECT employees.*, job_titles.job_title AS job_title FROM employees JOIN job_titles ON employees.job_title = job_titles.job_title_id";
$result = mysqli_query($database, $query);
?>
<div class="scrollable-table">
<table class="table employee-table">
    <thead>
        <tr>
            <th>Employee ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Date Hired</th>
            <th>First Day of Work</th>
            <th>Job Title</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['employee_id']; ?></td>
                <td><?php echo $row['employee_fname']; ?></td>
                <td><?php echo $row['employee_lname']; ?></td>
                <td><?php echo $row['date_hired']; ?></td>
                <td><?php echo $row['first_day_of_work']; ?></td>
                <td><?php echo $row['job_title']; ?></td>
                <td>
                    <button class="btn btn-primary edit-btn" data-id="<?php echo $row['employee_id']; ?>"><i class="fas fa-pen"></i></button>
                    <button class="btn btn-danger delete-btn" data-id="<?php echo $row['employee_id']; ?>"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>
    </div>
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
    echo "Welcome, " . $_SESSION['user']  ."             ". date("m/d/Y") . "<br>";
    ?>
    <i class="fas fa-cog" id="settings-icon" style="cursor: pointer;"></i>
</div>
<div id="password-change-modal" style="display: none;">
    <form id="password-change-form">
        <label for="current-password">Current Password:</label>
        <input type="password" id="current-password" name="current-password" required>
        <label for="new-password">New Password:</label>
        <input type="password" id="new-password" name="new-password" required>
        <label for="confirm-password">Confirm New Password:</label>
        <input type="password" id="confirm-password" name="confirm-password" required>
        <input type="submit" value="Change Password">
    </form>
</div>

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit-form">
            <div class="form-group">
                <label for="employee_id">Employee ID</label>
                <input type="text" class="form-control" id="employee_id" name="employee_id" readonly>
          <div class="form-group">
            <label for="employee_fname">First Name</label>
            <input type="text" class="form-control" id="employee_fname" name="employee_fname">
          </div>
          <div class="form-group">
            <label for="employee_lname">Last Name</label>
            <input type="text" class="form-control" id="employee_lname" name="employee_lname">
          </div>
          <div class="form-group">
            <label for="date_hired">Date Hired</label>
            <input type="date" class="form-control" id="date_hired" name="date_hired">
          </div>
          <div class="form-group">
            <label for="first_day_of_work">First Day of Work</label>
            <input type="date" class="form-control" id="first_day_of_work" name="first_day_of_work">
          </div>
          <div class="form-group">
            <label for="job_title">Job Title</label>
            <?php
$query = "SELECT * FROM job_titles";
$result = mysqli_query($database, $query);
$job_titles = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<select name="job_title_id" id="job_title">
    <?php foreach ($job_titles as $job_title): ?>
        <option value="<?php echo $job_title['job_title_id']; ?>">
            <?php echo $job_title['job_title']; ?>
        </option>
    <?php endforeach; ?>
</select>
          </div>
          <!-- Add more form fields as needed -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-changes">Save changes</button>
      </div>
    </div>
  </div>
</div>



</body>
    
<script>
function getPasswordChangeForm() {
    return `
        <form id="password-change-form" style="display: flex; flex-direction: column; align-items: center;">
            <label for="current-password" style="margin-top: 10px;">Current Password:</label>
            <input type="password" id="current-password" name="current-password" required style="margin-bottom: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; text-align: center;">
            <label for="new-password" style="margin-top: 10px;">New Password:</label>
            <input type="password" id="new-password" name="new-password" required style="margin-bottom: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; text-align: center;">
            <label for="confirm-password" style="margin-top: 10px;">Confirm New Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required style="margin-bottom: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; text-align: center;">
        </form>
    `;
}
document.getElementById('settings-icon').addEventListener('click', function() {
    Swal.fire({
        title: 'Change Password',
        html: getPasswordChangeForm(),
        showCancelButton: true,
        confirmButtonText: 'Change Password',
        preConfirm: () => {
            var currentPassword = Swal.getPopup().querySelector('#current-password').value;
            var newPassword = Swal.getPopup().querySelector('#new-password').value;
            var confirmPassword = Swal.getPopup().querySelector('#confirm-password').value;

            if (newPassword !== confirmPassword) {
                return Swal.showValidationMessage('New password and confirm password do not match.');
            }

            // Create a FormData object
            var formData = new FormData();
            formData.append('current-password', currentPassword);
            formData.append('new-password', newPassword);

            // Send the current and new password to the server
            return fetch('../../change_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(response => {
                if (response.status === 'error') {
                    throw new Error(response.message);
                }
                return response;
            })
            .catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: result.value.status === 'success' ? 'Success' : 'Error',
                text: result.value.message,
                icon: result.value.status
            });
        }
    });
});
document.querySelectorAll('.edit-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        var id = this.dataset.id;

        // Fetch the employee details from the server
        fetch('get_employee.php?id=' + id)
    .then(response => response.json())
    .then(employee => {
        // Fill the form with the employee details
        document.querySelector('#edit-form [name="employee_id"]').value = employee.employee_id;
        document.querySelector('#edit-form [name="employee_fname"]').value = employee.employee_fname;
        document.querySelector('#edit-form [name="employee_lname"]').value = employee.employee_lname;
        document.querySelector('#edit-form [name="date_hired"]').value = employee.date_hired;
        document.querySelector('#edit-form [name="first_day_of_work"]').value = employee.first_day_of_work;

        // Set the job_title_id value here
        document.querySelector('#edit-form [name="job_title_id"]').value = employee.job_title;

        // Show the modal
        $('#edit-modal').modal('show');
    });
    });
});
document.querySelectorAll('.delete-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        var id = this.dataset.id;

        // Confirm deletion
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send a DELETE request to the server
                fetch('delete_employee.php?id=' + id, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // If the deletion was successful, refresh the page
                        location.reload();
                    } else {
                        // If there was an error, log it to the console
                        console.error('Error:', data.error);
                        location.reload();
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            }
        });
    });
});
document.querySelector('#save-changes').addEventListener('click', function () {
    var employee_id = document.querySelector('#edit-form [name="employee_id"]').value;
    var fname = document.querySelector('#edit-form [name="employee_fname"]').value;
    var lname = document.querySelector('#edit-form [name="employee_lname"]').value;
    var dateHired = document.querySelector('#edit-form [name="date_hired"]').value;
    var firstDayOfWork = document.querySelector('#edit-form [name="first_day_of_work"]').value;
    var jobTitle = document.querySelector('#edit-form [name="job_title_id"]').value;

    Swal.fire({
        title: 'Save Changes',
        text:"Are you ready to save your changes?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, save it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send the updated employee details to the server
            fetch('update_employee.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'employee_id=' + encodeURIComponent(employee_id) +
                      '&employee_fname=' + encodeURIComponent(fname) +
                      '&employee_lname=' + encodeURIComponent(lname) +
                      '&date_hired=' + encodeURIComponent(dateHired) +
                      '&first_day_of_work=' + encodeURIComponent(firstDayOfWork) +
                      '&job_title=' + encodeURIComponent(jobTitle)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status==='success') {
                    // If the update was successful, refresh the page
                    location.reload();
                } else {
                    // If there was an error, log it to the console
                    console.error('Error:', data.error);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });

            // Close the modal
            $('#edit-modal').modal('hide');
        }
    });
});
</script>
</html>