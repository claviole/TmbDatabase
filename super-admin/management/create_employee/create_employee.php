<?php
session_start();
include '../../../connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');


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
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Create Employee</title>
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

        form {
        width: 120%; /* Adjust this value to make the form wider or narrower */
        margin: 0 auto; /* This will center the form */
    }
    </style>


</head>
<body style="background-image: url('../../../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container">
    <a href="../management.php" class="return-button">Return to Menu</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../../../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
       

     
    </h1>

    
    <div class="flex justify-center">
    <div class="flex flex-col justify-content: center  py-10 px-0">
        <form action="process_employee.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">
                    First Name
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="first_name" type="text" name="first_name" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">
                    Last Name
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="last_name" type="text" name="last_name" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Company Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" name="email" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="user_role">
                    User Role
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="user_role" name="user_role" required>
                    <option value="Sales">Sales</option>
                    <option value="Management">Management</option>
                    <option value="Human Resources">Human Resources</option>
                </select>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Create Employee
                </button>
            </div>
        </form>
    </div>
</div>
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
    echo "Welcome, " . $_SESSION['user']  ."             ". date("m/d/Y") . "<br>";
    ?>
    
</div>
<div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0; right: 0;">

</div>


<?php
if (isset($_GET['temp_password'])) {
    $temp_password = $_GET['temp_password'];
    echo "<script type='text/javascript'>
            Swal.fire({
                title: 'Employee creation successful',
                text: 'Temporary password is: $temp_password',
                icon: 'success',
                confirmButtonText: 'OK'
            });
          </script>";
}
?>
</body>


<script>
   



    </script>
</html>