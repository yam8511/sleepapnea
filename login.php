<?php
    require('check_login.php');

    if (isset($_POST['token'])) {
        ob_start();
        $_username = $_POST['username'] ?? '';
        $_password = $_POST['password'] ?? '';
        $_job = $_POST['job'];

        if ($_username !== '') {
            require('db.php');
            $sql = "
                SELECT * FROM `{$_job}`
                WHERE `{$_job}_account` = '{$_username}' AND `password` = '{$_password}'
            ";
            $result = $db->query($sql);
            if ($result) {
                $row = $result->fetch();
                if ($row) {
                    // 註冊成功，自動登入
                    setcookie("session", base64_encode(json_encode([
                        'name' => $row['name'],
                        'job' => $_job,
                    ])), time()+3600*24);
                    header("Location: /");
                }
            }

            $hint = '帳號密碼錯誤，請重新登入！！！';
        } else {
            $hint = '請輸入帳號密碼';
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

    <style>
        button {
            cursor: pointer;
        }
    </style>
</head>

<body>

    <h1 style=" color: red;">登入 </h1>
    
    <?php
        if ($hint != '') {
    ?>
        <h3 align="center" style="color: red"><?= $hint ?></h3>
    <?php
        }
        unset($hint);
    ?>
    <form action="login.php" method="POST">

        <p align="center">
            帳號 <input type="text" name="username" value="<?= $_POST['username'] ?>" />
        </p>
        <p align="center">
            密碼 <input type="password" name="password" value="<?= $_POST['password'] ?>" />
        </p>

        <div align="center">
            <input type="radio" name="job" value="family" <?= $_POST['job'] === 'family' ? 'checked' : '' ?>>家屬
            <input type="radio" name="job" value="caretaker" <?= $_POST['job'] === 'caretaker' ? 'checked' : '' ?>>看護
        </div>

        <input type="hidden" name="token" value="<?= rand() ?>" />

        <br>
        <div align="center">
            <a href="/register.php">註冊</a>
            <input type="submit" value="登入" />
        </div>

    </form>

</body>

</html>
