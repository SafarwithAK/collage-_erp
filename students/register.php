<?php
include("db.php");
// Fetch all departments
$departments = $conn->query("SELECT id, dept_name FROM departments");
/* Fetch Courses */
$courses = $conn->query("SELECT id, course_name FROM courses");
// Form Submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['student_name'];
    $student_id = $_POST['student_id'];
    $email = $_POST['student_email'];
    $password = password_hash($_POST['student_password'], PASSWORD_BCRYPT);
    $course = $_POST['course'];
    $department = $_POST['department'];

    /* ===== PROFILE PIC UPLOAD ===== */
    $uploadDir = "uploads/profile_pics/";

    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    $profilePic = "default.png"; // default image

    if(!empty($_FILES['profile_pic']['name'])){
        $imgName = time().'_'.basename($_FILES['profile_pic']['name']);
        $imgTmp  = $_FILES['profile_pic']['tmp_name'];
        $imgPath = $uploadDir.$imgName;

        $allowed = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));

        if(in_array($ext, $allowed)){
            move_uploaded_file($imgTmp, $imgPath);
            $profilePic = $imgName;
        }
    }

    /* ===== INSERT DATA ===== */
    $sql = "INSERT INTO students 
        (student_name, student_id, student_email, student_password, course, department, profile_pic) 
        VALUES 
        ('$name', '$student_id', '$email', '$password', '$course', '$department', '$profilePic')";

    if ($conn->query($sql) === TRUE) {
        $success = "✅ Registration successful. <a href='login.php'>Click here to login</a>";
    } else {
        $error = "❌ Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
        background: linear-gradient(135deg, #11998e, #38ef7d);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: Arial, sans-serif;
    }
    .register-card {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0px 8px 20px rgba(0,0,0,0.3);
        width: 450px;
        animation: fadeIn 1s ease-in-out;
    }
    .register-card h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #11998e;
    }
    .form-control {
        border-radius: 8px;
    }
    .btn-success {
        background: #11998e;
        border: none;
        border-radius: 8px;
        transition: 0.3s;
    }
    .btn-success:hover {
        background: #0c7b67;
    }
    .extra-links {
        text-align: center;
        margin-top: 10px;
    }
    .extra-links a {
        text-decoration: none;
        color: #11998e;
        font-weight: 500;
    }
    .error-msg { color: red; text-align: center; margin-bottom: 10px; font-weight: bold; }
    .success-msg { color: green; text-align: center; margin-bottom: 10px; font-weight: bold; }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <div class="register-card">
    <h2><i class="fas fa-user-plus"></i> Student Registration</h2>
    
    <?php if(isset($error)) { echo "<p class='error-msg'>$error</p>"; } ?>
    <?php if(isset($success)) { echo "<p class='success-msg'>$success</p>"; } ?>

   <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">
            <i class="fas fa-image"></i> Profile Picture
        </label>
        <input type="file" name="profile_pic" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
        <label class="form-label"><i class="fas fa-user"></i> Full Name</label>
        <input type="text" name="student_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label"><i class="fas fa-id-card"></i> Student ID</label>
        <input type="text" name="student_id" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
        <input type="email" name="student_email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label"><i class="fas fa-lock"></i> Password</label>
        <input type="password" name="student_password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label"><i class="fas fa-book"></i> Course</label>
        <select name="course" class="form-control" required>
            <option value="">-- Select Course --</option>
            <?php while($c = $courses->fetch_assoc()) { ?>
                <option value="<?= $c['course_name'] ?>">
                    <?= $c['course_name'] ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label"><i class="fas fa-building"></i> Department</label>
        <select name="department" class="form-control" required>
            <option value="">-- Select Department --</option>
            <?php while($d = $departments->fetch_assoc()) { ?>
                <option value="<?= $d['dept_name'] ?>">
                    <?= $d['dept_name'] ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <button type="submit" class="btn btn-success w-100">
        <i class="fas fa-user-plus"></i> Register
    </button>
</form>

    <div class="extra-links">
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </div>
</body>
</html>
