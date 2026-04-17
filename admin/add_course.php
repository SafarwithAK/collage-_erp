<?php
include("db.php");

if (isset($_POST['course_name'], $_POST['dept_id'], $_POST['course_code'])) {
    $course_name = trim($_POST['course_name']);
    $dept_id = intval($_POST['dept_id']);
    $course_code = trim($_POST['course_code']);

    if (!empty($course_name) && $dept_id > 0 && !empty($course_code)) {
        // Prepare INSERT query
        $stmt = $conn->prepare("INSERT INTO courses (course_name, dept_id, course_code) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $course_name, $dept_id, $course_code);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Course Added Successfully'); window.location='welcome.php#courses';</script>";
            exit();
        } else {
            echo "<script>alert('❌ Error adding course. Possibly duplicate course code'); window.location='welcome.php#addCourse';</script>";
        }
    } else {
        echo "<script>alert('⚠️ All fields are required!'); window.location='welcome.php#addCourse';</script>";
    }
} else {
    echo "<script>alert('⚠️ Invalid request'); window.location='welcome.php#addCourse';</script>";
}
?>
