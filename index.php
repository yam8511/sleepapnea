<?php
    require('./check_login.php');
    require('./auth_user.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>

<body>
    <?php
        require('show_auth.php');
        require('patient_list.php');
        // if ($_auth['job'] === 'caretaker') {
        //     require('caretaker_list.php');
        // } else {
        //     require('family_list.php');
        // }
    ?>
</body>

</html>
