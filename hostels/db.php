<?php
$host = "localhost";
$user = "root";   
$pass = "Ajit@9334";       
$dbname = "college_erp";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>
