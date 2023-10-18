<?php
session_start();
include '../connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');
$result = $database->query("SELECT COUNT(*) as count FROM invoice WHERE approval_status = 'Awaiting Approval'");
$awaiting_approval_count = $result->fetch_assoc()['count'];



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
    </style>
    
</head>
<body style="background-image: url('../images/steel_coils.jpg'); background-size: cover;">
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
        <div class="notification<?php echo $awaiting_approval_count > 0 ? ' flashing' : ''; ?>">
    Quotes Awaiting Approval: <?php echo $awaiting_approval_count; ?>
</div>
     
    </h1>
    
    <div class ="flex justify-center">
    <div class ="flex flex-col justify-content: center  py-10 px-0 "  >
        <button style="width:400px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick= "window.location.href='New_Quote/start_new_invoice.php'">Start New Quote</button>
        <button style="width:400px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md " onclick="window.location.href='add_new_customer/add_new_customer.php'">Add New Customer</button>
        <button style="width:400px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick="window.location.href='Lookup_customer/lookup_customer.php'">Look Up Customer</button>
        <button style="width:400px; margin-top: 10px;border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick="window.location.href='lookup_invoice/lookup_invoice.php'">Look Up Quote</button>
        <button style="width:400px; margin-top: 10px;border:2px solid black ;"class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick="window.location.href='manage_customers.php'">Manage Customers</button>
        <button style="width:400px; margin-top: 10px; border:2px solid black ;" class = "bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded max-w-md "onclick="window.location.href='manage_parts.php'">Manage Parts</button>
    </div>
    </div>
    <div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0;">
    <?php
    echo "Welcome, " . $_SESSION['user']  ."             ". date("m/d/Y") . "<br>";
    ?>
    
</div>
<div class="text-white font-bold py-2 px-4 rounded max-w-md" style="position: absolute; top: 0; right: 0;">
<form action="logout.php" method="post" style="position: absolute; top: 0; right: 0; width: 100px;" class="inline-flex w-full items-center  justify-center rounded-md border border-transparent bg-[#ffffff] px-6 py-4 text-sm font-bold text-black transition-all duration-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
    <input type="submit" value="Log Out">
</form>
</div>



</body>
</html>