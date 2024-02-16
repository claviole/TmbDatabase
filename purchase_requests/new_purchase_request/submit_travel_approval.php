<?php
session_start();
include '../../configurations/connection.php'; // Your database connection file
include '../../configurations/send_expense.php'; // Include the file where sendExpenseFormEmail function is defined
header('Content-Type: application/json'); // Specify the content type as JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $employee_name = mysqli_real_escape_string($database, $_POST['employee_name']);
    $expense_type = mysqli_real_escape_string($database, $_POST['expense_type']);
    $travel_start_date = mysqli_real_escape_string($database, $_POST['travel_start_date']);
    $travel_end_date = mysqli_real_escape_string($database, $_POST['travel_end_date']);
    $additional_comments = mysqli_real_escape_string($database, $_POST['additional_comments']);

    // Prepare an INSERT statement for the main expense record
    $query = "INSERT INTO purchase_requests (employee_name, expense_type, travel_start_date, travel_end_date, additional_comments, approval_status) VALUES (?, ?, ?, ?, ?, 'pending')";

    if ($stmt = mysqli_prepare($database, $query)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssss", $employee_name, $expense_type, $travel_start_date, $travel_end_date, $additional_comments);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $expense_id = mysqli_stmt_insert_id($stmt);

            // Initialize formData with basic information
            $formData = [
                'Expense ID' => $expense_id,
                'Employee Name' => $employee_name,
                'Expense Type' => $expense_type,
                'Travel Start Date' => $travel_start_date,
                'Travel End Date' => $travel_end_date,
                'Additional Comments' => $additional_comments,
            ];

            // Loop through each customer name and location
            foreach ($_POST['customer_name'] as $index => $name) {
                $customerIndex = $index + 1; // Start numbering from 1
                $customer_name = mysqli_real_escape_string($database, $name);
                $customer_location = mysqli_real_escape_string($database, $_POST['customer_location'][$index]);

                // Append each customer's info to formData
                $formData["Customer $customerIndex Name"] = $customer_name;
                $formData["Customer $customerIndex Location"] = $customer_location;

                // Prepare an INSERT statement for each customer entry
                $customerQuery = "INSERT INTO travel_items (expense_id, customer_name, customer_location) VALUES (?, ?, ?)";
                if ($customerStmt = mysqli_prepare($database, $customerQuery)) {
                    mysqli_stmt_bind_param($customerStmt, "iss", $expense_id, $customer_name, $customer_location);
                    mysqli_stmt_execute($customerStmt);
                    mysqli_stmt_close($customerStmt);
                }
            }

            // Send the email
            $emailResult = sendExpenseFormEmail($formData);

            // Check if the email was sent successfully
            if ($emailResult['status'] === 'success') {
                echo json_encode(['status' => 'success', 'message' => 'Request submitted and email sent successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Request submitted but failed to send email.']);
            }
        } else {
            // On failure to execute the statement
            echo json_encode(['status' => 'error', 'message' => 'ERROR: Could not execute query.']);
        }
    } else {
        echo "ERROR: Could not prepare query: $query. " . mysqli_error($database);
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($database);
} else {
    // Not a POST request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>