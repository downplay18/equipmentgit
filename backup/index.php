
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
          echo '<br/>loginResult =<br/>';
          print_r($loginResult);
          echo '<br/>POST = <br/>';
          print_r($_POST); */
        ?>

        <h2 align="center">ยินดีต้อนรับ ผู้มาเยือน</h2>
        
        <br/>
        
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

        <br/>

        <?php
        /* ส่วนการสร้างตาราง */
        require 'connection.php';
        include 'item_headerInfo.php';
        $qstatement = "SELECT * FROM `item`";
        $query = mysqli_query($connection, $qstatement) or die("_login_user.php คิวรี่ล้มเหลว");
        $count = mysqli_num_rows($query);

        //echo "พบ " . $count . " รายการ!<br/>";
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
                            ?>
                            <td><?= $row[$item_headerInfo[$i][1]] ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table> <?php
        }
        /* /ส่วนการสร้างตาราง */
        ?> 



        <!--Script -->
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
