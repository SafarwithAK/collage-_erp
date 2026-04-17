<?php
include("db.php");

// Record fetch
$row = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM departments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<script>alert('⚠️ Department not found!'); window.location='welcome.php';</script>";
        exit();
    }
    $row = $result->fetch_assoc();
} else {
    echo "<script>alert('⚠️ Invalid Request!'); window.location='welcome.php';</script>";
    exit();
}

// Update record
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $dept_name = trim($_POST['dept_name']);
    $dept_code = trim($_POST['dept_code']);

    if (!empty($dept_name) && !empty($dept_code)) {
        // Check for duplicate dept_code
        $stmt_check = $conn->prepare("SELECT id FROM departments WHERE dept_code = ? AND id != ?");
        $stmt_check->bind_param("si", $dept_code, $id);
        $stmt_check->execute();
        $res_check = $stmt_check->get_result();

        if ($res_check->num_rows > 0) {
            echo "<script>alert('⚠️ Department code already exists!');</script>";
        } else {
            $stmt = $conn->prepare("UPDATE departments SET dept_name=?, dept_code=? WHERE id=?");
            $stmt->bind_param("ssi", $dept_name, $dept_code, $id);

            if ($stmt->execute()) {
                echo "<script>alert('✅ Department Updated Successfully'); window.location='welcome.php';</script>";
                exit();
            } else {
                echo "<script>alert('❌ Error while updating!');</script>";
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
    <title>Edit Department</title>
    <style>
        body{font-family: Arial, sans-serif; background: #f0f2f5; padding: 30px;}
        h2{color:#2c3e50;}
        form{background:white; padding:20px; border-radius:8px; max-width:400px;}
        input{width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc;}
        button{padding:10px 20px; background:#3498db; color:white; border:none; border-radius:5px; cursor:pointer;}
        button:hover{background:#2980b9;}
    </style>
</head>
<body>
    <h2>Edit Department</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
        <input type="text" name="dept_name" value="<?= htmlspecialchars($row['dept_name']) ?>" placeholder="Department Name" required>
        <input type="text" name="dept_code" value="<?= htmlspecialchars($row['dept_code']) ?>" placeholder="Department Code" required>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
