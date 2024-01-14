<?php
// Include your database connection file here
include '../configurations/connection.php';

// Sanitize input
$orange_tag_id = mysqli_real_escape_string($database, $_POST['orange_tag_id']);
$ticket_type = mysqli_real_escape_string($database, $_POST['ticket_type']);
$originator = mysqli_real_escape_string($database, $_POST['originator']);
$location = mysqli_real_escape_string($database, $_POST['location']);
$priority = mysqli_real_escape_string($database, $_POST['priority']);
$section = mysqli_real_escape_string($database, $_POST['section']);
$supervisor = mysqli_real_escape_string($database, $_POST['supervisor']);
$maintenance_supervisor = mysqli_real_escape_string($database, $_POST['maintenance_supervisor']);
$safety_coordinator = mysqli_real_escape_string($database, $_POST['safety_coordinator']);
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

// Update data in the database
$query = "UPDATE `orange_tag` SET `ticket_type`='$ticket_type', `originator`='$originator', `location`='$location', `priority`='$priority', `section`='$section', `supervisor`='$supervisor', `maintenance_supervisor`='$maintenance_supervisor', `safety_coordinator`='$safety_coordinator', `orange_tag_creation_date`='$orange_tag_creation_date', `orange_tag_creation_time`='$orange_tag_creation_time', `orange_tag_due_date`='$orange_tag_due_date', `repairs_made`='$repairs_made', `root_cause`='$root_cause', `equipment_down_time`='$equipment_down_time', `total_repair_time`='$total_repair_time', `area_cleaned`='$area_cleaned', `follow_up_necessary`='$follow_up_necessary', `parts_needed`='$parts_needed', `reviewed_by_supervisor`='$reviewed_by_supervisor', `reviewed_by_safety_coordinator`='$reviewed_by_safety_coordinator', `supervisor_review_date`='$supervisor_review_date', `safety_coordinator_review_date`='$safety_coordinator_review_date', `verified`='$verified', `date_verified`='$date_verified', `orange_tag_description`='$orange_tag_description', `repair_technician`='$repair_technician', `total_cost`='$total_cost', `ticket_status`='$ticket_status', `work_order_number`='$work_order_number' WHERE `orange_tag_id`='$orange_tag_id'";

$result = mysqli_query($database, $query);

if ($result) {
    echo "Ticket updated successfully";
} else {
    echo "Error: " . mysqli_error($database);
}
?>