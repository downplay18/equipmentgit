<?php
session_start();
if ($_SESSION['CustomerID'] == "") {
    echo "โปรดยืนยันตัวตนก่อน !";
    exit();
}

if ($_SESSION['Status'] != "BOSS") {
    echo "สำหรับ -ผู้บริหาร- เท่านั้น!";
    exit();
}

require("connection.php");
//include("_login_connection.php");
?>

<html>
    <head>

        <title>ADMIN</title>
        <!-- Bootstrap Core CSS -->
        <?php include 'main_head.php'; ?>

    </head>
    <body>
        <?php
        //var_dump($_SESSION);
        /* navbar */
        /* ไม่ใช้ case unauthenticated เพราะไม่มีสิทธิ์เข้าหน้านี้อยู่แล้ว */
        require("navbar_authenticated.php");

        /*
          //var_dump($_SESSION);
          echo '<br/>';
          echo 'SESSION = ';
          print_r($_SESSION);
          echo '<br/>loginResult =<br/>';
          print_r($loginResult);
          echo '<br/>POST = <br/>';
          print_r($_POST);
         */
        ?>
        <!--
        <br/>Welcome to Admin Page! <br/>
        <table border="1" style="width: 300px">
            <tbody>
                <tr>
                    <td width="87"> &nbsp;Username</td>
                    <td width="197"><?= $loginResult["CustomerID"]; ?>
                    </td>
                </tr>
                <tr>
                    <td> &nbsp;Name</td>
                    <td><?= $loginResult["fname"]; ?></td>
                </tr>
            </tbody>
        </table>
        <br>
        <a href="edit_profile.php">Edit</a><br>
        <br>
        <a href="_logout.php">Logout</a>
        -->



        <?php
        include 'main_search.php';
        include 'main_script.php';
        ?>


    </body>
</html>
