<?php
session_start();
include '../../configurations/connection.php'; // Your database connection file
include '../../configurations/send_expense.php'; // Include the file where sendExpenseFormEmail function is defined

header('Content-Type: application/json');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $employee_name = mysqli_real_escape_string($database, $_POST['employee_name']);
    $expense_type = mysqli_real_escape_string($database, $_POST['expense_type']);
    $month_of_expense = mysqli_real_escape_string($database, $_POST['month_of_expense']);
    $date_of_visit = mysqli_real_escape_string($database, $_POST['date_of_visit']);
    $customer_name = mysqli_real_escape_string($database, $_POST['customer_name']);
    $customer_location = mysqli_real_escape_string($database, $_POST['customer_location']);
    $mileage = mysqli_real_escape_string($database, $_POST['mileage']);
    $mileage_expense = mysqli_real_escape_string($database, $_POST['mileage_expense']);
    $meals_expense = mysqli_real_escape_string($database, $_POST['meals_expense']);
    $entertainment_expense = mysqli_real_escape_string($database, $_POST['entertainment_expense']);
    // Add other fields as necessary

    // Prepare an INSERT statement
    $query = "INSERT INTO purchase_requests (employee_name, expense_type, month_of_expense, date_of_visit, customer_name, customer_location, mileage, mileage_expense, meals_expense, entertainment_expense) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($database, $query)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssdddd", $employee_name, $expense_type, $month_of_expense, $date_of_visit, $customer_name, $customer_location, $mileage, $mileage_expense, $meals_expense, $entertainment_expense);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $expense_id = mysqli_stmt_insert_id($stmt);

            // Handle file uploads
            if (!empty($_FILES['fileUpload']['name'][0])) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['fileUpload']['name'] as $key => $value) {
                    $fileTmpName = $_FILES['fileUpload']['tmp_name'][$key];
                    $fileName = uniqid() . '-' . basename($_FILES['fileUpload']['name'][$key]);
                    $filePath = $uploadDir . $fileName;

                    if (move_uploaded_file($fileTmpName, $filePath)) {
                        // Insert file data into the database
                        $insertFileQuery = "INSERT INTO expense_files (expense_id, file_name, file_path) VALUES (?, ?, ?)";
                        if ($fileStmt = mysqli_prepare($database, $insertFileQuery)) {
                            mysqli_stmt_bind_param($fileStmt, "iss", $expense_id, $fileName, $filePath);
                            mysqli_stmt_execute($fileStmt);
                            mysqli_stmt_close($fileStmt);
                        }
                    }
                }
            }

            // Prepare data for the email
            $formData = [
                'Employee Name' => $employee_name,
                'Expense Type' => $expense_type,
                'Month of Expense' => $month_of_expense,
                'Date of Visit' => $date_of_visit,
                'Customer Name' => $customer_name,
                'Customer Location' => $customer_location,
                'Mileage' => $mileage,
                'Mileage Expense' =>"$" . $mileage_expense,
                'Meals Expense' =>"$" . $meals_expense,
                'Entertainment Expense' => "$". $entertainment_expense 
                // Include additional fields as needed
            ];

            // Send the email
            $emailResult = sendExpenseFormEmail($formData);

            // Check if the email was sent successfully
            if ($emailResult['status'] === 'success') {
                echo json_encode(['status' => 'success', 'message' => 'Expense report submitted and email sent successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Expense report submitted but failed to send email.']);
            }
        } else {
            // On failure to execute the statement
            echo json_encode(['status' => 'error', 'message' => 'ERROR: Could not execute query. ' . mysqli_error($database)]);
        }
    } else {
        // On failure to prepare the statement
        echo json_encode(['status' => 'error', 'message' => 'ERROR: Could not prepare query. ' . mysqli_error($database)]);
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