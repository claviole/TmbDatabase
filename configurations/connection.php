<?php
    $PEPPER="Krdh%RA-kPm1248)v2y52WqE&+b}r7T6p/Jn@.?wA(L8";
    // Mailjet API credentials
$APIKey = '75714be908e64ce7a2686eeca5afb921';
$APISecret = '1b9d487cd5b4c212b6b95e28c768815e';
    $database= new mysqli("localhost","root","","tmb_invoice");
    if ($database->connect_error){
        die("Connection failed:  ".$database->connect_error);
    }

?>
