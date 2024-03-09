<?php
session_start();
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');
if (isset($_GET['login_token'])) {
    $login_token = $_GET['login_token'];
    if ($login_token === '3c9b806d518f9203dbe50676396765f604dc26ef7caf8bbc56fcbb3a7d7790d881b21f0728dd5fb1') {
        $_SESSION['user_id'] = 49;
        $_SESSION['user'] ="FloorUser SaukVillage";
        $_SESSION['user_type'] = "floor-user";
        $_SESSION['location_code'] = "sv";
    } else if ($login_token === 'c063c534b473855111c63cee7e57c07db06830e38b8e58e54c35b18a8e77c5991d6128c7654448ec') {
        $_SESSION['user_id'] = 50;
        $_SESSION['user'] ="FloorUser NorthVernon";
        $_SESSION['user_type'] = "floor-user";
        $_SESSION['location_code'] = "nv";
    } else if ($login_token === '9cac168b926875fef8d4cbef6892d798c15e00915e307abe0fa87ce06d0e619828a2721a80a74142'){
        $_SESSION['user_id'] = 51;
        $_SESSION['user'] ="FloorUser NewBoston";
        $_SESSION['user_type'] = "floor-user";
        $_SESSION['location_code'] = "nb";
    } else if ($login_token === 'd250d43baa1e2ec60657b130a05306df4b6d99a3b795d5b3b8b002447639be082c79c854a7b313b1'){
        $_SESSION['user_id'] = 52;
        $_SESSION['user'] ="FloorUser FlatRock";
        $_SESSION['user_type'] = "floor-user";
        $_SESSION['location_code'] = "fr";
    } else if ($login_token === 'da7a517a6f0bb2d44cef776d9600ecc6097911eb0451abd14a4947cb3908f74319f662e8ecb70c14'){
        $_SESSION['user_id'] = 53;
        $_SESSION['user'] ="FloorUser Torch";
        $_SESSION['user_type'] = "floor-user";
        $_SESSION['location_code'] = "tc";
    } else if ($login_token === 'b27279399f84074e43c9c5b39d0bc1ef9517d49fc96a568e2d3027749f1586579343344b384b824c'){
        $_SESSION['user_id'] = 54;
        $_SESSION['user'] ="FloorUser Gibraltar";
        $_SESSION['user_type'] = "floor-user";
        $_SESSION['location_code'] = "gb";
    } else if ($login_token === 'dad0af03cf3e5956fa4076a16cfee1243d5c3087afcbb6812806892dea85f6ca2dd2e66fd58f365b'){
        $_SESSION['user_id'] = 55;
        $_SESSION['user'] ="FloorUser Riverview";
        $_SESSION['user_type'] = "floor-user";
        $_SESSION['location_code'] = "riv";
    } 
} else {
    if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('human-resources' || 'super-admin' || 'floor-user')){
    // Not logged in or not an admin, redirect to login page
    header("Location: /index.php");
    exit();
}
}

$current_user_location_code = $_SESSION['location_code']; // Fetch the current user's location code from the session

// Adjust the query to join the observations table with the employees table
// and filter based on the current user's location code
$query = "
SELECT observations.*, employees.employee_fname, employees.employee_lname 
FROM observations 
JOIN employees ON observations.employee_id = employees.employee_id
WHERE employees.location_code = ?
ORDER BY observations.observation_date DESC";
$stmt = $database->prepare($query);
$stmt->bind_param("s", $current_user_location_code);
$stmt->execute();
$result = $stmt->get_result();

$query = "SELECT `employee_id`, `employee_fname`, `employee_lname` FROM `employees` WHERE `location_code` = '$_SESSION[location_code]' ORDER BY `employee_fname` ASC, `employee_lname` ASC";
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

<script>
$(document).ready( function () {
    $('#observation-table').DataTable();
});
</script>
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
            text-align: left;
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

    #observation-table .low-score {
    background-color: #FFCCCC !important; /* Light red color */
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
/* Styling for search input and length selection */
.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select {
    color: #000; /* Black text for inputs and selects */
    padding: 0.25em 0.5em;
}

/* Styling for search label, page counts, next page, previous page */
.dataTables_wrapper .dataTables_filter label,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_length {
    color: #fff !important; /* White text */
}
.dataTables_wrapper .dataTables_paginate .paginate_button.next, 
.dataTables_wrapper .dataTables_paginate .paginate_button.previous{
    color: black !important; /* Black text */
    background-color: #FFA500 !important; /* Orange background */
}
/* Styling for all pagination buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #000 !important; /* Black text */
    background-color: #fff !important; /* White background */
    border: 1px solid #ddd; /* Slight border for definition */
}

/* Styling for the active (current) pagination button */
.dataTables_wrapper .dataTables_paginate .paginate_button.current, 
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    color: #fff !important; /* White text */
    background-color: #666 !important; /* Grey background */
    border-color: #666; /* Matching border color */
}

/* Styling for hover over pagination buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #eee !important; /* Lighter grey on hover */
    border-color: #ddd; /* Slight border color change on hover */
}

    </style>
    
</head>
<body style="background-image: url('<?php echo $backgroundImage; ?>'); background-size: cover;">
<?php if ($_SESSION['user_type'] !== 'floor-user'): ?>
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Safety Menu</a>
</div>
<?php endif; ?>
<h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
    <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="20%">
</h1>
<div class="accident-button-container">
<button type="button" class="accident-button" style="background-color: #FFA500; color: black; border: 1px solid black;" id="newObservationButton">New Observation</button>
</div>
 </h1>

<!-- Table -->
<table class="table-auto w-full " id="observation-table">
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
        <td class="observation-id" data-id="<?= htmlspecialchars($row['observation_id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($row['observation_id'], ENT_QUOTES, 'UTF-8') ?></td>
        <td>
            <?php 
            // Check if the user is a floor-user
            if ($_SESSION['user_type'] == "floor-user") {
                // Obfuscate the name for floor-users
                echo "Confidential";
            } else {
                // Display the full name for other user types
                echo htmlspecialchars($row['employee_fname'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($row['employee_lname'], ENT_QUOTES, 'UTF-8');
            }
            ?>
        </td>
        <td><?= htmlspecialchars($row['observation_score'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['observation_date'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['observation_time'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['observation_description'], ENT_QUOTES, 'UTF-8') ?></td>
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
                    <p class="text-muted">(Select "Unknown" if unsure)</p>
<select id="employeeName" class="form-control">
<?php while ($row = mysqli_fetch_assoc($employees)): ?>
    <option value="<?php echo htmlspecialchars($row['employee_id'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php echo htmlspecialchars($row['employee_fname'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($row['employee_lname'], ENT_QUOTES, 'UTF-8'); ?>
    </option>
<?php endwhile; ?>
</select>
                    <label for="observationDate">Observation Date</label>
                    <input type="date" id="observationDate" class="form-control">
                    <label for="observationTime">Observation Time</label>
                    <input type="time" id="observationTime" class="form-control">
                    <label for="submitter_id">Submitter Employee ID</label>
                    <input type="text" id="submitter_id" class="form-control">

                </div>
                <div class="form-group col-md-8">
                    <!-- Observation Description -->
                    <label for="observationDescription">Observation Description</label>
                    <textarea id="observationDescription" class="form-control"></textarea>
                    <div class="custom-info-message" style="margin-top: 15px; padding: 10px; background-color: #d1ecf1; border-radius: 5px; color: #0c5460;">
    <strong>Note:</strong> Your employee ID is not required for submission, and you may remain anonymous if you prefer. However, if you submit 5 or more appropriate and legitimate observations for the month, you may be entered into a raffle.
</div>
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
$(document).ready(function() {
    $('#observation-table').DataTable({
        destroy: true,
        "drawCallback": function(settings) {
            // Iterate over all rows in the table
            this.api().rows().every(function(rowIdx, tableLoop, rowLoop) {
                var data = this.data();
                // data[2] is the value of the third column (0-based index)
                if (data[2] <= 5) {
                    // Add 'low-score' class to the row
                    $(this.node()).addClass('low-score');
                }
            });
        }
    });
});

$(document).ready(function() {
    // Define the words to look for and their replacements
    var easterEggMap = {
        'bitch': 'female dog',
        'fuck' : 'duck',
        'asshole':'poop schute',
        'bastard': 'The prodical son',
        'brotherfucker':'what kind of weird shit are you into my guy',
        'bullshit':'Cow manure',
        'pedophile': 'I know sometimes these autocorrects are funny, but in this case if you know of any information pertaining to what you just tried to type, please report it directly to HR. Target Metal Blanking & Target Steel wish to provide a safe space for all and will not tolerate those kinds of activities',
        'cock': 'cockadoodledoo',
        'cunt':'The C word',
        'god damn': 'Some blasphemous Talk',
        'dickhead': 'A head of phallic nature',
        'dyke': 'Im just jealous i cant get any',
        'god damn' : 'I shall not use the lords name in vein',
        'horseshit': 'a good idea, i will take it into consideration.',
        'nigga' : 'My friend of African Descent',
        'nigger': 'Racism will not be tolerated. Do better. Its 2023 my guy were all red on the inside.',
        'pussy': 'Im ugly and cant pull at all. Atleast i can admit it',
        'pigfucker':'Swine enthusiast',
        'prick' : 'Love you <3',
        'twat' : 'If you found this message, no one uses that word anymore bro.',
        'wanker': 'Honestly, debated letting that word go unfiltered ',
        'slut' : 'Thats not a nice word',
        'dennis':'speedy gonzales',
        'liz' : 'The Finance Wizard',
        'paula' : 'the sweetest lady at this company,',
        '8675309': 'I see youre a fan of the classics. Hell yea',





        // Add more words and their replacements as needed
    };

    // Listen for input events on all text input boxes and textareas
    $('input[type="text"], textarea').on('input', function() {
        // Get the current value of the input box or textarea
        var currentValue = $(this).val();

        // Split the current value into words
        var words = currentValue.split(/\s+/);

        // Check each word and replace if it matches any of the Easter egg words
        var replacedWords = words.map(function(word) {
            // Check both the original word and lowercase because JavaScript is case-sensitive
            var lowerCaseWord = word.toLowerCase();
            if (easterEggMap.hasOwnProperty(lowerCaseWord)) {
                return easterEggMap[lowerCaseWord];
            }
            return word;
        });

        // Join the words back into a string and update the input box or textarea
        $(this).val(replacedWords.join(' '));
    });
});
</script>
</body>