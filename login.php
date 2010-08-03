<?php
require_once 'library/Gorilla3d/Session.php';
require_once 'library/Gorilla3d/Template.php';
require_once 'models/Accounts.php';
$session = new Gorilla3d_Session();

if($session->get('accountId') !== null) {
    header('Location: app');
    exit;
}

$error = false;
$password = isset($_POST['password']) ? $_POST['password'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';

if (!empty($_POST)) {
    Accounts::setDatabase($db);
    $newAccount = false;
    if (!Accounts::exists()) {
        Accounts::createTable($db);
        $newAccount = true;
    }
    $account  = false;
    
    if ($newAccount) {
        $account = new Accounts();
        $account->setPasswd($password)
            ->setUsername($username);
        Accounts::insert($account);
        unset($account);
    }
    $account = Accounts::byLogin($username, $password);
    
    
    if ($account === false) {
        $error = 'Unable to Login';
    } else {
        $session->set('accountId', $account->id);
        header('Location: app');
        exit;
    }
}


Gorilla3d_Template::load('login.php', array(
    'pageTitle' => 'Login',
    'error' => $error,
    'username' => $username,
    'password' => $password
));
?>
