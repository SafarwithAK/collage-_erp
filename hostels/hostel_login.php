<?php
session_start();
include("db.php"); // same folder me hai to ok, warna ../db.php

$error = "";

if(isset($_POST['login'])){

    $login_input = trim($_POST['login_input'] ?? '');
    $password    = $_POST['password'] ?? '';

    // Prepared statement (secure)
    $stmt = $conn->prepare(
        "SELECT * FROM users 
         WHERE (username = ? OR email = ?)
         AND role = 'hostel'
         LIMIT 1"
    );
    $stmt->bind_param("ss", $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result && $result->num_rows === 1){
        $user = $result->fetch_assoc();

        // ✅ status = 'active' + password verify
        if($user['status'] === 'active' && password_verify($password, $user['password'])){
            $_SESSION['hostel_user'] = $user['username'];
            $_SESSION['hostel_id']   = $user['id'];

            header("Location: hostel_dashboard.php");
            exit();
        } else {
            $error = "❌ Inactive your account contact admin";
        }
    } else {
        $error = "❌ Invalid username/email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hostel Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#00b894,#0984e3);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:0;
}
.login-box{
    background:#fff;
    width:360px;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
}
.login-box h2{
    text-align:center;
    margin-bottom:20px;
}
.login-box input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:10px;
    border:1px solid #ccc;
}
.login-box input:focus{
    border-color:#00b894;
    outline:none;
}
.login-box button{
    width:100%;
    padding:12px;
    background:linear-gradient(135deg,#00b894,#0984e3);
    border:none;
    border-radius:10px;
    color:#fff;
    font-weight:600;
    cursor:pointer;
}
.error{
    background:#ffe6e6;
    color:#d63031;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    text-align:center;
}
</style>
</head>

<body>

<div class="login-box">
    <h2>🏨 Hostel Login</h2>

    <?php if($error!=""){ ?>
        <div class="error"><?php echo $error; ?></div>
        <script>
            // error ek baar dikhe, phir refresh
            setTimeout(() => {
                window.location.replace("hostel_login.php");
            }, 2000);
        </script>
    <?php } ?>

    <form method="POST">
        <input type="text" name="login_input" placeholder="Enter Username or Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <p style="text-align:center;margin-top:10px;">
        <a href="hostel_forgot_password.php">Forgot Password?</a>
    </p>
</div>

</body>
</html>
