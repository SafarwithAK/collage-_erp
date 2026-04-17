<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Input sanitize
    $name = trim($_POST['student_name']);
    $sid = trim($_POST['student_id']);
    $email = trim($_POST['student_email']);
    $password = password_hash($_POST['student_password'], PASSWORD_BCRYPT);
    $course = trim($_POST['course'] ?? '');
    $department = trim($_POST['department'] ?? '');

    // Prepare statement for security
    $stmt = $conn->prepare("INSERT INTO students (student_name, student_id, student_email, student_password, course, department) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $sid, $email, $password, $course, $department);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Student added successfully!'); window.location='welcome.php?page=students';</script>";
        exit();
    } else {
        echo "<script>alert('❌ Error: ".$conn->error."'); window.history.back();</script>";
        exit();
    }
}
?>
