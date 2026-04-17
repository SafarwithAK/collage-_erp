<?php
session_start();
include("db.php");

if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM assignments WHERE id=? AND faculty_id=?");
    $stmt->bind_param("ii", $id, $_SESSION['faculty_id']);

    if($stmt->execute()){
        echo "<script>alert('🗑️ Assignment Deleted Successfully'); window.location='faculty_dashboard.php?page=assignments';</script>";
    } else {
        echo "<script>alert('❌ Error deleting assignment'); window.location='faculty_dashboard.php?page=assignments';</script>";
    }
} else {
    echo "<script>alert('Invalid Request'); window.location='faculty_dashboard.php?page=assignments';</script>";
}
?>
