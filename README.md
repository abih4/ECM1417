Virtual machine: 
http://ml-lab-4d78f073-aa49-4f0e-bce2-31e5254052c7.ukwest.cloudapp.azure.com:61103/index.php

Run in chrome:
•	In terminal, **php -S localhost:8000 -ECM1417/**, to set up the server
•	Run, **http://localhost:8000/index.php**, in browser

index.php
•	Link to register session (registration.php) if session not set
•	If session is set, link to play game (pairs.php)

registration.php
•	Checks for valid username characters, then creates session
•	Allows avatar selection (COMPLEX) – press ‘s’, ‘e’, ‘m’ keys to change features and ‘Enter’ to submit
•	Avatar moved into navbar when selected
•	Click link to play game (pairs.php) when avatar and username selected

pairs.php
•	COMPLEX solution attempted
•	Music plays when ‘start game’ button pressed and disappears
•	Card images are randomly-generated emojis with random features (skin, eyes, mouth) and are not repeated
•	Cards shuffled when displayed 
•	Waits 1 second before cards flipped back
•	10 levels, with a new pair in each level (20 cards in level 10)
•	Points are calculated based on time taken (points added every numOfCards/8 secs) and every failed pair matching
•	Points of user and maximum points for level displayed at top
•	When user exceeds previous max level, background turns gold
•	User wins when completes level 10, user loses when points exceed max points
•	Stores users points in cookie and file
•	When game complete, user presses ‘submit’ to see leaderboard, or ‘play again’

leaderboard.php
•	Formatted table of results
•	First row is current user’s best points across levels
•	Rows 3 onwards are users from the points.txt file

Navbar
•	Displayed on all screens at the top 
•	“Home” top left, “Play Pairs” top right. If session valid, “Leaderboard” is displayed instead of “Register” if session not valid. 
•	Avatar image is displayed top right if selected
