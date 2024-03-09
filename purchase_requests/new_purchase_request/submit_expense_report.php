<?php
session_start();
include '../../configurations/connection.php'; // Your database connection file
include '../../configurations/send_expense.php'; // Include the file where sendExpenseFormEmail function is defined

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $employee_name = mysqli_real_escape_string($database, $_POST['employee_name']);
    $expense_type = "Expense Report"; // Hardcoded value for "Expense Report"
    $month_of_expense = mysqli_real_escape_string($database, $_POST['month_of_expense']);
  
    $gl_code = mysqli_real_escape_string($database, $_POST['expense_type']);
    $location_code= $_SESSION['location_code'];

    // Prepare an INSERT statement for the main expense report
    $query = "INSERT INTO purchase_requests (employee_name, gl_code, expense_type, month_of_expense,location_code) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($database, $query)) {
        mysqli_stmt_bind_param($stmt, "sisss", $employee_name, $gl_code, $expense_type, $month_of_expense, $location_code);
        if (mysqli_stmt_execute($stmt)) {
            $expense_id = mysqli_stmt_insert_id($stmt);
            mysqli_stmt_close($stmt);

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
                        $insertFileQuery = "INSERT INTO expense_files (expense_id, file_name, file_path) VALUES (?, ?, ?)";
                        if ($fileStmt = mysqli_prepare($database, $insertFileQuery)) {
                            mysqli_stmt_bind_param($fileStmt, "iss", $expense_id, $fileName, $filePath);
                            mysqli_stmt_execute($fileStmt);
                            mysqli_stmt_close($fileStmt);
                        }
                    }
                }
            }

            // Insert expense items
            $numItems = count($_POST['customer_name']);
            $insertItemQuery = "INSERT INTO expense_report_items (expense_id, customer_name, customer_location, mileage, mileage_expense, meals_expense, entertainment_expense, date_of_visit) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            for ($i = 0; $i < $numItems; $i++) {
                if ($itemStmt = mysqli_prepare($database, $insertItemQuery)) {
                    mysqli_stmt_bind_param($itemStmt, "isssssss", $expense_id, $_POST['customer_name'][$i], $_POST['customer_location'][$i], $_POST['mileage'][$i], $_POST['mileage_expense'][$i], $_POST['meals_expense'][$i], $_POST['entertainment_expense'][$i], $_POST['date_of_visit'][$i]);
                    mysqli_stmt_execute($itemStmt);
                    mysqli_stmt_close($itemStmt);
                }
            }

            // Construct HTML table for expense items
$expenseItemsTable = '<table border="1"><tr><th>Customer Name</th><th>Customer Location</th><th>Date Of Visit </th><th>Mileage</th><th>Mileage Expense</th><th>Meals Expense</th><th>Entertainment Expense</th></tr>';

for ($i = 0; $i < $numItems; $i++) {
    $expenseItemsTable .= '<tr>';
    $expenseItemsTable .= '<td style="text-align: center;">' . htmlspecialchars($_POST['customer_name'][$i]) . '</td>';
    $expenseItemsTable .= '<td style="text-align: center;">' . htmlspecialchars($_POST['customer_location'][$i]) . '</td>';
    $expenseItemsTable .= '<td style="text-align: center;">' . htmlspecialchars($_POST['date_of_visit'][$i]) . '</td>';
    $expenseItemsTable .= '<td style="text-align: center;">' . htmlspecialchars($_POST['mileage'][$i]) . '</td>';
    $expenseItemsTable .= '<td style="text-align: center;">' . "$". htmlspecialchars($_POST['mileage_expense'][$i]) . '</td>';
    $expenseItemsTable .= '<td style="text-align: center;">' . "$". htmlspecialchars($_POST['meals_expense'][$i]) . '</td>';
    $expenseItemsTable .= '<td style="text-align: center;">' . "$". htmlspecialchars($_POST['entertainment_expense'][$i]) . '</td>';
    $expenseItemsTable .= '</tr>';
}

$expenseItemsTable .= '</table>';

// Prepare data for the email, including the expense items table
$formData = [
    'Expense ID' => $expense_id,
    'Employee Name' => $employee_name,
    'Expense Type' => $expense_type,
    'GL Code' => $gl_code,
    'Month of Expense' => $month_of_expense,
    'Expense Items' => $expenseItemsTable, // Include the expense items table
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
            echo json_encode(['status' => 'error', 'message' => 'ERROR: Could not execute query. ' . mysqli_error($database)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ERROR: Could not prepare query. ' . mysqli_error($database)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ERROR: Invalid request method.']);
}


