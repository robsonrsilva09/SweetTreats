<?php
session_start();

$_SESSION = array();

session_destroy();

header("Location: admin_login.php");
exit(); // No further code is executed.
?>
