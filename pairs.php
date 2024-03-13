<?php
    //Start the session
    session_start();
?>
<?php
    //adding score to file
    function addScoresToFile() {
        if (isset($_SESSION['name'])) {
            chmod("scores.txt", 0777); //changing the file permissions to allow to be read and written 
            //writing score to file
            $scoreFile = fopen("scores.txt", "a");
            if($scoreFile == false) {
                echo ("Error in opening new file");
                }
            $level = count(explode(",", $_COOKIE["name"])) - 1; //getting the maximum level the user reached (stored in cookie)
            //creating string to store in file - using symbols to divide scores and users
            $scoreToBeAdded = $level. ">" . $_SESSION['name'] . ":" . $_COOKIE["name"] . "!"; 
            fwrite($scoreFile, $scoreToBeAdded);
            fclose($scoreFile);
            //reading file to check written to 
            $scoreFile = fopen("scores.txt", "r");
            $filesize = filesize("scores.txt");
            $filetext = fread($scoreFile, $filesize);
            echo $filetext;
            fclose($scoreFile);
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pairs game</title>
        <link rel="stylesheet" href="style.css">
        <?php include("navbar.php"); ?>
        <!--<link rel="shortcut icon" href="#">-->
    </head>
    <body>
        <div class = "main">
            <div class = "pointsDisplayed" id = "pointsDisplayed"> </div>
            <div class = "pairs" id = "pairs">
                <button id = "startGame" type = "button">Start the game</button>
                <div id = "endGame" class = "endGame">
                </div>
                <script>
                    let playing = false;
                    //adding an event listener to startGame button so will allow user to start playing
                    const startGame = document.getElementById('startGame');
                    startGame.addEventListener('click', RemoveButton);
                    function RemoveButton() {
                        //starting the music and playing the game
                        var sound = new Audio("Tyler Twombly - Background Radiation.mp3");
                        sound.play();
                        playing = true;
                        const start = Date.now();
                        startGame.parentNode.removeChild(startGame);
                        startPlaying();
                    }
                    const start = Date.now();
                    var flippedCardsImage = [] //array to store the images of cards so can compare
                    var flippedCards = []; //array to store cards that have been flipped
                    var matchedCards = []; //array to store cards that have been found to match
                    var points = 0; //integer to store points for each level based on attempts made by user 
                    var totalPoints = 0; //integer to store total number of points for whole game
                    var pointsOverLevels = ""; //string to store all the points for each level
                    var numOfCards = 0; //integer to store number of cards 
                    var level = 0; //integer to store the level
                    var loseGamePoints = (numOfCards/2) * 10; //number of pairs * 10
                    var timerID;
                    var checkLost;
                    var displayPointsTimer;
                        
                    function startPlaying() { 
                        level++;
                        var bestScores = ""; //string to store best scores after checking against previous scores
                        
                        //making background gold if user has reached a higher level than previously
                        const previousLevelNum = document.cookie.split("; ").find((row) => row.startsWith("name="))?.split("=")[1].split(",").length - 1;
                        if (level > previousLevelNum) {
                            const pairsDiv = document.getElementById("pairs");
                            pairsDiv.style.backgroundColor = '#FFD700';
                        }

                        function generateCard() {
                            //function to generate the cards for each level
                            const cardDiv = document.createElement("div");
                            cardDiv.setAttribute('class', "card");
                            cardDiv.setAttribute('name', "card");
                            const frontDiv = document.createElement("div");
                            frontDiv.setAttribute('class', 'front');
                            const frontImageNode = document.createElement("img");
                            frontImageNode.src = "frontCard.png";
                            frontDiv.appendChild(frontImageNode);
                            cardDiv.append(frontDiv);
                            const backDiv = document.createElement("div");
                            backDiv.setAttribute('class', "back");
                            const backSkinImage = document.createElement("img");
                            const backEyesImage = document.createElement("img");
                            const backMouthImage = document.createElement("img");
                            backDiv.append(backSkinImage);
                            backDiv.append(backEyesImage);
                            backDiv.append(backMouthImage);
                            cardDiv.append(backDiv);
                            const pairs = document.getElementsByClassName("pairs");
                            pairs[0].append(cardDiv);
                            numOfCards++;
                        }      
                        //generating pairs of cards (start with 2 for all levels) 
                        //adding two with each new level            
                        generateCard();
                        generateCard();

                        //shuffling cards so random cards presented
                        function shuffleCards(cards) {
                            for (const card of cards) {
                                let randNum = Math.floor(Math.random() * cards.length);
                                card.style.order = randNum;
                            }
                        }
                        
                        //setting the time for each level
                        if (level == 1) {
                            var interval = (numOfCards/4)*1000;
                        } else {
                            var interval = (numOfCards/8)*1000;
                        }
                        //calculating more points based on time passed
                        timerID = setInterval(function() {
                            points++;
                        }, interval); 

                        //checking if the user has lost the game every second
                        loseGamePoints = (numOfCards/2) * 10; //number of pairs * 10
                        var lostGame = false;
                        checkLost = setTimeout(function lost() {
                            //enabling the user to lose the game if exceeds certain amount of points
                            if (points >= loseGamePoints) {
                                clearTimeout(checkLost);
                                clearInterval(timerID);
                                clearInterval(displayPointsTimer);
                                lostGame = true;
                                totalPoints += points; //adding points to overall score
                                var pointsWithComma = points + ",";
                                pointsOverLevels += pointsWithComma; //adding points to string to be stored in file later
                                var cards = document.querySelectorAll('[name=card]');
                                for (const card of cards.values()) {
                                    //stopping the user being able to turn over more cards if game is over
                                    if (!card.classList.contains('flipped')) {
                                        card.classList.add('flipped');
                                    }
                                }
                                endGame();
                            }
                            if (lostGame === false) {
                                //checking if game has been lost if lostGame still false
                                checkLost = setTimeout(lost, 100);
                            }
                        }, 100);

                        //displaying points on screen to user
                        displayPointsTimer = setInterval(function() {
                            const pointsDisplayedPrevious = document.getElementsByTagName("h5");
                            if (pointsDisplayedPrevious.length == 2) {
                                //removing the previous points
                                pointsDisplayedPrevious[0].remove();
                                pointsDisplayedPrevious[0].remove();
                            }
                            const pointsDiv = document.getElementById("pointsDisplayed");
                            const topPointsDisplayed = document.createElement("h5");
                            const topPointsDisplayedNode = document.createTextNode("Max points: " + loseGamePoints);
                            topPointsDisplayed.appendChild(topPointsDisplayedNode);
                            pointsDiv.appendChild(topPointsDisplayed);
                            const pointsDisplayed = document.createElement("h5");
                            const pointsDisplayedNode = document.createTextNode("Points: " + points);
                            pointsDisplayed.appendChild(pointsDisplayedNode);
                            pointsDiv.appendChild(pointsDisplayed);
                        }, 10);

                        //generating emoji images
                        var currentEmojis = [];
                        function generateEmojiImages(card1, card2){
                            var face = [];
                            var skin = [];
                            skin[0] = "emoji assets/skin/green.png";
                            skin[1] = "emoji assets/skin/red.png";
                            skin[2] = "emoji assets/skin/yellow.png";
                            var randomSkin = Math.floor(Math.random() * 3);
                            var skinImage = skin[randomSkin];
                            face.push(skinImage);
                            var eye = [];
                            eye[0] = "emoji assets/eyes/closed.png";
                            eye[1] = "emoji assets/eyes/laughing.png";
                            eye[2] = "emoji assets/eyes/long.png";
                            eye[3] = "emoji assets/eyes/normal.png";
                            eye[4] = "emoji assets/eyes/rolling.png";
                            eye[5] = "emoji assets/eyes/winking.png";
                            var randomEye = Math.floor(Math.random() * 6);
                            var eyeImage = eye[randomEye];
                            face.push(eyeImage);
                            var mouths = [];
                            mouths[0] = "emoji assets/mouth/open.png";
                            mouths[1] = "emoji assets/mouth/sad.png";
                            mouths[2] = "emoji assets/mouth/smiling.png";
                            mouths[3] = "emoji assets/mouth/straight.png";
                            mouths[4] = "emoji assets/mouth/surprise.png";
                            mouths[5] = "emoji assets/mouth/teeth.png";
                            var randomMouth = Math.floor(Math.random() * 6);
                            var mouthImage = mouths[randomMouth];
                            face.push(mouthImage);
                            if (currentEmojis.includes(face)) {
                                //if the emoji already exists, finds new one
                                generateEmojiImages(card1, card2);
                            } else {
                                currentEmojis.push(face);
                                var card1Images = card1.getElementsByTagName("img");
                                var card2Images = card2.getElementsByTagName("img");
                                //starting from 1 because the element in position 0 is the front of card
                                card1Images[1].src = skinImage;
                                card1Images[2].src = eyeImage;
                                card1Images[3].src = mouthImage;
                                card2Images[1].src = skinImage;
                                card2Images[2].src = eyeImage;
                                card2Images[3].src = mouthImage;                   
                            }
                        }

                        var cards = document.querySelectorAll('[name=card]');
                        numOfCards = cards.length;
                        //generating emoji images for all the cards
                        var Cards = [];
                        for (const card of cards.values()) {
                            if (Cards.length == 0){
                                Cards.push(card);
                            } else {
                                Cards.push(card);
                                //generating emoji images once a pair of cards has been created
                                generateEmojiImages(Cards[0], Cards[1]);
                                Cards = [];
                            }
                        }
                        //shuffling cards with every game
                        shuffleCards(cards);
                        var cards_array = [...cards]; //converting cards from NodeList to Array
                        for (const card of cards_array) {
                            //adding an event listener to flip the card for every card
                            card.addEventListener('click', function() {
                                flipCard(this);
                            })
                        }
                        
                        function flipCard(card) {
                            if (!card.classList.contains('flipped')) {
                                //if the card hasn't been flipped already
                                card.classList.add('flipped');
                                card.classList.toggle("flipCard");
                                let cardImage = card.getElementsByTagName("img");
                                let cardImages = [];
                                for (let i = 1; i < 4; i++) {
                                    //need to start from 1, because 0 is front of card
                                    cardImages.push(cardImage[i].src);
                                }
                                setTimeout(function() {
                                    checkMatching(card, cardImages); //check if match
                                }, 1000); //allowing user to see cards before turning back over
                            }
                        }
                        function checkMatching(card, cardImage) {
                            flippedCardsImage.push(cardImage);
                            flippedCards.push(card);
                            var match = false;
                            for (const matchedCard in matchedCards) {
                                if (matchedCard === cardImage) {
                                    //if card is already in matched cards
                                    match = true;
                                }
                            }
                            if (!match && flippedCardsImage.length <= 2) {
                                if (flippedCardsImage.length === 2) {
                                    var noMatchFlip = false;
                                    for (var i = 0; i < 3; i++) {
                                        //need to go up to 3, because 0 is front of card
                                        //checking if any aspect of the image match - if no match on any element, then cards don't match
                                        if (flippedCardsImage[0][i] !== flippedCardsImage[1][i]) {
                                            noMatchFlip = true;
                                        }
                                    }
                                    if (noMatchFlip === false) {
                                        //the cards match, and so form a pair - add to matchedCards
                                        matchedCards.push(cardImage);
                                        //removing event listeners so cards can no longer be flipped
                                        flippedCards[0].removeEventListener('click', function() {
                                            flipCard(this);
                                        });
                                        flippedCards[1].removeEventListener('click', function() {
                                            flipCard(this);
                                        }); 
                                        flippedCards = [];
                                        flippedCardsImage = [];
                                    } else {
                                        //the cards did not match
                                        points++; 
                                        //flipping cards back over
                                        flippedCards[0].classList.toggle("flipCard");
                                        flippedCards[0].classList.remove('flipped');
                                        flippedCards[1].classList.toggle("flipCard");
                                        flippedCards[1].classList.remove('flipped');
                                        flippedCards = [];
                                        flippedCardsImage = [];
                                    }
                                }
                            }
                            if (matchedCards.length === numOfCards/2) {
                                //found all the matched cards
                                clearInterval(timerID);
                                clearTimeout(checkLost);
                                clearInterval(displayPointsTimer);
                                setTimeout(function() {
                                    endCurrentLevel();
                                }, 1000); //allowing user to see complete game before ending
                            }
                        }
                        function endCurrentLevel() {
                            totalPoints += points; 
                            var pointsWithComma = points + ",";
                            pointsOverLevels += pointsWithComma; //adding points to string to store in file later
                            //displaying scores to user
                            const scoreLevel = document.createElement("div");
                            scoreLevel.setAttribute('class', "scoreLevel");
                            scoreLevel.setAttribute('id', 'scoreLevel');
                            const text = document.createElement("h2");
                            const textNode = document.createTextNode("End of level " + level + "!");
                            text.appendChild(textNode);
                            scoreLevel.append(text);
                            const textPoints = document.createElement("h4");
                            const textPointsNode = document.createTextNode("Points: " + points);
                            textPoints.append(textPointsNode);
                            scoreLevel.append(textPoints);
                            //creating an end of the game (once 10 levels have been completed)
                            if (numOfCards === 20) {
                                //20 cards so there are 10 levels
                                const endGameWin = document.createElement("h5");
                                const endGameWinNode = document.createTextNode("You have completed all 10 levels!");
                                endGameWin.appendChild(endGameWinNode);
                                scoreLevel.appendChild(endGameWin);
                                const endGameButton = document.createElement('button');
                                endGameButton.innerText = 'End Game';
                                endGameButton.onclick = function() {
                                    scoreLevel.remove();
                                    endGame();
                                };
                                scoreLevel.append(endGameButton);
                            } else {
                                //allowing user to move to next level
                                const nextLevelButton = document.createElement('button');
                                nextLevelButton.innerText = 'Next Level';
                                nextLevelButton.onclick = function() {
                                    nextLevel()
                                };
                                scoreLevel.append(nextLevelButton);
                            }
                            const pairsDiv = document.getElementById("pairs");
                            pairsDiv.appendChild(scoreLevel);
                        }
                        function nextLevel() {
                            //moving to next level
                            const element = document.getElementById("scoreLevel");
                            element.remove();
                            matchedCards = [];
                            points = 0;
                            var cards = document.querySelectorAll('[name=card]');
                            for (const card of cards.values()) {
                                //flipping cards back over
                                card.classList.toggle("flipCard");
                                card.classList.remove('flipped');
                            }
                            startPlaying();
                        }
                        function endGame() {
                            //ending the game
                            pointsOverLevels += totalPoints;
                            const end = Date.now();
                            const textEnd = document.createElement("h2");
                            const textEndNode = document.createTextNode("End of game!");
                            textEnd.appendChild(textEndNode);
                            const element = document.getElementById("endGame");
                            element.appendChild(textEnd);
                            if (points >= loseGamePoints) {
                                //user has lost
                                const loseGame = document.createElement("h3");
                                const loseGameNode = document.createTextNode("You lose!");
                                loseGame.appendChild(loseGameNode);
                                element.appendChild(loseGame);
                            } else {
                                //user has won
                                const winGame = document.createElement("h3");
                                const winGameNode = document.createTextNode("You win!");
                                winGame.appendChild(winGameNode);
                                element.appendChild(winGame);
                                const winGameExplanation = document.createElement("h5");
                                const winGameExplanationNode = document.createTextNode("You have beaten all levels");
                                winGameExplanation.appendChild(winGameExplanationNode);
                                element.appendChild(winGameExplanation);
                            }
                            
                            var sessionValid = sessionStorage.getItem("name") != null;  
                            if (sessionValid == true) {
                                //storing scores in cookie
                                var previousScores = document.cookie.split("; ").find((row) => row.startsWith("name="))?.split("=")[1];
                                var username = sessionStorage.getItem("name");
                                var previousScoresArray = previousScores.split(",");
                                //loop through levels stored and compare if greater
                                var pointsOverLevelsArray = pointsOverLevels.split(",");
                                var levelScore = 0; //variable to track which score checking
                                if (bestScores === "") {
                                    var previousTotalScore = previousScoresArray.pop();
                                    //comparing to find the best scores across multiple attempts
                                    for (var previousScore of previousScoresArray) {
                                        if (previousScore === "0" || previousScore > parseInt(pointsOverLevelsArray[levelScore])) {
                                            bestScores += pointsOverLevelsArray[levelScore] + ",";
                                        }
                                        else {
                                            bestScores += previousScore + ",";
                                        }
                                        levelScore++;
                                    }
                                    //adding rest of scores when levels completed is greater than previous score
                                    for (var i=0; i < (pointsOverLevelsArray.length - previousScoresArray.length - 1); i++) {
                                        bestScores += pointsOverLevelsArray[previousScoresArray.length + i] + ",";
                                    } 
                                    //adding the total score to bestScores depending on which is better
                                    if (previousTotalScore === "0" || previousTotalScore > pointsOverLevelsArray[pointsOverLevelsArray.length-1]) {
                                        bestScores += pointsOverLevelsArray[pointsOverLevelsArray.length-1];
                                    } else {
                                        bestScores += previousTotalScore;
                                    }
                                    //storing values in cookie
                                    var userCookies = "name=" + bestScores;
                                    document.cookie = userCookies;
                                }
                                
                                //telling the user their scores
                                const textPoints = document.createElement("h4");
                                const textPointsNode = document.createTextNode("Total points: " + totalPoints);
                                textPoints.appendChild(textPointsNode);
                                const textTime = document.createElement("h4");
                                const timeTaken = (end-start) / 1000;
                                const textTimeNode = document.createTextNode("\nTime taken: " + timeTaken + " seconds");
                                textTime.appendChild(textTimeNode)
                                element.appendChild(textPoints);
                                element.appendChild(textTime);
                                //button to allow user to access leaderboard
                                const submitButton = document.createElement('button');
                                submitButton.innerText = 'Submit score';
                                submitButton.onclick = function() {
                                    //redirecting user to leaderboard
                                    location.href = "leaderboard.php";
                                };
                                element.appendChild(submitButton);
                                //button to allow user to play agai
                                const playAgainButton = document.createElement('button');
                                playAgainButton.innerText = 'Play again';
                                playAgainButton.onclick = function() {
                                    //redirecting user to pairs (play again)
                                    location.href = "pairs.php";
                                };
                                element.appendChild(playAgainButton);
                            }
                            console.log("<?php echo addScoresToFile()?>");
                        }
                        
                    }
                </script>
            </div>
        </div>  
    </body> 
</html>
