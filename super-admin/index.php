<?php
session_start();
include '../connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');
$result = $database->query("SELECT COUNT(*) as count FROM invoice WHERE approval_status = 'Awaiting Approval'");
$awaiting_approval_count = $result->fetch_assoc()['count'];



// Check if the user is logged in and is an admin
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('super-admin'|| 'sales')){
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
    </style>
    
</head>
<body style="background-image: url('../images/steel_coils.jpg'); background-size: cover;">

    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
        <?php if ($_SESSION['user_type'] == 'super-admin') { ?>
        <div class="notification<?php echo $awaiting_approval_count > 0 ? ' flashing' : ''; ?>">
    <a href="quote_approvals/quote_approval.php" style="color: inherit; text-decoration: none;">
        Quotes Awaiting Approval: <?php echo $awaiting_approval_count; ?>
    </a>
    <?php } ?>
</div>
     
    </h1>
    
    <div class ="flex justify-center">
    <div class ="flex flex-col justify-content: center  py-10 px-0 "  >
    <button style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md " onclick="startNewQuote()">Start New Quote</button>
        <button style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md " onclick="window.location.href='add_new_customer/add_new_customer.php'">Add New Customer</button>
        <button style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick="window.location.href='lookup_quote/lookup_quote.php'">Look Up Quote</button>
        <?php if ($_SESSION['user_type'] == 'super-admin') { ?>
        <button style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick="window.location.href='award_quotes/award_quotes.php'">Award Quotes</button>
        <button style="width:600px; padding:20px ; font-size: 20px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick="window.location.href='../admin-dashboard/index.php'">Return to Manager Menu</button>
        <?php } ?>
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
<div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0; right: 0;">

<form action="logout.php" method="post" style="position: absolute; top: 0; right: 0; width: 100px;" class="inline-flex w-full items-center  justify-center rounded-md border border-transparent bg-[#ffffff] px-6 py-4 text-sm font-bold text-black transition-all duration-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
    <input type="submit" value="Log Out">
</form>
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
            return fetch('change_password.php', {
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

document.querySelector('button[onclick="window.location.href=\'add_new_customer/add_new_customer.php\'"]').onclick = function() {
    Swal.fire({
    title: 'Add New Customer',
    width:'700px',
    html: `
        <style>
            .swal2-input {
                width: 85% !important;
            }
        </style>
        <input id="swal-input1" class="swal2-input" placeholder="Customer Name">
        <input id="swal-input2" class="swal2-input" placeholder="Customer Address">
        <input id="swal-input3" class="swal2-input" placeholder="Customer City">
        <input id="swal-input4" class="swal2-input" placeholder="Customer State">
        <input id="swal-input5" class="swal2-input" placeholder="Customer Zip">
        <input id="swal-input6" class="swal2-input" placeholder="Customer Phone">
        <input id="swal-input7" class="swal2-input" placeholder="Customer Email">
        <input id="swal-input8" class="swal2-input" placeholder="Customer Contact">
    `,
        preConfirm: () => {
    const values = [
        document.getElementById('swal-input1').value,
        document.getElementById('swal-input2').value,
        document.getElementById('swal-input3').value,
        document.getElementById('swal-input4').value,
        document.getElementById('swal-input5').value,
        document.getElementById('swal-input6').value,
        document.getElementById('swal-input7').value,
        document.getElementById('swal-input8').value
    ];
    // Check if any of the fields are empty
    if (values.some(value => value === '')) {
        Swal.showValidationMessage('Please fill out all fields');
        return;
    }
    return values;
}
    }).then((result) => {
        if (result.isConfirmed) {
            var formData = new FormData();
            formData.append('customerName', result.value[0]);
            formData.append('customerAddress', result.value[1]);
            formData.append('customerCity', result.value[2]);
            formData.append('customerState', result.value[3]);
            formData.append('customerZip', result.value[4]);
            formData.append('customerPhone', result.value[5]);
            formData.append('customerEmail', result.value[6]);
            formData.append('customerContact', result.value[7]);

            fetch('add_new_customer/submit_new_customer.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(response => {
                if (response.status === 'error') {
                    throw new Error(response.message);
                }
                Swal.fire('Success', 'New customer added successfully', 'success');
            })
            .catch(error => {
                Swal.fire('Error', `Request failed: ${error}`, 'error');
            });
        }
    });
};
</script>
</html>