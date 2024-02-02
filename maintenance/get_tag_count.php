<?php
session_start();
if(!isset($_SESSION['user'])){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}
include '../configurations/connection.php'; 

$query = "SELECT MAX(CAST(SUBSTRING(orange_tag_id, 4) AS UNSIGNED)) as max_id FROM `orange_tag`";
$result = mysqli_query($database, $query);
$data = mysqli_fetch_assoc($result);
echo $data['max_id'] + 1;
?>