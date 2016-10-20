<?php

//var_dump($_SESSION);
session_start();
require_once 'connection.php';

include 'root_url.php';
if ($_SESSION['user_id'] == "") {
    header("Location: $root_url/index.php", true, 302);
    exit();
}
if ($_SESSION['status'] != "KEY") {
    header("Location: $root_url/index.php", true, 302);
}
?>

<?php

/*
  echo '<pre>';
  echo 'SESSION = ';
  print_r($_SESSION);
  echo '<br/>POST = <br/>';
  print_r($_POST);
  echo "</pre>"; */
?>


<?php

/* นับจำนวนแถวว่าต้องทำกี่แถว เพราะไม่รู้ว่า user จะเพิ่มเข้ามากี่แถว */
/* ในกรณีที่ป้อนแบบเว้นบรรทัด ใช้ required บังคับให้userลบแถวว่างออก */
$row_count = count($_POST['varDetail']);

//สร้าง Query Statement ของแต่ละitem
$_SESSION['msg'] = array();
for ($rc = 0; $rc < $row_count; $rc++) {
    $addUrgRecQS = "INSERT INTO `item_urgent_record` (`urg_detail`,`urg_suffix`,`urg_qty`,`urg_unitPrice`,`urg_amount`,`urg_subTotal`,`urg_slipDate`,`urg_addDateTime`,`urg_adder`,`urg_purpose`)";
    $addUrgRecQS .= " VALUES (";
    $addUrgRecQS .= "'" . $_POST['varDetail'][$rc] . "'"; //detail
    $addUrgRecQS .= ",'" . $_POST['var_slipSuffix'][$rc] . "'"; //suffix
    $addUrgRecQS .= ",'" . $_POST['var_qty'][$rc] . "'"; //qty
    $addUrgRecQS .= ",'" . $_POST['var_unitPrice'][$rc] . "'"; //unit_price
    $addUrgRecQS .= ",'" . $_POST['var_amount'][$rc] . "'"; //amount
    $addUrgRecQS .= ",'" . $_POST['var_subTotal'][$rc] . "'"; //sub_total
    $addUrgRecQS .= ",'" . $_POST['var_slipDate'] . "'"; //slipDate
    //$timezone = date_default_timezone_get();
    //echo "timezone=".$timezone;
    date_default_timezone_set("Asia/Bangkok"); //set default timezone
    $addUrgRecQS .= ",'" . date('Y-m-d H:i') . "'"; //addDateTime
    $addUrgRecQS .= ",'" . $_POST['var_adder'] . "'"; //adder
    $addUrgRecQS .= ",'" . $_POST['var_purpose'] . "'"; //adder
    $addUrgRecQS .= ");";

    //print_r($addUrgRecQS);
    //
    //Query
    $addUrgRecQry = mysqli_query($connection, $addUrgRecQS) or die("คิวรี่ครั้งที่ $rc ล้มเหลว!: " . $mysqli_error($connection));

    //เก็บเป็น Alert ให้userดูอีกที
    $userAlert = $rc + 1;
    if ($addUrgRecQry) {
        array_push($_SESSION['msg'], "Query#$userAlert-เพิ่มรายการที่ $userAlert ...สำเร็จ!");
        if ($row_count-1 == $rc) {
            array_push($_SESSION['msg'], "- เพิ่ม $userAlert รายการเสร็จสิ้น -");
        }
    }
}

header("Location: $root_url/add_urgent.php", true, 302);
?>
 


