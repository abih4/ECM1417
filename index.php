<!DOCTYPE html>
<?php
    //Start the session
    session_start();
?>
<html>
    <head>
        <title>Landing page</title>
        <link rel="stylesheet" href="style.css">
        <?php include("navbar.php")?>
    </head>
    <body>
        <div class = "main">
            Welcome to Pairs <br>
        <?php
        if(isset($_SESSION['name'])) {
            //if the user is in a registered session, allowing them to start to play the game
	        echo '<br> <form method = "POST" action = "pairs.php">
            <input type = "submit" value = "Click here to play" style = "font-size:30px"/>
            </form>';
        } else {
            //if the user is not in a registered session, allowing them to register before playing the game
	        echo '<p style = "font-size:40px; padding: 50px"> You`re not using a registered session?<br>
            <a href = "registration.php" style = "font-size:30px; color: white;">Register now</a></p>';
        }
        ?>
        </div>
    </body>
</html>