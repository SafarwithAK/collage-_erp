<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>University Management System</title>
  <style>
    * {margin:0;padding:0;box-sizing:border-box;}
    body {font-family:'Segoe UI', Tahoma, sans-serif; background:#f4f6f9; display:flex;}

    /* Sidebar */
    .sidebar {
      width:250px;
      background:linear-gradient(180deg,#4e54c8,#8f94fb);
      color:white;
      height:100vh;
      position:fixed;
      left:0; top:0;
      padding:20px 0;
      display:flex;
      flex-direction:column;
      box-shadow:2px 0 8px rgba(0,0,0,0.15);
      overflow-y:auto;
    }
    .sidebar h2 { text-align:center; margin-bottom:20px; font-size:22px; }
    .sidebar ul { list-style:none; padding:0; }
    .sidebar ul li { margin:5px 0; }
    .sidebar ul li a {
      display:block;
      padding:12px 20px;
      color:white;
      text-decoration:none;
      font-size:15px;
      transition:background 0.3s;
      cursor:pointer;
    }
    .sidebar ul li a:hover,
    .sidebar ul li a.active {
      background:rgba(255,255,255,0.2);
      border-radius:6px;
    }
    .submenu {
      display:none;
      background:rgba(0,0,0,0.1);
    }
    .submenu a {
      padding:10px 40px;
      font-size:14px;
    }
    .logout-btn {
      margin-top:auto;
      background:#e74c3c;
      text-align:center;
      padding:12px;
      border-radius:6px;
      text-decoration:none;
      color:white;
    }
    .logout-btn:hover { background:#c0392b; }

    /* Main content */
    .main-content {
      margin-left:250px;
      padding:20px;
      width:calc(100% - 250px);
    }
    .page {
      display:none;
      background:#fff;
      padding:20px;
      margin-bottom:20px;
      border-radius:10px;
      box-shadow:0 4px 10px rgba(0,0,0,0.1);
      animation:fadeIn 0.5s ease-in-out;
    }
    .page h2 { color:#4e54c8; margin-bottom:15px; }
    @keyframes fadeIn {
      from {opacity:0; transform:translateY(20px);}
      to {opacity:1; transform:translateY(0);}
    }

    table { width:100%; border-collapse:collapse; margin-top:15px; }
    table th, table td { padding:10px; border:1px solid #ddd; text-align:center; }
    table th { background:#4e54c8; color:white; }
    table tr:nth-child(even) {background:#f9f9f9;}
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>🎓 Dashboard</h2>
    <ul>
      <li><a onclick="showPage('home')" class="active">🏠 Home</a></li>

      <li>
        <a onclick="toggleMenu('studentMenu')">📚 Students ▼</a>
        <div class="submenu" id="studentMenu">
          <a onclick="showPage('students')">All Students</a>
          <a onclick="showPage('addStudent')">Add Student</a>
        </div>
      </li>

      <li>
        <a onclick="toggleMenu('facultyMenu')">👨‍🏫 Faculty ▼</a>
        <div class="submenu" id="facultyMenu">
          <a onclick="showPage('faculty')">All Faculty</a>
          <a onclick="showPage('addFaculty')">Add Faculty</a>
        </div>
      </li>

      <li>
        <a onclick="toggleMenu('assignmentMenu')">📝 Assignments ▼</a>
        <div class="submenu" id="assignmentMenu">
          <a onclick="showPage('faculty1')">All Assignments</a>
          <a onclick="showPage('addAssignment')">Assign New</a>
        </div>
      </li>
    </ul>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="welcome-user">
      Welcome, <span style="color:#4e54c8; font-weight:bold;"><?php echo $_SESSION['username']; ?></span>!
    </div>

    <!-- Home -->
    <section id="home" class="page" style="display:block;">
      <h2>Welcome to Our Admin Dashboard</h2>
      <p>This is the main dashboard for managing university operations.</p>
    </section>

    <!-- All Students -->
    <section id="students" class="page">
      <h2>📚 Registered Students</h2>
      <?php
      $sql = "SELECT student_name, student_id, student_email FROM students";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        echo "<table><tr><th>Student Name</th><th>Student ID</th><th>Email</th></tr>";
        while ($row = $result->fetch_assoc()) {
          echo "<tr><td>{$row['student_name']}</td><td>{$row['student_id']}</td><td>{$row['student_email']}</td></tr>";
        }
        echo "</table>";
      } else {
        echo "<p style='color:gray;'>No students registered yet.</p>";
      }
      ?>
    </section>

    <!-- Add Student -->
    <section id="addStudent" class="page">
      <h2>➕ Add Student</h2>
      <form method="POST" action="add_student.php">
        <input type="text" name="student_name" placeholder="Student Name" required><br><br>
        <input type="text" name="student_id" placeholder="Student ID" required><br><br>
        <input type="email" name="student_email" placeholder="Email" required><br><br>
        <input type="password" name="student_password" placeholder="Password" required><br><br>
        <input type="submit" value="Add Student">
      </form>
    </section>

    <!-- All Faculty -->
    <section id="faculty" class="page">
      <h2>👨‍🏫 Faculty Section</h2>
      <?php
      $sql = "SELECT faculty_name, faculty_id, faculty_email FROM faculty";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        echo "<table><tr><th>Faculty Name</th><th>Faculty ID</th><th>Email</th></tr>";
        while ($row = $result->fetch_assoc()) {
          echo "<tr><td>{$row['faculty_name']}</td><td>{$row['faculty_id']}</td><td>{$row['faculty_email']}</td></tr>";
        }
        echo "</table>";
      } else {
        echo "<p style='color:gray;'>No faculty registered yet.</p>";
      }
      ?>
    </section>

    <!-- Add Faculty -->
    <section id="addFaculty" class="page">
      <h2>➕ Add Faculty</h2>
      <form method="POST" action="add_faculty.php">
        <input type="text" name="faculty_name" placeholder="Faculty Name" required><br><br>
        <input type="text" name="faculty_id" placeholder="Faculty ID" required><br><br>
        <input type="email" name="faculty_email" placeholder="Email" required><br><br>
        <input type="password" name="faculty_password" placeholder="Password" required><br><br>
        <input type="submit" value="Add Faculty">
      </form>
    </section>

    <!-- All Assignments -->
    <section id="faculty1" class="page">
      <h2>📝 Faculty Assigned Assignments</h2>
      <?php
      $sql = "
          SELECT a.assignment_id, f.faculty_name, s.student_name, sub.subject_name, a.title, a.description, a.assigned_date
          FROM assignments a
          JOIN faculty f ON a.faculty_id = f.faculty_id
          JOIN students s ON a.student_id = s.student_id
          JOIN subjects sub ON a.subject_id = sub.subject_id
          ORDER BY a.assigned_date DESC
      ";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        echo "<table><tr><th>Faculty</th><th>Student</th><th>Subject</th><th>Title</th><th>Description</th><th>Date</th></tr>";
        while($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['faculty_name']}</td>
                  <td>{$row['student_name']}</td>
                  <td>{$row['subject_name']}</td>
                  <td>{$row['title']}</td>
                  <td>{$row['description']}</td>
                  <td>{$row['assigned_date']}</td>
                </tr>";
        }
        echo "</table>";
      } else {
        echo "<p style='color:red;'>⚠️ No assignments assigned yet.</p>";
      }
      ?>
    </section>

    <!-- Assign New -->
    <section id="addAssignment" class="page">
      <h2>➕ Assign New Assignment</h2>
      <form method="POST" action="assign_assignment.php">
        <select name="faculty_id" required>
          <option value="">Select Faculty</option>
          <?php
          $f = $conn->query("SELECT faculty_id, faculty_name FROM faculty");
          while($row = $f->fetch_assoc()){
              echo "<option value='{$row['faculty_id']}'>{$row['faculty_name']}</option>";
          }
          ?>
        </select><br><br>

        <select name="student_id" required>
          <option value="">Select Student</option>
          <?php
          $st = $conn->query("SELECT student_id, student_name FROM students");
          while($row = $st->fetch_assoc()){
              echo "<option value='{$row['student_id']}'>{$row['student_name']}</option>";
          }
          ?>
        </select><br><br>

        <select name="subject_id" required>
          <option value="">Select Subject</option>
          <?php
          $sub = $conn->query("SELECT subject_id, subject_name FROM subjects");
          while($row = $sub->fetch_assoc()){
              echo "<option value='{$row['subject_id']}'>{$row['subject_name']}</option>";
          }
          ?>
        </select><br><br>

        <input type="text" name="title" placeholder="Assignment Title" required><br><br>
        <textarea name="description" placeholder="Assignment Description" required></textarea><br><br>
        <input type="submit" value="Assign Assignment">
      </form>
    </section>
  </div>

  <script>
    function showPage(pageId) {
      document.querySelectorAll('.page').forEach(p=>p.style.display='none');
      document.getElementById(pageId).style.display='block';
      document.querySelectorAll('.sidebar a').forEach(l=>l.classList.remove('active'));
    }
    function toggleMenu(menuId){
      const menu=document.getElementById(menuId);
      menu.style.display=(menu.style.display==='block')?'none':'block';
    }
  </script>
</body>
</html>
