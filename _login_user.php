<?php
//var_dump($_SESSION);
session_start();
//error_reporting(0);
require_once 'connection.php';

include 'root_url.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: $root_url/index.php", true, 302);
    exit();
}
if ($_SESSION['status'] == "BOSS") { //เพราะ ขี้เกียจเขียน 2เคส ทั้ง USER และ ADMIN
    header("Location: $root_url/_login_check.php", true, 302);
}
?>

<html>
    <head>

        <title>ADMIN</title>
        <!-- Bootstrap Core CSS -->
        <?php include 'main_head.php'; ?>

    </head>

    <body>
        <?php
        include("navbar.php");

        /*
          echo 'SESSION = <br/>';
          print_r($_SESSION);
          echo '<br/>POST = <br/>';
          print_r($_POST); */
        ?>

        <div class="row">
            <div class="col-md-2 sidebar">
                <?php include 'sidebar.php'; ?>
            </div>


            <div class="col-md-10">
                <div class="container-fluid">

                    <div class="col-md-6">
                        <div class="alert alert-info">

                            <h4 align="center">รายการที่สนใจ
                                <a class="btn btn-info" href="user_select_fav.php" target="" role="button"><span class="glyphicon glyphicon-edit"></span></a>
                            </h4>

                            <?php
                            //<!-- PART1 แกะ favlist detail จากid -->
                            //แสดงรายการที่สนใจที่ user เลือกเอาไว้
                            $favQS = "SELECT `favlist` FROM `user_favlist` WHERE `uid` LIKE " . $_SESSION['user_id']; /* `uid` & `favlist` */
                            $favQry = mysqli_query($connection, $favQS) or die("<br/>_login_user favQS คิวรี่ล้มเหลว: " . mysqli_error($connection));
                            $favRow = mysqli_fetch_assoc($favQry);
                            $favEx = explode("|", $favRow['favlist']); /* $favEx เป็นarray เก็บ iid */

                            $itemQS = "SELECT `iid`,`detail`,`suffix`,`quantity` FROM `item` WHERE `owner` LIKE '" . $_SESSION['division'] . "'";
                            $itemQry = mysqli_query($connection, $itemQS) or die("<br/>_login_user itemQS คิวรี่ล้มเหลว: " . mysqli_error($connection));

                            //แสดงรายการที่สนใจพร้อมจำนวนปัจจุบันที่เหลืออยู่ ท่ายากตรงที่เก็บ favlist เป็น iid ไม่ใช่ detail
                            $favlistDetail = array(); //เป็น detail ของ favlist
                            while ($itemRow = mysqli_fetch_assoc($itemQry)) {
                                foreach ($favEx as $value) { //แกะ $favEx ออกมาเป็น $favlistDetail
                                    if ($value == $itemRow['iid']) {
                                        //echo "<br/><kbd>" . $itemRow['iid'] . "</kbd> " . $itemRow['detail'] . " <b>(" . $itemRow['quantity'] . " " . $itemRow['suffix'] . ")</b>";
                                        $favlistDetail[] = $itemRow['detail'];
                                    }
                                }
                            }

                            //ที่ไม่ใช้ count($favEx) เพราะมันนับรวม empty string เป็น 1 ด้วย
                            $favCount = 0;
                            foreach ($favEx as $value) {
                                if ($value != "") {
                                    $favCount++;
                                }
                            }
                            ?> 

                            <?php
                            /* PART2 forecast และแสดงผล */
                            if ($favCount == 0) { //อันดับแรกต้องเช็คก่อนว่ามี favlist หรือไม่
                                echo "<br/><div align='center'>ไม่มีรายการเฝ้าดู</div>";
                            } else {



                                /* PART หาdetailที่userเลือกไว้ */
                                $iQS = "SELECT `detail`,`quantity`,`suffix`,`owner`"
                                        . " FROM `item`"
                                        . " WHERE `owner` LIKE '" . $_SESSION['division'] . "'"
                                        . " AND (";
                                $count = 0; //เพื่อหาตัวแรกและตัวสุดท้ายของarray
                                foreach ($favlistDetail as $fav) {
                                    if ($count == 0) {
                                        $iQS .= "`detail` LIKE '" . $fav . "'";
                                        $count++;
                                    } else {
                                        $iQS .= " OR `detail` LIKE '" . $fav . "'";
                                    }
                                }
                                $iQS .= ")";

                                //เอา iQS มาคิวรี่
                                $iQry = mysqli_query($connection, $iQS) or die("iQry fail!: " . mysqli_error($connection));
                                while($rowi = mysqli_fetch_assoc($iQry)){
                                    echo $rowi['detail']." (คงเหลือ <u>". $rowi['quantity']. " ". $rowi['suffix'] ."</u>)<br/>";
                                }
                                

                                /* PART คำนวณ */
                                //ดึง SUM(add) ย้อนหลัง 1 ปี
                                $aQry = mysqli_query($connection, "SELECT SUM(`add_qty`) as sum_addQty"
                                        . " FROM `item_add_record`"
                                        . " WHERE `adder` LIKE '" . $_SESSION['division'] . "'"
                                        . " AND `add_date` BETWEEN curdate()-365 AND curdate()")
                                        or die("aQry fail!: " . mysqli_error($connection));
                                ;
                                //ดึง SUM(take) ย้อนหลัง 1 ปี
                                $tQry = mysqli_query($connection, "SELECT SUM(`take_qty`) as sum_takeQty"
                                        . " FROM `item_take_record`"
                                        . " WHERE `taker` LIKE '" . $_SESSION['division'] . "'")
                                        or die("tQry fail!: " . mysqli_error($connection));
                                ;

                                $rowa = mysqli_fetch_assoc($aQry);
                                $rowt = mysqli_fetch_assoc($tQry);

                                //หาปริมาณใช้งานต่อวัน โดย SUM(take)หาร365 แบบปัดเศษขึ้น
                                $itemPerDay = ceil($rowt['sum_takeQty'] / 365);

                                //คาดว่าจะหมด
                                $forecastDay = $rowa['sum_addQty'] / $itemPerDay;

                                echo $rowa['sum_addQty'] . "หาร" . $rowt['sum_takeQty'] . "9jv;yo" . $itemPerDay . "sdfsd" . $forecastDay;
                            }
                            ?>

                        </div> <!-- /.clert -->
                    </div> <!-- /.col-md-12 -->


                    <div class="col-md-6">
                        <?php
                        /* PART หาdetailที่userเลือกไว้ */
                        $iQry = mysqli_query($connection, "SELECT `detail`,`quantity`,`suffix`,`owner` FROM `item`"
                                . " WHERE `owner` LIKE '" . $_SESSION['division'] . "'")
                                or die("iQry fail!: " . mysqli_error($connection));
                        while ($rowi = mysqli_fetch_assoc($iQry)) {
                            echo $rowi['detail'] . "<br>";
                        }

                        /* PART คำนวณ */
                        //ดึง SUM(add) ย้อนหลัง 1 ปี
                        $aQry = mysqli_query($connection, "SELECT SUM(`add_qty`) as sum_addQty"
                                . " FROM `item_add_record`"
                                . " WHERE `adder` LIKE '" . $_SESSION['division'] . "'"
                                . " AND `add_date` BETWEEN curdate()-365 AND curdate()")
                                or die("aQry fail!: " . mysqli_error($connection));
                        ;
                        //ดึง SUM(take) ย้อนหลัง 1 ปี
                        $tQry = mysqli_query($connection, "SELECT SUM(`take_qty`) as sum_takeQty"
                                . " FROM `item_take_record`"
                                . " WHERE `taker` LIKE '" . $_SESSION['division'] . "'")
                                or die("tQry fail!: " . mysqli_error($connection));
                        ;

                        $rowa = mysqli_fetch_assoc($aQry);
                        $rowt = mysqli_fetch_assoc($tQry);

                        //หาปริมาณใช้งานต่อวัน โดย SUM(take)หาร365 แบบปัดเศษขึ้น
                        $itemPerDay = ceil($rowt['sum_takeQty'] / 365);

                        //คาดว่าจะหมด
                        $forecastDay = $rowa['sum_addQty'] / $itemPerDay;

                        echo $rowa['sum_addQty'] . "หาร" . $rowt['sum_takeQty'] . "9jv;yo" . $itemPerDay . "sdfsd" . $forecastDay;
                        ?>
                    </div>

                </div> <!-- /.container-fluid -->
            </div> <!-- /.col-md-10 -->

        </div> <!-- /.row -->





        <!--Script -->
<?php include 'main_script.php'; ?>


    </body>
</html>
