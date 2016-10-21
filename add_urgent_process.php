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

$_SESSION['msg'] = array();
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

// เช็คไฟล์ก่อน
//ตั้งfolder สำหรับเก็บไฟล์ที่อัปมา
$target_dir = "slip/";

//สร้างfullpath โดย basenameคือแสดงชื่อไฟล์แบบมีนามสกุลด้วย
date_default_timezone_set("Asia/Bangkok"); //set default timezone
//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $_SESSION['user_id']."_".date('Y-m-d')."_".date('His') .".". pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);;

//ตั้งเป็นdefaultว่าokไว้ก่อน ถ้าเช็คตามเคสแล้วfalse จะโดนเปลี่ยนเป็น 0
$uploadOk = 1;

//เก็บนามสกุลไฟล์(extension)แบบไม่มีจุดนำหน้า
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// โค้ดเก่าสำหรับเช็คว่าเป็นรูปภาพหรือเปล่าเท่านั้น
// Check if image file is a actual image or fake image
/*
  if (isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if ($check !== false) {
  echo "File is an image - " . $check["mime"] . ".";
  $uploadOk = 1;
  } else {
  echo "File is not an image.";
  $uploadOk = 0;
  }
  } */

// เช็คไฟล์ซํ้า
if (file_exists($target_file)) {
    echo "มีไฟล์ชื่อซํ้ากันอยู่แล้ว<br/>";
    $uploadOk = 0;
} else {
    echo "ชื่อไฟล์ไม่ซํ้า ...OK!<br/>";
}

// Check file size
$maxFileSize = 10485760; //10485760=10MiB
if ($_FILES["fileToUpload"]["size"] > $maxFileSize) {
    echo "ไม่สามารถอัปโหลดไฟล์ที่มีขนาดเกิน&nbsp;" . $maxFileSize / 1048576 . " MB ได้<br/>";
    $uploadOk = 0;
} else {
    echo "ขนาดไฟล์ไม่เกิน" . $maxFileSize / 1048576 . " MB ...OK!<br/>";
}

// เช็คนามสกุลไฟล์
$allowedExts = array("pdf", "doc", "docx", "jpg", "jpeg", "png", "gif");
if ($imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
    echo "นามสกุลไฟล์ไม่ถูกต้อง กรุณาอัปโหลด pdf, doc, docx, jpg, jpeg, png, gif เท่านั้น<br/>";
    $uploadOk = 0;
} else {
    echo "นามสกุลไฟล์ถูกต้อง ...OK!<br/>";
}

// โค้ดย้ายไฟล์เข้าserver อยู่ข้างล่างการ query
?>

<?php

/* นับจำนวนแถวว่าต้องทำกี่แถว เพราะไม่รู้ว่า user จะเพิ่มเข้ามากี่แถว */
/* ในกรณีที่ป้อนแบบเว้นบรรทัด ใช้ required บังคับให้userลบแถวว่างออก */
$row_count = count($_POST['varDetail']);

//สร้าง Query Statement ของแต่ละitem
for ($rc = 0; $rc < $row_count; $rc++) {
    $addUrgRecQS = "INSERT INTO `item_urgent_record` (`urg_detail`,`urg_suffix`,`urg_qty`,`urg_unitPrice`,`urg_amount`,`urg_subTotal`,`urg_slipDate`,`urg_addDateTime`,`urg_adder`,`urg_purpose`,`urg_slip`)";
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
    $addUrgRecQS .= ",'" . $target_file . "'"; //slip
    $addUrgRecQS .= ");";

    //print_r($addUrgRecQS);
    //
    //Query
    $addUrgRecQry = mysqli_query($connection, $addUrgRecQS) or die("คิวรี่ครั้งที่ $rc ล้มเหลว!: " . $mysqli_error($connection));

    //เก็บเป็น Alert ให้userดูอีกที
    $userAlert = $rc + 1;
    if ($addUrgRecQry) {
        array_push($_SESSION['msg'], "Query#$userAlert-เพิ่มรายการที่ $userAlert ...สำเร็จ!");
    }
}

// เช็ค $uploadOK ไม่มี error
if ($uploadOk == 0) {
    echo "ไฟล์ไม่ถูกอัปโหลด";

// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "อัปโหลดไฟล์ " . basename($_FILES["fileToUpload"]["name"]) . " เสร็จสมบูรณ์!";
        array_push($_SESSION['msg'], "อัปโหลดไฟล์ ...OK!");
        array_push($_SESSION['msg'], "- เพิ่ม $userAlert รายการเสร็จสิ้น -");
        array_push($_SESSION['msg'], "<a href=\"$target_file\" target=\"_blank\">คลิกที่นี่เพื่อตรวจสอบไฟล์</a>");
    } else {
        echo "ไม่สามารถอัปโหลดไฟล์ได้ (Status(" . $uploadOk . "))";
    }
}

header("Location: $root_url/add_urgent.php", true, 302);
?>
 


