<?php
require_once 'library/Gorilla3d/Session.php';
require_once 'library/Gorilla3d/Template.php';
require_once 'models/Connections.php';
$session = new Gorilla3d_Session();

if ($session->get('accountId') === null) {
    header('Location: ../login');
    exit;
}

$error = false;
$success = false;

$password = isset($_POST['password']) ? $_POST['password'] : '';
$host     = isset($_POST['host']) ? $_POST['host'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';

if (isset($_POST['server'])) {
    include_once 'library/Gorilla3d/Ssh.php';
    
    $ssh = new Gorilla3d_Ssh($_POST['host'], $_POST['username'], $_POST['password']);
    if (!$ssh->isConnected()) {
        $error = 'Unable to connect';
    } else {
        $success = true;
        $connection = new Connections($db);
        $connection->setHost($host)
            ->setPort(22)
            ->setUsername($username)
            ->setPasswd($password);
    }
}

Gorilla3d_Template::load(
    'app/connection-edit.php', 
    array(
        'pageTitle' => 'Add/Edit Connection | Editor v0.1',
        'error' => $error,
        'success' => $success,
        'username' => $username,
        'password' => $password,
        'host' => $host
    )
);
?>
