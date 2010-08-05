<?php require 'templates/default/app/header.php'  ?>
<form action="" method="post">
    <fieldset>
        <legend>New Site</legend>
        <label for="server">
            Host:
        </label>
        <input type="text" name="host" id="host" value="<?php echo $host ?>" />
        <br />
        
        <label for="username">
            Username:
        </label>
        <input type="text" name="username" id="username" 
            value="<?php echo $username ?>" />
        <br />
        
        <label for="password">
            Password:
        </label>
        <input type="password" name="password" id="password"
            value="<?php echo $password ?>" />
        <br />
        
        <?php if ($success) { ?>
            <input type="submit" name="saveConnection" value="Save Site" />
        <?php } else { ?>
            <input type="submit" value="Test Connection" />
            <?php echo $error ?>
        <?php } ?>
    </fieldset>
</form>
<?php require 'templates/default/app/footer.php'  ?>
