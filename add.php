<?php
require('auth_user.php');
require('show_auth.php');
if (isset($_POST['token'])) {
    $check_list = [
        'patient_ID',
        'name',
        'gender',
        'height',
        'weight',
        'age',
        'caretaker_account',
    ];

    $required = false;
    foreach ($check_list as $i => $name) {
        $required = trim($_POST[$name] ?? '') === '';
        if ($required) {
            break;
        }
        $_POST[$name] = trim($_POST[$name]);
    }

    if ($required) {
        $hint = '請填入完整資料';
    } else {
        $insertSQL = "INSERT INTO `patient` (`patient_ID`, `name`, `gender`, `height`, `weight`, `age`, `caretaker_account`) 
        VALUES ('{$_POST['patient_ID']}', '{$_POST['name']}', '{$_POST['gender']}', '{$_POST['height']}', '{$_POST['weight']}', '{$_POST['age']}', '{$_POST['caretaker_account']}')";
        
        require('db.php');

        $result = $db->exec($insertSQL);
        if ($result) {
?>
            <!-- 處理新增 -->
            <h1 style="color: teal;">病人資料[<?= $_POST['patient_ID'] ?>]新增成功!
                <a href="/">[回首頁]</a>
            </h1>
<?php
            die();
        } else {
            // 檢查是不是病人ID重複
            $checkSQL = "SELECT `patient_ID` FROM `patient` WHERE `patient_ID` = '{$_POST['patient_ID']}'";
            $result = $db->query($checkSQL);
            if ($result) {
                $data = $result->fetchAll();
                if (count($data) > 0) {
                    $hint = '病人ID已經重複，請確認!';
                }
            }

            // 檢查看護帳號有沒有存在
            $checkSQL = "SELECT `caretaker_account` FROM `caretaker` WHERE `caretaker_account` = '{$_POST['caretaker_account']}'";
            $result = $db->query($checkSQL);
            if ($result) {
                $data = $result->fetchAll();
                if (count($data) == 0) {
                    $hint = '看護帳號不存在，請確認!';
                }
            } else {
                $hint = '看護帳號不存在，請確認!';
            }

            if (!isset($hint)) {
                $hint = '新增病人基本資料失敗，請稍候重試';
            }
        }
    }
}
?>

<h1 style=" color: red;">新增病人 </h1>

<?php
    if (isset($hint) && $hint != '') {
?>
    <h3 align="center" style="color: red"><?= $hint ?></h3>
<?php
        unset($hint);
    }
?>

<!-- 顯示新增表單 -->
<h2 align="center"> 病人基本資料</h2>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <p align="center"> 姓名 <input type="text" name="name" value="<?= $_POST['name'] ?? '' ?>"></p>
    <p align="center"> 性別 <input type="text" name="gender" value="<?= $_POST['gender'] ?? '' ?>"></p>
    <p align="center"> 身高 <input type="number" name="height" value="<?= $_POST['height'] ?? '' ?>"></p>
    <p align="center"> 體重 <input type="number" name="weight" value="<?= $_POST['weight'] ?? '' ?>"></p>
    <p align="center"> 年齡 <input type="number" name="age" value="<?= $_POST['age'] ?? '' ?>"></p>
    <p align="center"> 看護帳號 <input type="text" name="caretaker_account" value="<?= $_POST['caretaker_account'] ?? '' ?>"></p>
    <p align="center"> 病人ID <input type="text" name="patient_ID" value="<?= $_POST['patient_ID'] ?? '' ?>"></p>
    <input type="hidden" name="token" value="<?= base64_encode(rand()) ?>" />
    <p align="center">
        <input type="submit" value="新增">
        <a href="/">取消</a>
    </p>
</form>
