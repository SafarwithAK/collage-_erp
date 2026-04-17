<?php
include("db.php");

if (!isset($_GET['id'])) {
    echo "<script>alert('⚠️ Invalid student ID!'); window.location='welcome.php?page=students';</script>";
    exit();
}

$id = intval($_GET['id']);

// Fetch existing student data
$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "<script>alert('⚠️ Student not found!'); window.location='welcome.php?page=students';</script>";
    exit();
}

// Update student on form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['student_name'];
    $email = $_POST['student_email'];
    $student_id_field = $_POST['student_id'];
    $course = $_POST['course'];
    $department = $_POST['department'];

    $stmt = $conn->prepare("UPDATE students SET student_name=?, student_email=?, student_id=?, course=?, department=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $email, $student_id_field, $course, $department, $id);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Student updated successfully!'); window.location='welcome.php?page=students';</script>";
        exit();
    } else {
        echo "<script>alert('❌ Error updating student: ".$conn->error."');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Student</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
body {
    background:#f4f6f9;
    font-family:'Roboto', sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
}
.container {
    background:white;
    padding:30px 35px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.1);
    max-width:500px;
    width:100%;
    transition:0.3s;
}
.container:hover { box-shadow:0 12px 30px rgba(0,0,0,0.15); }
h2 { color:#4e54c8; text-align:center; margin-bottom:25px; font-size:24px; }
form input, form select {
    width:100%;
    padding:14px 12px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:15px;
    transition:0.3s;
}
form input:focus, form select:focus {
    border-color:#4e54c8;
    outline:none;
}
input[type="submit"] {
    background: linear-gradient(90deg,#4e54c8,#8f94fb);
    color:white;
    font-weight:600;
    border:none;
    cursor:pointer;
    padding:14px 20px;
    border-radius:8px;
    margin-top:15px;
    transition:0.3s;
}
input[type="submit"]:hover {
    background: linear-gradient(90deg,#6c63ff,#a29bfe);
}
@media(max-width:500px){ .container{ padding:25px 20px; } }
</style>
</head>
<body>
<div class="container">
    <h2>Edit Student</h2>
    <form method="POST">
        <input type="text" name="student_name" value="<?php echo htmlspecialchars($student['student_name']); ?>" placeholder="Student Name" required>
        <input type="text" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>" placeholder="Student ID" required>
        <input type="email" name="student_email" value="<?php echo htmlspecialchars($student['student_email']); ?>" placeholder="Email" required>

        <!-- Course Dropdown -->
        <select name="course" required>
            <option value="">Select Course</option>
            <?php
            $course_sql = "SELECT course_name FROM courses ORDER BY course_name ASC";
            $course_result = $conn->query($course_sql);
            if ($course_result && $course_result->num_rows > 0) {
                while ($course_row = $course_result->fetch_assoc()) {
                    $selected = ($course_row['course_name'] == $student['course']) ? "selected" : "";
                    echo "<option value='".htmlspecialchars($course_row['course_name'])."' $selected>".htmlspecialchars($course_row['course_name'])."</option>";
                }
            }
            ?>
        </select>

        <!-- Department Dropdown -->
        <select name="department" required>
            <option value="">Select Department</option>
            <?php
            $dept_sql = "SELECT dept_name FROM departments ORDER BY dept_name ASC";
            $dept_result = $conn->query($dept_sql);
            if ($dept_result && $dept_result->num_rows > 0) {
                while ($dept_row = $dept_result->fetch_assoc()) {
                    $selected = ($dept_row['dept_name'] == $student['department']) ? "selected" : "";
                    echo "<option value='".htmlspecialchars($dept_row['dept_name'])."' $selected>".htmlspecialchars($dept_row['dept_name'])."</option>";
                }
            }
            ?>
        </select>

        <input type="submit" value="Update Student">
    </form>
</div>

</body>
</html>
