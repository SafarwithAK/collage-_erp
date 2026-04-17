<?php
session_start();
include("db.php");

// User not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);

// If form submitted → Update notice
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title   = $conn->real_escape_string($_POST['title']);
    $message = $conn->real_escape_string($_POST['message']);
    $content = $conn->real_escape_string($_POST['content']);

    $sql = "UPDATE notices 
            SET title='$title', message='$message', content='$content'
            WHERE id=$id";

    if ($conn->query($sql)) {
        echo "<script>
                alert('Notice updated successfully!');
                window.location.href='welcome.php#notices';
              </script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch existing notice
$result = $conn->query("SELECT * FROM notices WHERE id=$id");
$notice = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Notice</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #8e44ad, #3498db);
    padding: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.edit-container {
    background: #fff;
    padding: 30px;
    border-radius: 14px;
    max-width: 550px;
    width: 100%;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

h2 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 25px;
}

form { display: flex; flex-direction: column; gap: 15px; }

input[type="text"], textarea {
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    width: 100%;
}

textarea { min-height: 120px; resize: vertical; }

input[type="submit"] {
    background: linear-gradient(90deg,#6c5ce7,#a29bfe);
    border: none;
    padding: 12px;
    color: white;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: .3s;
}

input[type="submit"]:hover {
    background: linear-gradient(90deg,#4e54c8,#8f94fb);
}
</style>
</head>

<body>

<div class="edit-container">
    <h2>✏ Edit Notice</h2>

    <form method="POST">
        <!-- Title -->
        <input type="text" 
               name="title" 
               value="<?php echo htmlspecialchars($notice['title']); ?>" 
               placeholder="Notice Title" required>

        <!-- Message -->
        <textarea name="message" placeholder="Short Message" required><?php 
            echo htmlspecialchars($notice['message']); 
        ?></textarea>

        <!-- Full Content -->
        <textarea name="content" placeholder="Full Notice Content (optional)"><?php 
            echo htmlspecialchars($notice['content']); 
        ?></textarea>

        <input type="submit" value="Update Notice">
    </form>
</div>

</body>
</html>
