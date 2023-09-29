<?php
include '../../connection.php';

if(isset($_POST['customerName'])){
    $customerName = $_POST['customerName'];
    $stmt = $database->prepare("SELECT * FROM invoice WHERE `Customer Name` = ?");
    $stmt->bind_param("s", $customerName);
    $stmt->execute();
    $result = $stmt->get_result();
    $invoices = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($invoices);
}
?>