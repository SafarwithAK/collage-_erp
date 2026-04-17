<?php
session_start();
include("db.php");

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])) {
    die("User ID missing");
}

$id = (int)$_GET['id'];

// Delete user
if($conn->query("DELETE FROM users WHERE id=$id")){
    echo "<script>alert('User deleted successfully'); window.location='welcome.php#usersList';</script>";
} else {
    echo "Error deleting user: " . $conn->error;
}
?>
