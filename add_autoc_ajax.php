<?php
/*สำหรับ autocomplete ใช้ใน 'add' และ 'take'*/
session_start();
require_once 'connection.php';
if ($_POST['type'] == 'item_table') {
    $row_num = $_POST['row_num'];
    $name = $_POST['name_startsWith'];
    $query = "SELECT `detail`,`suffix` FROM `item` WHERE `detail` LIKE '%" . $name . "%' AND `owner` LIKE '". $_SESSION['division'] ."'";
    $result = mysqli_query($connection, $query);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['detail'] . '|' . $row['suffix'];
        array_push($data, $name);
    }
    echo json_encode($data);
}


