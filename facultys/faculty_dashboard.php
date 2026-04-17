<?php
session_start();
include("db.php");
if(!isset($_SESSION['faculty_id'])){
    header("Location: faculty_login.php");
    exit();
}

$faculty_id = $_SESSION['faculty_id'];
$page = $_GET['page'] ?? 'dashboard';

// Fetch summary counts
$total_students = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'] ?? 0;
$total_assignments = $conn->query("SELECT COUNT(*) as total FROM assignments WHERE faculty_id='$faculty_id'")->fetch_assoc()['total'] ?? 0;
$total_notices = $conn->query("SELECT COUNT(*) as total FROM notices")->fetch_assoc()['total'] ?? 0;

// Handle HOD Approval
if (isset($_POST['update_hod'])) {

    $req_id = $_POST['request_id'];
    $status = $_POST['hod_status'];
    $remark = $conn->real_escape_string($_POST['hod_remark']);

    $sql = "UPDATE no_dues_requests 
            SET hod_status='$status',
                hod_remark='$remark'
            WHERE id='$req_id'";

    if ($conn->query($sql)) {
        $_SESSION['success_msg'] = "HOD status updated successfully!";
    } else {
        $_SESSION['success_msg'] = "Error while updating HOD status!";
    }

    // PRG redirect
    header("Location: faculty_dashboard.php");
    exit();
}

// Update Assignment
if(isset($_POST['update_assignment'])){
    $id = intval($_POST['assignment_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $due_date = $_POST['due_date'];

    $sql = "UPDATE assignments 
            SET title='$title', description='$description', subject='$subject', due_date='$due_date' 
            WHERE id='$id' AND faculty_id='$faculty_id'";

    if($conn->query($sql)){
        // Flash message
        $_SESSION['flash_msg'] = "✅ Assignment updated successfully!";
        header("Location: faculty_dashboard.php?page=assignments");
        exit();
    } else {
        echo "<script>alert('❌ Error updating assignment');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Faculty Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body { display:flex; min-height:100vh; background:#f5f6fa; margin:0; font-family:'Segoe UI', sans-serif; }
.sidebar { width:250px; background:#212529; color:white; flex-shrink:0; height:100vh; display:flex; flex-direction:column; align-items:center; padding-top:20px; position:fixed; transition:0.3s; }
.sidebar h4 { margin-bottom:30px; font-size:18px; text-align:center; }
.sidebar a { color:white; padding:12px 20px; display:flex; align-items:center; width:100%; font-size:16px; border-bottom:1px solid rgba(255,255,255,0.07); text-decoration:none; transition:0.3s; }
.sidebar a i { margin-right:10px; }
.sidebar a:hover, .sidebar a.active { background:#0d6efd; border-radius:0 20px 20px 0; }
.content { flex-grow:1; padding:20px; margin-left:250px; transition:0.3s; width:100%; }
.card { border-radius:12px; }
.section { display:none; }
.section.active { display:block; }
.table th, .table td { vertical-align: middle; }
.btn-custom { border-radius:8px; }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>👩‍🏫 Faculty Panel</h4>
    <a href="?page=dashboard" class="<?php echo ($page=='dashboard')?'active':''; ?>"><i class="fas fa-home"></i> Dashboard</a>
    <a href="?page=students" class="<?php echo ($page=='students')?'active':''; ?>"><i class="fas fa-users"></i> Students</a>
    <a href="?page=hod_requests" class="<?php echo ($page=='hod_requests')?'active':''; ?>"><i class="fas fa-file-signature"></i> HOD Requests</a>
    <a href="?page=attendance" class="<?php echo ($page=='attendance')?'active':''; ?>"><i class="fas fa-calendar-check"></i> Attendance</a>
    <a href="?page=add_attendance" class="<?php echo ($page=='add_attendance')?'active':''; ?>"><i class="fas fa-plus"></i> Add Attendance</a>
    <a href="?page=assignments" class="<?php echo ($page=='assignments')?'active':''; ?>"><i class="fas fa-book"></i> Assignments</a>
    <a href="?page=add_assignment" class="<?php echo ($page=='add_assignment')?'active':''; ?>"><i class="fas fa-plus-circle"></i> Add Assignment</a>
    <a href="?page=submitted_assignments" class="<?php echo ($page=='submitted_assignments')?'active':''; ?>"><i class="fas fa-file-pdf"></i> Submissions</a>
    <a href="?page=notices" class="<?php echo ($page=='notices')?'active':''; ?>"><i class="fas fa-bullhorn"></i> Notices</a>
    <a href="?page=results" class="<?php echo ($page=='results')?'active':''; ?>"><i class="fas fa-chart-bar"></i> Results</a>
    <a href="?page=faculty_password" class="<?php echo ($page=='faculty_password')?'active':''; ?>"><i class="fas fa-key"></i> Change Password</a>
    <a href="faculty_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Content -->
<div class="content">

<!-- Dashboard -->
<div id="dashboard" class="section <?php echo ($page=='dashboard')?'active':''; ?>">
    <h2>Welcome, <strong><?php echo $_SESSION['faculty_name']; ?></strong> 👋</h2>
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card text-bg-primary shadow p-3">
                <h5><i class="fas fa-users"></i> Total Students</h5>
                <p class="fs-3"><?php echo $total_students; ?></p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-bg-success shadow p-3">
                <h5><i class="fas fa-book"></i> Assignments</h5>
                <p class="fs-3"><?php echo $total_assignments; ?></p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-bg-warning shadow p-3">
                <h5><i class="fas fa-bullhorn"></i> Notices</h5>
                <p class="fs-3"><?php echo $total_notices; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Students Section -->
<div id="students" class="section <?php echo ($page=='students')?'active':''; ?>">
    <h3>👨‍🎓 Students List</h3>
    <?php
    $res = $conn->query("SELECT student_name, student_id, student_email, course, department FROM students");
    if($res->num_rows>0){
        echo "<table class='table table-bordered table-striped mt-3'>
                <tr><th>Name</th><th>ID</th><th>Email</th><th>Course</th><th>Department</th></tr>";
        while($row=$res->fetch_assoc()){
            echo "<tr>
                    <td>{$row['student_name']}</td>
                    <td>{$row['student_id']}</td>
                    <td>{$row['student_email']}</td>
                    <td>{$row['course']}</td>
                    <td>{$row['department']}</td>
                  </tr>";
        }
        echo "</table>";
    } else { echo "<p>No students found.</p>"; }
    ?>
</div>

<?php
if (isset($_SESSION['success_msg'])) {
    echo "<script>
            alert('{$_SESSION['success_msg']}');
          </script>";
    unset($_SESSION['success_msg']);
}
?>
<?php if ($page == 'hod_requests') { ?>
<section id="hod_requests" class="section active">

    <h2>🧑‍🏫 HOD No Dues Approvals</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student</th>
                <th>Account</th>
                <th>Transport</th>
                <th>Hostel</th>
                <th>Library</th>
                <th>HOD Action</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $requests = $conn->query("
            SELECT nd.*, s.student_name
            FROM no_dues_requests nd
            JOIN students s ON nd.student_id = s.id
            ORDER BY nd.created_at DESC
        ");

        if ($requests->num_rows > 0) {
            while ($r = $requests->fetch_assoc()) {
        ?>
            <tr>
                <td><?= $r['student_name'] ?></td>
                <td><?= $r['account_status'] ?></td>
                <td><?= $r['transport_status'] ?></td>
                <td><?= $r['hostel_status'] ?></td>
                <td><?= $r['library_status'] ?></td>

                <td>
                    <form method="POST">
                        <input type="hidden" name="request_id" value="<?= $r['id'] ?>">

                        <select name="hod_status" class="form-select mb-1" required>
                            <option value="Pending"  <?= ($r['hod_status']=='Pending')?'selected':'' ?>>Pending</option>
                            <option value="Approved" <?= ($r['hod_status']=='Approved')?'selected':'' ?>>Approved</option>
                            <option value="Rejected" <?= ($r['hod_status']=='Rejected')?'selected':'' ?>>Rejected</option>
                        </select>

                        <input type="text"
                               name="hod_remark"
                               class="form-control mb-1"
                               placeholder="HOD Remark"
                               value="<?= $r['hod_remark'] ?>">

                        <button type="submit"
                                name="update_hod"
                                class="btn btn-primary btn-sm w-100">
                            Update
                        </button>
                    </form>
                </td>
            </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='6'>No requests found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</section>
<?php } ?>

<!-- Attendance Section -->
<?php if($page=='attendance'){ ?>
<section>
<h3>📅 Attendance Records</h3>

<!-- Filter Form -->
<form method="GET" class="row g-3 mb-3">
    <input type="hidden" name="page" value="attendance">

    <!-- Date Range Filter -->
    <div class="col-auto">
        <label class="form-label">From Date</label>
        <input type="date" name="from_date" class="form-control" value="<?= $_GET['from_date'] ?? '' ?>">
    </div>

    <div class="col-auto">
        <label class="form-label">To Date</label>
        <input type="date" name="to_date" class="form-control" value="<?= $_GET['to_date'] ?? '' ?>">
    </div>

    <div class="col-auto align-self-end">
        <button type="submit" class="btn btn-primary btn-custom">Filter by Date</button>
    </div>

    <!-- Month Filter -->
    <div class="col-auto">
        <label class="form-label">Select Month</label>
        <input type="month" name="month_filter" class="form-control" value="<?= $_GET['month_filter'] ?? '' ?>">
    </div>
    <div class="col-auto align-self-end">
        <button type="submit" class="btn btn-secondary btn-custom">Filter by Month</button>
    </div>

    <!-- Refresh Button -->
    <div class="col-auto align-self-end">
        <a href="?page=attendance" class="btn btn-outline-danger btn-custom" title="Reset Filters">
            🔄
        </a>
    </div>
</form>

<?php
// Get filter values
$from_date = $_GET['from_date'] ?? '';
$to_date   = $_GET['to_date'] ?? '';
$month     = $_GET['month_filter'] ?? '';

// Render table only if a filter is applied
if($from_date || $to_date || $month){

    if($from_date && $to_date){
        // Date range filter
        $sql = "SELECT a.id, a.date, s.student_name, a.status 
                FROM attendance a 
                JOIN students s ON a.student_id = s.id 
                WHERE a.date BETWEEN ? AND ? 
                ORDER BY a.date DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $from_date, $to_date);
        $stmt->execute();
        $res = $stmt->get_result();

        $title = "Attendance from $from_date to $to_date";

    } elseif($month){
        // Month filter
        $year  = substr($month, 0, 4);
        $mon   = substr($month, 5, 2);

        $sql = "SELECT a.id, a.date, s.student_name, a.status 
                FROM attendance a 
                JOIN students s ON a.student_id = s.id 
                WHERE YEAR(a.date)=? AND MONTH(a.date)=? 
                ORDER BY a.date DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $year, $mon);
        $stmt->execute();
        $res = $stmt->get_result();

        $title = "Attendance for " . date("F Y", strtotime("$year-$mon-01"));

    }

    if($res && $res->num_rows > 0){
        echo "<h5>$title</h5>";
        echo "<table class='table table-bordered table-striped'>
                <tr><th>Date</th><th>Student</th><th>Status</th></tr>";
        while($row = $res->fetch_assoc()){
            $color = ($row['status']=="Present") ? "green" : "red";
            echo "<tr>
                    <td>{$row['date']}</td>
                    <td>{$row['student_name']}</td>
                    <td style='color:$color;font-weight:bold'>{$row['status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No attendance records found for selected period.</p>";
    }

} else {
    echo "<p class='text-muted'>Please select a date range or month to view attendance.</p>";
}
?>
</section>

<?php } ?>

<!-- Add Attendance -->
<?php if($page=='add_attendance'){ ?>
<section>
<h3>➕ Add Attendance</h3>
<?php
if(isset($_POST['submit_attendance'])){
    $date = $_POST['date'];
    foreach($_POST['status'] as $student_id=>$status){
        $check = $conn->query("SELECT * FROM attendance WHERE student_id=$student_id AND date='$date'");
        if($check->num_rows>0){
            $conn->query("UPDATE attendance SET status='$status' WHERE student_id=$student_id AND date='$date'");
        } else {
            $conn->query("INSERT INTO attendance (student_id,date,status) VALUES ($student_id,'$date','$status')");
        }
    }
    echo "<script>alert('✅ Attendance saved successfully!'); window.location='?page=attendance';</script>";
}

$res = $conn->query("SELECT id, student_name FROM students");
if($res->num_rows>0){
    echo '<form method="POST">';
    echo '<label>Date:</label><input type="date" name="date" class="form-control mb-3" required>';

    // Select All Checkbox
    echo '<div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="selectAllPresent">
            <label class="form-check-label fw-bold" for="selectAllPresent">Select All Present</label>
          </div>';

    echo "<table class='table table-bordered'>
            <tr><th>Student</th><th>Present</th><th>Absent</th></tr>";

    while($row=$res->fetch_assoc()){
        echo "<tr>
                <td>{$row['student_name']}</td>
                <td><input type='radio' class='present_radio' name='status[{$row['id']}]' value='Present' required></td>
                <td><input type='radio' class='absent_radio' name='status[{$row['id']}]' value='Absent'></td>
              </tr>";
    }
    echo "</table><br><input type='submit' name='submit_attendance' class='btn btn-success btn-custom' value='Save Attendance'>";
    echo "</form>";
}else{ echo "<p>No students found.</p>"; }
?>

<script>
    // Select All Present functionality
    document.getElementById('selectAllPresent').addEventListener('change', function(){
        let checked = this.checked;
        document.querySelectorAll('.present_radio').forEach(radio => {
            radio.checked = checked;
        });
        // If select all is checked, uncheck absent radios
        if(checked){
            document.querySelectorAll('.absent_radio').forEach(radio => radio.checked = false);
        }
    });
</script>
</section>



<?php
if(isset($_SESSION['flash_msg'])){
    echo "<script>alert('{$_SESSION['flash_msg']}');</script>";
    unset($_SESSION['flash_msg']); // Ek baar show hone ke baad remove
}
?>

<?php } ?>
<!-- Assignments Section -->
<?php if($page=='assignments'){ ?>
<section>
<h3>📚 Assignments</h3>
<?php
$res = $conn->query("SELECT * FROM assignments WHERE faculty_id='$faculty_id' ORDER BY due_date DESC");
if($res->num_rows>0){
    echo "<table class='table table-bordered table-striped'>
            <tr><th>Title</th><th>Description</th><th>Subject</th><th>Due Date</th><th>Actions</th></tr>";
    while($row=$res->fetch_assoc()){
        echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['description']}</td>
                <td>{$row['subject']}</td>
                <td>{$row['due_date']}</td>
                <td>
                    <button 
                        class='btn btn-sm btn-warning btn-custom edit-btn'
                        data-id='{$row['id']}'
                        data-title='".htmlspecialchars($row['title'], ENT_QUOTES)."'
                        data-description='".htmlspecialchars($row['description'], ENT_QUOTES)."'
                        data-subject='".htmlspecialchars($row['subject'], ENT_QUOTES)."'
                        data-due_date='{$row['due_date']}'
                        data-bs-toggle='modal'
                        data-bs-target='#editAssignmentModal'>
                        ✏️ Edit
                    </button>
                    <a href='delete_assignment.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")' class='btn btn-sm btn-danger btn-custom'>🗑️ Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}else{ echo "<p>No assignments found.</p>"; }
?>
</section>

<!-- Edit Assignment Modal -->
<div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-labelledby="editAssignmentLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="editAssignmentLabel">✏️ Edit Assignment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="assignment_id" id="assignment_id">
            <label class="form-label fw-bold">Title</label>
            <input type="text" name="title" id="title" class="form-control mb-3" required>

            <label class="form-label fw-bold">Description</label>
            <textarea name="description" id="description" class="form-control mb-3" required></textarea>

            <label class="form-label fw-bold">Subject</label>
            <input type="text" name="subject" id="subject" class="form-control mb-3" required>

            <label class="form-label fw-bold">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control mb-3" required>
        </div>
        <div class="modal-footer">
          <button type="submit" name="update_assignment" class="btn btn-success w-100">💾 Update Assignment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php } ?>

<!-- Add Assignment -->
<?php if($page=='add_assignment'){ ?>
<section>
<h3>➕ Add Assignment</h3>
<form method="POST" action="add_assignment.php">
<label>Title:</label><input type="text" name="title" class="form-control mb-3" required>
<label>Description:</label><textarea name="description" class="form-control mb-3" required></textarea>
<label>Subject:</label><input type="text" name="subject" class="form-control mb-3" required>
<label>Due Date:</label><input type="date" name="due_date" class="form-control mb-3" required>
<input type="submit" class="btn btn-success btn-custom" value="Add Assignment">
</form>
</section>
<?php } ?>

<!-- Submitted Assignments -->
<?php if($page=='submitted_assignments'){ ?>
<section>
<h3>📄 Submitted Assignments</h3>
<?php
$sql = "SELECT sa.*, s.student_name, a.title FROM submitted_assignments sa
        JOIN students s ON sa.student_id = s.id
        JOIN assignments a ON sa.assignment_id = a.id
        WHERE a.faculty_id='$faculty_id' ORDER BY sa.submitted_at DESC";
$res = $conn->query($sql);
if($res->num_rows>0){
    echo "<table class='table table-bordered table-striped'>
            <tr><th>Student</th><th>Assignment</th><th>File</th><th>Submitted At</th><th>Action</th></tr>";
    while($row=$res->fetch_assoc()){
        echo "<tr>
                <td>{$row['student_name']}</td>
                <td>{$row['title']}</td>
                <td><a href='../students/{$row['file_path']}' target='_blank'>📥 Download</a></td>
                <td>{$row['submitted_at']}</td>
                <td><a href='?page=submitted_assignments&delete_id={$row['id']}' onclick='return confirm(\"Are you sure?\")' class='btn btn-sm btn-danger btn-custom'>🗑️ Delete</a></td>
              </tr>";
    }
    echo "</table>";
}else{ echo "<p>No submissions found.</p>"; }

if(isset($_GET['delete_id'])){
    $del_id = $_GET['delete_id'];
    $fileQuery = $conn->query("SELECT file_path FROM submitted_assignments WHERE id='$del_id'");
    if($fileQuery->num_rows>0){
        $filePath = $fileQuery->fetch_assoc()['file_path'];
        if(file_exists("../students/$filePath")) unlink("../students/$filePath");
        $conn->query("DELETE FROM submitted_assignments WHERE id='$del_id'");
        echo "<script>alert('Submission deleted!'); window.location='?page=submitted_assignments';</script>";
    }
}
?>
</section>
<?php } ?>

<!-- Notices -->
<?php if($page=='notices'){ ?>
<section>
<h3>📢 Notices</h3>
<?php
$res = $conn->query("SELECT * FROM notices ORDER BY created_at DESC");
if($res->num_rows>0){
    echo "<table class='table table-bordered table-striped'>
            <tr><th>Title</th><th>Message</th><th>Content</th><th>Date</th></tr>";
    while($row=$res->fetch_assoc()){
        echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['message']}</td>
                <td>{$row['content']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    echo "</table>";
}else{ echo "<p>No notices found.</p>"; }
?>
</section>
<?php } ?>

<!-- Results Section -->
<?php if($page=='results'){ ?>
<section>
<h3>📊 Student Results</h3>

<?php
// Add / Update Result
if(isset($_POST['save_result'])){
    $student_id = $_POST['student_id'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];
    $status = ($marks >= 40) ? 'Pass' : 'Fail';

    $check = $conn->query("SELECT id FROM results 
                           WHERE student_id='$student_id' AND subject='$subject'");

    if($check->num_rows > 0){
        $conn->query("UPDATE results 
                      SET marks='$marks', status='$status'
                      WHERE student_id='$student_id' AND subject='$subject'");
        echo "<script>alert('✅ Result Updated');</script>";
    } else {
        $conn->query("INSERT INTO results (student_id, subject, marks, status)
                      VALUES ('$student_id','$subject','$marks','$status')");
        echo "<script>alert('✅ Result Added');</script>";
    }
}
?>

<!-- Add Result -->
<div class="card p-3 mb-4">
    <h5>➕ Add / Update Result</h5>
    <form method="POST" class="row g-2">
        <div class="col-md-4">
            <label>Student</label>
            <select name="student_id" class="form-control" required>
                <option value="">-- Select Student --</option>
                <?php
                $stu = $conn->query("SELECT id, student_name FROM students");
                while($s=$stu->fetch_assoc()){
                    echo "<option value='{$s['id']}'>{$s['student_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>

        <div class="col-md-3">
            <label>Marks</label>
            <input type="number" name="marks" min="0" max="100" class="form-control" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button name="save_result" class="btn btn-success w-100">Save</button>
        </div>
    </form>
</div>

<!-- View Results -->
<div class="card p-3">
    <h5>📄 Result List</h5>
    <table class="table table-bordered table-striped">
        <tr>
            <th>Student</th>
            <th>Subject</th>
            <th>Marks</th>
            <th>Status</th>
        </tr>

        <?php
        $res = $conn->query("
            SELECT r.*, s.student_name 
            FROM results r 
            JOIN students s ON r.student_id = s.id
            ORDER BY s.student_name
        ");

        if($res->num_rows>0){
            while($r=$res->fetch_assoc()){
                $color = ($r['status']=='Pass')?'green':'red';
                echo "<tr>
                        <td>{$r['student_name']}</td>
                        <td>{$r['subject']}</td>
                        <td>{$r['marks']}</td>
                        <td style='color:$color;font-weight:bold'>{$r['status']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No results found</td></tr>";
        }
        ?>
    </table>
</div>

</section>
<?php } ?>


<!-- Change Password -->
<?php if($page=='faculty_password'){ ?>
<section>
<h3>🔒 Change Password</h3>
<?php
if(isset($_POST['change_password'])){
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $res = $conn->query("SELECT faculty_password FROM faculty WHERE id='$faculty_id'");
    if($res->num_rows>0){
        $row = $res->fetch_assoc();
        if(password_verify($old_pass,$row['faculty_password'])){
            if($new_pass === $confirm_pass){
                $new_hash = password_hash($new_pass,PASSWORD_DEFAULT);
                $conn->query("UPDATE faculty SET faculty_password='$new_hash' WHERE id='$faculty_id'");
                echo "<script>alert('✅ Password changed successfully!');</script>";
            } else { echo "<script>alert('❌ New passwords do not match!');</script>"; }
        } else { echo "<script>alert('❌ Old password is incorrect!');</script>"; }
    } else { echo "<script>alert('❌ Faculty not found!');</script>"; }
}
?>
<form method="POST">
<label>Old Password:</label>
<input type="password" name="old_password" class="form-control mb-3" required>
<label>New Password:</label>
<input type="password" name="new_password" class="form-control mb-3" required>
<label>Confirm New Password:</label>
<input type="password" name="confirm_password" class="form-control mb-3" required>
<input type="submit" name="change_password" class="btn btn-warning btn-custom" value="Change Password">
</form>
</section>
<?php } ?>

</div>





<script>
    // Populate modal fields when edit button clicked
    document.querySelectorAll('.edit-btn').forEach(btn=>{
        btn.addEventListener('click', function(){
            document.getElementById('assignment_id').value = this.dataset.id;
            document.getElementById('title').value = this.dataset.title;
            document.getElementById('description').value = this.dataset.description;
            document.getElementById('subject').value = this.dataset.subject;
            document.getElementById('due_date').value = this.dataset.due_date;
        });
    });
</script>
</body>
</html>
