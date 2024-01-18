<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Guess the Letter</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

	
<body>
	
	<!--/* Set the website color */-->
	<div class = "w3-container w3-sand w3-padding">
		<div class = "w3-container">
			<h1><strong>Guess the Letter</strong></h1>
		</div>
	<!--/* Add in the description of the game */-->
		<div class= "w3-container"><p>In the year 2023, Clemson CCIT updated the University's
			web page services from PHP 5.6 to PHP 8.2, which not only removed some features but also added a selection of timesavers, useful functions, and more. 
      This game serves as an exhibition of these new features. By looking at the source code on GitHub,
			you can be able to observe these new features in-depth</p></div>
		<div class = "w3-container"><h2>How to play</h2></div>
		<div class= "w3-container"><p>The goal of the game is simple: Within 4 attempts, correctly guess a random letter in the alphabet. 
      If you miss all 4 attempts, you lose the game, however, if you fail on your 1st, 2nd, or 3rd try, 2 hints will be given: 
      Whether the correct answer is before or after your guess, and how close your guess is to the answer. 
      To provide a fair challenge, the distance hint will be relatively vague. The controls for this game, as well as the interface, are provided 
      in the white box below this description. It will display the number of attempts you will have left, and the hints, as well as a submit button. 
      Since this game relies on cookies to keep track of your attempts and guesses, please turn on cookies. 
      When you are ready to start the game, please press the Submit button in the white box below.</p></div>
		<div><p><strong>Note:</strong> If you want to reset the game, click the button that says Reset the Game.</p></div>
			
	<!--/* Create the game box. This is where all of the PHP code resides */-->
		<div class = "w3-container w3-card-4 w3-white w3-padding">
			<?php
				#These 3 commands set up error reporting in your code: if there is an error, an error message will be printed to the website.
				ini_set( 'display_errors', true );
				ini_set( 'display_startup_errors', true );
				ini_set( 'error_reporting', E_ALL | E_STRICT );
				# Set up a session. This replaces the need for cookies, as the values will be stored server-side. Each visitor will have a different session ID that maps to
				#  a unique set of variables that the user "has".
				session_start();
				
				#The session has the following variables stored:
				#state: The state of the game. If the game is ongoing, it is true. Otherwise, it is false.
				#ans: The answer to the game.
				# atts: The number of attempts left.
				
				#This section defines the cookies and the functions needed for the game.
				#2 cookies will be defined here: the attempt cookie, and the game state cookie.
				#The game state cookie will be set to false using the nullsafe operator.
				#This section defines the functions needed for the game to work.
				# Note: The null coalescing operator is a new feature added to PHP. This operator
			    # returns the left operand if it exists, otherwise, it returns the right operand.
				$newState = $_SESSION["state"] ?? 0;
				#Function 1: random_char(): char
				#Description: A letter randomizer that returns a random letter via using a randomizer to search for a random letter in an array of lowercase letters.
				#Parameters: None
				#Return: A random lowercase letter in the English Alphabet.
				#Preconditions: None
				#Postconditions: random_letter() <= ‘z’ && random_letter() >= ‘a’
				#Note: PHP added the option for a strict return cast to functions, allowing functions to only return results of a certain type. In this case, the return type
				# is a string. To add strict return casting, add a colon after the parameter parentheses,
				# and add the type that will be returned.
				function random_char(): string{
					#Set up an array of random lowercase characters.
					$alphabet = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 
									 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
									 'y', 'z');
					#Generate a "random" number between 0 and 25, inclusive.
					#Note: The number is not random, so to speak, but rather calculated using a combination of the current time. The formula is as follows:
					# ((Second * (day of week + 1) + (minutes * hour) + (day * month) + Year) % 26
					# If you are reading this just to cheese the game, good luck trying to calculate the answer for a given time and then clicking it within the second.
					$timeInt = ((idate('s') * (idate('w') + 1)) + (idate('i') * idate('h')) + 
							   (idate('d') * idate('m')) + idate('y')) % 26;
					
					#Return the corresponding letter.
					return   $alphabet[$timeInt];
				}
			
				/* Function 2: relative_loc(char guess, char ans, int direction): string
				 Description: A location calculator that returns the relative distance between the answer and the guess. If the answer is more than 13 letters away, 
                     it returns “Cold”. If the answer is more than 6 letters away, it returns “Cool”. If the answer is more than 3 letters away, it returns “Warm”. 
                     Otherwise, it returns “Hot”. The direction integer is what is returned from a spaceship operator between guess and ans.
				 Parameters:
				 Char guess: The user’s guess. Should be a lowercase letter
				 Char ans: The user’s answer. Should be a lowercase letter
				 int direction: The direction to where the correct answer is. Either a 1 or -1.
				 Returns: A relative statement of the distance between guess and ans (see description)
				 Preconditions: ‘a’ <= guess <= ‘z’ && ‘a’ <= ans <= ‘z’ && abs(direction) < 2
				 				&& guess <=> ans = direction
				 Postconditions: direction == #direction && guess == #guess && ans == #ans && [relative_loc returns a String that is specified in the description]
				 */
				# Note: You can now separate parameters via line breaks in PHP, as seen in this function.
				function relative_loc(string $guess,
									 string $ans,
									 int $direction): string
				{
					#Find the absolute difference between guess and ans, noted as dist.
					#Note: The ord function converts the first character of a string to its ASCII
					# numerical equivalent.
					$dist = (ord($guess) - ord($ans)) * $direction;					
					#Then, branch for the following conditions:
					# Case 1: dist is greater than 13.
					# Case 2: dist is between 13 and 7, inclusive
					# Case 3: dist is between 6 and 4, inclusive.
					# Case 4: dist is less than 4.
					if($dist > 13){
						#Case 1 is met.
						#The user's guess is far from the correct answer, and thus "Cold" is returned
						return '<strong class = "w3-text-blue" >Cold</strong>';
					} elseif($dist > 6) {
						#Case 2 is met.
						# The user's guess is moderately close to the correct answer, and thus "Cool" is returned.
						return '<strong class = "w3-text-light-blue" >Cool</strong>';
					}elseif($dist > 4){
						#Case 3 is met.
						#The user's guess is close to the correct answer but not directly nearby.
						#Hence, "Warm" is returned.
						return '<strong class = "w3-text-orange" >Warm</strong>';
					}else{
						#Case 4 is met.
						# The user's guess is extremely close to the correct answer, and thus,
						# "Hot" is returned.
						return '<strong class = "w3-text-red" >Hot</strong>';
					}
					
					
				}
				/*Function 3: game_state(char guess, char ans, int &atts): bool
				Description: This function handles the process of a single step in the game: It checks if guess is equal to ans, and if so, true is returned, 
                     and a victory message is printed.. Otherwise, atts is subtracted by one, and if it is then 0, true is returned, and a loss message is printed. 
                     Otherwise, the number of attempts, the last guess, the direction to which the correct answer is located, and the relative distance 
                     to the correct answer are printed to the screen, and false is returned.
				Parameters:
				Char guess: The user’s guess
				Char ans: The correct answer
				Int atts: The amount of attempts the user has left.
				Returns: A boolean statement that represents the state of the game. (has it ended or has it begun)
				Preconditions: ‘a’ <= guess <= ‘z’ && ‘a’ <= ans <= ‘z’ && 1 <= atts <= 4
				Postconditions: guess == #guess && ans == #ans && atts == #atts - abs(guess ⇔ ans)
				*/
				# Note: The union type is a new type added to PHP. It defines a variable that is either one of the two types that are adjacent to a |. 
        # For example, int | bool represents either an integer or a boolean.
				function game_state(string $guess, 
									string $ans, 
									int $atts): int | bool
				{
					#First, compare $guess and $ans using the spaceship operator, which returns
					# 0 when th two operands are equal, -1 if the right operand is greater than
					# the left operand, and 1 if the left operand is greater than the right
					# operand. The value will be stored in $direction
					$direction = $guess <=> $ans;
					#Then, branch for the following cases:
					#Case 1: $direction == 0
					#Case 2: $direction != 0
					if($direction == 0){
						#Case 1 is satisfied.
						#In this case, the user has won the game, and thus the following steps must be taken.
						#Print out a victory message, congratulating the player on winning the game.
						echo("<div><p>Congratulations! You have correctly guessed the letter, which was <strong>" . $ans . "</strong>. If you would like to try again, 
                  please press Submit. </p></div>");
						# Set the global atts to 0.
						$_SESSION["atts"] = 0;
						
						# Return 0
						return 0;
					}else{
						#Case 2 is satisfied.
						#In this case, the user has guessed incorrectly. Decrement atts by 1.
						$atts = $atts - 1;
						#Update the global atts cookie.
						$_SESSION["atts"] = $atts;
						#Then, branch for the following cases:
						# Case 2A: atts is greater than 0.
						# Case 2B: atts == 0.
						if($atts > 0){
							#Case 2A is satisfied.
							#In this case, the user has some attempts left in the game.
							#Give the user 4 hints (by printing them to the screen):
							# Hint #1: The number of attempts left.
							#Print atts
							echo('<div class = "w3-text-purple"><p>Attempts Remaining: <strong>' . $atts . '</strong></p></div>');
							# Hint #2: The letter that the user entered in.
							# Print guess
							echo('<div><p>Your previous guess was <strong>' . $guess . '</strong>.</p></div>');
								
							echo('<div><div><p><strong>Hints:</strong></p></div>');
							# Hint # 3: The direction to which the correct letter is.
							#Branches off to 2 cases:
							# Case 2Aa: direction == 1
							# Case 2Ab: direction == -1
							#Alternatively, this can be done with ternaries, and by shifting direction so that ternaries are supported. 
              # If direction is 1, then the correct answer will be to the left of the user's guess.
							#If the direction is -1, then the correct answer  will be to the right of the user's guess
							$hack_dir = $direction + 1;
							echo('<div><p>#1: The correct answer is to the ' . ($hack_dir ? '<strong>left' : '<strong>right') . '</strong> of your previous guess. </p></div>');
							
							#Hint #4: How far the correct answer is.
							# Call relative_loc, and print the hint to the screen.
							$distStr = relative_loc($guess, $ans, $direction);
							echo('<div><p>#2: Your guess is ' . $distStr .'</p></div>');
							echo('</div>');
							
							#Return 1, since the game is still on.
							return 1;
						}else{
							#Case 2B is satisfied.
							#In this case, the user has lost the game, so print out a defeat message.
							echo("<div><p>You have ran out of attempts! Game Over</p></div>");
							
							#Show the user the correct answer.
							echo('<div><p>The correct answer was <strong>' . $ans . '</strong>.</p></div>');
							
							echo('<div><p>If you think you can do better on the next try, please press Submit</p></div>');
							#Return 0, since the game has ended.
							return 0;
						}
					}
					
				}
			
				
			
				#The main process of the website.
			
				# Branch for the following case:
				# Case 1: state is true
				if(($newState == 1)){
					#Case 1 is satisfied, meaning that the game is ongoing.
					
					#First, set the value of attsLeft using the null coalescing operator to safely set it to 0 if the cookie hasn't been set (it will be set later)
					#Note: Session variables are treated like cookie variables, in that they are
					# accessed by a superglobal array. However, setting a superglobal function
					# only requires a simple declaration statement, as opposed to calling a 
					# function to set a cookie.
					$attsLeft = $_SESSION["atts"] ?? 0;
					
					$reset = $_POST['reset'] ?? "off";
					
					#If the game has resetted, set attsLeft to 0
					if($reset == "on"){
						$attsLeft = 0;
					}
					#Then branch for the following cases:
					# Case 1A: attsLeft = 0 (the game has just started)
					# Case 1B: attsLeft != 0 (the game has not started yet.)
					if($attsLeft == 0){
						#Case 1A is satisfied, so set the random character using random_char
						#Call random_char
						$randChar = random_char();
						#Set the answer cookie to that random letter
						$_SESSION["ans"] = $randChar;
						
						#Set attsLeft and the atts session value to 4
						$attsLeft = 4;
						$_SESSION["atts"] = 4;
						#Display the number of attempts left.
						echo('<div class = "w3-text-purple"><p>Attempts Remaining: ' . $attsLeft . '</p></div>');
					}else{
						#Case 1B is satisfied, meaning that the user has spent at least 1 attempt.
						#Sanitize the user's answer.
						$str = $_POST['uAttempt'] ?? ' ';
						$filteredStr = htmlspecialchars($str);
						$loweredStr = strtolower($filteredStr);
						$attempt = $loweredStr[0] ?? ' ';
						#Do note that the user's answer could be blank, so branch off if the following cases are met:
						#Case 1Ba: The user's post does not exist or it is a non-character.
						#Case 1Bb: The user's post is a character.
						if(!(($attempt >= 'a') && ($attempt <= 'z'))){
							#Case 1Ba has beenn satisfied. Remind the user to input a letter.
							echo("<div><p>Your answer is invalid. Please enter a lowercase letter in the English alphabet</p></div>");
							}else{
							#Case 1Bb has been satisfied, meaning that the user has submitted a valid answer.
							#Call game_state, and set the state cookie to the result.
							$answer = $_SESSION["ans"];
							$newState = game_state($attempt, $answer, $attsLeft);
						}
						
						
					}
				}else{
					#Set newState to 1.
					$newState = 1;
				}
				
				#Set the state value to newState
				$_SESSION["state"] = $newState;
			?>
			<!---/ Display the button /--->
			<form method = "post" action = "<?php echo $_SERVER['PHP_SELF'];?>">
				<div>Enter your guess: <input type = "text" name = "uAttempt"></div>
				<div>Reset the game <input type = "checkbox" name = "reset"></div>
			<input type = "submit"> </form>
			
		</div>
	<!--/* End of game box */-->
	
	<!--/* End of website container */-->
	</div>
	
	
	
</body>
</html>
