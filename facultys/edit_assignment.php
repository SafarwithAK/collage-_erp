<?php
session_start();
include("db.php");

if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid Assignment ID'); window.location='faculty_dashboard.php?page=assignments';</script>";
    exit();
}

$id = intval($_GET['id']);

// Fetch assignment
$res = $conn->query("SELECT * FROM assignments WHERE id=$id AND faculty_id=".$_SESSION['faculty_id']);
if($res->num_rows == 0){
    echo "<script>alert('Assignment not found!'); window.location='faculty_dashboard.php?page=assignments';</script>";
    exit();
}
$assignment = $res->fetch_assoc();

// Update Assignment
if(isset($_POST['update'])){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $subject = trim($_POST['subject']);
    $due_date = $_POST['due_date'];

    if(!empty($title) && !empty($description) && !empty($subject) && !empty($due_date)){
        $stmt = $conn->prepare("UPDATE assignments SET title=?, description=?, subject=?, due_date=? WHERE id=? AND faculty_id=?");
        $stmt->bind_param("ssssii", $title, $description, $subject, $due_date, $id, $_SESSION['faculty_id']);
        
        if($stmt->execute()){
            echo "<script>alert('✅ Assignment Updated Successfully'); window.location='faculty_dashboard.php?page=assignments';</script>";
        } else {
            echo "<script>alert('❌ Error updating assignment');</script>";
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
<title>Edit Assignment | Faculty Panel</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{
    background:#f5f6fa;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.card{
    width:600px;
    border-radius:15px;
    box-shadow:0 12px 35px rgba(0,0,0,0.15);
}

.card-header{
    background:#212529;
    color:white;
    border-radius:15px 15px 0 0;
    padding:20px;
}

.card-header h4{
    margin:0;
}

.form-label{
    font-weight:600;
}

.btn-success{
    background:#198754;
    border:none;
}

.btn-success:hover{
    background:#157347;
}
</style>
</head>
<body>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="fas fa-pen-to-square fa-2x me-3 text-warning"></i>
        <div>
            <h4>Edit Assignment</h4>
            <small class="text-muted">Faculty Dashboard</small>
        </div>
    </div>

    <div class="card-body p-4">

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-heading"></i> Assignment Title
                </label>
                <input type="text" name="title" class="form-control"
                       value="<?= htmlspecialchars($assignment['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-align-left"></i> Description
                </label>
                <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($assignment['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-book"></i> Subject
                </label>
                <input type="text" name="subject" class="form-control"
                       value="<?= htmlspecialchars($assignment['subject']) ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-calendar-day"></i> Due Date
                </label>
                <input type="date" name="due_date" class="form-control"
                       value="<?= $assignment['due_date'] ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="dashboard.php#assignments" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                <button type="submit" name="update" class="btn btn-success">
                    <i class="fas fa-save"></i> Update Assignment
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>

