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

$_SESSION['addMsg'] = array();
?>

<html>

    <head>
        <?php include 'main_head.php'; ?>  
    </head>

    <?php
    include("navbar.php");

    echo '<br/>';
    echo 'SESSION = ';
    print_r($_SESSION);
    echo '<br/>POST = <br/>';
    print_r($_POST);
    ?>






    <?php
// เช็คไฟล์ก่อน
//ตั้งfolder สำหรับเก็บไฟล์ที่อัปมา
    $target_dir = "slip/";

//ตั้งเป็นdefaultว่าokไว้ก่อน ถ้าเช็คตามเคสแล้วfalse จะโดนเปลี่ยนเป็น 0
    $uploadOk = 1;

//เช็คว่าได้เลือกไฟล์ไหม
    if (empty($_FILES['fileToUpload']['name'])) {
        echo "<br/>ไม่ได้เลือกไฟล์เพื่ออัปโหลด";
        array_push($_SESSION['addMsg'], "ไม่พบไฟล์สลิป...");
        $uploadOk = 0;
    } else { //กรณีมีไฟล์ถูกอัปโหลด ให้มีไฟล์ก่อนถึงค่อยสร้าง full path ไม่งั้นถึงไม่มีไฟล์ก็สร้าง จำทำให้มีแต่ชื่อไม่มีไฟล์
        //สร้างfullpath โดย basenameคือแสดงชื่อไฟล์แบบมีนามสกุลด้วย
        date_default_timezone_set("Asia/Bangkok"); //set default timezone
        $target_file = $target_dir . "n" . $_SESSION['user_id'] . "_" . date('Y-m-d') . "_" . date('His') . "." . pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
    }

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
    /* ในกรณีที่ป้อนแบบเว้นบรรทัด ใช้ array_filter ช่วยในการตัดบรรทัดที่ไม่ได้ป้อน */
    /* ใช้ array_values ช่วยให้มันเรียง index ใหม่ จาก 0 ถึงตัวหลังสุด */
    /* ###### ใช้ได้ในกรณีที่userกรอกทุกบรรทัดติดกัน เท่านั้น ถ้าป้อนแบบเว้นบรรทัดจะขึ้น warning ทันที */
    $row_count = count($_POST['varDetail']);




    /* บังคับให้ "ชื่อผู้เพิ่มรายการ" เป็นชื่อคนที่ล็อกอินในขณะนั้น */
    //$_POST['var_adder'] = $_SESSION['name'];
    //filter array เพื่อทำให้แถวที่เป็น empty string ถูกทำให้ null โดยไม่เสียตำแหน่งที่ถูกต้องของ index
    $postZDIR = array_filter($_POST['var_zdir']);
    $postDetail = array_filter($_POST['varDetail']);
    $postSlipSuffix = array_filter($_POST['var_slipSuffix']);
    $postQty = array_filter($_POST['var_qty']);
    $postUnitPrice = array_filter($_POST['var_unitPrice']);
    $postAmount = array_filter($_POST['var_amount']);
    $postLastSuffix = array_filter($_POST['var_lastSuffix']);
    $postLastQty = array_filter($_POST['var_lastQty']);


    //เช็ค `item` ว่ามีชื่อเดียวกันแต่ต่างกลุ่มงานอยู่หรือไม่
    $itemCheckQS = "SELECT `detail`,`owner` FROM `item`"; //ได้เคส itemซํ้า แต่ กลุ่มงานต่าง
    $itemCheckQry = mysqli_query($connection, $itemCheckQS);
    $itemDuplicate = "";
    $addNormal = true;
    for ($i = 0; $i < $row_count; $i++) {
        while ($rowItemCheck = mysqli_fetch_assoc($itemCheckQry)) {
            echo '<br>rowItemCheck=';
            print_r($rowItemCheck);
            if ($rowItemCheck['detail'] == $_POST['varDetail'][$i]) {
                echo '<br>ENTER big if';
                if ($rowItemCheck['owner'] != $_SESSION['division']) {
                    //$itemDuplicate .= $rowItemCheck['detail'];
                    $addNormal = false;
                } else {
                    $addNormal = true;
                    break; // <-เจออันที่addปกติแล้วไม่ต้องเช็คต่อ รีบออกเลย
                }
            }
        }
    }

    echo '<br>itemduplicate=';
    print_r($itemDuplicate);
    echo '<br>addNormal=';
    print_r($addNormal);

    //การสร้างประโยค query
    $addItemStatement = ""; /* บันทึกลงใน table: item_slip */
    $item_add_record_statement = ""; /* บันทึก record ของ table: item_add_record */
    $item_statement = "";
    // for ของ $row_count (aka. $rc) 
    include 'item_headerInfo.php';
    for ($rc = 0; $rc < $row_count; $rc++) { /* 1 $rc คือ 1 แถวของรายการใน 1 ใบเสร็จ */
        if ($_POST['varDetail'][$rc] != "") { /* เช็คที่ detail เพราะมันเป็น primary key */
            $addItemStatement .= "INSERT INTO `item_slip` (`zpo`,`zdir`,`slip_date`,`detail`,`slip_suffix`,`qty`,`unit_price`,`amount`,`sub_total`,`grand_total`,`adder`,`slip`)";
            $item_add_record_statement .= "INSERT INTO `item_add_record` (`add_detail`,`add_suffix`,`add_qty`,`add_date`,`add_time`,`adder`)";
            $item_statement .= "INSERT INTO `item` (`detail`,`suffix`,`quantity`,`owner`)";

            /* สร้างประโยคหลัง INSERT INTO `item_slip` 
              for ($i = 1; $i < $item_size; $i++) {
              if ($_POST[$item_headerInfo[$i][2]] != "") {
              $addItemStatement .= ",`" . $item_headerInfo[$i][1] . "`";
              }
              } */

            //ใส่ใน TABLE: item_slip
            $addItemStatement .= " VALUES ('" . $_POST['var_zpo'] . "'"; /* zpo */
            $addItemStatement .= ",'" . $postZDIR[$rc] . "'"; /* zdir[] */
            $addItemStatement .= ",'" . $_POST['var_slipDate'] . "'"; /* slip_date */
            $addItemStatement .= ",'" . $postDetail[$rc] . "'"; /* detail[] */
            $addItemStatement .= ",'" . $postSlipSuffix[$rc] . "'"; /* slip_suffix[] */
            $addItemStatement .= ",'" . $postQty[$rc] . "'"; /* qty[] */
            $addItemStatement .= ",'" . $postUnitPrice[$rc] . "'"; /* unit_price[] */
            $addItemStatement .= ",'" . $postAmount[$rc] . "'"; /* amount[] */
            $addItemStatement .= ",'" . $_POST['var_subTotal'] . "'"; /* sub_total */
            $addItemStatement .= ",'" . $_POST['var_grandTotal'] . "'"; /* grand_total */
            $addItemStatement .= ",'" . $_POST['var_adder'] . "'"; /* adder */
            $addItemStatement .= ",'" . $target_file . "'"; //slip
            $addItemStatement .= ");";

            //ใส่ใน TABLE: item_add_record
            $item_add_record_statement .= " VALUES ('" . $postDetail[$rc] . "'";
            $item_add_record_statement .= ",'" . $postLastSuffix[$rc] . "'";
            $qtysum = ($_POST['var_qty'][$rc] * $postLastSuffix[$rc]); //ผลคูณของการแปลง slip suffix เป็น item suffix

            $item_add_record_statement .= ",'" . ($_POST['var_qty'][$rc] * $postLastQty[$rc]) . "'";
            date_default_timezone_set("Asia/Bangkok");
            $item_add_record_statement .= ",'" . date('Y-m-d') . "'"; /* ต้องใช้วันที่ปัจจุบัน */
            $item_add_record_statement .= ",'" . date("H:i") . "'"; /* ต้องใช้เวลาปัจจุบัน */
            $item_add_record_statement .= ",'" . $_POST['var_adder'] . "'";
            $item_add_record_statement .= ");";

            /* ปัญหาในกรณีที่ `detail` เดียวกัน แต่คนละกลุ่มงาน จะไป update ซํ้า 
             * SOLUTION: query เอา item มาเช็คก่อน แล้วค่อย insert into    
             *  */


            //ใส่ใน TABLE: item
            $item_statement .= " VALUES ('" . $postDetail[$rc] . "'";
            $item_statement .= ",'" . $postLastSuffix[$rc] . "'";
            $item_statement .= ",'" . ($_POST["var_qty"][$rc] * $postLastQty[$rc]) . "'";
            $item_statement .= ",'" . $_POST['var_adder'] . "'";
            if ($addNormal == true) {
                $item_statement .= ") ON DUPLICATE KEY UPDATE `quantity`=`quantity`+" . ($_POST['var_qty'][$rc] * $postLastQty[$rc]) . ";";
            } else {
                $item_statement .= ");";
            }
        }
    }

    /* debug section */
    //echo "<br/></br>addItemStatement= " . $addItemStatement;
    //echo "<br/></br>additem= " . $addItemStatement;
    //echo "<br/></br>additem__RECORD= " . $item_add_record_statement;
    //echo "<br/><br/>";


    /* คิวรี่รวดเดียว เพราะถ้า error จะได้หยุดทั้งหมด (จริงเหรอ)จากที่ทดสอบ พบว่าคิวรี่1-2-3 ถ้าerror2 จะทำให้3ไม่ทำงานจริง แต่1ก็ไม่rollbackให้  */
    $fullStatement = "START TRANSACTION;";
    $fullStatement .= $addItemStatement;    /* TABLE: item_slip */
    $fullStatement .= $item_add_record_statement; /* TABLE: item_add_record */
    $fullStatement .= $item_statement; /* TABLE: item */
    $fullStatement .= "COMMIT;";

    array_push($_SESSION['addMsg'], "เพิ่มใบเสร็จ ...OK!");
    array_push($_SESSION['addMsg'], "รายการเพิ่ม ...OK!");
    array_push($_SESSION['addMsg'], "รายการนับ ...OK!");

    //$item_add จะพิเศษหน่อยตรงที่ มันมี detail เป็น key ทำให้เวลา INSERT INTO ที่เป็ฯ statement ใหญ่ๆ ถ้ามีบางตัวที่ซํ้า มันจะทำให้ตัวอื่นที่ไม่ซํ้า error ไปหมด


    echo "<br/>row_count==" . $row_count;
    echo "<br/><br/>add item_slip=" . $addItemStatement;
    echo "<br/><br/>add item_add_record=" . $item_add_record_statement;
    echo "<br/><br/>add item=" . $item_statement;
    echo "<br/><br/>fullState=" . $fullStatement;


    mysqli_multi_query($connection, $fullStatement) or die("add_confirm.php/fullStatement FAIL" . mysqli_error($connection));
    ?>





    <?php
// เช็ค $uploadOK ไม่มี error
    if ($uploadOk == 0) {
        echo "ไฟล์ไม่ถูกอัปโหลด";

// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "อัปโหลดไฟล์ " . basename($_FILES["fileToUpload"]["name"]) . " เสร็จสมบูรณ์!";
            array_push($_SESSION['addMsg'], "อัปโหลดไฟล์ ...OK!");
            array_push($_SESSION['addMsg'], "<a href=\"$target_file\" target=\"_blank\">คลิกที่นี่เพื่อตรวจสอบไฟล์</a>");
        } else {
            echo "ไม่สามารถอัปโหลดไฟล์ได้ (Status(" . $uploadOk . "))";
        }
    }

    //header("Location: $root_url/add_urgent.php", true, 302);
    ?>





















    <!------------------------------- ดึงค่าของ table:item ออกมาก่อน เอาไว้บวกกับค่าที่เพิ่มเข้ามาเพื่อแสดงอย่างเดียว -------------------------------> 
    <?php
    $qx = "SELECT `quantity` FROM `item`";
    $queryx = mysqli_query($connection, $qx) or die("add_confirm.php/_queryx fail: " . mysqli_error($connection));
    $countx = mysqli_num_rows($queryx);

//กำหนดตัวแปรฝั่ง item
    $itemQty[] = "";

    while ($rowx = mysqli_fetch_array($queryx)) {
        $itemQty[] .= $rowx['quantity'];
    }
    ?>


    <!-- แสดงค่ารอตรวจสอบและตกลงเพื่อยืนยันการเพิ่ม -->

    <div align="center">
        <h2>เพิ่มรายละเอียดใบสั่งซื้อเสร็จสมบูรณ์!</h2><br/>
        <?= "ZPO ใบสั่งซื้อ: " . $_POST['var_zpo']; ?><br/>
        <?= "วันที่: " . date("d/m/Y", strtotime($_POST['var_slipDate'])); ?><br/>
        <?= "ชื่อผู้เพิ่ม: " . $_POST['var_adder']; ?><br/>
    </div>

    <br/>

    <table class="table table-striped table-bordered table-hover">
        <tr>
            <?php
            for ($i = 2; $i < $item_size - 3; $i++) {
                ?>
                <th align="center"><?= $item_headerInfo[$i][0] ?></th>
            <?php } ?>
            <th align="center" bgcolor="#ffff66">หน่วยย่อย</th>
            <th align="center" bgcolor="#ffff66">จำนวน/หน่วย</th>
            <th align="center" bgcolor='#ffc299'>จำนวนเบิกจ่าย</th>
        </tr>

        <?php
        for ($j = 0; $j < $row_count; $j++) {
            ?>
            <tr>
                <td align="center"><?= $postZDIR[$j] ?></td>
                <td align="center"><?= $postDetail[$j] ?></td>
                <td align="center"><?= $postSlipSuffix[$j] ?></td>
                <td align="center" style='color: blue;'><?= $postQty[$j] ?></td>
                <td align="center"><?= $postUnitPrice[$j] ?></td>
                <td align="center"><?= $postAmount[$j] ?></td>
                <?php if (!isset($postLastSuffix) || !isset($postLastQty)) { ?>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                <?php } else { ?>
                    <td align="center" bgcolor="#ffffe6"><b><?= $postLastSuffix[$j] ?></b></td>
                    <td align="center" bgcolor="#ffffe6"  style='color: red;'><b><?= $postLastQty[$j] ?></b></td>
                    <td align="center" bgcolor='#fff0e6'><b><?= "<font color='blue'>" . $_POST['var_qty'][$j] . "</font>x<font color='red'>" . $postLastQty[$j] . "</font> = <u>" . ($_POST['var_qty'][$j] * $postLastQty[$j]) . "</u>" ?></b></td>
                <?php } ?>
            </tr>
        <?php } ?>

    </table>
    <br/>

    <div align="center">
        <h4><?= "ยอดรวม: " . $_POST['var_subTotal']; ?> บาท<br/></h4>
        <h4><?= "ยอดรวมสุทธิ(+VAT7%): <b>" . $_POST['var_grandTotal'] . "</b> บาท"; ?><br/></h4>
    </div>

    <br/>

    <div class="alert alert-danger" align="center">
        <div><input type="button" class="btn btn-danger btn-lg" value=" X ปิดหน้านี้" onclick="self.close()"/></div> 				
        <h3>เมื่อตรวจสอบเสร็จสิ้น ควรปิดหน้านี้ทันที่</h3>
        อย่ากดปุ่ม <kbd><span class="glyphicon glyphicon-refresh"></span></kbd> หรือ <kbd>F5</kbd> เพราะจะทำให้ "จำนวนเบิกจ่าย" เพิ่มเป็น 2 เท่า
    </div>




    <!--Script -->
    <?php include 'main_script.php'; ?>



</html>