<?php
include("db.php");

if(isset($_POST['type'], $_POST['id'], $_POST['status'])){
    $type = $_POST['type'];
    $id = (int)$_POST['id'];
    $status = (int)$_POST['status'];

    $table = ($type === 'student') ? 'students' : 'faculty';

    $stmt = $conn->prepare("UPDATE $table SET status=? WHERE id=?");
    $stmt->bind_param("ii", $status, $id);

    if($stmt->execute()){
        echo ($status == 1) ? "Account Activated" : "Account Deactivated";
    } else {
        echo "Failed to update status";
    }

} else {
    echo "Invalid request";
}
?>
