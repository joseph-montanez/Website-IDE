<?php
error_reporting(E_ALL);

define('EDITOR_BLOWFISH', 'ChangeMe');
define('EDITOR_LOCALE', 'en_US.utf8');
define('EDITOR_TIMEZONE', 'America/Los_Angeles');

setlocale(LC_ALL, EDITOR_LOCALE);
date_default_timezone_set(EDITOR_TIMEZONE);

$db = false;
try {
    $db = new PDO("sqlite:data/database.sdb");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo $e->getMessage();
}
?>
