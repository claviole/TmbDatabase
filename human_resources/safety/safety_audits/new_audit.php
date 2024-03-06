<?php
session_start();
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');


if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('human-resources' || 'super-admin' || 'supvervisor')){
    // Not logged in or not an admin, redirect to login page
    header("Location: /index.php");
    exit();
}

$location_code = $_SESSION['location_code'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>S.A.F.E.</title>
    <style>
        .return-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1B145D;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-weight: 700;
        }

        .return-button:hover {
            background-color: #111;
        }

        .return-button-container {
            text-align: right;
            margin-right: 10px;
        }
        .form-container {
    max-width: 95%;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-container table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.form-container th, .form-container td {
    text-align: left;
    padding: 8px;
    border-bottom: 1px solid #ddd;
}

.form-container th {
    background-color: #007bff;
    color: white;
    position: sticky;
    top: 0;
}

.form-container td {
    background-color: #FFFFFF;
}

.form-container label {
    margin-right: 10px;
}

input[type="radio"] {
    margin-right: 5px;
}

textarea {
    width: 95%;
    height: 60px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: vertical; /* Allows the user to vertically resize the textarea (not horizontally) */
}

input[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .form-container table, .form-container thead, .form-container tbody, .form-container th, .form-container td, .form-container tr { 
        display: block; 
    }
    .form-container thead tr { 
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    .form-container tr { border: 1px solid #ccc; }
    .form-container td { 
        border: none;
        border-bottom: 1px solid #eee; 
        position: relative;
        padding-left: 50%; 
        text-align: right;
    }
    .form-container td:before { 
        /* Now like a table header */
        position: absolute;
        /* Top/left values mimic padding */
        top: 6px;
        left: 6px;
        width: 45%; 
        padding-right: 10px; 
        white-space: nowrap;
        text-align: left;
        font-weight: bold;
    }
    /* Label the data */
    .form-container td:nth-of-type(1):before { content: "Area of Inspection"; }
    .form-container td:nth-of-type(2):before { content: "Ranking"; }
    .form-container td:nth-of-type(3):before { content: "Source of Danger or Hazard"; }
    .form-container td:nth-of-type(4):before { content: "Reported to Whom and When"; }
    .form-container td:nth-of-type(5):before { content: "Corrective Action Plan"; }
}
.additional-info textarea {
    width: 100%;
    margin-bottom: 10px; /* Add some space below each textarea */
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #e9ecef; /* A light grey background to indicate read-only */
    resize: none; /* Prevent resizing */
}

/* Make the textareas read-only if you don't want them to be editable */
.additional-info textarea[readonly] {
    cursor: default;
    color: #212529; /* Dark text for better readability */
    font-size: 0.9em; /* Slightly smaller text */
}
body {
    background: url('<?php echo $backgroundImage; ?>') no-repeat center center fixed; 
    background-size: cover; /* Cover the entire page */
}
.audit-info input {

    margin-bottom: 10px; /* Add some space below each input */
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.submit-button {
    text-align: center;
    margin-top: 20px;
}

.highlight {
    background-color: #ffcccc; /* Light red color */
}
    </style>
    
</head>
<body>
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Safety Dashboard</a>
</div>


<h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
    <img src="<?php echo $companyHeaderImage; ?>" alt="company header" width="30%" height="20%">
 
 </h1>
</div>
<div class="form-container"> 
<form action = "submit_audit.php" method="post">
    <div class="audit-info"
        <label for = "safety_auditor">Safety Auditor:</label>
        <input type="text" id="safety_auditor" name="safety_auditor" required>
        <label for = "audit_date">Audit Date:</label>
        <input type="date" id="date_completed" name="date_completed" required>
        <label for = "facility">Facility:</label>
        <input type="text" id="facility" name="facility" required>
        <label for ="department">Department:</label>
        <input type="text" id="department" name="department" required>
        <input type="hidden" name="location_code" value="<?php echo $location_code; ?>">
    </div>

    <div class="additional-info">
            <textarea name="rating_scale" rows="2" readonly>RATING Scale: 1 – 3
                 1) Needs Immediate Attention. (2) Adequate but needs attention (15 day completion deadline). (3) Meets Standards</textarea>
            <textarea name="area_location" rows="2" readonly>Identify the exact area and location by using identifiers such as department, column number, work station, office, etc.</textarea>
        </div>
 <!-- Table for questions -->
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
                <!-- Repeat this row for each question -->
                <tr>
                    <input type="hidden" name="question1_text" value="No Trip Hazards. Walkways are clear">
                    <td>No Trip Hazards. Walkways are clear</td>
                    <td>
                        <label><input type="radio" name="question1_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question1_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question1_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question1_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question1_a_one"></textarea></td>
                    <td><textarea name="question1_a_two"></textarea></td>
                    <td><textarea name="question1_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question2_text" value="Aisles and Walkways are Adequately Lit">
                    <td>Aisles and Walkways are Adequately Lit</td>
                    <td>
                        <label><input type="radio" name="question2_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question2_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question2_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question2_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question2_a_one"></textarea></td>
                    <td><textarea name="question2_a_two"></textarea></td>
                    <td><textarea name="question2_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question3_text" value="Clear Access to Exits & Fire Extinguishers">
                    <td>Clear Access to Exits & Fire Extinguishers</td>
                    <td>
                        <label><input type="radio" name="question3_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question3_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question3_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question3_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question3_a_one"></textarea></td>
                    <td><textarea name="question3_a_two"></textarea></td>
                    <td><textarea name="question3_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question4_text" value="Fire Extinguishers: Easily Accessible. Inspection Dates are within past month.">
                    <td>Fire Extinguishers Easily Accessible. Inspection Dates are within past month.</td>
                    <td>
                        <label><input type="radio" name="question4_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question4_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question4_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question4_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question4_a_one"></textarea></td>
                    <td><textarea name="question4_a_two"></textarea></td>
                    <td><textarea name="question4_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question5_text" value="Emergency Site Maps are Strategically Posted">
                    <td>Emergency Site Maps are Strategically Posted</td>
                    <td>
                        <label><input type="radio" name="question5_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question5_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question5_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question5_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question5_a_one"></textarea></td>
                    <td><textarea name="question5_a_two"></textarea></td>
                    <td><textarea name="question5_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question6_text" value="General Housekeeping: Floors vacuumed, surfaces are dusted, and work stations are organized.">
                    <td>General Housekeeping: Floors vacuumed, surfaces are dusted, and work stations are organized.</td>
                    <td>
                        <label><input type="radio" name="question6_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question6_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question6_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question6_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question6_a_one"></textarea></td>
                    <td><textarea name="question6_a_two"></textarea></td>
                    <td><textarea name="question6_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question7_text" value="Electrical Panels are closed.">
                    <td>Electrical Panels are closed.</td>
                    <td>
                        <label><input type="radio" name="question7_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question7_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question7_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question7_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question7_a_one"></textarea></td>
                    <td><textarea name="question7_a_two"></textarea></td>
                    <td><textarea name="question7_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question8_text" value="Unnecessary/ Outdated Signs, Displays, Notices, & Alerts have been removed">
                    <td>Unnecessary/ Outdated Signs, Displays, Notices, & Alerts have been removed</td>
                    <td>
                        <label><input type="radio" name="question8_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question8_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question8_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question8_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question8_a_one"></textarea></td>
                    <td><textarea name="question8_a_two"></textarea></td>
                    <td><textarea name="question8_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question9_text" value="Visitor Sign In Log (lobby area) is posted and being used.">
                    <td>Visitor Sign In Log (lobby area) is posted and being used.</td>
                    <td>
                        <label><input type="radio" name="question9_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question9_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question9_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question9_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question9_a_one"></textarea></td>
                    <td><textarea name="question9_a_two"></textarea></td>
                    <td><textarea name="question9_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question10_text" value="PPE stocked and available">
                    <td>PPE stocked and available</td>
                    <td>
                        <label><input type="radio" name="question10_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question10_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question10_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question10_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question10_a_one"></textarea></td>
                    <td><textarea name="question10_a_two"></textarea></td>
                    <td><textarea name="question10_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question11_text" value="Spill Kits - Complete Inventory (note date of last inspection)">
                    <td>"Spill Kits - Complete Inventory (note date of last inspection)"</td>
                    <td>
                        <label><input type="radio" name="question11_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question11_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question11_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question11_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question11_a_one"></textarea></td>
                    <td><textarea name="question11_a_two"></textarea></td>
                    <td><textarea name="question11_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question12_text" value="Load Limits Posted on Cranes and hooks / grabbers">
                    <td>Load Limits Posted on Cranes and Hooks / Grabbers</td>
                    <td>
                        <label><input type="radio" name="question12_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question12_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question12_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question12_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question12_a_one"></textarea></td>
                    <td><textarea name="question12_a_two"></textarea></td>
                    <td><textarea name="question12_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question13_text" value="Storage/Elevated Platforms have 42 inch safety rails">
                    <td>Storage/Elevated Platforms have 42" safety rails</td>
                    <td>
                        <label><input type="radio" name="question13_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question13_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question13_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question13_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question13_a_one"></textarea></td>
                    <td><textarea name="question13_a_two"></textarea></td>
                    <td><textarea name="question13_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question14_text" value="Dumpsters Over Flowing / Leaking">
                    <td>Dumpsters Over Flowing / Leaking </td>
                    <td>
                        <label><input type="radio" name="question14_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question14_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question14_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question14_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question14_a_one"></textarea></td>
                    <td><textarea name="question14_a_two"></textarea></td>
                    <td><textarea name="question14_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question15_text" value="Seasonal Concerns">
                    <td>Seasonal Concerns</td>
                    <td>
                        <label><input type="radio" name="question15_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question15_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question15_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question15_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question15_a_one"></textarea></td>
                    <td><textarea name="question15_a_two"></textarea></td>
                    <td><textarea name="question15_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question16_text" value="Dock Doors Closed or Safety Chains provided">
                    <td>Dock Doors Closed or Safety Chains provided</td>
                    <td>
                        <label><input type="radio" name="question16_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question16_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question16_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question16_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question16_a_one"></textarea></td>
                    <td><textarea name="question16_a_two"></textarea></td>
                    <td><textarea name="question16_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question17_text" value="Safety Mats Provided at Workstations. Condition of Mats?">
                    <td>Safety Mats Provided at Workstations. Condition of Mats?</td>
                    <td>
                        <label><input type="radio" name="question17_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question17_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question17_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question17_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question17_a_one"></textarea></td>
                    <td><textarea name="question17_a_two"></textarea></td>
                    <td><textarea name="question17_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question18_text" value="Electrical Panels have 36 inches of Clearance?">
                    <td>Electrical Panels have 36" Clearance?</td>
                    <td>
                        <label><input type="radio" name="question18_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question18_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question18_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question18_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question18_a_one"></textarea></td>
                    <td><textarea name="question18_a_two"></textarea></td>
                    <td><textarea name="question18_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question19_text" value="Pre-Shift Check Sheets - Completed & Accurate">
                    <td>Pre-Shift Check Sheets - Completed & Accurate</td>
                    <td>
                        <label><input type="radio" name="question19_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question19_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question19_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question19_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question19_a_one"></textarea></td>
                    <td><textarea name="question19_a_two"></textarea></td>
                    <td><textarea name="question19_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question20_text" value="Propane storage cage locked & proper distance away from building">
                    <td>Propane storage cage locked & proper distance away from building</td>
                    <td>
                        <label><input type="radio" name="question20_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question20_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question20_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question20_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question20_a_one"></textarea></td>
                    <td><textarea name="question20_a_two"></textarea></td>
                    <td><textarea name="question20_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question21_text" value="SDS labels on Proprietary Containers">
                    <td>SDS labels on Proprietary Containers</td>
                    <td>
                        <label><input type="radio" name="question21_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question21_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question21_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question21_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question21_a_one"></textarea></td>
                    <td><textarea name="question21_a_two"></textarea></td>
                    <td><textarea name="question21_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question22_text" value="Extinguishers Fully Charged">
                    <td>Extinguishers Fully Charged</td>
                    <td>
                        <label><input type="radio" name="question22_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question22_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question22_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question22_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question22_a_one"></textarea></td>
                    <td><textarea name="question22_a_two"></textarea></td>
                    <td><textarea name="question22_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question23_text" value="First Aid Supplies Stocked">
                    <td>First Aid Supplies Stocked</td>
                    <td>
                        <label><input type="radio" name="question23_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question23_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question23_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question23_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question23_a_one"></textarea></td>
                    <td><textarea name="question23_a_two"></textarea></td>
                    <td><textarea name="question23_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question24_text" value="Trauma Kit Stocked">
                    <td>Trauma Kit Stocked </td>
                    <td>
                        <label><input type="radio" name="question24_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question24_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question24_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question24_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question24_a_one"></textarea></td>
                    <td><textarea name="question24_a_two"></textarea></td>
                    <td><textarea name="question24_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question25_text" value="Emergency Contact Numbers Posted (Ambulance, Cab, Medical Provider, Ext)">
                    <td>Emergency Contact Numbers Posted (Ambulance, Cab, Medical Provider, Ext).</td>
                    <td>
                        <label><input type="radio" name="question25_ranking" value="1"> 1</label>
                        <label><input type="radio" name="question25_ranking" value="2"> 2</label>
                        <label><input type="radio" name="question25_ranking" value="3"> 3</label>
                        <label><input type="radio" name="question25_ranking" value="NA"> N/A</label>
                    </td>
                    <td><textarea name="question25_a_one"></textarea></td>
                    <td><textarea name="question25_a_two"></textarea></td>
                    <td><textarea name="question25_a_three"></textarea></td>
                </tr>

                <!-- Add more rows as needed for each question -->
            </tbody>

        </table>
        <label for="additional_comments">Additional Comments:</label>
        <textarea name="additional_comments" rows="4"></textarea>
        <table>
            <thead>
                <tr>
                    <th>Safety Check Questions for Review</th>
                    <th>Yes or No</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <input type="hidden" name="question26_text" value="Were there any situations that pose an imminent danger or hazard to workers or environment?">
                    <td>Were there any situations that pose an imminent danger or hazard to workers or environment?</td>
                    <td><input type="radio" name="question26_a_one" value="yes"> Yes <input type="radio" name="question26_a_one" value="no"> No</td>
                    <td><textarea name="question26_a_two"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question27_text" value="Were these situations reported to the Supervisor or Committee or Emergency Response Team for immediate attention?">
                    <td>Were these situations reported to the Supervisor or Committee or Emergency Response Team for immediate attention?</td>
                    <td><input type="radio" name="question27_a_one value="yes"> Yes <input type="radio" name="question27_a_one" value="no"> No</td>
                    <td><textarea name="question27_a_two"></textarea></td>
            </tbody>
        </table>
        <div class="submit-button">
            <input type="submit" value="Submit Audit">
        </div>

    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Check if all questions have a ranking selected
        let allRanked = true;
        document.querySelectorAll('tbody tr').forEach(function(row) {
            const radios = Array.from(row.querySelectorAll('input[type="radio"]'));
            const isRanked = radios.some(radio => radio.checked);
            if (!isRanked) {
                allRanked = false;
                row.classList.add('highlight'); // Highlight the row
            }
        });

        if (allRanked) {
            // Use FormData to collect all form data
            let formData = new FormData(form);

            // Use fetch API to submit the form data via POST to submit_audit.php
            fetch('submit_audit.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Assuming submit_audit.php returns JSON
            .then(data => {
                // Check the response from submit_audit.php
                if(data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Safety audit submitted successfully.',
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Optionally, reload the form or redirect
                            window.location.href = '../index.php';
                        }
                    });
                } else {
                    // Handle failure
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was a problem submitting the audit.',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
            Swal.fire({
                title: 'Incomplete Form',
                text: 'Please provide at least a ranking for each question.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    });
});
</script>
</body>
</html>


