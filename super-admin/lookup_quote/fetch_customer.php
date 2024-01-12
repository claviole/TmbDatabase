<?php
include '../../configurations/connection.php';

if(isset($_POST["customerName"])) {
    $customerName = $_POST["customerName"];
    $stmt = $database->prepare("SELECT * FROM Customer WHERE `Customer Name` = ?");
    $stmt->bind_param("s", $customerName);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $customerData = $result->fetch_assoc();
        echo json_encode($customerData);
    }
    $stmt->close();
}
?>