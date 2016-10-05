<!DOCTYPE html>

<html>

    <head>
        <title>MMTC Equipment</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
        <link href="favicon.ico" rel="icon">
        <link href="css/style.css" rel="stylesheet" type="text/css">     
        <style type="text/css">
            .navbar-inverse{
                background-color: aquamarine;
            }

            /*Disable input[type=number] spin button*/
            input[type=number]::-webkit-inner-spin-button, 
            input[type=number]::-webkit-outer-spin-button { 
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                margin: 0; 
            }
            /*
            .col-md-3{
                border: 1px solid;
            }
            .col-md-2{
                border: 1px dotted;
            }
            #cont{
                border: 1px double;
            }*/
        </style>   

    </head>

    <body>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">

                    <a class="navbar-brand" href="#">MMTC Equipment</a>

                    <!--สร้างปุ่มเมนู สำหรับมุมมองแบบ responsive-->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="nav navbar-nav">
                        <!--เรียก <a> ใน class ของ "nav navbar-nav"-->
                        <li class="active"><a href="http://localhost:81/equipment1php/index.php"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;HOME</a></li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Link ที่เกี่ยวข้อง<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="http://localhost:81/equipment1php/static_customer.php" target="_blank">รายชื่อพนักงาน</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="mmtc.egat.co.th">MMTC Home Page</a></li>
                                <li><a href="http://10.249.50.18/stock/">เว็บครุภัณฑ์</a></li>
                                <li><a href="http://10.249.50.18/equipment">เว็บเครื่องมือเครื่องใช้</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">รายงานสรุป<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">รายการซ่อม</a></li>
                                <li><a href="#">รายการอุปกรณ์สิ้นเปลือง</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">MIS<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">แยกตามอาคาร</a></li>
                                <li><a href="#">แยกตามชนิด</a></li>
                                <li><a href="#">แยกตามราบชื่อพนักงาน</a></li>
                            </ul>
                        </li>

                        <li><a href="#">คู่มือ</a></li>
                    </ul> 

                    <form id="signin" class="navbar-form navbar-right" action="check_login.php" method="post" target="_blank">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input type="number" class="form-control" name="login_cid" placeholder="รหัสพนักงาน" autocomplete="on" min="000000" max="9999999">                                        
                        </div>

                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="password" class="form-control" name="login_pwd" placeholder="รหัสผ่าน">                                        
                        </div>

                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </nav>


        <?php include("connection.php"); ?>


        <div class="container-fluid bg-primary" align="center">Please use % as wildcard character!</div>
        <br/>

        <div class="row">
            <form id="searchSQL" class="navbar-form container-fluid" action="search_result.php" method="post" target="_blank">

                <div class="col-md-12">
                    <div class="col-md-3" align="right">รหัสพนักงาน/รหัสผู้รับผิดชอบ</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="xxxxxxxxxx" placeholder="ยังใช้ไม่ได้"></div>
                    <div class="col-md-2" align="right">ชื่อพนักงาน/ชื่อผู้รับผิดชอบ</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_fname"></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" align="right"><b>รหัส</b>เครื่องมือเครื่องใช้</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_pid"></div>
                    <div class="col-md-2" align="right">รหัสสินทรัพย์</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_pc"></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-3" align="right"><b>รายละเอียด</b>เครื่องมือเครื่องใช้</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_name"></div>
                    <div class="col-md-6"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" align="right">ยี่ห้อ</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_brand"></div>
                    <div class="col-md-1" align="right">รุ่น</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_model"></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" align="right">หมายเลขเครื่อง</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_numm"></div>
                    <div class="col-md-6"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" align="right">เลขที่ใบโอน</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_nums"></div>
                    <div class="col-md-6"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" align="right">ครุภัณฑ์/เครื่องมือเครื่องใช้</div> <!--ครุภัณฑ์/เครื่องมือเครื่องใช้-->
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_tp2"></div>
                    <div class="col-md-6"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" align="right">ประเภท</div> <!--printer/คอมฯ/รถยนต์/etc.-->
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_tp"></div>
                    <div class="col-md-6"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" align="right">ผู้รับผิดชอบ</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_fname"></div>
                    <div class="col-md-6"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" align="right">ผู้ใช้งาน</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_fname2"></div>
                    <div class="col-md-6"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" align="right">สถานที่ตั้ง</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_addr"></div>
                    <div class="col-md-6"></div>
                    <div class="col-md-1"></div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" align="right">สถานะ</div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="search_sp"></div>
                    <div class="col-md-6"></div>
                    <div class="col-md-1"></div>
                </div>
                <input type="submit" value=">>>">
            </form>
        </div>











        <!--css ของ footer จะอยู่ใน css\style.css -->    
        <footer class="footer">
            <div class="container-fluid">
                <p>กองพัฒนาด้านเทคโนโลยีโรงไฟฟ้าถ่านหินและเหมือง</p>
            </div>
        </footer>

        <script src="js/jquery-1.12.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>

</html>
