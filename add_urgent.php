<?php
//var_dump($_SESSION);
session_start();
require_once 'connection.php';

include 'root_url.php';
if ($_SESSION['user_id'] == "") {
    header("Location: $root_url/index.php", true, 302);
    exit();
}
if($_SESSION['status']!=KEY) {
    header("Location: $root_url/index.php", true, 302);
}
?>

<html>
    <head>
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


        <div class="row">

            <div class="col-md-2 sidebar">
                <?php include 'sidebar.php'; ?>
            </div>

            <div class="col-md-10">

                <!-- main container -->
                <div class="container-fluid">

                    <div class="page-header">
                        <h2>เพิ่มใบสั่งซื้อ <small>บันทึกใบสั่งซื้อ(แบบเร่งด่วน)</small></h2>
                    </div>

                    - ซื้อแล้วใช้เลยทันทีหรือเปล่า(100% ไม่มีเก็บไว้)? 


                </div> <!-- /main container -->
            </div> <!-- /.col-md-10 -->

        </div> <!-- /.row -->






        <script src="js/bootstrap.min.js"></script>


    </body>

</html>