<?php

session_start();
?>


<?php

require 'connection.php';
include 'root_url.php';

if (!isset($_POST['check_favlist'])) {
    //echo 'case 1st 0 <br>'.print_r($_POST['check_favlist']);
    $favlist_imp = "ไม่มี";
}
if (count($_POST['check_favlist']) == 1) {
    //echo count($_POST['check_favlist']).'case 2st 1อัน'.print_r($_POST['check_favlist']);
    $favlist_imp = $_POST['check_favlist'][0];
} else {
    //echo 'case 3nd หลาย '.print_r($_POST['check_favlist']);
    $favlist_imp = implode("|", $_POST['check_favlist']);
    //echo 'favimp=' . $favlist_imp;
}

$qstatement = "INSERT INTO `user_favlist` (`uid`,`favlist`) VALUES ('" . $_SESSION['user_id'] . "','$favlist_imp') ON DUPLICATE KEY UPDATE `favlist`='$favlist_imp';";
//echo '<br><br>qstatement='.$qstatement;
$query = mysqli_query($connection, $qstatement) or die("<br/>user_select_fav_process item table คิวรี่ล้มเหลว!<br/>" . mysql_error());
header('Location: ' . $root_url . '/_login_user.php');
?>

