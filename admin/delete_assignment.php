<?php
session_start();
include("db.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);
$conn->query("DELETE FROM assignments WHERE id=$id");
header("Location: welcome.php#assignments");
?>
