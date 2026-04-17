<?php
include("db.php");

if(isset($_POST['reset'])){
    $email = $_POST['faculty_email'];
    $sql = "SELECT * FROM faculty WHERE faculty_email='$email'";
    $res = $conn->query($sql);
    if($res->num_rows > 0){
        $new_password = password_hash("123456", PASSWORD_BCRYPT); // temporary password
        $update = "UPDATE faculty SET faculty_password='$new_password' WHERE faculty_email='$email'";
        if($conn->query($update)){
            $msg = "Temporary password is <b>123456</b>. Please login and change it.";
            $msg_type = "success";
        }
    } else {
        $msg = "Email not found!";
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
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
.forgot-card {
    background: #fff;
    padding: 35px 25px;
    border-radius: 12px;
    width: 400px;
    box-shadow: 0px 8px 20px rgba(0,0,0,0.3);
    animation: fadeIn 1s ease-in-out;
    text-align: center;
}
.forgot-card h2 {
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

<div class="forgot-card">
    <h2><i class="fas fa-unlock-alt"></i> Forgot Password</h2>
    <?php if(isset($msg)) echo "<div class='msg $msg_type'>$msg</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <input type="email" name="faculty_email" class="form-control" placeholder="Enter your registered email" required>
        </div>
        <button type="submit" name="reset" class="btn btn-primary w-100"><i class="fas fa-redo"></i> Reset Password</button>
    </form>
    <div class="extra-links">
        <a href="faculty_login.php">Back to Login</a>
    </div>
</div>

</body>
</html>
