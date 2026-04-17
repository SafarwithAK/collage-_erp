<?php
session_start();
include("db.php");

if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $subject = trim($_POST['subject']);
    $due_date = $_POST['due_date'];
    $faculty_id = $_SESSION['faculty_id'];

    if (!empty($title) && !empty($description) && !empty($subject) && !empty($due_date)) {
        $stmt = $conn->prepare("INSERT INTO assignments (title, description, subject, due_date, faculty_id) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $title, $description, $subject, $due_date, $faculty_id);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Assignment Added Successfully'); window.location='faculty_dashboard.php?page=assignments';</script>";
        } else {
            echo "<script>alert('❌ Error while adding assignment'); window.location='faculty_dashboard.php?page=add_assignment';</script>";
        }
    } else {
        echo "<script>alert('⚠️ All fields are required!'); window.location='faculty_dashboard.php?page=add_assignment';</script>";
    }
}
?>
