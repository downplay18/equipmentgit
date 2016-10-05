<?php
//var_dump($_SESSION);
session_start();
if ($_SESSION['user_id'] == "") {
    echo "โปรดยืนยันตัวตนก่อน !";
    exit();
}
?>

<html>

    <head>
        <?php include 'main_head.php'; ?>
    </head>

    <body>

        <?php require("connection.php"); ?>

        <?php include("navbar_authen.php"); ?>

        <?php
        /*
          echo "FROM _login_update.php<br/>";
          echo "dump POST = ";
          var_dump($_POST);
          echo "<br/>dump SESSION = <br/>";
          var_dump($_SESSION);
          echo '<br/>LoginResult = <br/>';
          print_r($loginResult); */
        ?>



        <!-- MAIN CONTAINER, EDIT BOX COLUMN -->
        <div class="container">

                <!-- ส่วนหัว+breadcrumb -->
                <div class="page-header">
                    <h2>แก้ไขข้อมูลพนักงาน <small><?= "@" . $_SESSION['user_id'] . " " . $loginResult["name"] . " (" . $loginResult['rank'] . ")" ?></small></h2>
                </div>

                <ol class="breadcrumb">
                    <li><a href="_login_user.php">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง กพทถ-ห.</a></li>
                    <li class="active">ระบบแก้ไขข้อมูลพนักงาน</li>
                </ol> 
                <!-- /ส่วนหัว+breadcrumb -->

            <!-- MAIN EDIT BOX COLUMN -->
            <form action="_login_update_process.php" method="post">
                <div class="col-lg-8">
                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>เลขพนักงาน</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["user_id"]; ?></div>
                        <div class="col-md-4"></div>
                        <div class="col-md-2"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>ชื่อพนักงาน</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["name"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_name" placeholder="เปลี่ยนชื่อพนักงาน <ที่นี่>"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>รหัสผ่าน</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["password"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_pwd" placeholder="เปลี่ยนรหัสผ่าน <ที่นี่>"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>ตำแหน่ง</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["rank"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_rank" placeholder="เปลี่ยนตำแหน่ง <ที่นี่>"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>ที่อยู่</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["building"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_building" placeholder="เปลี่ยนที่อยู่ <ที่นี่>"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>ห้อง</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["room"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_room" placeholder="เปลี่ยนห้อง <ที่นี่>"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>เบอร์โทรศัพท์โต๊ะทำงาน</b></div> <!--ครุภัณฑ์/เครื่องมือเครื่องใช้-->
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["office_tel"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_ot" placeholder="เปลี่ยนเบอร์โทรศัพท์ <ที่นี่>"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>สถานะ</b></div> <!--printer/คอมฯ/รถยนต์/etc.-->
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["status"]; ?></div>
                        <div class="col-md-4"></div>
                        <div class="col-md-2"></div>
                    </div>

                    <!-- input button -->
                    <div class="form-group col-md-12" align="center">
                        <button class="btn btn-lg btn-warning" type="submit">
                            <span class="glyphicon glyphicon-pencil"></span>&nbsp;แก้ไข
                        </button>
                    </div>  <!-- /input button -->

                </div> 
            </form>
            <!-- /MAIN EDIT BOX COLUMN --->

            <div class="alert alert-info col-md-4">
                <span class = "label label-info" role="alert">Info</span> สามารถเว้นว่างไว้ หากไม่ต้องการเปลี่นแปลง<br/>
            </div>

            <div class="alert alert-warning col-md-4">
                <span class = "label label-info" role="alert">Info</span> <b>ชื่อ</b> ควรตรงกับบัตรพนักงาน/บัตรประชาชน<br/>
                <span class = "label label-danger" role="alert">Info</span> <b>รหัสผ่าน</b> ไม่ควรเหมือนเลขพนักงาน<br/>
                <span class = "label label-info" role="alert">Info</span> <b>รหัสผ่าน</b> ควรมีความยาวอย่างน้อย 6 - 20 อักขระ<br/>
                <span class = "label label-warning" role="alert">Info</span> <b>ตำแหน่ง</b> ควรตรงกับประกาศ กฟผ.<br/>
                <span class = "label label-info" role="alert">Info</span> <b>เบอร์โต๊ะ</b> ควรเป็นปัจจุบันที่ใช้ติดต่อได้<br/>
            </div>

        </div> <!-- /MAIN CONTAINER -->


        <?php include 'main_script.php'; ?>
    </body>
</html>
