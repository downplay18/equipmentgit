<?php
//var_dump($_SESSION);
session_start();
require_once 'connection.php';

include 'root_url.php';
if ($_SESSION['user_id'] == "") {
    header("Location: $root_url/index.php", true, 302);
    exit();
}

unset($_SESSION['detail']);
unset($_SESSION['suffix']);
unset($_SESSION['owner']);

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
                        <h2>สืบค้น <small>ระบบสืบค้นและพิมพ์รายงาน</small></h2>
                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <label for="selDiv">ค้นหาโดยกลุ่มงาน</label>
                            <div class="input-group" id="selDiv">
                                <select class="form-control">
                                    <option>-- เลือกกลุ่มงาน --</option>
                                    <?php
                                    //เรียก list กลุ่มงานทั้งหมด
                                    $divQS = "SELECT `listDivision` FROM `list_division`";
                                    $divQry = mysqli_query($connection, $divQS);
                                    while ($rowDiv = mysqli_fetch_assoc($divQry)) {
                                        ?>
                                        <option 
                                        <?php
                                        //เลือกกลุ่มงานตัวเองไว้โดยอัตโนมัติ
                                        if ($rowDiv['listDivision'] == $_SESSION['division']) {
                                            echo 'selected';
                                        }
                                        ?>>
                                            <?php echo $rowDiv['listDivision']; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-info" type="button" autofocus="" ><span class="glyphicon glyphicon-search"></span> ค้นหา</button>
                                </span>
                            </div> <!-- /input-group -->
                        </div> <!-- /.col-md-6 -->

                        <div class="col-md-6">
                            <label for="selBuilding">ค้นหาโดยสถานที่ใช้งาน</label>
                            <div class="input-group" id="selBuilding">
                                <select class="form-control">
                                    <option>-- เลือกสถานที่ใช้งาน --</option>
                                    <?php
                                    //เรียก list ตุกทั้งหมด
                                    $siteQS = "SELECT `listBuilding` FROM `list_building`";
                                    $siteQry = mysqli_query($connection, $siteQS);
                                    while ($rowSite = mysqli_fetch_assoc($siteQry)) {
                                        ?>
                                        <option><?= $rowSite['listBuilding']; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-info" type="button"><span class="glyphicon glyphicon-search"></span> ค้นหา</button>
                                </span>
                            </div> <!-- /input-group -->
                        </div> <!-- /.col-md-6 -->
                        
                        <div class="col-md-12">
                            <br/>
                            <b>กำลังแสดงตารางของ:</b> <?= $_SESSION['division']; ?> 
                            <?php //ดึง item
                            $initQS = "SELECT `detail`,`quantity`,`suffix`,`owner` FROM `item`";
                            $initQry = mysqli_query($connection, $initQS);
                            //echo "<b>มีทั้งหมด:</b> " . count($initQry['detail']) . " รายการ";
                            ?>
                            <table class="table table-bordered table-condensed table-striped table-hover">
                                <thead>
                                    <tr align="center">
                                        <th>รายการ</th>
                                        <th>จำนวน</th>
                                        <th>หน่วย</th>
                                        <th>เจ้าของ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($rowInit = mysqli_fetch_assoc($initQry)) {
                                        ?>
                                        <tr align="center">
                                            <td align="left">
                                                <a href="show_item.php?detail=<?= $rowInit['detail'] ?>&owner=<?= $rowInit['owner']?>&suffix=<?= $rowInit['suffix'] ?>" target="_blank">
                                                    <?= $rowInit['detail'] ?>
                                                </a>
                                            </td>
                                            <td><?= $rowInit['quantity'] ?></td>
                                            <td><?= $rowInit['suffix'] ?></td>
                                            <td><?= $rowInit['owner'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div> <!-- /.col-md-12 -->

                    </div><!-- Main container -->
                </div> <!-- /.col-md-10 -->

            </div> <!-- /.row -->



            <?php include 'main_script.php'; ?>
            <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jqc-1.12.3/dt-1.10.12/datatables.min.js"></script>

    </body>
</html>
