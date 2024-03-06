<?php
session_start();
include '../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');


if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('human-resources' || 'super-admin' ||'supervisor')){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../../index.php");
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
    </style>
    
</head>
<body style="background-image: url('<?php echo $backgroundImage; ?>'); background-size: cover;">
<div class="return-button-container">
    <a href="../../super-admin/index.php" class="return-button">Return to Dashboard</a>
</div>


<h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
    <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="20%">
 
 </h1>
</div>
     
   
    
    <div class ="flex justify-center">
    <div class ="flex flex-col justify-content: center  py-10 px-0 "  >
        <button style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick= "window.location.href='accident_log/index.php'">Accident Log</button>
        <button style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick= "window.location.href='observations/index.php'">Observations</button>
        <button style="width:600px; padding:20px; font-size: 20px; margin-top: 10px; border:2px solid black;" class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md" id="safetyAuditsButton">Safety Audits</button>
    </div>
    </div>
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
echo "Welcome, " . htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') . "             " . date("m/d/Y") . "<br>";
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
            return fetch('../../configurations/change_password.php', {
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
document.getElementById('safetyAuditsButton').addEventListener('click', function() {
    Swal.fire({
        title: 'Safety Audits',
        text: 'Choose an option:',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Start New Audit',
        cancelButtonText: 'View Previous Audits',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'safety_audits/new_audit.php';
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Fetch and display previous audits
            fetch('fetch_previous_audits.php')
                .then(response => response.json())
                .then(audits => {
                    if (audits.length > 0) {
                        const optionsHtml = audits.map(audit => `<option value="${audit.checklist_id}">${audit.date_completed}</option>`).join('');
                        Swal.fire({
                            title: 'Select a Previous Audit',
                            html: `<select id="audit-dropdown" class="swal2-input">${optionsHtml}</select>`,
                            focusConfirm: false,
                            preConfirm: () => {
                                const selectedAuditId = document.getElementById('audit-dropdown').value;
                                downloadAudit(selectedAuditId);
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Download Selected Audit',
                            cancelButtonText: 'Cancel'
                        });
                    } else {
                        Swal.fire('No Previous Audits', 'There are no previous audits to display.', 'info');
                    }
                })
                .catch(error => console.error('Error fetching previous audits:', error));
        }
    });
});

function downloadAudit(checklistId) {
    // Navigate to the download_audit.php script with the checklist_id as a query parameter
    window.location.href = `download_audit.php?checklist_id=${checklistId}`;
}
</script>
</html>