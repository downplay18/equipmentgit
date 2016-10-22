<?php
/*สำหรับ autocomplete ใช้ใน 'add' และ 'take'*/
session_start();
require_once 'connection.php';
if ($_POST['type'] == 'item_table') {
    $row_num = $_POST['row_num'];
    $name = $_POST['name_startsWith'];
    $query = "SELECT `urg_detail`,`urg_suffix` FROM `item_urgent_record`"
            . " WHERE `urg_detail` LIKE '%" . $name . "%'"
            . " AND `urg_adder` LIKE '". $_SESSION['division'] ."'"
            . " GROUP BY `urg_detail`";
    $result = mysqli_query($connection, $query);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['urg_detail'] . '|' . $row['urg_suffix'];
        array_push($data, $name);
    }
    echo json_encode($data);
}


