
        <?php
        /* debug section */
        echo "FROM main_search.php<br/>";
        echo "dump POST = ";
        var_dump($_POST);
        echo "<br/>dump SESSION = ";
        var_dump($_SESSION);
        echo '<br/>LoginResult = ';
        print_r($loginResult);
        ?>

<!-- Main form, the one and only -->
<form action="search_result.php" method="post" target="_blank" autocomplete="on">

    <!-- Page Content -->
    <div class="container">

        <!-- NEW SELECT COLUMN TO SHOW SECTION -->
        <div id="main_well" class="well well-sm col-lg-12" align="center">
            <div><h4>เลือกคอลัมน์ที่ต้องการให้แสดง</h4></div>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">รหัสครุภัณฑ์</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="ProductID" checked />
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">รหัสสินทรัพย์</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="product_code"/>
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">รายละเอียด</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="name" checked />
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">ยี่ห้อ</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="brand" checked />
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">รุ่น</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="model" checked />
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">หมายเลขเครื่อง</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="num_machine" />
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">เลขที่ใบโอน</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="num_slip"/>
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">ภัสดุ/ครุภัณฑ์</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="typePro2" />
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">ประเภท</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="typePro" checked />
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">ผู้รับผิดชอบ</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="fname" checked/>
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">ผู้ใช้งาน</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="fname2" />
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">สถานที่ตั้ง</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="address" checked />
            </label>

            <label class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="info" style="font-size:0.98em">สถานะ</button>
                <input type="checkbox" class="hidden" name="select_column[]" value="status_pro" checked />
            </label>

        </div> <!-- /SELECT COLUMN TO SHOW -->

        <!-- MAIN SEARCH BOX col-lg-8 -->
        <div class="col-lg-8">
            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">รหัสผู้รับผิดชอบ</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="xxxxxxxxxx" placeholder="ยังใช้ไม่ได้"></div>
                <div class="col-md-2" align="right" style="padding:0.4em">ชื่อผู้รับผิดชอบ</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_fname"></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">รหัส คมคช.</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_pid" placeholder="รหัสเครื่องมือเครื่องใช้..."></div>
                <div class="col-md-2" align="right" style="padding:0.4em">รหัสสินทรัพย์</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_pc"></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">รายละเอียด</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_name" placeholder="โทรทัศน์, PC, เครื่องวัด..."></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">ยี่ห้อ</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_brand"></div>
                <div class="col-md-2" align="right" style="padding:0.4em">รุ่น</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_model"></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">หมายเลขเครื่อง</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_numm"></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">เลขที่ใบโอน</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_nums"></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">ครุภัณฑ์/คมคช.</div> <!--ครุภัณฑ์/เครื่องมือเครื่องใช้-->
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_tp2"></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">ประเภท</div> <!--printer/คอมฯ/รถยนต์/etc.-->
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_tp"></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">ผู้รับผิดชอบ</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_fname"></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">ผู้ใช้งาน</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_fname2"></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right" style="padding:0.4em">สถานที่ตั้ง</div>
                <div class="col-md-4"><input type="text" class="form-control input-sm" name="search_addr"></div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2" align="right">สถานะ</div> 
                <div class="col-md-10 btn-group">

                    <label class="button-checkbox">
                        <button type="button" class="btn btn-xs" data-color="primary">ใช้งาน</button>
                        <input type="checkbox" class="hidden" name="search_sp[]" value="ใช้งาน" checked />
                    </label>

                    <label class="button-checkbox">
                        <button type="button" class="btn btn-xs" data-color="primary">ส่งซ่อม</button>
                        <input type="checkbox" class="hidden" name="search_sp[]" value="ส่งซ่อม" checked />
                    </label>

                    <label class="button-checkbox">
                        <button type="button" class="btn btn-xs" data-color="primary">คืนคลัง</button>
                        <input type="checkbox" class="hidden" name="search_sp[]" value="คืนคลัง" checked />
                    </label>

                </div>

            </div>

            <!-- input button -->
            <div class="form-group col-md-12" align="center">
                <button class="btn btn-lg btn-success" type="submit">
                    <span class="glyphicon glyphicon-search"></span>&nbsp;ค้นหา
                </button>
                <!--
                <button class="btn btn-danger" onClick="windows.history.go(0)" VALUE="Refresh">
                    <span class="glyphicon glyphicon-alert"></span>&nbsp;ล้าง
                </button> -->
            </div> <!-- /input button -->
            
        </div> <!-- /MAIN SEARCH BOX col-lg-8 -->

        <!-- SIDEBAR COLUMN -->
        <?php
        if (isset($_SESSION) && $loginResult["CustomerID"] == $loginResult["Password"]) {
            ?>
            <div class = "alert alert-danger col-lg-4" role = "alert">
                <span class = "label label-danger">Danger!</span> ท่านยังใช้รหัสผ่านตั้งต้นอยู่ กรุณา<a href="_login_edit.php" target="_blank">เปลี่ยนรหัสผ่าน!</a> <br/>
            </div>
        <?php } ?> 

        <div class="well well-sm col-lg-4">
            <span class="label label-default">Default</span>
            <span class="label label-info">Info</span>
            <span class="label label-primary">Primary</span>
            <span class="label label-success">Success</span>
            <span class="label label-warning">Warning</span>
            <span class="label label-Danger">Danger</span>
        </div>

        <div class = "alert alert-warning col-md-4" role = "alert">
            <span class = "label label-warning">Warning</span> ไม่ควร! <code>จดจำรหัสผ่าน</code> ในคอมพิวเตอร์สาธารณะ<br/>
        </div>

        <div class = "alert alert-info col-md-4" role = "alert">
            <span class = "label label-info">Info</span> สามารถใช้ &nbsp;<kbd>%</kbd>&nbsp; แทนตัวอักษรใดๆ ในคำค้นหาได้! <br/>
            <span class = "label label-info">Info</span> กดปุ่ม &nbsp;<span class="glyphicon glyphicon-refresh"></span>&nbsp; หรือ <kbd>F5</kbd> เพื่อลบค่าในช่องค้นหาทั้งหมด!
        </div>

        <!-- /SIDEBAR COLUMN -->
    </div> <!-- /.container -->
</form>