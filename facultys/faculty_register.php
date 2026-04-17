<?php
include("db.php");

if(isset($_POST['register'])){
    $name = $_POST['faculty_name'];
    $email = $_POST['faculty_email'];
    $password = password_hash($_POST['faculty_password'], PASSWORD_BCRYPT);

    $check = "SELECT * FROM faculty WHERE faculty_email='$email'";
    $res = $conn->query($check);
    if($res->num_rows > 0){
        $msg = "Email already registered!";
        $msg_type = "error";
    } else {
        $sql = "INSERT INTO faculty (faculty_name, faculty_email, faculty_password) VALUES ('$name', '$email', '$password')";
        if($conn->query($sql)){
            header("Location: faculty_login.php?registered=1");
            exit();
        } else {
            $msg = "Error: ".$conn->error;
            $msg_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Faculty Registration</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: Arial, sans-serif;
}
.register-card {
    background: #fff;
    padding: 35px 25px;
    border-radius: 12px;
    width: 400px;
    box-shadow: 0px 8px 20px rgba(0,0,0,0.3);
    animation: fadeIn 1s ease-in-out;
    text-align: center;
}
.register-card h2 {
    margin-bottom: 20px;
    color: #2a5298;
}
.form-control {
    border-radius: 8px;
}
.btn-primary {
    background: #2a5298;
    border: none;
    border-radius: 8px;
    transition: 0.3s;
}
.btn-primary:hover {
    background: #1e3c72;
}
.extra-links {
    text-align: center;
    margin-top: 10px;
}
.extra-links a {
    text-decoration: none;
    color: #2a5298;
    font-weight: 500;
}
.msg {
    margin-bottom: 15px;
    font-weight: 500;
}
.msg.success { color: green; }
.msg.error { color: red; }
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<div class="register-card">
    <h2><i class="fas fa-user-plus"></i> Faculty Registration</h2>
    <?php if(isset($msg)) echo "<div class='msg $msg_type'>$msg</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="faculty_name" class="form-control" placeholder="Name" required>
        </div>
        <div class="mb-3">
            <input type="email" name="faculty_email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
            <input type="password" name="faculty_password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" name="register" class="btn btn-primary w-100"><i class="fas fa-user-plus"></i> Register</button>
    </form>
    <div class="extra-links">
        Already have an account? <a href="faculty_login.php">Login</a>
    </div>
</div>

</body>
</html>
