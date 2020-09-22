<?php

$allow_path = [
    '/login.php',
    '/register.php',
];

if (!in_array($_SERVER['PHP_SELF'], $allow_path)) {
    // var_dump(in_array($_SERVER['PHP_SELF'], $allow_path));
    // var_dump($_SERVER['PHP_SELF']);
    // var_dump($allow_path);
    // die();
    if (!isset($_COOKIE['session'])) {
        header("Location: /login.php");
    }
} else {
    if (isset($_COOKIE['session'])) {
        header("Location: /");
    }
}
