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

    </head>

    <body>
        <?php
        /* navbar */
        /* ไม่ใช้ case unauthen เพราะไม่มีสิทธิ์เข้าหน้านี้อยู่แล้ว */
        include 'navbar.php';


        echo '<br/>';
        echo 'SESSION = ';
        print_r($_SESSION);
        echo '<br/>loginResult =<br/>';
        print_r($loginResult);
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


                    <?php require("connection.php"); ?>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="selDiv">ค้นหาโดยกลุ่มงาน</label>
                                <select class="form-control" id="selDiv">
                                    <option>-- เลือกกลุ่มงาน --</option>
                                    <?php
                                    //เรียก list ของกลุ่มงานทั้งหมดออกมา
                                    $divQS = "SELECT `listDivision` FROM `list_division`";
                                    $divQry = mysqli_query($connection, $divQS);
                                    while ($rowDiv = mysqli_fetch_assoc($divQry)) {
                                        ?>
                                        <option><?php echo $rowDiv['listDivision'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="selSite">ค้นหาโดยสถานที่ใช้งาน</label>
                                <select class="form-control" id="selSite">
                                    <option>-- เลือกสถานที่ --</option>
                                    <?php
                                    //เรียก list ของกลุ่มงานทั้งหมดออกมา
                                    $siteQS = "SELECT `listBuilding` FROM `list_building`";
                                    $siteQry = mysqli_query($connection, $siteQS);
                                    while ($rowSite = mysqli_fetch_assoc($siteQry)) {
                                        ?>
                                        <option><?php echo $rowSite['listBuilding'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>


                    </div><!-- Main container -->
                </div> <!-- /.col-md-10 -->

            </div> <!-- /.row -->







            <?php
            include 'main_script.php';
            ?>

            <!--Live Search Script -->
            <script>
                var $search_rows = $('#search_table tr');
                $('#search_live').keyup(function () {
                    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

                    $search_rows.show().filter(function () {
                        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                        return !~text.indexOf(val);
                    }).hide();
                });
            </script><!-- /Live Search Script -->

    </body>
</html>
