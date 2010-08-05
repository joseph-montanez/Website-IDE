<?php
ignore_user_abort(true);

require_once 'library/Gorilla3d/Ssh.php';
require_once 'library/Gorilla3d/Ssh/Inode.php';
require_once 'library/Gorilla3d/Session.php';
require_once 'library/Gorilla3d/Template.php';
require_once 'models/Sites.php';
$session = new Gorilla3d_Session();

if ($session->get('accountId') === null) {
    header('Location: ../login');
    exit;
}

$site = Sites::byId($_REQUEST['id']);

if ($site === null) {
    
}

$ssh = new Gorilla3d_Ssh(
    $site->host, $site->username, $site->passwd
);
file_put_contents(
    'data/log.txt', "\n" . 'started: ' . date('F j, Y H:i:s a'), FILE_APPEND
);
echo '
    Connected...
    <script type="text/javascript">
    window.stop(); document.execCommand(\'Stop\');
    </script>
    <pre><!-- ' . str_repeat(" ", 1024 * 8) . ' --></pre>
';
flush();

while (1) {
    try {
        if (!$ssh->isConnected()) {
            echo 'unable to connect';
            break;
        }
        $command = file_get_contents('data/command.txt');
        file_put_contents('data/command.txt', '');
        
        if ($command == 'quit') {
            file_put_contents(
                'data/log.txt', "\n" . 'stopped: ' . 
                    date('F j, Y H:i:s a'), 
                FILE_APPEND
            );
            break;
        } else if (!empty($command)) {
            file_put_contents(
                'data/log.txt', "\n" . 'running: ' . $command . ' ' 
                    . date('F j, Y H:i:s a'), 
                FILE_APPEND
            );
            file_put_contents('data/results.txt', $ssh->command($command));
        }
        
        set_time_limit(30);
        sleep(1);
    } catch (Exception $e) {
        $error = (string) $e;
        file_put_contents('data/log.txt', "\n" . 'error: ' . date('F j, Y H:i:s a') . ' ' . $error, FILE_APPEND);
        break;
    }
}
?>
