<?php
session_start();
include '../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');
// Prepare a parameterized statement


// Check if the user is logged in 
if(!isset($_SESSION['user']) ){
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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

    </style>
    
</head>
<body style="background-image: url('<?php echo $backgroundImage; ?>'); background-size: cover;">
<div class="return-button-container">
    <a href="../super-admin/index.php" class="return-button">Return to Dashboard</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="20%">

    </h1>
    
    <div class ="flex justify-center">
   
    <div class ="flex flex-col justify-content: center  py-10 px-5 "  >
    <button id="newPurchaseRequestBtn" data-role="super-admin accounts-payable human-resources " style="width:600px; padding:20px; font-size: 20px; margin-top: 10px; border:2px solid black;" class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md">
    New Purchase Request
</button>
<button data-role="super-admin accounts-payable" style="width:600px; padding:20px; font-size: 20px; margin-top: 10px; border:2px solid black;" class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md" data-url="/purchase_requests/manage_expense_requests/index.php">
    Manage Expenses
</button>
</div>
</div>

    
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
echo "Welcome, " . htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') . "             " . date("m/d/Y") . "<br>";
?>
    <i class="fas fa-cog" id="settings-icon" style="cursor: pointer;"></i>
    
    <?php if ($_SESSION['user_type'] == 'super-admin') { ?>
    <div id="location-change-dropdown" style="position: absolute; top: 0; right: -150px;">
        <select id="locationCodeSelect" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <option value="sv" <?php echo ($_SESSION['location_code'] == 'sv') ? 'selected' : ''; ?>>Sauk Village</option>
            <option value="nv" <?php echo ($_SESSION['location_code'] == 'nv') ? 'selected' : ''; ?>>North Vernon</option>
            <option value="nb" <?php echo ($_SESSION['location_code'] == 'nb') ? 'selected' : ''; ?>>New Boston</option>
            <option value="fr" <?php echo ($_SESSION['location_code'] == 'fr') ? 'selected' : ''; ?>>Flatrock</option>
            <option value="tc" <?php echo ($_SESSION['location_code'] == 'tc') ? 'selected' : ''; ?>>Torch</option>
            <option value="gb" <?php echo ($_SESSION['location_code'] == 'gb') ? 'selected' : ''; ?>>Gibraltar</option>
            <option value="riv" <?php echo ($_SESSION['location_code'] == 'riv') ? 'selected' : ''; ?>>Riverview</option>
        </select>
    </div>
<?php } ?>

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
<div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0; right: 0;">


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
            return fetch('../configurations/change_password.php', {
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
document.getElementById('locationCodeSelect').addEventListener('change', function() {
    var newLocationCode = this.value;
    fetch('../configurations/update_location.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'location_code=' + newLocationCode
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire('Location Updated', '', 'success');
            // Optionally refresh the page to reflect changes
            location.reload();
        } else {
            Swal.fire('Error', 'Failed to update location', 'error');   
        }
    })
    .catch(error => console.error('Error:', error));
});
document.querySelectorAll('button').forEach(button => {
    button.addEventListener('click', function(event) {
        var allowedRoles = this.getAttribute('data-role').split(' ');
        var userRole = '<?php echo strtolower($_SESSION['user_type']); ?>'; // Convert user role to lower case

        if (!allowedRoles.includes(userRole)) {
            event.preventDefault();
            // Show Bootstrap alert
            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: 'You do not have permission to access this feature.',
            });
        } else {
            // If user role is allowed, navigate to the page
            var pageUrl = this.getAttribute('data-url');
            if (pageUrl) {
                window.location.href = pageUrl;
            }
        }
    });
});

document.getElementById('newPurchaseRequestBtn').addEventListener('click', function() {
    Swal.fire({
        title: 'Select the request type',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Travel Approval',
        denyButtonText: `Expense Report`,
        cancelButtonText: 'Itemized Expenses',
    }).then((result) => {
        if (result.isConfirmed) {
            // For Travel Approval, gl_code is "NULL"
            window.location.href = `/purchase_requests/new_purchase_request/travel_approval.php?gl_code=NULL`;
        } else if (result.isDenied) {
            // For Expense Report, gl_code is 7920
            window.location.href = `/purchase_requests/new_purchase_request/expense_report.php?gl_code=7920`;
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            initiateItemizedExpenseFlow();
        }
    });
});
var userLocationCode = '<?php echo $_SESSION["location_code"]; ?>';
function initiateItemizedExpenseFlow() {
    const selectedLocationCode = userLocationCode; // Use the global variable directly

// Proceed to fetch the first category based on the user's location code
fetchDropdownData('fetch_first_category.php', {location_code: selectedLocationCode}, 'Select First Category', 'first_cat');
}

function fetchDropdownData(endpoint, data, title, nextStep) {
    fetch(`../configurations/${endpoint}`, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(options => {
        // Remove null or empty keys
        if (options.hasOwnProperty('') && options[''] === null) {
            delete options[''];
        }

        console.log(options); // Debugging line to check the options format

        Swal.fire({
            title: title,
            input: 'select',
            inputOptions: options,
            inputPlaceholder: `Select a ${title.toLowerCase()}`,
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                const selectedValue = result.value;
                console.log(`Selected: ${selectedValue}`); // Debugging line to check the selected value

                if (nextStep === 'location_code') {
                    fetchDropdownData('fetch_first_category.php', {location_code: selectedValue}, 'Select First Category', 'first_cat');
                } else if (nextStep === 'first_cat') {
                    fetchDropdownData('fetch_second_category.php', {first_cat: selectedValue}, 'Select Second Category', 'second_cat');
                } else if (nextStep === 'second_cat') {
                    fetchDropdownData('fetch_names.php', {second_cat: selectedValue}, 'Select Name', 'submit');
                } else if (nextStep === 'submit') {
                    // Submit the form or redirect with the selected GL code
                    window.location.href = `/purchase_requests/new_purchase_request/itemized_expense.php?gl_code=${selectedValue}`;
                }
            }
        });
    });
}


</script>

</html>
```