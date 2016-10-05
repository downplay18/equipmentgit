<!DOCTYPE html>

<html>

    <head>
        <?php include 'main_head.php'; ?>  
    </head>

    <body>

        <?php
        //include("navbar_unauthenticated.php");
        /* เลือกแสดง navbar แบบ ล็อกอินแล้ว(authenticated) และ ยังไม่ล็อกอิน(unauthenticated) */
        if (!isset($_SESSION['CustomerID'])) {
            include("navbar_unauthen.php");
        } else {
            include("navbar_authen.php");
        }
        //var_dump($_SESSION);
        ?>

        <!-- เรียก Select Column + ฟังก์ชันค้นหาหลัก + Side Column-->
        <?php include 'main_search.php'; ?>

        <!-- Script -->
        <?php include 'main_script.php'; ?>

    </body>
</html>
