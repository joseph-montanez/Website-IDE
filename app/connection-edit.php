<?php
require_once 'library/Gorilla3d/Session.php';
require_once 'library/Gorilla3d/Template.php';
$session = new Gorilla3d_Session();

if($session->get('accountId') === null) {
    header('Location: ../login');
    exit;
}

$error = false;
$success = false;

if(isset($_POST['server'])) {
    require_once 'library/Gorilla3d/Ssh.php';
    
    $ssh = new Gorilla3d_Ssh($_POST['server'], $_POST['username'], $_POST['password']);
    if(!$ssh->isConnected()) {
        $error = 'Unable to connect';
    }

}

Gorilla3d_Template::load('app/connection-edit.php', array(
    'pageTitle' => 'Add/Edit Connection | Editor v0.1',
    'error' => $error,
    'success' => $success
));
?>
