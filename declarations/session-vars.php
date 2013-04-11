<?php

/* URL LEGEND
 *
 * "t"       -- topic ID
 * "a"       -- answer ID
 * "u"       -- topic URL
 * "hm"      -- launch home page
 * "sa"		 -- launch share answer page
 * "ra"      -- launch read answer page
 * "ga"      -- launch give answer page
 * "af"      -- launch ask friend page
 * "st"      -- launch suggest topic page
 * "at"      -- launch approve topics page
 * "aa"      -- launch approve answers page
 * "bb"      -- launch buy book page
 * "gn"      -- launch get newsletter page
 * "itv"     -- launch interview page
 * "abt"     -- launch about page
 * "csa"     -- launch user content submission agreement page
 * "tos"     -- launch terms of service page
 * "ccp"     -- launch copyright compliance policy page
 * "ppo"     -- launch privacy policy page
 * "agu"     -- launch answer guidelines page
 * "thx"     -- launch thank you page
 * "err"     -- launch error page
 * "s-..."   -- designated as a submitted page
 * "adm-..." -- designated as an admin page
 *
 **************************************************/
 
// By default, the overlay window and whitewash will not be shown
$b_overlay = FALSE;

// If user is on home page for the first time
if (empty($_GET)) {

	// Set Open Graph meta tags
	$_SESSION["meta-description"] = "A collection of personal stories that answer compelling, yet rarely discussed questions, such as \"What does it feel like to be blind since birth?\"";
	$_SESSION["meta-title"] = "Too Polite To Ask: Dedicated to Understanding Others";
}
	
// If form in Share Answer page is submitted
if (isset($_REQUEST["s-sa"])) {
	emailAnswer ($_SESSION['a_selectedApprovedAnswer']['id'], $_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic'], $_REQUEST["your_username"], $_REQUEST["your_email"], $_REQUEST["friend_username"], $_REQUEST["friend_email"], $_REQUEST["message"], isset($_REQUEST["email_opt_in"]), isset($_REQUEST["cc_sender"]));
	
	// THANK YOU overlay that tells user next steps
	$_REQUEST["thx"] = "Your message has successfully been sent.<br><br><a href=\"".getUrl("what-it-feels-like-to-".stringToUrl($_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic'])."/".$_SESSION['a_selectedApprovedAnswer']['id']."/")."\">Back to answer</a>";

	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Sharing answer to a question about what it feels like to ".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic'];
	$_SESSION["meta-title"] = $_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic'];
}

// If form in Buy Book page or Get Newsletter page is submitted
if ((isset($_REQUEST["s-bb"]))||(isset($_REQUEST["s-gn"]))) {
	addAuthor ($_REQUEST["username"], $_REQUEST["email"], 1);

	// User submitted form regarding buying the book
	if (isset($_REQUEST["s-bb"])) {
	
		// THANK YOU overlay that tells user next steps
		$_REQUEST["thx"] = "We received your information and will notify you when the book is available for purchase.";

		// Set Open Graph meta tags	
		$_SESSION["meta-description"] = "Received your request to receive updates regarding the soon-to-be-released Too Polite To Ask e-book which will initially be available on the Kindle and Nook";
		$_SESSION["meta-title"] = "Buy the book";
		
	// User submitted form regarding receiving the newsletter
	} else {
	
		// THANK YOU overlay that tells user next steps
		$_REQUEST["thx"] = "We received your information and will notify you of future product announcements.";

		// Set Open Graph meta tags	
		$_SESSION["meta-description"] = "Received your request to receive the Too Polite To Ask newsletter";
		$_SESSION["meta-title"] = "Get the newsletter";
	}	
}

// If form in Give Answer page is submitted
if (isset($_REQUEST["s-ga"])) {
	addAnswer ($_SESSION['a_selectedApprovedTopic']['id'], $user_profile['name'], $user_profile['email'], $_REQUEST["answer_text"], isset($_REQUEST["anonymous"]), isset($_REQUEST["book_opt_in"]), isset($_REQUEST["email_opt_in"]));
	
	// THANK YOU overlay that tells user next steps
	$_REQUEST["thx"] = "We received your answer and will notify you if it is accepted";
	if (isset($_REQUEST["book_opt_in"])) { $_REQUEST["thx"] .= " and if it is chosen to be included in the book"; }
	$_REQUEST["thx"] .= ".";

	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Your answer to the question of what it feels like to ".$_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic']." has been received";
	$_SESSION["meta-title"] = "Answer recieved";
}

// If form in Ask Friend page is submitted
if (isset($_REQUEST["s-af"])) {
	
	emailTopic ($_SESSION['a_selectedApprovedTopic']['id'], $_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic'], $user_profile['name'], $user_profile['email'], $_REQUEST["friend_username"], $_REQUEST["friend_email"], $_REQUEST["message"], isset($_REQUEST["email_opt_in"]), isset($_REQUEST["cc_sender"]));
	
	// THANK YOU overlay that tells user next steps
	$_REQUEST["thx"] = "Your message has successfully been sent.";
	
	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Message successfully sent to your friend asking for them to tell what it feels like to ".$_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic'];
	$_SESSION["meta-title"] = "Email sent";
}

// If form in Suggest Topic page is submitted
if (isset($_REQUEST["s-st"])) {

	$pk_existingTopicId = addTopic ($_REQUEST["new_topic"], $user_profile['name'], $user_profile['email'], isset($_REQUEST["notify_author"]), isset($_REQUEST["email_opt_in"]));

	if (!$pk_existingTopicId) {
		// THANK YOU overlay that tells user next steps
		$_REQUEST["thx"] = "Your suggested topic has been received";
		if (isset($_REQUEST["notify_author"])) { $_REQUEST["thx"] .= " and we will notify you if it is accepted"; }
		$_REQUEST["thx"] .= ".";
	} else {
		$existingTopicStatus = getTopicStatus ($pk_existingTopicId);
		if (isPending($existingTopicStatus)) {
			$_REQUEST["thx"] = "Your suggested topic has already been submitted and is pending approval";
		} else if (isApproved($existingTopicStatus)) {
			$_REQUEST["thx"] = "Your suggested topic has already been submitted and is available to be answered now";
		} else if (isDenied($existingTopicStatus)) {
			$_REQUEST["thx"] = "Your suggested topic has already been submitted and has unfortunately been denied";
		} else {
			$_REQUEST["thx"] = "what? ".$pk_existingTopicId." - ".$existingTopicStatus;
		}
	}
		
	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Other popular topics that have been submitted include \"What does it feel like to have a seizure?\" and \"What does it feel like to be bullied in school?\"";
	$_SESSION["meta-title"] = "Topic received";
}

// If form in Interview Confirmation page is submitted
if (isset($_REQUEST["s-itv"])) {
	
	if (str_replace(array("\r"), array(""), $_REQUEST["biography"])==$s_biography_FM) { $_REQUEST["biography"]=""; }	
	
	confirmInterview ($_SESSION['a_selectedApprovedTopic']['id'], $_REQUEST["username"], $_REQUEST["email"], $_REQUEST["biography"], isset($_REQUEST["legal_opt_in"]), isset($_REQUEST["anonymous"]), isset($_REQUEST["email_opt_in"]));
	
	// THANK YOU overlay that tells user next steps
	$_REQUEST["thx"] = "You information has been sent.  If you have not already done so, please confirm your interview time by emailing interview@toopolite.com";
	
	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Information has successfully been sent for your interview on topic, 'What does it feels like to ".$_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic']."'";
	$_SESSION["meta-title"] = "Interview confirmed on topic 'What does it feel like to ".$_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic']."'";
}

// If this is the first time the user is accessing the website or they are returning to the home page
if((!isset($_SESSION['a_answeredTopics']))||(isset($_REQUEST["hm"]))) {

	// If this is the first time the user is accessing the website 
	if (!isset($_SESSION['a_answeredTopics'])){
	
		// Load the list of all answered topics from the database
		$_SESSION['a_answeredTopics'] = getAnsweredTopics ();
		$_SESSION['i_numAnsweredTopics']=count($_SESSION['a_answeredTopics']); 
		
		// Shuffle that list so that each time user comes to the site, he/she will see new topics
		shuffle($_SESSION['a_answeredTopics']);
		
		// Select the topic in the middle of the list so the user can scroll up or down in equal direction
		if ($_SESSION['i_numAnsweredTopics']>31) { 
			$_SESSION['a_selectedAnsweredTopic']['lcv']=16;
		} else {
			$_SESSION['a_selectedAnsweredTopic']['lcv']=floor($_SESSION['i_numAnsweredTopics']/2);
		}
		$_SESSION['a_selectedAnsweredTopic']['id']= $_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['id'];

		// Load approved answers for selected topic (only includes id and quote)
		$_SESSION['a_approvedAnswers'] = getApprovedAnswers($_SESSION['a_selectedAnsweredTopic']['id']);
		$_SESSION['i_numApprovedAnswers'] = count($_SESSION['a_approvedAnswers']);
		
		// Set the selected answer to be the first one in the list
		$_SESSION['a_selectedApprovedAnswer']['lcv']= 0;
		$_SESSION['a_selectedApprovedAnswer']['id']= $_SESSION['a_approvedAnswers'][0]['id'];
	}
	
	// If the user is returning to the home page
	if (isset($_REQUEST["hm"])) {	

		// If the user has selected a new approved answer
		if (isset($_REQUEST["a"])) {

			// Check to see if answer is approved
			if (isAnswerApproved($_REQUEST["a"]) ) {

				// Set the selected answer id to the number passed in the URL
				$_SESSION['a_selectedApprovedAnswer']['id']= $_REQUEST["a"];

				// Set the selected answer lcv to correspond to the answer id passed in the URL
				$found = FALSE;
				foreach ($_SESSION['a_approvedAnswers'] as $i_count => $a_approvedAnswer) {
					if ($a_approvedAnswer['id'] == $_SESSION['a_selectedApprovedAnswer']['id']){
						$_SESSION['a_selectedApprovedAnswer']['lcv']= $i_count;
						$found = TRUE;
					}
				}
				
				if (!$found) {
					$_REQUEST["t"] = getAnswerTopic ($_REQUEST["a"]);
				} else {

					// Set Open Graph meta tags specific to this topic and topic answer
					$_SESSION["meta-description"] = "The topic '".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']."' answered by ".$_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['name']." is currently highlighted.  To view the full story, click on the link entitled 'Read More'";
					$_SESSION["meta-title"] = "Selected '".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']."' answered by ".$_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['name'];
				}
					
			} else {
				// send user to error page that shows other related topics and stories
				$_REQUEST["err"] = "answer-not-found";
			}	
		}
		
		// if the user has selected a new topic on the home page
		if (isset($_REQUEST["t"])) {

			// Check to see if topic is approved (unapproved topics should direct users to last selected topic)
			if (isTopicApproved($_REQUEST["t"]) ) {
			
				// Check to see if topic is answered and that answer is approved
				if (isTopicAnswered($_REQUEST["t"])) {
				
					// Set the selected topic id to the number passed in the URL
					$_SESSION['a_selectedAnsweredTopic']['id']=$_REQUEST["t"];

					// Set the selected topic lcv to correspond to the topic id passed in the URL
					foreach ($_SESSION['a_answeredTopics'] as $i_count => $a_answeredTopic) {
						if ($a_answeredTopic['id'] == $_SESSION['a_selectedAnsweredTopic']['id']){
							$_SESSION['a_selectedAnsweredTopic']['lcv'] = $i_count;
						}
					}

					// Load approved answers for selected topic (only includes id and quote)
					$_SESSION['a_approvedAnswers'] = getApprovedAnswers($_SESSION['a_selectedAnsweredTopic']['id']);
					$_SESSION['i_numApprovedAnswers'] = count($_SESSION['a_approvedAnswers']);

					// Set the selected answer to be the first one in the list
					$_SESSION['a_selectedApprovedAnswer']['lcv']= 0;
					$_SESSION['a_selectedApprovedAnswer']['id']= $_SESSION['a_approvedAnswers'][0]['id'];

					// only occurs if answer not corresponding to original topic # is typed in
					if (isset($_REQUEST["a"])) {
					
						// Set the selected answer id to the number passed in the URL
						$_SESSION['a_selectedApprovedAnswer']['id']= $_REQUEST["a"];

						// Set the selected answer lcv to correspond to the answer id passed in the URL
						foreach ($_SESSION['a_approvedAnswers'] as $i_count => $a_approvedAnswer) {
							if ($a_approvedAnswer['id'] == $_SESSION['a_selectedApprovedAnswer']['id']){
								$_SESSION['a_selectedApprovedAnswer']['lcv']= $i_count;
							}
						}

						// Set Open Graph meta tags specific to this topic and topic answer
						$_SESSION["meta-description"] = "The topic '".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']."' answered by ".$_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['name']." is currently highlighted.  To view the full story, click on the link entitled 'Read More'";
						$_SESSION["meta-title"] = "Selected '".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']."' answered by ".$_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['name'];
					} else {

						// Set Open Graph meta tags specific to this topic
						$_SESSION["meta-description"] = "The topic '".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']."' is currently highlighted.  To view the full story, click on the link entitled 'Read More'";
						$_SESSION["meta-title"] = "Selected '".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']."'";
					}	
				} else {
					// send user to error page that shows other related topics and stories
					$_REQUEST["err"] = "topic-not-found";
				}	

			} else {
				// send user to error page that shows other related topics and stories
				$_REQUEST["err"] = "topic-not-found";
			}	
		}
	}
}

// If the user wants to read the full text of an answer or share the answer with a friend
if ((isset($_REQUEST["ra"]))||(isset($_REQUEST["sa"]))){

	// If the user has selected an answer to read (they must)
	if (isset($_REQUEST["a"])) {

		// Check to see if answer is approved (unapproved answers should direct users to home page)
		if (isAnswerApproved($_REQUEST["a"])) {
	
			// Set the selected answer id to the number passed in the URL
			$_SESSION['a_selectedApprovedAnswer']['id']= $_REQUEST["a"];
			
			// Check if answer id is in approved answer list for selected topic
			// If not, the user likely linked directly to a story from external source
			$b_isAnswerInSelectedTopic = FALSE;
			foreach ($_SESSION['a_approvedAnswers'] as $i_count => $a_approvedAnswer) {
			
				// If selected answer is in answer list for selected topic
				if ($a_approvedAnswer['id'] == $_SESSION['a_selectedApprovedAnswer']['id']){
				
					// Update answer lcv to correspond to the answer id passed in the URL
					$b_isAnswerInSelectedTopic = TRUE;
					$_SESSION['a_selectedApprovedAnswer']['lcv']= $i_count;
				}
			}
			
			// If the selected answer is not in the answer list for selected topic
			if (!$b_isAnswerInSelectedTopic) {
			
				// Use answer id passed in the URL to retrieve topic id from database
				$_SESSION['a_selectedAnsweredTopic']['id'] = getAnswerTopic ($_SESSION['a_selectedApprovedAnswer']['id']);

				// Set the selected topic lcv to correspond to the topic id retrieved from database
				foreach ($_SESSION['a_answeredTopics'] as $i_count => $a_answeredTopic) {
					if ($a_answeredTopic['id'] == $_SESSION['a_selectedAnsweredTopic']['id']){
						$_SESSION['a_selectedAnsweredTopic']['lcv']= $i_count;
					}
				}
				
				// Retrieve list of answers for topic id retrieved from database
				$_SESSION['a_approvedAnswers'] = getApprovedAnswers($_SESSION['a_selectedAnsweredTopic']['id']);
				$_SESSION['i_numApprovedAnswers'] = count($_SESSION['a_approvedAnswers']);

				// Set the selected answer lcv to correspond to the answer id passed in the URL
				foreach ($_SESSION['a_approvedAnswers'] as $i_count => $a_approvedAnswer) {
					if ($a_approvedAnswer['id'] == $_SESSION['a_selectedApprovedAnswer']['id']){
						$_SESSION['a_selectedApprovedAnswer']['lcv']= $i_count;
					}
				}
			}

			// For when Read Answer page is launched
			if (isset($_REQUEST["ra"])){

				// Since topic string is passed in the URL (for SEO purposes) in addition to answer ID, check against user manually typing in answer ID with wrong topic string
				// If topic string passed in URL matches topic string of selected answer
				if ($_REQUEST["u"] == $_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['url']) {

					// Display overlay window on top of washed-out homepage
					$b_overlay = TRUE;
					
					// Load all approved answer information
					$_SESSION['a_approvedAnswer'] = getAnswer ($_SESSION['a_selectedApprovedAnswer']['id']);

					// Set Open Graph meta tags
					$_SESSION["meta-description"] = $_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['quote']."...";
					$_SESSION["meta-title"] = "What does it feel like to ".$_SESSION['a_approvedAnswer']['topic']."?";

					// Retrieve other topics in same category
					$_SESSION["a_categoryTopics"] = getCategoryTopics ($_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['categoryId'], $_SESSION['a_selectedAnsweredTopic']['id']);

					// Shuffle that list so that each time user read the article, he/she will see new topics
					shuffle($_SESSION['a_categoryTopics']);
					
					// increment answer view count
					incrementViewCount( $_SESSION['a_selectedApprovedAnswer']['id'] );

				// If topic string passed in URL does not match topic string of selected answer
				} else {
					
					// redirect user to correct url based on answer ID
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header( "Location: ".getUrl("what-it-feels-like-to-".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['url']."/".$_SESSION['a_selectedApprovedAnswer']['id']."/") );
				}
				
			// For sharing an answer
			} else if (isset($_REQUEST["sa"])){
			
				// Display overlay window on top of washed-out homepage
				$b_overlay = TRUE;

				// Set Open Graph meta tags
				$_SESSION["meta-description"] = $_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['quote']."...";
				$_SESSION["meta-title"] = "Share answer about what it feels like to ".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic'];
			}
		} else {

			// send user to error page that shows other related answers
			$_REQUEST["err"] = "answer-not-found";

			// don't go to expected pages
			unset($_REQUEST["ra"]);
			unset($_REQUEST["sa"]);
		}		
	}
	
	// If the user did not provide an answer id to read or the answer id is not approved
	If (!$b_overlay) {
		
		// Send to home page
		// Set Open Graph meta tags
		$_SESSION["meta-description"] = "A collection of personal stories that answer compelling, yet rarely discussed questions, such as \"What does it feel like to flee your home country?\"";
		$_SESSION["meta-title"] = "Too Polite To Ask: Dedicated to Understanding Others";
	}
}

// If the user wants to submit a new answer, confirm an interview, ask a friend for an answer or suggest a new topic; or if an admin wants to approve or update topics
if ((isset($_REQUEST["bb"]))||(isset($_REQUEST["gn"]))||(isset($_REQUEST["itv"]))||(isset($_REQUEST["ga"]))||(isset($_REQUEST["af"]))||(isset($_REQUEST["st"]))) {

	// Display overlay window on top of washed-out homepage
	$b_overlay = TRUE;
	
	// If this is the first time the user or admin has loaded the list of approved topics
	if(!isset($_SESSION['a_approvedTopics'])) {
	
		// Retrieve list of approved topics from the database (arranged in database alphabetically)
		$_SESSION['a_approvedTopics'] = getApprovedTopics();
		$_SESSION['i_numApprovedTopics'] = count($_SESSION['a_approvedTopics']); 
		
		// Default selected topic to first one in list
		$_SESSION['a_selectedApprovedTopic']['lcv'] = 0;
		$_SESSION['a_selectedApprovedTopic']['id'] = $_SESSION['a_approvedTopics'][0]['id'];
	}
	
	if (isset($_REQUEST["t"])) {
	
		// Topic can be empty if no topics have answers yet
		if ($_REQUEST["t"] != "") {
			if (isTopicApproved($_REQUEST["t"])) {
				// Set topic id to value passed in URL
				$_SESSION['a_selectedApprovedTopic']['id']= $_REQUEST["t"];	
			} else {
				$_REQUEST["err"] = "topic-not-found";
				
				// prevent user from going to page
				unset ($_REQUEST["bb"]);
				unset ($_REQUEST["gn"]);
				unset ($_REQUEST["itv"]);
				unset ($_REQUEST["ga"]);
				unset ($_REQUEST["af"]);
				unset ($_REQUEST["st"]);
			}
		}
		
		if (!isset($_REQUEST["err"])) {
			// Set the selected topic lcv to correspond to the topic id passed in URL
			foreach ($_SESSION['a_approvedTopics'] as $i_count => $a_approvedTopic) {
				if ($a_approvedTopic['id'] == $_SESSION['a_selectedApprovedTopic']['id']){
					$_SESSION['a_selectedApprovedTopic']['lcv'] = $i_count;
				}
			}
		}
	}

	if (isset($_REQUEST["err"])){

		// Set Open Graph meta tags	
		header( "HTTP/1.1 404 Not Found" ); 
		$_SESSION["meta-description"] = "Unfortunately, the page you are looking for does not exist or is no longer available to be displayed";
		$_SESSION["meta-title"] = "Page not found";
		
	} else if (isset($_REQUEST["bb"])){

		// Set Open Graph meta tags	
		$_SESSION["meta-description"] = "An anthology of the very best personal stories that answer questions such as \"What does it feel like to weigh 600 pounds?\" sold in the Kindle and NOOKbook stores";
		$_SESSION["meta-title"] = "Buy the book";
		
	} else if (isset($_REQUEST["gn"])) {
	
		// Set Open Graph meta tags	
		$_SESSION["meta-description"] = "Receive updates on the soon-to-be released book and highlights of the very best personal stories that have been submitted.";
		$_SESSION["meta-title"] = "Get the newsletter";
	
	} else if (isset($_REQUEST["ga"])) {

		// Set Open Graph meta tags	
		$_SESSION["meta-description"] = "Provide insight into what it feels like to ".$_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic'];
		$_SESSION["meta-title"] = "Tell us what it feels like to ".$_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic'];

	} else if (isset($_REQUEST["itv"])) {

		// Set Open Graph meta tags	
		$_SESSION["meta-description"] = "Confirm an interview on the topic of what it feels like to ".$_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic'];
		$_SESSION["meta-title"] = "Confirm interview on topic 'What does it feel like to ".$_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic']."'";

	} else if (isset($_REQUEST["af"])) {
		
		// Set Open Graph meta tags	
		$_SESSION["meta-description"] = "Reach out to those you know that could give insight into what it feels like to ".$_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic'];
		$_SESSION["meta-title"] = "Ask a friend what it feels like to ".$_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic'];

	} else if (isset($_REQUEST["st"])) {
	
		// Set Open Graph meta tags	
		$_SESSION["meta-description"] = "Other popular topics that have been submitted include \"What does it feel like to be an orphan?\" and \"What does it feel like to be rich?\"";
		$_SESSION["meta-title"] = "Suggest a topic";
	}
}

// Display the story submission guidelines, terms of service, copyright, privacy policy or thank you page
if ((isset($_REQUEST["abt"]))||(isset($_REQUEST["csa"]))||(isset($_REQUEST["tos"]))||(isset($_REQUEST["ccp"]))||(isset($_REQUEST["ppo"]))||(isset($_REQUEST["agu"]))||(isset($_REQUEST["thx"]))||(isset($_REQUEST["err"]))) {

	// Set Open Graph meta tags	
	if (isset($_REQUEST["abt"])) {
		$_SESSION["meta-description"] = "A collection of personal stories that answer compelling, yet rarely discussed questions, such as \"What does it feel like to lose your vision?\"";
		$_SESSION["meta-title"] = "About";

	} else if (isset($_REQUEST["csa"])) {
		$_SESSION["meta-description"] = "Agreement for submitting answers, topics and other content to Too Polite To Ask, as well as an explanation of how answers will be chosen for the book and how the authors will be compensated";
		$_SESSION["meta-title"] = "User Content Submission Agreement";

	} else if (isset($_REQUEST["tos"])) {
		$_SESSION["meta-description"] = "Agreement that govern user access to and use of the services, websites, and applications offered by Too Polite To Ask";
		$_SESSION["meta-title"] = "Terms of Service";

	} else if (isset($_REQUEST["ccp"])) {
		$_SESSION["meta-description"] = "Policy that explains procedures undertaken by Too Polite To Ask to respond to notices of alleged copyright infringement from copyright owners and terminating the accounts of repeat infringers";
		$_SESSION["meta-title"] = "Copyright Compliance Policy";

	} else if (isset($_REQUEST["ppo"])) {
		$_SESSION["meta-description"] = "Policy that explains the collection and use of personal information by Too Polite To Ask";
		$_SESSION["meta-title"] = "Privacy Policy";

	} else if (isset($_REQUEST["agu"])) {
		$_SESSION["meta-description"] = "Guidelines that explains the types of answers that should and should not be submitted to Too Polite To Ask";
		$_SESSION["meta-title"] = "Answer Guidelines";
	} else if (isset($_REQUEST["err"])) {
		header( "HTTP/1.1 404 Not Found" ); 
		$_SESSION["meta-description"] = "Unfortunately, the page you are looking for does not exist or is no longer available to be displayed";
		$_SESSION["meta-title"] = "Page not found";
	}

	$b_overlay = TRUE;
}

//if (isset($_REQUEST["share"])) { $_SESSION["fbSharing"] = !$_SESSION["fbSharing"]; }
?>