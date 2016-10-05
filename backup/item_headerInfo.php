<?php

/* array('ชื่อหัวตาราง', 'ชื่อในฐานข้อมูล', 'ชื่อตัวแปร') */
/* array('user header', 'sql header', '$_POST header') */

$item_headerInfo = array(
    array('เลขที่ใบเสร็จ', 'slip_no', 'var_slipNo'), /* การเรียกใช้ array([0][0], [0][1], [0][2]) */
    array('วันที่ใบเสร็จ', 'slip_date', 'var_slipDate'), 
    array('รายการ', 'detail', 'var_detail'),
    array('จำนวน', 'quantity', 'var_qnt'),
    array('หน่วย', 'suffix', 'var_suffix'),
    array('ราคาต่อรายการ', 'price', 'var_price'),
    array('ผู้เพิ่มรายการ', 'adder', 'var_adder'),
);
$item_size = count($item_headerInfo); /* เท่ากับขนาดของ $header_info */

?>