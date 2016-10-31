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

unset($_SESSION['detail']);
unset($_SESSION['suffix']);
unset($_SESSION['owner']);
?>

<?php
//สร้าง Query Statement สำหรับแสดง ใบสั่งซื้อ(ปกติ)
$divSiteQS = "";
$tableHeader = "";
$tableData = "";
$qryMsg = "";


if ($_POST['divName'] == "-- แยกตามกลุ่มงาน --" || empty($_POST['divName']) || isset($_POST['submitAll'])) {
    //กดปุ่ม แสดงทั้งหมด
    echo "SHOW ALL";
    unset($_SESSION['lastDiv']);
    unset($_POST['divName']);
    unset($queryMsg);
    $divSiteQS = "SELECT `detail`,`quantity`,`suffix`,`owner`"
            . " FROM `item`";
    $tableHeader = array("รายการ", "คงเหลือ", "เจ้าของ");
    $tableData = array("detail", "quantity", "suffix", "owner");
    $qryMsg = "แสดงทั้งหมด";
} else { //แสดงเฉพาะที่เลือก
    echo 'SHOW SELECTED';
    $divSiteQS = "SELECT `detail`,`quantity`,`suffix`,`owner`"
            . " FROM `item`"
            . " WHERE `owner`"
            . " LIKE '" . $_POST['divName'] . "'";
    $tableHeader = array("รายการ", "ใช้ไป", "เจ้าของ");
    $tableData = array("detail", "quantity", "suffix", "owner");
    $qryMsg = $_POST['divName'];
    $_SESSION['lastDiv'] = $_POST['divName'];
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
                        <h2>สืบค้น <small>ระบบสืบค้นและพิมพ์รายงาน</small></h2>
                    </div>


                    <div class="row">
                        <!-- แถว แสดง dropdown กลุ่มงาน+site -->
                        <div class="col-md-12" style="padding: 1.5em">

                            <form action="" method="post">
                                <div class="col-md-3">
                                    <select id="selDiv" class="form-control" name="divName">
                                        <option>-- แยกตามกลุ่มงาน --</option>
                                        <?php
                                        //เรียก list กลุ่มงานทั้งหมด
                                        $divQS = "SELECT `listDivision` FROM `list_division` ORDER BY `divisionID` ASC";
                                        $divQry = mysqli_query($connection, $divQS);
                                        while ($rowDiv = mysqli_fetch_assoc($divQry)) {
                                            ?>
                                            <option 
                                            <?php
                                            //แยกตามกลุ่มงานล่าสุด
                                            if ($rowDiv['listDivision'] == $_SESSION['lastDiv']) {
                                                echo 'selected';
                                            }
                                            ?>>
                                                    <?php echo $rowDiv['listDivision']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div> <!-- /.col-md-3 -->
                                <div class="col-md-1">
                                    <button class="btn btn-success" type="submit" name="submitBtn" value="submit"><span class="glyphicon glyphicon-search"></span> ค้นหา</button>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-default" type="submit" name="submitAll" value="-- แยกตามกลุ่มงาน --"><span class="glyphicon glyphicon-list" autofocus></span> แสดงทั้งหมด</button>
                                </div>
                            </form>

                        </div> <!-- /.col-md-12 -->











                        <?php
                        $divSiteQry = mysqli_query($connection, $divSiteQS);
                        $divSiteCount = mysqli_num_rows($divSiteQry);
                        if ($divSiteCount == 0) { //ค้นแล้วเจอ 0 รายการ
                            ?>
                            <div><b>คำค้น: </b><?= $qryMsg; ?> (0 รายการ)</div>
                        <?php } else { ?>
                            <div class="col-md-12">
                                <div><b>คำค้น: </b><?= $qryMsg ?> (<?= $divSiteCount ?> รายการ)</div>
                                <table id="example" class="table table-bordered table-condensed table-striped table-hover">
                                    <thead>
                                        <tr align="center">
                                            <?php
                                            foreach ($tableHeader as $value) {
                                                echo "<th>" . $value . "</th>";
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        while ($rowDivSite = mysqli_fetch_assoc($divSiteQry)) {
                                            ?>
                                            <tr align="center">
                                                <td align="left">
                                                    <a href="show_item.php?detail=<?= $rowDivSite['detail'] ?>&owner=<?= $rowDivSite['owner'] ?>&suffix=<?= $rowDivSite['suffix'] ?>" target="_blank">
                                                        <?= $rowDivSite[$tableData[0]] ?>
                                                    </a>
                                                </td>
                                                <td nowrap><?= $rowDivSite[$tableData[1]] . ' ' . $rowDivSite[$tableData[2]] ?></td>
                                                <td><?= $rowDivSite[$tableData[3]] ?></td>
                                                <?php if (isset($rowDivSite[$tableData[4]])) { ?>
                                                    <td><?= $rowDivSite[$tableData[4]] ?></td>
                                                <?php } ?>
                                                <?php if (isset($rowDivSite[$tableData[5]])) { ?>
                                                    <td><?= $rowDivSite[$tableData[5]] ?></td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div> <!-- /.col-md-12 -->
                        <?php } ?>














                    </div><!-- Main container -->
                </div> <!-- /.col-md-10 -->

            </div> <!-- /.row -->



            <?php include 'main_script.php'; ?>

            <script>
                $(document).ready(function () {
                    var table = $('#example').DataTable({
                        dom:
                                "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                                "<'row'<'col-sm-12'tr>>" +
                                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'print', 'colvis']
                    });


                    table.buttons().container()
                            .appendTo($('#example_wrapper .col-sm-6:eq(0)'));
                });
            </script>

    </body>
</html>
