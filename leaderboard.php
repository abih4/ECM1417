<?php
//opening and reading the file storing the scores for the leaderboard
$scoreFile = fopen("scores.txt", "r");
$filesize = filesize("scores.txt");
$filetext = fread($scoreFile, $filesize);
fclose($scoreFile);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Leaderboard</title>
        <link rel="stylesheet" href="style.css">
        <?php include("navbar.php"); ?>
    </head>
    <body>
        <div class = "main">
            <div class = "leaderboard">
                <table id = "leaderboard">
                    <tbody>
                    <tr style = "background-color: blue">
                        <th>Usernames</th>
                        <th colspan=11>Best scores</th>
                    </tr>
                    <tr style = "background-color: blue">
                        <th></th>
                        <th>Level 1</th>
                        <th>Level 2</th>
                        <th>Level 3</th>
                        <th>Level 4</th>
                        <th>Level 5</th>
                        <th>Level 6</th>
                        <th>Level 7</th>
                        <th>Level 8</th>
                        <th>Level 9</th>
                        <th>Level 10</th>
                        <th>Best Total Score</th>
                    </tr>
                    </tbody>
                </table>
                <script>
                    //filling in the table with the current user details
                    //getting the username stored in the session
                    var username = sessionStorage.getItem("name");
                    //getting the points stored in the cookie
                    var cookieValue = document.cookie.split("; ").find((row) => row.startsWith("name="))?.split("=")[1];
                    var points = cookieValue.split(",");
                    //getting the table elements and inserting the data
                    var table = document.getElementById("leaderboard");
                    var row = table.insertRow(-1);
                    var cellUsername = row.insertCell(0);
                    cellUsername.innerHTML = username;
                    for (var i = 1; i < 11; i++) {
                        var cell = row.insertCell(i);
                        if (i < points.length) {
                            cell.innerHTML = points[i-1];
                        } else {
                            cell.innerHTML = "N/A";
                        }
                    }
                    var cellTotalPoints = row.insertCell(11);
                    cellTotalPoints.innerHTML = points[points.length-1];

                    //filling in table with details from file storing other users
                    var row1 = table.insertRow(-1); //creating a gap between file data
                    var cellEmpty1 = row1.insertCell(0);
                    var cellEmpty2 = row1.insertCell(1);
                    cellEmpty2.innerHTML = "Scores";
                    var cellEmpty3 = row1.insertCell(2);
                    cellEmpty3.innerHTML = "for";
                    var cellEmpty4 = row1.insertCell(3);
                    cellEmpty4.innerHTML = "first";
                    var cellEmpty5 = row1.insertCell(4);
                    cellEmpty5.innerHTML = "10";
                    var cellEmpty6 = row1.insertCell(5);
                    cellEmpty6.innerHTML = "users";
                    var cellEmpty7 = row1.insertCell(6);
                    cellEmpty7.innerHTML = "in";
                    var cellEmpty8 = row1.insertCell(7);
                    cellEmpty8.innerHTML = "file";
                    var cellEmpty9 = row1.insertCell(8);
                    var cellEmpty10 = row1.insertCell(9);
                    var cellEmpty11 = row1.insertCell(10);
                    var cellEmpty12 = row1.insertCell(11);
                    //getting the score data from the file, and storing each score for each person in an array levelScores
                    var php_fileText = "<?php echo $filetext; ?>";
                    var people = php_fileText.split("!");
                    var levelScores = [];
                    for (const person of people) {
                        levelScores.push(person);
                    }
                    //putting the scores found above into the table displayed
                    var num = 0;
                    for (const item of levelScores) {
                        if (num < 10) {
                            var scoreWithoutLevel = item.split(">")[1];
                            console.log(scoreWithoutLevel);
                            var username = scoreWithoutLevel.split(":")[0];
                            var points = scoreWithoutLevel.split(":")[1];
                            var pointsArray = points.split(","); //splitting the points for each level
                            var row2 = table.insertRow(-1);
                            var cellUsername1 = row2.insertCell(0);
                            cellUsername1.innerHTML = username; //inserting the username
                            //inserting all the points
                            for (var j = 1; j < 11; j++) {
                                var cell1 = row2.insertCell(j);
                                if (j < pointsArray.length) {
                                    cell1.innerHTML = pointsArray[j-1];
                                } else {
                                    cell1.innerHTML = "N/A";
                                }
                            }
                            var cellTotalPoints1 = row2.insertCell(11);
                            cellTotalPoints1.innerHTML = pointsArray[pointsArray.length-1];
                        }
                        num++;
                    }
                </script>
            </div>
        </div>
    </body>
</html>