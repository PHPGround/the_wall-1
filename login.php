<?php
session_start();

?>
<!doctype html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <title>Login</title>
</head>
<body>
        <?php
        if(isset($_SESSION['error'])) 
        {
                ?>
                <p><?= $_SESSION['error']['message'] ?></p>
                <?php
        }
        ?>
        <form action="process.php" method="post"> <!-- //don't need enctype cuz no file -->
                <input type="hidden" name="action" value="login"> //NOTE value is now changed to login, so still goes to process.php, but this determines which process gets run and which one doesn't.
                <input type="text" name="email" placeholder="Enter Email">
                <input type="password" name="password" placeholder="Password">
                <input type="submit" value="Login">
        </form>
</body>
</html>