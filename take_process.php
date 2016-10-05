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

        <?php /*
        echo '<br/>';
        echo 'SESSION = ';
        print_r($_SESSION);
        //echo '<br/>loginResult =<br/>';
        //print_r($loginResult);
        echo '<br/>POST = <br/>';
        print_r($_POST); */
        ?>

        <?php
        $take_statement = "";
        $take_statement .= "UPDATE `item` SET `quantity`=`quantity`-" . $_POST['var_quantity'][0];
        $take_statement .= " WHERE `detail`='".$_POST['varDetail'][0]."';";
        
        $takeRecord_statement = "";
        $takeRecord_statement .= "INSERT INTO `item_take_record` (`take_detail`,`take_suffix`,`take_qty`,`take_date`,`take_time`,`taker`)";
        $takeRecord_statement .= " VALUES ('".$_POST['varDetail'][0]."'"; /* $take_detail */
        $takeRecord_statement .= ",'".$_POST['var_suffix'][0]."'"; /* $take_suffix */
        $takeRecord_statement .= ",'".$_POST['var_quantity'][0]."'"; /* $take_qty */
        date_default_timezone_set("Asia/Bangkok");
        $takeRecord_statement .= ",'" . date('Y-m-d') . "'"; /* $take_date */
        $takeRecord_statement .= ",'" . date("H:i") . "'";
        $takeRecord_statement .= ",'" . $_SESSION['name'] . "'"; /* $taker ใช้ชื่อคนล็อกอินปัจจุบัน เพราะถ้าใช้ชื่อคนที่เอาไปใช้จริงบางทีเป็นลูกจ้าง ไม่มีรหัสพนักงาน */
        $takeRecord_statement .= ");";

        $full_statement = $take_statement.$takeRecord_statement;
        
        require('connection.php');
        mysqli_multi_query($connection, $full_statement) or die("<br/>take_process ล้มเหลว! <br/><br/>โปรด <a class='btn btn-danger' href='javascript:history.back(-1)' role='button'><span class='glyphicon glyphicon-backward'></span> ย้อนกลับ</a> และเลือกรายการ 'เบิกใช้งาน' อีกครั้ง");
        mysqli_close($connection);

        /*debug section*/
        //echo $full_statement;
        
        include 'root_url.php';
        header("Location: $root_url/take.php"); /* Redirect browser */
        //exit();
        ?>



        <?php include 'main_script.php'; ?>
        <!-- เมหือนจะไม่ได้ใช้
        <link  href="css/jquery-ui-1.12.0.css" rel="stylesheet">
        <script src="js/jquery-ui.js"></script> 
        -->
        <script src="js/autocTake.js" type="text/javascript"></script>

    </body>
</html>
