<div class="list-group">
    <a href="<?= $root_url ?>/_login_check.php" class="list-group-item active" align="center"><span class="glyphicon glyphicon-home"></span> หน้าหลัก</a>
    <a href="<?= $root_url ?>/show.php" class="list-group-item"><span class="glyphicon glyphicon-search"></span> สืบค้น(ซื้อปกติ)</a>
    <a href="<?= $root_url ?>/show_urgent.php" class="list-group-item"><span class="glyphicon glyphicon-search"></span> สืบค้น(ซื้อเร่งด่วน)</a>
    <?php if ($_SESSION['status'] == "KEY") { ?>
        <a href="<?= $root_url ?>/add.php" class="list-group-item"><span class="glyphicon glyphicon-plus"></span> เพิ่มใบสั่งซื้อ(ปกติ)</a>
        <a href="<?= $root_url ?>/add_urgent.php" class="list-group-item"><span class="glyphicon glyphicon-plus"></span> เพิ่มใบสั่งซื้อ(เร่งด่วน)</a>
        <!--
        <a href="<?= $root_url ?>/take.php" class="list-group-item"><span class="glyphicon glyphicon-minus-sign"></span> เบิกใช้งาน</a>
        -->
    <?php } ?>
        <!--
    <div class="list-group-item">กระดานข่าว:<br/>
        ADMIN
    </div> -->
</div>   