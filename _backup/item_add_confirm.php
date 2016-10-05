<?php
session_start();
if ($_SESSION['user_id'] == "") {
    echo "<br/>โปรดยืนยันตัวตนก่อน !";
    exit();
}
?>

<html>

    <head>
        <?php include 'main_head.php'; ?>  
    </head>

    <body>

        <?php
        /* เลือกแสดง navbar แบบ ล็อกอินแล้ว(authenticated) และ ยังไม่ล็อกอิน(unauthenticated) */
        if (!isset($_SESSION['user_id'])) {
            include("navbar_unauthen.php");
        } else {
            include("navbar_authen.php");
        }
        /*
          echo '<br/>';
          echo 'SESSION = ';
          print_r($_SESSION);
          echo '<br/>loginResult =<br/>';
          print_r($loginResult);
          echo '<br/>POST = <br/>';
          print_r($_POST);
         */
        ?>

        <!-- breadcrumb -->
        <div class="container-fluid">

            <ol class="breadcrumb">
                <li><a href="index.php">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง กพทถ-ห.</a></li>
                <li><a href="_login_user.php"><?= $loginResult['name'] ?></a></li>
                <li><a href="javascript:history.back()">เพิ่มข้อมูลใหม่ตามใบเสร็จ</a></li>
                <li class="active">ตรวจสอบ</li>
            </ol> 
        </div><!-- /breadcrumb -->




        <?php
        /*
          echo "_POST=<br/>";
          print_r($_POST); */

        /* นับจำนวนแถวว่าต้องทำกี่แถว เพราะไม่รู้ว่า user จะเพิ่มเข้ามากี่แถว */
        $row_count = count($_POST['var_detail']);
        $_SESSION['row_count'] = $row_count ; //ส่งจำนวนแถวที่ user ป้อน
        
        /* เพิ่ม SESSION ใหม่ เพื่อส่งไปทำใน item_add_suffix */
        $_SESSION['var_detail'] = $_POST['var_detail'];

        /*หน่วย&จำนวน เอาไปเขียนhint*/
        $_SESSION['var_slipSuffix'] = $_POST['var_slipSuffix'];
        $_SESSION['var_qty'] = $_POST['var_qty'];
        
        /* บังคับให้ "ชื่อผู้เพิ่มรายการ" เป็นชื่อคนที่ล็อกอินในขณะนั้น */
        $_POST['var_adder'] = $_SESSION['name'];
        
        $addItemStatement = ""; /* บันทึกลงใน table: item_slip */ 
        $item_add_record_statement = ""; /* บันทึก record ของ table: item_add_record */
        $item_statement = "";
        
        /* for ของ $row_count (aka. $rc) */
        include 'item_headerInfo.php';
        for ($rc = 0; $rc < $row_count; $rc++) { /* 1 $rc คือ 1 แถวของรายการใน 1 ใบเสร็จ */
            if ($_POST['var_detail'][$rc] != "") { /* เช็คที่ detail เพราะมันเป็น primary key */
                $addItemStatement .= "INSERT INTO `item_slip` (`zpo`,`zdir`,`slip_date`,`detail`,`slip_suffix`,`qty`,`unit_price`,`amount`,`sub_total`,`grand_total`,`adder`)";
                $item_add_record_statement .= "INSERT INTO `item_add_record` (`add_detail`,`add_suffix`,`add_qty`,`add_date`,`add_time`,`adder`)";
                $item_statement .= "INSERT INTO `item` (`detail`,`suffix`,`qty`)";
                
                /* สร้างประโยคหลัง INSERT INTO `item_slip` 
                  for ($i = 1; $i < $item_size; $i++) {
                  if ($_POST[$item_headerInfo[$i][2]] != "") {
                  $addItemStatement .= ",`" . $item_headerInfo[$i][1] . "`";
                  }
                  } */

                $addItemStatement .= " VALUES ('" . $_POST['var_zpo'] . "'"; /* zpo */
                $addItemStatement .= ",'" . $_POST['var_zdir'][$rc] . "'"; /* zdir[] */
                $addItemStatement .= ",'" . $_POST['var_slipDate'] . "'"; /* slip_date */
                $addItemStatement .= ",'" . $_POST['var_detail'][$rc] . "'"; /* detail[] */
                $addItemStatement .= ",'" . $_POST['var_slipSuffix'][$rc] . "'"; /* slip_suffix[] */
                $addItemStatement .= ",'" . $_POST['var_qty'][$rc] . "'"; /* qty[] */
                $addItemStatement .= ",'" . $_POST['var_unitPrice'][$rc] . "'"; /* unit_price[] */
                $addItemStatement .= ",'" . $_POST['var_amount'][$rc] . "'"; /* amount[] */
                $addItemStatement .= ",'" . $_POST['var_subTotal'] . "'"; /* sub_total */
                $addItemStatement .= ",'" . $_POST['var_grandTotal'] . "'"; /* grand_total */
                $addItemStatement .= ",'" . $_POST['var_adder'] . "'"; /* adder */
                $addItemStatement .= ");";

                $item_add_record_statement .= " VALUES ('" . $_POST['var_detail'][$rc] . "'";
                $item_add_record_statement .= ",'" . $_POST['var_slipSuffix'][$rc] . "'";
                $item_add_record_statement .= ",'" . $_POST['var_qty'][$rc] . "'";
                date_default_timezone_set("Asia/Bangkok");
                $item_add_record_statement .= ",'" . $_POST['var_slipDate'] . "'"; /* ต้องใช้วันที่ปัจจุบัน */
                $item_add_record_statement .= ",'" . date("h:i:sa") . "'"; /* ต้องใช้เวลาปัจจุบัน */
                $item_add_record_statement .= ",'" . $_POST['var_adder'] . "'";
                $item_add_record_statement .= ");";
            
                $item_statement .= " VALUES ('".$_POST['var_detail'][$rc]."'";
                $item_statement .= ",'" . $_POST['var_slipSuffix'][$rc] . "'";
                $item_statement .= ",'" . $_POST['var_qty'][$rc] . "'";
                $item_statement .= ");";
            }
        }
        /*
          echo "<br/></br>additem= " . $addItemStatement;
          echo "<br/></br>additem__RECORD= " . $item_add_record_statement;
          echo "<br/><br/>";
         */

        /* mysql ไม่สามารถคิวรี่ 2 table พร้อมกันเฉยๆได้ ต้องใช้ TRANSACTION+COMMIT ช่วย */
        /* จึงสร้างตัวแปร fullStatement ขึ้นมาสร้างคำสั่ง TRANSACTION+COMMIT */
        $fullStatement = "START TRANSACTION;";
        $fullStatement .= $addItemStatement;
        $fullStatement .= $item_add_record_statement;
        $fullStatement .= $item_statement;
        $fullStatement .= "COMMIT;";

        /*
          echo "<br/><br/>fullState=" . $fullStatement;
          echo "<br/><br/>";
         */


        require('connection.php');
        mysqli_multi_query($connection, $fullStatement) or die("item_add_confirm.php/fullStatement FAIL");
        mysqli_close($connection);
        ?>


        <!-- แสดงค่ารอตรวจสอบและตกลงเพื่อยืนยันการเพิ่ม -->
        <div class="container">
            <div align="center">
                <h2>เพิ่มรายละเอียดใบสั่งซื้อเสร็จสมบูรณ์!</h2><br/>
                <?= "ZPO ใบสั่งซื้อ: " . $_POST['var_zpo']; ?><br/>
                <?= "วันที่: " . date("d/m/Y", strtotime($_POST['var_slipDate'])); ?><br/>
                <?= "ชื่อผู้เพิ่ม: " . $_POST['var_adder']; ?><br/>
            </div>

            <br/>

            <table style="width: 100%">
                <tr>
                    <?php
                    for ($i = 2; $i < $item_size - 3; $i++) {
                        ?>
                        <th align="center"><?= $item_headerInfo[$i][0] ?></th>
                    <?php } ?>
                </tr>

                <?php
                for ($j = 0; $j < $row_count; $j++) {
                    ?>
                    <tr>
                        <?php
                        for ($k = 2; $k < $item_size - 3; $k++) {
                            ?>
                            <td align="center"><?= $_POST[$item_headerInfo[$k][2]][$j] ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>

            </table>
            <br/>

            <div align="center">
                <?= "ยอดรวม: " . $_POST['var_subTotal']; ?> บาท<br/>
                <?= "ยอดรวมสุทธิ(+VAT7%): <b>" . $_POST['var_grandTotal'] . "</b> บาท"; ?><br/>
            </div>

            <br/>

            <!-- ระบุหน่วยย่อย -->
            <div align="center" class="well well-sm"><h4> ---------- หน่วยในใบเสร็จ เป็นหน่วยที่ใช้ในการ<b>เบิกจ่ายจริง</b>หรือไม่? ---------- </h4></div>

            <div class="alert alert-success">
                <h4 align="center">ถ้า <b>ใช่!</b> คุณเสร็จสิ้นการเพิ่มใบสั่งซื้อแล้ว สามารถปิดหน้านี้ได้ทันที!</h4>
            </div>

            <div class="alert alert-danger">
                <h4 align="center">ถ้า <b>ไม่ใช่!</b> โปรดแก้ไขหน่วยนับตามฟอร์มข้างล่าง</h4>

                <form action="item_add_suffix.php" method="post">
                    <table style="width: 100%;border: 1px solid black;">

                        <!-- แถว หัวตาราง -->
                        <tr>
                            <th align="center">รายการ</th>
                            <th align="center">หน่วยตามใบสั่งซื้อ</th>
                            <th align="center">จน.ตามใบสั่งซื้อ</th>
                            <th align="center">หน่วยเบิกจ่าย</th>
                            <th align="center">จน.ใหม่ตามหน่วยเบิกจ่าย</th>
                        </tr> <!-- /แถว หัวตาราง -->

                        <?php for ($j = 0; $j < $row_count; $j++) { ?>
                            <tr> <?php
                                for ($k = 3; $k < 8; $k++) {
                                    if ($k == 3 || $k < 6) {
                                        ?>
                                        <td align="center"><?= $_POST[$item_headerInfo[$k][2]][$j] ?></td>
                                    <?php } elseif ($k == 6) { ?>
                                        <td align="center"><input type="text" class="form-control" name="var_suffix[]" placeholder="var_suffix"></td>
                                    <?php } elseif ($k == 7) { ?>
                                        <td align="center"><input type="text" class="form-control" name="var_quantity[]" placeholder="var_quantity"></td>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        <?php } ?>

                    </table>

                    <!-- ปุ่ม -->
                    <div class="form-group" align="center" style="padding: 15px;">

                        <button class="btn btn-lg btn-warning" type="submit">
                            <span class="glyphicon glyphicon-edit"></span>&nbsp;แก้ไข
                        </button>

                    </div> <!-- /ปุ่ม -->

                </form>
            </div>



            <!--
                        <div class="form-group" align="center" style="padding: 5px 5px">
            
                            <a class="btn btn-success" href="index.php" role="button"> 
                                <span class="glyphicon glyphicon-ok"></span>&nbsp;ใช่
                            </a> 
            
                            <a class="btn btn-danger btn-lg" href="item_add_suffix.php" role="button"> 
                                <span class="glyphicon glyphicon-remove"></span>&nbsp;ไม่ใช่ (เพิ่มหน่วยเบิกจ่าย)
                            </a> 
            
                        </div>
            -->







            <div align="center"> ---------- หรือ ---------- </div>
            <!-- /ระบุหน่วยย่อย -->

            <!-- ปุ่มกลับ -->
            <div class="form-group" align="center" style="padding: 10px 5px 50px">

                <a class="btn btn-default btn-lg" href="item_add.php" role="button"> 
                    <span class="glyphicon glyphicon-refresh"></span>&nbsp;รีเซ็ทและเพิ่มใบเสร็จอื่น
                </a>

                <a class="btn btn-default btn-lg" href="_login_user.php" role="button"> 
                    <span class="glyphicon glyphicon-step-backward"></span>&nbsp;กลับไปหน้าแรก
                </a>

            </div><!-- /ปุ่มกลับ -->

        </div> <!-- /content container -->










        <!--Script -->
        <?php include 'main_script.php'; ?>

    </body>
</html>