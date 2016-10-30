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

$keyMsg = array();
?>

<?php
if ($_POST['newKeyItem'] != "") {
    //คิวรี่เช็คว่ามันซํ้าในdbหรือเปล่า
    $nkCheckQry = mysqli_query($connection, "SELECT `key_detail` FROM `key_item` WHERE `key_detail`='" . $_POST['newKeyItem'] . "'");
    $num_rows = mysqli_num_rows($nkCheckQry);
    if ($num_rows) { //เช็คแล้วเจอซํ้า
        array_push($keyMsg, "<font color='red'>ชื่อซํ้า! มีอยู่แล้วในฐานข้อมูล!</font>");
    } else { //ไม่เจอซํ้า เพิ่มเข้าไปใน :key_item
        $keyItemAddQry = mysqli_query($connection, "INSERT INTO `key_item` (`key_detail`) VALUES ('" . $_POST['newKeyItem'] . "')");
        array_push($keyMsg, "<font color='blue'>เพิ่มรายการ " . $_POST['newKeyItem'] . " ในฐานข้อมูลสำเร็จ!</font>");
    }
}
?>


<html>
    <head>

        <title>ADMIN</title>
        <!-- Bootstrap Core CSS -->
        <?php require 'main_head.php'; ?>
    </head>

    <body>
        <?php
        include 'navbar.php';
        /*
          echo 'SESSION = ';
          print_r($_SESSION);
          echo '<br/>POST = <br/>';
          print_r($_POST); */
        ?>

        <div class="row">

            <div class="col-md-2 sidebar">
                <?php
                //status bar
                include 'sidebar.php';
                ?>
            </div>



            <div class="col-md-10">

                <!-- main container -->
                <div class="container-fluid">

                    <div class="page-header">
                        <h2>รายชื่อเครื่องมือเครื่องใช้ทั้งหมด <small></small></h2>
                    </div>


                    <div class="row">

                        <div class="col-md-8">
                            <div class="alert alert-default">
                                <div align="center"><h4>รายชื่อเครื่องมือเครื่องใช้ทั้งหมด</h4></div>
                                <?php
                                $keyiQry = mysqli_query($connection, "SELECT * FROM `key_item`");
                                /*
                                  while ($rowKeyi = mysqli_fetch_assoc($keyiQry)) {
                                  echo $rowKeyi['key_id'] . " " . $rowKeyi['key_detail'] . "<br/>";
                                  } */
                                ?>
                                <table id="example" class="display table table-bordered table-condensed table-striped table-hover">
                                    <col width="10%">
                                    <thead>
                                        <tr align="center">
                                            <th>ID</th>
                                            <th>รายการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        while ($rowKeyi = mysqli_fetch_assoc($keyiQry)) {
                                            ?>
                                            <tr align="center">
                                                <td><?= $rowKeyi['key_id'] ?></td>
                                                <td><?= $rowKeyi['key_detail'] ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>

                            </div> <!-- /.alert -->
                        </div> <!-- /.col-md-? -->


                        <!-- เพิ่มชื่อเครื่องมือเครื่องใช้ -->
                        <div class="col-md-4">
                            <div class="alert alert-warning">

                                <form id="mainForm" action="" method="post">
                                    <h4>เพิ่มชื่อเครื่องมือเครื่องใช้</h4>
                                    ***เพิ่มชื่อเครื่องมือเครื่องใช้ใหม่ โปรดตรวจสอบให้แน่ใจว่าไม่มีรายการซํ้าซ้อน <br/><br/>
                                    <?php
                                    foreach ($keyMsg as $msg) {
                                        echo "<p align='center' style='font-size: 135%'>" . $msg . "</p><br/>";
                                    }
                                    unset($_POST['newKeyItem']);
                                    ?>
                                    <input class="form-control" type="text"  name="newKeyItem" placeholder="ชื่อเครื่องมือเครื่องใช้ใหม่ที่ต้องการเพิ่ม" maxlength="100" autocomplete="off"/>
                                    <div class="form-group" align="center" style="padding: 20px;">
                                        <button id="submitBtn" class="btn btn-lg btn-danger" type="submit">
                                            <span class="glyphicon glyphicon-ok-sign"></span>&nbsp;ตกลง
                                        </button>
                                    </div>
                                </form>

                            </div> <!-- /.alert-warning -->

                                <div class = "alert alert-danger"> 
                                    <span class = "label label-warning">คำเตือน!</span> โปรดตรวจสอบให้แน่ใจว่าไม่มีรายการซํ้าที่คล้ายกัน ด้วยการเว้น "&nbsp;&nbsp;" 2 ช่อง<br/>
                                    <span class = "label label-warning">คำเตือน!</span> การเว้นช่องว่าง " " ไม่ควรเว้น 2 ช่อง<br/>
                                </div> 


                        </div> <!-- /.col-md-? -->

                    </div> <!--/.row -->
                </div> <!-- /main container -->
            </div> <!-- /.col-md-10 -->





        </div> <!-- /.row -->



        <?php require("main_script.php"); ?>
        <script src="js/autocWithAddRow_urgent.js" type="text/javascript"></script>

        <script> /*PREVENT DOUBLE SUBMIT: ทำให้ปุ่ม submit กดได้ครั้งเดียว ป้องกับปัญหาเนต lag แล้ว user กดเบิ้ล มันจะทำให้ส่งค่า 2 เท่า */
            $(document).ready(function () {
                $("#mainForm").submit(function () {
                    $("#submitBtn").attr("disabled", true);
                    return true;
                });
            });
        </script>

        <script> /*PREVENT DOUBLE SUBMIT: ทำให้ปุ่ม submit กดได้ครั้งเดียว ป้องกับปัญหาเนต lag แล้ว user กดเบิ้ล มันจะทำให้ส่งค่า 2 เท่า */
            $(document).ready(function () {
                $("#mainForm").submit(function () {
                    $("#submitBtn").attr("disabled", true);
                    return true;
                });
            });
        </script>

        <script>
            $(document).ready(function () {
                $('#example').DataTable();
            });
        </script>

    </body>
</html>
