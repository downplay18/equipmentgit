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
        <?php include 'main_head.php'; ?>  
    </head>

    <body>

        <?php
        /* navbar */
        /* ไม่ใช้ case unauthen เพราะไม่มีสิทธิ์เข้าหน้านี้อยู่แล้ว */
        if (!isset($_SESSION['user_id'])) {
            include("navbar_unauthen.php");
        } else {
            include("navbar_authen.php");
        }

        /*
          echo '<br/>';
          echo 'SESSION = ';
          print_r($_SESSION);
          echo '<br/>loginResult =<br/>';
          print_r($loginResult);
          echo '<br/>POST = <br/>';
          print_r($_POST); */
        ?>

        <!-- main container -->
        <div class="container-fluid">

            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> หน้าแรก</a></li>
                <li><a href="_login_user.php">รายการหลัก</a></li>
                <li class="active">เพิ่มใบสั่งซื้อ</li>
            </ol> <!-- /breadcrumb -->

            <div class="page-header">
                <h2>เพิ่มใบสั่งซื้อ</h2>
            </div>

            - ซื้อแล้วใช้เลยทันทีหรือเปล่า(100% ไม่มีเก็บไว้)? 
            

        </div> <!-- /main container -->







        <script src="js/bootstrap.min.js"></script>


    </body>

</html>