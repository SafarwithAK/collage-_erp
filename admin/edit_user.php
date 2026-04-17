<?php
session_start();
include("db.php");

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])) {
    die("User ID missing");
}

$id = (int)$_GET['id'];

// Fetch existing user data
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
if(!$user) die("User not found");

// Handle POST update
if(isset($_POST['update_user'])){
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);

    // Optional: update password only if provided
    $password_sql = "";
    if(!empty($_POST['password'])){
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_sql = ", password='$password'";
    }

    $sql = "UPDATE users SET 
            username='$username',
            email='$email',
            role='$role'
            $password_sql
            WHERE id=$id";

    if($conn->query($sql)){
        echo "<script>alert('User updated successfully'); window.location='welcome.php#usersList';</script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #6c5ce7, #00b894);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

.container {
    background: #fff;
    padding: 30px 25px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    width: 400px;
    max-width: 90%;
    position: relative;
}

.container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #2d3436;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

form input, form select {
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 15px;
    transition: all 0.3s ease;
}

form input:focus, form select:focus {
    border-color: #6c5ce7;
    outline: none;
}

form input[type="submit"] {
    background: linear-gradient(135deg, #6c5ce7, #00b894);
    border: none;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

form input[type="submit"]:hover {
    background: linear-gradient(135deg, #00b894, #6c5ce7);
}

.back-btn {
    position: absolute;
    top: 15px;
    left: 20px;
    background: #fdcb6e;
    color: #2d3436;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}

.back-btn:hover {
    background: #e17055;
    color: white;
}
</style>
</head>
<body>

<div class="container">
    <a href="welcome.php#usersList" class="back-btn">← Back</a>
    <h2>Edit User</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <input type="password" name="password" placeholder="New Password (leave blank to keep)">
        <select name="role" required>
            <option value="admin" <?php if($user['role']=='admin') echo "selected"; ?>>Admin</option>
            <option value="accounts" <?php if($user['role']=='accounts') echo "selected"; ?>>Accounts</option>
            <option value="hostel" <?php if($user['role']=='hostel') echo "selected"; ?>>Hostel</option>
            <option value="transport" <?php if($user['role']=='transport') echo "selected"; ?>>Transport</option>
        </select>
        <input type="submit" name="update_user" value="Update User">
    </form>
</div>

</body>
</html>
