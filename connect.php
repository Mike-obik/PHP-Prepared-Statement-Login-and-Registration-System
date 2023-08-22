<?php
// declare to database varables
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'sample_web_project2');
 
// connection string
$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// validate connection
if($connect === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>