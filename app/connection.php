<?php
require_once 'library/Gorilla3d/Session.php';
require_once 'library/Gorilla3d/Template.php';
require_once 'models/Sites.php';
$session = new Gorilla3d_Session();

if ($session->get('accountId') === null) {
    header('Location: ../login');
    exit;
}

$error = false;
$success = '';
$site = Sites::byId($_REQUEST['id']);


//TODO: Detect protocal
$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '/runner?id=' . $_REQUEST['id'];
$context = stream_context_create(
    array( 
    'http' => array( 
        'timeout' => 1 
        ) 
    ) 
); 
//file_get_contents($url, 0, $context); 

if ($site === false) {
    $error = 'Invalid Site';
}

Gorilla3d_Template::load(
    'app/site.php', 
    array(
        'pageTitle' => 'Site | Editor v0.1',
        'error' => $error,
        'success' => $success,
        'site' => $site
    )
);
?>
