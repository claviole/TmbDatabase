<?php
include '../configurations/connection.php'; 

$query = "SELECT * FROM employees WHERE job_title IN (16,18,25)";
$result = mysqli_query($database, $query);

$technicians = array();
while ($row = mysqli_fetch_assoc($result)) {
    $technicians[] = array(
        'employee_id' => $row['employee_id'],
        'employee_fname' => $row['employee_fname'],
        'employee_lname' => $row['employee_lname']
    );
}

header('Content-Type: application/json');
echo json_encode($technicians);
?>