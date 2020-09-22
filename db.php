<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

$driver = 'mysql';
$host = 'db';
$port = 3306;
$dbname = 'sleepapnea';
$username = 'root';
$password = 'qwe123';

try {
    $db = new PDO(
        "$driver:host=$host;dbname=$dbname;port=$port;charset=utf8",
        $username,
        $password
    );
} catch (PDOException $e) {
    echo 'DB connection failed ' . $e->getMessage();
    exit;
}
