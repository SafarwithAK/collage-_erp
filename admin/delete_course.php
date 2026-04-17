<?php
include("db.php");

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid course ID'); window.location='welcome.php#courses';</script>";
    exit();
}

$id = intval($_GET['id']);

// Delete the course
$stmt = $conn->prepare("DELETE FROM courses WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('✅ Course Deleted'); window.location='welcome.php#courses';</script>";
} else {
    echo "<script>alert('❌ Error deleting course'); window.location='welcome.php#courses';</script>";
}
?>
