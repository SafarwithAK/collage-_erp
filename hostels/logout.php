<?php
session_start();
session_unset();
session_destroy();

header("Location: hostel_login.php");
exit();
