<?php

    $database= new mysqli("localhost","root","","tmb_invoice");
    if ($database->connect_error){
        die("Connection failed:  ".$database->connect_error);
    }

?>