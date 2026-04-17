<?php
$host = "localhost";
$user = "root";   // XAMPP me by default root hota hai
$pass = "Ajit@9334";       // password empty hota hai agar aapne set nahi kiya
$dbname = "college_erp";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>
