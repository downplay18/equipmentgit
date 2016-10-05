<?php
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

        /*
        echo '<br/>';
        echo 'SESSION = ';
        print_r($_SESSION);
        echo '<br/>loginResult =<br/>';
        print_r($loginResult);
        echo '<br/>POST = <br/>';
        print_r($_POST); */
        ?>

        <!-- breadcrumb -->
        <div class="container-fluid">

            <ol class="breadcrumb">
                <li><a href="index_item.php">ระบบเครื่องมือเครื่องใช้และวัสดุสิ้นเปลือง กพทถ-ห.</a></li>
                <li><a href="_login_user.php">รายการของ <?= $loginResult['name'] ?></a></li>
                <li class="active">เพิ่มรายการใหม่</li>
            </ol> 
        </div><!-- /breadcrumb -->

        <!-- main container -->
        <div class="container">

            <!-- form and submit button -->
            <form action="item_add_process.php" method="post" target="">

                <!-- main col-md-12 -->
                <div class="col-md-12">

                    <!-- เลขที่ใบเสร็จ+วันที่+ชื่อผู้เพิ่มฯ -->
                    <div class="row alert alert-danger">

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">เลขใบเสร็จ</span>
                                <input type="text" class="form-control" name="var_slipNo" placeholder="" style="padding: 0.4em;" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-addon">วันที่ใบเสร็จ</span>
                                <input type="date" class="form-control" name="var_slipDate" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">ชื่อผู้เพิ่มรายการ</span>
                                <input type="text" class="form-control" name="var_adder" value="<?= $_SESSION["name"]; ?>" style="padding: 0.4em;" readonly>
                            </div>
                        </div>

                    </div> <!-- /เลขที่ใบเสร็จ+วันที่+ชื่อผู้เพิ่มฯ -->


                    <!-- file upload -->
                    <div class="col-md-12 well" align="center">
                        <input type="file">          
                    </div>  <!-- /file upload -->

                    <!-- ส่วนหัวตาราง -->
                    <div class="col-md-12">
                        <div class="col-md-1" align="center">ลำดับที่</div>
                        <div class="col-md-4" align="center">รายการ/รายละเอียด</div>
                        <div class="col-md-1" align="center">จำนวน</div>
                        <div class="col-md-2" align="center">หน่วย</div>
                        <div class="col-md-2" align="center">ราคาต่อรายการ**</div>
                    </div> <!-- /ส่วนหัวตาราง -->

                    <!-- แถวที่x ของช่องข้อมูลการเพิ่มข้อมูล -->
                    <div class="col-md-12">
                        <div class="col-md-1" align="center" style="padding: 0.4em;">1</div>

                        <div class="col-md-4"> <!-- รายการ -->
                            <input type="text" class="form-control" name="var_detail[]" required>
                        </div>

                        <div class="col-md-1"> <!-- จำนวน -->
                            <input type="text" class="form-control" name="var_qnt[]" pattern="[0-9]+" title="ตัวเลขเท่านั้น!" required>
                        </div>

                        <div class="col-md-2 form-group"> <!-- หน่วย -->
                            <!-- <label for="sel1">Select list:</label> -->
                            <select class="form-control" name="var_suffix[]" required>
                                <option value="">---</option>
                                <option value="หน่วย">หน่วย</option>
                                <option value="เครื่อง">เครื่อง</option>
                                <option value="เซท">เซท</option>
                                <option value="กิโลกรัม">กิโลกรัม</option>
                                <option value="คู่">คู่</option>
                                <option value="ม้วน">ม้วน</option>
                                <option value="กล่อง">กล่อง</option>
                                <option value="เมตร">เมตร</option>
                                <option value="หลอด">หลอด</option>
                                <option value="ห่อ">ห่อ</option>
                                <option value="กระป๋อง">กระป๋อง</option>
                                <option value="ฟุต">ฟุต</option>
                            </select>
                        </div>

                        <div class="col-md-2"> <!-- ราคาต่อรายการ -->
                            <input type="text" class="form-control" name="var_price[]" pattern="[0-9]+" title="ตัวเลขเท่านั้น!" required>
                        </div>


                        <!-- ปุ่มเพิ่ม/ลบ รายการ -->
                        <div class="col-md-1">
                            <span class="form-group">
                                <button id="add_field_button" type="button" class="btn btn-info">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;เพิ่มรายการ
                                </button>
                            </span>
                        </div> <!-- /ปุ่มเพิ่ม/ลบ รายการ -->

                    </div>

                    <!-- วางจุดที่จะให้เพิ่ม elementจากการกดปุ่ม +เพิ่มรายการ -->
                    <div class="input_fields_wrap"></div>

                    <!-- ปุ่มตกลงและรีเซ็ท -->
                    <div class="form-group col-md-11" align="center" style="padding: 20px;">

                        <button class="btn btn-sm btn-default" type="reset">
                            <span class="glyphicon glyphicon-repeat"></span>&nbsp;รีเซ็ท
                        </button>

                        <button class="btn btn-lg btn-success" type="submit">
                            <span class="glyphicon glyphicon-ok"></span>&nbsp;เสร็จสิ้น
                        </button>

                    </div> <!-- /ปุ่มตกลงและรีเซ็ท -->

                </div> <!-- /main col-md-12 -->
            </form> <!-- /form and submit button -->


            <div class="container col-md-4">
                <div class = "alert alert-info">
                    <b>ขั้นตอนการเพิ่ม:</b>
                    </br><code>1</code> กรอกเลขที่ใบเสร็จ
                    </br><code>2</code> เลือกไฟล์ที่สแกนจากใบเสร็จ เป็นนามสกุล *.pdf, *.jpg, *.jpeg, *.png, *.gif เท่านั้น
                    </br><code>3</code> หาก 1 ใบเสร็จมีหลายรายการ ให้กดปุ่ม <kbd>+เพิ่มรายการ</kbd> 
                    </br><code>4</code> กรอกรายละเอียดให้ครบทุกช่อง 
                    </br><code>5</code> ตรวจสอบข้อมูลให้ถูกต้อง แล้วกดปุ่ม <kbd>เสร็จสิ้น</kbd>
                </div> 
            </div>

            <div class="container col-md-4">
                <div class = "alert alert-info">
                    <span class = "label label-warning">INFO</span> <code>เลขที่ใบเสร็จที่กรอกลงไป</code> และ <code>เลขที่ใบเสร็จบนไฟล์</code> ต้องตรงกัน!<br/>
                    <span class = "label label-info">INFO</span> ราคาต่อรายการ** หมายถึง ผลรวมราคาของทุกชิ้นในรายการนั้น<br/>
                </div> 

            </div>



        </div> <!-- /main container -->

        <!--Script -->
        <?php include 'main_script.php'; ?>

        <script>
            $(document).ready(function () {
                var max_fields = 50; //maximum input boxes allowed
                var wrapper = $(".input_fields_wrap"); //Fields wrapper
                var add_button = $("#add_field_button"); //Add button ID

                var x = 1; //initlal text box count
                $(add_button).click(function (e) { //on add input button click
                    e.preventDefault();
                    if (x < max_fields) { //max input box allowed
                        x++; //text box increment
                        $(wrapper).append('<div class="col-md-12"><div class="col-md-1" align="center" style="padding: 0.4em;">_</div>\n\
            <div class="col-md-4"><input type="text" class="form-control" name="var_detail[]" required></div>\n\
<div class="col-md-1"><input type="text" class="form-control" name="var_qnt[]" pattern="[0-9]+" title="ตัวเลขเท่านั้น!" required></div>\n\
<div class="col-md-2 form-group"><select class="form-control" name="var_suffix[]" required><option value="">---</option><option value="หน่วย">หน่วย</option><option value="เครื่อง">เครื่อง</option><option value="เซท">เซท</option><option value="กิโลกรัม">กิโลกรัม</option><option value="คู่">คู่</option><option value="ม้วน">ม้วน</option><option value="กล่อง">กล่อง</option><option value="เมตร">เมตร</option><option value="หลอด">หลอด</option><option value="ห่อ">ห่อ</option><option value="กระป๋อง">กระป๋อง</option><option value="ฟุต">ฟุต</option></select></div>\n\
<div class="col-md-2"><input type="text" class="form-control" name="var_price[]" pattern="[0-9]+" title="ตัวเลขเท่านั้น!" required></div>\n\
<a href="#" id="remove_field" class="btn btn-warning btn-sm" role="button"><span class="glyphicon glyphicon-minus"></span></a>\n\
</div>'); //add input box
                    }
                });

                $(wrapper).on("click", "#remove_field", function (e) { //user click on remove text
                    e.preventDefault();
                    $(this).parent('div').remove();
                    x--;
                })
            });
        </script>

    </body>
</html>
