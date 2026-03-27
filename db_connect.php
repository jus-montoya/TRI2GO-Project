<?php

$servername = "mysql-add1e04-justinesmontoya06.j.aivencloud.com"; 
$username = "avnadmin";
$password = "<redacted>"; 
$dbname = "defaultdb"; // Note: Aiven's default is "defaultdb" unless you renamed it
$port = 12132;

// Create connection including the port
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Force SSL if your code requires it, but mysqli often handles it.
?>
