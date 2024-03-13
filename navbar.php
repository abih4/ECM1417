<?php
    //Start the session
    //session_start();
?>
<html>
    <head>
        <style type = "text/css">
			ul.navbar {
                font-family: Verdana;
                font-size: 12px;
                font-weight: bold;
                color: white;
                background-color: blue;
                position: fixed;
                top: 0px;
                width: 100%;
                overflow: hidden;
				list-style-type: none;
				margin: 0;
				padding: 0px;
				overflow: hidden;
			}
            ul.navbar li {
                color: white;
                display: inline-block;
                padding: 10px;
            }
            ul.navbar a {
                color: white;
                text-align: center;
                text-decoration: none;
            }
			ul.navbar li a:hover {
                padding: 5px;
				background-color: #007FFF;
			}
            div.navbar-right {
                float: right;
            }
            div.navbar-Avatar img {
                height: 30px;
                width: 30px;
                padding: 0px;
                margin: 0px;
                top: 5px;
                float: right;
                z-index: 5;
            }
        </style>
    </head>
    <body>
        <ul class="navbar">
			<li><a href="index.php" name="home">Home</a></li>
            <div class = "navbar-right" id = "navbar-right">
            <li><canvas class = "navbar-Avatar" width = "30" height = "30"></canvas></li>
        
            <script>
                var sessionValid = '<?= isset($_SESSION["name"])?>';
                //if there is a valid session, adding the users profile image (stored in local storage) to the navbar 
                if (sessionValid) {
                    var dataImage = new Image();
                    dataImage.src = localStorage.getItem('imgData');
                    const avatarNavbar = document.getElementsByClassName("navbar-Avatar");
                    avatarNavbar[0].width = dataImage.width;
                    avatarNavbar[0].height = dataImage.height;
                    avatarNavbar[0].getContext("2d").drawImage(dataImage, 0, 0, avatarNavbar[0].width, avatarNavbar[0].height);
                }
            </script>
        
			<li><a href="pairs.php" name="memory">Play Pairs</a></li>
            <?php        
            //if the session is valid, allowing the user to access the leaderboard, and if not, allowing the user to register
            if(isset($_SESSION["name"])) {
	        echo '<li><a href="leaderboard.php" name="leaderboard">Leaderboard</a></li>';
            } else {
	        echo '<li><a href="registration.php" name="register">Register</a></li>';
            }
            ?>	
            </div>					
		</ul>
        <div class = "navbar-Avatar">
        </div>
    </body>
</html>