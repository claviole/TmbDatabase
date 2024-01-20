<?php
include '../configurations/connection.php'; 

$query = "SELECT * FROM Users WHERE user_type = 'maintenance-tech'";
$result = mysqli_query($database, $query);

$technicians = array();
while ($row = mysqli_fetch_assoc($result)) {
    $technicians[] = array(
        'id' => $row['id'],
        'username' => $row['username']
    );
}

header('Content-Type: application/json');
echo json_encode($technicians);
?>