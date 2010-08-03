<?php
require_once 'library/Gorilla3d/Session.php';
require_once 'library/Gorilla3d/Template.php';
$session = new Gorilla3d_Session();

if($session->get('accountId') === null) {
    header('Location: login');
    exit;
}

@unlink('data/command.txt');
@unlink('data/log.txt');
@unlink('data/results.txt');

Gorilla3d_Template::load('app.php', array(
    'pageTitle' => 'Editor v0.1'
));
?>
