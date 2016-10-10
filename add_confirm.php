<?php
//var_dump($_SESSION);
session_start();
require_once 'connection.php';

include 'root_url.php';
if ($_SESSION['user_id'] == "") {
    header("Location: $root_url/index.php", true, 302);
    exit();
}
if($_SESSION['status']!="KEY") {
    header("Location: $root_url/index.php", true, 302);
}
?>

<html>

    <head>
        <?php include 'main_head.php'; ?>  
    </head>



    <?php
        include("navbar.php");
        /*
        echo '<br/>';
        echo 'SESSION = ';
        print_r($_SESSION);
        echo '<br/>loginResult =<br/>';
        print_r($loginResult);
        echo '<br/>POST = <br/>';
        print_r($_POST); */
        ?>

    <!-- breadcrumb -->
    <div class="container-fluid">

        <ol class="breadcrumb">
            <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> หน้าแรก</a></li>
        <li><a href="_login_user.php">รายการหลัก</a></li>
            <li><a href="javascript:history.back()">เพิ่มใบสั่งซื้อ</a></li>
            <li class="active">ตรวจสอบ</li>
        </ol> 
    </div><!-- /breadcrumb -->

    

    <?php
    /* นับจำนวนแถวว่าต้องทำกี่แถว เพราะไม่รู้ว่า user จะเพิ่มเข้ามากี่แถว */
    /* ในกรณีที่ป้อนแบบเว้นบรรทัด ใช้ array_filter ช่วยในการตัดบรรทัดที่ไม่ได้ป้อน */
    /* ใช้ array_values ช่วยให้มันเรียง index ใหม่ จาก 0 ถึงตัวหลังสุด */
    /* ###### ใช้ได้ในกรณีที่userกรอกทุกบรรทัดติดกัน เท่านั้น ถ้าป้อนแบบเว้นบรรทัดจะขึ้น warning ทันที */
    $row_count = count($_POST['varDetail']);
    /*
      print_r($row_count);
      echo '<br>filterPost==';
      print_r(array_filter($_POST['varDetail']));
      echo '<br>filterLS==';
      print_r(array_filter($_POST['var_lastSuffix'])); */



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

    //การสร้างประโยค query
    $addItemStatement = ""; /* บันทึกลงใน table: item_slip */
    $item_add_record_statement = ""; /* บันทึก record ของ table: item_add_record */
    $item_statement = "";
    /* for ของ $row_count (aka. $rc) */
    include 'item_headerInfo.php';
    for ($rc = 0; $rc < $row_count; $rc++) { /* 1 $rc คือ 1 แถวของรายการใน 1 ใบเสร็จ */
        if ($_POST['varDetail'][$rc] != "") { /* เช็คที่ detail เพราะมันเป็น primary key */
            $addItemStatement .= "INSERT INTO `item_slip` (`zpo`,`zdir`,`slip_date`,`detail`,`slip_suffix`,`qty`,`unit_price`,`amount`,`sub_total`,`grand_total`,`adder`)";
            $item_add_record_statement .= "INSERT INTO `item_add_record` (`add_detail`,`add_suffix`,`add_qty`,`add_date`,`add_time`,`adder`)";
            $item_statement .= "INSERT INTO `item` (`detail`,`suffix`,`quantity`,`type`,`owner`)";

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

            //ใส่ใน TABLE: item
            $item_statement .= " VALUES ('" . $postDetail[$rc] . "'";
            $item_statement .= ",'" . $postLastSuffix[$rc] . "'";
            $item_statement .= ",'" . ($_POST["var_qty"][$rc] * $postLastQty[$rc]) . "'";
            $item_statement .= ",'ปกติ'";
            $item_statement .= ",'" . $_POST['var_adder'] . "'";
            $item_statement .= ") ON DUPLICATE KEY UPDATE `quantity`=`quantity`+" . ($_POST['var_qty'][$rc] * $postLastQty[$rc]) . ";";
        }
    }
    
    /*debug section*/
    //echo "<br/></br>addItemStatement= " . $addItemStatement;
      //echo "<br/></br>additem= " . $addItemStatement;
      //echo "<br/></br>additem__RECORD= " . $item_add_record_statement;
      //echo "<br/><br/>";
     

    /* mysql ไม่สามารถคิวรี่ 2 table พร้อมกันเฉยๆได้ ต้องใช้ TRANSACTION+COMMIT ช่วย */
    /* จึงสร้างตัวแปร fullStatement ขึ้นมาสร้างคำสั่ง TRANSACTION+COMMIT */
    $fullStatement = "START TRANSACTION;";
    $fullStatement .= $addItemStatement;    /* TABLE: item_slip */
    $fullStatement .= $item_add_record_statement; /* TABLE: item_add_record */
    $fullStatement .= $item_statement; /* TABLE: item */
    $fullStatement .= "COMMIT;";

    
    //$item_add จะพิเศษหน่อยตรงที่ มันมี detail เป็น key ทำให้เวลา INSERT INTO ที่เป็ฯ statement ใหญ่ๆ ถ้ามีบางตัวที่ซํ้า มันจะทำให้ตัวอื่นที่ไม่ซํ้า error ไปหมด

    /*
      echo "row_count==" . $row_count;
      echo "<br/><br/>add item_slip=" . $addItemStatement;
      echo "<br/><br/>add item_add_record=" . $item_add_record_statement;
      echo "<br/><br/>add item=" . $item_statement;
      echo "<br/><br/>fullState=" . $fullStatement;
     */

    require('connection.php');
    mysqli_multi_query($connection, $fullStatement) or die("add_confirm.php/fullStatement FAIL");
    mysqli_close($connection);
    ?>

    <!------------------------------- ดึงค่าของ table:item ออกมาก่อน เอาไว้บวกกับค่าที่เพิ่มเข้ามาเพื่อแสดงอย่างเดียว -------------------------------> 
    <?php
    require 'connection.php';
    $qx = "SELECT `quantity` FROM `item`";
    $queryx = mysqli_query($connection, $qx) or die("add_confirm.php/_queryx คิวรี่ล้มเหลว<br/>");
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