<?php
session_start();
include("db.php");

/* ================= AUTH ================= */
if(!isset($_SESSION['hostel_user'])){
    header("Location: hostel_login.php");
    exit();
}

/* ================= NO DUES UPDATE ================= */
if(isset($_POST['update_nodues'])){
    $id = (int)$_POST['request_id'];
    $status = $_POST['hostel_status'];
    $remark = $conn->real_escape_string($_POST['hostel_remark']);

    $conn->query("
        UPDATE no_dues_requests 
        SET hostel_status='$status', hostel_remark='$remark' 
        WHERE id=$id
    ");

    $_SESSION['success_msg'] = "No-Dues updated successfully!";
    header("Location: hostel_dashboard.php#no_dues");
    exit();
}

/* ================= ATTENDANCE SAVE ================= */
if(isset($_POST['attendance_submit'])){
    $date = $_POST['attendance_date'];

    foreach($_POST['status'] as $student_id => $status){
        $student_id = (int)$student_id;
        $status = $conn->real_escape_string($status);

        $check = $conn->query("
            SELECT id FROM hostel_attendance 
            WHERE student_id=$student_id AND attendance_date='$date'
        ");

        if($check->num_rows > 0){
            $conn->query("
                UPDATE hostel_attendance 
                SET status='$status' 
                WHERE student_id=$student_id AND attendance_date='$date'
            ");
        } else {
            $conn->query("
                INSERT INTO hostel_attendance (student_id, attendance_date, status)
                VALUES ($student_id,'$date','$status')
            ");
        }
    }

    $_SESSION['success_msg'] = "Attendance saved successfully!";
    header("Location: hostel_dashboard.php#attendance");
    exit();
}

/* ================= DATA FETCH ================= */
$total_students = $conn->query("SELECT COUNT(*) total FROM students")->fetch_assoc()['total'];

$requests = $conn->query("
    SELECT n.*, s.student_name 
    FROM no_dues_requests n
    JOIN students s ON n.student_id = s.id
    ORDER BY n.id DESC
");

$students = $conn->query("SELECT * FROM students ORDER BY student_name");

$attendance = $conn->query("
    SELECT a.*, s.student_name
    FROM hostel_attendance a
    JOIN students s ON a.student_id = s.id
    ORDER BY a.attendance_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>🏨 Hostel Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body{margin:0;font-family:Poppins;display:flex;background:#f1f2f6}
.sidebar{width:230px;background:#0984e3;color:#fff;min-height:100vh}
.sidebar h2{text-align:center;padding:15px}
.sidebar a{display:block;color:#fff;padding:12px 20px;text-decoration:none;cursor:pointer}
.sidebar a:hover,.sidebar a.active{background:#74b9ff}
.content{flex:1;padding:20px}
.card{background:#fff;padding:20px;border-radius:10px;margin-bottom:20px}
table{width:100%;border-collapse:collapse}
th,td{padding:10px;border:1px solid #ccc}
section{display:none}
section.active{display:block}
input,select{padding:6px;border-radius:5px;border:1px solid #ccc}
button{padding:6px 10px;border:none;border-radius:5px;background:#0984e3;color:#fff;cursor:pointer}

.status.pending{background:#f1c40f;color:#fff;padding:4px 8px;border-radius:5px}
.status.approved{background:#2ecc71;color:#fff;padding:4px 8px;border-radius:5px}
.status.rejected{background:#e74c3c;color:#fff;padding:4px 8px;border-radius:5px}
</style>
</head>
<body>

<div class="sidebar">
    <h2>🏨 Hostel</h2>
    <a class="active" onclick="showSection('dashboard',this)">Dashboard</a>
    <a onclick="showSection('students',this)">Students</a>
    <a onclick="showSection('no_dues',this)">No-Dues</a>
    <a onclick="showSection('attendance',this)">Attendance</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">

<?php
if(isset($_SESSION['success_msg'])){
    echo "<script>alert('{$_SESSION['success_msg']}')</script>";
    unset($_SESSION['success_msg']);
}
?>

<!-- DASHBOARD -->
<section id="dashboard" class="active">
    <div class="card">
        <h3>Total Students: <?php echo $total_students; ?></h3>
    </div>
</section>

<!-- STUDENTS -->
<section id="students">
    <div class="card">
        <h3>Students</h3>
        <table>
            <tr><th>Name</th><th>Email</th><th>Course</th></tr>
            <?php while($s=$students->fetch_assoc()){ ?>
            <tr>
                <td><?php echo htmlspecialchars($s['student_name']); ?></td>
                <td><?php echo htmlspecialchars($s['student_email']); ?></td>
                <td><?php echo htmlspecialchars($s['course']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</section>

<!-- NO DUES -->
<section id="no_dues">
<div class="card">
<h3>No-Dues Requests</h3>

<input type="text" id="searchNoDues" placeholder="Search Student">
<select id="statusFilter">
    <option value="">All</option>
    <option value="Pending">Pending</option>
    <option value="Approved">Approved</option>
    <option value="Rejected">Rejected</option>
</select>

<table>
<tr>
<th>Student</th><th>Account</th><th>HOD</th><th>Library</th>
<th>Status</th><th>Update</th>
</tr>

<?php while($r=$requests->fetch_assoc()){ ?>
<tr class="nodues-row"
    data-name="<?php echo strtolower($r['student_name']); ?>"
    data-status="<?php echo $r['hostel_status']; ?>">

<td><?php echo $r['student_name']; ?></td>
<td><?php echo $r['account_status']; ?></td>
<td><?php echo $r['hod_status']; ?></td>
<td><?php echo $r['library_status']; ?></td>

<td>
<span class="status <?php echo strtolower($r['hostel_status']); ?>">
<?php echo $r['hostel_status']; ?>
</span>
</td>

<td>
    <form method="POST">
    <select name="hostel_status">
        <option value="Pending" <?php if($r['hostel_status']=='Pending') echo 'selected'; ?>>
            Pending
        </option>
        <option value="Approved" <?php if($r['hostel_status']=='Approved') echo 'selected'; ?>>
            Approved
        </option>
        <option value="Rejected" <?php if($r['hostel_status']=='Rejected') echo 'selected'; ?>>
            Rejected
        </option>
    </select>

    <input 
        type="text" 
        name="hostel_remark" 
        value="<?php echo htmlspecialchars($r['hostel_remark']); ?>" 
        placeholder="Enter remark"
    >

    <input type="hidden" name="request_id" value="<?php echo $r['id']; ?>">
    <button type="submit" name="update_nodues">Update</button>
</form>

</td>
</tr>
<?php } ?>
</table>
</div>
</section>

<!-- ATTENDANCE -->
<section id="attendance">
<div class="card">
<h3>Attendance</h3>
<form method="POST">
<input type="date" name="attendance_date" required>
<table>
<tr><th>Student</th><th>Present</th><th>Absent</th></tr>
<?php
$stud = $conn->query("SELECT id,student_name FROM students");
while($st=$stud->fetch_assoc()){ ?>
<tr>
<td><?php echo $st['student_name']; ?></td>
<td><input type="radio" name="status[<?php echo $st['id']; ?>]" value="Present"></td>
<td><input type="radio" name="status[<?php echo $st['id']; ?>]" value="Absent" checked></td>
</tr>
<?php } ?>
</table>
<button name="attendance_submit">Save Attendance</button>
</form>
</div>
</section>

</div>

<script>
function showSection(id,el){
document.querySelectorAll('section').forEach(s=>s.classList.remove('active'));
document.getElementById(id).classList.add('active');
document.querySelectorAll('.sidebar a').forEach(a=>a.classList.remove('active'));
el.classList.add('active');
}

document.getElementById("searchNoDues").onkeyup =
document.getElementById("statusFilter").onchange = function(){
let s=document.getElementById("searchNoDues").value.toLowerCase();
let st=document.getElementById("statusFilter").value;
document.querySelectorAll(".nodues-row").forEach(r=>{
r.style.display=(r.dataset.name.includes(s)&&(st==""||r.dataset.status==st))?"":"none";
});
};
</script>

</body>
</html>
