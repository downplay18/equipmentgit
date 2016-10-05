<?php

// Create connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "equipment";

//$mysqli->set_charset("utf8");

$connection = mysqli_connect($servername, $username, $password, $dbname) 
        or die("connection.php could not connect to database: ไม่สามารถเชื่อมต่อฐานข้อมูลได้");


/*
  mysqli_close($connection) or die("Unable to close connection.");
  echo "<br/><br/>";
  echo "connection closed";
  echo "<br/><br/>";
 */
?>