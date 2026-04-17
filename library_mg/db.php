<?php
$host = "localhost";
$user = "root";
$pass = "Ajit@9334";
$db = "college_erp";

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}
?>
