<?php
session_start();
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');



// Check if the user is logged in and is an admin
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('Human Resources' || 'super-admin')){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../../index.php");
    exit();
}

$result = $database->query("SELECT `job_title_id`, `job_title` FROM `job_titles`");
$job_titles = $result->fetch_all(MYSQLI_ASSOC);
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
    <title>Add New Hire</title>
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
<body style="background-image: url('../../../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Hr Menu</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../../../images/home_page_company_header_hr.png" alt="company header" width="30%" height="20%" > 
    </h1>
    
    <div id="employee-info" class="flex justify-center">
    <form class="flex flex-wrap" method="post" action="submit_employee.php">
        <div class="w-1/2 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="first-name">
                First Name
            </label>
            <input name="first-name" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="first-name" type="text" placeholder="John">
        </div>
        <div class="w-1/2 px-3">
            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="last-name">
                Last Name
            </label>
            <input name="last-name" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="last-name" type="text" placeholder="Doe">
        </div>
        <div class="w-1/2 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="email">
                Email
            </label>
            <input name="email" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="email" type="text" placeholder="optional">
        </div>
        <div class="w-1/2 px-3">
            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="date-of-hire">
                Date of Hire
            </label>
            <input name="date-of-hire" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="date-of-hire" type="date">
        </div>
        <div class="w-1/2 px-3 mb-6 md:mb-0">
    <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="job-title">
        Job Title
    </label>
    <select name="job-title" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="job-title">
            <?php foreach($job_titles as $job_title): ?>
                <option value="<?= $job_title['job_title_id'] ?>"><?= $job_title['job_title'] ?></option>
            <?php endforeach; ?>

            .</select>
</div>
        <div class="w-1/2 px-3">
            <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="first-day-of-work">
                First Day of Work
            </label>
            <input name="first-day-of-work" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="first-day-of-work" type="date">
        </div>
        <div class="w-1/2 px-3">
                <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2" for="location_code">
                    Location
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="location_code" name="location_code" required>
                    <option value="sv">Sauk Village</option>
                    <option value="nv">North Vernon</option>
                    <option value="nb">New Boston</option>
                    <option value="fr">Flatrock</option>
                    <option value="tc">Torch</option>
                    <option value="gb">Gibraltar</option>
                </select>
            </div>
       

    
</div>
<div class="flex justify-center py-4">
    <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">
        Submit
    </button>
</div>
</form>
</div>
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
    echo "Welcome, " . $_SESSION['user']  ."             ". date("m/d/Y") . "<br>";
    ?>
</div>





</body>

<script>
    <?php if(isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= $_SESSION['success'] ?>',
        })
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>


</script>
</html>