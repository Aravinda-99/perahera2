<?php

$servername = "localhost:4306";
$username = "root"; 
$password = "";     
$dbname = "peraheara_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>