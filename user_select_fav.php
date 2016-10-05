<?php
//var_dump($_SESSION);
session_start();
if ($_SESSION['user_id'] == "") {
    echo "<br/>โปรดยืนยันตัวตนก่อน !";
    exit();
}

/*
if ($_SESSION['status'] != "USER") {
    echo "<br/>สำหรับ -พนักงาน- เท่านั้น!";
    exit();
} */

require("connection.php");
?>

<html>
    <head>

        <?php include 'main_head.php'; ?>
        <link href="css/bootstrap-3.3.7/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    </head>

    <body>
        <?php
        /* navbar */
        /* ไม่ใช้ case unauthen เพราะไม่มีสิทธิ์เข้าหน้านี้อยู่แล้ว */
        require("navbar_authen.php");

        /*
          echo '<br/>';
          echo 'SESSION = ';
          print_r($_SESSION);
          echo '<br/>loginResult =<br/>';
          print_r($loginResult);
          echo '<br/>POST = <br/>';
          print_r($_POST); */
        ?>

        <!-- Main container -->
        <div class="container-fluid">

            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> หน้าแรก</a></li>
                <li><a href="_login_user.php">รายการหลัก</a></li>
                <li class="active">เลือกรายการที่สนใจ</li>
            </ol> <!-- /breadcrumb -->   

            <div class="page-header">
                <h2>รายการที่สนใจ</h2>
            </div>







            <?php include 'root_url.php'; ?>

            <!--
            <h3 class="page-header">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง <small>กองพัฒนาด้านเทคโนโลยีโรงไฟฟ้าถ่านหินและเหมือง (กพทถ-ห.)</small></h3>
            -->

            <?php
            /* เรียก "รายการโปรด" ที่ user เคยเลือกไว้แล้วมาแสดง */
            $qss = "SELECT `uid`,`favlist` FROM `user_favlist` WHERE `uid` LIKE ".$_SESSION['user_id'];
            $qrs = mysqli_query($connection, $qss) or die("<br/>_login_user user_fav คิวรี่ล้มเหลว!<br/>" . mysql_error());
            $rw = mysqli_fetch_assoc($qrs);
            $rwex = explode("|", $rw['favlist']);
                /*
              echo 'rw=';
              print_r($rw);
              echo 'rwex=';
              print_r($rwex); */
            ?>


            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                            <input type="text" id="search_live" class="form-control" placeholder="ค้นหาทันที..." autocomplete="on">
                        </div>
                    </div>
                    <div class="col-md-4"></div>

                    <div class="col-md-12" align="center">
                        <?php
                        /* เรียก `iid`กับ `detail` มาแสดงให้ user เอาไว้เลือก */
                        //$qryArr = array('detail');
                        //$qryArr_size = count($qryArr);
                        $qstatement = "SELECT `iid`,`detail`,`suffix`,`quantity` FROM `item`";
                        $itemQuery = mysqli_query($connection, $qstatement) or die("<br/>user_select_fav item table คิวรี่ล้มเหลว!<br/>" . mysql_error());
                        $count = mysqli_num_rows($itemQuery);
                        echo "<br/>มีทั้งหมด " . $count . " รายการ<br/>";
                        ?> 
                    </div>

                    <form id='mainForm' action="user_select_fav_process.php" method="post">

                        <table id="search_table" width = "100%" border = "0" cellspacing = "0" cellpadding = "0">
                            <?php
                            while ($itemRow = mysqli_fetch_assoc($itemQuery)) {
                                ?>
                                <tr>
                                    <td>
                                        <span class="button-checkbox">
                                            <button type="button" class="btn" data-color="info" ><?php echo $itemRow['detail'] . '<b> (' . $itemRow['quantity'] . ' ' . $itemRow['suffix'] . ')</b>' ?></button>
                                            <input type="checkbox" class="hidden" name="check_favlist[]" value="<?php echo $itemRow['iid'] ?>" 
                                            <?php
                                            /* เช็คว่าก่อนหน้านี้userจำค่าอะไรไว้ */
                                            /* ถ้ามีค่าที่จำไว้ จะถูกติ๊กไว้โดย echo checked */
                                            foreach ($rwex as $value) {
                                                if ($value == $itemRow['iid']) {
                                                    echo 'checked';
                                                }
                                            }
                                            ?>/>
                                        </span>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>

                        <div class="col-md-12" align="center">--------</div>

                        <!-- submit button -->
                        <div class="form-group col-md-12" align="center">
                            <a class="btn btn-default" href="_login_user.php" target=""><span class="glyphicon glyphicon-backward"></span> กลับ</a>
                            <button id='submitBtn' class="btn btn-lg btn-info" type="submit">
                                <span class="glyphicon glyphicon-pencil"></span>&nbsp;แก้ไข
                            </button>
                        </div>  <!-- /submit button -->
                    </form> <!-- /.form -->
                </div> <!-- /.col-md-8 -->
                <div class="col-md-2">
                </div>

                <br/>



            </div> <!-- /.row -->

        </div><!-- Main container -->









        <script src="js/jquery-1.12.2.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-3.3.7/bootstrap.min.js" type="text/javascript"></script>

        <script src="js/jQueryChkbxBtn.js" type="text/javascript"></script>

        <!--Live Search Script -->
        <script>
            var $search_rows = $('#search_table tr');
            $('#search_live').keyup(function () {
                var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                $search_rows.show().filter(function () {
                    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                    return !~text.indexOf(val);
                }).hide();
            });
        </script><!-- /Live Search Script -->

        <script> /*PREVENT DOUBLE SUBMIT: ทำให้ปุ่ม submit กดได้ครั้งเดียว ป้องกับปัญหาเนต lag แล้ว user กดเบิ้ล มันจะทำให้ส่งค่า 2 เท่า */
            $(document).ready(function () {
                $("#mainForm").submit(function () {
                    $("#submitBtn").attr("disabled", true);
                    return true;
                });
            });
        </script>

    </body>
</html>
