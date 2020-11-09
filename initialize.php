<?php

require __DIR__ . '/bootstrap.php';
global $db;

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS users (
    user_id INTEGER PRIMARY KEY,
    username  TEXT NOT NULL UNIQUE,
    password  TEXT
)
SQL;

$db->exec($sql);

$sql = <<<SQL
DROP TABLE exercise
SQL;

var_dump($db->exec($sql));

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS exercise (
    user_id INTEGER NOT NULL,
    exercise_id TEXT NOT NULL,
    weight  INTEGER NOT NULL,
    date_time TEXT
)
SQL;

var_dump($db->exec($sql));



