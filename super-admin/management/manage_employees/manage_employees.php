<?php
session_start();
include '../../../connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');
$result = $database->query("SELECT COUNT(*) as count FROM invoice WHERE approval_status = 'Awaiting Approval'");
$awaiting_approval_count = $result->fetch_assoc()['count'];



// Check if the user is logged in and is an admin
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != 'super-admin'){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Manage Employees</title>
    <style>
          .flashing {
        animation: flash .5s linear infinite;
    }
        @keyframes flash {
    0% {background-color: white;}
    50% {background-color: yellow;}
    100% {background-color: white;}
}
    .notification {
        position: absolute;
        top: 0;
        right: 300px;
        padding: 10px;
        background-color: #f2f2f2;
        border: 1px solid #ccc;
        
    }
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
        th {
    position: sticky;
    top: 0;
    background-color: white; /* To ensure the text is readable when scrolling */
    z-index: 1000; /* To ensure it stays on top of other elements */
    cursor: pointer; /* Change cursor to pointer when hovering over the headers */
}

th:hover {
    background-color: #f2f2f2; /* Change background color when hovering over the headers */
}

th.asc::after {
    content: ' ▲'; /* Add an up arrow to indicate ascending sort */
}

th.desc::after {
    content: ' ▼'; /* Add a down arrow to indicate descending sort */
}
    </style>


</head>
<body style="background-image: url('../../../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container">
    <a href="../management.php" class="return-button">Return to Menu</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../../../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
        <div class="notification<?php echo $awaiting_approval_count > 0 ? ' flashing' : ''; ?>">
    <a href="../../quote_approvals/quote_approval.php" style="color: inherit; text-decoration: none;">
        Quotes Awaiting Approval: <?php echo $awaiting_approval_count; ?>
    </a>
</div>
     
    </h1>
    
    <div class="flex justify-center">

    <div style="height: 400px; overflow-y: auto; background-color: white; padding: 20px; border-radius: 10px;">
    <?php
// Fetch employee data from the database
$result = $database->query("SELECT id, username, email, user_type FROM Users");

echo '<table id="employeeTable" class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th onclick="sortTable(0)">First Name</th>';
echo '<th onclick="sortTable(1)">Last Name</th>';
echo '<th onclick="sortTable(2)">Email</th>';
echo '<th onclick="sortTable(3)">User Type</th>';
echo '<th>Action</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

while ($row = $result->fetch_assoc()) {
    list($first_name, $last_name) = explode(' ', $row['username']);
    $email = $row['email'];
    $user_type = $row['user_type'] == 'super-admin' ? 'Manager' : 'Sales';

    echo '<tr>';
    echo '<td>' . $first_name . '</td>';
    echo '<td>' . $last_name . '</td>';
    echo '<td>' . $email . '</td>';
    echo '<td>' . $user_type . '</td>';
    echo '<td><button class="delete-button" data-id="' . $row['id'] . '">🗑️</button></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
?>
    </div>
</div>
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
    echo "Welcome, " . $_SESSION['user']  ."             ". date("m/d/Y") . "<br>";
    ?>
    
</div>
<div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0; right: 0;">

</div>



</body>


<script type="text/javascript">
document.querySelectorAll('.delete-button').forEach(function(button) {
    button.addEventListener('click', function(event) {
        var id = event.target.getAttribute('data-id');

        Swal.fire({
            title: 'Confirm deletion',
            text: 'Are you sure you want to delete this employee?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('delete_employee.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(id)
                })
                .then(response => response.text())
                .then(result => {
                    Swal.fire({
                        title: 'Deletion successful',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                });
            }
        });
    });
});


function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("employeeTable");
    switching = true;
    // Remove the sort direction indicator from all headers
    Array.from(table.getElementsByTagName("th")).forEach(th => th.className = '');
    dir = "asc"; 
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount ++;      
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
    // Add the sort direction indicator to the current sort column
    table.getElementsByTagName("th")[n].className = dir;
}
</script>
</html>