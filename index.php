<?php
    require('./check_login.php');
    $_auth = json_decode(base64_decode($_COOKIE['session']), true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>

<body>
    <h1>Welcome Home (待做)</h1>
     
    <?php
        if (isset($_COOKIE['session'])) {
    ?>
    <h3><?= $_auth['name'] ?> (<?= $_auth['job'] === 'family' ? '家屬' : '照顧者' ?>) 您好~</h3>
    <a href="/logout.php">登出</a>
    <?php
        }
    ?>
</body>

</html>
