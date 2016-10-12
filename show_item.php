<?php
//var_dump($_SESSION);
session_start();
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
                            <div class="form-group">
                                <label class="col-md-2 control-label">ลงบันทึกเบิก: </label>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="qty" placeholder="ต้องการเบิกจำนวน" style="size: 10px" required="">
                                        <div class="input-group-addon">จากคงเหลือ(<?= $itemResult['quantity'] . " " . $itemResult['suffix'] . ")" ?></div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="worker" required="">
                                        <option>-- เลือกผู้ใช้ --</option>
                                        <?php
                                        //list ลูกจ้างในกลุ่มงานเดียวกัน
                                        $workerQS = "SELECT `wname`,`wdivision` FROM `worker` WHERE `wdivision` LIKE '" . $_SESSION['division'] . "' ORDER BY `wname` ASC";
                                        $workerQry = mysqli_query($connection, $workerQS);
                                        while ($rowWorker = mysqli_fetch_assoc($workerQry)) {
                                            ?>
                                            <option><?php echo $rowWorker['wname'] ?></option>
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
                                                <option><?php echo $rowBuilding['listBuilding'] ?></option>
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
                    <div style="padding: 10px">
                        <button class="btn btn-default"><span class="glyphicon glyphicon-file"></span> ดูสลิป</button>
                        <button class="btn btn-default"><span class="glyphicon glyphicon-print"></span> พิมพ์หน้านี้</button>
                    </div>

                    <div class="col-md-6">
                        <?php
                        //ดึง ADD RECORD
                        $addRecordQS = "SELECT `add_detail`,`add_suffix`,`add_qty`,`add_date`,`add_time`,`adder` FROM `item_add_record`"
                                . " WHERE `add_detail` LIKE '" . $_SESSION['detail'] . "'";
                        $addRecordQry = mysqli_query($connection, $addRecordQS) or die("addRecordQry failed: " . mysqli_error($connection));
                        ?>
                        <table class="table table-bordered table-hover table-condensed table-striped">
                            <thead>
                                <tr align="center">
                                    <th>รายการ</th>
                                    <th>จำนวน</th>
                                    <th>หน่วย</th>
                                    <th>วันที่</th>
                                    <th>เวลา</th>
                                    <th>เจ้าของ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($rowInit = mysqli_fetch_assoc($addRecordQry)) {
                                    ?>
                                    <tr align="center">
                                        <td align="left"><?= $rowInit['add_detail'] ?></td>
                                        <td><?= $rowInit['add_qty'] ?></td>
                                        <td><?= $rowInit['add_suffix'] ?></td>
                                        <td><?= $rowInit['add_date'] ?></td>
                                        <td><?= date("H:i", strtotime($rowInit['add_time'])) ?></td>
                                        <td><?= $rowInit['adder'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <?php
                        //ดึง TAKE RECORD
                        $addRecordQS = "SELECT `take_detail`,`take_suffix`,`take_qty`,`take_date`,`take_time`,`taker` FROM `item_take_record`"
                                . " WHERE `take_detail` LIKE '" . $_SESSION['detail'] . "'";
                        $addRecordQry = mysqli_query($connection, $addRecordQS) or die("addRecordQry failed: " . mysqli_error($connection));
                        //echo "<b>มีทั้งหมด:</b> " . count($addRecordQry['detail']) . " รายการ";
                        ?>
                        <table border="1">
                            <thead>
                                <tr align="center">
                                    <th>รายการ</th>
                                    <th>จำนวน</th>
                                    <th>หน่วย</th>
                                    <th>วันที่</th>
                                    <th>เวลา</th>
                                    <th>เจ้าของ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($rowInit = mysqli_fetch_assoc($addRecordQry)) {
                                    ?>
                                    <tr align="center">
                                        <td align="left"><?= $rowInit['add_detail'] ?></td>
                                        <td><?= $rowInit['add_qty'] ?></td>
                                        <td><?= $rowInit['add_suffix'] ?></td>
                                        <td><?= $rowInit['add_date'] ?></td>
                                        <td><?= date("H:i", strtotime($rowInit['add_time'])) ?></td>
                                        <td><?= $rowInit['adder'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

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
