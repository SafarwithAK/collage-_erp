<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dept_name = $_POST['dept_name'];
    $dept_code = $_POST['dept_code'];

    $sql = "INSERT INTO departments (dept_name, dept_code) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $dept_name, $dept_code);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Department Added Successfully!'); window.location='welcome.php';</script>";
    } else {
        echo "<script>alert('⚠️ Error Adding Department'); window.location='welcome.php';</script>";
    }
}
?>
