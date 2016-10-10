<?php
//var_dump($_SESSION);
session_start();
require_once 'connection.php';

include 'root_url.php';
if ($_SESSION['user_id'] == "") {
    header("Location: $root_url/index.php", true, 302);
    exit();
}

if ($_SESSION['status'] != "KEY" || $_SESSION['division'] != $_GET['owner']) {
    echo 'ไม่สามารถดูรายละเอียดได้ ไม่ใช่ผู้อยู่กลุ่มงานนี้ !!!';
    exit();
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

        /*
          echo '<br/>';
          echo 'SESSION = ';
          print_r($_SESSION);
          echo '<br/>loginResult =<br/>';
          print_r($loginResult);
          echo '<br/>POST = <br/>';
          print_r($_POST); */
        ?>

        <div class="row">

            <div class="col-md-2 sidebar">
                <?php include 'sidebar.php'; ?>
            </div>

            <div class="col-md-10">

                <!-- Main container -->
                <div class="container-fluid">

                    <div class="page-header">
                        <h2>สืบค้น <small>ระบบสืบค้นและพิมพ์รายงาน</small></h2>
                    </div>

                    <div style="padding: 10px">
                        <button class="btn btn-default"><span class="glyphicon glyphicon-file"> ดูสลิป</span></button>
                        <button class="btn btn-default"><span class="glyphicon glyphicon-print"> พิมพ์หน้านี้</span></button>
                    </div>

                    <div class="col-md-6">
                        <?php //ADD RECORD
                        $addRecordQS = "SELECT `add_detail`,`add_suffix`,`add_qty`,`add_date`,`add_time`,`adder` FROM `item_add_record`"
                                . " WHERE `add_detail` LIKE '" . $_GET['detail'] . "'";
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

                    <div class="col-md-6">
                        <?php //TAKE RECORD
                        $addRecordQS = "SELECT `take_detail`,`take_suffix`,`take_qty`,`take_date`,`take_time`,`taker` FROM `item_take_record`"
                                . " WHERE `take_detail` LIKE '" . $_GET['detail'] . "'";
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

                    </body>
                    </html>
