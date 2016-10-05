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

            <!-- function container -->
            <div class="row">

                <div class="col-md-3"></div>
                <div class="col-md-6" align="center">
                    <?php if($_SESSION['status'] == 'KEY' || $_SESSION['status'] == 'ADMIN') { ?>
                    <a class="btn btn-success" href="add.php" target="_blank"><span class="glyphicon glyphicon-plus-sign"></span> เพิ่มใบสั่งซื้อ(ปกติ)</a>
                    <a class="btn btn-success" href="add_urgent.php" target="_blank"><span class="glyphicon glyphicon-plus-sign"></span> เพิ่มใบสั่งซื้อ(เร่งด่วน)</a>
                    <?php } ?>
                    <a class="btn btn-warning" href="take.php" target="_blank"><span class="glyphicon glyphicon-minus-sign"></span> เบิกใช้งาน</a>
                    <a class="btn btn-info" href="show.php" target="_blank"><span class="glyphicon glyphicon-search"></span> สืบค้น</a>
                </div>
                <div class="col-md-3"></div>
                
                <div class="col-md-12" style="padding: 100px">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 alert alert-info" align="center">
                        <br/>มีข้อขัดข้อง ติดต่อผู้ดูแลระบบ
                        <br/>XXXXXXXX
                        <br/>
                        <br/>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div><!--/.row -->




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
