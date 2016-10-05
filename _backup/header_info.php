<?php

/* array('ชื่อหัวตาราง', 'ชื่อในฐานข้อมูล', 'ชื่อตัวแปร') */
/* array('user header', 'sql header', '$_POST header') */

$header_info = array(
    array('รหัสครุภัณฑ์', 'ProductID', 'search_pid'), /* การเรียกใช้ array([0][0], [0][1], [0][2]) */
    array('รหัสสินทรัพย์', 'product_code', 'search_pc'), 
    array('รายละเอียด', 'name', 'search_name'),
    array('ยี่ห้อ', 'brand', 'search_brand'),
    array('รุ่น', 'model', 'search_model'),
    array('หมายเลขเครื่อง', 'num_machine', 'search_numm'),
    array('เลขที่ใบโอน', 'num_slip', 'search_nums'),
    array('ภัสดุ/ครุภัณฑ์', 'typePro2', 'search_tp2'),
    array('ประเภท', 'typePro', 'search_tp'),
    array('ผู้รับผิดชอบ', 'fname', 'search_fname'),
    array('ผู้ใช้งาน', 'fname2', 'search_fname2'),
    array('สถานที่ตั้ง', 'address', 'search_addr'),
    array('สถานะ', 'status_pro', 'search_sp'),
);
$hi_size = count($header_info); /* เท่ากับขนาดของ $header_info */

$select_column_info = array(
    
);
?>