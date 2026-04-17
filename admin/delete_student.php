<?php
include("db.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare statement for security
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
            alert('✅ Student deleted successfully!');
            window.location='welcome.php?page=students';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('❌ Error deleting student: ".$conn->error."');
            window.history.back();
        </script>";
        exit();
    }
} else {
    echo "<script>
        alert('⚠️ Invalid student ID!');
        window.history.back();
    </script>";
    exit();
}
?>
