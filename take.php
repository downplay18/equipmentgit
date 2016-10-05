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


        <?php
        /*
          require("connection.php");
          $strSQL = "SELECT * FROM `item` WHERE `adder` LIKE '" . $_SESSION['name'] . "'";
          $itemTakeQuery = mysqli_query($connection, $strSQL) or die("item_take.php คิวรี่ล้มเหลว!");
          $itemTakeResult = mysqli_fetch_array($itemTakeQuery);

          echo '<br/>$itemTakeResult = <br/>';
          print_r($itemTakeResult);
         */
        ?>

        <!-- MAIN CONTAINER, EDIT BOX COLUMN -->
        <div class="container-fluid">

            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> หน้าแรก</a></li>
                <li><a href="_login_user.php">รายการหลัก</a></li>
                <li class="active">เบิกใช้งาน</li>
            </ol> <!-- /breadcrumb -->

            <div class="page-header">
                <h2>เบิกใช้งาน <small>บันทึกการเบิกจ่ายเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง</small></h2>
            </div>

            <div class="row">

                <!-- ขวา -->
                <form id="takeForm" action="take_process.php" method="post">
                    <div class="col-md-8">
                        <div class="alert alert-default">

                            <table class="table table-bordered">
                                <col width="60%"> <!-- detail -->
                                <col width="10%"> <!-- qty --> 
                                <col width="10%"> <!-- slipSuffix -->
                                <col width="20%"> <!-- worker -->
                                <tr bgcolor="#ffd1b3">
                                    <th>รายการ</th>
                                    <th>เบิกจำนวน</th>
                                    <th>หน่วย</th>    
                                    <th>ผู้นำไปใช้</th>      
                                </tr>

                                <tr>
                                    <td><input class="form-control" type='text' id='varDetail_1' name='varDetail[]'/></td>
                                    <td><input class="form-control" type='number' id='var_quantity_1' name='var_quantity[]' required/> </td>
                                    <td><input class="form-control" type='text' id='var_suffix_1' name='var_suffix[]' required readonly/> </td>



                                    <td>

                                <div class="form-group">
                                  
                                    <select class="form-control">
                                        <?php
                                        //เรียก list ของกลุ่มงานทั้งหมดออกมา
                                        $siteQS = "SELECT `wname` FROM `worker` GROUP BY `wname`";
                                        $siteQry = mysqli_query($connection, $siteQS);
                                        while ($rowSite = mysqli_fetch_assoc($siteQry)) {
                                            ?>
                                            <option><?php echo $rowSite['wname'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                        </td>

                                </tr>
                            </table>

                            <div class="col-md-12" align="center">
                                <button id="takeBtn" class="btn btn-lg btn-warning" type="submit">
                                    <span class="glyphicon glyphicon-minus"></span>&nbsp;ลงบัญชีเบิก
                                </button>
                            </div>

                        </div> <!-- /.alert -->
                    </div> <!-- /.col-md-6 -->
                </form>

                <!-- ซ้าย -->
                <div class="col-md-4">

                    <div class="alert alert-warning">
                        <?php
                        $takeQS = "SELECT * FROM `item_take_record` ORDER BY `take_id` DESC LIMIT 10;";
                        $takeQry = mysqli_query($connection, $takeQS) or die("index takeQS คิวรี่ล้มเหลว<br/>" . mysql_error());

                        while ($takeRow = mysqli_fetch_assoc($takeQry)) {
                            echo "<kbd>" . $takeRow['take_id'] . "</kbd> <b>[</b>" . $takeRow['take_detail'] . "<b>]</b> จำนวน " . $takeRow['take_qty']
                            . " " . $takeRow['take_suffix'] . "<code>" . " " . $takeRow['taker'] . "("
                            . date("d/m/Y", strtotime($takeRow['take_date'])) . " " . $takeRow['take_time'] . ")</code><br/>";
                        }
                        ?>
                    </div> <!-- /.col-md-4 -->

                </div> <!-- /.row -->







            </div> <!-- /MAIN CONTAINER -->


            <?php include 'main_script.php'; ?>
            <link  href="css/jquery-ui-1.12.0.css" rel="stylesheet">
            <script src="js/jquery-ui.js"></script>
            <script src="js/autocTake.js" type="text/javascript"></script>

            <script> /*PREVENT DOUBLE SUBMIT: ทำให้ปุ่ม submit กดได้ครั้งเดียว ป้องกับปัญหาเนต lag แล้ว user กดเบิ้ล มันจะทำให้ส่งค่า 2 เท่า */
                $(document).ready(function () {
                    $("#takeForm").submit(function () {
                        $("#takeBtn").attr("disabled", true);
                        return true;
                    });
                });
            </script>

    </body>
</html>
