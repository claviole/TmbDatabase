<?php
include '../configurations/connection.php'; 

$orange_tag_id = $_GET['orange_tag_id'];

$query = "SELECT * FROM `orange_tag` WHERE `orange_tag_id` = '$orange_tag_id'";
$result = mysqli_query($database, $query);

$response = array('exists' => mysqli_num_rows($result) > 0 ? true : false);

echo json_encode($response);
?>