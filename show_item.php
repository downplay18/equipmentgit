<?php
//var_dump($_SESSION);
session_start();
error_reporting(0);
require_once 'connection.php';
include 'root_url.php';

if ($_SESSION['user_id'] == "") {
    header("Location: $root_url/index.php", true, 302);
    exit();
}

if (isset($_GET['detail'])) {
    $_SESSION['detail'] = $_GET['detail'];
    $_SESSION['owner'] = $_GET['owner'];
    $_SESSION['suffix'] = $_GET['suffix'];
}
?>

<?php 
//เข้ามาครั้งแรก ยังไม่กดปุ่มใดๆ
$addTakeQS = "SELECT * FROM `item` WHERE `detail` LIKE '" . $_SESSION['detail'] . "'";
$addTakeSize = 4;
$addTakeHeader = array('รายการ', 'จำนวน', 'หน่วย', 'เจ้าของ');
$addTakeData = array('detail', 'quantity', 'suffix', 'owner');
$addTakeMsg = "รายการคงเหลือปัจจุบัน";
?>

<?php
if (isset($_POST['showAddBtn'])) {
    $addTakeQS = "SELECT * FROM `item_add_record` WHERE `add_detail` LIKE '" . $_SESSION['detail'] . "'"
            . " AND `adder` LIKE '" . $_SESSION['owner'] . "'"; //มันแค่เช็ค add ใช้แค่ owner ไม่ใช่ division
    $addTakeSize = 6;
    $addTakeHeader = array('รายการ', 'จำนวน', 'หน่วย', 'วันที่', 'เวลา', 'ผู้ลงบันทึกเพิ่ม');
    $addTakeData = array('add_detail', 'add_qty', 'add_suffix', 'add_date', 'add_time', 'adder');
    $addTakeMsg = "รายการเพิ่มทั้งหมด";
} elseif (isset($_POST['showTakeBtn'])) {
    $addTakeQS = "SELECT * FROM `item_take_record` WHERE `take_detail` LIKE '" . $_SESSION['detail'] . "'"
            . " AND `taker` LIKE '" . $_SESSION['owner'] . "'";
    $addTakeSize = 8;
    $addTakeHeader = array('รายการ', 'จำนวน', 'หน่วย', 'วันที่', 'เวลา', 'ผู้ลงบันทึกเบิก', 'ผู้ใช้งาน', 'สถานที่ใช้งาน');
    $addTakeData = array('take_detail', 'take_qty', 'take_suffix', 'take_date', 'take_time', 'taker', 'worker', 'site');
    $addTakeMsg = "รายการเบิกใช้งานทั้งหมด";
} elseif (isset($_POST['showItemBtn'])) {
    $addTakeQS = "SELECT * FROM `item` WHERE `detail` LIKE '" . $_SESSION['detail'] . "'";
    $addTakeSize = 4;
    $addTakeHeader = array('รายการ', 'จำนวน', 'หน่วย', 'เจ้าของ');
    $addTakeData = array('detail', 'quantity', 'suffix', 'owner');
    $addTakeMsg = "รายการคงเหลือปัจจุบัน";
} elseif (isset($_POST['slipBtn'])) {
    
} elseif (isset($_POST['printBtn'])) {
    
}
?>

<html>
    <head>

        <title>ADMIN</title>
        <!-- Bootstrap Core CSS -->
        <?php include 'main_head.php'; ?>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jqc-1.12.3/dt-1.10.12/datatables.min.css"/>
    </head>

    <body>
        <?php
        /* navbar */
        /* ไม่ใช้ case unauthen เพราะไม่มีสิทธิ์เข้าหน้านี้อยู่แล้ว */
        include 'navbar.php';

        echo 'SESSION = ';
        print_r($_SESSION);
        echo '<br/>POST = <br/>';
        print_r($_POST);
        ?>

        <div class="row">
            <div class="col-md-2 sidebar">
                <?php include 'sidebar.php'; ?>
            </div>

            <div class="col-md-10">

                <!-- Main container -->
                <div class="container-fluid">

                    <div class="page-header">
                        <h2>สืบค้น <small><?= $_SESSION['detail'] ?> ของ<?= $_SESSION['owner'] ?></small></h2>
                    </div>


                    <?php
                    //โค้ดใน tag PHP เป็นของแถวที่ใช้ take
                    $takeRowMsg = "";
                    if ($_SESSION['status'] != "KEY" || $_SESSION['division'] != $_SESSION['owner']) { //ถ้ากดเข้ามาดูของที่ไม่ใช่กลุ่มงานตัวเอง
                        $takeRowMsg = "ไม่สามารถแก้ไขรายการนี้ได้ เนื่องจาก";
                        if ($_SESSION['status'] != "KEY") {
                            $takeRowMsg .= " (คุณไม่ใช่ผู้ดูแลประจำกลุ่มงาน)";
                        }
                        if ($_SESSION['division'] != $_SESSION['owner']) {
                            $takeRowMsg .= " (รายการนี้เป็นของ " . $_SESSION['owner'] . ")<br/>";
                        }
                        echo '<div class="alert alert-warning">';
                        echo $takeRowMsg;
                        echo '</div>';
                    } else {
                        ?>
                        <form id="singleSubmitForm" class="form-horizontal" action="show_take_process.php" method="post">
                            <?php
                            $itemQS = "SELECT `detail`,`quantity`,`suffix`,`owner` FROM `item` WHERE `owner` LIKE '" . $_SESSION['division'] . "'"
                                    . " AND `detail` LIKE '" . $_SESSION['detail'] . "'";
                            $itemQry = mysqli_query($connection, $itemQS) or die("itemQry failed: " . mysqli_error($connection));
                            $itemResult = mysqli_fetch_assoc($itemQry);
                            ?>
                            <label class="col-md-2 control-label">ลงบันทึกเบิก: </label>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="qty" placeholder="ต้องการเบิกจำนวน" style="size: 10px" required="">
                                        <div class="input-group-addon">จากคงเหลือ(<?= $itemResult['quantity'] . " " . $itemResult['suffix'] . ")" ?></div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select id="selWorker" class="form-control" name="worker" required="">
                                        <option>-- เลือกผู้ใช้ --</option>
                                        <?php
                                        //list ลูกจ้างในกลุ่มงานเดียวกัน
                                        $workerQS = "SELECT `wname`,`wdivision` FROM `worker` WHERE `wdivision` LIKE '" . $_SESSION['division'] . "' ORDER BY `wname` ASC";
                                        $workerQry = mysqli_query($connection, $workerQS);
                                        while ($rowWorker = mysqli_fetch_assoc($workerQry)) {
                                            ?>
                                            <option <?php
                                            if ($rowWorker['wname'] == $_SESSION['lastTakeWorker']) {
                                                echo 'selected';
                                            }
                                            ?>><?php echo $rowWorker['wname'] ?></option>
                                            <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control" name="site" required="">
                                            <option>-- นำไปใช้ที่ --</option>
                                            <?php
                                            $buildingQS = "SELECT `buildingID`,`listBuilding` FROM `list_building` ORDER BY `buildingID` ASC";
                                            $buildingQry = mysqli_query($connection, $buildingQS);
                                            while ($rowBuilding = mysqli_fetch_assoc($buildingQry)) {
                                                ?>
                                                <option <?php
                                                if ($rowBuilding['listBuilding'] == $_SESSION['lastTakeSite']) {
                                                    echo 'selected';
                                                }
                                                ?>><?php echo $rowBuilding['listBuilding'] ?></option>
                                                <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-warning singleSubmitBtn" name="takeSubmit" value="Submit"><span class="glyphicon glyphicon-minus-sign"></span> ลงบันทึกเบิก</button>
                                </div>
                            </div>
                        </form> <!-- /.form-horizontal -->
                    <?php } ?>


                    <form action="" method="post">
                        <div class="col-md-12" style="padding: 10px">
                            <div class="btn btn-group" style="float: left">
                                <button class="btn btn-default" type="submit" name="showAddBtn" value="submit"><span class="glyphicon glyphicon-leaf"></span> แสดงรายการเพิ่ม</button>
                                <button class="btn btn-default" type="submit" name="showTakeBtn" value="submit"><span class="glyphicon glyphicon-fire"></span> แสดงรายการเบิก</button>
                                <button class="btn btn-default" type="submit" name="showItemBtn" value="submit"><span class="glyphicon glyphicon-thumbs-up"></span> แสดงรายการคงเหลือปัจจุบัน</button>
                            </div>
                            <div class="btn btn-group" style="float: right">
                                <button class="btn btn-default" type="submit" name="slipBtn" value="submit"><span class="glyphicon glyphicon-file"></span> ดูสลิป</button>
                                <button class="btn btn-default" type="submit" name="printBtn" value="submit"><span class="glyphicon glyphicon-print"></span> พิมพ์หน้านี้</button>
                            </div>
                        </div>
                    </form>

                    <div class="col-md-12">
                        <?php
                        //ดึง ADD RECORD
                        $addTakeQry = mysqli_query($connection, $addTakeQS) or die("addTakeQry failed: " . mysqli_error($connection));
                        ?>
                        <b>กำลังแสดง: </b><?= $addTakeMsg ?>
                        <table class="table table-bordered table-hover table-condensed table-striped">
                            <thead>
                                <tr align="center">
                                    <?php
                                    foreach ($addTakeHeader as $value) {
                                        echo "<th>" . $value . "</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($rowAddTake = mysqli_fetch_assoc($addTakeQry)) {
                                    if (isset($_POST['showAddBtn'])) { //CASE แสดงรายการเพิ่ม
                                        echo '<tr align="center">';
                                        echo '<td align="left">' . $rowAddTake[$addTakeData[0]] . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[1]] . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[2]] . '</td>';
                                        echo '<td>' . preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $rowAddTake[$addTakeData[3]]) . '</td>';
                                        echo '<td>' . date("H:i", strtotime($rowAddTake[$addTakeData[4]])) . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[5]] . '</td>';
                                        echo '</tr>';
                                    } elseif (isset($_POST['showTakeBtn'])) {
                                        echo '<tr align="center">';
                                        echo '<td align="left">' . $rowAddTake[$addTakeData[0]] . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[1]] . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[2]] . '</td>';
                                        echo '<td>' . preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $rowAddTake[$addTakeData[3]]) . '</td>';
                                        echo '<td>' . date("H:i", strtotime($rowAddTake[$addTakeData[4]])) . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[5]] . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[6]] . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[7]] . '</td>';
                                        echo '</tr>';
                                    } elseif (isset($_POST['showItemBtn'])) {
                                        echo '<tr align="center">';
                                        echo '<td align="left">' . $rowAddTake[$addTakeData[0]] . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[1]] . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[2]] . '</td>';
                                        echo '<td>' . $rowAddTake[$addTakeData[3]] . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> <!-- /.col-md-12 -->


                    <?php include 'main_script.php'; ?>
                    <script> /*PREVENT DOUBLE SUBMIT: ทำให้ปุ่ม submit กดได้ครั้งเดียว ป้องกับปัญหาเนต lag แล้ว user กดเบิ้ล มันจะทำให้ส่งค่า 2 เท่า */
                        $(document).ready(function () {
                            $("#singleSubmitForm").submit(function () {
                                $("#singleSubmitBtn").attr("disabled", true);
                                return true;
                            });
                        });
                    </script>

                    </body>
                    </html>
