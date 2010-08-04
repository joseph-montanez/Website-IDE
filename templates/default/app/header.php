<?php Gorilla3d_Template::start('head') ?>
<style type="text/css">
body {
    margin: 0px;
    padding: 0px;
    height: 100%;
}
html {
    height: 100%;
}
* {
    font-family: monospaced;
}
#left-pane-outer {
    width: 25%;
    height: 100%;
    overflow: auto;
    background: rgb(93,93,93);
    background: -moz-linear-gradient(top, rgba(200,200,200, 1.0), rgba(93,93,93, 1));
    background-image: -webkit-gradient(
        linear, left top, 
        left bottom, 
        from(rgba(200,200,200, 1.0)), 
        to(rgba(93,93,93, 1))
    );
    float: left;
}
#right-pane-inner {
    margin: 10px;
}
#right-pane-outer {
    width:75%; 
    height: 100%; 
    overflow: auto; 
    float: left;
}
</style>
<?php Gorilla3d_Template::end('head') ?>
<?php Gorilla3d_Template::load('header.php', array('pageTitle' => $pageTitle)) ?>
<div id="left-pane-outer"><div id="left-pane-inner">
    <h2></h2>
    <ul>
        <li>
            <a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>/app/connection-edit">
                Add FTP/SFTP
            </a>
        </li>
        <li><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>/logout">Logout</a></li>
    </ul>
</div></div>
<div id="right-pane-outer"><div id="right-pane-inner">
    <h1>Editor</h1>
