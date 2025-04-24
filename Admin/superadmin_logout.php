<?php
session_start();
session_destroy();
header("Location: superadmin_login.php");
exit();
?>