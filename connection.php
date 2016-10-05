<?php

// Create connection
$servername = "localhost";
$username = "root"; 
$password = ""; //simulator
$dbname = "equipment";

//$mysqli->set_charset("utf8");

$connection = mysqli_connect($servername, $username, $password, $dbname) 
        or die("connection.php ไม่สามารถเชื่อมต่อฐานข้อมูลได้");

?>