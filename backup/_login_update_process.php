<?php

session_start();
if ($_SESSION['CustomerID'] == "") {
    echo "โปรดยืนยันตัวตนก่อน !";
    exit();
}
/*
  var_dump($_SESSION);
  echo "<br>"
 */
?>

<?php

/* การเรียกใช้ array([0][0], [0][1], [0][2]) */
$customer_header = array(
    //array('เลขพนักงาน', 'CustomerID', ''), /*แก้ไขเองไม่ได้*/
    array('ชื่อพนักงาน', 'fname', 'lupdate_fname'),
    array('รหัสผ่าน', 'Password', 'lupdate_pwd'),
    array('ตำแหน่ง', 'Position', 'lupdate_pos'),
    array('ที่อยู่', 'address', 'lupdate_addr'),
    array('ห้อง', 'class', 'lupdate_class'),
    array('เบอร์โทรศัพท์โต๊ะทำงาน', 'tel', 'lupdate_tel'),
        //array('สถานะ', 'Status', ''), /*แก้ไขเองไม่ได้*/
);
$ch_count = count($customer_header);

/* algorithm การสร้าง query statement */
$update_statement = "UPDATE customer SET "; /* ประโยค query statement ตั้งต้น */
$check_empstr = 0; /* ตัวแปรนับจำนวน $_POST เพื่อเติ่ม , คั่นระหว่างข้อความให้ถูกต้อง */
for ($i = 0; $i < $ch_count; $i++) {
    /* ดูเฉพาะช่อง $_POST ที่ผู้ใช้แก้ค่า */
    if ($_POST[$customer_header[$i][2]] != "") {
        //echo "case " . $customer_header[$i][1] . "<br>";
        if ($check_empstr == 0) {
            //echo $check_empstr . 'case if <br>';
            $check_empstr++;
            $update_statement .= $customer_header[$i][1] . "='" . mysql_real_escape_string($_POST[$customer_header[$i][2]]) . "'";
        } else {
            //echo $check_empstr . 'case else<br>';
            $update_statement .= "," . $customer_header[$i][1] . "='" . mysql_real_escape_string($_POST[$customer_header[$i][2]]) . "'";
        }
    }
}

$update_url = "http://localhost:81/equipment1php/_login_update.php";
/* เคสที่ผู้ใช้ไม่ได้แก้ค่าแม้แต่ช่องเดียว แต่กดปุ่ม submit */
if ($check_empstr == 0) {
    header("location: $update_url"); /* redirect มาหน้าเดิม ใน tab เดิม */
    exit(0);
}

$update_statement .= " WHERE CustomerID='" . $_SESSION['CustomerID'] . "'";
echo $update_statement . '<br/>';

include 'connection.php';
$updateQuery = mysqli_query($connection, $update_statement) or die("_login_update_process.php query ล้มเหลว!");
//$updateResult = mysqli_fetch_array($updateQuery);

/* เมื่อทำการ update query เรียบร้อย redirect กลับหน้าเดิม */
header("location: $update_url");
?>
