<?php
require_once 'library/Gorilla3d/Session.php';
require_once 'library/Gorilla3d/Template.php';
require_once 'models/Sites.php';
$session = new Gorilla3d_Session();

if ($session->get('accountId') === null) {
    header('Location: login');
    exit;
}

$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : '';

Gorilla3d_Template::load(
    'app.php', 
    array(
        'pageTitle' => 'Editor v0.1',
        'success' => $success
    )
);
?>
