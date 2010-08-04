<?php
if (!is_writable('data')) {
    die('Please make your data folder writable');
}

if (!function_exists('ssh2_connect')) {
    die(
        'Please install SSH2 php extension: <b>pecl install SSH2-beta</b> or 
        <b>sudo apt-get install libssh2-php</b>'
    );
}

if (!class_exists('PDO')) {
    die('Please install PDO php extension');
}

header('Location: bootstrap.php/app');
exit;
?>
