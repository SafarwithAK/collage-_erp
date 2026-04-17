<?php
session_start();
session_destroy();
header("Location: library_login.php");
exit();
?>
