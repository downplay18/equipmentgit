<?php

session_start();
require_once 'connection.php';

include 'root_url.php';
print_r($_POST);
print_r($_GET);

$escapeName = $_SESSION['name'];
$escapeMykey = $_GET['getmykey'];

//แก้ช่อง `mykey` ให้ว่าง
$deleteQS = "UPDATE `user_config` SET `mykey`='' WHERE `cname`='$escapeName'";
$deleteQry = mysqli_query($connection, $deleteQS) or die("DELETE FROM fail: " . mysqli_error($connection));

//แก้ status จาก KEY >เป็น> USER
$statusQS = "UPDATE `user` SET `status`='USER' WHERE `name`='$escapeMykey'";
$statusQry = mysqli_query($connection,$statusQS);

header("Location: $root_url/_login_check.php", true, 302);
?>