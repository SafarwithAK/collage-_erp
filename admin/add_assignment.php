<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $due_date = $conn->real_escape_string($_POST['due_date']);
    $faculty_id = intval($_POST['faculty_id']);

    // Insert into assignments table
    $sql = "INSERT INTO assignments (title, description, subject, due_date, faculty_id) 
            VALUES ('$title', '$description', '$subject', '$due_date', $faculty_id)";

    if ($conn->query($sql)) {
        echo "<script>alert('Assignment added successfully!'); window.location.href='welcome.php#assignments';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
