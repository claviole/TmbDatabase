<?php
session_start();

// Check if the user is logged in and is an admin
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != 'admin'){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=" ../index.css ">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>TMB Quotes Database</h1>
    <div class ="flex items-center justify-center min-h-screen bg-image px-4 py-8 md:pt-0 bg-gray-50">
    <div class >
        <button onclick= "window.location.href='New_Quote/start_new_invoice.php'">Start New Quote</button>
        <button onclick="window.location.href='add_new_part/add_new_part.php'">Add New Part</button>
        <button onclick="window.location.href='add_new_customer/add_new_customer.php'">Add New Customer</button>
        <button onclick="window.location.href='Lookup_customer/lookup_customer.php'">Look Up Customer</button>
        <button onclick="window.location.href='lookup_invoice/lookup_invoice.php'">Look Up Quote</button>
        <button onclick="window.location.href='manage_customers.php'">Manage Customers</button>
        <button onclick="window.location.href='manage_parts.php'">Manage Parts</button>
    </div>
    </div>

</body>
</html>