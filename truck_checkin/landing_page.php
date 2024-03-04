<?php
session_start();
$_SESSION['location_code'] = isset($_GET['location_code']) ? $_GET['location_code'] : '';
$location_code = $_SESSION['location_code'];
include '../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');



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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Check In</title>
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
  /* Fix for the background image repeating */
  body {
            background-image: url(/images/truck.webp);
            background-size: cover;
            background-repeat: no-repeat; /* Prevent background image from repeating */
        }

        /* Improved styling for mobile usage */
        .container {
            max-width: 100%; /* Use the full width on mobile */
            padding: 20px; /* Add some padding around the form */
        }

        form {
            background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent background */
            padding: 20px; /* Padding inside the form */
            border-radius: 8px; /* Rounded corners for the form */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Soft shadow around the form */
        }

        input, button {
            width: 100%; /* Full width for inputs and button */
            margin-bottom: 15px; /* Space between form elements */
        }

        label {
            display: block; /* Ensure labels are block-level for better alignment */
            margin-bottom: 5px; /* Space between label and input */
        }

        /* Tailwind classes can be used directly in HTML for further responsiveness */
    </style>
</head>
<body>

    <div class="container mx-auto px-4">
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="20%"> 
    </h1>
    <form id="checkinForm" action="submit_checkin.php" method="post" class="w-full max-w-md mx-auto bg-white rounded-lg shadow-md p-5">
            <input type="hidden" name="location_code" value="<?php echo htmlspecialchars($location_code); ?>">
            <input type="hidden" name="arrival_date" value="<?php echo date('Y-m-d H:i:s'); ?>">

            <div class="mb-4">
                <label for="load_number" class="block text-gray-700 text-sm font-bold mb-2">Load Number:</label>
                <input type="text" id="load_number" name="load_number" pattern="\d{6}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="6 digit number">
            </div>

            <div class="mb-4">
                <label for="truck_number" class="block text-gray-700 text-sm font-bold mb-2">Truck Number:</label>
                <input type="text" id="truck_number" name="truck_number" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="weight" class="block text-gray-700 text-sm font-bold mb-2">Weight:</label>
                <input type="text" id="weight" name="weight" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

            <div class="mb-6">
                <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="123-456-7890" oninput="formatPhoneNumber(this)">            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Check in
                </button>
            </div>
            <div class="text-xs text-gray-600 mt-2">
                By clicking "Check in", you agree to receive text message notifications related to your service.
            </div>
        </form>
    </div>

    <!-- Existing body content can remain unchanged -->

    <script>
function formatPhoneNumber(input) {
    // Remove all non-digit characters from the input
    let numbers = input.value.replace(/\D/g, '');
    // Capture up to the first 3 digits, next 3 digits, and last 4 digits
    let phoneNumber = numbers.substring(0, 3) + (numbers.length > 3 ? '-' : '') + numbers.substring(3, 6) + (numbers.length > 6 ? '-' : '') + numbers.substring(6, 10);
    // Update the input value with the formatted phone number
    input.value = phoneNumber;
}

$(document).ready(function() {
    $('#checkinForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        $.ajax({
            url: 'submit_checkin.php', // Your submission script
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
                // Display SweetAlert on success
                Swal.fire({
                    title: 'Success!',
                    text: 'Your information has been received, please stay seated in your truck. You will receive a text message when it is your turn to pull in!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed || result.dismiss) {
                        // Hide the form
                        $('#checkinForm').hide();
                        // Display the closing message
                        $('.container').append('<p class="text-center mt-4">You may now close this page.</p>');
                    }
                });
            },
            error: function(xhr, status, error) {
                // Optionally handle error
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong, please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
</script>
</body>
</html>