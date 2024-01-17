<?php
include '../configurations/connection.php'; 

$tagId = $_GET['orange_tag_id'];

$query = "SELECT * FROM repair_parts WHERE orange_tag_id = ?";
$stmt = $database->prepare($query);
$stmt->bind_param("s", $tagId);
$stmt->execute();
$result = $stmt->get_result();

$parts = [];
while ($row = $result->fetch_assoc()) {
    $parts[] = $row;
}

echo json_encode($parts);
?>