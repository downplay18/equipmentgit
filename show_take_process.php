<?php
//var_dump($_SESSION);
session_start();
//error_reporting(0);
require_once 'connection.php';
include 'root_url.php';

echo 'SESSION = ';
print_r($_SESSION);
echo '<br/>POST = <br/>';
print_r($_POST);
?>

<?php /*
  if(isset($_POST['takeSubmit'])) {
  echo 'takSubmit OK';
  } else {
  echo 'takSubmit fucking failed';
  } */
?>

<?php

//ดึง จาก table:item
$itemQS = "SELECT `iid`,`detail`,`quantity`,`suffix`,`owner` FROM `item` WHERE `owner` LIKE '" . $_SESSION['division'] . "'"
        . " AND `detail` LIKE '" . $_SESSION['detail'] . "'";
$itemQry = mysqli_query($connection, $itemQS) or die("itemQry failed: " . mysqli_error($connection));
$itemResult = mysqli_fetch_assoc($itemQry);

//`user_config` >> อัปเดต `favworker`
$userCfgUpdQS = "INSERT INTO `user_config` (`cname`,`favworker`) VALUES ('" . $_SESSION['name'] . "','" . $_POST['worker'] . "')"
        . " ON DUPLICATE KEY UPDATE `favworker`='" . $_POST['worker'] . "'";
$userCfgUpdQry = mysqli_query($connection, $userCfgUpdQS) or die("userCfgUpdQry failed: " . mysqli_error($connection));

//เพิ่มรายการใน `item_take_record`
date_default_timezone_set("Asia/Bangkok");
$itemTakeRecordQS = "INSERT INTO `item_take_record` (`take_detail`,`take_qty`,`take_suffix`,`take_date`,`take_time`,`taker`,`worker`,`site`)"
        . " VALUES ('" . $_SESSION['detail'] . "','" . $_POST['qty'] . "','" . $_SESSION['suffix'] . "'"
        . ",'" . date('Y-m-d') . "','" . date('H:i:s') . "'"
        . ",'" . $_SESSION['division'] . "','" . $_POST['worker'] . "','" . $_POST['site'] . "')";
$itemTakeRecordQry = mysqli_query($connection, $itemTakeRecordQS) or die("itemTakeRecordQry failed: " . mysqli_error($connection));

//หักของออกจาก `item`
$itemTakeQS = "INSERT INTO `item` (`iid`,`quantity`) VALUES ('". $itemResult['iid'] ."','" . $itemResult['quantity'] . "') ON DUPLICATE KEY UPDATE `quantity`=`quantity`-" . $_POST['qty'] . ";";
$itemTakeQry = mysqli_query($connection, $itemTakeQS) or die("itemTakeQS failed: " . mysqli_error($connection));


/*
  echo "<br>userCfgUpdQS=<br>";
  print_r($userCfgUpdQS);
  echo "<br>itemTakeRecordQS=<br>";
  print_r($itemTakeRecordQS);
  echo "<br>itemTakeQS=<br>";
  print_r($itemTakeQS); */
 



header("Location: $root_url/show_item.php", true, 302);
?>