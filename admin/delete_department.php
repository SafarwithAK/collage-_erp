<?php
include("../db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM departments WHERE id=$id");
    echo "<script>alert('❌ Department Deleted'); window.location='view_department.php';</script>";
}
?>
