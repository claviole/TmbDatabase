<?php
session_start();
include '../../configurations/connection.php'; // Your database connection file
include '../../configurations/send_expense.php'; // Include the file where sendExpenseFormEmail function is defined

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $employee_name = mysqli_real_escape_string($database, $_SESSION['user']); // Assuming the username is stored in the session
    $customer_location = mysqli_real_escape_string($database, $_POST['customer_location']);
    $vendor_name = mysqli_real_escape_string($database, $_POST['vendor_name']);
    $month_of_expense = mysqli_real_escape_string($database, $_POST['month_of_expense']);
    // Add other fields as necessary

    // Prepare an INSERT statement for the office supplies request
    $query = "INSERT INTO purchase_requests (employee_name, customer_location, vendor_name, month_of_expense) VALUES (?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($database, $query)) {
        mysqli_stmt_bind_param($stmt, "ssss", $employee_name, $customer_location, $vendor_name, $month_of_expense);

        if (mysqli_stmt_execute($stmt)) {
            $expense_id = mysqli_stmt_insert_id($stmt);

            // Handle each item
            $itemsHtml = ""; // Initialize HTML string for items
            foreach ($_POST['item_names'] as $index => $itemName) {
                $itemQuantity = $_POST['item_quantities'][$index];
                $itemPrice = $_POST['item_prices'][$index];
                $totalCost = $itemQuantity * $itemPrice;

                // Insert item into the expense_items table
                $itemQuery = "INSERT INTO expense_items (expense_id, item_name, item_quantity, price_per_item, total_cost) VALUES (?, ?, ?, ?, ?)";
                if ($itemStmt = mysqli_prepare($database, $itemQuery)) {
                    mysqli_stmt_bind_param($itemStmt, "isidd", $expense_id, $itemName, $itemQuantity, $itemPrice, $totalCost);
                    mysqli_stmt_execute($itemStmt);
                    mysqli_stmt_close($itemStmt);
                }

                // Append item details to the itemsHtml string
                $itemsHtml .= "<p>Item: $itemName, Quantity: $itemQuantity, Price per Item: $itemPrice, Total Cost: $totalCost</p>";
            }

            // Prepare data for the email
            $formData = [
                'Employee Name' => $employee_name,
                'Customer Location' => $customer_location,
                'Vendor Name' => $vendor_name,
                'Month of Expense' => $month_of_expense,
                'Items' => $itemsHtml // Include the items HTML
            ];

            // Send the email
            $emailResult = sendExpenseFormEmail($formData);

            // Check if the email was sent successfully
            if ($emailResult['status'] === 'success') {
                echo json_encode(['status' => 'success', 'message' => 'Office supplies request submitted and email sent successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Office supplies request submitted but failed to send email.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ERROR: Could not execute query. ' . mysqli_error($database)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ERROR: Could not prepare query. ' . mysqli_error($database)]);
    }

    mysqli_close($database);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>