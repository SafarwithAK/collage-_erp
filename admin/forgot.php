<?php
include("db.php");

if (isset($_POST['reset'])) {
    $email = $_POST['email'];
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_pass, $email);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "<script>alert('✅ Password Reset Successful! Please Login.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('⚠️ Email not found! Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <style>
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
      background: linear-gradient(-45deg, #36b9cc, #4e73df, #1cc88a, #f6c23e);
      background-size: 400% 400%;
      animation: gradientBG 12s ease infinite;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .reset-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      padding: 40px 30px;
      border-radius: 16px;
      width: 380px;
      text-align: center;
      color: #fff;
      box-shadow: 0 0 20px rgba(54,185,204,0.4);
      transition: 0.3s;
    }

    .reset-card:hover {
      box-shadow: 0 0 35px rgba(54,185,204,0.7);
      transform: translateY(-6px);
    }

    .reset-card h2 {
      margin-bottom: 18px;
      font-size: 26px;
      text-shadow: 0 0 10px rgba(78,115,223,0.6);
    }

    .icon {
      font-size: 50px;
      margin-bottom: 15px;
      color: #00ffc3;
      text-shadow: 0 0 12px #00ffc3;
    }

    .input-group {
      position: relative;
      margin: 16px 0;
    }

    .input-group input {
      width: 100%;
      padding: 14px 12px;
      border: 2px solid rgba(255,255,255,0.4);
      border-radius: 10px;
      background: transparent;
      color: #fff;
      font-size: 15px;
      outline: none;
      transition: 0.3s;
    }

    .input-group label {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: rgba(255,255,255,0.7);
      font-size: 14px;
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

    .reset-card button {
      width: 100%;
      padding: 14px;
      margin-top: 20px;
      border: none;
      border-radius: 10px;
      background: linear-gradient(135deg, #4e73df, #36b9cc);
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .reset-card button:hover {
      background: linear-gradient(135deg, #36b9cc, #4e73df);
      box-shadow: 0 0 15px #36b9cc;
    }

    .reset-card p {
      margin-top: 15px;
      font-size: 14px;
      color: #eee;
    }

    .reset-card a {
      color: #f6c23e;
      text-decoration: none;
      font-weight: 500;
    }

    .reset-card a:hover {
      text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 420px) {
      .reset-card {
        width: 90%;
        padding: 30px 25px;
      }
    }
  </style>
</head>
<body>
  <div class="reset-card">
    <div class="icon">🔑</div>
    <h2>Reset Password</h2>
    <form method="POST">
      <div class="input-group">
        <input type="email" name="email" required>
        <label>Email Address</label>
      </div>
      <div class="input-group">
        <input type="password" name="new_password" required>
        <label>New Password</label>
      </div>
      <button type="submit" name="reset">Reset Password</button>
    </form>
    <p><a href="login.php">⬅ Back to Login</a></p>
    <p>Don’t have an account? <a href="register.php">Create Account</a></p>
  </div>
</body>
</html>
