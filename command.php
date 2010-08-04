<?php
file_put_contents('data/command.txt', $_REQUEST['command']);
file_put_contents('data/log.txt', "\ncommand: ". $_REQUEST['command'], FILE_APPEND);

while (1) {
    if (is_file('data/results.txt')) {
        $results = file_get_contents('data/results.txt');
        unlink('data/results.txt');

        if ($_REQUEST['command'] == 'quit') {
            echo 'Disconnected...';
            break;
        }

        if ($results !== false) {
            echo '<pre>', $results, '</pre>';
            break;
        }
    }
    sleep(1);
}
?>
