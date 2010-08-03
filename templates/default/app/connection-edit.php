<?php include('header.php') ?>
<form action="" method="post">
    <fieldset>
        <legend>New Connection</legend>
        <label for="server">
            Server Ip / Name:
        </label>
        <input type="text" name="server" id="server" /><br />
        
        <label for="username">
            Username:
        </label>
        <input type="text" name="username" id="username" /><br />
        
        <label for="password">
            Password:
        </label>
        <input type="password" name="password" id="password" /><br />
        
        <?php if ($success) { ?>
            <input type="submit" name="saveConnection" value="Save Connection" />
        <?php } else { ?>
            <input type="submit" value="Test Connection" />
            <?php echo $error ?>
        <?php } ?>
    </fieldset>
</form>
<?php include('footer.php') ?>
