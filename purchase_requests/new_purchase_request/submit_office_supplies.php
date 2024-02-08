<?php
session_start();
include '../../configurations/connection.php'; // Your database connection file
include '../../configurations/send_expense.php'; // Include the file where sendExpenseFormEmail function is defined

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $employee_name = mysqli_real_escape_string($database, $_SESSION['user']); // Assuming the username is stored in the session
    $expense_type = "Office Supplies"; // Assuming the expense type is fixed
    $customer_location = mysqli_real_escape_string($database, $_POST['customer_location']);
    $vendor_name = mysqli_real_escape_string($database, $_POST['vendor_name']);
    $month_of_expense = mysqli_real_escape_string($database, $_POST['month_of_expense']);
    // Add other fields as necessary

    // Prepare an INSERT statement for the office supplies request
    $query = "INSERT INTO purchase_requests (employee_name,expense_type, customer_location, vendor_name, month_of_expense) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($database, $query)) {
        mysqli_stmt_bind_param($stmt, "sssss", $employee_name,$expense_type, $customer_location, $vendor_name, $month_of_expense);

        if (mysqli_stmt_execute($stmt)) {
            $expense_id = mysqli_stmt_insert_id($stmt);

// Initialize HTML string for items with a styled table
$itemsHtml = "<table style='width: 100%; border-collapse: collapse; border: 1px solid #ddd; font-family: Arial, sans-serif;'>";
$itemsHtml .= "<thead style='background-color: #f2f2f2;'><tr>";
$itemsHtml .= "<th style='padding: 8px; border: 1px solid #ddd;'>Item</th>";
$itemsHtml .= "<th style='padding: 8px; border: 1px solid #ddd;'>Quantity</th>";
$itemsHtml .= "<th style='padding: 8px; border: 1px solid #ddd;'>Price per Item</th>";
$itemsHtml .= "<th style='padding: 8px; border: 1px solid #ddd;'>Total Cost</th>";
$itemsHtml .= "<th style='padding: 8px; border: 1px solid #ddd;'>Department</th>";
$itemsHtml .= "</tr></thead>";
$itemsHtml .= "<tbody>";

foreach ($_POST['item_names'] as $index => $itemName) {
    $itemQuantity = $_POST['item_quantities'][$index];
    $itemPrice = $_POST['item_prices'][$index];
    $department = $_POST['department'][$index];
    $totalCost = $itemQuantity * $itemPrice;

    // Insert item into the expense_items table
    $itemQuery = "INSERT INTO expense_items (expense_id, item_name, item_quantity, price_per_item, total_cost, department) VALUES (?, ?, ?, ?, ?, ?)";
    if ($itemStmt = mysqli_prepare($database, $itemQuery)) {
        mysqli_stmt_bind_param($itemStmt, "isidds", $expense_id, $itemName, $itemQuantity, $itemPrice, $totalCost, $department);
        mysqli_stmt_execute($itemStmt);
        mysqli_stmt_close($itemStmt);
    }

    // Append item details to the itemsHtml string in table row format
    $itemsHtml .= "<tr>";
    $itemsHtml .= "<td style='padding: 8px; border: 1px solid #ddd;'>$itemName</td>";
    $itemsHtml .= "<td style='padding: 8px; border: 1px solid #ddd;'>$itemQuantity</td>";
    $itemsHtml .= "<td style='padding: 8px; border: 1px solid #ddd;'>$itemPrice</td>";
    $itemsHtml .= "<td style='padding: 8px; border: 1px solid #ddd;'>$totalCost</td>";
    $itemsHtml .= "<td style='padding: 8px; border: 1px solid #ddd;'>$department</td>";
    $itemsHtml .= "</tr>";
}

// Close the table after all items are added
$itemsHtml .= "</tbody></table>";

// Close the table after all items are added
$itemsHtml .= "</table>";
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