<?php
//var_dump($_SESSION);
session_start();
require_once 'connection.php';

include 'root_url.php';
if ($_SESSION['user_id'] == "") {
    header("Location: $root_url/index.php", true, 302);
    exit();
}
if ($_SESSION['status'] != "BOSS") {
    header("Location: $root_url/_login_check.php", true, 302);
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


        <div class="row">
            <div class="col-md-2 sidebar">
                <?php include 'sidebar.php'; ?>
            </div>


            <div class="col-md-10">
                <div class="container-fluid">
                    <?php
                    $configQS = "SELECT `cname`,`mykey`,`favworker` FROM `user_config` WHERE `cname` LIKE '" . $_SESSION['name'] . "'";
                    $configQry = mysqli_query($connection, $configQS) or die("_login_boss query error: " . mysqli_error($connection));
                    $resultConfig = mysqli_fetch_assoc($configQry);
                    if (count($resultConfig['cname']) == 0 || $resultConfig['mykey'] == "") {
                        echo "ยังไม่มีผู้ดูแลประจำกลุ่มงาน:";
                        ?>
                        <form action="_login_boss_selkey.php" method="post">
                            <select class="form-control" name="boss_selkey" style="width: 20em">
                                <?php
                                echo "<option>-- เลือกผู้ดูแลที่นี่ --</option>";
                                $ulistQS = "SELECT `name` FROM `user` WHERE `division` LIKE '" . $_SESSION['division'] . "'"
                                        . " AND `status` LIKE 'USER'";
                                $ulistQry = mysqli_query($connection, $ulistQS);
                                while ($rowUlist = mysqli_fetch_assoc($ulistQry)) {
                                    echo "<option>" . $rowUlist['name'] . "</option>";
                                }
                                ?>
                            </select>
                            <div style="padding: 5px">
                                <button class="btn btn-success" type="submit">
                                    <span class="glyphicon glyphicon-check"></span>&nbsp;ตกลง
                                </button>
                            </div>
                        </form>
                    <?php } else { ?>
                        <!--แสดง/ลบ ผู้ดูแลประจำกลุ่มงาน -->
                        <form action="_login_boss_deleteSelkey.php" method="get" style="margin: 0; padding: 0">
                            <b>ผู้ดูแลประจำกลุ่มงานของคุณคือ </b>
                            <?php
                            $cuserQS = "SELECT `mykey` FROM `user_config` WHERE `cname` LIKE '" . $_SESSION['name'] . "'";
                            $cuserQry = mysqli_query($connection, $cuserQS) or die("cuserQS fail: " . mysqli_error($connection));
                            $cuserResult = mysqli_fetch_assoc($cuserQry);
                            echo $cuserResult['mykey'];
                            ?>
                            <input class="hidden" name="getmykey" value="<?= $cuserResult['mykey'] ?>"/>
                            <button class="btn btn-default" type="submit">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </form>
                    <?php } ?>
                </div> <!-- /.container-fluid -->
            </div> <!-- /.col-md-10 -->

        </div> <!-- /.row -->









        <?php include 'main_script.php'; ?>

    </body>
</html>
