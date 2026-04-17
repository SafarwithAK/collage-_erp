<?php
session_start();
include("db.php");

if(!isset($_SESSION['username'])) exit('Unauthorized');

if(isset($_POST['id'], $_POST['status'])){
    $id = (int)$_POST['id'];
    $status = (int)$_POST['status'];

    $sql = "UPDATE users SET status=$status WHERE id=$id";
    if($conn->query($sql)){
        echo "User status updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
