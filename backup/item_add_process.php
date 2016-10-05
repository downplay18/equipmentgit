<?php
session_start();
if ($_SESSION['user_id'] == "") {
    echo "<br/>โปรดยืนยันตัวตนก่อน !";
    exit();
}

if ($_SESSION['status'] != "USER") {
    echo "<br/>สำหรับ -พนักงาน- เท่านั้น!";
    exit();
}

include 'item_headerInfo.php';

var_dump($_POST);
var_dump($_SESSION);

/* นับจำนวนแถวที่ user กดเพิ่มเข้ามา โดยใช้ var_detail เป็นตัวนับ */
$row_count = count($_POST['var_detail']);

/* บังคับให้ "ชื่อผู้เพิ่มรายการ" เป็น ชื่อคนที่ล็อกอินในขณะนั้น */
$_POST['var_adder'] = $_SESSION['name'];
$addItemStatement = "";

/* for ของ $row_count (aka. $rc) */
for ($rc = 0; $rc < $row_count; $rc++) { /* 1 $rc คือ 1 แถวของรายการใน 1 ใบเสร็จ */
    if ($_POST['var_detail'][$rc] != "") { /* เช็คที่ detail เพราะมันเป็น primary key */
        $addItemStatement .= "INSERT INTO `item` (`" . $item_headerInfo[0][1] . "`";
        for ($i = 1; $i < $item_size; $i++) {
            if ($_POST[$item_headerInfo[$i][2]] != "") {
                $addItemStatement .= ",`" . $item_headerInfo[$i][1] . "`";
            }
        }
        $addItemStatement .= ")";

        $addItemStatement .= " VALUES ('" . $_POST[$item_headerInfo[0][2]] . "'"; /* slip number ไม่เป็น array */

//for ($k = 1; $k < $item_size; $k++) {
        $addItemStatement .= ",'" . $_POST[$item_headerInfo[1][2]] . "'"; /* slip_date ไม่เป็น array */
        $addItemStatement .= ",'" . $_POST[$item_headerInfo[2][2]][$rc] . "'"; /* detail */
        $addItemStatement .= ",'" . $_POST[$item_headerInfo[3][2]][$rc] . "'"; /* quantity */
        $addItemStatement .= ",'" . $_POST[$item_headerInfo[4][2]][$rc] . "'"; /* suffix */
        $addItemStatement .= ",'" . $_POST[$item_headerInfo[5][2]][$rc] . "'"; /* price */
        $addItemStatement .= ",'" . $_POST[$item_headerInfo[6][2]] . "'";  /* adder */
//{
        $addItemStatement .= ");";
    }
}

echo "<br/></br>additem= " . $addItemStatement;
?>

<html>

    <head>
        <?php include 'main_head.php'; ?>  
    </head>

    <body>

        <?php
//include("navbar_unauthenticated.php");
        /* เลือกแสดง navbar แบบ ล็อกอินแล้ว(authenticated) และ ยังไม่ล็อกอิน(unauthenticated) */
        if (!isset($_SESSION['user_id'])) {
            include("navbar_unauthen.php");
        } else {
            include("navbar_authen.php");
        }
        ?>

        <?php
        require('connection.php');
        ?>

        <!-- breadcrumb -->
        <div class="container-fluid">

            <ol class="breadcrumb">
                <li><a href="index_item.php">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง กพทถ-ห.</a></li>
                <li><a href="_login_user.php">รายการของ <?= $loginResult['name'] ?></a></li>
                <li><a href="javascript:history.back()">เพิ่มรายการใหม่</a></li>
                <li class="active">เสร็จสิ้น</li>
            </ol> 
        </div><!-- /breadcrumb -->

        <div class="container">
            <?php if (mysqli_multi_query($connection, $addItemStatement)) { ?>
                <div align="center">
                    <h2>เพิ่มรายการในฐานข้อมูลแล้ว!</h2><br/>
                    <!-- เลขที่ใบเสร็จ -->
                    <?= $item_headerInfo[0][0] . ": " . $_POST['var_slipNo']; ?><br/>
                    <!-- วันที่ในใบเสร็จ -->
                    <?= $item_headerInfo[1][0] . ": " . $_POST['var_slipDate']; ?> (ปปปป-ดด-วว)<br/>
                </div>
                <br/>

                <table style="width: 100%">
                    <tr>
                        <?php for ($i = 2; $i < $item_size; $i++) { ?>
                            <td align="center"><u><?= $item_headerInfo[$i][0] ?></u></td>
                        <?php } ?>
                    </tr>

                    <?php for ($j = 0; $j < $row_count; $j++) { ?>
                        <tr>
                            <?php for ($k = 2; $k < $item_size; $k++) { ?>
                                <td align="center"><?= $_POST[$item_headerInfo[$k][2]][$j] ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </table>
                <?php
            } else {
                echo "Error: " . $addItemStatement . "<br>" . mysqli_error($connection);
            }
            ?>

            <!-- ปุ่มกลับ -->
            <div class="form-group" align="center" style="padding: 20px">

                <a class="btn btn-success btn-lg" href="item_add.php" role="button"> 
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;เพิ่มรายการอื่น
                </a>

                <a class="btn btn-warning btn-lg" href="_login_user.php" role="button"> 
                    <span class="glyphicon glyphicon-step-backward"></span>&nbsp;กลับไปหน้าแรก
                </a>

            </div><!-- /ปุ่มกลับ -->

        </div> <!-- /content container -->



        <!--Script -->
        <?php include 'main_script.php'; ?>

    </body>
</html>