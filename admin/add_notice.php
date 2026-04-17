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

    $title   = $conn->real_escape_string($_POST['title']);
    $message = $conn->real_escape_string($_POST['message']);   // SHORT MESSAGE
    $content = $conn->real_escape_string($_POST['content']);   // FULL CONTENT

    // Created By (faculty/admin username)
    $created_by = $_SESSION['username'];

    // Insert into notices table
    $sql = "INSERT INTO notices (title, message, content, created_by) 
            VALUES ('$title', '$message', '$content', '$created_by')";

    if ($conn->query($sql)) {
        echo "<script>
                alert('Notice added successfully!');
                window.location.href='welcome.php#notices';
              </script>";
    } else {
        echo 'Error: ' . $conn->error;
    }
}
?>
