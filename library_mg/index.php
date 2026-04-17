<?php
session_start();
include("db.php");

// Check login
if(!isset($_SESSION['library_id'])){
    header("Location: library_login.php");
    exit();
}

// Active page
$page = $_GET['page'] ?? 'home';

// ===== POST HANDLERS =====
// 1. Add Book
if(isset($_POST['add_book'])){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $qty = $_POST['quantity'];

    $conn->query("INSERT INTO books (title, author, category, quantity, available) VALUES ('$title','$author','$category','$qty','$qty')");
    echo "<script>alert('✅ Book Added Successfully');</script>";
}

// 3. Issue Book
if(isset($_POST['issue_book'])){
    $book = $_POST['book_id'];
    $student = $_POST['student_id'];
    $date = date("Y-m-d");

    $conn->query("INSERT INTO issue_books (book_id, student_id, issue_date) VALUES ($book,$student,'$date')");
    $conn->query("UPDATE books SET available = available - 1 WHERE id=$book");
    echo "<script>alert('✅ Book Issued Successfully');</script>";
}

// 4. Return Book
if(isset($_POST['return_book'])){
    $id = $_POST['issue_id'];
    $today = date("Y-m-d");

    $info = $conn->query("SELECT issue_date, book_id FROM issue_books WHERE id=$id")->fetch_assoc();
    $issue = $info['issue_date'];
    $days = (strtotime($today) - strtotime($issue)) / 86400;
    $fine = ($days > 7) ? ($days-7)*10 : 0;

    $conn->query("UPDATE issue_books SET return_status=1, return_date='$today', fine=$fine WHERE id=".$id);
    $conn->query("UPDATE books SET available = available + 1 WHERE id=".$info['book_id']);
    echo "<script>alert('✅ Book Returned. Fine: Rs $fine');</script>";
}

// ===== NO DUES REQUESTS HANDLERS =====
// Handle Library Approval
if (isset($_POST['update_library'])) {

    $req_id = $_POST['request_id'];
    $status = $_POST['library_status'];
    $remark = $conn->real_escape_string($_POST['library_remark']);

    $sql = "UPDATE no_dues_requests 
            SET library_status='$status',
                library_remark='$remark'
            WHERE id='$req_id'";

    if ($conn->query($sql)) {
        $_SESSION['success_msg'] = "Library status updated successfully!";
    } else {
        $_SESSION['success_msg'] = "Error while updating library status!";
    }

    // PRG Redirect
    header("Location: index.php#no_dues_requests");
    exit();
}


// CHANGE PASSWORD HANDLER
if(isset($_POST['change_password'])){
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $library_id = $_SESSION['library_id'];

    // Current password check
    $res = $conn->query("SELECT password FROM library_staff WHERE id='$library_id'");
    $row = $res->fetch_assoc();

    if(password_verify($current, $row['password'])){
        if($new === $confirm){
            $new_hash = password_hash($new, PASSWORD_BCRYPT);
            $conn->query("UPDATE library_staff SET password='$new_hash' WHERE id='$library_id'");
            $_SESSION['success_msg'] = "✅ Password updated successfully!";
        } else {
            $_SESSION['success_msg'] = "⚠️ New password and confirm password do not match!";
        }
    } else {
        $_SESSION['success_msg'] = "❌ Current password is incorrect!";
    }

    header("Location: index.php?page=change_password");
    exit();
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Dashboard - Sandip University</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background:#f5f5f5; }
        
        /* HEADER */
        .navbar-brand { 
            font-size: 1.5rem; 
            font-weight: bold; 
            letter-spacing: 1px; 
        }

        /* SIDEBAR */
        .sidebar { 
            height: calc(100vh - 60px); 
            width:250px; 
            background:#343a40; 
            position:fixed; 
            top:60px;
            padding-top:20px; 
        }
        .sidebar a { 
            padding:12px 20px; 
            display:block; 
            color:white; 
            text-decoration:none; 
            font-size:18px; 
            transition: 0.2s;
        }
        .sidebar a:hover { background:#495057; }

        /* MAIN CONTENT */
        .content { 
            margin-left:260px; 
            padding:20px; 
        }

        .section { display:none; }
        #dashboard { display:block; }

        .card-icon { font-size:30px; }
        .overdue { background:#f8d7da !important; font-weight:bold; }

        /* TABLE */
        table th, table td { vertical-align: middle !important; }

        /* BUTTONS */
        .btn { transition: 0.2s; }
        .btn:hover { transform: scale(1.05); }

        /* HEADER FIXED SPACER */
        .header-spacer { height:60px; }
    </style>
</head>
<body>

<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <span class="navbar-brand w-100 text-center">🏫 Sandip University Library Dashboard</span>
    </div>
</nav>

<div class="header-spacer"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="#" onclick="showSection('dashboard')">📊 Dashboard</a>
    <a href="#" onclick="showSection('add_book')">📚 Add Book</a>
    <a href="#" onclick="showSection('issue_book')">📖 Issue Book</a>
    <a href="#" onclick="showSection('return_book')">🔄 Return Book</a>
    <a href="#" onclick="showSection('search')">🔍 Search Books</a>
    <a href="#" onclick="showSection('no_dues_requests')">📝 No Dues Requests</a>
    <a href="#" onclick="showSection('change_password')">🔑 Change Password</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="content">

<!-- DASHBOARD -->
<div id="dashboard" class="section">
    <h2>📊 Dashboard</h2><hr>
    <?php
        $books = $conn->query("SELECT COUNT(*) AS total FROM books")->fetch_assoc()['total'];
        $students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
        $issued = $conn->query("SELECT COUNT(*) AS total FROM issue_books WHERE return_status=0")->fetch_assoc()['total'];
        $returned = $conn->query("SELECT COUNT(*) AS total FROM issue_books WHERE return_status=1")->fetch_assoc()['total'];
        $overdue = $conn->query("SELECT COUNT(*) AS total FROM issue_books WHERE return_status=0 AND DATEDIFF(CURDATE(),issue_date)>7")->fetch_assoc()['total'];
    ?>
    <div class="row mt-3 g-3">
        <div class="col-md-2">
            <div class="card p-3 text-bg-primary shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Books</h6>
                        <h4><?= $books ?></h4>
                    </div>
                    <i class="bi bi-book card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card p-3 text-bg-info shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Students</h6>
                        <h4><?= $students ?></h4>
                    </div>
                    <i class="bi bi-people card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card p-3 text-bg-warning shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Issued Books</h6>
                        <h4><?= $issued ?></h4>
                    </div>
                    <i class="bi bi-journal-arrow-up card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card p-3 text-bg-success shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Returned Books</h6>
                        <h4><?= $returned ?></h4>
                    </div>
                    <i class="bi bi-journal-arrow-down card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card p-3 text-bg-danger shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Overdue Books</h6>
                        <h4><?= $overdue ?></h4>
                    </div>
                    <i class="bi bi-exclamation-circle card-icon"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADD BOOK -->
<div id="add_book" class="section">
    <h2>📚 Add Book</h2><hr>
    <form method="POST">
        <input type="text" name="title" class="form-control mb-2" placeholder="Book Title" required>
        <input type="text" name="author" class="form-control mb-2" placeholder="Author" required>
        <input type="text" name="category" class="form-control mb-2" placeholder="Category">
        <input type="number" name="quantity" class="form-control mb-2" placeholder="Quantity" required>
        <button class="btn btn-primary w-100" name="add_book">Add Book</button>
    </form>
</div>

<!-- ISSUE BOOK -->
<div id="issue_book" class="section">
    <h2>📖 Issue Book</h2><hr>
    <form method="POST">
        <label>Select Book</label>
        <select name="book_id" class="form-control mb-2">
            <?php
            $booksList = $conn->query("SELECT * FROM books WHERE available>0");
            while($b = $booksList->fetch_assoc()){
                echo "<option value='{$b['id']}'>{$b['title']} ({$b['available']} left)</option>";
            }
            ?>
        </select>

        <label>Select Student</label>
        <select name="student_id" class="form-control mb-2">
            <?php
            $studentsList = $conn->query("SELECT * FROM students");
            while($s = $studentsList->fetch_assoc()){
                echo "<option value='{$s['id']}'>{$s['student_name']} (ID: {$s['student_id']})</option>";
            }
            ?>
        </select>

        <button class="btn btn-dark w-100" name="issue_book">Issue Book</button>
    </form>
</div>

<!-- RETURN BOOK -->
<div id="return_book" class="section">
    <h2>🔄 Return Book</h2><hr>
    <table class="table table-bordered table-striped">
        <tr class="table-dark">
            <th>Book</th>
            <th>Student</th>
            <th>Issue Date</th>
            <th>Days Since Issue</th>
            <th>Fine (₹)</th>
            <th>Return</th>
        </tr>
        <?php
        $issuedBooks = $conn->query("SELECT issue_books.*, books.title, students.student_name
                                     FROM issue_books
                                     JOIN books ON issue_books.book_id = books.id
                                     JOIN students ON issue_books.student_id = students.id
                                     WHERE return_status=0");
        while($i = $issuedBooks->fetch_assoc()){
            $issue_date = $i['issue_date'];
            $today = date('Y-m-d');
            $days = (strtotime($today) - strtotime($issue_date)) / 86400;
            $fine = ($days > 7) ? ($days-7)*10 : 0;
            $overdueClass = ($days > 7) ? "table-danger fw-bold" : "";

            echo "<tr class='$overdueClass'>
                    <td>{$i['title']}</td>
                    <td>{$i['student_name']}</td>
                    <td>{$issue_date}</td>
                    <td>".intval($days)." days</td>
                    <td>₹$fine</td>
                    <td>
                        <form method='POST'>
                            <input type='hidden' name='issue_id' value='{$i['id']}'>
                            <button class='btn btn-success' name='return_book'>Return</button>
                        </form>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</div>

<!-- SEARCH BOOKS -->
<div id="search" class="section">
    <h2>🔍 Search Books</h2><hr>
    <form method="GET">
        <input type="text" name="q" class="form-control mb-2" placeholder="Search Title or Author...">
        <button class="btn btn-secondary w-100">Search</button>
    </form>

    <?php
    if(isset($_GET['q'])){
        $q = $_GET['q'];
        $res = $conn->query("SELECT * FROM books WHERE title LIKE '%$q%' OR author LIKE '%$q%'");
        echo "<table class='table table-striped mt-2'><tr class='table-dark'><th>Title</th><th>Author</th><th>Available</th></tr>";
        while($r = $res->fetch_assoc()){
            echo "<tr><td>{$r['title']}</td><td>{$r['author']}</td><td>{$r['available']}</td></tr>";
        }
        echo "</table>";
    }
    ?>
</div>

<?php
if (isset($_SESSION['success_msg'])) {
    echo "<script>
            alert('{$_SESSION['success_msg']}');
          </script>";
    unset($_SESSION['success_msg']); // refresh pe repeat nahi hoga
}
?>

<!-- NO DUES REQUESTS FOR ADMIN/LIBRARY -->
<div id="no_dues_requests" class="section">
    <h2>📝 Student No Dues Requests</h2>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Student</th>
                    <th>Account</th>
                    <th>HOD</th>
                    <th>Transport</th>
                    <th>Hostel</th>
                    <th>Library</th>
                    <th>Registrar</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $requests = $conn->query("
                    SELECT nd.*, s.student_name 
                    FROM no_dues_requests nd
                    JOIN students s ON nd.student_id=s.id
                    ORDER BY nd.created_at DESC
                ");

                if($requests->num_rows > 0){
                    while($r=$requests->fetch_assoc()){
                        echo "<tr>
                                <td>{$r['student_name']}</td>
                                <td>{$r['account_status']}<br><small>{$r['account_remark']}</small></td>
                                <td>{$r['hod_status']}<br><small>{$r['hod_remark']}</small></td>
                                <td>{$r['transport_status']}<br><small>{$r['transport_remark']}</small></td>
                                <td>{$r['hostel_status']}<br><small>{$r['hostel_remark']}</small></td>
                                <td>
                                    <form method='POST'>
                                        <input type='hidden' name='request_id' value='{$r['id']}'>
                                        <select name='library_status' class='form-select mb-1'>
                                            <option value='Pending' ".($r['library_status']=='Pending'?'selected':'').">Pending</option>
                                            <option value='Approved' ".($r['library_status']=='Approved'?'selected':'').">Approved</option>
                                            <option value='Rejected' ".($r['library_status']=='Rejected'?'selected':'').">Rejected</option>
                                        </select>
                                        <input type='text' name='library_remark' class='form-control mb-1' placeholder='Remark' value='{$r['library_remark']}'>
                                        <button type='submit' name='update_library' class='btn btn-sm btn-success w-100'>Update</button>
                                    </form>
                                </td>
                                <td>{$r['registrar_status']}<br><small>{$r['registrar_remark']}</small></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No No Dues requests available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<?php
if (isset($_SESSION['success_msg'])) {
    echo "<script>alert('{$_SESSION['success_msg']}');</script>";
    unset($_SESSION['success_msg']); // Refresh pe repeat nahi hoga
}
?>

<!-- CHANGE PASSWORD SECTION -->
<div id="change_password" class="section">
    <h2>🔑 Change Password</h2>
    <hr>
    <form method="POST">
        <label class="form-label fw-bold">Current Password</label>
        <input type="password" name="current_password" class="form-control mb-3" required>

        <label class="form-label fw-bold">New Password</label>
        <input type="password" name="new_password" class="form-control mb-3" required>

        <label class="form-label fw-bold">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control mb-3" required>

        <button type="submit" name="change_password" class="btn btn-success w-100">💾 Update Password</button>
    </form>
</div>


</div>

<script>
function showSection(id){
    document.querySelectorAll('.section').forEach(s=>s.style.display='none');
    document.getElementById(id).style.display='block';
}
</script>

</body>
</html>
