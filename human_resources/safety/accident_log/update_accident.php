<?php
// Assuming you have already connected to the database and started the session at the beginning of this file
include '../../../configurations/connection.php';
session_start();
// Check if the accident ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Accident ID is required.']);
    exit;
}

$accidentId = $_GET['id'];

// Prepare the SQL statement for updating the accident report
$stmt = $database->prepare("UPDATE `accident_report` SET `employee_id` = ?, `non_employee_name` = ?, `accident_type` = ?, `date_added` = ?, `accident_date` = ?, `accident_time` = ?, `shift` = ?, `time_sent_to_clinic` = ?, `date_sent_to_clinic` = ?, `accident_location` = ?, `time_of_report` = ?, `shift_start_time` = ?, `accident_description` = ?, `consecutive_days_worked` = ?, `proper_ppe_used` = ?, `proper_ppe_used_explain` = ?, `procedure_followed` = ?, `procedure_followed_explain` = ?, `potential_severity` = ?, `potential_severity_explain` = ?, `environmental_impact` = ?, `environmental_impact_explain` = ?, `prevent_reoccurance` = ?, `immediate_corrective_action` = ?, `irp_required` = ?, `irp_names` = ?, `equip_out_of_service` = ?, `equip_out_of_service_explain` = ? WHERE `accident_id` = ?");
$stmt->bind_param('issssssssssssissssssssssssssi', $_POST['employee_id'], $_POST['non_employee_name'], $_POST['accident_type'], $_POST['date_added'], $_POST['accident_date'], $_POST['accident_time'], $_POST['shift'], $_POST['time_sent_to_clinic'], $_POST['date_sent_to_clinic'], $_POST['accident_location'], $_POST['time_of_report'], $_POST['shift_start_time'], $_POST['accident_description'], $_POST['consecutive_days_worked'], $_POST['proper_ppe_used'], $_POST['proper_ppe_used_explain'], $_POST['procedure_followed'], $_POST['procedure_followed_explain'], $_POST['potential_severity'], $_POST['potential_severity_explain'], $_POST['environmental_impact'], $_POST['environmental_impact_explain'], $_POST['prevent_reoccurance'], $_POST['immediate_corrective_action'], $_POST['irp_required'], $_POST['irp_names'], $_POST['equip_out_of_service'], $_POST['equip_out_of_service_explain'], $accidentId);
$stmt->execute();

// Check if files were uploaded
if (isset($_FILES['fileUpload']['name']) && is_array($_FILES['fileUpload']['name']) && $_FILES['fileUpload']['error'][0] !== UPLOAD_ERR_NO_FILE) {
    // Loop through each file
    for ($i = 0; $i < count($_FILES['fileUpload']['name']); $i++) {
        // Check if file uploaded without errors
        if ($_FILES['fileUpload']['error'][$i] === UPLOAD_ERR_OK) {
            $fileTmpName = $_FILES['fileUpload']['tmp_name'][$i];
            $fileName = uniqid() . '-' . $_FILES['fileUpload']['name'][$i]; // Generate a unique name for the file

            // Check if directory exists
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/accident_files/';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            // Define the path where the file will be stored
            $filePath = $dir . $fileName;

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($fileTmpName, $filePath)) {
                $stmt = $database->prepare("INSERT INTO accident_files (accident_id, file_name, file_path) VALUES (?, ?, ?)");
                $stmt->bind_param('iss', $accidentId, $fileName, $filePath);
                $stmt->execute();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload file: ' . $_FILES['fileUpload']['name'][$i]]);
                exit;
            }
        } else {
            // Handle file upload error
            echo json_encode(['status' => 'error', 'message' => 'Error uploading file: ' . $_FILES['fileUpload']['name'][$i]]);
            exit;
        }
    }
    // If all files are processed successfully
    echo json_encode(['status' => 'success', 'message' => 'Accident report and files updated successfully.']);
} else {
    // If there are no files to upload, just update the accident report
    echo json_encode(['status' => 'success', 'message' => 'Accident report updated successfully. No files were uploaded.']);
}