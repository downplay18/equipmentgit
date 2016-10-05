<style type="text/css">
    html * {
        font-size: .945em !important;
        color: #000 !important;
        font-family: Tahoma !important;
        /*background-color: #e6f7ff;*/
    }
</style>

<?php
require("connection.php");
include("header_info.php");
$sc_size = count($_POST['select_column']);
?>

<!-- แสดงหัวของหน้า -->
<div class="page-header">
    <h1 align="center">รายงานหนี้สินครุภัณฑ์/เครื่องมือเครื่องใช้</h1>
</div>

<?php
/* แสดงหัวคำค้นหาให้ผู้ใช้ทราบ */
/* ตัวอย่าง--> เลือก  รหัสครุภัณฑ์: 0000, */
echo "เลือก &nbsp;&nbsp;&nbsp;";
for ($i = 0; $i < $hi_size; $i++) {
    if (isset($_POST[$header_info[$i][2]]) && $_POST[$header_info[$i][2]] != "") {
        if (!is_array($_POST[$header_info[$i][2]])) { /* เคส input text ไม่ว่าง && ไม่เป็น array */
            echo "<b>" . $header_info[$i][0] . "</b>:" . $_POST[$header_info[$i][2]] . " &nbsp;";
        } elseif (is_array($_POST[$header_info[$i][2]])) {
            echo "<b>" . $header_info[$i][0] . "</b>:";
            foreach ($_POST[$header_info[$i][2]] as $v) {
                echo $v . " ";
            }
        }
    }
}
echo "<br/>";




/* ============================================= สร้างคำค้นหาใส่ใน $query_statement ============================================= */
/* สร้างส่วนแรกของ query statement ด้วยคอลัมน์ที่ต้องการให้แสดง */
$query_statement = "SELECT ";
for ($i = 0; $i < $sc_size; $i++) {
    $query_statement .= mysql_real_escape_string($_POST['select_column'][$i]);
    if ($i < ($sc_size) - 1) {
        $query_statement .= ',';
    }
}
$query_statement .= " FROM product WHERE (" . $header_info[0][1] . " LIKE '%" . mysql_real_escape_string($_POST[$header_info[0][2]]) . "%')";
for ($i = 1; $i < $hi_size; $i++) { /* ให้ index $i เริ่มที่ 1 เพราะ index 0 คือ ProductID นั้น ถูกสร้างไว้ในประโยคตั้งต้นแล้ว */
//จากการทดสอบพบว่า unchecked checkbox นั้น ทีค่าเป็นทั้ง NULL, 0, empty string ซึ่ง textbox อื่นก็เป็นค่าพวกนี้ได้ ยกเว้น NULL จึงใช้ isset ซึ่งตรวจ NULL เป็นตัวแยก

    if (isset($_POST[$header_info[$i][2]])) {
        /* เคส 1 ==> input เป็น textbox ทั่วไป ทั้ง filled และ empty */
        if (!is_array($_POST[$header_info[$i][2]])) {
            //echo "<br/>case 1 = " . $i . "<br/>";
            $query_statement .= " AND ";
            $query_statement .= "(" . $header_info[$i][1] . " LIKE " . "'%" . mysql_real_escape_string($_POST[$header_info[$i][2]]) . "%')";
            /* เคส 2 ==> input เป็น checkbox และ เป็น array แบบ filled */
        } elseif (is_array($_POST[$header_info[$i][2]])) { /* เคสที่ค่าจาก checkbox จะเป็น array */
            //echo "<br/>case 2 = " . $i . "<br/>";
            $query_statement .= " AND (";
            /* $lastOR เก็บขนาดอาเรย์ */
            $lastOR = count($_POST[$header_info[$i][2]]);
            /* $zxc ใช้นับเพื่อเติม " OR " ให้ถูกต้อง */
            $zxc = 0;
            foreach ($_POST[$header_info[$i][2]] as $item) {
                $zxc++;
                $query_statement .= "(" . $header_info[$i][1] . " LIKE " . "'%" . mysql_real_escape_string($item) . "%')";
                if ($zxc != $lastOR) {
                    $query_statement .= " OR ";
                }
            }
            $query_statement .= ")";
        }
        /* เคส 3 ==> input เป็น empty checkbox */
    } elseif (!isset($_POST[$header_info[$i][2]])) { /* เคสที่ค่าจาก checkbox จะเป็น array */
        //echo "<br/>case 3 = " . $i . "<br/>";
        $query_statement .= "<br/><br/> CASE 3 is ERROR";
    }
}

/*
 * var_dump($_POST];
  echo "<br/><br/>query_statement = " . $query_statement . "<br/><br/>";
  เอาไว้เช็ค $_POST['select_column'][$i]
  for ($i = 0; $i < $sc_size; $i++) {
  echo "for [$i] = " . $_POST['select_column'][$i] . "<br>";
  }
 */

/* ================================================================ */
/* โค้ดสำหรับสร้างหัวตารางตามที่ user เลือก */
$select_column_header = array();
for ($i = 0; $i < $sc_size; $i++) {
    for ($j = 0; $j < $hi_size; $j++) {
        if ($_POST['select_column'][$i] == $header_info[$j][1]) {
            $select_column_header[$i] = $header_info[$j][0];
        }
    }
}
/* ================================================================ */

/* * *******************************ส่งคำสั่ง sql query ******************************** */
$query = mysqli_query($connection, $query_statement) or die("การค้นหาล้มเหลว! โปรดเลือก checkbox อย่างน้อย 1 ค่า");
$count = mysqli_num_rows($query);

echo "พบ " . $count . " รายการ!<br/>";

/* เช็คว่ามีรายการตรงกับคำค้นหาหรือไม่ */
if ($count == 0) {
    echo 'ไม่พบข้อมูลที่ค้นหา!';
} else { /* เมื่อพบรายการที่ค้นหา */
    /* สร้างตาราง */
    ?>
    <table id="table" width = "100%" border = "1" cellspacing = "0" cellpadding = "0">
        <tr align = "center">
            <td><b>ลำดับ</b></td>
            <?php
            /* พิมพ์หัวตาราง */
            for ($i = 0; $i < $sc_size; $i++) {
                echo "<td><b>" . $select_column_header[$i] . "</b></td>"; /* หา header */
            }
            ?>
        </tr>

        <?php
        $jj = '1'; /* ตัวแปรนับลำดับที่เพื่อแสดงในตารางให้ user ไล่ลำดับได้ง่ายขึ้น */
        /* แกะ sql query มาไว้ใน $row */
        while ($row = mysqli_fetch_array($query)) {
            ?>
            <tr>
                <td align='center'><?= $jj++; ?></td>
                <?php
                /* แกะผลการค้นหาเป็น $row ออกมาแสดง */
                for ($i = 0; $i < $sc_size; $i++) {
                    echo "<td align='center'>" . $row[$_POST['select_column'][$i]] . "</td>";
                    /* if ($row[$header_info[$i][1]] == $row['name']) {
                      <a href="http://10.249.50.18/stock/product/detailboss.php?ProductID=01-0048">$row['name']</a>
                      } */
                }
                ?>
            </tr>
        <?php } ?>
    </table> <?php
}
?>
