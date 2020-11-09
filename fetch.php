<?php

require __DIR__ . '/bootstrap.php';
global $db;

$sql = <<<SQL
SELECT * FROM users
SQL;

var_dump($db->query($sql)->fetchAll(PDO::FETCH_ASSOC));

