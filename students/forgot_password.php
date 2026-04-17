<?php
include("db.php");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['student_email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $sql = "UPDATE students SET student_password='$new_password' WHERE student_email='$email'";
    if ($conn->query($sql) === TRUE) {
        $msg = "✅ Password updated successfully!";
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password | Student Panel</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{
    height:100vh;
    background:#f5f6fa;
    display:flex;
    align-items:center;
    justify-content:center;
}

.card{
    width:420px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
}

.card-header{
    background:#212529;
    color:white;
    text-align:center;
    padding:20px;
    border-radius:15px 15px 0 0;
}

.card-header i{
    font-size:40px;
    color:#0d6efd;
}

.btn-primary{
    background:#0d6efd;
    border:none;
}

.btn-primary:hover{
    background:#0b5ed7;
}

a{
    text-decoration:none;
}
</style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <i class="fas fa-user-lock"></i>
        <h4 class="mt-2 mb-0">Student Panel</h4>
        <small>Reset Password</small>
    </div>

    <div class="card-body p-4">

        <?php if($msg!=""){ ?>
            <div class="alert alert-info text-center">
                <?= $msg ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" name="student_email" class="form-control"
                       placeholder="Enter registered email" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-key"></i> New Password
                </label>
                <input type="password" name="new_password" class="form-control"
                       placeholder="Enter new password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-sync-alt"></i> Update Password
            </button>
        </form>

        <hr>

        <div class="text-center">
            <a href="login.php">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>

    </div>
</div>

</body>
</html>
