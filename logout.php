<?php
require_once 'library/Gorilla3d/Session.php';
$session = new Gorilla3d_Session();

$session->logout();

header('Location: app');
exit;
?>
