<?php
session_start();
include("db.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $due_date = $_POST['due_date'];

    $sql = "UPDATE assignments 
            SET title='$title', description='$description', subject='$subject', due_date='$due_date' 
            WHERE id=$id";

    if ($conn->query($sql)) {
        echo "<script>
                alert('Assignment updated successfully!');
                window.location.href='welcome.php#assignments';
              </script>";
        exit();
    } else {
        $error = $conn->error;
    }
}

$result = $conn->query("SELECT * FROM assignments WHERE id=$id");
$assignment = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Assignment</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:#f1f5f9;
}

/* CONTAINER */
.container{
    max-width:520px;
    margin:60px auto;
    padding:30px;
    background:#ffffff;
    border-radius:18px;
    box-shadow:0 20px 45px rgba(0,0,0,0.12);
}

/* HEADING */
.container h2{
    text-align:center;
    margin-bottom:25px;
    color:#0f172a;
}

/* FORM GROUP */
.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    font-weight:500;
    margin-bottom:6px;
    color:#334155;
}

.form-group input{
    width:100%;
    padding:13px 14px;
    border-radius:10px;
    border:1px solid #cbd5e1;
    font-size:15px;
    outline:none;
    transition:.3s;
}

.form-group input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

/* BUTTON */
.btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg,#2563eb,#38bdf8);
    color:white;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

.btn:hover{
    opacity:.9;
    transform:translateY(-2px);
}

/* ERROR */
.error{
    background:#fee2e2;
    color:#991b1b;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
    text-align:center;
}

/* RESPONSIVE */
@media(max-width:600px){
    .container{
        margin:30px 15px;
        padding:22px;
    }
}
</style>
</head>

<body>

<div class="container">
<h2>Update Assignment</h2>

<?php if(isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label>Assignment Title</label>
        <input type="text" name="title"
               value="<?php echo htmlspecialchars($assignment['title']); ?>" required>
    </div>

    <div class="form-group">
        <label>Description</label>
        <input type="text" name="description"
               value="<?php echo htmlspecialchars($assignment['description']); ?>" required>
    </div>

    <div class="form-group">
        <label>Subject</label>
        <input type="text" name="subject"
               value="<?php echo htmlspecialchars($assignment['subject']); ?>">
    </div>

    <div class="form-group">
        <label>Due Date</label>
        <input type="date" name="due_date"
               value="<?php echo $assignment['due_date']; ?>" required>
    </div>

    <button type="submit" class="btn">Update Assignment</button>

</form>
</div>

</body>
</html>
