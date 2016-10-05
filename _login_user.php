<?php
//var_dump($_SESSION);
session_start();
require_once 'connection.php';

include 'root_url.php';
if (!isset($_SESSION['user_id'])) {
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
        include("navbar.php");

        echo '<br/>';
        echo 'SESSION = ';
        print_r($_SESSION);
        echo '<br/>loginResult =<br/>';
        print_r($loginResult);
        echo '<br/>POST = <br/>';
        print_r($_POST);
        ?>

        <!-- Main container -->
        <div class="container-fluid">

            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li class="active"><span class="glyphicon glyphicon-home"></span> หน้าแรก</li>
            </ol> <!-- /breadcrumb -->    

            <div class="row">
                <div class="col-md-2 sidebar">
                    <div class="list-group">
                        <a href="#" class="list-group-item active" align="center"><span class="glyphicon glyphicon-home"></span> หน้าหลัก</a>
                        <a href="<?= $root_url ?>/show.php" class="list-group-item"><span class="glyphicon glyphicon-search"></span> สืบค้น<span class="badge">999 items</span></a>
                        <a href="<?= $root_url ?>/add.php" class="list-group-item"><span class="glyphicon glyphicon-plus"></span> เพิ่มใบสั่งซื้อ(แบบปกติ)</a>
                        <a href="<?= $root_url ?>/add_urgent.php" class="list-group-item"><span class="glyphicon glyphicon-plus"></span> เพิ่มใบสั่งซื้อ(แบบเร่งด่วน)</a>
                        <a href="<?= $root_url ?>/take.php" class="list-group-item"><span class="glyphicon glyphicon-minus-sign"></span> เบิกใช้งาน</a>
                    </div>   

                </div>


                <div class="col-md-10" style="padding: 80px">
                    <div class="container-fluid">
                        
                        <div class="col-md-4 col-md-offset-4">
                            <div class="alert alert-info">
                                รายการสนใจพิเศษ
                            </div>
                        </div>
                        
                    </div> <!-- /.container-fluid -->
                </div> <!-- /.col-md-10 -->


            </div>




        </div> <!-- /.container-fluid -->






        <!--Script -->
        <?php include 'main_script.php'; ?>
        <script src="js/jquery.table2excel.js" type="text/javascript"></script>

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
