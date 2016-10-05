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

        /*
          echo '<br/>';
          echo 'SESSION = ';
          print_r($_SESSION);
          echo '<br/>loginResult =<br/>';
          print_r($loginResult);
          echo '<br/>POST = <br/>';
          print_r($_POST); */
        ?>

        <!-- Main container -->
        <div class="container-fluid">





            <!--
            <h3 class="page-header">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง <small>กองพัฒนาด้านเทคโนโลยีโรงไฟฟ้าถ่านหินและเหมือง (กพทถ-ห.)</small></h3>
            -->

            <div class="row">
                <div class="col-md-2 sidebar">
                    <?php include 'sidebar.php'; ?>
                </div>

                <div class="col-md-10">
                    <!-- MAIN CONTAINER, EDIT BOX COLUMN -->
                    <div class="container">

                        

                    </div> <!-- /MAIN CONTAINER -->
                </div> <!--col-md-10-->
            </div> <!-- /.row -->



        </div><!-- Main container -->









        <?php include 'main_script.php'; ?>
        <script>
            //Morris charts snippet - js

            $.getScript('http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js', function () {
                $.getScript('http://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.min.js', function () {

                    Morris.Area({
                        element: 'area-example',
                        data: [
                            {y: '1.1.', a: 100, b: 90},
                            {y: '2.1.', a: 75, b: 65},
                            {y: '3.1.', a: 50, b: 40},
                            {y: '4.1.', a: 75, b: 65},
                            {y: '5.1.', a: 50, b: 40},
                            {y: '6.1.', a: 75, b: 65},
                            {y: '7.1.', a: 100, b: 90}
                        ],
                        xkey: 'y',
                        ykeys: ['a', 'b'],
                        labels: ['Series A', 'Series B']
                    });

                    Morris.Line({
                        element: 'line-example',
                        data: [
                            {year: '2010', value: 20},
                            {year: '2011', value: 10},
                            {year: '2012', value: 5},
                            {year: '2013', value: 2},
                            {year: '2014', value: 20}
                        ],
                        xkey: 'year',
                        ykeys: ['value'],
                        labels: ['Value']
                    });

                    Morris.Donut({
                        element: 'donut-example',
                        data: [
                            {label: "Android", value: 12},
                            {label: "iPhone", value: 30},
                            {label: "Other", value: 20}
                        ]
                    });

                    Morris.Bar({
                        element: 'bar-example',
                        data: [
                            {y: 'Jan 2014', a: 100},
                            {y: 'Feb 2014', a: 75},
                            {y: 'Mar 2014', a: 50},
                            {y: 'Apr 2014', a: 75},
                            {y: 'May 2014', a: 50},
                            {y: 'Jun 2014', a: 75}
                        ],
                        xkey: 'y',
                        ykeys: ['a'],
                        labels: ['Visitors', 'Conversions']
                    });

                });
            });
        </script>


    </body>
</html>
