<?php
// start session
session_start();
 
// Unset session
$_SESSION = array();
 
// Destroy session.
session_destroy();
 
// login page redirect
header("location: login.php");
exit;
?>