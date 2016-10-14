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
          echo 'SESSION = ';
          print_r($_SESSION);
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
                        <h2>เพิ่มใบสั่งซื้อ <small>บันทึกใบสั่งซื้อ(แบบเร่งด่วน)</small></h2>
                    </div>

                    - ซื้อแล้วใช้เลยทันทีหรือเปล่า(100% ไม่มีเก็บไว้)? 
                </div> <!-- /.row -->



                <?php include 'main_script.php'; ?>
                <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jqc-1.12.3/dt-1.10.12/datatables.min.js"></script>

                </body>
                </html>
