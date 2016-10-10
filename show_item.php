<?php
//var_dump($_SESSION);
session_start();
require_once 'connection.php';

include 'root_url.php';
if ($_SESSION['user_id'] == "") {
    header("Location: $root_url/index.php", true, 302);
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

                    <?php
                    $initQS = "SELECT `add_detail`,`add_suffix`,`add_qty`,`add_date`,`add_time` FROM `item_add_record`"
                            . " WHERE `detail` LIKE '" . $_GET['detail'] . "'"
                            . " AND `";
                    $initQry = mysqli_query($connection, $initQS);
                    //echo "<b>มีทั้งหมด:</b> " . count($initQry['detail']) . " รายการ";
                    ?>
                    <table border="1">
                        <thead>
                            <tr align="center">
                                <th>รายการ</th>
                                <th>จำนวน</th>
                                <th>หน่วย</th>
                                <th>ประเภท</th>
                                <th>เจ้าของ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($rowInit = mysqli_fetch_assoc($initQry)) {
                                ?>
                                <tr align="center">
                                    <td align="left"><a href="show_item.php?detail=<?= $rowInit['detail'] ?>" target="_blank"><?= $rowInit['detail'] ?></a></td>
                                    <td><?= $rowInit['quantity'] ?></td>
                                    <td><?= $rowInit['suffix'] ?></td>
                                    <td><?= $rowInit['type'] ?></td>
                                    <td><?= $rowInit['owner'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>



                    <?php include 'main_script.php'; ?>

                    </body>
                    </html>
