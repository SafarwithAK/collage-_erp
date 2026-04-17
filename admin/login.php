<?php
session_start();
include("db.php");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            header("Location: welcome.php");
            exit();
        } else {
            echo "<script>alert('❌ Invalid Password!');</script>";
        }
    } else {
        echo "<script>alert('⚠️ User not found! Please register.'); window.location='register.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .login-card {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(15px);
      border-radius: 18px;
      padding: 40px 35px;
      width: 380px;
      text-align: center;
      box-shadow: 0 0 25px rgba(0, 255, 200, 0.3);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 0 40px rgba(0, 255, 200, 0.6);
    }

    .login-card h2 {
      margin-bottom: 20px;
      font-size: 28px;
      color: #fff;
      text-shadow: 0 0 10px rgba(0,255,200,0.7);
    }

    .icon {
      font-size: 55px;
      margin-bottom: 18px;
      color: #00ffc3;
      text-shadow: 0 0 12px #00ffc3;
    }

    .input-group {
      position: relative;
      margin: 18px 0;
    }

    .input-group input {
      width: 100%;
      padding: 14px 12px;
      background: transparent;
      border: 2px solid rgba(255, 255, 255, 0.4);
      border-radius: 10px;
      color: #fff;
      font-size: 16px;
      outline: none;
      transition: 0.3s;
    }

    .input-group label {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      font-size: 14px;
      color: rgba(255, 255, 255, 0.7);
      pointer-events: none;
      transition: 0.3s;
    }

    .input-group input:focus {
      border-color: #00ffc3;
      box-shadow: 0 0 12px #00ffc3;
    }

    .input-group input:focus + label,
    .input-group input:valid + label {
      top: -8px;
      left: 10px;
      font-size: 12px;
      color: #00ffc3;
      background: #2c5364;
      padding: 0 6px;
      border-radius: 4px;
    }

    .login-card button {
      width: 100%;
      padding: 14px;
      margin-top: 22px;
      background: linear-gradient(135deg, #00ffc3, #00aaff);
      border: none;
      border-radius: 10px;
      color: #fff;
      font-size: 17px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .login-card button:hover {
      background: linear-gradient(135deg, #00aaff, #00ffc3);
      box-shadow: 0 0 15px #00ffc3;
    }

    .login-card p {
      margin-top: 16px;
      font-size: 14px;
      color: #ddd;
    }

    .login-card a {
      color: #00ffc3;
      text-decoration: none;
      font-weight: 500;
    }

    .login-card a:hover {
      text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 420px) {
      .login-card {
        width: 90%;
        padding: 30px 25px;
      }
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="icon">🔐</div>
    <h2>Admin Login</h2>
    <form method="POST">
      <div class="input-group">
        <input type="text" name="username" required>
        <label>Username or Email</label>
      </div>
      <div class="input-group">
        <input type="password" name="password" required>
        <label>Password</label>
      </div>
      <button type="submit" name="login">Login</button>
    </form>
    <p><a href="forgot.php">Forgot Password?</a></p>
    <p>Don't have an account? <a href="register.php">Register</a></p>
  </div>
</body>
</html>
