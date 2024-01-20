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
		<div class= "w3-container"><p>
			In the year 2023, Clemson CCIT updated the University's
			web page services from PHP 5.6 to PHP 8.2, which not only removed some features but also added a selection of timesavers, useful functions, and more. 
      			This game serves as an exhibition of these new features. By looking at the source code on GitHub,
			you can be able to observe these new features in-depth</p></div>
		<div class = "w3-container"><h2>How to Play</h2></div>
		<div class= "w3-container"><p>
      			The goal of the game is simple: Within 4 attempts, correctly guess a random letter in the alphabet. 
      			If you miss all 4 attempts, you lose the game, however, if you fail on your 1st, 2nd, or 3rd try, 2 hints will be given: 
      			Whether the correct answer is before or after your guess, and how close your guess is to the answer. 
     			To provide a fair challenge, the distance hint will be relatively vague. The controls for this game, as well as the interface, are provided 
     			in the white box below this description. It will display the number of attempts you will have left, and the hints, as well as a submit button.  
                        When you are ready to start the game, please press the Submit button in the white box below.
		</p></div>
		<div><p><strong>Note:</strong> If you want to reset the game, click the button that says Reset the Game.</p></div>
			
	<!--/* Create the game box. This is where all of the PHP code resides */-->
		<div class = "w3-container w3-card-4 w3-white w3-padding">
			<?php
				# HELPFUL FUNCTIONS: The following 3 commands are useful for debugging your PHP website. Provided that no fatal errors occur, these
                                #                    3 functions will report all errors in your PHP script by printing error messages to your web page.
				ini_set( 'display_errors', true );
				ini_set( 'display_startup_errors', true );
				ini_set( 'error_reporting', E_ALL | E_STRICT );
                                /*
				  Set up a session. This replaces the need for cookies, as the values will be stored server-side. 
                                  Each visitor will have a different session ID that maps to a unique set of variables that the user "has".
                                  In this website, the following variables will be stored in the session:
				  	int state: This variable represents the state of the game. There are 2 possible states,
                                                   either the game has ended (represented by 0), or the game is currently ongoing (represented by 1.
				        string ans: This variable represents the correct answer to the game. It should be a 1-character string that is a
	                                            lowercase letter.
					int atts: This variable represents how much attempts the user has left. The value of this variable may change depending on the
                                                  user's actions, but it is always positive and less than 5.
                                */
				session_start();
				
				
				// Define the value newState, which represents the state of the game after the user's actions, and
                                // initialize it to the value stored in the state session variable, if it exists.
                                // If the state session variable does not exist, then set it to 0.
                                # NEW FEATURE: The following expression uses the null coalescing operator, which has been introduced to PHP in PHP 7.0
                                #              The null coalescing operator is represented by ??, and behaves like a ternary expression.
                                #              If the value to the left of the ?? is null (that is, the value does not exist), then the statement returns the
                                #              value to the right of the ??. If the value to the left of the ?? is not null, then statement returns that value.
                                #              The null coalescing operator is best used to define default values for a variable in a single line of code.
				$newState = $_SESSION["state"] ?? 0;
				

				// The following functions will now be defined:
                                //	Function 1: random_char
                                //      Function 2: relative_loc
                                //      Function 3: game_state
				/*
                                  Function 1: random_char(): string
				  Description: A letter randomizer that returns a random letter via using a randomizer to search for a random letter
                                               in an array of lowercase letters.
				  Parameters: None
				  Return: A random lowercase letter in the English Alphabet.
				  Preconditions: None
				  Postconditions: random_letter() <= ‘z’ && random_letter() >= ‘a’
                                */
				# NEW FEATURE: Since PHP 7.0, it is possible to declare return types for functions. Return type declarations specify the type of variable
                                #              that is returned from a function, just as argument type declarations specify the type for function arguuments.
                                #              To declare a return type for a function, simply add a colon after the argument parentheses and type in the type that is
                                #              to be returned from said function.
				function random_char(): string{
					// Initialize an array consisting of lowercase letters sorted in alphabetic order.
					$alphabet = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 
									 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
									 'y', 'z');
					// Generate a "random" number between 0 and 25, inclusive, using the following formula:
					//  ((second * (day of the week + 1) + (minute * hour) + (day * month) + Year) % 26
					$timeInt = ((idate('s') * (idate('w') + 1)) + (idate('i') * idate('h')) + 
							(idate('d') * idate('m')) + idate('y')) % 26;
					// Return a lowercase letter from the alphabet that corresponds to the value of the random number
					return $alphabet[$timeInt];
				}
			
				/* 
                                  Function 2: relative_loc(string guess, string ans, int direction): string
				  Description: A location calculator that returns the relative distance between the answer and the guess. 
                                               If the guess is more than 13 letters away from the answer, this function returns “Cold”. 
					       If the guess is more than 6 letters away from the answer, ths function returns “Cool”. 
					       If the guess is more than 3 letters away from the answer, this function returns “Warm”. 
                                               If the guess is less than 4 letters away from the answer, this function returns “Hot”. 
					       The direction integer is what is returned from a spaceship operator between guess and ans.
				  Parameters:
				 	 string guess: The user’s guess. Should be a lowercase letter
				 	 string ans: The user’s answer. Should be a lowercase letter
				         int direction: The direction to where the correct answer is. Either a 1 or -1.
				  Returns: A string that roughly explains how far guess is from ans (see description for a more elaborate explanation)
				  Preconditions: ‘a’ <= guess <= ‘z’ && ‘a’ <= ans <= ‘z’ && abs(direction) < 2
				 		  && (guess <=> ans) == direction
				  Postconditions: direction == #direction && guess == #guess && ans == #ans 
                                                  && [relative_loc returns a String that is specified in the description]
				 */
				# NEW FEATURES: Since PHP 8.0, it is now possible to separate parameters in parameter lists with line breaks,
                                #               as seen in the following function declaration. Additionally, trailing commas
                                #               (that is, commas that do not precede further parameter declarations) can now be added in parameter lists.
				function relative_loc(string $guess,
						      string $ans,
						      int $direction,): string
				{
					//Find the absolute difference between guess and ans, noted as dist.
					# USEFUL FUNCTION: The ord function converts the first character of a string to its ASCII numerical equivalent.
					$dist = (ord($guess) - ord($ans)) * $direction;					
					/* 
                                         Branch to one of the following cases if its respective condition is met:
					     Case 1: dist is greater than 13.
					     Case 2: dist is between 13 and 7, inclusive
					     Case 3: dist is between 6 and 4, inclusive.
					     Case 4: dist is less than 4.
                                        */
					if($dist > 13){
						// Case 1's conditions are satisfied, therefore, the user's guess is far away from the correct answer. 
						// Return "Cold" in bold and in blue text.
						return '<strong class = "w3-text-blue" >Cold</strong>';
					} elseif($dist > 6) {
						// Case 2's conditions are satisfied, therefore the user's guess is not that far away from the correct answer. 
						// Return "Cool" in bold and in light blue text.
						return '<strong class = "w3-text-light-blue" >Cool</strong>';
					}elseif($dist > 4){
						// Case 3's conditions are satisfied, therefore the user's guess is near the correct answer.
						// Return "Warm" in bold and in orange text.
						return '<strong class = "w3-text-orange" >Warm</strong>';
					}else{
						// Case 4's conditions are satisfied, therefore, the user's guess is very close to the correct answer.
						// Return "Hot" in bold and in red text.
						return '<strong class = "w3-text-red" >Hot</strong>';
					}
					
					
				}
				/*
                                  Function 3: game_state(string guess, string ans, int atts): int
				  Description: This function handles the process of a single step in the game: It checks if guess is equal to ans, and if so, 
                                               0 is returned, and a victory message is printed. Otherwise, atts is subtracted by one, and if it is then 0, 
					       0 is returned, and a loss message is printed. Otherwise, the number of attempts, the user's guess, 
	                                       the direction to which the correct answer is in respect to the guess, and a string representing the relative distance 
					       from the user's guess to the correct answer are printed to the screen, and 1 is returned.
				  Parameters:
				  	string guess: The user’s guess
					string ans: The correct answer
					int atts: The amount of attempts the user has left.
				  Returns: A 1 if the user has neither won nor lost, and a 0 if the user has either won or lost.
				  Preconditions: ‘a’ <= guess <= ‘z’ && ‘a’ <= ans <= ‘z’ && 1 <= atts <= 4
				  Postconditions: guess == #guess && ans == #ans && atts == #atts - abs(guess <=> ans)
				*/
				# NEW FEATURE: The union type is a new type introduced in PHP 8.0, and represents a value 
                                #              that can also be expressed by one of the types adjacent to the union operator ( | ). 
                                #              For example, a variable declared with type bool | int can either be an integer or a boolean value.
				function game_state(string $guess, 
						    string $ans, 
						    int $atts,): int | bool
				{
					// Compare guess and ans using the spaceship operator.
					# NEW FEATURE:
					$direction = $guess <=> $ans;
					#Then, branch for the following cases:
					#	Case 1: $direction == 0
					#	Case 2: $direction != 0
					if($direction == 0){
						#Case 1 is satisfied.
						#In this case, the user has won the game, and thus the following steps must be taken.
						#Print out a victory message, congratulating the player on winning the game.
						echo("<div><p>Congratulations! You have correctly guessed the letter, which was <strong>" . $ans . "</strong>. 
                                                      If you would like to try again, please press Submit. </p></div>");
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
						# Then, branch for the following cases:
						# 	Case 2A: atts is greater than 0.
						# 	Case 2B: atts == 0.
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
							# 	Case 2Aa: direction == 1
							# 	Case 2Ab: direction == -1
							#Alternatively, this can be done with ternaries, and by shifting direction so that ternaries are supported. 
                                                        # If direction is 1, then the correct answer will be to the left of the user's guess.
							#If the direction is -1, then the correct answer  will be to the right of the user's guess
							$hack_dir = $direction + 1;
							echo('<div><p>#1: The correct answer is to the ' . ($hack_dir ? '<strong>left' : '<strong>right') . 
							     '</strong> of your previous guess. </p></div>');
							
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
					
					#First, set the value of attsLeft using the null coalescing operator to safely set it to 0 
					# if the session variable hasn't been set (it will be set later)
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
					# 	Case 1A: attsLeft = 0 (the game has just started)
					# 	Case 1B: attsLeft != 0 (the game has not started yet.)
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
						#	Case 1Ba: The user's post does not exist or it is a non-character.
						#	Case 1Bb: The user's post is a character.
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
