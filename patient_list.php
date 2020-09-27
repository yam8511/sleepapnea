<?php
    require('db.php'); # 取DB連線

    // 撈取病人資料，供頁面顯示
    $patient = [];
    $sql = 'SELECT * FROM `patient`';
    $result = $db->query($sql);
    if ($result) {
        $patient = $result->fetchAll();
    }    
?>

<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    text-align: center;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    text-align: center;
}

tr:nth-child(even) {
    background-color: #dddddd;
} 
</style>

<div>
    <h1 style=" color: red;">家屬</h1>
    <?php if ($_auth['job'] === 'caretaker') { ?>
        <div align="center">
            <a href="/add.php"><button type="button">新增病人資料</button></a>
        </div>
        <hr>
    <?php } ?>

    <?php if (count($patient) == 0) { ?>
    <h2 align="center">目前無病人資料</h2>
    <?php 
        } else {
    ?>

    <table style="width:100%">
        <tr>
            <th>病人</th>
            <th>姓名</th>
            <th>ID</th>
            <th>操作</th>
        </tr>
    <?php
        foreach ($patient as $index => $p) {
    ?>
        <tr>
            <td><?= $index+1 ?></td>
            <td><?= $p['name'] ?></td>
            <td><?= $p['patient_ID'] ?></td>
            <td>
                <a href="<?= "/patient_info.php?id={$p['patient_ID']}" ?>">
                    <button>進入</button>
                </a>
            </td>
        </tr>
    <?php
        }
    ?>

    </table>

    <?php } ?>
</div>
