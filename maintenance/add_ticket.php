<?php
// Include your database connection file here
include '../configurations/connection.php';

// Sanitize input
$orange_tag_id = mysqli_real_escape_string($database, $_POST['orange_tag_id']);
$ticket_type = mysqli_real_escape_string($database, $_POST['ticket_type']);
$originator = mysqli_real_escape_string($database, $_POST['originator']);
$location = mysqli_real_escape_string($database, $_POST['location']);
$priority = mysqli_real_escape_string($database, $_POST['priority']);
$line_name = mysqli_real_escape_string($database, $_POST['line_name']);
$die_number = mysqli_real_escape_string($database, $_POST['die_number']);
$section = mysqli_real_escape_string($database, $_POST['section']);
$supervisor = mysqli_real_escape_string($database, $_POST['supervisor']);
$orange_tag_creation_date = mysqli_real_escape_string($database, $_POST['orange_tag_creation_date']);
$orange_tag_creation_time = mysqli_real_escape_string($database, $_POST['orange_tag_creation_time']);
$orange_tag_due_date = mysqli_real_escape_string($database, $_POST['orange_tag_due_date']);
$repairs_made = mysqli_real_escape_string($database, $_POST['repairs_made']);
$root_cause = mysqli_real_escape_string($database, $_POST['root_cause']);
$equipment_down_time = mysqli_real_escape_string($database, $_POST['equipment_down_time']);
$total_repair_time = mysqli_real_escape_string($database, $_POST['total_repair_time']);
$area_cleaned = mysqli_real_escape_string($database, $_POST['area_cleaned']);
$follow_up_necessary = mysqli_real_escape_string($database, $_POST['follow_up_necessary']);
$parts_needed = mysqli_real_escape_string($database, $_POST['parts_needed']);
$reviewed_by_supervisor = mysqli_real_escape_string($database, $_POST['reviewed_by_supervisor']);
$reviewed_by_safety_coordinator = mysqli_real_escape_string($database, $_POST['reviewed_by_safety_coordinator']);
$supervisor_review_date = mysqli_real_escape_string($database, $_POST['supervisor_review_date']);
$safety_coordinator_review_date = mysqli_real_escape_string($database, $_POST['safety_coordinator_review_date']);
$verified = mysqli_real_escape_string($database, $_POST['verified']);
$date_verified = mysqli_real_escape_string($database, $_POST['date_verified']);
$orange_tag_description = mysqli_real_escape_string($database, $_POST['orange_tag_description']);
$repair_technician = '';
if (isset($_POST['repair_technician']) && is_array($_POST['repair_technician'])) {
    $repair_technician = mysqli_real_escape_string($database, implode(',', $_POST['repair_technician']));
}
$total_cost = mysqli_real_escape_string($database, $_POST['total_cost']);
$ticket_status = mysqli_real_escape_string($database, $_POST['ticket_status']);
$work_order_number = mysqli_real_escape_string($database, $_POST['work_order_number']);

// Insert data into the database
$query = "INSERT INTO `orange_tag` (`orange_tag_id`, `ticket_type`, `originator`, `location`, `priority`,`line_name`,`die_number`, `section`, `supervisor`, `orange_tag_creation_date`, `orange_tag_creation_time`, `orange_tag_due_date`, `repairs_made`, `root_cause`, `equipment_down_time`, `total_repair_time`, `area_cleaned`, `follow_up_necessary`, `parts_needed`, `reviewed_by_supervisor`, `reviewed_by_safety_coordinator`, `supervisor_review_date`, `safety_coordinator_review_date`, `verified`, `date_verified`,`orange_tag_description`, `repair_technician`, `total_cost`, `ticket_status`, `work_order_number`) VALUES ('$orange_tag_id', '$ticket_type', '$originator', '$location', '$priority','$line_name','$die_number', '$section', '$supervisor', '$orange_tag_creation_date', '$orange_tag_creation_time', '$orange_tag_due_date', '$repairs_made', '$root_cause', '$equipment_down_time', '$total_repair_time', '$area_cleaned', '$follow_up_necessary', '$parts_needed', '$reviewed_by_supervisor', '$reviewed_by_safety_coordinator', '$supervisor_review_date', '$safety_coordinator_review_date', '$verified', '$date_verified', '$orange_tag_description', '$repair_technician','$total_cost', '$ticket_status', '$work_order_number')";

$result = mysqli_query($database, $query);

if ($result) {
    echo "Ticket added successfully";
} else {
    echo "Error: " . mysqli_error($database);
}
?>