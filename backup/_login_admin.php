<?php
session_start();
if ($_SESSION['user_id'] == "") {
    echo "<br/>โปรดยืนยันตัวตนก่อน !";
    exit();
}

if ($_SESSION['status'] != "ADMIN") {
    echo "<br/>สำหรับ -ผู้ดูแลระบบ- เท่านั้น!";
    exit();
}

//require("connection.php");
?>

<html>
    <head>

        <title>ADMIN</title>
        <!-- Bootstrap Core CSS -->
        <?php include 'main_head.php'; ?>

    </head>
    <body>
        <?php
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



        <?php include 'root_url.php'; ?>

        <!--
        <h3 class="page-header">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง <small>กองพัฒนาด้านเทคโนโลยีโรงไฟฟ้าถ่านหินและเหมือง (กพทถ-ห.)</small></h3>
        -->

        <!-- function container -->
        <div class="row">

            <div class="col-md-4" align="right">
                <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-list"></span> แสดงทั้งหมด</button>
            </div>

            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                    <input type="text" id="search_live" class="form-control" placeholder="ค้นหาทันที..." autocomplete="on">
                </div>
            </div>

            <div class="col-md-4">
                <a class="btn btn-default" href="add_item.php"><span class="glyphicon glyphicon-plus"></span> เพิ่มรายการใหม่</a>
            </div>

        </div><!--/function container -->

        <?php
        require 'connection.php';
        include 'main_header_info.php';
        $qstatement = "SELECT * FROM `item`";
        $query = mysqli_query($connection, $qstatement) or die("ไฟล์ index_item.php คิวรี่ล้มเหลว");
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
                    for ($i = 0; $i < $hi_size; $i++) {
                        ?>
                        <th align='center'><?= $header_info[$i][0] ?></th> <!-- หา header -->
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
                        for ($i = 0; $i < $hi_size; $i++) {
                            if ($row[$header_info[$i][1]] == $row["detail"]) {
                                ?>
                                <td><a href="item_edit.php" target="_blank"><?= $row["detail"] ?></a></td>
                            <?php } else { ?>
                                <td><?= $row[$header_info[$i][1]] ?></td>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table> <?php
        }
        ?>







        <?php
        include 'main_script.php';
        ?>

    </body>
</html>
