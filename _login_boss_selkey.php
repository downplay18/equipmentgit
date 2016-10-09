<?php

session_start();
require_once 'connection.php';

include 'root_url.php';
print_r($_POST);


if ($_POST['boss_selkey'] == '-- เลือกผู้ดูแลที่นี่ --') { //ไม่เลือกแต่กดตกลง
    header("Location: $root_url/_login_check.php", true, 302);
} else { //เลือก > แก้ค่าใน table:user_config และ table:user
    $escapeName = $_SESSION['name'];
    $escapeSelkey = $_POST['boss_selkey'];

    //เพิ่มค่าใน table:config
    $selkeyQS = "INSERT INTO `user_config` (`cname`,`mykey`) VALUES ('$escapeName','$escapeSelkey') ON DUPLICATE KEY UPDATE `mykey`='$escapeSelkey';";
    $selkeyQry = mysqli_query($connection, $selkeyQS) or die("INSERT INTO ล้มเหลว: " . mysqli_error($connection));

    //แก้ status ใน table:user
    $statusQS = "UPDATE `user` SET `status`='KEY' WHERE `name`='" . $escapeSelkey . "';";
    $statusQry = mysqli_query($connection, $statusQS) or die("UPDATE status ล้มเหลว: " . mysqli_error($connection));

    //บันทึกลงใน edit_record
    date_default_timezone_set("Asia/Bangkok");
    $recordQS = "INSERT INTO `item_edit_record` (`edit_date`,`edit_time`,`editor`,`note`)";
    $recordQS .= " VALUES ('".date('Y-m-d')."','". date("H:i") ."','$escapeName','ตั้งผู้KEYประจำกลุ่มงาน เป็น $escapeSelkey');"  ;
    echo $recordQS;
    $recordQry = mysqli_query($connection,$recordQS) or die ("INSERT record ล้มเหลว: ".mysqli_error($connection));

    header("Location: $root_url/_login_check.php", true, 302);
}
?>