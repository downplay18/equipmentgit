<?php
include("root_url.php");
?>

<!-- Navigation -->
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="#">กพทถ-ห.</a>
            <!--สร้างปุ่มเมนู สำหรับมุมมองแบบ responsive-->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <!--ปุ่ม HOME -->
                <li class="active"><a href="index.php"><span class="glyphicon glyphicon-knight"></span> &nbsp;ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง</a></li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">ลิงค์ที่เกี่ยวข้อง<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= $root_url . '/static_userList.php' ?>" target="_blank">รายชื่อพนักงาน</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="mmtc.egat.co.th" target="_blank">MMTC Home Page</a></li>
                        <li><a href="http://10.249.50.18/stock/" target="_blank">เว็บครุภัณฑ์</a></li>

                    </ul>
                </li>

                <li><a href="#">คู่มือ</a></li>
            </ul> 

            <?php
            /* ใช้แสดงชื่อผู้ล็อกอิน */ /* `user_id`,`password`,`nname` */
            include 'connection.php';
            $strSQL = "SELECT * FROM user WHERE user_id = '" . $_SESSION['user_id'] . "' ";
            $objQuery = mysqli_query($connection, $strSQL) or die("Error: " . mysqli_error($connection));
            $loginResult = mysqli_fetch_array($objQuery); /* ไม่ได้ SELECT Status เพราะ ใช้ค่าจาก $_SESSION */
            ?>

            <div class="navbar-form navbar-left">

            </div>

            <!-- ปุ่มสำหรับ user -->
            <div class="btn-group navbar-right">

                <!-- Sign Out กลับไปหน้า Index -->
                <a class="btn btn-default navbar-btn" href="_logout.php" role="button">
                    <span class="glyphicon glyphicon-log-out"></span>&nbsp;ออกจากระบบ
                </a>

            </div><!-- /ปุ่มสำหรับ user -->

            <!-- แสดงชื่อผู้ใช้งาน -->
            <p class="navbar-text navbar-right">
                <?php
                if (empty($loginResult['division'])) {
                    echo "ยังไม่ระบุสังกัด";
                } else {
                    echo $loginResult['division'];
                }
                ?> 
                <a href="_login_update.php" target="" class="navbar-link"><?= "[" . $loginResult["user_id"] . "] " . $loginResult["name"] . "</a> (" . $_SESSION["status"] . ") &nbsp;" ?></p>

        </div>
    </div>
</nav>

