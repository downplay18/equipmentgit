<?php

session_start();
require_once 'connection.php';

include 'root_url.php';
print_r($_POST);


if ($_POST['boss_selkey'] == '-- เลือกผู้ดูแลที่นี่ --') {
    header("Location: $root_url/_login_check.php", true, 302);
} else {
    $escapeName = $_SESSION['name'];
    $escapeSelkey = $_POST['boss_selkey'];
    
    $selkeyQS = "INSERT INTO `user_config` (`cname`,`mykey`) VALUES ('$escapeName','$escapeSelkey')";
    $selkeyQry = mysqli_query($connection,$selkeyQS) or die("INSERT INTO ล้มเหลว: ".mysqli_error($connection));
    header("Location: $root_url/_login_check.php", true, 302);
}
?>