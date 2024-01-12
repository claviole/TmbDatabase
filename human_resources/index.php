<?php
session_start();
include '../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');



// Check if the user is logged in and is an admin
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('Human Resources' || 'super-admin')){
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
            text-align: center;
            margin-right: 10px;
        }
        .button-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        padding: 10px 5px;
    }
    .button-container button {
        padding: 20px;
        font-size: 20px;
        margin: 10px;
        border: 2px solid black;
        width: 90%;
        max-width: 400px;
    }
    @media (min-width: 768px) {
        .button-container button {
            width: calc(100% / 3 - 20px);
        }
    }
        
        
    </style>
    
</head>
<body style="background-image: url('../images/steel_coils.jpg'); background-size: cover;">
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../images/home_page_company_header_hr.png" alt="company header" width="30%" height="20%" > 
 </h1>
 
</div>
     
    
    
<div class="button-container">
    <button class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md" onclick="window.location.href='HR/index.php'">
        <img src="../images/HR.png" alt="HR">
    </button>
    <button class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md" onclick="window.location.href='safety/index.php'">
        <img src="../images/safety.jpg" alt="Safety">
    </button>
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
<?php if ($_SESSION['user_type'] == 'super-admin') { ?>
<div class="return-button-container">
<button style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick="window.location.href='../admin-dashboard/index.php'">Return to Manager Menu</button>
</div>
<?php } ?>
<div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0; right: 0;">
<form action="logout.php" method="post" style="position: absolute; top: 0; right: 0; width: 100px;" class="inline-flex w-full items-center  justify-center rounded-md border border-transparent bg-[#ffffff] px-6 py-4 text-sm font-bold text-black transition-all duration-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
    <input type="submit" value="Log Out">
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
</script>
</html>