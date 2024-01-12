<?php
include '../../configurations/connection.php';

$quoteId = $_POST['quoteId'];
$version = $_POST['version'];

$query = "UPDATE `invoice` SET `award_status` = 'Awarded' WHERE `invoice_id` = ? AND `version` = ?";
$stmt = $database->prepare($query);
$stmt->bind_param("ss", $quoteId, $version);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Quote awarded successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}
?>