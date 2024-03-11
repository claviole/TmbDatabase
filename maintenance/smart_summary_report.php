<?php
session_start();
require '../configurations/connection.php'; // Adjust the path as necessary

// Security check: Ensure the user is logged in and has the appropriate user type
if (!isset($_SESSION['user']) || !in_array($_SESSION['user_type'], ['super-admin', 'maintenance-tech', 'supervisor'])) {
    exit('Access Denied');
}

header('Content-Type: text/html; charset=utf-8');

// Define the date range for the past month
$startDate = date("Y-m-d H:i:s", strtotime("-1 month"));
$endDate = date("Y-m-d H:i:s");

// Query to get the total amount of tickets created and closed within the past month
$totalTicketsQuery = "SELECT 
                        (SELECT COUNT(*) FROM `orange_tag` WHERE `orange_tag_creation_date` BETWEEN ? AND ? AND `priority` <> 5) AS total_created,
                        (SELECT COUNT(*) FROM `orange_tag` WHERE `date_closed` BETWEEN ? AND ? AND `priority` <> 5) AS total_closed
                      FROM dual";

// Query to get the number of tickets each technician currently has assigned
// Assuming $_SESSION['location_code'] holds the current user's location code
$currentUserLocationCode = $_SESSION['location_code'];

// Modified query to include location code check and user_type restriction
$technicianTicketsQuery = "SELECT u.username, COUNT(ot.orange_tag_id) AS ticket_count
                           FROM `Users` u
                           LEFT JOIN `orange_tag` ot ON FIND_IN_SET(u.id, ot.repair_technician) 
                               AND ot.ticket_status = 'Open' 
                               AND ot.orange_tag_due_date <= DATE_ADD(CURDATE(), INTERVAL 6 MONTH)
                               AND ot.priority <> 5
                           WHERE u.user_type = 'maintenance-tech' AND u.location_code = ?
                           GROUP BY u.id";

// Prepare and bind parameter
$technicianTicketsStmt = $database->prepare($technicianTicketsQuery);
$technicianTicketsStmt->bind_param('s', $currentUserLocationCode); // 's' specifies the parameter type => string
$technicianTicketsStmt->execute();
$technicianTicketsResult = $technicianTicketsStmt->get_result();

$averageTimeQuery = "SELECT AVG(TIMESTAMPDIFF(HOUR, `orange_tag_creation_date`, `date_closed`)) AS average_hours
                     FROM `orange_tag`
                     WHERE `date_closed` IS NOT NULL 
                     AND `orange_tag_creation_date` BETWEEN ? AND ?
                     AND `priority` <> 5";
// Execute the total tickets query
$stmt = $database->prepare($totalTicketsQuery);
$stmt->bind_param('ssss', $startDate, $endDate, $startDate, $endDate);
$stmt->execute();
$totalTicketsResult = $stmt->get_result()->fetch_assoc();



// Execute the average time query
$averageTimeStmt = $database->prepare($averageTimeQuery);
$averageTimeStmt->bind_param('ss', $startDate, $endDate);
$averageTimeStmt->execute();
$averageTimeResult = $averageTimeStmt->get_result()->fetch_assoc();

// Start the output buffer
ob_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMART Summary Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 960px;
            margin: 20px auto;
            background: white;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            color: #444;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 20px;
            color: #555;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        p, li {
            font-size: 16px;
            line-height: 1.6;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            background-color: #eee;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            border-left: 5px solid #009688; /* Teal accent */
        }
        p.note {
            font-style: italic;
            color: #666;
            font-size: 14px;
        }
        button {
            background-color: #009688; /* Teal */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        button:hover {
            background-color: #00796b;
        }
        @media print {
            #printButton {
                display: none;
            }
        }
    </style>
     <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>
<button id="printButton" onclick="printReport()">Print Report</button>
<h1>SMART Summary Report</h1>
    <p class="note">Statistics generated based on data from the last 30 days and does not include data gathered from Preventive Maintenance Tickets.</p>
    <p>Total Tickets Created in the Past Month: <?php echo $totalTicketsResult['total_created']; ?></p>
    <p>Total Tickets Closed in the Past Month: <?php echo $totalTicketsResult['total_closed']; ?></p>
    <p>Average Time from Creation to Closure: <?php echo round($averageTimeResult['average_hours'] / 24, 2); ?> days</p>
    <h2>Total Tickets currently Assigned to Technicians</h2>
    <ul>
        <?php while ($row = $technicianTicketsResult->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($row['username']) . ': ' . $row['ticket_count']; ?></li>
        <?php endwhile; ?>
    </ul>
 
</body>
</html>