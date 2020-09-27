<?php
    require('check_login.php');

    if (isset($_POST['token'])) {
        ob_start();
        $check_list = [
            'username',
            'password',
            'name',
            'address',
            'phone',
            'job',
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
            require('db.php');
            $username = $_POST['username'];
            $password = $_POST['password'];
            $name = $_POST['name'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $job = $_POST['job'];

            $sql = "
            SELECT * FROM `{$job}` WHERE `{$job}_account` = '{$username}'
            ";
            $result = $db->query($sql)->fetch();
            if ($result) {
                $hint = '帳號已經存在，請填入新的帳號';
            } else {
                $sql = "
                INSERT INTO `{$job}` (`{$job}_account`, `password`, `name`, `address`, `phonenum`)
                VALUES ('$username', '$password', '$name', '$address', '$phone')
                ";
                $result = $db->exec($sql);
                if ($result) {
                    // 註冊成功，自動登入
                    setcookie("session", base64_encode(json_encode([
                        'name' => $name,
                        'job' => $job,
                    ])), time()+3600*24);
                    header("Location: /");
                } else {
                    $hint = '創建新的帳號失敗，請稍候重試';
                }
            }
        }

        ob_end_flush();
        unset($_POST['token']);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>

<body>

    <h1 style=" color: red;">註冊 </h1>

    <?php
        if (isset($hint) && $hint != '') {
    ?>
        <h3 align="center" style="color: red"><?= $hint ?></h3>
    <?php
            unset($hint);
        }
    ?>

    <form action="register.php" method="POST">
        <p align="center"> 帳號 <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>"></p>
        <p align="center">密碼 <input type="password" name="password" value="<?= $_POST['password'] ?? '' ?>"></p>
        <p align="center">姓名 <input type="text" name="name" value="<?= $_POST['name'] ?? '' ?>"></p>
        <p align="center">住址 <input type="text" name="address" value="<?= $_POST['address'] ?? '' ?>"></p>
        <p align="center">手機號碼 <input type="text" name="phone" value="<?= $_POST['phone'] ?? '' ?>"></p>

        <div align="center">
            <input type="radio" name="job" value="family" <?= ($_POST['job'] ?? '') === 'family' ? 'checked' : '' ?>>家屬
            <input type="radio" name="job" value="caretaker" <?= ($_POST['job'] ?? '') === 'caretaker' ? 'checked' : '' ?>>看護
        </div>

        <input type="hidden" name="token" value="<?= base64_encode(rand()) ?>" />

        <br>
        <div align="center">
            <input type="submit" value="確認" />
            <a href="/login.php">取消</a>
        </div>
    </form>

</body>

</html>
