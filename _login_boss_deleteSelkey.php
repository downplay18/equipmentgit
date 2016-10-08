<?php

session_start();
require_once 'connection.php';

include 'root_url.php';
print_r($_POST);

$escapeName = $_SESSION['name'];
$deleteQS = "DELETE FROM `user_config` WHERE `cname`='$escapeName'";
$deleteQry = mysqli_query($connection, $deleteQS) or die("DELETE FROM fail: " . mysqli_error($connection));
header("Location: $root_url/_login_check.php", true, 302);
?>