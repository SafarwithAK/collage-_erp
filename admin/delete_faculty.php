<?php
include("db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM faculty WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Faculty deleted successfully!'); window.location='welcome.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
