<?php 
// Hostname
$host = "localhost";
// Username
$uname = "root";
// Password
$pw = "123";
// Database Name
$dbname = "simple_attendance_db";

try{
    $conn = new MySQLi($host, $uname, $pw, $dbname);
    $conn->set_charset("utf8mb4");
}catch(Exception $e){
    echo "Database Connection Failed: <br>";
    print_r($e->getMessage());
    exit;
}
?>