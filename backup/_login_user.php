<?php
//var_dump($_SESSION);
session_start();
if ($_SESSION['user_id'] == "") {
    echo "<br/>โปรดยืนยันตัวตนก่อน !";
    exit();
}

if ($_SESSION['status'] != "USER") {
    echo "<br/>สำหรับ -พนักงาน- เท่านั้น!";
    exit();
}

require("connection.php");
?>

<html>
    <head>

        <title>ADMIN</title>
        <!-- Bootstrap Core CSS -->
        <?php include 'main_head.php'; ?>

    </head>

    <body>
        <?php
        /* navbar */
        /* ไม่ใช้ case unauthen เพราะไม่มีสิทธิ์เข้าหน้านี้อยู่แล้ว */
        require("navbar_authen.php");


        echo '<br/>';
        echo 'SESSION = ';
        print_r($_SESSION);
        echo '<br/>loginResult =<br/>';
        print_r($loginResult);
        echo '<br/>POST = <br/>';
        print_r($_POST);
        ?>

        <!-- breadcrumb -->
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="active">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง กพทถ-ห.</li>
            </ol> 
        </div><!-- /breadcrumb -->









        <?php include 'root_url.php'; ?>

        <!--
        <h3 class="page-header">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง <small>กองพัฒนาด้านเทคโนโลยีโรงไฟฟ้าถ่านหินและเหมือง (กพทถ-ห.)</small></h3>
        -->

        <!-- function container -->
        <div class="row">

            <div class="col-md-4" align="right">
                <a class="btn btn-success" href="item_add.php" target="_blank"><span class="glyphicon glyphicon-plus-sign"></span> เพิ่มรายการใหม่</a>
            </div>

            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                    <input type="text" id="search_live" class="form-control" placeholder="ค้นหาทันที..." autocomplete="on">
                </div>
            </div>

            <div class="col-md-4" align="left">
                <a class="btn btn-danger" href="item_take.php" target="_blank"><span class="glyphicon glyphicon-minus-sign"></span> ลงบันทึกการใช้งาน</a>
            </div>

        </div><!--/function container -->

        
        <?php /* ส่วนการสร้างตาราง */
        require 'connection.php';
        include 'item_headerInfo.php';
        $qstatement = "SELECT * FROM `item`";
        $query = mysqli_query($connection, $qstatement) or die("_login_user.php คิวรี่ล้มเหลว");
        $count = mysqli_num_rows($query);

        echo "พบ " . $count . " รายการ!<br/>";
        if ($count == 0) {
            echo 'ไม่พบข้อมูลที่ค้นหา!';
        } else { /* เมื่อพบรายการที่ค้นหา */
            /* สร้างตาราง */
            ?>
            <table id="search_table" width = "100%" border = "1" cellspacing = "0" cellpadding = "0">
                <tr>
                    <th align = "center">ลำดับ</th>
                    <?php
                    /* พิมพ์หัวตาราง */
                    for ($i = 0; $i < $item_size; $i++) {
                        ?>
                        <th align='center'><?= $item_headerInfo[$i][0] ?></th> <!-- หา header -->
                    <?php } ?>
                </tr>

                <?php
                $jj = '1'; /* ตัวแปรนับลำดับที่เพื่อแสดงในตารางให้ user ไล่ลำดับได้ง่ายขึ้น */
                /* แกะ sql query มาไว้ใน $row */
                
                while ($row = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td><?= $jj++; ?></td>
                        <?php
                        /* แกะผลการค้นหาเป็น $row ออกมาแสดง */
                        for ($i = 0; $i < $item_size; $i++) {
                            if ($row[$item_headerInfo[$i][1]] == $row["detail"]) {
                                ?>
                                <?php if ($row["adder"] == $_SESSION["name"]) { ?>
                                    <td><a href="item_edit.php?detail=<?= $row["detail"] ?>" target="_blank"><?php echo $row["detail"]; ?></a></td>
                                <?php } else { ?>
                                    <td><?php echo $row["detail"]; ?></td>
                                <?php } ?>
                            <?php } else { ?>
                                <td><?= $row[$item_headerInfo[$i][1]] ?></td>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table> <?php
        }
        /* /ส่วนการสร้างตาราง */ 
        ?> 











        <?php
        include 'main_script.php';
        ?>

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

    </body>
</html>
