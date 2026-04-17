<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['faculty_name'];
    $fid = $_POST['faculty_id'];
    $email = $_POST['faculty_email'];
    $password = password_hash($_POST['faculty_password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO faculty (faculty_name, faculty_id, faculty_email, faculty_password) 
            VALUES ('$name', '$fid', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Faculty added successfully!'); window.location='welcome.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
