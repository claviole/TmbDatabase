<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');
// Prepare a parameterized statement
$stmt = $database->prepare("SELECT COUNT(*) as count FROM invoice WHERE approval_status = ?");
// Bind the 'Awaiting Approval' parameter
$stmt->bind_param("s", $approval_status);

// Set the parameter and execute
$approval_status = 'Awaiting Approval';
$stmt->execute();

// Fetch the result
$result = $stmt->get_result();
$awaiting_approval_count = $result->fetch_assoc()['count'];

// Close the statement
$stmt->close();

// Check if the user is logged in 
if(!isset($_SESSION['user']) ){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}?>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Admin Dashboard</title>
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
.swal2-popup {
  font-family: Arial, sans-serif;
  font-size: 1.2rem;
}

.swal2-input, .swal2-textarea {
  font-size: 1rem;
  border: 1px solid #ddd;
  box-shadow: none;
  transition: border-color 0.3s;
}

.swal2-input:focus, .swal2-textarea:focus {
  border-color: #a5dc86;
}

.swal2-confirm {
  background-color: #3085d6;
  color: white;
  border: none;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  font-weight: bold;
}

.swal2-styled.swal2-confirm:focus {
  box-shadow: 0 0 0 3px rgba(48, 133, 214, 0.5);
}

.swal2-cancel {
  color: #555;
}

.swal2-title {
  color: #333;
  font-weight: bold;
}

/* Additional custom styles */
.swal2-input, .swal2-textarea {
  width: 90%; /* Adjust input width */
  margin: 0 auto; /* Center inputs */
}

.swal2-textarea {
  height: 120px; /* Adjust textarea height */
}
    </style>
    
</head>
<body style="background-image: url('<?php echo $backgroundImage; ?>'); background-size: cover;">
<div class="return-button-container">
<button onclick="window.location.href='logout.php';" class="return-button">
    Log Out
</button>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="20%" >
    </h1>
       


    
    <div class ="flex justify-center">
        <!-- 
    <div class ="flex flex-col justify-content: center  py-10 px-0 "  >
     <button data-role="super-admin sales"  style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md " onclick="startNewQuote()">Start New Quote</button> 
    <button data-role="super-admin sales" style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "data-url='lookup_quote/lookup_quote.php'">Look Up Quote</button>
    <button data-role="super-admin" style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md " data-url='award_quotes/award_quotes.php'">Award Quotes</button>

    </div>
    -->
    <div class ="flex flex-col justify-content: center  py-10 px-5 "  >
    <button data-role="super-admin human-resources maintenance-tech floor-user supervisor" style="width:600px; padding:20px; font-size: 20px; margin-top: 10px; border:2px solid black;" class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md" data-url='../maintenance/orange_tag_db.php'>
    S.M.A.R.T Database<br>
    <span style="font-size: 0.75em;">Safety, Maintenance And Repair Tracking</span>
</button>
<button data-role="super-admin human-resources" style="width:600px; padding:20px; font-size: 20px; margin-top: 10px; border:2px solid black;" class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md" data-url='../human_resources/HR/index.php'>
    S.T.A.R.T Database<br>
    <span style="font-size: 0.75em;">Skills Training And Resource Tracking</span>
</button>
<button data-role="super-admin human-resources supervisor" style="width:600px; padding:20px; font-size: 20px; margin-top: 10px; border:2px solid black;" class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md" data-url='../human_resources/safety/index.php'>
    S.A.F.E Database<br>
    <span style="font-size: 0.75em;">Safety Awareness & Follow-up Environment</span>
</button>
<button data-role="super-admin sales human-resources supervisor accounts-payable supervisor" style="width:600px; padding:20px; font-size: 20px; margin-top: 10px; border:2px solid black;" class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md" data-url='../purchase_requests/index.php'>Purchase Requests</button>
    <button data-role="super-admin"  style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;margin-bottom: 10px;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "data-url='../admin-dashboard/management/management.php'>User Management</button>
    <button data-role="super-admin sales human-resources supervisor accounts-payable floor-user maintenance-tech supervisor" id="suggestionButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Have a suggestion or question?</button>

    </div>
    </div>
    
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
echo "Welcome, " . htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') . "             " . date("m/d/Y") . "<br>";
?>
    <i class="fas fa-cog" id="settings-icon" style="cursor: pointer;"></i>
    <i class="fas fa-plane-departure" id="oof-button" style="cursor: pointer; margin-left: 20px;"></i>
    
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
    function startNewQuote() {
    Swal.fire({
        title: 'Select Quote Type',
        input: 'radio',
        inputOptions: {
            'blanking': 'Detail Blanking Quote',
            'quick': 'Quick Blanking Quote'
        },
        inputValidator: (value) => {
            if (!value) {
                return 'You need to choose something!'
            }
        },
        showCancelButton: true, // This will show the cancel button
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value === 'blanking') {
                window.location.href = 'New_Quote/start_new_invoice.php';
            } else if (result.value === 'quick') {
                window.location.href = 'quick_quote/new_quick_quote.php';
            }
        }
    });
}
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
document.addEventListener('DOMContentLoaded', function() {
    var isSuperAdmin = <?php echo ($_SESSION['user_type'] == 'super-admin') ? 'true' : 'false'; ?>;


 if (isSuperAdmin) {
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
}
});

document.getElementById('suggestionButton').addEventListener('click', function() {
    Swal.fire({
        title: 'Submit an Inquiry',
        html: `
            <input type="text" id="suggestionSubject" class="swal2-input" placeholder="Subject" required>
            <textarea id="suggestionDescription" class="swal2-textarea" placeholder="Description" required></textarea>
        `,
        confirmButtonText: 'Submit',
        focusConfirm: false,
        showCancelButton: true, // Adds a cancel button
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const subject = document.getElementById('suggestionSubject').value;
            const description = document.getElementById('suggestionDescription').value;
            if (!subject || !description) {
                Swal.showValidationMessage("Please fill in both the subject and description");
                return false;
            }
            return { subject: subject, description: description };
        }
    }).then((result) => {
        if (result.value) {
            // Send the suggestion via AJAX to a PHP handler
            $.ajax({
                url: '../configurations/submit_suggestion.php', // Adjust the path as necessary
                type: 'POST',
                data: {
                    subject: result.value.subject,
                    description: result.value.description
                },
                success: function(response) {
                    Swal.fire('Submitted!', 'Your suggestion has been submitted.', 'success');
                },
                error: function() {
                    Swal.fire('Error', 'There was a problem submitting your suggestion.', 'error');
                }
            });
        }
    });
});
document.getElementById('oof-button').addEventListener('click', function() {
    fetch('../configurations/get_current_designee.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'user_id=' + '<?php echo $_SESSION['user_id']; ?>' // Assuming user_id is stored in session
    })
    .then(response => response.json())
    .then(data => {
        // Check if data.designee is explicitly "NULL" or empty and adjust accordingly
        const currentDesignee = (data.designee && data.designee !== "NULL") ? data.designee : '';
        const placeholderText = currentDesignee ? '' : 'OOF currently not enabled. Please enter a designee email.';
        Swal.fire({
            title: 'Out of Office Settings',
            html: `
                <input type="email" id="designeeEmail" class="swal2-input" 
                placeholder="${placeholderText}" 
                value="${currentDesignee ? currentDesignee : ''}">
            `,
            showCancelButton: true,
            confirmButtonText: 'Set Designee',
            cancelButtonText: 'Disable Out of Office',
            showDenyButton: true,
            denyButtonText: `Cancel`,
            preConfirm: () => {
                return document.getElementById('designeeEmail').value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Set Designee
                $.ajax({
                    url: '../configurations/set_designee.php',
                    type: 'POST',
                    data: {
                        user_id: '<?php echo $_SESSION['user_id']; ?>',
                        designee_email: result.value
                    },
                    success: function(response) {
                        Swal.fire('Success', 'Designee set successfully.', 'success');
                    },
                    error: function() {
                        Swal.fire('Error', 'There was a problem setting the designee.', 'error');
                    }
                });
            } else if (result.isDismissed && Swal.DismissReason.cancel) {
                // Disable Out of Office
                $.ajax({
                    url: '../configurations/disable_oof.php',
                    type: 'POST',
                    data: {
                        user_id: '<?php echo $_SESSION['user_id']; ?>'
                    },
                    success: function(response) {
                        Swal.fire('Success', 'Out of Office disabled successfully.', 'success');
                    },
                    error: function() {
                        Swal.fire('Error', 'There was a problem disabling Out of Office.', 'error');
                    }
                });
            }
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>
</html>