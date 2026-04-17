<?php
include("db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM faculty WHERE id = $id");
    $row = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['faculty_name'];
    $fid = $_POST['faculty_id'];
    $email = $_POST['faculty_email'];

    if (!empty($_POST['faculty_password'])) {
        $password = password_hash($_POST['faculty_password'], PASSWORD_DEFAULT);
        $sql = "UPDATE faculty SET faculty_name='$name', faculty_id='$fid', faculty_email='$email', faculty_password='$password' WHERE id=$id";
    } else {
        $sql = "UPDATE faculty SET faculty_name='$name', faculty_id='$fid', faculty_email='$email' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Faculty updated successfully!'); window.location='welcome.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Faculty</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
body {
    background:#f4f6f9;
    font-family:'Roboto', sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
}
.container {
    background:white;
    padding:30px 35px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.1);
    max-width:450px;
    width:100%;
    transition:0.3s;
}
.container:hover {
    box-shadow:0 12px 30px rgba(0,0,0,0.15);
}
h2 {
    color:#4e54c8;
    text-align:center;
    margin-bottom:25px;
    font-size:24px;
}
form input {
    width:100%;
    padding:14px 12px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:15px;
    transition:0.3s;
}
form input:focus {
    border-color:#4e54c8;
    outline:none;
}
input[type="submit"] {
    background: linear-gradient(90deg,#4e54c8,#8f94fb);
    color:white;
    font-weight:600;
    border:none;
    cursor:pointer;
    padding:14px 20px;
    border-radius:8px;
    margin-top:15px;
    transition:0.3s;
}
input[type="submit"]:hover {
    background: linear-gradient(90deg,#6c63ff,#a29bfe);
}
@media(max-width:500px){
    .container{ padding:25px 20px; }
}
</style>
</head>
<body>

<div class="container">
    <h2>Edit Faculty</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="text" name="faculty_name" value="<?php echo $row['faculty_name']; ?>" placeholder="Faculty Name" required>
        <input type="text" name="faculty_id" value="<?php echo $row['faculty_id']; ?>" placeholder="Faculty ID" required>
        <input type="email" name="faculty_email" value="<?php echo $row['faculty_email']; ?>" placeholder="Email" required>
        <input type="password" name="faculty_password" placeholder="New Password (leave blank to keep old)">
        <input type="submit" value="Update Faculty">
    </form>
</div>

</body>
</html>
