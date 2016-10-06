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

                <!-- MAIN CONTAINER, EDIT BOX COLUMN -->
                <div class="container-fluid">

                    <div class="page-header">
                        <h2>เบิกใช้งาน <small>บันทึกการเบิกจ่ายเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง</small></h2>
                    </div>

                    <form id="takeForm" action="take_process.php" method="post">
                        <div class="col-md-8">

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
                                <button class="btn btn-sm btn-default" type="reset">
                                    <span class="glyphicon glyphicon-repeat"></span>&nbsp;รีเซ็ท
                                </button>
                                <button id="takeBtn" class="btn btn-lg btn-warning" type="submit">
                                    <span class="glyphicon glyphicon-minus"></span>&nbsp;ลงบัญชีเบิก
                                </button>
                            </div>

                        </div> <!-- /.col-md-8 -->
                    </form>


                    <!-- RIGHT alert box -->
                    <div class="col-md-4">

                        <div class="alert alert-warning">
                            <?php
                            $takeQS = "SELECT `iid`,`detail`,`suffix`,`quantity`,`type`,`owner` FROM `item`"
                                    . " WHERE `owner` LIKE '".$_SESSION['name']."' AND `type` LIKE 'normal';";
                            $takeQry = mysqli_query($connection, $takeQS) or die("index takeQS คิวรี่ล้มเหลว<br/>" . mysqli_error($connection));
                            
                            while ($takeRow = mysqli_fetch_assoc($takeQry)) {
                                echo "<kbd>" . $takeRow['iid'] . "</kbd> <b>[</b>" . $takeRow['detail'] . "<b>]</b> (<u>" . $takeRow['quantity']
                                . " " . $takeRow['suffix']."</u>)<br/>";
                            }
                            ?>
                        </div> 

                    </div> <!-- /.col-md-4 -->

                </div> <!-- /.MAIN CONTAINER -->
            </div> <!-- /.col-md-10 -->

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
