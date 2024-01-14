<?php
include '../configurations/connection.php'; 

$query = "SELECT COUNT(*) as total FROM `orange_tag`";
$result = mysqli_query($database, $query);
$data = mysqli_fetch_assoc($result);
echo $data['total'];
?>