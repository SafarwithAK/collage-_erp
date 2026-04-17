<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['student_email'];
    $password = $_POST['student_password'];

    $sql = "SELECT * FROM students WHERE student_email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Check if account is active
        if($row['status'] == 0){
            $error = "❌ Your account is inactive. Please contact admin.";
        } else if (password_verify($password, $row['student_password'])) {
            $_SESSION['student_id'] = $row['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid password!";
        }
    } else {
        $error = "❌ No account found with this email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Login</title>
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
    .login-card {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0px 8px 20px rgba(0,0,0,0.3);
        width: 400px;
        animation: fadeIn 1s ease-in-out;
    }
    .login-card h2 {
        text-align: center;
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
    .error-msg {
        color: red;
        text-align: center;
        margin-bottom: 10px;
        font-weight: bold;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <div class="login-card">
    <h2><i class="fas fa-user-graduate"></i> Student Login</h2>
    <?php if(isset($error)) { echo "<p class='error-msg'>$error</p>"; } ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" name="student_email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-lock"></i> Password</label>
            <input type="password" name="student_password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt"></i> Login</button>
    </form>
    <div class="extra-links">
        <a href="forgot_password.php">Forgot Password?</a> | 
        <a href="register.php">Register</a>
    </div>
  </div>
</body>
</html>
