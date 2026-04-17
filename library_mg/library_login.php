<?php
session_start();
include("db.php");

if(isset($_POST['login'])){
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM library_staff WHERE username='$username'");
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        if(password_verify($password, $row['password'])){
            $_SESSION['library_id'] = $row['id'];
            $_SESSION['library_name'] = $row['name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Library Staff Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:400px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="card-title text-center mb-3">📚 Library Staff Login</h3>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control mb-2" required>
                
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control mb-3" required>
                
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mt-2 text-center"><a href="library_forget.php">Forgot Password?</a></p>
            <p class="text-center mt-1">New? <a href="library_register.php">Register Here</a></p>
        </div>
    </div>
</div>
</body>
</html>
