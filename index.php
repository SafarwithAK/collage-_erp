<?php
/**
 * College ERP System - Comprehensive PHP Implementation
 * Full English Version with all data views
 */

session_start();

// --- DATABASE CONFIGURATION ---
$db_host = 'localhost';
$db_name = 'college_erp';
$db_user = 'root';
$db_pass = 'Ajit@9334';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// --- LOGOUT LOGIC ---
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// --- LOGIN LOGIC ---
$login_error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php?page=dashboard");
        exit;
    } else {
        $login_error = "Invalid credentials! Use a username from the 'users' table.";
    }
}

$is_logged_in = isset($_SESSION['user_id']);
$current_page = $_GET['page'] ?? 'dashboard';

// Helper for counts
function getCount($pdo, $table) {
    return $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College ERP - Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

<?php if (!$is_logged_in): ?>
    <!-- LOGIN SCREEN -->
    <div class="min-h-screen flex items-center justify-center p-6 bg-gradient-to-br from-indigo-50 to-white">
        <div class="max-w-md w-full bg-white rounded-[2rem] shadow-2xl border border-slate-100 p-10">
            <div class="flex flex-col items-center mb-10">
                <div class="h-16 w-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl mb-4">
                    <i class="fas fa-graduation-cap text-3xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-800">College ERP</h2>
                <p class="text-slate-400 font-medium">Administration Portal</p>
            </div>
            
            <?php if ($login_error): ?>
                <div class="mb-6 p-4 bg-red-50 text-red-600 text-sm rounded-2xl border border-red-100 font-bold flex items-center">
                    <i class="fas fa-exclamation-circle mr-3"></i> <?php echo $login_error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2 ml-1">Username</label>
                    <input type="text" name="username" required 
                        class="w-full px-6 py-4 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 transition-all font-medium"
                        placeholder="e.g. Ajit@123">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2 ml-1">Password</label>
                    <input type="password" name="password" required 
                        class="w-full px-6 py-4 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50 transition-all font-medium"
                        placeholder="••••••••">
                </div>
                <button type="submit" name="login" 
                    class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all hover:-translate-y-1">
                    Sign In
                </button>
            </form>
        </div>
    </div>

<?php else: ?>
    <!-- APP SHELL -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside class="w-80 bg-white border-r border-slate-200 flex flex-col z-30">
            <div class="p-10 flex items-center gap-4">
                <div class="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span class="font-black text-2xl tracking-tighter">CAMPUS<span class="text-indigo-600">OS</span></span>
            </div>

            <nav class="flex-1 px-8 space-y-2 overflow-y-auto">
                <?php 
                $nav = [
                    ['dashboard', 'fa-home', 'Dashboard'],
                    ['students', 'fa-user-graduate', 'Students'],
                    ['faculty', 'fa-chalkboard-teacher', 'Faculty'],
                    ['attendance', 'fa-calendar-check', 'Attendance'],
                    ['library', 'fa-book', 'Library'],
                    ['assignments', 'fa-tasks', 'Assignments'],
                    ['results', 'fa-poll-h', 'Results'],
                    ['nodues', 'fa-file-invoice-dollar', 'No Dues'],
                ];
                foreach($nav as $item): 
                    $active = ($current_page == $item[0]);
                ?>
                <a href="?page=<?php echo $item[0]; ?>" class="flex items-center gap-4 px-5 py-4 rounded-2xl transition-all <?php echo $active ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600'; ?>">
                    <i class="fas <?php echo $item[1]; ?> w-5"></i> <?php echo $item[2]; ?>
                </a>
                <?php endforeach; ?>
            </nav>

            <div class="p-8 border-t border-slate-100">
                <a href="?action=logout" class="flex items-center gap-4 px-5 py-4 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-2xl transition-all font-bold">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 flex flex-col bg-slate-50/50 overflow-hidden">
            <!-- Global Header -->
            <header class="h-24 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-12 sticky top-0 z-20">
                <div>
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Management</h2>
                    <h1 class="text-2xl font-black text-slate-800 capitalize"><?php echo $current_page; ?></h1>
                </div>
                <div class="flex items-center gap-8">
                    <div class="hidden md:flex flex-col text-right">
                        <p class="text-sm font-black text-slate-800"><?php echo $_SESSION['username']; ?></p>
                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest"><?php echo $_SESSION['role']; ?> Access</p>
                    </div>
                    <div class="h-12 w-12 bg-indigo-50 rounded-2xl border-2 border-indigo-100 flex items-center justify-center font-black text-indigo-600">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                </div>
            </header>

            <!-- Dynamic Content Area -->
            <section class="flex-1 overflow-y-auto p-12">
                
                <?php if ($current_page == 'dashboard'): ?>
                    <!-- DASHBOARD -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                        <?php 
                        $stats = [
                            ['Active Students', getCount($pdo, 'students'), 'fa-user-graduate', 'blue'],
                            ['Total Faculty', getCount($pdo, 'faculty'), 'fa-chalkboard-teacher', 'indigo'],
                            ['Catalog Books', getCount($pdo, 'books'), 'fa-book', 'purple'],
                            ['Assignments', getCount($pdo, 'assignments'), 'fa-file-alt', 'orange'],
                        ];
                        foreach($stats as $stat):
                        ?>
                        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                            <div class="flex justify-between items-center mb-6">
                                <div class="p-4 bg-<?php echo $stat[3]; ?>-50 text-<?php echo $stat[3]; ?>-600 rounded-2xl text-xl">
                                    <i class="fas <?php echo $stat[2]; ?>"></i>
                                </div>
                            </div>
                            <h3 class="text-4xl font-black text-slate-800"><?php echo $stat[1]; ?></h3>
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-2"><?php echo $stat[0]; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                        <!-- Notices -->
                        <div class="lg:col-span-2 bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
                            <h2 class="text-xl font-black text-slate-800 mb-8 flex items-center gap-4">
                                <i class="fas fa-bullhorn text-indigo-500"></i> Latest News & Notices
                            </h2>
                            <div class="space-y-6">
                                <?php 
                                $notices = $pdo->query("SELECT * FROM notices ORDER BY created_at DESC LIMIT 3")->fetchAll();
                                foreach($notices as $notice):
                                ?>
                                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 hover:bg-white hover:shadow-md transition-all group">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-extrabold text-slate-800 group-hover:text-indigo-600"><?php echo $notice['title']; ?></h4>
                                        <span class="text-[10px] font-black text-slate-400 uppercase"><?php echo date('d M', strtotime($notice['created_at'])); ?></span>
                                    </div>
                                    <p class="text-sm text-slate-500 leading-relaxed"><?php echo $notice['message']; ?></p>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="bg-indigo-600 p-10 rounded-[2.5rem] text-white relative overflow-hidden flex flex-col justify-center">
                            <i class="fas fa-university absolute -bottom-10 -right-10 text-[180px] opacity-10 rotate-12"></i>
                            <h2 class="text-3xl font-black mb-4">Academic Year 2026</h2>
                            <p class="opacity-80 font-medium mb-10 leading-relaxed">You have full administrative control over student records and department logistics.</p>
                            <a href="?page=students" class="w-full py-4 bg-white text-indigo-600 text-center font-black rounded-2xl shadow-xl shadow-indigo-900/20 hover:scale-105 transition-all">View All Students</a>
                        </div>
                    </div>

                <?php elseif ($current_page == 'students'): ?>
                    <!-- STUDENTS -->
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-10 border-b border-slate-50 flex justify-between items-center">
                            <h2 class="text-2xl font-black text-slate-800">Student Enrollment</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-[11px] font-black uppercase text-slate-400 tracking-widest">
                                        <th class="px-10 py-6">Student</th>
                                        <th class="px-10 py-6">Details</th>
                                        <th class="px-10 py-6">Program</th>
                                        <th class="px-10 py-6">Account Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php 
                                    $students = $pdo->query("SELECT * FROM students")->fetchAll();
                                    foreach($students as $s):
                                    ?>
                                    <tr class="hover:bg-indigo-50/30 transition-all">
                                        <td class="px-10 py-8">
                                            <div class="flex items-center gap-5">
                                                <div class="h-12 w-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center font-black">
                                                    <?php echo strtoupper($s['student_name'][0]); ?>
                                                </div>
                                                <div>
                                                    <p class="font-extrabold text-slate-800"><?php echo $s['student_name']; ?></p>
                                                    <p class="text-xs text-slate-400 font-bold">Roll: <?php echo $s['student_id']; ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-10 py-8">
                                            <p class="text-sm font-bold text-slate-600"><?php echo $s['student_email']; ?></p>
                                            <p class="text-[10px] text-slate-400 uppercase font-black mt-1">Join Date: <?php echo date('Y-m-d', strtotime($s['created_at'])); ?></p>
                                        </td>
                                        <td class="px-10 py-8">
                                            <span class="px-4 py-1.5 bg-slate-100 rounded-xl text-[10px] font-black uppercase text-slate-500 border border-slate-200"><?php echo $s['course']; ?> (<?php echo $s['department']; ?>)</span>
                                        </td>
                                        <td class="px-10 py-8">
                                            <span class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-wider <?php echo $s['status'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                                <?php echo $s['status'] ? 'Active' : 'Restricted'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php elseif ($current_page == 'faculty'): ?>
                    <!-- FACULTY -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php 
                        $faculty = $pdo->query("SELECT * FROM faculty")->fetchAll();
                        foreach($faculty as $f):
                        ?>
                        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative group overflow-hidden">
                            <div class="h-1 bg-indigo-500 absolute top-0 left-0 w-full opacity-0 group-hover:opacity-100 transition-all"></div>
                            <div class="flex items-center gap-6 mb-8">
                                <div class="h-16 w-16 bg-slate-100 rounded-3xl flex items-center justify-center text-slate-400 font-black text-2xl group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <?php echo strtoupper($f['faculty_name'][0]); ?>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-800"><?php echo $f['faculty_name']; ?></h3>
                                    <p class="text-xs font-bold text-indigo-500 uppercase">Professor</p>
                                </div>
                            </div>
                            <div class="space-y-3 mb-8">
                                <div class="flex items-center gap-3 text-sm text-slate-500 font-medium">
                                    <i class="fas fa-id-card w-5 text-slate-300"></i> Staff ID: <?php echo $f['faculty_id']; ?>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-slate-500 font-medium">
                                    <i class="fas fa-envelope w-5 text-slate-300"></i> <?php echo $f['faculty_email']; ?>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black uppercase text-slate-400">Status: <?php echo $f['status'] ? 'Available' : 'On Leave'; ?></span>
                                <button class="h-10 w-10 bg-slate-50 rounded-xl text-slate-400 hover:bg-indigo-600 hover:text-white transition-all"><i class="fas fa-ellipsis-h"></i></button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                <?php elseif ($current_page == 'assignments'): ?>
                    <!-- ASSIGNMENTS -->
                    <div class="space-y-8">
                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl font-black text-slate-800">Department Assignments</h2>
                            <button class="bg-slate-800 text-white px-6 py-3 rounded-2xl font-bold text-sm">Create New</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <?php 
                            $assignments = $pdo->query("SELECT a.*, f.faculty_name FROM assignments a LEFT JOIN faculty f ON a.faculty_id = f.id")->fetchAll();
                            foreach($assignments as $task):
                            ?>
                            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
                                <div class="flex justify-between items-start mb-6">
                                    <span class="px-4 py-1.5 bg-orange-100 text-orange-700 rounded-xl text-[10px] font-black uppercase"><?php echo $task['subject']; ?></span>
                                    <span class="text-xs font-black text-red-500"><i class="fas fa-clock mr-2"></i>Due: <?php echo $task['due_date']; ?></span>
                                </div>
                                <h3 class="text-xl font-black text-slate-800 mb-2"><?php echo $task['title']; ?></h3>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-8"><?php echo $task['description']; ?></p>
                                <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 bg-slate-100 rounded-lg flex items-center justify-center text-[10px] font-black text-slate-500">
                                            <?php echo strtoupper($task['faculty_name'][0] ?? 'A'); ?>
                                        </div>
                                        <span class="text-xs font-bold text-slate-400"><?php echo $task['faculty_name'] ?? 'Admin'; ?></span>
                                    </div>
                                    <button class="text-indigo-600 font-black text-xs uppercase tracking-widest hover:underline">Submissions</button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                <?php elseif ($current_page == 'attendance'): ?>
                    <!-- ATTENDANCE -->
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-10">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <!-- Class Attendance -->
                            <div>
                                <h3 class="text-xl font-black text-slate-800 mb-8 border-b border-slate-100 pb-4">Lecture Attendance</h3>
                                <div class="space-y-4">
                                    <?php 
                                    $att = $pdo->query("SELECT a.*, s.student_name FROM attendance a JOIN students s ON a.student_id = s.id ORDER BY a.date DESC LIMIT 10")->fetchAll();
                                    foreach($att as $record):
                                    ?>
                                    <div class="flex items-center justify-between p-5 bg-slate-50 rounded-2xl border border-slate-100">
                                        <div class="flex items-center gap-4">
                                            <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-sm font-black text-slate-400 shadow-sm">
                                                <?php echo date('d', strtotime($record['date'])); ?>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-sm"><?php echo $record['student_name']; ?></p>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase"><?php echo date('F Y', strtotime($record['date'])); ?></p>
                                            </div>
                                        </div>
                                        <span class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase <?php echo $record['status'] == 'Present' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                            <?php echo $record['status']; ?>
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Hostel Attendance -->
                            <div>
                                <h3 class="text-xl font-black text-slate-800 mb-8 border-b border-slate-100 pb-4">Hostel Night Roll-Call</h3>
                                <div class="space-y-4">
                                    <?php 
                                    $hatt = $pdo->query("SELECT ha.*, s.student_name FROM hostel_attendance ha JOIN students s ON ha.student_id = s.id ORDER BY ha.attendance_date DESC LIMIT 10")->fetchAll();
                                    foreach($hatt as $hrecord):
                                    ?>
                                    <div class="flex items-center justify-between p-5 bg-slate-50 rounded-2xl border border-slate-100">
                                        <div class="flex items-center gap-4">
                                            <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-sm font-black text-slate-400 shadow-sm">
                                                <i class="fas fa-bed text-indigo-300"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-sm"><?php echo $hrecord['student_name']; ?></p>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase">Reported: <?php echo $hrecord['attendance_date']; ?></p>
                                            </div>
                                        </div>
                                        <span class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase <?php echo $hrecord['status'] == 'Present' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-200 text-slate-500'; ?>">
                                            <?php echo $hrecord['status']; ?>
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($current_page == 'results'): ?>
                    <!-- RESULTS -->
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-10">
                        <h2 class="text-2xl font-black text-slate-800 mb-8">Examination Results</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            <?php 
                            $results = $pdo->query("SELECT r.*, s.student_name, s.student_id as roll FROM results r JOIN students s ON r.student_id = s.id")->fetchAll();
                            foreach($results as $res):
                            ?>
                            <div class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100 flex flex-col items-center">
                                <div class="h-20 w-20 bg-white rounded-full flex items-center justify-center shadow-lg mb-6 border-4 border-indigo-100">
                                    <span class="text-2xl font-black text-slate-800"><?php echo $res['marks']; ?>%</span>
                                </div>
                                <h4 class="font-extrabold text-slate-800 text-center"><?php echo $res['student_name']; ?></h4>
                                <p class="text-[10px] text-slate-400 font-black uppercase mb-4 tracking-widest">ID: <?php echo $res['roll']; ?></p>
                                <div class="w-full pt-4 border-t border-slate-200 text-center">
                                    <p class="text-xs font-bold text-indigo-500 uppercase"><?php echo $res['subject']; ?></p>
                                    <span class="inline-block mt-2 px-6 py-1 bg-<?php echo $res['status'] == 'Pass' ? 'green' : 'red'; ?>-100 text-<?php echo $res['status'] == 'Pass' ? 'green' : 'red'; ?>-700 rounded-lg text-[10px] font-black uppercase">
                                        <?php echo $res['status']; ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                <?php elseif ($current_page == 'library'): ?>
                    <!-- LIBRARY -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php 
                        $books = $pdo->query("SELECT * FROM books")->fetchAll();
                        foreach($books as $book):
                        ?>
                        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm flex gap-8 hover:shadow-xl transition-all">
                            <div class="h-32 w-24 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-200">
                                <i class="fas fa-book text-4xl"></i>
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] font-black text-indigo-500 uppercase bg-indigo-50 px-3 py-1 rounded-lg"><?php echo $book['category']; ?></span>
                                    <h3 class="text-xl font-black text-slate-800 mt-3"><?php echo $book['title']; ?></h3>
                                    <p class="text-xs font-bold text-slate-400">by <?php echo $book['author']; ?></p>
                                </div>
                                <div class="flex items-center justify-between border-t border-slate-50 pt-4">
                                    <p class="text-lg font-black text-slate-800"><?php echo $book['available']; ?> <span class="text-[10px] text-slate-300">/ <?php echo $book['quantity']; ?></span></p>
                                    <button class="text-xs font-black text-slate-400 hover:text-indigo-600">History</button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                <?php elseif ($current_page == 'nodues'): ?>
                    <!-- NO DUES -->
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-10 border-b border-slate-50">
                            <h2 class="text-2xl font-black text-slate-800">Clearance Management</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-center border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-[10px] font-black uppercase text-slate-400 tracking-widest">
                                        <th class="px-8 py-6 text-left">Student</th>
                                        <th class="px-4 py-6">Accounts</th>
                                        <th class="px-4 py-6">HOD</th>
                                        <th class="px-4 py-6">Library</th>
                                        <th class="px-4 py-6">Hostel</th>
                                        <th class="px-4 py-6">Registrar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php 
                                    $nodues = $pdo->query("SELECT n.*, s.student_name FROM no_dues_requests n JOIN students s ON n.student_id = s.id")->fetchAll();
                                    foreach($nodues as $req):
                                        $cols = [
                                            ['status' => $req['account_status'], 'remark' => $req['account_remark']],
                                            ['status' => $req['hod_status'], 'remark' => $req['hod_remark']],
                                            ['status' => $req['library_status'], 'remark' => $req['library_remark']],
                                            ['status' => $req['hostel_status'], 'remark' => $req['hostel_remark']],
                                            ['status' => $req['registrar_status'], 'remark' => $req['registrar_remark']],
                                        ];
                                    ?>
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-8 py-8 text-left">
                                            <p class="font-extrabold text-slate-800"><?php echo $req['student_name']; ?></p>
                                            <p class="text-[10px] font-black text-slate-400 uppercase">ID: <?php echo $req['student_id']; ?></p>
                                        </td>
                                        <?php foreach($cols as $col): ?>
                                        <td class="px-4 py-8">
                                            <div class="flex flex-col items-center gap-1">
                                                <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase <?php 
                                                    echo $col['status'] == 'Approved' ? 'bg-green-100 text-green-700' : ($col['status'] == 'Rejected' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-400'); 
                                                ?>">
                                                    <?php echo $col['status']; ?>
                                                </span>
                                                <?php if(!empty($col['remark'])): ?>
                                                    <p class="text-[8px] font-bold text-slate-400 max-w-[80px] truncate" title="<?php echo $col['remark']; ?>"><?php echo $col['remark']; ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php endif; ?>

            </section>
        </main>

    </div>
<?php endif; ?>

</body>
</html>