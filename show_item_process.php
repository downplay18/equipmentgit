<?php
//var_dump($_SESSION);
session_start();
require_once 'connection.php';

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
$itemQS = "SELECT `detail`,`quantity`,`suffix`,`owner` FROM `item` WHERE `owner` LIKE '" . $_SESSION['division'] . "'"
        . " AND `detail` LIKE '" . $_GET['detail'] . "'";
$itemQry = mysqli_query($connection, $itemQS) or die("itemQry failed: " . mysqli_error($connection));
$itemResult = mysqli_fetch_assoc($itemQry);

//เช็ค form submit
if (isset($_POST['takeSubmit'])) {
    //`user_config` >> อัปเดต `favworker`
    $userCfgUpdQS = "INSERT INTO `user_config` (`cname`,`favworker`) VALUES ('" . $_SESSION['name'] . "','" . $_POST['worker'] . "')"
            . " ON DUPLICATE KEY UPDATE `favworker`='" . $_POST['worker'] . "'";
    $userCfgUpdQry = mysqli_query($connection, $userCfgUpdQS) or die("userCfgUpdQry failed: " . mysqli_error($connection));

    //เพิ่มรายการใน `item_take_record`
    date_default_timezone_set("Asia/Bangkok");
    $itemTakeRecordQS = "INSERT INTO `item_take_record` (`take_detail`,`take_qty`,`take_suffix`,`take_date`,`take_time`,`taker`,`worker`,`site`)"
            . " VALUES ('" . $_GET['detail'] . "','" . $_POST['qty'] . "','" . $_GET['suffix'] . "'"
            . ",'" . date('Y-m-d') . "','" . date('H:i:s') . "'"
            . ",'" . $_SESSION['division'] . "','" . $_POST['worker'] . "','" . $_POST['site'] . "')";
    $itemTakeRecordQry = mysqli_query($connection, $itemTakeRecordQS) or die("itemTakeRecordQry failed: " . mysqli_error($connection));

    //หักของออกจาก `item`
    $itemTakeQS = "INSERT INTO `item` (`detail`,`quantity`,`suffix`) VALUES ('" . $itemResult['detail'] . "','" . $itemResult['quantity'] . "','" . $itemResult['suffix'] . "') ON DUPLICATE KEY UPDATE `quantity`=`quantity`-" . $_POST['qty'] . ";";
    $itemQry = mysqli_query($connection, $itemTakeQS) or die("itemTakeQS failed: ".mysqli_error($connection));
    
    //เสร็จสิ้นทุกกระบวนการไม่มี error
    $message = "Success! You entered: " . $_POST['qty'] . $_POST['worker'] . $_POST['site'];
    
    /*
    echo "<br>userCfgUpdQS=<br>";
    print_r($userCfgUpdQS);
    echo "<br>itemTakeRecordQS=<br>";
    print_r($itemTakeRecordQS);
    echo "<br>itemTakeQS=<br>";
    print_r($itemTakeQS);
    */ 
    
    //header("Location: $root_url/index.php", true, 302);
}
?>