<?php
session_start();
include '../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');
// Prepare a parameterized statement


// Check if the user is logged in 
if(!isset($_SESSION['user']) ){
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Travel Approval</title>
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


    <h2 style="display: flex; justify-content: center; align-items: flex-start;"> 
    <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="15%">
    <div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</div>
    </h2>
    <div class="container mx-auto p-4">
        <div class="max-w-2xl mx-auto bg-white p-5 rounded shadow">
            <h2 class="text-2xl font-bold mb-4 text-center">Travel Approval Form</h2>
            <form id="travelApprovalForm" action="submit_travel_approval.php" method="POST">
                <input type="hidden" name="employee_name" value="<?php echo htmlspecialchars($_SESSION['user']); ?>">
                <input type="hidden" name="expense_type" value="Travel Approval">

                <div class="mb-4">
                    <label for="travel_start_date" class="block text-gray-700 text-sm font-bold mb-2">Travel Start Date:</label>
                    <input type="date" id="travel_start_date" name="travel_start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="travel_end_date" class="block text-gray-700 text-sm font-bold mb-2">Travel End Date:</label>
                    <input type="date" id="travel_end_date" name="travel_end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="customer_name" class="block text-gray-700 text-sm font-bold mb-2">Customer Name:</label>
                    <input type="text" id="customer_name" name="customer_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="customer_location" class="block text-gray-700 text-sm font-bold mb-2">Customer Location:</label>
                    <input type="text" id="customer_location" name="customer_location" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="additional_comments" class="block text-gray-700 text-sm font-bold mb-2">Additional Comments:</label>
                    <textarea id="additional_comments" name="additional_comments" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
    
   

    
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
echo "Welcome, " . htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') . "             " . date("m/d/Y") . "<br>";
?>
</div>
</body>
<script>
document.getElementById('travelApprovalForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    const formData = new FormData(this); // 'this' refers to the form

    fetch('submit_travel_approval.php', { // Replace with the actual path to your PHP script
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success'
            }).then((result) => {
                if (result.isConfirmed || result.dismiss) {
                    window.location.href = '../index.php'; // Redirect after closing the alert
                }
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message,
                icon: 'error'
            });
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>
</html>
```