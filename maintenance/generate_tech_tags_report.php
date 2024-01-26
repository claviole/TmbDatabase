<?php
session_start();
include '../configurations/connection.php';

header('Content-Type: text/html; charset=utf-8');

// Check if the user is logged in and has the right user type
if (!isset($_SESSION['user']) || !in_array($_SESSION['user_type'], ['super-admin', 'maintenance-tech'])) {
    exit('Access Denied');
}

// Get the selected technician's ID from the query parameter
$selectedTechId = isset($_GET['techId']) ? $_GET['techId'] : '';

$locationCode = $_SESSION['location_code'];

// Query to get all tags assigned to the selected technician, with open tags first
$query = "SELECT ot.*, GROUP_CONCAT(u.username SEPARATOR ', ') AS tech_names
          FROM orange_tag ot
          LEFT JOIN Users u ON FIND_IN_SET(u.id, ot.repair_technician)
          WHERE ot.location_code = ? AND FIND_IN_SET(?, ot.repair_technician)
          GROUP BY ot.orange_tag_id
          ORDER BY ot.date_closed IS NULL DESC, ot.date_closed ASC, ot.orange_tag_creation_date DESC";

$stmt = $database->prepare($query);
$stmt->bind_param('ss', $locationCode, $selectedTechId);
$stmt->execute();
$result = $stmt->get_result();

// Start the output buffer
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tech Tags Report</title>
    <!-- Include the same style as in the previous report -->
    <style>
        /* ... [Same styles as in the previous report] ... */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            color: black;
        }
        .report-container {
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.85em;
        }
        #printButton {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
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
    <div class="report-container">
        <h1>Tech Tags Report</h1>
        <table>
            <thead>
                <tr>
                    <th>Orange Tag ID</th>
                    <th>Creation Date</th>
                    <th>Closed Date</th>
                    <th>Ticket Type</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['orange_tag_id']); ?></td>
                    <td><?php echo htmlspecialchars(date('m-d-Y', strtotime($row['orange_tag_creation_date']))); ?></td>
                    <td><?php echo htmlspecialchars($row['date_closed'] ? date('m-d-Y', strtotime($row['date_closed'])) : ''); ?></td>
                    <td><?php echo htmlspecialchars($row['ticket_type']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="footer">
            Report generated on <?php echo date('m/d/Y h:i:s a'); ?>
        </div>
    </div>
</body>
</html>

<?php
// Send the output buffer and clean it
echo ob_get_clean();
?>