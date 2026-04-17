<?php
include("db.php");

if(isset($_POST['reset'])){
    $username = $conn->real_escape_string($_POST['username']);
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $res = $conn->query("SELECT * FROM library_staff WHERE username='$username'");
    if($res->num_rows > 0){
        $conn->query("UPDATE library_staff SET password='$new_password' WHERE username='$username'");
        $success = "Password reset successfully! <a href='library_login.php'>Login</a>";
    } else {
        $error = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Library Staff Forget Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:400px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="card-title text-center mb-3">🔒 Reset Password</h3>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
            <form method="POST">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control mb-2" required>
                
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control mb-3" required>
                
                <button type="submit" name="reset" class="btn btn-warning w-100">Reset Password</button>
            </form>
            <p class="mt-2 text-center"><a href="library_login.php">Back to Login</a></p>
        </div>
    </div>
</div>
</body>
</html>
