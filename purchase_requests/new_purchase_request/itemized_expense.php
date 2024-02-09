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
    <title>Purchase Requests</title>
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
<body style="background-image: url('../../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
     
    </h1>
    <div class="container mx-auto mt-10">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold mb-5 text-center">Office Supplies Request</h2>
        <form id="officeSuppliesForm" method="POST" action="submit_office_supplies.php" enctype="multipart/form-data" class="space-y-4">
            
            <div>
                <label for="customer_location" class="block text-gray-700 text-sm font-bold mb-2">Facility:</label>
                <select id="customer_location" name="customer_location" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="Flat Rock">Flat Rock</option>
                    <option value="Riverview">Riverview</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="All">All</option>
                </select>
            </div>
            <div>
    <label for="month_of_expense" class="block text-gray-700 text-sm font-bold mb-2">Month of Expense:</label>
    <select id="month_of_expense" name="month_of_expense" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <option value="January">January</option>
        <option value="February">February</option>
        <option value="March">March</option>
        <option value="April">April</option>
        <option value="May">May</option>
        <option value="June">June</option>
        <option value="July">July</option>
        <option value="August">August</option>
        <option value="September">September</option>
        <option value="October">October</option>
        <option value="November">November</option>
        <option value="December">December</option>
    </select>
</div>
            <div>
            <input type="hidden" name="expense_type" value="Office Supplies">
                <Label for="employee_name" class="block text-gray-700 text-sm font-bold mb-2">Employee Name:</Label>
                <input type="text" id="employee_name" name="employee_name" value="<?php echo htmlspecialchars($_SESSION['user']); ?>" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
            <div>
                <label for="vendor_name" class="block text-gray-700 text-sm font-bold mb-2">Vendor Name:</label>
                <input type="text" id="vendor_name" name="vendor_name" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <br>
            <div id="itemsContainer">
                <!-- Items will be added here dynamically -->
            </div>
            <br>
            <button type="button" id="addItemButton" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Add Item
            </button>

            <div class="flex items-center justify-between mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addItemButton').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const itemDiv = document.createElement('div');
        itemDiv.classList.add('item');
        itemDiv.innerHTML = `
            <input type="text" name="item_names[]" placeholder="Item Name" class="shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <input type="number" name="item_quantities[]" placeholder="Quantity" class="item-quantity shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <input type="text" name="item_prices[]" placeholder="Price per Item" class="item-price shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <input type="number" name="item_total_costs[]" placeholder="Total Cost" class="item-total-cost shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required readonly>
            <input type="text" name="department[]" placeholder="Department" class="shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <button type="button" class="removeItemButton bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">Remove</button>
        `;
        container.appendChild(itemDiv);

        // Add remove functionality to the button
        itemDiv.querySelector('.removeItemButton').addEventListener('click', function() {
            itemDiv.remove();
        });

        // Add event listeners to quantity and price inputs for automatic total cost calculation
        const quantityInput = itemDiv.querySelector('.item-quantity');
        const priceInput = itemDiv.querySelector('.item-price');
        const totalCostInput = itemDiv.querySelector('.item-total-cost');

        const updateTotalCost = () => {
            const quantity = parseFloat(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const totalCost = quantity * price;
            totalCostInput.value = totalCost.toFixed(2); // Assuming 2 decimal places for currency
        };

        quantityInput.addEventListener('input', updateTotalCost);
        priceInput.addEventListener('input', updateTotalCost);
    });

    document.getElementById('officeSuppliesForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this);

        fetch('submit_office_supplies.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Submitted!',
                    text: data.message,
                }).then((result) => {
                    if (result.isConfirmed || result.isDismissed) {
                        window.location.href = '../index.php';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                });
            }
        })
        .catch((error) => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while submitting the form.',
            });
        });
    });
});


</script>

</body>
</html>

```