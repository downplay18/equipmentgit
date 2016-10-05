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
        if (!isset($_SESSION['user_id'])) {
            include("navbar_unauthen.php");
        } else {
            include("navbar_authen.php");
        }
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
                <!-- เอาไว้เวนระยะระหว่าง navbar กับ sidebar 
                <div class="mini-submenu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </div> -->

                <div class="list-group">
                    <a href="#" class="list-group-item active" align="center"><span class="glyphicon glyphicon-home"></span> หน้าหลัก</a>
                    <a href="<?= $root_url ?>/show.php" class="list-group-item"><span class="glyphicon glyphicon-search"></span> สืบค้น</a>
                    <a href="<?= $root_url ?>/add.php" class="list-group-item"><span class="glyphicon glyphicon-plus"></span> เพิ่มใบสั่งซื้อ(แบบปกติ)</a>
                    <a href="<?= $root_url ?>/add_urgent.php" class="list-group-item"><span class="glyphicon glyphicon-plus"></span> เพิ่มใบสั่งซื้อ(แบบเร่งด่วน)</a>
                    <a href="<?= $root_url ?>/take.php" class="list-group-item"><span class="glyphicon glyphicon-minus-sign"></span> เบิกใช้งาน</a>
                    <a href="#" class="list-group-item"><i class="fa fa-folder-open-o"></i> Lorem ipsum <span class="badge">14</span></a>
                </div>   

            </div>


            <div class="col-md-10" style="padding: 100px">
                <div class="container-fluid">
                    <form action="_login_check.php" method="post" role="form" target="">
                        <div class="col-md-4 alert alert-default" align="center">
                            เข้าสู่ระบบ
                            <br/><br/>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input type="text" class="form-control" name="login_cid" placeholder="เลขพนักงาน" autocomplete="on" 
                                       maxlength="6" size="6" pattern="[0-9]+" title="ตัวเลข 6 ตัว เท่านั้น!">                                        
                            </div>
                            <br/>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input type="password" class="form-control" name="login_pwd" placeholder="รหัสผ่าน"
                                       maxlength="50" size="8">                                        
                            </div>
                            <br/>
                            <div class="form-group">
                                <button name="submit" type="submit" value="login" class="btn btn-default">
                                    <span class="glyphicon glyphicon-log-in"></span>&nbsp;เข้าสู่ระบบ
                                </button>
                            </div> 
                        </div>

                        <div class="col-md-4 col-md-offset-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title" align="center">ล็อกอิน</h3>
                                </div>
                                <div class="panel-body">
                                    <form accept-charset="UTF-8" role="form">
                                        <fieldset>
                                            <div class="form-group">
                                                <input class="form-control" placeholder="เลขพนักงาน" name="email" type="text">
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" placeholder="รหัสผ่าน" name="password" type="password" value="">
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="remember" type="checkbox" value="Remember Me"> Remember Me
                                                </label>
                                            </div>
                                            <input class="btn btn-lg btn-success btn-block" type="submit" value="เข้าสู่ระบบ">
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form> <!-- /Sign In form -->
                </div> <!-- /.container-fluid -->
            </div> <!-- /.col-md-10 -->


        </div>




        <!--Script -->
        <?php include 'main_script.php'; ?>




    </body>
</html>
