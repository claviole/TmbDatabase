<?php
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection

// Insert form data into the accident_report table
$stmt = $database->prepare("INSERT INTO `accident_report` (`employee_id`,`non_employee_name`, `accident_type`, `date_added`, `accident_date`, `accident_time`, `shift`, `time_sent_to_clinic`, `date_sent_to_clinic`, `accident_location`, `time_of_report`, `shift_start_time`, `accident_description`, `consecutive_days_worked`, `proper_ppe_used`, `proper_ppe_used_explain`, `procedure_followed`, `procedure_followed_explain`, `potential_severity`, `potential_severity_explain`, `enverionmental_impact`, `enverionmental_impact_explain`, `prevent_reoccurance`, `immediate_corrective_action`, `irp_required`, `irp_names`, `equip_out_of_service`, `equip_out_of_service_explain`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param( 'issssssssssssissssssssssssss', $_POST['employee_id'],$_POST['non_employee_name'] ,$_POST['accident_type'], $_POST['date_added'], $_POST['accident_date'], $_POST['accident_time'], $_POST['shift'], $_POST['time_sent_to_clinic'], $_POST['date_sent_to_clinic'], $_POST['accident_location'], $_POST['time_of_report'], $_POST['shift_start_time'], $_POST['accident_description'], $_POST['consecutive_days_worked'], $_POST['proper_ppe_used'], $_POST['proper_ppe_used_explain'], $_POST['procedure_followed'], $_POST['procedure_followed_explain'], $_POST['potential_severity'], $_POST['potential_severity_explain'], $_POST['enverionmental_impact'], $_POST['enverionmental_impact_explain'], $_POST['prevent_reoccurance'], $_POST['immediate_corrective_action'], $_POST['irp_required'], $_POST['irp_names'], $_POST['equip_out_of_service'], $_POST['equip_out_of_service_explain']);
$stmt->execute();

// Get the ID of the accident
$accidentId = $database->insert_id;

// Check if file was uploaded
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $file = $_FILES['file'];

    // Generate a unique name for the file
    $fileName = uniqid() . '-' . $file['name'];

    // Check if directory exists
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/accident_files/';
    if (!is_dir($dir)) {
        // If not, create the directory
        mkdir($dir, 0777, true);
    }

    // Define the path where the file will be stored
    $filePath = $dir . $fileName;

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Insert file info into the database
        $stmt = $database->prepare("INSERT INTO accident_files (accident_id, file_name, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $accidentId, $fileName, $filePath);
        $stmt->execute();

        // Return a JSON response
        echo json_encode([
            'status' => 'success',
            'message' => 'File uploaded successfully.',
            'accident_id' => $accidentId,
            'file_name' => $fileName,
            'file_path' => $filePath
        ]);
    } else {
        // Return a JSON response
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to upload file.'
        ]);
    }
} else {
    // Return a JSON response
    echo json_encode([
        'status' => 'success',
        'message' => 'Form submitted successfully without file.',
        'accident_id' => $accidentId
    ]);
}
?>