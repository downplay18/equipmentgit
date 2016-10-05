<?php
session_start();
include 'root_url.php';
?>

<html>

    <head>
        <?php include 'main_head.php'; ?>  
    </head>

    <body>

        <?php
        include("navbar.php");
        /*
          echo '<br/>';
          echo 'SESSION = ';
          print_r($_SESSION);
          //echo '<br/>loginResult =<br/>';
          //print_r($loginResult);
          echo '<br/>POST = <br/>';
          print_r($_POST); */
        ?>

        <div class="row">
            <div class="col-md-2 sidebar">
                <div class="list-group">
                    <a href="#" class="list-group-item active" align="center"><span class="glyphicon glyphicon-home"></span> หน้าหลัก</a>
                    <a href="<?= $root_url ?>/show.php" class="list-group-item"><span class="glyphicon glyphicon-search"></span> สืบค้น<span class="badge">999 items</span></a>
                    <a href="<?= $root_url ?>/add.php" class="list-group-item"><span class="glyphicon glyphicon-plus"></span> เพิ่มใบสั่งซื้อ(แบบปกติ)</a>
                    <a href="<?= $root_url ?>/add_urgent.php" class="list-group-item"><span class="glyphicon glyphicon-plus"></span> เพิ่มใบสั่งซื้อ(แบบเร่งด่วน)</a>
                    <a href="<?= $root_url ?>/take.php" class="list-group-item"><span class="glyphicon glyphicon-minus-sign"></span> เบิกใช้งาน</a>
                </div>   
            </div>


            <div class="col-md-10" style="padding: 80px">
                <div class="container-fluid">
                    <form action="_login_check.php" method="post" role="form" target="">

                        <div class="col-md-4 col-md-offset-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title" align="center">ระบบยืนยันตัวตน</h3>
                                </div>
                                <div class="panel-body">
                                    <form accept-charset="UTF-8" role="form">
                                        <fieldset>
                                            <div class="form-group">
                                                <input class="form-control" name="login_cid" placeholder="เลขพนักงาน" type="text" autocomplete="on" 
                                                       maxlength="6" size="6" pattern="[0-9]+" title="ตัวเลข 6 ตัว เท่านั้น!">
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" placeholder="รหัสผ่าน" name="login_pwd" type="password" value="">
                                            </div>
                                            <!--
                                            <div class="checkbox">
                                                <label>
                                                    <input name="remember" type="checkbox" value="Remember Me"> Remember Me
                                                </label>
                                            </div> -->
                                            <input class="btn btn-lg btn-success btn-block" type="submit" value="เข้าสู่ระบบ">
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form> <!-- /Sign In form -->
                </div> <!-- /.container-fluid -->
            </div> <!-- /.col-md-10 -->


        </div> <!-- /.row -->




        <!--Script -->
        <?php include 'main_script.php'; ?>




    </body>
</html>
