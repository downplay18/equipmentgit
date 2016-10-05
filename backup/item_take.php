<?php
session_start();
if ($_SESSION['user_id'] == "") {
    echo "<br/>โปรดยืนยันตัวตนก่อน !";
    exit();
}

/* ผู้เพิ่มรายการเท่านั้นถึงเข้าหน้านี้ได้ 
  if ($_SESSION['name'] != $_SESSION['adder']) {
  echo "<br/>สำหรับ -พนักงาน- เท่านั้น!";
  exit();
  } */
?>

<html>

    <head>
        <?php include 'main_head.php'; ?>
    </head>

    <body>

        <?php
        /* navbar */
        /* ไม่ใช้ case unauthen เพราะไม่มีสิทธิ์เข้าหน้านี้อยู่แล้ว */
        if (!isset($_SESSION['user_id'])) {
            include("navbar_unauthen.php");
        } else {
            include("navbar_authen.php");
        }

        echo '<br/>';
        echo 'SESSION = ';
        print_r($_SESSION);
        echo '<br/>loginResult =<br/>';
        print_r($loginResult);
        echo '<br/>POST = <br/>';
        print_r($_POST);
        ?>


        <?php
        require("connection.php");
        $strSQL = "SELECT * FROM `item` WHERE `adder` LIKE '" . $_SESSION['name'] . "'";
        $itemTakeQuery = mysqli_query($connection, $strSQL) or die("item_take.php คิวรี่ล้มเหลว!");
        $itemTakeResult = mysqli_fetch_array($itemTakeQuery);

        echo '<br/>$itemTakeResult = <br/>';
        print_r($itemTakeResult);
        ?>

        <!-- MAIN CONTAINER, EDIT BOX COLUMN -->
        <div class="container">

            <!-- ส่วนหัว+breadcrumb -->
            <div class="page-header">
                <h2>ระบบลงบันทึกการใช้งานเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง <small><?= $_SESSION["name"] ?></small></h2>
            </div>

            <ol class="breadcrumb">
                <li><a href="_login_user.php">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง กพทถ-ห.</a></li>
                <li class="active">ลงบันทึกการใช้งาน</li>
            </ol> 
            <!-- /ส่วนหัว+breadcrumb -->




            <!-- Live Search Container -->
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                        <input type="text" id="search_live" class="form-control" placeholder="ค้นหาทันที..." autocomplete="on">
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div><!--/Live Search Container -->

            <?php
            /* ส่วนการสร้างตาราง */
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
                            <th align="center">เบิกใช้</th>
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
                                    <td align="center"><input typer="number" maxlength="5" size="2"></td>
                        </tr>
                    <?php } ?>
                </table> <?php
            }
            /* /ส่วนการสร้างตาราง */
            ?> 

            <br/>








        </div> <!-- /MAIN CONTAINER -->


        <?php include 'main_script.php'; ?>
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
