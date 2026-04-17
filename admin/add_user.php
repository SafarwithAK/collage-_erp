<?php
session_start();
include("db.php");

// Check login
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['add_user'])){
    $username = $conn->real_escape_string($_POST['username']);
    $email    = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role     = $conn->real_escape_string($_POST['role']);

    // Insert into database
    $sql = "INSERT INTO users (username, email, password, role, status) 
            VALUES ('$username', '$email', '$password', '$role', 1)";

    if($conn->query($sql)){
        $_SESSION['success_msg'] = "User added successfully!";
    } else {
        $_SESSION['success_msg'] = "Error: " . $conn->error;
    }

    header("Location: welcome.php#addUser");
    exit();
}
?>
