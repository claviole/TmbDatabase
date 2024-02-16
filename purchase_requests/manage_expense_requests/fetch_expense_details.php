<?php
// Start the session and include your database configuration
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../configurations/connection.php';

header('Content-Type: application/json');

// Check if the ID is provided
if (isset($_POST['id'])) {
    $expenseId = mysqli_real_escape_string($database, $_POST['id']);

    // Initialize an array to hold the response
    $response = [];

    // Prepare the SQL query to fetch the expense details
    $query = "SELECT * FROM purchase_requests WHERE expense_id = ?";
    
    if ($stmt = mysqli_prepare($database, $query)) {
        // Bind the expense ID to the prepared statement
        mysqli_stmt_bind_param($stmt, "i", $expenseId);
        
        // Execute the query
        mysqli_stmt_execute($stmt);
        
        // Bind the result variables
        $result = mysqli_stmt_get_result($stmt);
        
        // Fetch the details
        if ($row = mysqli_fetch_assoc($result)) {
            $response['details'] = $row;

             // If the expense type is Travel Approval, fetch associated customer details
            if ($row['expense_type'] == 'Travel Approval') {
                $customerQuery = "SELECT customer_name, customer_location FROM travel_items WHERE expense_id = ?";
                if ($customerStmt = mysqli_prepare($database, $customerQuery)) {
                    mysqli_stmt_bind_param($customerStmt, "i", $expenseId);
                    mysqli_stmt_execute($customerStmt);
                    $customerResult = mysqli_stmt_get_result($customerStmt);
                    $customers = [];
                    while ($customerRow = mysqli_fetch_assoc($customerResult)) {
                        $customers[] = $customerRow;
                    }
                    $response['customers'] = $customers;
                    mysqli_stmt_close($customerStmt);
                }
            }

            else if($row['expense_type'] == 'Expense Report'){
                $itemsQuery = "SELECT * FROM expense_report_items WHERE expense_id = ?";
                if ($itemsStmt = mysqli_prepare($database, $itemsQuery)) {
                    mysqli_stmt_bind_param($itemsStmt, "i", $expenseId);
                    mysqli_stmt_execute($itemsStmt);
                    $itemsResult = mysqli_stmt_get_result($itemsStmt);
                    $items = [];
                    while ($itemRow = mysqli_fetch_assoc($itemsResult)) {
                        $items[] = $itemRow;
                    }
                    $response['items'] = $items;
                    mysqli_stmt_close($itemsStmt);
                }
            }
        
            // Check if the expense type is Office Supplies before fetching items
            else if ($row['expense_type'] != 'Travel Approval' && $row['expense_type'] != "Expense Report") {
                // Prepare the SQL query to fetch associated expense items
                $itemsQuery = "SELECT * FROM expense_items WHERE expense_id = ?";
                if ($itemsStmt = mysqli_prepare($database, $itemsQuery)) {
                    mysqli_stmt_bind_param($itemsStmt, "i", $expenseId);
                    mysqli_stmt_execute($itemsStmt);
                    $itemsResult = mysqli_stmt_get_result($itemsStmt);
                    $items = [];
                    while ($itemRow = mysqli_fetch_assoc($itemsResult)) {
                        $items[] = $itemRow;
                    }
                    $response['items'] = $items;
                    mysqli_stmt_close($itemsStmt);
                }
            }
        } else {
            // If no record is found
            echo json_encode(['error' => 'No expense record found.']);
            exit;
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Error preparing the statement
        echo json_encode(['error' => 'Error preparing the SQL statement for expense details.']);
        exit;
    }

    // Now, fetch the associated files
    $queryFiles = "SELECT file_name, file_path FROM expense_files WHERE expense_id = ?";
    if ($stmtFiles = mysqli_prepare($database, $queryFiles)) {
        mysqli_stmt_bind_param($stmtFiles, "i", $expenseId);
        mysqli_stmt_execute($stmtFiles);
        $resultFiles = mysqli_stmt_get_result($stmtFiles);
        $files = [];
        while ($fileRow = mysqli_fetch_assoc($resultFiles)) {
            $files[] = $fileRow;
        }
        $response['files'] = $files;
        mysqli_stmt_close($stmtFiles);
    } else {
        echo json_encode(['error' => 'Error preparing the SQL statement for files.']);
        exit;
    }

    // Close database connection
    mysqli_close($database);

    // Return the combined response
    echo json_encode($response);
} else {
    // If the ID isn't set
    echo json_encode(['error' => 'Expense ID not provided.']);
}
?>