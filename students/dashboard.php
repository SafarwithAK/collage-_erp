<?php
session_start();
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}
include("db.php");

$stu_id = $_SESSION['student_id'];
$student_id = $_SESSION['student_id'];


// Student Details
$studentQuery = $conn->query("SELECT * FROM students WHERE id='$stu_id'");
$student = $studentQuery->fetch_assoc();

// Set profile picture with default fallback
$profilePic = !empty($student['profile_pic']) ? $student['profile_pic'] : 'default.png';

// Attendance
$attendanceQuery = $conn->query("SELECT * FROM attendance WHERE student_id='$stu_id' ORDER BY date DESC");

// Assignments
$assignmentQuery = $conn->query("SELECT a.*, f.faculty_name 
                                 FROM assignments a 
                                 LEFT JOIN faculty f ON a.faculty_id = f.id 
                                 ORDER BY due_date ASC");
 
// Notices
$noticeQuery = $conn->query("SELECT * FROM notices ORDER BY created_at DESC");

// Results
$resultQuery = $conn->query("SELECT * FROM results WHERE student_id='$stu_id'");


// Handle No Dues Submission
if (isset($_POST['submit_no_dues'])) {

    $stu_id = $_SESSION['student_id']; // assume already login

    $sql = "INSERT INTO no_dues_requests (student_id) VALUES ('$stu_id')";

    if ($conn->query($sql)) {
        $_SESSION['success_msg'] = "No Dues request submitted successfully!";
    } else {
        $_SESSION['success_msg'] = "Error while submitting No Dues request!";
    }

    // Redirect (PRG)
    header("Location: dashboard.php#no-dues");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    display: flex;
    height: 100vh;
    background: #f5f6fa;
    overflow: hidden;
}

.sidebar {
    width: 250px;
    background: #212529;
    color: white;
    height: 100%;
    box-shadow: 3px 0 10px rgba(0,0,0,0.3);
    display: flex;
    flex-direction: column;
    align-items: center; /* Pic center me */
    padding-top: 20px;
}

.sidebar .profile-pic-wrapper {
    margin-bottom: 15px;
}

.sidebar .profile-pic-wrapper img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #0d6efd;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.sidebar h4 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 18px;
}

.sidebar a {
    color: white;
    padding: 12px 20px;   /* Links left-aligned */
    display: flex;
    align-items: center;
    width: 100%;
    font-size: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    text-decoration: none;
    transition: 0.3s;
}

.sidebar a i {
    margin-right: 10px; /* Icon aur text ke beech gap */
}

.sidebar a:hover,
.sidebar a.active {
    background: #0d6efd;
}


.content {
    flex-grow: 1;
    padding: 20px;
    overflow-y: auto;
}

.card {
    border-radius: 12px;
}

.section {
    display: none;
}

.section.active {
    display: block;
}

/* Sidebar hidden state */
.sidebar.collapsed {
    width: 0;
    overflow: hidden;
    transition: width 0.3s;
}

/* Content full width when sidebar collapsed */
.content.fullwidth {
    margin-left: 0;
    transition: margin-left 0.3s;
}


</style>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <!-- Toggle Button -->
<button id="toggleSidebar" class="btn btn-sm btn-secondary" 
        style="position:fixed; top:10px; left:260px; z-index:1000;">
    <i class="fas fa-bars"></i> Menu
</button>

  <div class="sidebar">
    
    <div class="profile-pic-wrapper">
        <img src="uploads/profile_pics/<?php echo $student['profile_pic']; ?>" alt="Profile Picture">
    </div>
    <h4>📘 Student Panel</h4>
    <a href="#" class="nav-link active" onclick="showSection('dashboard')"><i class="fas fa-home"></i> Dashboard</a>
    <a href="#" onclick="showSection('details')"><i class="fas fa-user"></i> Student Details</a>
    <a href="#" onclick="showSection('attendance')"><i class="fas fa-calendar-check"></i> Attendance</a>
    <a href="#" onclick="showSection('assignments')"><i class="fas fa-book"></i> Assignments</a>
    <a href="#" onclick="showSection('submit-assignment')"><i class="fas fa-upload"></i> Submit Assignment</a>
    <a href="#" onclick="showSection('my-submissions')"><i class="fas fa-file-pdf"></i> My Submissions</a>
    <a href="#" onclick="showSection('notices')"><i class="fas fa-bullhorn"></i> Notices</a>
    <a href="#" onclick="showSection('results')"><i class="fas fa-chart-bar"></i> Results</a>
    <a href="#" onclick="showSection('no-dues')"><i class="fas fa-file-invoice"></i> Apply for No Dues</a>
    <a href="#" onclick="showSection('due_books')">📚 My Issued Books</a>
    <a href="#" onclick="showSection('change-password')"><i class="fas fa-key"></i> Change Password</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>



<div class="content">

<!-- Dashboard -->
<div id="dashboard" class="section active">
    <h2>Welcome, <strong><?php echo $student['student_name']; ?></strong> 👋</h2>
    <p class="text-muted">Here is your academic overview</p>
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card text-bg-primary shadow-lg">
                <div class="card-body">
                    <h5><i class="fas fa-users"></i> Attendance</h5>
                    <p class="fs-3"><?php echo $attendanceQuery->num_rows; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-bg-success shadow-lg">
                <div class="card-body">
                    <h5><i class="fas fa-tasks"></i> Assignments</h5>
                    <p class="fs-3"><?php echo $assignmentQuery->num_rows; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-bg-warning shadow-lg">
                <div class="card-body">
                    <h5><i class="fas fa-bullhorn"></i> Notices</h5>
                    <p class="fs-3"><?php echo $noticeQuery->num_rows; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student Details -->
<div id="details" class="section">
    <h3>👨‍🎓 Student Details</h3>
    <table class="table table-bordered mt-3 shadow">
        <tr><th>Name</th><td><?php echo $student['student_name']; ?></td></tr>
        <tr><th>Email</th><td><?php echo $student['student_email']; ?></td></tr>
        <tr><th>Student ID</th><td><?php echo $student['student_id']; ?></td></tr>
        <tr><th>Course</th><td><?php echo $student['course']; ?></td></tr>
        <tr><th>Department</th><td><?php echo $student['department']; ?></td></tr>
    </table>
    <!-- Edit Button -->
    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#editDetailsModal"><i class="fas fa-edit"></i> Edit Details</button>
</div>
<!-- Edit Details Modal -->
<div class="modal fade" id="editDetailsModal" tabindex="-1" aria-labelledby="editDetailsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="editDetailsLabel">✏️ Edit My Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <!-- Name -->
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="student_name" class="form-control mb-3" value="<?php echo $student['student_name']; ?>" required>

            <!-- Email -->
            <label class="form-label fw-bold">Email</label>
            <input type="email" name="student_email" class="form-control mb-3" value="<?php echo $student['student_email']; ?>" required>

            <!-- Course -->
            <label class="form-label fw-bold">Course</label>
            <input type="text" name="course" class="form-control mb-3" value="<?php echo $student['course']; ?>" required>

            <!-- Department -->
            <label class="form-label fw-bold">Department</label>
            <input type="text" name="department" class="form-control mb-3" value="<?php echo $student['department']; ?>" required>

            <!-- Profile Picture Preview -->
            <label class="form-label fw-bold">Profile Picture</label><br>
            <img src="uploads/profile_pics/<?php echo !empty($student['profile_pic']) ? $student['profile_pic'] : 'default.png'; ?>" 
                 alt="Profile Pic" 
                 style="width:100px; height:100px; border-radius:50%; object-fit:cover; margin-bottom:10px;">
            <input type="file" name="profile_pic" class="form-control" accept="image/*">
            <small class="text-muted">Upload new picture to replace current one</small>

        </div>
        <div class="modal-footer">
          <button type="submit" name="update_details" class="btn btn-success w-100"><i class="fas fa-save"></i> Update Details</button>
        </div>
      </form>
    </div>
  </div>
</div>


<?php
// Handle Edit Details
if(isset($_POST['update_details'])){
    $name = $conn->real_escape_string($_POST['student_name']);
    $email = $conn->real_escape_string($_POST['student_email']);
    $course = $conn->real_escape_string($_POST['course']);
    $department = $conn->real_escape_string($_POST['department']);

    // Handle profile picture
    $fileName = $student['profile_pic']; // default to existing pic
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
        $uploadDir = "uploads/profile_pics/";
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        $newFile = time() . "_" . basename($_FILES['profile_pic']['name']);
        $targetPath = $uploadDir . $newFile;

        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($newFile, PATHINFO_EXTENSION));

        if(in_array($ext, $allowed)){
            // Delete old pic if exists
            if(!empty($student['profile_pic']) && file_exists($uploadDir . $student['profile_pic'])){
                unlink($uploadDir . $student['profile_pic']);
            }
            move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath);
            $fileName = $newFile;
        } else {
            echo "<script>alert('Invalid file type for profile picture');</script>";
        }
    }

    // Update DB
    $update = "UPDATE students SET student_name='$name', student_email='$email', course='$course', department='$department', profile_pic='$fileName' WHERE id='$stu_id'";
    if($conn->query($update)){
        echo "<script>
                alert('Details updated successfully!');
                window.location='dashboard.php#details';
              </script>";
    }
}

?>

<!-- Attendance -->
<div id="attendance" class="section">
    <h3>📅 Attendance</h3>
    <table class="table table-striped mt-3 shadow">
        <thead><tr><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        <?php while($row=$attendanceQuery->fetch_assoc()){ ?>
            <tr>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Assignments -->
<div id="assignments" class="section">
    <h3>📖 Assignments</h3>
    <?php while($row=$assignmentQuery->fetch_assoc()){ ?>
        <div class="card mt-3 shadow-sm">
            <div class="card-body">
                <h5><?php echo $row['title']; ?></h5>
                <p><?php echo $row['description']; ?></p>
                <small class="text-muted">
                    Subject: <?php echo $row['subject']; ?> | 
                    Faculty: <?php echo $row['faculty_name']; ?> | 
                    Due: <?php echo $row['due_date']; ?>
                </small>
            </div>
        </div>
    <?php } ?>
</div>


<!-- Submit Assignment -->
<div id="submit-assignment" class="section">
    <h3>📤 Submit Assignment</h3>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Select Assignment</label>
            <select name="assignment_id" class="form-control" required>
                <option value="">-- Select Assignment --</option>
                <?php
                $ass = $conn->query("SELECT id, title FROM assignments");
                while($a = $ass->fetch_assoc()){
                    echo "<option value='{$a['id']}'>{$a['title']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Upload File</label>
            <input type="file" name="assignment_file" class="form-control" required>
        </div>

        <button type="submit" name="submit_assignment" class="btn btn-primary">
            📤 Submit Assignment
        </button>
    </form>
</div>

<?php
if(isset($_POST['submit_assignment'])){

    $assignment_id = $_POST['assignment_id'];
    $student_id = $stu_id; // session se aa raha hai

    // uploads folder (students/uploads/)
    $uploadDir = "uploads/";

    // folder exist na kare to create karo
    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    // file info
    $originalName = $_FILES['assignment_file']['name'];
    $tmpPath = $_FILES['assignment_file']['tmp_name'];

    // unique file name
    $fileName = time() . "_" . basename($originalName);
    $targetPath = $uploadDir . $fileName;

    // allowed file types
    $allowed = ['pdf','doc','docx'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed)){
        echo "<script>alert('Only PDF, DOC, DOCX files allowed');</script>";
        exit;
    }

    // upload file
    if(move_uploaded_file($tmpPath, $targetPath)){

        // save in database
        $conn->query("INSERT INTO submitted_assignments
            (student_id, assignment_id, file_path, submitted_at)
            VALUES
            ('$student_id', '$assignment_id', '$fileName', NOW())");

        echo "<script>
                alert('Assignment submitted successfully!');
                window.location='dashboard.php#my-submissions';
              </script>";
    } else {
        echo "<script>alert('File upload failed');</script>";
    }
}
?>



<!-- My Submitted Assignments -->
<div id="my-submissions" class="section">
    <h3>📄 My Submitted Assignments</h3>
    <?php
    $res = $conn->query("SELECT sa.id, a.title, sa.file_path, sa.submitted_at, f.faculty_name
                         FROM submitted_assignments sa
                         JOIN assignments a ON sa.assignment_id = a.id
                         JOIN faculty f ON a.faculty_id = f.id
                         WHERE sa.student_id='$stu_id'
                         ORDER BY sa.submitted_at DESC");

    if($res->num_rows > 0){
        echo "<table class='table table-bordered mt-3 shadow'>
                <tr>
                    <th>Assignment</th>
                    <th>Faculty</th>
                    <th>File</th>
                    <th>Submitted At</th>
                    <th>Download</th>
                    <th>Action</th>
                </tr>";
        while($row=$res->fetch_assoc()){
            echo "<tr>
                    <td>{$row['title']}</td>
                    <td>{$row['faculty_name']}</td>
                    <td>{$row['file_path']}</td>
                    <td>{$row['submitted_at']}</td>
                    <td><a href='../uploads/{$row['file_path']}' download>📄 Download</a></td>
                    <td>
                        <a href='?delete_id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this submission?')\">🗑 Delete</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else { echo "<p>No submissions yet.</p>"; }

    // Delete submission
    if(isset($_GET['delete_id'])){
        $del_id = $_GET['delete_id'];
        $fileQuery = $conn->query("SELECT file_path FROM submitted_assignments WHERE id='$del_id' AND student_id='$stu_id'");
        if($fileQuery->num_rows > 0){
            $filePath = $fileQuery->fetch_assoc()['file_path'];
            if(file_exists("../uploads/$filePath")) unlink("../uploads/$filePath");
            $conn->query("DELETE FROM submitted_assignments WHERE id='$del_id' AND student_id='$stu_id'");
            echo "<script>alert('Submission deleted successfully!'); window.location='dashboard.php';</script>";
        }
    }
    ?>
</div>

<!-- Notices -->
<div id="notices" class="section mt-4">
    <h3 class="text-primary mb-3">
        <i class="bi bi-megaphone-fill"></i> Notices
    </h3>

    <?php if ($noticeQuery->num_rows > 0): ?>
        <ul class="list-group shadow-sm">

            <?php while ($row = $noticeQuery->fetch_assoc()): ?>
                <li class="list-group-item p-3">

                    <!-- Title -->
                    <h5 class="fw-bold text-dark mb-1">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </h5>

                    <!-- Short Message -->
                    <p class="text-secondary mb-2">
                        <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                    </p>

                    <!-- Optional Full Content -->
                    <?php if (!empty($row['content'])): ?>
                        <div class="text-muted mb-2" style="font-size: 14px;">
                            <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Footer Info -->
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="bi bi-calendar-event"></i>
                            <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?>
                        </small>

                        <small class="text-muted">
                            <i class="bi bi-person-fill"></i>
                            <?php echo htmlspecialchars($row['created_by']); ?>
                        </small>
                    </div>

                </li>
            <?php endwhile; ?>

        </ul>

    <?php else: ?>
        <div class="alert alert-info shadow-sm">
            <i class="bi bi-info-circle"></i> No notices available right now.
        </div>
    <?php endif; ?>
</div>


<!-- Results -->
<div id="results" class="section">
    <h3>📊 Results</h3>
    <?php if($resultQuery->num_rows>0){ ?>
        <table class="table table-bordered mt-3 shadow">
            <thead><tr><th>Subject</th><th>Marks</th><th>status</th></tr></thead>
            <tbody>
            <?php while($row=$resultQuery->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $row['subject']; ?></td>
                    <td><?php echo $row['marks']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { echo "<p>No results available yet.</p>"; } ?>
</div>

<!-- DUE BOOKS SECTION -->
<div id="due_books" class="section">
    <h2>📚 My Due Books</h2>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Issue Date</th>
                    <th>Days</th>
                    <th>Fine (₹)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $dueBooks = $conn->query("
                SELECT 
                    ib.issue_date,
                    ib.fine,
                    b.title,
                    b.author,
                    DATEDIFF(CURDATE(), ib.issue_date) AS days
                FROM issue_books ib
                JOIN books b ON ib.book_id = b.id
                WHERE ib.student_id = '$student_id'
                  AND ib.return_status = 0
                ORDER BY ib.issue_date DESC
            ");

            if($dueBooks->num_rows > 0){
                while($d = $dueBooks->fetch_assoc()){
                    $days = $d['days'];
                    $fine = ($days > 7) ? ($days - 7) * 10 : 0;

                    $rowClass = ($days > 7) ? "table-danger fw-bold" : "";

                    echo "<tr class='$rowClass'>
                            <td>{$d['title']}</td>
                            <td>{$d['author']}</td>
                            <td>{$d['issue_date']}</td>
                            <td>{$days} days</td>
                            <td>₹{$fine}</td>
                            <td>".($days > 7 ? "Overdue" : "Issued")."</td>
                          </tr>";
                }
            } else {
                echo "<tr>
                        <td colspan='6' class='text-center'>🎉 No Due Books</td>
                      </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>



<?php
if (isset($_SESSION['success_msg'])) {
    echo "<script>
            alert('{$_SESSION['success_msg']}');
          </script>";
    unset($_SESSION['success_msg']); // refresh pe dobara na aaye
}
?>

<!-- No Dues Section -->
<div id="no-dues" class="section">
    <h3>📝 Apply for No Dues</h3>

    <!-- Submit Form -->
    <div class="card shadow p-3 mb-4">
        <p>Click submit to request No Dues clearance from all departments.</p>
        <form method="POST">
            <button type="submit" name="submit_no_dues" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Submit Request
            </button>
        </form>
    </div>

    <!-- Requests Table -->
    <div class="card shadow p-3">
        <h5>📊 My No Dues Requests</h5>
        <div class="table-responsive mt-2">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Request ID</th>
                        <th>Account</th>
                        <th>HOD</th>
                        <th>Transport</th>
                        <th>Hostel</th>
                        <th>Library</th>
                        <th>Registrar</th>
                        <th>Submitted On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $reqQuery = $conn->query("SELECT * FROM no_dues_requests WHERE student_id='$stu_id' ORDER BY created_at DESC");
                    if($reqQuery->num_rows>0){
                        while($r=$reqQuery->fetch_assoc()){
                            echo "<tr>
                                    <td>{$r['id']}</td>
                                    <td>{$r['account_status']}<br><small>{$r['account_remark']}</small></td>
                                    <td>{$r['hod_status']}<br><small>{$r['hod_remark']}</small></td>
                                    <td>{$r['transport_status']}<br><small>{$r['transport_remark']}</small></td>
                                    <td>{$r['hostel_status']}<br><small>{$r['hostel_remark']}</small></td>
                                    <td>{$r['library_status']}<br><small>{$r['library_remark']}</small></td>
                                    <td>{$r['registrar_status']}<br><small>{$r['registrar_remark']}</small></td>
                                    <td>".date("d M Y, h:i A", strtotime($r['created_at']))."</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No No Dues requests submitted yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Change Password -->
<div id="change-password" class="section">
    <h3>🔒 Change Password</h3>
    <form action="" method="POST" class="shadow p-4" style="max-width:600px;">
        <label class="form-label fw-bold">Old Password</label>
        <input type="password" name="old_password" class="form-control mb-3" required>

        <label class="form-label fw-bold">New Password</label>
        <input type="password" name="new_password" class="form-control mb-3" required>

        <label class="form-label fw-bold">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control mb-3" required>

        <button type="submit" name="change_password" class="btn btn-warning w-100"><i class="fas fa-key"></i> Change Password</button>
    </form>
</div>

<?php
// Handle Update Details
if(isset($_POST['update_details'])){
    $name = $conn->real_escape_string($_POST['student_name']);
    $email = $conn->real_escape_string($_POST['student_email']);
    $course = $conn->real_escape_string($_POST['course']);
    $dept = $conn->real_escape_string($_POST['department']);

    $conn->query("UPDATE students SET student_name='$name', student_email='$email', course='$course', department='$dept' WHERE id='$stu_id'");
    echo "<script>alert('Details updated successfully!'); window.location='dashboard.php';</script>";
}

// Handle Change Password
if(isset($_POST['change_password'])){
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $passQuery = $conn->query("SELECT student_password FROM students WHERE id='$stu_id'");
    $row = $passQuery->fetch_assoc();

    // Agar passwords hashed hain
    if(password_verify($old, $row['student_password'])){
        if($new === $confirm){
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $conn->query("UPDATE students SET student_password='$hashed' WHERE id='$stu_id'");
            echo "<script>alert('Password changed successfully!'); window.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('New password and confirm password do not match!');</script>";
        }
    } else {
        echo "<script>alert('Old password is incorrect!');</script>";
    }
}
?>


</div>

<script>
    const sidebar = document.querySelector('.sidebar');
const content = document.querySelector('.content');
const toggleBtn = document.getElementById('toggleSidebar');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    content.classList.toggle('fullwidth');

    // Adjust toggle button position
    if(sidebar.classList.contains('collapsed')){
        toggleBtn.style.left = '10px';
    } else {
        toggleBtn.style.left = '260px';
    }
});

function showSection(id){
    document.querySelectorAll('.section').forEach(sec=>sec.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    document.querySelectorAll('.sidebar a').forEach(link=>link.classList.remove('active'));
    event.target.classList.add('active');
}
</script>

</body>
</html>
