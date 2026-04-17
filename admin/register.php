<?php
include("db.php");

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if user already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('⚠️ Email already registered! Please login.'); window.location='login.php';</script>";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Registration Successful! Please Login.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('❌ Error while registering!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
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
      background: linear-gradient(135deg, #1cc88a, #36b9cc, #4e73df, #f6c23e);
      background-size: 400% 400%;
      animation: gradientShift 12s ease infinite;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .register-card {
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(14px);
      padding: 40px 30px;
      width: 380px;
      border-radius: 16px;
      text-align: center;
      color: #fff;
      box-shadow: 0 0 20px rgba(28,200,138,0.4);
      transition: 0.3s;
    }

    .register-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 0 35px rgba(28,200,138,0.7);
    }

    .register-card h2 {
      margin-bottom: 18px;
      font-size: 26px;
      text-shadow: 0 0 10px rgba(0,255,200,0.7);
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

    .register-card button {
      width: 100%;
      padding: 14px;
      margin-top: 20px;
      border: none;
      border-radius: 10px;
      background: linear-gradient(135deg, #1cc88a, #36b9cc);
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .register-card button:hover {
      background: linear-gradient(135deg, #36b9cc, #1cc88a);
      box-shadow: 0 0 15px #00ffc3;
    }

    .register-card p {
      margin-top: 15px;
      font-size: 14px;
      color: #eee;
    }

    .register-card a {
      color: #f6c23e;
      text-decoration: none;
      font-weight: 500;
    }

    .register-card a:hover {
      text-decoration: underline;
    }

    @media (max-width: 420px) {
      .register-card {
        width: 90%;
        padding: 30px 25px;
      }
    }
  </style>
</head>
<body>
  <div class="register-card">
    <div class="icon">📝</div>
    <h2>Create Account</h2>
    <form method="POST">
      <div class="input-group">
        <input type="text" name="name" required>
        <label>Full Name</label>
      </div>
      <div class="input-group">
        <input type="email" name="email" required>
        <label>Email Address</label>
      </div>
      <div class="input-group">
        <input type="password" name="password" required>
        <label>Password</label>
      </div>
      <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
</body>
</html>
