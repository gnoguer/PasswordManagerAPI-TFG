<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "passwordmanager";
 
 
//creating a new connection object using mysqli 
$conn = new mysqli($servername, $username, $password, $database);
 
//if there is some error connecting to the database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>