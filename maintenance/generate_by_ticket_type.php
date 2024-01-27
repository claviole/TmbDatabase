<?php
session_start();
include '../configurations/connection.php';

header('Content-Type: text/html; charset=utf-8');

// Check if the user is logged in and has the right user type
if (!isset($_SESSION['user']) || !in_array($_SESSION['user_type'], ['super-admin', 'maintenance-tech'])) {
    exit('Access Denied');
}


$locationCode = $_SESSION['location_code'];

$selectedTicketType = isset($_GET['ticketType']) ? $_GET['ticketType'] : '';

// Query to get all tags with the selected ticket type and 'Open' ticket status and matching location code in the tag id
$query = "SELECT ot.*, l.Line_Location, l.Line_Name, e.employee_fname, e.employee_lname,
          (SELECT GROUP_CONCAT(u.username SEPARATOR ', ') FROM `Users` u WHERE FIND_IN_SET(u.id, ot.repair_technician)) AS repair_technicians
          FROM `orange_tag` ot
          LEFT JOIN `Lines` l ON ot.line_name = l.line_id
          LEFT JOIN `employees` e ON ot.supervisor = e.employee_id
          WHERE SUBSTRING(ot.orange_tag_id, 1, 2) = ? AND ot.ticket_type = ? AND ot.ticket_status = 'Open'
          GROUP BY ot.orange_tag_id
          ORDER BY ot.date_closed IS NULL DESC, ot.date_closed ASC, ot.orange_tag_creation_date DESC";

$stmt = $database->prepare($query);
$stmt->bind_param('ss', $locationCode, $selectedTicketType);
$stmt->execute();
$result = $stmt->get_result();

// Start the output buffer
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Priority Tags Report</title>
    <link rel="stylesheet" href="path/to/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .printable-page {
            page-break-after: always;
        }
        .ticket-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .ticket-details-table th,
        .ticket-details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .ticket-details-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .repairs-maintenance {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .repairs-maintenance th {
            width: 25%;
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 8px;
            text-align: left;
        }
        .repairs-maintenance td {
            width: 75%;
            padding: 8px;
        }
        .input-box {
            display: block;
            width: 100%;
            min-height: 70px; /* Increased height for writing space */
            padding: 5px;
            border: 1px solid #ddd;
            background-color: #fff;
            margin: 2px 0;
            vertical-align: top; /* Aligns text to the top */
        }
        .footer {
            text-align: center;
            margin-top: 20px;
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
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="report-container printable-page">
        <h1 style="text-align: center;">
            Ticket Details<?php echo isset($row['work_order_number']) ? ' - ' . htmlspecialchars($row['work_order_number']) : ''; ?>
        </h1> <!-- Conditionally display work order number if available -->
        <table class="ticket-details-table">
            <tbody>
                <tr>
                    <th>Tag ID</th>
                    <td><?php echo htmlspecialchars($row['orange_tag_id']); ?></td>
                    <th>Ticket Type</th>
                    <td><?php echo htmlspecialchars($row['ticket_type']); ?></td>
                </tr>
                <tr>
                    <th>Line</th>
                    <td><?php echo htmlspecialchars($row['Line_Location']) . ' - ' . htmlspecialchars($row['Line_Name']); ?></td>
                    <th>Die Number</th>
                    <td><?php echo htmlspecialchars($row['die_number']); ?></td>
                </tr>
                <tr>
                    <th>Priority</th>
                    <td><?php echo htmlspecialchars($row['priority']); ?></td>
                    <th>Supervisor</th>
                    <td><?php echo htmlspecialchars($row['employee_fname']) . ' ' . htmlspecialchars($row['employee_lname']); ?></td>
                </tr>
                <tr>
        <th>Creation Date</th>
        <td><?php echo htmlspecialchars(date('m-d-Y', strtotime($row['orange_tag_creation_date']))); ?></td>
        <th>Repair Technician(s)</th> <!-- Changed from Creation Time to Repair Technician(s) -->
        <td><?php echo htmlspecialchars($row['repair_technicians']); ?></td> <!-- Display the usernames -->
    </tr>
            </tbody>
        </table>

        <h1 style="text-align: center;">Repairs/Maintenance</h1> <!-- Centered the Repairs/Maintenance header -->
        <table class="repairs-maintenance">
            <tr>
                <th>Description</th>
                <td><span class="input-box"><?php echo htmlspecialchars($row['orange_tag_description']); ?></span></td>
            </tr>
            <tr>
                <th>Repairs Made</th>
                <td><span class="input-box"><?php echo htmlspecialchars($row['repairs_made']); ?></span></td>
            </tr>
            <tr>
                <th>Root Cause</th>
                <td><span class="input-box"><?php echo htmlspecialchars($row['root_cause']); ?></span></td>
            </tr>
            <tr>
                <th>Due Date</th>
                <td><span class="input-box"><?php echo htmlspecialchars(date('m-d-Y', strtotime($row['orange_tag_due_date']))); ?></span></td>
            </tr>
            <tr>
                <th>Total Repair Time</th>
                <td><span class="input-box"><?php echo htmlspecialchars($row['total_repair_time']); ?></span></td>
            </tr>
            <tr>
                <th>Equipment Down Time</th>
                <td><span class="input-box"><?php echo htmlspecialchars($row['equipment_down_time']); ?></span></td>
            </tr>
        </table>
        <table class="footer-table" style="width: 100%;">
    <tr>
        <td colspan="4" style="text-align: center;">This Document is for Reference Only</td>
    </tr>
</table>
    </div>
    <?php endwhile; ?>
    
</body>
</html>

<?php
// Send the output buffer and clean it
echo ob_get_clean();
?>