<?php
//var_dump($_SESSION);
session_start();
//error_reporting(0);
require_once 'connection.php';
include 'root_url.php';

if ($_SESSION['user_id'] == "") {
    header("Location: $root_url/index.php", true, 302);
    exit();
}

$_SESSION['pMsg'] = array();
?>

<?php
if ($_POST['submitBtn'] != "") {
    //array_push($_SESSION['pMsg'], "SUBMIT SET");
    //หาวันแรก กับวันสุดท้ายก่อน
    $daterange = explode(" - ", $_POST['daterange']);
    $firstDate = $daterange[0];
    $endDate = $daterange[1];

    //คิรรี่หารายงานในช่วงเวลา
    $allDetailQS = "SELECT add_detail as allDetail,adder as allDivision
                FROM item_add_record
                WHERE adder LIKE '" . $_SESSION['division'] . "'
                GROUP BY add_detail
                UNION
                (
                    SELECT take_detail as allDetail,taker as allDivision
                    FROM item_take_record
                    WHERE taker LIKE '" . $_SESSION['division'] . "'
                    GROUP BY take_detail
                )
                ORDER BY CONVERT(`allDetail` USING TIS620);";
    $allDetailHeader = array("รายการ");
    $allDetailData = array("allDetail");
}
?>

<html>
    <head>

        <title>ADMIN</title>
        <?php include 'main_head.php'; ?>        
    </head>

    <body>
        <?php
        /* navbar */
        /* ไม่ใช้ case unauthen เพราะไม่มีสิทธิ์เข้าหน้านี้อยู่แล้ว */
        include 'navbar.php';


        echo 'SESSION = ';
        print_r($_SESSION);
        echo '<br/>POST = <br/>';
        print_r($_POST);
        ?>

        <div class="row">

            <div class="col-md-2 sidebar">
                <?php include 'sidebar.php'; ?>
            </div>

            <div class="col-md-10">

                <!-- Main container -->
                <div class="container-fluid">

                    <div class="page-header">
                        <h2>รายงานตามช่วงเวลา <small>เลือกช่วงเวลาที่ต้องการ</small></h2>
                    </div>

                    <div class="col-md-12">
                        <form action="" method="post">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input id="reportrange" class="pull-right" type="text" name="daterange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" >
                                </div>
                            </div>


                            <!-- ปุ่มตกลงและรีเซ็ท -->
                            <div class="col-md-1">
                                <button class="btn btn-success" type="submit" name="submitBtn" value="submit">
                                    <span class="glyphicon glyphicon-search"></span> ค้นหา
                                </button>
                                <!--
                                <button class="btn btn-sm btn-default" type="reset">
                                    <span class="glyphicon glyphicon-repeat"></span>&nbsp;รีเซ็ท
                                </button>
                                -->
                            </div> <!-- /ปุ่มตกลงและรีเซ็ท -->

                        </form>
                    </div>



                    <table id="example" class="table table-bordered table-condensed table-striped table-hover">
                        <thead>
                            <tr align="center">
                                <?php foreach ($allDetailHeader as $val) { ?>
                                    <th><?= $val ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $allDetailQry = mysqli_query($connection, $allDetailQS);
                            while ($row = mysqli_fetch_assoc($allDetailQry)) {
                                ?>
                                <tr>
                                    <td><?= $row['allDetail'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>


                </div><!-- Main container -->
            </div> <!-- /.col-md-10 -->

        </div> <!-- /.row -->




        <?php include 'main_script.php'; ?>
        <script src="bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <script src="bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(function () {

                var start = moment().subtract(29, 'days');
                var end = moment();

                function cb(start, end) {
                    $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                }

                $('#reportrange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    "opens": "right",
                    locale: {
                        format: 'DD/MM/YYYY'
                    },
                    ranges: {
                        'วันนี้': [moment(), moment()],
                        '7 วันที่แล้ว': [moment().subtract(6, 'days'), moment()],
                        '30 วันที่แล้ว': [moment().subtract(29, 'days'), moment()],
                        'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
                        'เดือนก่อน': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);

                cb(start, end);

            });
        </script>


        <script>
            $(document).ready(function () {
                var table = $('#example').DataTable({
                    dom:
                            "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    lengthChange: false,
                    buttons: ['copy', 'excel', 'print', 'colvis']
                });


                table.buttons().container()
                        .appendTo($('#example_wrapper .col-sm-6:eq(0)'));
            });
        </script>

    </body>
</html>
