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
    width: auto;
    color: white;
    position: sticky;
    top: 0;
}

.form-container td {
    width: auto;
    background-color: #FFFFFF;
    text-wrap: normal;
}

.form-container label {
    max-width: 2px;
    margin-right: 5px;
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
@media (max-width: 600px) {
    .form-container {
        overflow-x: auto; /* Enable horizontal scrolling */
    }

    .form-container table {
        min-width: 600px; /* Set a minimum width for the table */
    }

    /* Adjust font sizes and padding for smaller screens */
    .form-container th, 
    .form-container td {
        font-size: 12px; /* Reduce font size */
        padding: 8px; /* Reduce padding */
    }
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
/* Style the select boxes */
.form-container select {
    width: 100%; /* Full width */
    padding: 8px 12px; /* Padding for aesthetics */
    margin: 8px 0; /* Margin for spacing */
    display: inline-block; /* For proper alignment */
    border: 1px solid #ccc; /* Border color */
    border-radius: 4px; /* Rounded corners */
    box-sizing: border-box; /* Include padding and border in the width */
    -webkit-appearance: none; /* Remove default styling specific to WebKit browsers */
    -moz-appearance: none; /* Remove default styling specific to Mozilla browsers */
    appearance: none; /* Remove default arrow for all browsers */
    background-color: #f8f8f8; /* Background color */
    font-size: 16px; /* Text size */
    line-height: 1.5; /* Line height for text */
    color: #333; /* Text color */
}

/* Style the select boxes with a custom arrow */
.form-container select {
    background-image: url('data:image/svg+xml;charset=US-ASCII,<svg width="12px" height="12px" viewBox="0 0 4 5" xmlns="http://www.w3.org/2000/svg"><path fill="%23333" d="M2 0L0 2h4z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 12px 12px;
}

/* Change the background color of select boxes on hover */
.form-container select:hover {
    background-color: #e8e8e8;
}

/* Change the border color of select boxes on focus */
.form-container select:focus {
    border-color: #aaa;
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
                        <select name="question1_ranking">
                        <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question1_a_one"></textarea></td>
                    <td><textarea name="question1_a_two"></textarea></td>
                    <td><textarea name="question1_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question2_text" value="Aisles and Walkways are Adequately Lit">
                    <td>Aisles and Walkways are Adequately Lit</td>
                    <td>
                    <select name="question2_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question2_a_one"></textarea></td>
                    <td><textarea name="question2_a_two"></textarea></td>
                    <td><textarea name="question2_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question3_text" value="Clear Access to Exits & Fire Extinguishers">
                    <td>Clear Access to Exits & Fire Extinguishers</td>
                    <td>
                    <select name="question3_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question3_a_one"></textarea></td>
                    <td><textarea name="question3_a_two"></textarea></td>
                    <td><textarea name="question3_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question4_text" value="Fire Extinguishers: Easily Accessible. Inspection Dates are within past month.">
                    <td>Fire Extinguishers Easily Accessible. Inspection Dates are within past month.</td>
                    <td>
                    <select name="question4_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question4_a_one"></textarea></td>
                    <td><textarea name="question4_a_two"></textarea></td>
                    <td><textarea name="question4_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question5_text" value="Emergency Site Maps are Strategically Posted">
                    <td>Emergency Site Maps are Strategically Posted</td>
                    <td>
                        <select name="question5_ranking">
                        <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                       
                    </td>
                    <td><textarea name="question5_a_one"></textarea></td>
                    <td><textarea name="question5_a_two"></textarea></td>
                    <td><textarea name="question5_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question6_text" value="General Housekeeping: Floors vacuumed, surfaces are dusted, and work stations are organized.">
                    <td>General Housekeeping: Floors vacuumed, surfaces are dusted, and work stations are organized.</td>
                    <td>
                    <select name="question6_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question6_a_one"></textarea></td>
                    <td><textarea name="question6_a_two"></textarea></td>
                    <td><textarea name="question6_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question7_text" value="Electrical Panels are closed.">
                    <td>Electrical Panels are closed.</td>
                    <td>
                    <select name="question7_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question7_a_one"></textarea></td>
                    <td><textarea name="question7_a_two"></textarea></td>
                    <td><textarea name="question7_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question8_text" value="Unnecessary/ Outdated Signs, Displays, Notices, & Alerts have been removed">
                    <td>Unnecessary/ Outdated Signs, Displays, Notices, & Alerts have been removed</td>
                    <td>
                    <select name="question8_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question8_a_one"></textarea></td>
                    <td><textarea name="question8_a_two"></textarea></td>
                    <td><textarea name="question8_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question9_text" value="Visitor Sign In Log (lobby area) is posted and being used.">
                    <td>Visitor Sign In Log (lobby area) is posted and being used.</td>
                    <td>
                    <select name="question9_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question9_a_one"></textarea></td>
                    <td><textarea name="question9_a_two"></textarea></td>
                    <td><textarea name="question9_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question10_text" value="PPE stocked and available">
                    <td>PPE stocked and available</td>
                    <td>
                    <select name="question10_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question10_a_one"></textarea></td>
                    <td><textarea name="question10_a_two"></textarea></td>
                    <td><textarea name="question10_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question11_text" value="Spill Kits - Complete Inventory (note date of last inspection)">
                    <td>"Spill Kits - Complete Inventory (note date of last inspection)"</td>
                    <td>
                    <select name="question11_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question11_a_one"></textarea></td>
                    <td><textarea name="question11_a_two"></textarea></td>
                    <td><textarea name="question11_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question12_text" value="Load Limits Posted on Cranes and hooks / grabbers">
                    <td>Load Limits Posted on Cranes and Hooks / Grabbers</td>
                    <td>
                    <select name="question12_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question12_a_one"></textarea></td>
                    <td><textarea name="question12_a_two"></textarea></td>
                    <td><textarea name="question12_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question13_text" value="Storage/Elevated Platforms have 42 inch safety rails">
                    <td>Storage/Elevated Platforms have 42" safety rails</td>
                    <td>
                    <select name="question13_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question13_a_one"></textarea></td>
                    <td><textarea name="question13_a_two"></textarea></td>
                    <td><textarea name="question13_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question14_text" value="Dumpsters Over Flowing / Leaking">
                    <td>Dumpsters Over Flowing / Leaking </td>
                    <td>
                    <select name="question14_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question14_a_one"></textarea></td>
                    <td><textarea name="question14_a_two"></textarea></td>
                    <td><textarea name="question14_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question15_text" value="Seasonal Concerns">
                    <td>Seasonal Concerns</td>
                    <td>
                    <select name="question15_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question15_a_one"></textarea></td>
                    <td><textarea name="question15_a_two"></textarea></td>
                    <td><textarea name="question15_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question16_text" value="Dock Doors Closed or Safety Chains provided">
                    <td>Dock Doors Closed or Safety Chains provided</td>
                    <td>
                    <select name="question16_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question16_a_one"></textarea></td>
                    <td><textarea name="question16_a_two"></textarea></td>
                    <td><textarea name="question16_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question17_text" value="Safety Mats Provided at Workstations. Condition of Mats?">
                    <td>Safety Mats Provided at Workstations. Condition of Mats?</td>
                    <td>
                    <select name="question17_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question17_a_one"></textarea></td>
                    <td><textarea name="question17_a_two"></textarea></td>
                    <td><textarea name="question17_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question18_text" value="Electrical Panels have 36 inches of Clearance?">
                    <td>Electrical Panels have 36" Clearance?</td>
                    <td>
                    <select name="question18_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question18_a_one"></textarea></td>
                    <td><textarea name="question18_a_two"></textarea></td>
                    <td><textarea name="question18_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question19_text" value="Pre-Shift Check Sheets - Completed & Accurate">
                    <td>Pre-Shift Check Sheets - Completed & Accurate</td>
                    <td>
                    <select name="question19_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question19_a_one"></textarea></td>
                    <td><textarea name="question19_a_two"></textarea></td>
                    <td><textarea name="question19_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question20_text" value="Propane storage cage locked & proper distance away from building">
                    <td>Propane storage cage locked & proper distance away from building</td>
                    <td>
                    <select name="question20_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question20_a_one"></textarea></td>
                    <td><textarea name="question20_a_two"></textarea></td>
                    <td><textarea name="question20_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question21_text" value="SDS labels on Proprietary Containers">
                    <td>SDS labels on Proprietary Containers</td>
                    <td>
                    <select name="question21_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question21_a_one"></textarea></td>
                    <td><textarea name="question21_a_two"></textarea></td>
                    <td><textarea name="question21_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question22_text" value="Extinguishers Fully Charged">
                    <td>Extinguishers Fully Charged</td>
                    <td>
                    <select name="question22_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question22_a_one"></textarea></td>
                    <td><textarea name="question22_a_two"></textarea></td>
                    <td><textarea name="question22_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question23_text" value="First Aid Supplies Stocked">
                    <td>First Aid Supplies Stocked</td>
                    <td>
                    <select name="question23_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question23_a_one"></textarea></td>
                    <td><textarea name="question23_a_two"></textarea></td>
                    <td><textarea name="question23_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question24_text" value="Trauma Kit Stocked">
                    <td>Trauma Kit Stocked </td>
                    <td>
                    <select name="question24_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question24_a_one"></textarea></td>
                    <td><textarea name="question24_a_two"></textarea></td>
                    <td><textarea name="question24_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question25_text" value="Emergency Contact Numbers Posted (Ambulance, Cab, Medical Provider, Ext)">
                    <td>Emergency Contact Numbers Posted (Ambulance, Cab, Medical Provider, Ext).</td>
                    <td>
                    <select name="question3_ranking">
                    <option value="" selected disabled>Select an rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="NA">N/A</option>
                        </select>
                    </td>
                    <td><textarea name="question25_a_one"></textarea></td>
                    <td><textarea name="question25_a_two"></textarea></td>
                    <td><textarea name="question25_a_three"></textarea></td>
                </tr>
                <tr>
                    <input type="hidden" name="question26_text" value="Light curtain cards filled out?">
                    <td>Light curtain cards filled out?</td>
                    <td>
    <select name="question26_a_one">
        <option value="" selected disabled>Select an option</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>
                    </td>
                    <td><textarea name="question26_a_two"></textarea></td>
    
</td>
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
    <input type="hidden" name="question27_text" value="Were there any situations that pose an imminent danger or hazard to workers or environment?">
    <td>Were there any situations that pose an imminent danger or hazard to workers or environment? (yes or no will automatically calculate based off your answers)</td>
    <td>
        <select name="question27_a_one">
            <option value="" selected disabled>Select an option</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </td>
    <td><textarea name="question27_a_two"></textarea></td>
</tr>
<tr>
    <input type="hidden" name="question28_text" value="Were these situations reported to the Supervisor or Committee or Emergency Response Team for immediate attention?">
    <td>Were these situations reported to the Supervisor or Committee or Emergency Response Team for immediate attention?</td>
    <td>
        <select name="question28_a_one">
            <option value="" selected disabled>Select an option</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </td>
    <td><textarea name="question28_a_two"></textarea></td>
</tr>
            </tbody>
        </table>
        <div class="submit-button">
            <input type="submit" value="Submit Audit">
        </div>

    </form>
</div>
<script>

document.addEventListener('DOMContentLoaded', function() {
    const questionsToMonitor = [
        'question1_ranking', 'question2_ranking', 'question3_ranking',
        'question4_ranking', 'question7_ranking', 'question10_ranking',
        'question18_ranking', 'question22_ranking', 'question23_ranking',
        'question24_ranking', 'question25_ranking'
    ];
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        let allRanked = true;
        // Update to use select for question27_a_one
        let question27SelectedValue = document.querySelector('select[name="question27_a_one"]').value;
        let question27YesChecked = question27SelectedValue === 'yes';
        let question27CommentFilled = document.querySelector('textarea[name="question27_a_two"]').value.trim() !== '';

        // Check if all questions have a ranking selected
        document.querySelectorAll('tbody tr').forEach(function(row) {
            const selects = Array.from(row.querySelectorAll('select'));
            const isRanked = selects.some(select => select.value !== '');
            if (!isRanked) {
                allRanked = false;
                row.classList.add('highlight'); // Highlight the row
            }
        });

        // Additional check for question 27
        if (question27YesChecked && !question27CommentFilled) {
            Swal.fire({
                title: 'Incomplete Form',
                text: 'Please provide more information for question 27 as you have reported something that poses an imminent danger or hazard to workers or the facilities.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return; // Stop the form submission
        }

        if (allRanked) {
            // Proceed with form submission if all checks pass
            let formData = new FormData(form);

            fetch('submit_audit.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Safety audit submitted successfully.',
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../index.php';
                        }
                    });
                } else {
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

    // Function to check the questions and update question 27
    function updateQuestion27() {
        let question27SetToYes = false;

        // List of question names to monitor
        const questionsToMonitor = [
            'question1_ranking', 'question2_ranking', 'question3_ranking',
            'question4_ranking', 'question7_ranking', 'question10_ranking',
            'question18_ranking', 'question22_ranking', 'question23_ranking',
            'question24_ranking', 'question25_ranking'
        ];

        // Check each question
        for (let questionName of questionsToMonitor) {
            // Find the select element for each question
            const selectElement = document.querySelector(`select[name="${questionName}"]`);
            if (selectElement && selectElement.value === '1') {
                question27SetToYes = true;
                break; // Stop checking if we've found a "1"
            }
        }

        // Update question 27's select box based on the check
        const question27SelectBox = document.querySelector('select[name="question27_a_one"]');
        if (question27SetToYes) {
            question27SelectBox.value = 'yes';
        } else {
            question27SelectBox.value = 'no';
        }
    }

    // Attach the check function to change events for all monitored questions
    questionsToMonitor.forEach(function(questionName) {
        const selectElement = document.querySelector(`select[name="${questionName}"]`);
        if (selectElement) {
            selectElement.addEventListener('change', updateQuestion27);
        }
    });
});
</script>
</body>
</html>


