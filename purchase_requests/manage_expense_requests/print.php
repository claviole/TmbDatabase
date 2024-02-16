<?php
include '../../configurations/connection.php';

$expenseId = isset($_GET['expenseId']) ? $_GET['expenseId'] : '';

// Fetch expense details from the database based on expenseId
$query = "SELECT * FROM purchase_requests WHERE expense_id = ?";
$stmt = $database->prepare($query);
$stmt->bind_param("s", $expenseId);
$stmt->execute();
$result = $stmt->get_result();
$details = $result->fetch_assoc();

// Determine the expense type
$expenseType = $details['expense_type'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Print Expense Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #dee2e6;
            box-shadow: 0 5px 15px rgba(0,0,0,.05);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            color: #007bff;
        }
        .detail {
            margin-bottom: 15px;
        }
        .detail strong {
            display: inline-block;
            min-width: 150px;
            color: #495057;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="container">
        <div class="header">
            <h2>Expense Details- <?= htmlspecialchars($expenseType) ?></h2>
        </div>
        <?php
        // Display fields based on expense type
        switch ($expenseType) {
            case 'Expense Report':
                echo "<div class='detail'><strong>Employee Name:</strong> " . htmlspecialchars($details['employee_name']) . "</div>";
                echo "<div class='detail'><strong>Month of Expense:</strong> " . htmlspecialchars($details['month_of_expense']) . "</div>";
                echo "<div class='detail'><strong>Date of Visit:</strong> " . htmlspecialchars($details['date_of_visit']) . "</div>";
                echo "<div class='detail'><strong>Approval Status:</strong> " . htmlspecialchars($details['approval_status']) . "</div>";
                $expenseReportItemsQuery = "SELECT * FROM expense_report_items WHERE expense_id = ?";
                $expenseReportItemsStmt = $database->prepare($expenseReportItemsQuery);
                $expenseReportItemsStmt->bind_param("i", $expenseId);
                $expenseReportItemsStmt->execute();
                $expenseReportItemsResult = $expenseReportItemsStmt->get_result();

                if($expenseReportItemsResult->num_rows > 0) {
                    echo "<div class='detail'><strong>Details</strong></div>";
                    echo "<table style='width:100%; border-collapse: collapse;'>";
                    echo "<tr><th style='border: 1px solid #ddd; padding: 8px;'>Customer Name</th><th style='border: 1px solid #ddd; padding: 8px;'>Customer Location</th><th style='border: 1px solid #ddd; padding: 8px;'>Mileage</th><th style='border: 1px solid #ddd; padding: 8px;'>Mileage Expense</th><th style='border: 1px solid #ddd; padding: 8px;'>Meals Expense</th><th style='border: 1px solid #ddd; padding: 8px;'>Entertainment Expense</th></tr>";
                    while($item = $expenseReportItemsResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($item['customer_name']) . "</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($item['customer_location']) . "</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($item['mileage']) . "</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($item['mileage_expense']) . "</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($item['meals_expense']) . "</td>";
                        echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($item['entertainment_expense']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<div class='detail'>No expense items found for this expense.</div>";
                }
                break;
                case 'Travel Approval':
                    echo "<div class='detail'><strong>Employee Name:</strong> " . htmlspecialchars($details['employee_name']) . "</div>";
                    echo "<div class='detail'><strong>Travel Start Date:</strong> " . htmlspecialchars($details['travel_start_date']) . "</div>";
                    echo "<div class='detail'><strong>Travel End Date:</strong> " . htmlspecialchars($details['travel_end_date']) . "</div>";
                    echo "<div class='detail'><strong>Approval Status:</strong> " . htmlspecialchars($details['approval_status']) . "</div>";
                    echo "<div class='detail'><strong>Additional Comments:</strong> " . htmlspecialchars($details['additional_comments']) . "</div>";
                
                    // Query to fetch travel items associated with this expense_id
                    $travelItemsQuery = "SELECT * FROM travel_items WHERE expense_id = ?";
                    $travelItemsStmt = $database->prepare($travelItemsQuery);
                    $travelItemsStmt->bind_param("i", $expenseId);
                    $travelItemsStmt->execute();
                    $travelItemsResult = $travelItemsStmt->get_result();
                
                    if ($travelItemsResult->num_rows > 0) {
                        echo "<div class='detail'><strong>Customer Details:</strong></div>";
                        while ($travelItem = $travelItemsResult->fetch_assoc()) {
                            echo "<div class='detail'>";
                            echo "<strong>Customer Name:</strong> " . htmlspecialchars($travelItem['customer_name']) . "<br>";
                            echo "<strong>Customer Location:</strong> " . htmlspecialchars($travelItem['customer_location']);
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='detail'>No customer details found for this travel expense.</div>";
                    }
                    break;
                default:
                    echo "<div class='detail'><strong>Employee Name:</strong> " . htmlspecialchars($details['employee_name']) . "</div>";
                    echo "<div class='detail'><strong>Customer Location:</strong> " . htmlspecialchars($details['customer_location']) . "</div>";
                    echo "<div class='detail'><strong>Vendor Name:</strong> " . htmlspecialchars($details['vendor_name']) . "</div>";
                    echo "<div class='detail'><strong>Month of Expense:</strong> " . htmlspecialchars($details['month_of_expense']) . "</div>";
                    echo "<div class='detail'><strong>Approval Status:</strong> " . htmlspecialchars($details['approval_status']) . "</div>";

                
                    // Query to fetch expense items associated with this expense_id
                    $itemsQuery = "SELECT * FROM expense_items WHERE expense_id = ?";
                    $itemsStmt = $database->prepare($itemsQuery);
                    $itemsStmt->bind_param("i", $expenseId);
                    $itemsStmt->execute();
                    $itemsResult = $itemsStmt->get_result();
                
                    if ($itemsResult->num_rows > 0) {
                        echo "<div class='detail'><strong>Items:</strong></div>";
                        echo "<table style='width:100%; border-collapse: collapse;'>";
                        echo "<tr><th style='border: 1px solid #ddd; padding: 8px;'>Item Name</th><th style='border: 1px solid #ddd; padding: 8px;'>Quantity</th><th style='border: 1px solid #ddd; padding: 8px;'>Price Per Item</th><th style='border: 1px solid #ddd; padding: 8px;'>Total Cost</th></tr>";
                        while ($item = $itemsResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($item['item_name']) . "</td>";
                            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($item['item_quantity']) . "</td>";
                            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($item['price_per_item']) . "</td>";
                            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($item['total_cost']) . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<div class='detail'>No items found for this expense.</div>";
                    }
                break;
            // Add other cases as necessary
        }
        ?>
        <div class="footer">
            <p>GL Code: <?= htmlspecialchars($details['gl_code']) ?></p>
        </div>
    </div>
</body>
</html>