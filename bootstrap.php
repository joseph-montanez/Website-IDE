<?php
include_once 'config.php';

$callString = str_replace('../', '', $_SERVER['PATH_INFO']);
$path = '';

if (!empty($callString)) {
    $path = realpath(dirname(__FILE__) . $callString . '.php');
}
if (!empty($path)) {
    include($path);
}

$db = null;
?>
