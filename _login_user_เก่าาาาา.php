<?php
//var_dump($_SESSION);
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<br/>โปรดล็อกอินก่อน!";
    header('Location: http://localhost:81/equipment/index.php', true, 302);
    exit();
}
  /*
  if ($_SESSION['status'] != "USER") {
  echo "<br/>สำหรับ -พนักงาน- เท่านั้น!";
  exit(); 
  } */

require 'connection.php';
?>

<html>
    <head>

        <title>ADMIN</title>
        <!-- Bootstrap Core CSS -->
        <?php include 'main_head.php'; ?>

    </head>

    <body>
        <?php
        /* navbar */

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
          print_r($_POST); */
        ?>

        <!-- Main container -->
        <div class="container-fluid">

            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> หน้าแรก</a></li>
                <li class="active">รายการหลัก</li>
            </ol> <!-- /breadcrumb -->    





            <?php include 'root_url.php'; ?>

            <!--
            <h3 class="page-header">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง <small>กองพัฒนาด้านเทคโนโลยีโรงไฟฟ้าถ่านหินและเหมือง (กพทถ-ห.)</small></h3>
            -->

            <div class="row">
                <!-- สำหรับแถวแรก เป็นปุ่มเพิ่ม/เบิกใช้ -->
                <div class="col-md-12" style="padding: 0px 0px 15px;">
                    <div class="col-md-6" align="right">
                        <a class="btn btn-success" href="add.php" target="_blank"><span class="glyphicon glyphicon-plus-sign"></span> เพิ่มใบสั่งซื้อ</a>
                    </div>

                    <div class="col-md-6" align="left">
                        <a class="btn btn-warning" href="take.php" target="_blank"><span class="glyphicon glyphicon-minus-sign"></span> เบิกใช้งาน</a>
                    </div>
                </div> <!-- /.col-md-12 -->

                <!-- รายการที่สนใจ -->
                <div class="col-md-12">
                    <div class="well well-sm">

                        <h3 align="center">รายการที่สนใจ
                            <a class="btn btn-info" href="user_select_fav.php" target="" role="button"><span class="glyphicon glyphicon-edit"></span></a>
                        </h3>

                        <?php
                        //<!-- part1 favlist+favCount -->
                        //แสดงรายการที่สนใจที่ user เลือกเอาไว้
                        $favQS = "SELECT `favlist` FROM `user_favlist` WHERE `uid` LIKE " . $_SESSION['user_id']; /* `uid` & `favlist` */
                        $favQry = mysqli_query($connection, $favQS) or die("<br/>_login_user favQS คิวรี่ล้มเหลว<br/>" . mysql_error());
                        $favRow = mysqli_fetch_assoc($favQry);
                        $favEx = explode("|", $favRow['favlist']); /* $favEx เป็นarray เก็บ iid */

                        $itemQS = "SELECT `iid`,`detail`,`suffix`,`quantity` FROM `item`";
                        $itemQry = mysqli_query($connection, $itemQS) or die("<br/>_login_user itemQS คิวรี่ล้มเหลว<br/>" . mysql_error());

                        //แสดงรายการที่สนใจพร้อมจำนวนปัจจุบันที่เหลืออยู่ ท่ายากตรงที่เก็บ favlist เป็น iid ไม่ใช่ detail
                        $favlistDetail = ""; //เป็น detail ของ favlist
                        while ($itemRow = mysqli_fetch_assoc($itemQry)) {
                            foreach ($favEx as $value) { //แกะ $favEx ออกมาเป็น $favlistDetail
                                if ($value == $itemRow['iid']) {
                                    //echo "<br/><kbd>" . $itemRow['iid'] . "</kbd> " . $itemRow['detail'] . " <b>(" . $itemRow['quantity'] . " " . $itemRow['suffix'] . ")</b>";
                                    $favlistDetail[] = $itemRow['detail'];
                                }
                            }
                        }
                        /*
                          echo "<pre>favlistDetail_";
                          print_r($favlistDetail);
                          echo "</pre>"; */

                        //หารายการที่ add ล่าสุดจาก item_add_record
                        //ที่ไม่ใช้ count($favEx) เพราะมันนับรวม empty string เป็น 1 ด้วย
                        $favCount = 0;
                        foreach ($favEx as $value) {
                            if ($value != "") {
                                $favCount++;
                            }
                        }
                        //echo '<br>favCount=' . $favCount;
                        ?> <!-- /part1 favlist+favCount -->




                        <?php
                        if ($favCount == 0) { //อันดับแรกต้องเช็คก่อนว่ามี favlist หรือไม่
                            echo "<br/><div align='center'>ไม่มีรายการเฝ้าดู</div>";
                        } else {
                            //สร้างตารางดังนี้
                            /* ---0---+-----1----+-----2-----+---3---+----4---+----5-----+---6----+
                             * favlist|maxAddDate|maxTakeDate|takeSum|diffdate|unitPerDay|forecast|
                             * -------|----------|-----------|-------|--------|----------|--------|
                             *        |          |           |       |        |          |        | */
                            //สร้าง array ขึ้นมารองรับตารางข้างบนก่อน
                            for ($d = 0; $d < $favCount; $d++) {
                                $forecast[$d]['favlist'] = "";
                                $forecast[$d]['maxAddDate'] = "";
                                $forecast[$d]['maxTakeDate'] = "";
                                $forecast[$d]['diffdate'] = 0;
                                $forecast[$d]['takeSum'] = 0;
                                $forecast[$d]['unitPerDay'] = 0;
                                $forecast[$d]['forecast'] = "";
                            }

                            //ใส่ favlist--------------------------------------------------------------------------------
                            for ($z = 0; $z < $favCount; $z++) {
                                $forecast[$z]['favlist'] = $favlistDetail[$z];
                            }

                            //หา maxAddDate--------------------------------------------------------------------------------
                            if ($favCount == 1) { //เมื่อ favlist ตัวเดียว
                                $lastAddQS = "SELECT `add_detail`,MAX(`add_date`) AS maxAddDate FROM `item_add_record`"
                                        . " WHERE `add_detail` IN (SELECT `add_detail` FROM `item_add_record`"
                                        . " WHERE `add_detail` LIKE '" . $favlistDetail[0] . "'";
                            } else { //เมื่อมี favlist > 1
                                $lastAddQS = "SELECT `add_detail`,MAX(`add_date`) AS maxAddDate FROM `item_add_record`"
                                        . " WHERE `add_detail` IN (SELECT `add_detail` FROM `item_add_record`"
                                        . " WHERE `add_detail` LIKE '" . $favlistDetail[0] . "'";
                                $c = 1;
                                foreach ($favlistDetail as $value) {
                                    if ($c == 1) { //skip สมาชิกตัวแรกเพราะทำข้างบนไปแล้ว
                                        $c++;
                                        continue;
                                    } else {
                                        $lastAddQS .= " OR `add_detail` LIKE '" . $value . "'";
                                    }
                                }
                            } $lastAddQS .= ") GROUP BY `add_detail`;";
                            //echo "<br><br>lastAddQS= " . $lastAddQS;
                            $lastAddQry = mysqli_query($connection, $lastAddQS);
                            while ($rowAQ = mysqli_fetch_assoc($lastAddQry)) {
                                for ($i = 0; $i < $favCount; $i++) {
                                    if ($forecast[$i]['favlist'] == $rowAQ['add_detail']) {
                                        $forecast[$i]['maxAddDate'] = $rowAQ['maxAddDate'];
                                    }
                                }
                            }

                            //หา maxTakeDate--------------------------------------------------------------------------------
                            if ($favCount == 1) { //เมื่อ favlist ตัวเดียว
                                $lastTakeQS = "SELECT `take_detail`,MAX(`take_date`) AS maxTakeDate FROM `item_take_record`"
                                        . " WHERE `take_detail` IN (SELECT `take_detail` FROM `item_take_record`"
                                        . " WHERE `take_detail` LIKE '" . $favlistDetail[0] . "'";
                            } else { //เมื่อมี favlist > 1
                                $lastTakeQS = "SELECT `take_detail`,MAX(`take_date`) AS maxTakeDate FROM `item_take_record`"
                                        . " WHERE `take_detail` IN (SELECT `take_detail` FROM `item_take_record`"
                                        . " WHERE `take_detail` LIKE '" . $favlistDetail[0] . "'";
                                $c = 1;
                                foreach ($favlistDetail as $value) {
                                    if ($c == 1) { //skip สมาชิกตัวแรกเพราะทำข้างบนไปแล้ว
                                        $c++;
                                        continue;
                                    } else {
                                        $lastTakeQS .= " OR `take_detail` LIKE '" . $value . "'";
                                    }
                                }
                            } $lastTakeQS .= ") GROUP BY `take_detail`;";
                            //echo "<br><br>lastTakeQS= " . $lastTakeQS;
                            $lastTakeQry = mysqli_query($connection, $lastTakeQS);
                            while ($rowTQ = mysqli_fetch_assoc($lastTakeQry)) {
                                for ($i = 0; $i < $favCount; $i++) {
                                    if ($forecast[$i]['favlist'] == $rowTQ['take_detail']) {
                                        $forecast[$i]['maxTakeDate'] = $rowTQ['maxTakeDate'];
                                    }
                                }
                            }

                            //หา takeSum--------------------------------------------------------------------------------
                            $takeSumQS = "SELECT `take_detail`,`take_qty` FROM `item_take_record`";
                            $takeSumQry = mysqli_query($connection, $takeSumQS);
                            while ($rowTS = mysqli_fetch_assoc($takeSumQry)) {
                                for ($i = 0; $i < $favCount; $i++) {
                                    if ($forecast[$i]['favlist'] == $rowTS['take_detail']) {
                                        $forecast[$i]['takeSum'] += $rowTS['take_qty'];
                                    }
                                }
                            }

                            //หา diffdate--------------------------------------------------------------------------------
                            for ($i = 0; $i < $favCount; $i++) {
                                $day1 = strtotime($forecast[$i]['maxAddDate']);
                                $day2 = strtotime($forecast[$i]['maxTakeDate']);
                                $forecast[$i]['diffdate'] = (abs($day1 - $day2)) / (60 * 60 * 24);
                            }

                            //หา unitPerDay--------------------------------------------------------------------------------
                            for ($i = 0; $i < $favCount; $i++) {
                                //เหตุที่ใช้ ceiling แทนที่จะเป็น floor เพราะอัตราการใช้จริงมีแนวโน้มน้อยกว่าที่คำนวณได้ 
                                //forecast มีหน่วยเป็น ชิ้นต่อวัน
                                if ($forecast[$i]['diffdate'] == 0 || $forecast[$i]['takeSum'] == 0) {
                                    $forecast[$i]['unitPerDay'] = "diffdate หรือ takeSum เป็นศูนย์ ไม่สามารถคำนวณได้";
                                } else {
                                    $forecast[$i]['unitPerDay'] = ceil($forecast[$i]['diffdate'] / $forecast[$i]['takeSum']);
                                }
                            }

                            //หา forecast + ส่วนการแสดงค่า ----------------------------------------------------------------
                            $remainQS = "SELECT `iid`,`detail`,`quantity`,`suffix` FROM `item`";
                            $remainQry = mysqli_query($connection, $remainQS);
                            while ($rowRemain = mysqli_fetch_assoc($remainQry)) {
                                for ($i = 0; $i < $favCount; $i++) {
                                    if ($rowRemain['detail'] == $forecast[$i]['favlist']) { //เช็คให้แสดงเอาเฉพาะตัวที่มีใน favlist
                                        $forecast[$i]['forecast'] = $rowRemain['quantity'] * $forecast[$i]['unitPerDay'];
                                        echo "<br/><kbd>" . $rowRemain['iid'] . "</kbd> <b>[</b>" . $rowRemain['detail'] . "<b>]</b>"
                                        . " จำนวน " . $rowRemain['quantity'] . " " . $rowRemain['suffix']
                                                ." <br/>เบิกใช้งาน ".$forecast[$i]['takeSum'] . " " . $rowRemain['suffix'] . " ในเวลา " . $forecast[$i]['diffdate']. " วัน"
                                        . " <br/><font color='red'>(คาดว่าจะหมดในอีก " . $forecast[$i]['forecast'] . " วัน)</font><br/>";
                                    }
                                }
                            }

                            /*
                            echo "<pre>";
                            print_r($forecast);
                            echo "</pre>"; */
                        }
                        ?>












                        <!-- livesearch table:item
                        <div class="col-md-4">
                            ยังว่าง 1
                        </div>

                        <div class="col-md-4">
                            ยังว่างอยู่ 2
                        </div>

                        <div class="col-md-4">
                            ยังว่างอยู่ 3
                        </div>  -->

                    </div> 
                </div>  <!-- /รายการที่สนใจ -->


                <div class="col-md-4">
                    <h3 align="center">เพิ่มล่าสุด
                        <a class="btn btn-success" href="add.php" target="_blank" rel="noopener" role="button"><span class="glyphicon glyphicon-plus-sign"></span></a>
                    </h3>
                    <div class="alert alert-success">
                        <?php
                        $addQS = "SELECT * FROM `item_add_record` ORDER BY `add_id` DESC LIMIT 10;";
                        $addQry = mysqli_query($connection, $addQS) or die("<br/>_login_user addQS คิวรี่ล้มเหลว<br/>" . mysql_error());

                        while ($addRow = mysqli_fetch_assoc($addQry)) {
                            echo "<kbd>" . $addRow['add_id'] . "</kbd> <b>[</b>" . $addRow['add_detail'] . "<b>]</b> จำนวน " . $addRow['add_qty']
                            . " " . $addRow['add_suffix'] . "<code>by " . " " . $addRow['adder'] . "("
                            . date("d/m/Y", strtotime($addRow['add_date'])) . " " . $addRow['add_time'] . ")</code><br/>";
                        }
                        ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <h3 align="center">เบิกใช้งานล่าสุด
                        <a class="btn btn-warning" href="take.php" target="_blank" rel="noopener" role="button"><span class="glyphicon glyphicon-minus-sign"></span></a></h3>
                    </h3>
                    <div class="alert alert-warning">
                        <?php
                        $takeQS = "SELECT * FROM `item_take_record` ORDER BY `take_id` DESC LIMIT 10;";
                        $takeQry = mysqli_query($connection, $takeQS) or die("<br/>_login_user takeQS คิวรี่ล้มเหลว<br/>" . mysql_error());

                        while ($takeRow = mysqli_fetch_assoc($takeQry)) {
                            echo "<kbd>" . $takeRow['take_id'] . "</kbd> <b>[</b>" . $takeRow['take_detail'] . "<b>]</b> จำนวน " . $takeRow['take_qty']
                            . " " . $takeRow['take_suffix'] . "<code>by " . " " . $takeRow['taker'] . "("
                            . date("d/m/Y", strtotime($takeRow['take_date'])) . " " . $takeRow['take_time'] . ")</code><br/>";
                        }
                        ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <h3 align="center">แก้ไขล่าสุด
                        <a class="btn btn-default" href="edit_itemList.php" target="" rel="noopener" role="button"><span class="glyphicon glyphicon-edit"></span></a>
                    </h3>
                    <div class="alert alert-default">
                        <?php
//(`detail`,`edit_detail`,`qty`,`edit_qty`,`suffix`,`edit_suffix`,`edit_date`,`edit_time`,`editor`,`note`)
                        $editQS = "SELECT * FROM `item_edit_record` ORDER BY `edit_id` DESC LIMIT 6;";
                        $editQry = mysqli_query($connection, $editQS) or die("<br/>_login_user editQS คิวรี่ล้มเหลว<br/>" . mysql_error());


                        while ($editRow = mysqli_fetch_assoc($editQry)) {
                            //reset ตัวเองทุกครั้งที่มี loop ทำให้ค่าไม่ค้างเวลาขึ้น loop ใหม่แล้วไม่มีการแก้ไขใน TABLE
                            $shEditDetail = "";
                            $shEditQty = "";
                            $shEditSuffix = "";
                            //ถ้ามีการแก้ detail
                            if ($editRow['edit_detail'] != "") {
                                $shEditDetail .= "<b>(</b><u>รายละเอียด</u>:" . $editRow['detail'] . "<b> => </b>" . $editRow['edit_detail'] . "<b>)</b>";
                            }

                            //ถ้ามีการแก้ quantity
                            $shEditQty = "";
                            if ($editRow['edit_qty'] != "") {
                                $shEditDetail .= " <b>(</b><u>จำนวน</u>:" . $editRow['qty'] . "<b> => </b>" . $editRow['edit_qty'] . "<b>)</b>";
                                ;
                            }

                            //ถ้ามีการแก้ suffix
                            $shEditSuffix = "";
                            if ($editRow['edit_suffix'] != "") {
                                $shEditDetail .= " <b>(</b><u>หน่วย</u>:" . $editRow['suffix'] . "<b> => </b>" . $editRow['edit_suffix'] . "<b>)</b>";
                                ;
                            }

                            echo "<kbd>" . $editRow['edit_id'] . "</kbd> <b>[</b>" . $editRow['detail'] . "<b>]</b>" . " แก้เป็น "
                            . $shEditDetail
                            . $shEditQty
                            . $shEditSuffix
                            . " <b>(</b><u>หมายเหตุ</u>:" . $editRow['note'] . "<b>)</b> "
                            . "<code>by " . " " . $editRow['editor'] . "("
                            . date("d/m/Y", strtotime($editRow['edit_date'])) . " "
                            . $editRow['edit_time'] . ")</code><br/>";
                        }
                        ?>
                    </div>
                </div>

            </div>


<div class="col-md-12">
        <div id="line-example" style="height: 300px;"></div>
    </div>

        </div><!-- Main container -->









        <?php include 'main_script.php'; ?>
        <script>
            //Morris charts snippet - js

            $.getScript('http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js', function () {
                $.getScript('http://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.min.js', function () {

                    Morris.Area({
                        element: 'area-example',
                        data: [
                            {y: '1.1.', a: 100, b: 90},
                            {y: '2.1.', a: 75, b: 65},
                            {y: '3.1.', a: 50, b: 40},
                            {y: '4.1.', a: 75, b: 65},
                            {y: '5.1.', a: 50, b: 40},
                            {y: '6.1.', a: 75, b: 65},
                            {y: '7.1.', a: 100, b: 90}
                        ],
                        xkey: 'y',
                        ykeys: ['a', 'b'],
                        labels: ['Series A', 'Series B']
                    });

                    Morris.Line({
                        element: 'line-example',
                        data: [
                            {year: '2010', value: 20},
                            {year: '2011', value: 10},
                            {year: '2012', value: 5},
                            {year: '2013', value: 2},
                            {year: '2014', value: 20}
                        ],
                        xkey: 'year',
                        ykeys: ['value'],
                        labels: ['Value']
                    });

                    Morris.Donut({
                        element: 'donut-example',
                        data: [
                            {label: "Android", value: 12},
                            {label: "iPhone", value: 30},
                            {label: "Other", value: 20}
                        ]
                    });

                    Morris.Bar({
                        element: 'bar-example',
                        data: [
                            {y: 'Jan 2014', a: 100},
                            {y: 'Feb 2014', a: 75},
                            {y: 'Mar 2014', a: 50},
                            {y: 'Apr 2014', a: 75},
                            {y: 'May 2014', a: 50},
                            {y: 'Jun 2014', a: 75}
                        ],
                        xkey: 'y',
                        ykeys: ['a'],
                        labels: ['Visitors', 'Conversions']
                    });

                });
            });
        </script>


    </body>
</html>
