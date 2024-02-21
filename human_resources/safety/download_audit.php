<?php
session_start();
include '../../configurations/connection.php';

// Check if the user is logged in and has the correct user type
if (!isset($_SESSION['user']) || $_SESSION['user_type'] != 'Human Resources' && $_SESSION['user_type'] != 'super-admin') {
    header("Location: ../../../index.php");
    exit();
}

// Check if the checklist ID is set
if (!isset($_GET['checklist_id'])) {
    echo "No checklist ID provided.";
    exit();
}

$checklist_id = $_GET['checklist_id'];

// Fetch the checklist data
$checklist_query = "SELECT * FROM safety_checklist WHERE checklist_id = ?";
$stmt = $database->prepare($checklist_query);
$stmt->bind_param("i", $checklist_id);
$stmt->execute();
$checklist_result = $stmt->get_result();
$checklist_data = $checklist_result->fetch_assoc();

// Fetch the checklist answers data
$answers_query = "SELECT * FROM safety_checklist_answers WHERE checklist_id = ?";
$stmt = $database->prepare($answers_query);
$stmt->bind_param("i", $checklist_id);
$stmt->execute();
$answers_result = $stmt->get_result();
$answers_data = $answers_result->fetch_all(MYSQLI_ASSOC);

// Start output buffering to capture the HTML content
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safety Audit Report</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f4;
    }
    .report-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    h1 {
        color: #333;
        text-align: center; /* Center the title */
    }
    .rating-scale {
        margin-top: 20px;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        text-align: left;
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #4CAF50;
        color: white;
    }
    .highlight {
        background-color: #f2f2f2;
    }
    .print-button {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }
    .footer {
        margin-top: 40px;
    }
    ol {
        padding-left: 20px;
    }

    @media print {
        .print-button {
            display: none; /* Hide the print button when printing */
        }
    }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button">Print Report</button>
    <div class="report-container">
        <h1>Safety Audit Report</h1>
        <p><strong>Auditor:</strong> <?php echo htmlspecialchars($checklist_data['safety_auditor']); ?></p>
        <p><strong>Date Completed:</strong> <?php echo htmlspecialchars($checklist_data['date_completed']); ?></p>
        <p><strong>Facility:</strong> <?php echo htmlspecialchars($checklist_data['facility']); ?></p>
        <p><strong>Department:</strong> <?php echo htmlspecialchars($checklist_data['department']); ?></p>
      

        <div class="rating-scale">
            <strong>RATING Scale: 1-3</strong><br>
            (1) Needs Immediate Attention. <br>
            (2) Adequate but needs attention (15 day completion deadline). <br>
            (3) Meets Standards
        </div>

        <table>
            <thead>
                <tr>
                    <th>Area of Inspection</th>
                    <th>Ranking</th>
                    <th>Source of Danger or Hazard</th>
                    <th>Reported to Whom and When</th>
                    <th>Corrective Action Plan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($answers_data as $answer): ?>
                <tr class="<?php echo $answer['ranking'] > 2 ? 'highlight' : ''; ?>">
                    <td><?php echo htmlspecialchars($answer['question']); ?></td>
                    <td><?php echo htmlspecialchars($answer['ranking']); ?></td>
                    <td><?php echo htmlspecialchars($answer['a_one']); ?></td>
                    <td><?php echo htmlspecialchars($answer['a_two']); ?></td>
                    <td><?php echo htmlspecialchars($answer['a_three']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
           
        </table>
        <p><strong>Additional Comments:</strong> <?php echo htmlspecialchars($checklist_data['additional_comments']); ?></p>
        <div class="footer">
            <h2>Instruction for Reporting & Tracking Open Issues</h2>
            <ol>
                <li>Health & Safety Committees will track open issues on a master copy of the Open Issues / Action Items Matrix.</li>
                <li>Plant Managers will provide support for completing corrective actions by appointing appropriate resources to open issue.</li>
                <li>All open issues will be listed on the Health & Safety Committee Meeting.</li>
            </ol>
        </div>
    </div>
</body>
</html>

<?php
// Capture the output and clean the buffer
$html_content = ob_get_clean();

// Set headers to trigger the download
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=safety_audit_report.html");
header("Content-Length: " . strlen($html_content));

// Output the content
echo $html_content;
exit();
?>