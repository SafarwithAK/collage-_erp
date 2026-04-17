<?php
session_start();
include("db.php");

$msg = "";

if(isset($_POST['reset'])){

    $login_input = mysqli_real_escape_string($conn, trim($_POST['login_input'] ?? ''));
    $new_password = $_POST['new_password'] ?? '';

    if($login_input == "" || $new_password == ""){
        $msg = "❌ All fields required";
    } else {

        // Hostel + Active user only
        $sql = "SELECT * FROM users 
                WHERE (username='$login_input' OR email='$login_input')
                AND role='hostel'
                AND status=1
                LIMIT 1";

        $result = $conn->query($sql);

        if($result && $result->num_rows === 1){
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);

            $conn->query("UPDATE users SET password='$hashed' 
                          WHERE username='$login_input' OR email='$login_input'");

            $msg = "✅ Password reset successfully";

            // Auto redirect to login
            echo "<script>
                    setTimeout(function(){
                        window.location.href='hostel_login.php';
                    },2000);
                  </script>";
        } else {
            $msg = "❌ Invalid username/email or inactive account";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
<style>
body{
    font-family:Poppins;
    background:linear-gradient(135deg,#0984e3,#00b894);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.box{
    background:#fff;
    padding:30px;
    width:360px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
}
.box h2{
    text-align:center;
    margin-bottom:20px;
}
.box input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:10px;
    border:1px solid #ccc;
}
.box button{
    width:100%;
    padding:12px;
    background:#0984e3;
    border:none;
    color:#fff;
    border-radius:10px;
    font-weight:600;
}
.msg{
    text-align:center;
    margin-bottom:15px;
    font-size:14px;
}
</style>
</head>

<body>
<div class="box">
    <h2>🔐 Forgot Password</h2>

    <?php if($msg!=""){ ?>
        <div class="msg"><?php echo $msg; ?></div>
    <?php } ?>

    <form method="POST">
        <input type="text" name="login_input" placeholder="Enter Username or Email" required>
        <input type="password" name="new_password" placeholder="Enter New Password" required>
        <button type="submit" name="reset">Reset Password</button>
    </form>
</div>
</body>
</html>
