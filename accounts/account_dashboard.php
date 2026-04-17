<?php
session_start();
include("db.php");

// Only allow accounts role
if(!isset($_SESSION['username']) || $_SESSION['role'] != 'accounts'){
    header("Location: account_login.php");
    exit();
}

// Fetch students
$students = $conn->query("SELECT * FROM students ORDER BY student_name ASC");

// Fetch no dues requests
$dues = $conn->query("
    SELECT n.id, n.student_id, n.account_status, n.account_remark, 
           n.hod_status, n.transport_status, n.hostel_status, 
           n.library_status, n.registrar_status, s.student_name
    FROM no_dues_requests n
    JOIN students s ON n.student_id = s.id
    ORDER BY n.id DESC
");

// Handle AJAX request for account status update
if(isset($_POST['update_status'])){
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $remark = $conn->real_escape_string($_POST['remark']);
    
    $update = $conn->query("UPDATE no_dues_requests SET account_status='$status', account_remark='$remark' WHERE id=$id");
    
    if($update){
        echo "success";
    } else {
        echo "error";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Accounts Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body { font-family: 'Poppins', sans-serif; margin:0; padding:0; display:flex; min-height:100vh; background:#f5f6fa; }
/* Sidebar */
.sidebar { width:220px; background:#6c5ce7; color:white; display:flex; flex-direction:column; padding:20px 0; }
.sidebar h2 { text-align:center; margin-bottom:30px; font-size:22px; }
.sidebar a { padding:12px 20px; text-decoration:none; color:white; font-weight:500; display:flex; align-items:center; gap:10px; transition:0.3s; cursor:pointer; }
.sidebar a:hover, .sidebar a.active { background:#5a4bcf; border-radius:8px; }
/* Main content */
.main-content { flex:1; padding:25px; }
section { display:none; }
section.active { display:block; }

h2.section-title { margin-bottom:20px; color:#6c5ce7; text-align:center; }

/* Table */
table { width:100%; border-collapse:collapse; margin-top:20px; }
th, td { padding:12px 10px; border:1px solid #ddd; text-align:left; }
th { background: linear-gradient(90deg,#6c5ce7,#00b894); color:white; }
tr:nth-child(even){ background:#f2f2f2; }
tr:hover{ background:#dfe6e9; }

select, input[type=text] { padding:5px 8px; border-radius:5px; border:1px solid #ccc; width:100%; }
button { padding:5px 10px; border:none; border-radius:5px; background:#6c5ce7; color:white; cursor:pointer; transition:0.3s; }
button:hover { background:#5a4bcf; }

.status-approved { background-color:#2ecc71; color:white; padding:3px 7px; border-radius:5px; }
.status-rejected { background-color:#e74c3c; color:white; padding:3px 7px; border-radius:5px; }
.status-pending { background-color:#f1c40f; color:white; padding:3px 7px; border-radius:5px; }

/* Responsive */
@media(max-width:900px){
    body{ flex-direction:column; }
    .sidebar{ width:100%; flex-direction:row; justify-content:space-around; padding:10px 0; }
    .sidebar a{ padding:8px 10px; font-size:14px; }
    table, th, td{ font-size:14px; }
}
.filter-box{
    display:flex;
    gap:15px;
    margin-bottom:15px;
}

.filter-box input,
.filter-box select{
    padding:8px 12px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:14px;
    outline:none;
    transition:0.3s;
}

.filter-box input:focus,
.filter-box select:focus{
    border-color:#6c5ce7;
    box-shadow:0 0 5px rgba(108,92,231,0.4);
}

</style>

<script>
function updateStatus(id){
    var status = $('#status-'+id).val();
    var remark = $('#remark-'+id).val();
    $.post('', {update_status:1, id:id, status:status, remark:remark}, function(res){
        if(res=='success'){
            alert('Status updated successfully!');
            location.reload();
        } else {
            alert('Error updating status.');
        }
    });
}

$(document).ready(function(){
    // Default: show students
    $('.sidebar a').click(function(){
        $('.sidebar a').removeClass('active');
        $(this).addClass('active');
        var target = $(this).data('target');
        $('section').removeClass('active');
        $(target).addClass('active');
    });
    $('.sidebar a[data-target="#students-section"]').click(); // default
});
</script>
</head>
<body>

<div class="sidebar">
    <h2>Accounts</h2>
    <a data-target="#students-section"><i class="bi bi-people-fill"></i> Registered Students</a>
    <a data-target="#no-dues-section"><i class="bi bi-check2-circle"></i> No Dues Requests</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="main-content">

    <!-- Students Section -->
    <section id="students-section">
        <h2 class="section-title">📚 Registered Students</h2>
        <?php if($students && $students->num_rows>0): ?>
        <table>
            <tr>
                <th>Name</th><th>Student ID</th><th>Email</th><th>Course</th><th>Department</th>
            </tr>
            <?php while($s=$students->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($s['student_name']); ?></td>
                <td><?php echo htmlspecialchars($s['student_id']); ?></td>
                <td><?php echo htmlspecialchars($s['student_email']); ?></td>
                <td><?php echo htmlspecialchars($s['course']); ?></td>
                <td><?php echo htmlspecialchars($s['department']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No students found.</p>
        <?php endif; ?>
    </section>

    <!-- No Dues Section -->
    <section id="no-dues-section">
        <h2 class="section-title">📝 No Dues Requests</h2>
        <div class="filter-box">
    <input type="text" id="searchInput" placeholder="🔍 Search by Student Name">

    <select id="statusFilter">
        <option value="">All Status</option>
        <option value="Pending">Pending</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
    </select>
</div>

        <?php if($dues && $dues->num_rows>0): ?>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Student ID</th>
                <th>Account Status</th>
                <th>Account Remark</th>
                <th>HOD Status</th>
                <th>Transport Status</th>
                <th>Hostel Status</th>
                <th>Library Status</th>
                <th>Registrar Status</th>
                <th>Action</th>
            </tr>
            <?php while($d=$dues->fetch_assoc()): ?>
            <tr class="data-row"
    data-name="<?php echo strtolower(trim($d['student_name'])); ?>" 
    data-status="<?php echo $d['account_status']; ?>">

                <td><?php echo htmlspecialchars($d['student_name']); ?></td>
                <td><?php echo htmlspecialchars($d['student_id']); ?></td>
                <td>
                    <span class="status-<?php echo strtolower($d['account_status']); ?>"><?php echo $d['account_status']; ?></span>
                </td>
                <td><input type="text" id="remark-<?php echo $d['id']; ?>" value="<?php echo htmlspecialchars($d['account_remark']); ?>" placeholder="Remark"></td>
                <td><?php echo $d['hod_status']; ?></td>
                <td><?php echo $d['transport_status']; ?></td>
                <td><?php echo $d['hostel_status']; ?></td>
                <td><?php echo $d['library_status']; ?></td>
                <td><?php echo $d['registrar_status']; ?></td>
                <td>
                    <select id="status-<?php echo $d['id']; ?>">
                        <option value="Pending" <?php if($d['account_status']=='Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Approved" <?php if($d['account_status']=='Approved') echo 'selected'; ?>>Approved</option>
                        <option value="Rejected" <?php if($d['account_status']=='Rejected') echo 'selected'; ?>>Rejected</option>
                    </select>
                    <button onclick="updateStatus(<?php echo $d['id']; ?>)">Update</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No dues requests found.</p>
        <?php endif; ?>
    </section>

</div>

<script>
$(document).ready(function(){

    function filterTable(){
        let search = $('#searchInput').val().toLowerCase().trim();
        let status = $('#statusFilter').val();

        $('.data-row').each(function(){
            let name = $(this).attr('data-name');
            let rowStatus = $(this).attr('data-status');

            let nameMatch = name.includes(search);
            let statusMatch = (status === "" || rowStatus === status);

            if(nameMatch && statusMatch){
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('#searchInput').on('keyup', filterTable);
    $('#statusFilter').on('change', filterTable);

});
</script>


</body>
</html>
