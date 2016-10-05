<?php
session_start();
if ($_SESSION['user_id'] == "") {
    echo "<br/>โปรดยืนยันตัวตนก่อน !";
    exit();
}

/* ผู้เพิ่มรายการเท่านั้นถึงเข้าหน้านี้ได้ */
if ($_SESSION['status'] != "USER") {
    echo "<br/>สำหรับ -พนักงาน- เท่านั้น!";
    exit();
}

$_SESSION["detail"] = $_GET["detail"];
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
        $strSQL = "SELECT * FROM `item` WHERE `detail` LIKE '". $_SESSION["detail"]."'" ; 
        $itemEditQuery = mysqli_query($connection, $strSQL) or die("item_edit.php คิวรี่ล้มเหลว!");
        $itemEditResult = mysqli_fetch_array($itemEditQuery);
        
                echo '<br/>$itemEditResult = <br/>';
        print_r($itemEditResult);
        ?>

        <!-- MAIN CONTAINER, EDIT BOX COLUMN -->
        <div class="container">
            <div class="page-header">
                <h2>กำลังแก้ไข <small><?= $_SESSION["detail"] ?></small></h2>
            </div>

            <!-- MAIN EDIT BOX COLUMN -->
            <form action="item_edit_process.php" method="post">
                <div class="col-lg-8">
                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>ชื่อผู้เพิ่มรายการ</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $itemEditResult["adder"]; ?></div>
                        <div class="col-md-4"></div>
                        <div class="col-md-2"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>เลขที่ใบเสร็จ</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $itemEditResult["slip_no"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="itemUp_slipNo" placeholder="เปลี่ยน  เลขที่ใบเสร็จ"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>วันที่ใบเสร็จ</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $itemEditResult["slip_date"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="itemUp_slipDate" placeholder="เปลี่ยน  วันที่ใบเสร็จ"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>รายการ</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $itemEditResult["detail"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="itemUp_detail" placeholder="เปลี่ยน  รายละเอียด"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>จำนวน</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $itemEditResult["quantity"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="itemUp_qnt" placeholder="เปลี่ยน  จำนวน"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>หน่วย</b></div>
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $itemEditResult["suffix"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="itemUp_suffix" placeholder="เปลี่ยน  หน่วย"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-3" align="right" style="padding:0.4em"><b>ราคาต่อรายการ</b></div> <!--ครุภัณฑ์/เครื่องมือเครื่องใช้-->
                        <div class="col-md-3"><p type="text" class="form-control-static"><?= $itemEditResult["price"]; ?></div>
                        <div class="col-md-4"><input type="text" class="form-control input-sm" name="itemUp_price" placeholder="เปลี่ยน  ราคาต่อรายการ"></div>
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
