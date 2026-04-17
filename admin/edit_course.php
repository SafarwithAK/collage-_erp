<?php
include("db.php");

if (!isset($_GET['id'])) {
    echo "<script>alert('⚠️ Invalid course ID'); window.location='welcome.php#courses';</script>";
    exit();
}

$id = intval($_GET['id']);

// Fetch existing course data
$stmt = $conn->prepare("SELECT * FROM courses WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('⚠️ Course not found'); window.location='welcome.php#courses';</script>";
    exit();
}
$course = $result->fetch_assoc();

// Update course on form submit
if (isset($_POST['update'])) {
    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);
    $dept_id = intval($_POST['dept_id']);

    if (!empty($course_name) && !empty($course_code) && $dept_id > 0) {
        // Check duplicate course_code
        $stmt_check = $conn->prepare("SELECT id FROM courses WHERE course_code=? AND id != ?");
        $stmt_check->bind_param("si", $course_code, $id);
        $stmt_check->execute();
        $res_check = $stmt_check->get_result();

        if ($res_check->num_rows > 0) {
            echo "<script>alert('⚠️ Course code already exists!');</script>";
        } else {
            $stmt = $conn->prepare("UPDATE courses SET course_name=?, course_code=?, dept_id=? WHERE id=?");
            $stmt->bind_param("ssii", $course_name, $course_code, $dept_id, $id);
            if ($stmt->execute()) {
                echo "<script>alert('✅ Course Updated Successfully'); window.location='welcome.php#courses';</script>";
                exit();
            } else {
                echo "<script>alert('❌ Error updating course');</script>";
            }
        }
    } else {
        echo "<script>alert('⚠️ All fields are required!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Course</title>
    <style>
        body{font-family: Arial, sans-serif; background:#f0f2f5; padding:30px;}
        h2{color:#2c3e50;}
        form{background:white; padding:20px; border-radius:8px; max-width:450px;}
        input, select{width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;}
        select{height:40px;}
        input[type="submit"]{background:#3498db; color:white; border:none; cursor:pointer; font-weight:bold;}
        input[type="submit"]:hover{background:#2980b9;}
    </style>
</head>
<body>
    <h2>Edit Course</h2>
    <form method="POST">
        <input type="text" name="course_name" placeholder="Course Name" value="<?= htmlspecialchars($course['course_name']) ?>" required>
        <input type="text" name="course_code" placeholder="Course Code" value="<?= htmlspecialchars($course['course_code']) ?>" required>
        <select name="dept_id" required>
            <option value="">Select Department</option>
            <?php
            $dept_sql = "SELECT id, dept_name FROM departments";
            $dept_result = $conn->query($dept_sql);
            if ($dept_result && $dept_result->num_rows > 0) {
                while ($dept = $dept_result->fetch_assoc()) {
                    $selected = ($dept['id'] == $course['dept_id']) ? 'selected' : '';
                    echo "<option value='{$dept['id']}' $selected>{$dept['dept_name']}</option>";
                }
            }
            ?>
        </select>
        <input type="submit" name="update" value="Update Course">
    </form>
</body>
</html>
