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
   /* Custom styles for the entry container */
   .entry {
        background-color: #f9f9f9; /* Light grey background */
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    /* Style for individual input elements */
    .entry input[type="text"] {
        background-color: #ffffff; /* White background */
        border: 1px solid #ddd; /* Light grey border */
        border-radius: 4px;
        padding: 8px 12px;
        width: calc(50% - 16px); /* Adjust width as needed, accounting for padding */
        margin-right: 8px; /* Space between inputs */
    }

    /* Style for the "Add Another" button */
    #addAnother {
        background-color: #4CAF50; /* Green background */
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #addAnother:hover {
        background-color: #45a049; /* Darker green on hover */
    }
    </style>
    
</head>
<body style="background-image: url('<?php echo $backgroundImage; ?>'); background-size: cover;">
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</div>

    <h2 style="display: flex; justify-content: center; align-items: flex-start;"> 
    <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="15%">
    
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

                <div id="entriesContainer" class="mb-4">
    <div class="entry p-4 bg-gray-100 rounded shadow-md">
        <label class="block">Customer Name:</label>
        <input type="text" name="customer_name[]" class="mt-1 p-2 w-full border rounded" required>
        <label class="block mt-2">Customer Location:</label>
        <input type="text" name="customer_location[]" class="mt-1 p-2 w-full border rounded" required>
    </div>
</div>
<button type="button" id="addAnother" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Another</button>

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
    e.preventDefault();
    const formData = new FormData(this);

    fetch('submit_travel_approval.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({
            title: data.status === 'success' ? 'Success!' : 'Error!',
            text: data.message,
            icon: data.status
        }).then(() => {
            if (data.status === 'success') {
                window.location.href = '../index.php'; // Redirect on success
            }
        });
    })
    .catch(error => console.error('Error:', error));
});
document.getElementById('addAnother').addEventListener('click', function() {
    const container = document.getElementById('entriesContainer');
    const newEntry = container.children[0].cloneNode(true);
    newEntry.querySelectorAll('input').forEach(input => input.value = ''); // Clear inputs
    container.appendChild(newEntry);
});
</script>
</html>
```