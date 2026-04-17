<?php
session_start();
include("db.php");

if(!empty($_SESSION['username']) && !empty($_SESSION['role']) && $_SESSION['role']=='accounts'){
    header("Location: account_dashboard.php");
    exit();
}


$error = "";

if(isset($_POST['login'])){
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND role='accounts' LIMIT 1";
    $result = $conn->query($sql);

    if($result && $result->num_rows==1){
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: account_dashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Account not found or not authorized!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Accounts Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body { font-family:'Poppins',sans-serif; background: linear-gradient(135deg,#6c5ce7,#00b894); display:flex; justify-content:center; align-items:center; height:100vh; }
.login-box { background: rgba(255,255,255,0.95); padding:30px 25px; border-radius:15px; width:350px; box-shadow:0 10px 25px rgba(0,0,0,0.2); }
.login-box h2 { text-align:center; margin-bottom:20px; color:#2d3436; }
.login-box form { display:flex; flex-direction:column; gap:15px; }
.login-box input { padding:12px 15px; border-radius:8px; border:1px solid #ccc; font-size:15px; outline:none; transition:0.3s; }
.login-box input:focus { border-color:#6c5ce7; box-shadow:0 0 5px rgba(108,92,231,0.5); }
.login-box input[type="submit"] { background:linear-gradient(135deg,#6c5ce7,#00b894); color:white; font-weight:600; border:none; cursor:pointer; transition:0.3s; }
.login-box input[type="submit"]:hover { background:linear-gradient(135deg,#4e54c8,#00b894); }
.error-msg { color:red; text-align:center; font-weight:500; }
</style>
</head>
<body>
<div class="login-box">
    <h2>Accounts Login</h2>
    <?php if($error != ""){ echo "<p class='error-msg'>$error</p>"; } ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>
</div>
</body>
</html>
