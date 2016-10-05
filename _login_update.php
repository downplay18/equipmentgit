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
        <?php include 'main_head.php'; ?>
    </head>

    <body>

        <?php
        include("navbar.php");
        /*
          echo "FROM _login_update.php<br/>";
          echo "dump POST = ";
          var_dump($_POST);
          echo "<br/>dump SESSION = <br/>";
          var_dump($_SESSION);
          echo '<br/>LoginResult = <br/>';
          print_r($loginResult); */
        ?>

        <div class="row">
            <div class="col-md-2 sidebar">
                <?php include 'sidebar.php'; ?>
            </div>

            <div class="col-md-10">
                <!-- MAIN CONTAINER, EDIT BOX COLUMN -->
                <div class="container">

                    <!-- ส่วนหัว -->
                    <div class="page-header">
                        <h2>แก้ไขข้อมูลพนักงาน <small><?= "@" . $_SESSION['user_id'] . " " . $loginResult["name"] . " (" . $loginResult['rank'] . ")" ?></small></h2>
                    </div>

                    <!-- MAIN EDIT BOX COLUMN -->
                    <form id="mainForm" action="_login_update_process.php" method="post">
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
                                <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_name" value="<?= $loginResult["name"]; ?>"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-3" align="right" style="padding:0.4em"><b>รหัสผ่าน</b></div>
                                <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["password"]; ?></div>
                                <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_pwd" value="<?= $loginResult["password"]; ?>"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-3" align="right" style="padding:0.4em"><b>สังกัด</b></div>
                                <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult['division']; ?></div>


                                <div class="col-md-6">

                                    <select class="form-control" name="lupdate_div">
                                        <?php
                                        $divQS = "SELECT `listDivision` FROM `list_division`";
                                        $divQry = mysqli_query($connection, $divQS);
                                        while ($rowDiv = mysqli_fetch_assoc($divQry)) {
                                            ?>
                                            <option><?php echo $rowDiv['listDivision'] ?></option>
                                        <?php } ?>

                                    </select>

                                </div>


                            </div>

                            <div class="col-md-12">
                                <div class="col-md-3" align="right" style="padding:0.4em"><b>ตำแหน่ง</b></div>
                                <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["rank"]; ?></div>
                                <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_rank" value="<?= $loginResult["rank"]; ?>"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-3" align="right" style="padding:0.4em"><b>ที่อยู่</b></div>
                                <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["building"]; ?></div>
                                <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_building" value="<?= $loginResult["building"]; ?>"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-3" align="right" style="padding:0.4em"><b>ห้อง</b></div>
                                <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["room"]; ?></div>
                                <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_room" value="<?= $loginResult["room"]; ?>"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-3" align="right" style="padding:0.4em"><b>เบอร์โทรศัพท์โต๊ะทำงาน</b></div> <!--ครุภัณฑ์/เครื่องมือเครื่องใช้-->
                                <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["office_tel"]; ?></div>
                                <div class="col-md-4"><input type="text" class="form-control input-sm" name="lupdate_tel" value="<?= $loginResult["office_tel"]; ?>"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-3" align="right" style="padding:0.4em"><b>สถานะ</b></div> <!--printer/คอมฯ/รถยนต์/etc.-->
                                <div class="col-md-3"><p type="text" class="form-control-static"><?= $loginResult["status"]; ?></div>
                                <div class="col-md-4"></div>
                                <div class="col-md-2"></div>
                            </div>

                            <!-- input button -->
                            <div id="submitBtn" class="form-group col-md-12" align="center">
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
            </div> <!--col-md-10-->
        </div> <!-- /.row -->

        <?php include 'main_script.php'; ?>

        <script> /* PREVENT DOUBLE SUBMIT: ทำให้ปุ่ม submit กดได้ครั้งเดียว ป้องกับปัญหาเนต lag แล้ว user กดเบิ้ล มันจะทำให้ส่งค่า 2 เท่า */
            $(document).ready(function () {
                $("#mainForm").submit(function () {
                    $("#submitBtn").attr("disabled", true);
                    return true;
                });
            });
        </script>

    </body>
</html>
