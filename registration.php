<!DOCTYPE html>
<html>
    <head>
        <title>Registration page</title>
        <link rel="stylesheet" href="style.css">
        <?php include("navbar.php"); ?>
    </head>
    <body>
    <div class = "main" id = "main">
        <form method = "POST">
            <div class = "userForm">
            Username: <br>
            <input type = "text" size = "50" name = "username">
        </form>
            <?php 
            echo '<br>';
            if (isset($_POST['username'])) {
                $name = $_POST["username"];
            } else {
                $name = "";
            }
            ?>
            <script type="text/javascript"> 
                var name = "<?php echo $name; ?>";
            </script>
            <?php
            if (isset($_POST['username'])) {
                $error = false;
                if(empty($name)) {
                    $error = true;
                    echo '<div class = "noInput">
                    <br>(Please enter a username)<br></div>';
                }
                if (preg_match("/[!\@\#\%\&\*\(\)\+\=\{\}\[\]\-\;\:\"\'\<\>\?\/]/", $name)) {
                    $error = true;
                    echo '<div class = "noInput">
                    <br>(The username must not contain any invalid characters: !@#%&*()+={}[]-;:\"\'<>?)<br></div>';
                }
                
                if (!$error) {
                    session_start();
                    $_SESSION["name"] = $name;
                    setcookie("name", 0);
                    echo '<script type="text/javascript"> 
                            //var name = "<?php echo $name; ?>";
                            //var name = $name;
                            sessionStorage.setItem("name", name); </script>';
                    echo '<div class = "cookies"> 
                    <br>Session and cookie created! <br>
                    </div>';
                }
            }
            ?> 
            <br>
            </div>
        
        <div class = "avatar">
        Avatar: <br>
        <div class = "avatarInstructions">
        Press: <br>
        's' to change colour of skin <br>
        'e' to change shape of eyes <br>
        'm' to change shape of mouth <br>
        'Enter' to confirm choices
        </div>
        <img class = "Skin" id = "Skin" src = "emoji assets/skin/green.png" name = "Skin">
        <script>
            //declaring an array to store the skin images
            var skin = [];
            skin[0] = "emoji assets/skin/green.png";
            skin[1] = "emoji assets/skin/red.png";
            skin[2] = "emoji assets/skin/yellow.png";
            //an event listener to change the image as required
            window.addEventListener('keydown', NextSkin);
            var skinNum = 0;
            function NextSkin() {
                if (event.key == "s") {
                    skinNum ++;
                    document.getElementById("Skin").src = skin[skinNum % skin.length];
                }
            }
        </script>
        <img class = "Eyes" id = "Eyes" src = "emoji assets/eyes/closed.png" name = "Eyes">
        <script>
            //declaring an array to store the eye images
            var eye = [];
            eye[0] = "emoji assets/eyes/closed.png";
            eye[1] = "emoji assets/eyes/laughing.png";
            eye[2] = "emoji assets/eyes/long.png";
            eye[3] = "emoji assets/eyes/normal.png";
            eye[4] = "emoji assets/eyes/rolling.png";
            eye[5] = "emoji assets/eyes/winking.png";
            //an event listener to change the image as required
            window.addEventListener('keydown', NextEye);
            var eyesNum = 0;
            function NextEye() {
                if (event.key == "e") {
                    eyesNum ++;
                    document.getElementById("Eyes").src = eye[eyesNum % eye.length];
                }
            }
        </script>
        <img class = "Mouth" id = "Mouth" src = "emoji assets/mouth/open.png" name = "Mouth">
        <script>
            //declaring an array to store the mouth images
            var mouths = [];
            mouths[0] = "emoji assets/mouth/open.png";
            mouths[1] = "emoji assets/mouth/sad.png";
            mouths[2] = "emoji assets/mouth/smiling.png";
            mouths[3] = "emoji assets/mouth/straight.png";
            mouths[4] = "emoji assets/mouth/surprise.png";
            mouths[5] = "emoji assets/mouth/teeth.png";
            //an event listener to change the image as required
            window.addEventListener("keydown", NextMouth);
            var mouthNum = 0;
            function NextMouth(event) {
                if (event.key == "m") {
                    mouthNum ++;
                    document.getElementById("Mouth").src = mouths[mouthNum % mouths.length];
                }
            }
        </script>
        </div>
        <br>
        <div class = "start" id = "start">
        </div>
        <script>
            window.addEventListener("keydown", AvatarCreator);
            function AvatarCreator(event) {
                if (event.key == "Enter") {
                    let skinImage = document.querySelectorAll('[name = Skin]')[0];
                    let eyeImage = document.querySelectorAll('[name = Eyes]')[0];
                    let mouthImage = document.querySelectorAll('[name = Mouth]')[0];
                    const avatarImage = document.getElementsByClassName("navbar-Avatar");
                    const context = avatarImage[0].getContext("2d");
                    avatarImage[0].width = skinImage.width;
                    avatarImage[0].height = skinImage.height;
                    context.drawImage(skinImage, 0, 0, avatarImage[0].width, avatarImage[0].height);
                    context.drawImage(eyeImage, 0, 0, avatarImage[0].width, avatarImage[0].height);
                    context.drawImage(mouthImage, 0, 0, avatarImage[0].width, avatarImage[0].height);
                    //save avatar image in cookie, so navbar can add it when saved
                    const avatarImage1 = document.getElementsByClassName("navbar-Avatar");
                    imgData = getBase64Image(avatarImage1[0]);
                    localStorage.setItem("imgData", imgData);
                    function getBase64Image(img) {
                        var canvas = document.createElement("canvas");
                        canvas.width = img.width;
                        canvas.height = img.height;
                        var ctx = canvas.getContext("2d");
                        ctx.drawImage(img, 0, 0);
                        var dataURL = canvas.toDataURL("image/png");
                        return dataURL;
                    }

                    var pairsLink = document.createElement('a'); 
                    var link = document.createTextNode("Start game");
                    pairsLink.appendChild(link); 
                    pairsLink.href = "pairs.php"; 
                    var main = document.getElementById("start");
                    main.appendChild(pairsLink);
                }
            }
        </script>
    </div>
    </body>
</html>