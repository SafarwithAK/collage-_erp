<?php
include("db.php"); // DB connection

if(isset($_POST['register'])){
    $name = $conn->real_escape_string($_POST['name']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check = $conn->query("SELECT * FROM library_staff WHERE username='$username'");
    if($check->num_rows > 0){
        $error = "Username already exists!";
    } else {
        $conn->query("INSERT INTO library_staff (name, username, password) VALUES ('$name','$username','$password')");
        $success = "Registration successful! <a href='library_login.php'>Login</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Library Staff Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:500px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="card-title text-center mb-3">📚 Library Staff Register</h3>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
            <form method="POST">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control mb-2" required>
                
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control mb-2" required>
                
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control mb-3" required>
                
                <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
            </form>
            <p class="mt-2 text-center">Already registered? <a href="library_login.php">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>
