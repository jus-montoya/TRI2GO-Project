<?php

// Aiven Connection Details
$servername = "mysql-add1e04-justinesmontoya06.j.aivencloud.com"; 
$username   = "avnadmin";
$password   = "AVNS_qY9L-L4Nhj_0nGfhN1y"; 
$dbname     = "defaultdb"; 
$port       = 12132;

// Create connection including the port number for cloud hosting
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If you get here, your TRI2GO app is successfully connected!
?>
