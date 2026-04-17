<?php
session_start();
include("db.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


// Handle Registrar Approval (POST)
if (isset($_POST['update_registrar'])) {

    $req_id  = $_POST['request_id'];
    $status  = $_POST['registrar_status'];
    $remark  = $conn->real_escape_string($_POST['registrar_remark']);

    $sql = "UPDATE no_dues_requests 
            SET registrar_status='$status',
                registrar_remark='$remark'
            WHERE id='$req_id'";

    if ($conn->query($sql)) {
        $_SESSION['success_msg'] = "Registrar status updated successfully!";
    } else {
        $_SESSION['success_msg'] = "Error while updating status!";
    }

    // PRG pattern (redirect)
    header("Location: welcome.php#registrarRequests");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* Reset & Font */
* { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
body { display:flex; min-height:100vh; background: linear-gradient(120deg, #8e44ad, #3498db); }

/* Sidebar */
.sidebar {
    width:250px; background: rgba(255,255,255,0.1); backdrop-filter: blur(12px);
    padding:25px 15px; display:flex; flex-direction:column; box-shadow:2px 0 15px rgba(0,0,0,0.2); transition:0.3s;
}
.sidebar h2 { color:white; text-align:center; margin-bottom:25px; font-size:24px; }
.sidebar a {
    color:white; text-decoration:none; padding:12px 18px; border-radius:10px; margin-bottom:10px;
    display:block; transition:0.3s; font-weight:500;
}
.sidebar a:hover { background:#00cec9; }
.sidebar a.logout { background:#e17055; margin-top:auto; }
.sidebar a.logout:hover { background:#d63031; }

/* Main Content */
.main-content { flex:1; padding:25px; overflow:auto; }
.welcome-user {
    font-size:20px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);
    padding:15px; margin-bottom:25px; border-radius:15px; color:white;
    text-align:center; box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

/* Sections */
section { display:none; background:rgba(255,255,255,0.95); padding:25px; border-radius:15px; margin-bottom:25px; box-shadow:0 6px 25px rgba(0,0,0,0.1); animation:fadeIn 0.4s ease-in-out; 
 margin:auto;
}
section.active { display:block; }
h2 { color:#2c3e50; margin-bottom:20px; font-size:24px; text-align:center; }

/* Dashboard Cards */
/* Dashboard Cards */
.dashboard-cards { 
    display:flex; 
    gap:15px;           /* gap thoda kam */
    flex-wrap:wrap; 
}

.dashboard-card {
    flex:1;
    min-width:150px;    /* pehle 180px tha */
    padding:15px;      /* pehle 25px tha */
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
    text-align:center;
    font-weight:600;
}

.dashboard-card h3 { 
    margin-bottom:6px; 
    font-size:15px;    /* pehle 18px */
}

.dashboard-card p { 
    font-size:22px;    /* pehle 28px */
    font-weight:700; 
}

/* Tables */
table { width:100%; border-collapse:collapse; margin-top:20px; border-radius:12px; overflow:hidden; }
th, td { padding:12px; text-align:left; }
th { background:linear-gradient(90deg,#6c5ce7,#a29bfe); color:white; }
tr:nth-child(even) { background:#f2f2f2; }
tr:hover { background:#dfe6e9; }
a.edit, a.delete { text-decoration:none; padding:6px 12px; border-radius:8px; color:white; font-weight:500; margin-right:6px; }
a.edit { background:#0984e3; }
a.edit:hover { background:#74b9ff; }
a.delete { background:#d63031; }
a.delete:hover { background:#e17055; }

/* Forms */
form { display:flex; flex-direction:column; gap:15px; max-width:500px; margin:auto; }
form input, form select, form textarea { padding:12px 15px; border-radius:10px; border:1px solid #ccc; font-size:15px; }
form input:focus, form select:focus, form textarea:focus { border-color:#6c5ce7; outline:none; }
form input[type="submit"] { background:linear-gradient(90deg,#6c5ce7,#a29bfe); border:none; color:white; font-weight:600; cursor:pointer; transition:0.3s; }
form input[type="submit"]:hover { background:linear-gradient(90deg,#4e54c8,#8f94fb); }

/* Animations */
@keyframes fadeIn { from {opacity:0; transform:translateY(10px);} to {opacity:1; transform:translateY(0);} }

/* Responsive */
@media(max-width:900px){ 
    body{flex-direction:column;} 
    .sidebar{width:100%; flex-direction:row; overflow-x:auto;} 
    .sidebar a{flex:1; margin:0 5px; text-align:center;} 
    .main-content{padding:15px;} 
}

.section filter-box{
    display:flex;
    gap:15px;
    margin-bottom:15px;
}

.section filter-box input,
.section filter-box select{
    padding:8px 12px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:14px;
    outline:none;
    transition:0.3s;
}

.section filter-box input:focus,
.section filter-box select:focus{
    border-color:#6c5ce7;
    box-shadow:0 0 5px rgba(108,92,231,0.4);
}


</style>
</head>

<body>

<div class="sidebar">
<h2>Admin Panel</h2>
<a href="#" onclick="showPage('home')"><i class="bi bi-house-door"></i> Home</a>
<a href="#" onclick="showPage('accountSearch')"><i class="bi bi-person-gear"></i> Account Search</a>
<a href="#" onclick="showPage('students')"><i class="bi bi-people"></i> Students</a>
<a href="#" onclick="showPage('registrarRequests')"><i class="bi bi-file-earmark-text"></i> No Dues Requests</a>
<a href="#" onclick="showPage('addStudent')"><i class="bi bi-person-plus"></i> Add Student</a>
<a href="#" onclick="showPage('faculty')"><i class="bi bi-person-badge"></i> Faculty</a>
<a href="#" onclick="showPage('addFaculty')"><i class="bi bi-person-plus"></i> Add Faculty</a>
<a href="#" onclick="showPage('assignments')"><i class="bi bi-journal-text"></i> Assignments</a>
<a href="#" onclick="showPage('addAssignment')"><i class="bi bi-journal-plus"></i> Add Assignment</a>
<a href="#" onclick="showPage('notices')"><i class="bi bi-megaphone"></i> Notices</a>
<a href="#" onclick="showPage('addNotice')"><i class="bi bi-plus-circle"></i> Add Notice</a>
<a href="#" onclick="showPage('courses')"><i class="bi bi-book"></i> Courses</a>
<a href="#" onclick="showPage('addCourse')"><i class="bi bi-plus-circle"></i> Add Course</a>
<a href="#" onclick="showPage('departments')"><i class="bi bi-building"></i> Departments</a>
<a href="#" onclick="showPage('addDepartment')"><i class="bi bi-plus-circle"></i> Add Department</a>
<a href="#" onclick="showPage('addUser')"><i class="bi bi-person-plus-fill"></i> Add User</a>
<a href="#" onclick="showPage('usersList')"><i class="bi bi-people-fill"></i> Users</a>
<a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="main-content">
<div class="welcome-user">
    <!-- Common Back Button -->
<button onclick="showPage('home')" 
        style="margin-bottom:15px; padding:8px 15px; border:none; border-radius:8px; 
               background:#6c5ce7; color:white; cursor:pointer; font-weight:bold;">
    HOME
</button>

👋 Welcome back, <b><?php echo $_SESSION['username']; ?></b>
</div>

<!-- Dashboard Section -->
<section id="home" class="page active">
<h2>📊 Dashboard Overview</h2>
<?php
$stu_count = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'] ?? 0;
$fac_count = $conn->query("SELECT COUNT(*) as total FROM faculty")->fetch_assoc()['total'] ?? 0;
$ass_count = $conn->query("SELECT COUNT(*) as total FROM assignments")->fetch_assoc()['total'] ?? 0;
$not_count = $conn->query("SELECT COUNT(*) as total FROM notices")->fetch_assoc()['total'] ?? 0;
$course_count = $conn->query("SELECT COUNT(*) as total FROM courses")->fetch_assoc()['total'] ?? 0;
$dept_count = $conn->query("SELECT COUNT(*) as total FROM departments")->fetch_assoc()['total'] ?? 0;
?>
<div class="dashboard-cards">
    <div class="dashboard-card" style="background:linear-gradient(135deg,#4e54c8,#8f94fb);"><h3>👨‍🎓 Students</h3><p><?php echo $stu_count; ?></p></div>
    <div class="dashboard-card" style="background:linear-gradient(135deg,#00b894,#55efc4);"><h3>👨‍🏫 Faculty</h3><p><?php echo $fac_count; ?></p></div>
    <div class="dashboard-card" style="background:linear-gradient(135deg,#fd79a8,#e84393);"><h3>📑 Assignments</h3><p><?php echo $ass_count; ?></p></div>
    <div class="dashboard-card" style="background:linear-gradient(135deg,#fdcb6e,#e17055);"><h3>📢 Notices</h3><p><?php echo $not_count; ?></p></div>
    <div class="dashboard-card" style="background:linear-gradient(135deg,#6c5ce7,#a29bfe);"><h3>📘 Courses</h3><p><?php echo $course_count; ?></p></div>
    <div class="dashboard-card" style="background:linear-gradient(135deg,#00cec9,#81ecec);"><h3>🏛 Departments</h3><p><?php echo $dept_count; ?></p></div>
</div>
</section>
<section id="accountSearch" class="page">
<h2>🔍 Account Management</h2>

<input type="text"
       id="searchInput"
       placeholder="Search by Name or ID..."
       onkeyup="searchAccount()"
       style="width:320px;padding:8px 12px;margin-bottom:15px;
              border-radius:8px;border:1px solid #ccc;">

<table id="accountTable" style="display:none;">
<tr>
    <th>Name</th>
    <th>ID</th>
    <th>Email</th>
    <th>Type</th>
    <th>Status</th>
    <th>Update Status</th>
</tr>

<?php
/* STUDENTS */
$s = $conn->query("SELECT id, student_name AS name, student_id AS uid,
                   student_email AS email, status FROM students");
while($r = $s->fetch_assoc()){
    $statusText = $r['status'] ? "Active" : "Inactive";
    $statusColor = $r['status'] ? "green" : "red";
    $selectedActive = $r['status'] ? "selected" : "";
    $selectedInactive = !$r['status'] ? "selected" : "";

    echo "<tr>
        <td>{$r['name']}</td>
        <td>{$r['uid']}</td>
        <td>{$r['email']}</td>
        <td>Student</td>
        <td style='color:$statusColor;font-weight:600;' id='status-student-{$r['id']}'>$statusText</td>
        <td>
            <select onchange=\"confirmChange('student', {$r['id']}, this.value)\">
                <option value='1' $selectedActive>Active</option>
                <option value='0' $selectedInactive>Inactive</option>
            </select>
        </td>
    </tr>";
}

/* FACULTY */
$f = $conn->query("SELECT id, faculty_name AS name, faculty_id AS uid,
                   faculty_email AS email, status FROM faculty");
while($r = $f->fetch_assoc()){
    $statusText = $r['status'] ? "Active" : "Inactive";
    $statusColor = $r['status'] ? "green" : "red";
    $selectedActive = $r['status'] ? "selected" : "";
    $selectedInactive = !$r['status'] ? "selected" : "";

    echo "<tr>
        <td>{$r['name']}</td>
        <td>{$r['uid']}</td>
        <td>{$r['email']}</td>
        <td>Faculty</td>
        <td style='color:$statusColor;font-weight:600;' id='status-faculty-{$r['id']}'>$statusText</td>
        <td>
            <select onchange=\"confirmChange('faculty', {$r['id']}, this.value)\">
                <option value='1' $selectedActive>Active</option>
                <option value='0' $selectedInactive>Inactive</option>
            </select>
        </td>
    </tr>";
}
?>
</table>

<script>
function searchAccount() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let table = document.getElementById('accountTable');
    let tr = table.getElementsByTagName('tr');
    let found = false;

    for (let i = 1; i < tr.length; i++) {
        let tdName = tr[i].getElementsByTagName('td')[0];
        let tdID = tr[i].getElementsByTagName('td')[1];
        if (tdName && tdID) {
            let nameVal = tdName.textContent.toLowerCase();
            let idVal = tdID.textContent.toLowerCase();
            if (nameVal.includes(input) || idVal.includes(input)) {
                tr[i].style.display = '';
                found = true;
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
    table.style.display = found ? 'table' : 'none';
}

// Function to confirm status change
function confirmChange(type, id, value){
    let msg = (value == 1) ? "Activate" : "Deactivate";
    if(confirm(`Are you sure you want to ${msg} this account?`)){
        // AJAX request
        let formData = new FormData();
        formData.append('type', type);
        formData.append('id', id);
        formData.append('status', value);

        fetch('update_status.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            alert(data);
            // Update the status text & color in table
            const statusCell = document.getElementById(`status-${type}-${id}`);
            if(statusCell){
                statusCell.textContent = value == 1 ? "Active" : "Inactive";
                statusCell.style.color = value == 1 ? "green" : "red";
            }
        })
        .catch(err => alert('Error: '+err));
    } else {
        // Revert select back to previous value
        const selectBox = document.querySelector(`#status-${type}-${id}`).nextElementSibling.querySelector('select');
        selectBox.value = value == 1 ? 0 : 1;
    }
}
</script>
</section>


<!-- Students Section -->
<section id="students" class="page">
    <h2>📚 Registered Students</h2>
    <?php
    $sql = "SELECT id, student_name, student_id, student_email, course, department FROM students";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<div class='table-card'><table>
                <tr>
                    <th>Name</th>
                    <th>Student ID</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['student_name']}</td>
                <td>{$row['student_id']}</td>
                <td>{$row['student_email']}</td>
                <td>{$row['course']}</td>
                <td>{$row['department']}</td>
                <td>
                    <a href='edit_student.php?id={$row['id']}' class='edit'>✏ Edit</a>
                    <a href='delete_student.php?id={$row['id']}' class='delete' onclick=\"return confirm('Delete this student?');\">🗑 Delete</a>
                </td>
                </tr>";
        }
        echo "</table></div>";
    } else {
        echo "<p>No students found.</p>";
    }
    ?>
</section>


<!-- Registrar No Dues Requests Section -->
 <?php
if (isset($_SESSION['success_msg'])) {
    echo "<script>
            alert('{$_SESSION['success_msg']}');
          </script>";
    unset($_SESSION['success_msg']); // refresh par dobara na aaye
}
?>


<section id="registrarRequests" class="page">
    <h2>📝 Registrar No Dues Requests</h2>
    <div class="filter-box">
    <input type="text" id="searchRegistrar" placeholder="🔍 Search by Student Name"
           style="padding:8px; width:220px;">

    <select id="registrarStatusFilter" style="padding:8px;">
        <option value="">All Status</option>
        <option value="Pending">Pending</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
    </select>
</div>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Account</th>
                <th>HOD</th>
                <th>Transport</th>
                <th>Hostel</th>
                <th>Library</th>
                <th>Registrar</th>
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

        if($requests->num_rows > 0){
            while($r = $requests->fetch_assoc()){
                echo "<tr class='registrar-row'
      data-name='".strtolower(trim($r['student_name']))."'
      data-status='{$r['registrar_status']}'>

                    <td>{$r['student_name']}</td>
                    <td>{$r['account_status']}<br><small>{$r['account_remark']}</small></td>
                    <td>{$r['hod_status']}<br><small>{$r['hod_remark']}</small></td>
                    <td>{$r['transport_status']}<br><small>{$r['transport_remark']}</small></td>
                    <td>{$r['hostel_status']}<br><small>{$r['hostel_remark']}</small></td>
                    <td>{$r['library_status']}<br><small>{$r['library_remark']}</small></td>
                    <td>
                        <form method='POST'>
                            <input type='hidden' name='request_id' value='{$r['id']}'>
                            <select name='registrar_status' class='form-select mb-1'>
                                <option value='Pending' ".($r['registrar_status']=='Pending'?'selected':'').">Pending</option>
                                <option value='Approved' ".($r['registrar_status']=='Approved'?'selected':'').">Approved</option>
                                <option value='Rejected' ".($r['registrar_status']=='Rejected'?'selected':'').">Rejected</option>
                            </select>
                            <input type='text' name='registrar_remark' class='form-control mb-1' placeholder='Remark' value='{$r['registrar_remark']}'>
                            <input type='submit' name='update_registrar' value='Update' class='btn btn-success btn-sm w-100'>
                        </form>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No requests found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</section>




<!-- Add Student Section -->
<section id="addStudent" class="page">
    <h2>➕ Add Student</h2>
    <form method="POST" action="add_student.php">
        <input type="text" name="student_name" placeholder="Student Name" required>
        <input type="text" name="student_id" placeholder="Student ID" required>
        <input type="email" name="student_email" placeholder="Email" required>
        <input type="password" name="student_password" placeholder="Password" required>
        <select name="course" required>
            <option value="">Select Course</option>
            <?php
            $course_sql = "SELECT course_name FROM courses ORDER BY course_name ASC";
            $course_result = $conn->query($course_sql);
            if ($course_result && $course_result->num_rows > 0) {
                while ($course = $course_result->fetch_assoc()) {
                    echo "<option value='".htmlspecialchars($course['course_name'])."'>".htmlspecialchars($course['course_name'])."</option>";
                }
            }
            ?>
        </select>
        <select name="department" required>
            <option value="">Select Department</option>
            <?php
            $dept_sql = "SELECT dept_name FROM departments ORDER BY dept_name ASC";
            $dept_result = $conn->query($dept_sql);
            if ($dept_result && $dept_result->num_rows > 0) {
                while ($dept = $dept_result->fetch_assoc()) {
                    echo "<option value='".htmlspecialchars($dept['dept_name'])."'>".htmlspecialchars($dept['dept_name'])."</option>";
                }
            }
            ?>
        </select>
        <input type="submit" value="Add Student">
    </form>
</section>

<!-- Faculty Section -->
<section id="faculty" class="page">
    <h2>👨‍🏫 Registered Faculty</h2>
    <?php
    $sql = "SELECT * FROM faculty";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<div class='table-card'><table>
                <tr><th>Name</th><th>ID</th><th>Email</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
            <td>{$row['faculty_name']}</td>
            <td>{$row['faculty_id']}</td>
            <td>{$row['faculty_email']}</td>
            <td>
                <a href='edit_faculty.php?id={$row['id']}' class='edit'>✏ Edit</a>
                <a href='delete_faculty.php?id={$row['id']}' class='delete' onclick=\"return confirm('Delete this faculty?');\">🗑 Delete</a>
            </td>
            </tr>";
        }
        echo "</table></div>";
    } else {
        echo "<p>No faculty found.</p>";
    }
    ?>
</section>

<!-- Add Faculty Section -->
<section id="addFaculty" class="page">
    <h2>➕ Add Faculty</h2>
    <form method="POST" action="add_faculty.php">
        <input type="text" name="faculty_name" placeholder="Faculty Name" required>
        <input type="text" name="faculty_id" placeholder="Faculty ID" required>
        <input type="email" name="faculty_email" placeholder="Email" required>
        <input type="password" name="faculty_password" placeholder="Password" required>
        <input type="submit" value="Add Faculty">
    </form>
</section>

<!-- Assignments Section -->
<section id="assignments" class="page">
    <h2>📑 Manage Assignments</h2>
    <?php
    $sql = "SELECT a.*, f.faculty_name 
            FROM assignments a 
            LEFT JOIN faculty f ON a.faculty_id = f.id 
            ORDER BY due_date DESC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<div class='table-card'><table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Subject</th>
            <th>Deadline</th>
            <th>Faculty</th>
            <th>Actions</th>
        </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['description']}</td>
                <td>{$row['subject']}</td>
                <td>{$row['due_date']}</td>
                <td>{$row['faculty_name']}</td>
                <td>
                    <a href='edit_assignment.php?id={$row['id']}' class='edit'>✏ Edit</a>
                    <a href='delete_assignment.php?id={$row['id']}' class='delete' onclick=\"return confirm('Delete this assignment?');\">🗑 Delete</a>
                </td>
            </tr>";
        }
        echo "</table></div>";
    } else {
        echo "<p>No assignments found.</p>";
    }
    ?>
</section>

<!-- Add Assignment Section -->
<section id="addAssignment" class="page">
    <h2>➕ Add New Assignment</h2>
    <form method="POST" action="add_assignment.php">
        <input type="text" name="title" placeholder="Assignment Title" required>
        <textarea name="description" placeholder="Enter assignment details" required></textarea>
        <input type="text" name="subject" placeholder="Subject" required>
        <input type="date" name="due_date" required>
        <select name="faculty_id" required>
            <option value="">Select Faculty</option>
            <?php
            $fac_sql = "SELECT id, faculty_name FROM faculty";
            $fac_result = $conn->query($fac_sql);
            if ($fac_result && $fac_result->num_rows > 0) {
                while ($fac = $fac_result->fetch_assoc()) {
                    echo "<option value='{$fac['id']}'>{$fac['faculty_name']}</option>";
                }
            }
            ?>
        </select>
        <input type="submit" value="Add Assignment">
    </form>
</section>

<!-- Notices Section -->
<section id="notices" class="page">
    <h2>📢 Notices</h2>
    <?php
    $sql = "SELECT * FROM notices ORDER BY created_at DESC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<div class='table-card'><table>
        <tr><th>Title</th><th>Message</th><th>Content</th><th>Date</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['message']}</td>
                <td>{$row['content']}</td>
                <td>{$row['created_at']}</td>
                <td>
                    <a href='edit_notice.php?id={$row['id']}' class='edit'>✏ Edit</a>
                    <a href='delete_notice.php?id={$row['id']}' class='delete' onclick=\"return confirm('Delete this notice?');\">🗑 Delete</a>
                </td>
            </tr>";
        }
        echo "</table></div>";
    } else { echo "<p>No notices found.</p>"; }
    ?>
</section>

<!-- Add Notice Section -->
<section id="addNotice" class="page">
    <h2>➕ Add Notice</h2>
    <form method="POST" action="add_notice.php">
        <input type="text" name="title" placeholder="Notice Title" required>
        <textarea name="message" placeholder="Short Message" required></textarea>
        <textarea name="content" placeholder="Full Content (Optional)"></textarea>
        <input type="submit" value="Add Notice">
    </form>
</section>

<!-- Courses Section -->
<section id="courses" class="page">
    <h2>📘 Courses</h2>
    <?php
    $sql = "SELECT c.id, c.course_name, c.course_code, d.dept_name 
            FROM courses c LEFT JOIN departments d ON c.dept_id = d.id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<div class='table-card'><table>
        <tr><th>Course Name</th><th>Code</th><th>Department</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['course_name']}</td>
                <td>{$row['course_code']}</td>
                <td>{$row['dept_name']}</td>
                <td>
                    <a href='edit_course.php?id={$row['id']}' class='edit'>✏ Edit</a>
                    <a href='delete_course.php?id={$row['id']}' class='delete' onclick=\"return confirm('Delete this course?');\">🗑 Delete</a>
                </td>
            </tr>";
        }
        echo "</table></div>";
    } else { echo "<p>No courses found.</p>"; }
    ?>
</section>

<!-- Add Course Section -->
<section id="addCourse" class="page">
    <h2>➕ Add Course</h2>
    <form method="POST" action="add_course.php">
        <input type="text" name="course_name" placeholder="Course Name" required>
        <input type="text" name="course_code" placeholder="Course Code" required>
        <select name="dept_id" required>
            <option value="">Select Department</option>
            <?php
            $dept_sql = "SELECT id, dept_name FROM departments";
            $dept_result = $conn->query($dept_sql);
            if ($dept_result && $dept_result->num_rows > 0) {
                while ($dept = $dept_result->fetch_assoc()) {
                    echo "<option value='{$dept['id']}'>{$dept['dept_name']}</option>";
                }
            }
            ?>
        </select>
        <input type="submit" value="Add Course">
    </form>
</section>
<!-- Departments Section -->
<section id="departments" class="page">
    <h2>🏛 Departments</h2>
    
    <?php
    $sql = "SELECT * FROM departments";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<div class='table-card'><table>
        <tr>
            <th>Department Name</th>
            <th>Department Code</th>
            <th>Actions</th>
        </tr>";
        while ($row = $result->fetch_assoc()) {
            $dept_name = htmlspecialchars($row['dept_name']);
            $dept_code = htmlspecialchars($row['dept_code']); // show dept code safely

            echo "<tr>
                <td>{$dept_name}</td>
                <td>{$dept_code}</td>
                <td>
                    <a href='edit_department.php?id={$row['id']}' class='edit'>✏ Edit</a>
                    <a href='delete_department.php?id={$row['id']}' class='delete' onclick=\"return confirm('Delete this department?');\">🗑 Delete</a>
                </td>
            </tr>";
        }
        echo "</table></div>";
    } else { 
        echo "<p>No departments found.</p>"; 
    }
    ?>
</section>

<!-- Add Department Section -->
<section id="addDepartment" class="page">
    <h2>➕ Add Department</h2>
    <form method="POST" action="add_department.php">
    <input type="text" name="dept_name" placeholder="Department Name" required>
    <input type="text" name="dept_code" placeholder="Department Code" required>
    <input type="submit" value="Add Department">
</form>

</section>

<?php
if (isset($_SESSION['success_msg'])) {
    echo "<script>
            alert('{$_SESSION['success_msg']}');
          </script>";
    unset($_SESSION['success_msg']); // so it doesn't appear again on refresh
}
?>

<!-- Add User Section -->
<section id="addUser" class="page">
    <h2>➕ Add User (Admin / Accounts / Hostel / Transport)</h2>

    <form method="POST" action="add_user.php">
        <input type="text" name="username" placeholder="Username" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="accounts">Accounts</option>
            <option value="hostel">Hostel</option>
            <option value="transport">Transport</option>
        </select>

        <input type="submit" name="add_user" value="Add User">
    </form>
</section>

<!-- Users List Section -->
<section id="usersList" class="page">
<h2>👥 System Users</h2>

<table>
<tr>
    <th>Username</th>
    <th>Email</th>
    <th>Role</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
while($u = $users->fetch_assoc()){
    $selectedActive = $u['status'] ? "selected" : "";
    $selectedInactive = !$u['status'] ? "selected" : "";

    echo "<tr>
        <td>{$u['username']}</td>
        <td>{$u['email']}</td>
        <td>{$u['role']}</td>
        <td>
            <select onchange=\"updateUserStatus({$u['id']}, this.value)\">
                <option value='1' $selectedActive>Active</option>
                <option value='0' $selectedInactive>Inactive</option>
            </select>
        </td>
        <td>
            <a href='edit_user.php?id={$u['id']}' class='edit'>✏ Edit</a>
            <a href='delete_user.php?id={$u['id']}' class='delete' onclick=\"return confirm('Delete this user?');\">🗑 Delete</a>
        </td>
    </tr>";
}
?>
</table>
</section>



</div>


<script>    
function showPage(pageId) {
    document.querySelectorAll("section").forEach(sec => sec.classList.remove("active"));
    document.getElementById(pageId).classList.add("active");
}
function updateUserStatus(id, value){
    if(confirm("Are you sure you want to change this user's status?")){
        let formData = new FormData();
        formData.append('id', id);
        formData.append('status', value);

        fetch('update_user_status.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(data => alert(data))
        .catch(err => alert('Error: ' + err));
    } else {
        location.reload(); // Revert dropdown if cancelled
    }
}


document.addEventListener("DOMContentLoaded", function () {

    function filterRegistrarTable() {
        let search = document.getElementById("searchRegistrar").value.toLowerCase().trim();
        let status = document.getElementById("registrarStatusFilter").value;

        document.querySelectorAll(".registrar-row").forEach(row => {
            let name = row.getAttribute("data-name");
            let rowStatus = row.getAttribute("data-status");

            let nameMatch = name.includes(search);
            let statusMatch = (status === "" || rowStatus === status);

            row.style.display = (nameMatch && statusMatch) ? "" : "none";
        });
    }

    document.getElementById("searchRegistrar").addEventListener("keyup", filterRegistrarTable);
    document.getElementById("registrarStatusFilter").addEventListener("change", filterRegistrarTable);

});

</script>

</body>
</html>
