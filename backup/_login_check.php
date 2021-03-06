<?php
$lifetime = '30600'; /* หน่วยเป็นวินาที ดังนั้น 3600 = 1 ชั่วโมง ดังนั้น 30600 = 8 ชม. 30 นาที */
session_set_cookie_params($lifetime, "/"); /* ตั้งเวลาให้ session cookie */
session_start();


/* =====_login_connection===== */
/* รับ $_POST มาจาก navbar_unauthen.php */
/* $_POST ของ Username = login_cid */
/* $_POST ของ Password = login_pwd */
require("connection.php");
$strSQL = "SELECT `user_id`,`status`,`name` FROM user WHERE user_id = '" . mysql_real_escape_string($_POST["login_cid"]) . "'
	and password = '" . mysql_real_escape_string($_POST["login_pwd"]) . "'"; /* ไม่ SELECT `Password` เพราะไม่ได้แสดงค่ามัน */
$loginQuery = mysqli_query($connection, $strSQL) or die("_login_check.php คิวรี่ล้มเหลว!");
$loginResult = mysqli_fetch_array($loginQuery); /* ได้ loginResult เป็นผลลัพธ์ของการ query */
/* =====/_login_connection===== */

/* เช็คว่า Username กับ Password ที่ป้อนเข้ามา มีใน db หรือไม่ */
if (!$loginResult) {
    echo "รหัสพนักงาน หรือ รหัสผ่าน ไม่ถูกต้อง!";
} else {
    $_SESSION["user_id"] = $loginResult["user_id"]; /* user_id เป็น PRIMARY KEY ของ `user`*/
    $_SESSION["status"] = $loginResult["status"];
    $_SESSION["name"] = $loginResult["name"];
    
    session_write_close();

    if ($loginResult["status"] == "ADMIN") {
        header("location:_login_admin.php");
    } else {
        header("location:_login_user.php");
    }

    /*
      switch ($loginResult["Status"]) {
      case 'ADMIN':
      header("location:_login_admin.php");
      break;
      case 'BOSS':
      header("location:_login_boss.php");
      break;
      case 'USER':
      header("location:_login_user.php");
      break;
      }
     */
}
?>
