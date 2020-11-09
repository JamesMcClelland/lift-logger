<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$db = new PDO("sqlite:".__DIR__."/weightlogger.sqlite");

