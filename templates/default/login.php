<?php require 'header.php' ?>
<h1>Login</h1>
<?php echo $error ?>
<form target="runner" action="" method="post">
    <input type="text" name="username" placeholder="Username" /><br />
    <input type="password" name="password" placeholder="Password" /><br />
    <input type="submit" value="Login" />
</form>
<?php require 'footer.php' ?>
